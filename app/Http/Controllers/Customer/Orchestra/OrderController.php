<?php

namespace App\Http\Controllers\Customer\Orchestra;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;   
use App\Classes\Modules;
use App\Classes\ProformaInvoice;
use Response;
use App\HangTagOrders;
use App\ProfomaInvoice as PI;
use App\ProfomaInvoiceItemDetails;
use App\HangTagOrdersQty;
use App\SpecialTagOrders;
use App\SpecialTagOrdersQty;
use App\WashLabelOrders;
use App\WashLabelOrdersQty;
use App\WashCareComposition;
use App\SolidCartonStickerOrders;
use App\SolidCartonStickerOrdersQty;
use App\SolidPolyStickerOrders;
use App\SolidPolyStickerOrdersQty;
use App\StandardCartonStickers;
use App\StandardPolyStickers;
use App\StandardCartonStickersQty;
use App\StandardPolyStickersQty;
use App\HeatTransferOrders;
use App\HeatTransferOrdersQty;
use App\SizeLabelOrders;
use App\SizeLabelOrdersQty;
use App\MainLabelOrders;
use App\MainLabelOrdersQty;
use View;
use DB;
use Auth;
use App\Classes\ERPOrderStatusCheck;
use App\Classes\ErpOrders;
use Carbon\Carbon;
use App\OrchestraParcelDetails;
use App\OrchestraParcelQuantityDetails;
use Illuminate\Support\Facades\Redis;
use App\Classes\EditErpOrders;
use App\ErpOrders as Erp;
use App\ErpOrderDetails;


class OrderController extends Controller
{
    private $modules_permission;
    private $edit_erp_obj;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('customer_auth');
        $this->edit_erp_obj    = new EditErpOrders();
		$this->current_date = Carbon::now('Asia/Kolkata')->toDateTimeString();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $module_var = new Modules();
        $is_allowed = $module_var->permittedModules();
        if($is_allowed)
        {
            $top_mod = "Order Management";
            $get_main_module = $module_var->getMainModules();
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module) {
                return $view->with(['modules' => $get_main_module]);
            });
            $get_sub_module = $module_var->getSubModules(9);
 	    $orders = DB::table('orchestra_parcel_details')->select('*')->get();
            return view('Admin/Orchestra/order-management',['orders' => $orders,'sub_modules' => $get_sub_module]);
        }
        else
        {
             return redirect('/admin/dashboard');
        }
        
    } 
    
    // public function labelOrders()
    // {
    //         $orders = DB::table('orchestra_item_details')->select('id','UVC','Collection as Season','SupplierName','Description','Theme','Style_Name','Disney','Quantity')->groupBy('UVC')->get();
    //         echo json_encode($orders);
    // } 
    // public function backLabelOrders()
    // {
    //         $data['orders'] = DB::table('orchestra_item_details')->select('id','UVC','Collection as Season','SupplierName','Description','Theme','Style_Name','Disney','Quantity')->groupBy('UVC')->get();
    //         $data['buyer'] =DB::table('buyers')->select('*')->where('is_active','=',1)->get();
    //         echo json_encode($data);
    // } 
    // public function backStickerOrders()
    // {
    //         $data['orders'] = DB::table('orchestra_parcel_details')->select('id','UVC','BuyerPONo','SupplierName','Theme','Quantity','IsSTD as Type')->get();
    //         $data['buyer'] =DB::table('buyers')->select('*')->where('is_active','=',1)->get();
    //         echo json_encode($data);
    // }
    public function Orders()
    {
		
		 $UVC_TCO_array1 = [];
         // check for po data missing for SKU Code(In price CSV)
         $parcel_qty_details = OrchestraParcelQuantityDetails::select(DB::raw('distinct on (orchestra_parcel_details."UVC_TCO") orchestra_parcel_details."UVC_TCO"'))->join('orchestra_parcel_details', 'orchestra_parcel_quantity_details.UVC_TCO', 'orchestra_parcel_details.UVC_TCO')->groupBy('orchestra_parcel_details.UVC_TCO')->get()->toArray();

         foreach ($parcel_qty_details as $key => $value) {
           array_push($UVC_TCO_array1, $value['UVC_TCO']);
         }
		
		
		 $company_id = Auth::guard('web_customer')->user()->company_id;
		
		
		 $customers = DB::table('customers')->select('*')->where('id',$company_id)->get();
		
		 $data['sticker_orders'] = DB::table('orchestra_parcel_details')->select(DB::raw('distinct on ("UVC","BuyerPONo") "UVC"'),'id',DB::raw("'-' as \"Season\""),'BuyerPONo','SupplierName','NomApple as Style_Name','Quantity','IsSTD as Type',DB::raw("'Stickers' as \"OrderType\""),'sticker_order_status as Order Status',DB::raw("'-' as \"Disney\""))->whereNotIn('UVC_TCO', $UVC_TCO_array1)->where('orchestra_parcel_details.FactoryCode', $customers{0}->factory_code)->groupBy('id','UVC')->get();

        $data['label_orders'] = DB::table('orchestra_parcel_details')->select(DB::raw('distinct on ("UVC") "UVC"'),'id','Season_Code as Season',DB::raw("'-' as \"BuyerPONo\""),'SupplierName','Theme as Description','NomApple as Style_Name','Licence_Code as Disney',DB::raw("'-' as \"Type\""),'Quantity',DB::raw("'Labels' as \"OrderType\""),'order_status as Order Status')->whereNotIn('UVC_TCO', $UVC_TCO_array1)->where('orchestra_parcel_details.FactoryCode', $customers{0}->factory_code)->groupBy('id','UVC')->get();
        
        echo json_encode($data);
    } 

    /**
     * Customer Orchestra Order status page
     */
    public function orchestraOrderStatus()
    {
            $customer_id = Auth::guard('web_customer')->user()->company_id;
            $ddmmyy="DD-MM-YYYY";
			$actions='-';
            $data['orchestra_hang_tag_orders'] = DB::table('orchestra_hang_tag_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_hang_tag_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_hang_tag_orders.template_front_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_hang_tag_orders.status','!=','Order Cancelled')->select('orchestra_hang_tag_orders.color_code','orchestra_parcel_details.UVC',DB::raw("orchestra_hang_tag_orders.season_code as \"Season\""),DB::raw("'-' as \"BuyerPONo\""),DB::raw("'Hang Tag' as \"ArtworkType\""),DB::raw("'Hang Tag' as \"TemplateName\""),'orchestra_item_master.item_name as Reference No','customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_hang_tag_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_hang_tag_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_hang_tag_orders.status as Artwork Status','orchestra_hang_tag_orders.pi_status as PI Status','orchestra_hang_tag_orders.po_status as PO Status','orchestra_hang_tag_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_hang_tag_orders' as \"tableName\""),'orchestra_hang_tag_orders.id  as tableID','orchestra_hang_tag_orders.po_file','customers.pi_required','customers.po_required','orchestra_hang_tag_orders.template_front_id as template_id','orchestra_hang_tag_orders.order_flag','customers.delivery_day_count','orchestra_hang_tag_orders.is_order_count_empty')->where('customers.id', $customer_id)->groupBy('orchestra_parcel_details.UVC','orchestra_parcel_details.SupplierName','orchestra_hang_tag_orders.created_at','orchestra_hang_tag_orders.delivery_date','orchestra_hang_tag_orders.status','orchestra_hang_tag_orders.pi_status','orchestra_hang_tag_orders.status','orchestra_hang_tag_orders.po_status','orchestra_hang_tag_orders.po_number','customers.pi_required','customers.id','orchestra_hang_tag_orders.id','orchestra_item_master.item_name')->orderBy('orchestra_hang_tag_orders.id','asc')->get();
		
            $data['orchestra_special_tag_orders'] = DB::table('orchestra_special_tag_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_special_tag_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_special_tag_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_special_tag_orders.status','!=','Order Cancelled')->select('orchestra_special_tag_orders.color_code','orchestra_parcel_details.UVC',DB::raw("orchestra_special_tag_orders.season_code as \"Season\""),DB::raw("'-' as \"BuyerPONo\""),DB::raw("'Special Tag' as \"ArtworkType\""),DB::raw("'Special Tag' as \"TemplateName\""),'orchestra_item_master.item_name as Reference No','customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_special_tag_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_special_tag_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_special_tag_orders.status as Artwork Status','orchestra_special_tag_orders.pi_status as PI Status','orchestra_special_tag_orders.po_status as PO Status','orchestra_special_tag_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_special_tag_orders' as \"tableName\""),'orchestra_special_tag_orders.id  as tableID','orchestra_special_tag_orders.po_file','customers.pi_required','customers.po_required','orchestra_special_tag_orders.template_id','orchestra_special_tag_orders.order_flag','customers.delivery_day_count','orchestra_special_tag_orders.is_order_count_empty')->where('customers.id', $customer_id)->groupBy('orchestra_parcel_details.UVC','orchestra_parcel_details.SupplierName','orchestra_special_tag_orders.created_at','orchestra_special_tag_orders.delivery_date','orchestra_special_tag_orders.status','orchestra_special_tag_orders.pi_status','orchestra_special_tag_orders.status','orchestra_special_tag_orders.po_status','orchestra_special_tag_orders.po_number','customers.pi_required','customers.id','orchestra_special_tag_orders.id','orchestra_item_master.item_name')->orderBy('orchestra_special_tag_orders.id','asc')->get();
     
               
            $data['orchestra_main_label_orders'] = DB::table('orchestra_main_label_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_main_label_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_main_label_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_main_label_orders.status','!=','Order Cancelled')->select('orchestra_main_label_orders.color_code','orchestra_parcel_details.UVC',DB::raw("orchestra_main_label_orders.season_code as \"Season\""),DB::raw("'-' as \"BuyerPONo\""),DB::raw("'Main Label' as \"ArtworkType\""),DB::raw("'Main Label' as \"TemplateName\""),'orchestra_item_master.item_name as Reference No','customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_main_label_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_main_label_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_main_label_orders.status as Artwork Status','orchestra_main_label_orders.pi_status as PI Status','orchestra_main_label_orders.po_status as PO Status','orchestra_main_label_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_main_label_orders' as \"tableName\""),'orchestra_main_label_orders.id  as tableID','orchestra_main_label_orders.po_file','customers.pi_required','customers.po_required','orchestra_main_label_orders.template_id','orchestra_main_label_orders.order_flag','customers.delivery_day_count','orchestra_main_label_orders.is_order_count_empty')->where('customers.id', $customer_id)->groupBy('orchestra_parcel_details.UVC','orchestra_parcel_details.SupplierName','orchestra_main_label_orders.created_at','orchestra_main_label_orders.delivery_date','orchestra_main_label_orders.status','orchestra_main_label_orders.pi_status','orchestra_main_label_orders.status','orchestra_main_label_orders.po_status','orchestra_main_label_orders.po_number','customers.pi_required','customers.id','orchestra_main_label_orders.id','orchestra_item_master.item_name')->orderBy('orchestra_main_label_orders.id','asc')->get();
		
			$data['orchestra_size_label_orders'] = DB::table('orchestra_size_label_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_size_label_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_size_label_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_size_label_orders.status','!=','Order Cancelled')->select('orchestra_size_label_orders.color_code','orchestra_parcel_details.UVC',DB::raw("orchestra_size_label_orders.season_code as \"Season\""),DB::raw("'-' as \"BuyerPONo\""),DB::raw("'Size Label' as \"ArtworkType\""),DB::raw("'Size Label' as \"TemplateName\""),'orchestra_item_master.item_name as Reference No','customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_size_label_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_size_label_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_size_label_orders.status as Artwork Status','orchestra_size_label_orders.pi_status as PI Status','orchestra_size_label_orders.po_status as PO Status','orchestra_size_label_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_size_label_orders' as \"tableName\""),'orchestra_size_label_orders.id  as tableID','orchestra_size_label_orders.po_file','customers.pi_required','customers.po_required','orchestra_size_label_orders.template_id','orchestra_size_label_orders.order_flag','customers.delivery_day_count','orchestra_size_label_orders.is_order_count_empty')->where('customers.id', $customer_id)->groupBy('orchestra_parcel_details.UVC','orchestra_parcel_details.SupplierName','orchestra_size_label_orders.created_at','orchestra_size_label_orders.delivery_date','orchestra_size_label_orders.status','orchestra_size_label_orders.pi_status','orchestra_size_label_orders.status','orchestra_size_label_orders.po_status','orchestra_size_label_orders.po_number','customers.pi_required','customers.id','orchestra_size_label_orders.id','orchestra_item_master.item_name')->orderBy('orchestra_size_label_orders.id','asc')->get();
		
			$data['orchestra_heat_transfer_orders'] = DB::table('orchestra_heat_transfer_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_heat_transfer_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_heat_transfer_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_heat_transfer_orders.status','!=','Order Cancelled')->select('orchestra_heat_transfer_orders.color_code','orchestra_parcel_details.UVC',DB::raw("orchestra_heat_transfer_orders.season_code as \"Season\""),DB::raw("'-' as \"BuyerPONo\""),DB::raw("'Heat Transfer' as \"ArtworkType\""),DB::raw("'Heat Transfer' as \"TemplateName\""),'orchestra_item_master.item_name as Reference No','customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_heat_transfer_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_heat_transfer_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_heat_transfer_orders.status as Artwork Status','orchestra_heat_transfer_orders.pi_status as PI Status','orchestra_heat_transfer_orders.po_status as PO Status','orchestra_heat_transfer_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_heat_transfer_orders' as \"tableName\""),'orchestra_heat_transfer_orders.id  as tableID','orchestra_heat_transfer_orders.po_file','customers.pi_required','customers.po_required','orchestra_heat_transfer_orders.template_id','orchestra_heat_transfer_orders.order_flag','customers.delivery_day_count','orchestra_heat_transfer_orders.is_order_count_empty')->where('customers.id', $customer_id)->groupBy('orchestra_parcel_details.UVC','orchestra_parcel_details.SupplierName','orchestra_heat_transfer_orders.created_at','orchestra_heat_transfer_orders.delivery_date','orchestra_heat_transfer_orders.status','orchestra_heat_transfer_orders.pi_status','orchestra_heat_transfer_orders.status','orchestra_heat_transfer_orders.po_status','orchestra_heat_transfer_orders.po_number','customers.pi_required','customers.id','orchestra_heat_transfer_orders.id','orchestra_item_master.item_name')->orderBy('orchestra_heat_transfer_orders.id','asc')->get();

            $data['orchestra_wash_care_label_orders'] = DB::table('orchestra_wash_care_label_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_wash_care_label_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_wash_care_label_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_wash_care_label_orders.status','!=','Order Cancelled')->select('orchestra_wash_care_label_orders.color_code','orchestra_parcel_details.UVC',DB::raw("orchestra_wash_care_label_orders.season_code as \"Season\""),DB::raw("orchestra_wash_care_label_orders.po as \"BuyerPONo\""),DB::raw("'WashCareLabel' as \"ArtworkType\""),'orchestra_wash_care_label_orders.template_name as TemplateName','orchestra_item_master.item_name as Reference No','customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_wash_care_label_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_wash_care_label_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_wash_care_label_orders.status as Artwork Status','orchestra_wash_care_label_orders.pi_status as PI Status','orchestra_wash_care_label_orders.po_status as PO Status','orchestra_wash_care_label_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_wash_care_label_orders' as \"tableName\""),'orchestra_wash_care_label_orders.id  as tableID','orchestra_wash_care_label_orders.po_file','customers.pi_required','customers.po_required','orchestra_wash_care_label_orders.template_id','orchestra_wash_care_label_orders.order_flag','customers.delivery_day_count','orchestra_wash_care_label_orders.is_order_count_empty')->where('customers.id', $customer_id)->groupBy('orchestra_parcel_details.UVC','orchestra_parcel_details.SupplierName','orchestra_wash_care_label_orders.created_at','orchestra_wash_care_label_orders.delivery_date','orchestra_wash_care_label_orders.status','orchestra_wash_care_label_orders.pi_status','orchestra_wash_care_label_orders.status','orchestra_wash_care_label_orders.po_status','orchestra_wash_care_label_orders.po_number','customers.pi_required','customers.id','orchestra_wash_care_label_orders.id','orchestra_item_master.item_name')->orderBy('orchestra_wash_care_label_orders.id','asc')->get();

            $data['orchestra_solid_carton_sticker_orders'] = DB::table('orchestra_solid_carton_sticker_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_solid_carton_sticker_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_solid_carton_sticker_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_solid_carton_sticker_orders.status','!=','Order Cancelled')->select(DB::raw('distinct on (orchestra_solid_carton_sticker_orders.uvc,orchestra_solid_carton_sticker_orders.po) "UVC"'),DB::raw("orchestra_solid_carton_sticker_orders.season_code as \"Season\""),DB::raw("orchestra_solid_carton_sticker_orders.po as \"BuyerPONo\""),DB::raw("'Solid Carton Stickers' as \"ArtworkType\""),DB::raw("'Solid Carton Stickers' as \"TemplateName\""),DB::raw("'-' as \"Reference No\""),'customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_solid_carton_sticker_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_solid_carton_sticker_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_solid_carton_sticker_orders.color_code','orchestra_solid_carton_sticker_orders.status as Artwork Status','orchestra_solid_carton_sticker_orders.pi_status as PI Status','orchestra_solid_carton_sticker_orders.po_status as PO Status','orchestra_solid_carton_sticker_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_solid_carton_sticker_orders' as \"tableName\""),'orchestra_solid_carton_sticker_orders.id  as tableID','orchestra_solid_carton_sticker_orders.po_file','customers.pi_required','customers.po_required','orchestra_solid_carton_sticker_orders.template_id','orchestra_solid_carton_sticker_orders.order_flag','customers.delivery_day_count','orchestra_solid_carton_sticker_orders.is_order_count_empty')->where('customers.id', $customer_id)->where('orchestra_parcel_details.IsSTD',0)->groupBy('orchestra_solid_carton_sticker_orders.uvc','orchestra_parcel_details.UVC','orchestra_solid_carton_sticker_orders.po','orchestra_parcel_details.SupplierName','orchestra_solid_carton_sticker_orders.created_at','orchestra_solid_carton_sticker_orders.delivery_date','orchestra_solid_carton_sticker_orders.status','orchestra_solid_carton_sticker_orders.pi_status','orchestra_solid_carton_sticker_orders.status','orchestra_solid_carton_sticker_orders.po_status','orchestra_solid_carton_sticker_orders.po_number','customers.pi_required','customers.id','orchestra_solid_carton_sticker_orders.id')->get();
              
            $data['orchestra_solid_poly_bag_sticker_orders'] = DB::table('orchestra_solid_poly_bag_sticker_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_solid_poly_bag_sticker_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_solid_poly_bag_sticker_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_solid_poly_bag_sticker_orders.status','!=','Order Cancelled')->select(DB::raw('distinct on (orchestra_solid_poly_bag_sticker_orders.uvc,orchestra_solid_poly_bag_sticker_orders.po) "UVC"'),DB::raw("orchestra_solid_poly_bag_sticker_orders.season_code as \"Season\""),DB::raw("orchestra_solid_poly_bag_sticker_orders.po as \"BuyerPONo\""),DB::raw("'Solid Poly Bag Stickers' as \"ArtworkType\""),DB::raw("'Solid Poly Bag Stickers' as \"TemplateName\""),DB::raw("'-' as \"Reference No\""),'customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_solid_poly_bag_sticker_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_solid_poly_bag_sticker_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_solid_poly_bag_sticker_orders.status as Artwork Status','orchestra_solid_poly_bag_sticker_orders.pi_status as PI Status','orchestra_solid_poly_bag_sticker_orders.po_status as PO Status','orchestra_solid_poly_bag_sticker_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_solid_poly_bag_sticker_orders' as \"tableName\""),'orchestra_solid_poly_bag_sticker_orders.color_code','orchestra_solid_poly_bag_sticker_orders.id  as tableID','orchestra_solid_poly_bag_sticker_orders.po_file','customers.pi_required','customers.po_required','orchestra_solid_poly_bag_sticker_orders.template_id','orchestra_solid_poly_bag_sticker_orders.order_flag','customers.delivery_day_count','orchestra_solid_poly_bag_sticker_orders.is_order_count_empty')->where('customers.id', $customer_id)->where('orchestra_parcel_details.IsSTD',0)->groupBy('orchestra_solid_poly_bag_sticker_orders.uvc','orchestra_parcel_details.UVC','orchestra_solid_poly_bag_sticker_orders.po','orchestra_parcel_details.SupplierName','orchestra_solid_poly_bag_sticker_orders.created_at','orchestra_solid_poly_bag_sticker_orders.delivery_date','orchestra_solid_poly_bag_sticker_orders.status','orchestra_solid_poly_bag_sticker_orders.pi_status','orchestra_solid_poly_bag_sticker_orders.po_status','orchestra_solid_poly_bag_sticker_orders.po_number','orchestra_solid_poly_bag_sticker_orders.id','customers.pi_required','customers.id')->get();
    
            $data['orchestra_standard_carton_sticker_orders'] = DB::table('orchestra_standard_carton_sticker_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_standard_carton_sticker_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_standard_carton_sticker_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_standard_carton_sticker_orders.status','!=','Order Cancelled')->select(DB::raw('distinct on (orchestra_standard_carton_sticker_orders.uvc,orchestra_standard_carton_sticker_orders.po) "UVC"'),DB::raw("orchestra_standard_carton_sticker_orders.season_code as \"Season\""),DB::raw("orchestra_standard_carton_sticker_orders.po as \"BuyerPONo\""),DB::raw("'Standard Carton Stickers' as \"ArtworkType\""),DB::raw("'Standard Carton Stickers' as \"TemplateName\""),DB::raw("'-' as \"Reference No\""),'customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_standard_carton_sticker_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_standard_carton_sticker_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_standard_carton_sticker_orders.status as Artwork Status','orchestra_standard_carton_sticker_orders.pi_status as PI Status','orchestra_standard_carton_sticker_orders.po_status as PO Status','orchestra_standard_carton_sticker_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_standard_carton_sticker_orders' as \"tableName\""),'orchestra_standard_carton_sticker_orders.color_code','orchestra_standard_carton_sticker_orders.id  as tableID','orchestra_standard_carton_sticker_orders.po_file','customers.pi_required','customers.po_required','orchestra_standard_carton_sticker_orders.template_id','orchestra_standard_carton_sticker_orders.order_flag','customers.delivery_day_count','orchestra_standard_carton_sticker_orders.is_order_count_empty')->where('customers.id', $customer_id)->where('orchestra_parcel_details.IsSTD',1)->groupBy('orchestra_standard_carton_sticker_orders.uvc','orchestra_parcel_details.UVC','orchestra_standard_carton_sticker_orders.po','orchestra_parcel_details.SupplierName','orchestra_standard_carton_sticker_orders.created_at','orchestra_standard_carton_sticker_orders.delivery_date','orchestra_standard_carton_sticker_orders.status','orchestra_standard_carton_sticker_orders.pi_status','orchestra_standard_carton_sticker_orders.po_status','orchestra_standard_carton_sticker_orders.po_number','orchestra_standard_carton_sticker_orders.id','customers.pi_required','customers.id')->get();

            $data['orchestra_standard_poly_bag_sticker_orders'] = DB::table('orchestra_standard_poly_bag_sticker_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_standard_poly_bag_sticker_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_standard_poly_bag_sticker_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('orchestra_standard_poly_bag_sticker_orders.status','!=','Order Cancelled')->select(DB::raw('distinct on (orchestra_standard_poly_bag_sticker_orders.uvc,orchestra_standard_poly_bag_sticker_orders.po) "UVC"'),DB::raw("orchestra_standard_poly_bag_sticker_orders.season_code as \"Season\""),DB::raw("orchestra_standard_poly_bag_sticker_orders.po as \"BuyerPONo\""),DB::raw("'Standard Poly Bag Stickers' as \"ArtworkType\""),DB::raw("'Standard Poly Bag Stickers' as \"TemplateName\""),DB::raw("'-' as \"Reference No\""),'customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_standard_poly_bag_sticker_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_standard_poly_bag_sticker_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_standard_poly_bag_sticker_orders.status as Artwork Status','orchestra_standard_poly_bag_sticker_orders.pi_status as PI Status','orchestra_standard_poly_bag_sticker_orders.po_status as PO Status','orchestra_standard_poly_bag_sticker_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_standard_poly_bag_sticker_orders' as \"tableName\""),'orchestra_standard_poly_bag_sticker_orders.color_code','orchestra_standard_poly_bag_sticker_orders.id  as tableID','orchestra_standard_poly_bag_sticker_orders.po_file','customers.pi_required','customers.po_required','orchestra_standard_poly_bag_sticker_orders.template_id','orchestra_standard_poly_bag_sticker_orders.order_flag','customers.delivery_day_count','orchestra_standard_poly_bag_sticker_orders.is_order_count_empty')->where('customers.id', $customer_id)->where('orchestra_parcel_details.IsSTD',1)->groupBy('orchestra_standard_poly_bag_sticker_orders.uvc','orchestra_parcel_details.UVC','orchestra_standard_poly_bag_sticker_orders.po','orchestra_parcel_details.SupplierName','orchestra_standard_poly_bag_sticker_orders.created_at','orchestra_standard_poly_bag_sticker_orders.delivery_date','orchestra_standard_poly_bag_sticker_orders.status','orchestra_standard_poly_bag_sticker_orders.pi_status','orchestra_standard_poly_bag_sticker_orders.po_status','orchestra_standard_poly_bag_sticker_orders.po_number','orchestra_standard_poly_bag_sticker_orders.id','customers.pi_required','customers.id')->get();
            
            /***********REDIS***************/
            /** set order-status data into redis */ 
            Redis::set('customer-order-status:orchestra-'.$customer_id, json_encode($data));

            /***********REDIS***************/
            /** get order-status data from redis */
            $data = Redis::get('customer-order-status:orchestra-'.$customer_id);
            
            echo  $data;
    }
  /**
     * Show the label orders details.
     *
     * @return Json Value
     */

    public function labelOrderDetails(Request $request)
    {
            $uvc=$request->uvc;
            $data['order_details'] = DB::table('orchestra_parcel_details')
                                        ->select('orchestra_parcel_details.*','orchestra_parcel_details.Size_Range_Label as Age','orchestra_parcel_details.Start_Print_Date	 as HangTag_Print_Date','orchestra_size_chart.cm',DB::raw("sum(orchestra_parcel_details.\"Quantity\") as \"Sum\""), DB::raw("'null' as \"PrixEurope\""), DB::raw("'null' as \"PrixClubEurope\""), DB::raw("'null' as \"Prix_Vente_Arabie_Saoudite\""), DB::raw("'null' as \"Prix_Club_Arabie_Saoudite\""), DB::raw("'null' as \"Prix_Vente_Canada\""), DB::raw("'null' as \"Prix_Club_Canada\""), DB::raw("'null' as \"Prix_Vente_Suisse\""), DB::raw("'null' as \"Prix_Club_Suisse\""), DB::raw("'null' as \"Prix_Vente_Maroc\""), DB::raw("'null' as \"Prix_Club_Maroc\""), DB::raw("'null' as \"Prix_Vente_Ile_Maurice\""), DB::raw("'null' as \"Prix_Club_Ile_Maurice\""), DB::raw("'null' as \"Prix_Vente_ALGERIE\""), DB::raw("'null' as \"Prix_Club_ALGERIE\""), DB::raw("'null' as \"Prix_Vente_USA\""), DB::raw("'null' as \"Prix_Club_USA\""))
                                        ->leftJoin('orchestra_size_chart','orchestra_parcel_details.Size_Label','=','orchestra_size_chart.age')
                                        ->where('orchestra_parcel_details.UVC',$uvc)
                                        ->groupBy('orchestra_parcel_details.id','Size_Label','orchestra_size_chart.cm')
                                        ->orderBy('orchestra_parcel_details.id','asc')
                                        ->get();
		
		//$data['tu'] = DB::table('orchestra_tu_meta_data')->select('orchestra_tu_meta_data.*')->where('orchestra_tu_meta_data.meta_data',$data['order_details']{0}->Size)->get();
		
		
		$data['color_wcl'] = DB::table('orchestra_parcel_details')->select('orchestra_parcel_details.Color_Code')->where('orchestra_parcel_details.UVC',$uvc)->groupBy('orchestra_parcel_details.Color_Code') ->orderBy('orchestra_parcel_details.Color_Code','asc')->get();
		
		
//			$sku_code = DB::table('orchestra_parcel_details')
//                                        ->select('orchestra_parcel_details.SKU_Code')
//                                        ->where('orchestra_parcel_details.UVC',$uvc)
//                                        ->groupBy('orchestra_parcel_details.SKU_Code')
//                                        ->get();
//			$data['sku'] =array();
//
//			foreach($sku_code as $val)
//			{
//			  $str = explode('-',$val->SKU_Code);
//				
//			  if (in_array($str[1], $data['sku'])) 
//			  { 
//			  	
//			  }
//				else
//				{
//					array_push($data['sku'],$str[1]);
//				}
//			}
		

            // get price details for po data
            foreach($data['order_details'] as $key => $value)
            {
                $price_data = DB::table('orchestra_parcel_quantity_details')
                                ->select('orchestra_parcel_quantity_details.*')
                                ->where('orchestra_parcel_quantity_details.UVC_TCO', $value->SKU_Code)
                                ->get();
                
                foreach ($price_data as $key1 => $value2) 
                {
                    $currency = $value2->Currency;

                    switch ($currency) {
                        case "EUR":
                            $value->PrixEurope  = $value2->Price;
                            $value->PrixClubEurope = $value2->Club_Price;
                            break;
                        case "SAR":
                            $value->Prix_Vente_Arabie_Saoudite  = $value2->Price;
                            $value->Prix_Club_Arabie_Saoudite = $value2->Club_Price;
                            break;
                        case "CAD":
                            $value->Prix_Vente_Canada  = $value2->Price;
                            $value->Prix_Club_Canada = $value2->Club_Price;
                            break;
                        case "CHF":
                            $value->Prix_Vente_Suisse  = $value2->Price;
                            $value->Prix_Club_Suisse = $value2->Club_Price;
                            break;
                        case "MAD":
                            $value->Prix_Vente_Maroc  = $value2->Price;
                            $value->Prix_Club_Maroc = $value2->Club_Price;
                            break;
                        case "MUR":
                            $value->Prix_Vente_Ile_Maurice  = $value2->Price;
                            $value->Prix_Club_Ile_Maurice = $value2->Club_Price;
                            break;
                        case "DZD":
                            $value->Prix_Vente_ALGERIE  = $value2->Price;
                            $value->Prix_Club_ALGERIE = $value2->Club_Price;
                            break;
                        case "USD":
                            $value->Prix_Vente_USA  = $value2->Price;
                            $value->Prix_Club_USA = $value2->Club_Price;
                            break;
                        default:
                            echo json_encode("Invalid Currency");
                    }
                }
            }

            $data['barcode'] = DB::table('orchestra_parcel_details')->select('*')->where('UVC',$uvc)->groupBy('id','BuyerPONo')->orderBy('orchestra_parcel_details.BuyerPONo','asc')->get();
        
           $total_qty = DB::table('orchestra_parcel_details')->select('*',DB::raw("sum(\"Quantity\") as total_qty"))->where('UVC',$uvc)->groupBy('id','UVC')->get();
            $data['total_qty']=$total_qty{0}->total_qty;
            // Licence_Code = DI for disney
            if($total_qty{0}->Licence_Code === 'DI')
            {
                $orchestra_template = DB::table('orchestra_template')->select('*')->where('item_id','5')->get();
                $data['orchestra_template']=$orchestra_template{0}->template_file;
                $data['orchestra_template_id']=5;
                
                $data['orchestra_wash_template_front']="/storage/app/template/Orchestra/WCL/sticker1-disney.svg";
                $data['orchestra_wash_template_back']="/storage/app/template/Orchestra/WCL/sticker2.svg";
                $data['orchestra_wash_template_middle']="/storage/app/template/Orchestra/WCL/sticker3.svg";
				
                $data['orchestra_wash_template_id']=5;
				
				$factory_code = $data['order_details']{0}->FactoryCode;
				$customer_id = DB::table('customers')->select('*')->where('factory_code',$factory_code)->get();
				
				$customer_active = DB::table('customers')->select('*')->where('factory_code',$factory_code)->where('is_active',1)->get();
				
				if(count($customer_active) > 0)
				{
					$data['customer_active'] = "active";
				}
				else
				{
					$data['customer_active'] = "inactive";
				}

				if(count($customer_id) > 0)
				{
					$carton_moq = DB::table('price_taxation')->select('*')->where('customer_id',$customer_id{0}->id)->where('buyer_id',1)->where('template_id',5)->get();

					if(count($carton_moq) > 0)
					{
						$data['wash_moq']=$carton_moq{0}->moq;
					}
					else
					{
						$data['wash_moq']=1;
					}


				}
				else
				{
					$data['wash_moq']=1;
				}
				
				
                
                $orchestra_template_front = DB::table('orchestra_template')->select('*')->where('item_id','5')->where('disney',1)->get();
                $data['orchestra_template_front']=$orchestra_template_front;
				
				 $treeviewData= DB::table("orchestra_item_master")->select('id',DB::raw("(CASE WHEN ref_no = '' THEN item_name ELSE CONCAT(item_name,' - ',ref_no) END) as text"),'parent_id')->orWhere('id','103')->orWhere('tag_name','disney')->orderBy('item_name', 'asc')->get()->toArray();
            if($treeviewData){
                        $childs = array();
                        foreach($treeviewData as &$item) 
                        {
                        $childs[$item->parent_id][] = &$item;
                        unset($item);
                        }
                        foreach($treeviewData as &$item) 
                        {
                            if (isset($childs[$item->id]))
                            {
                                $item->children = $childs[$item->id];
                                unset($item);
                            }
                        }
                        $tree = $childs[0];
                        $data['treefinalData']=$tree;

               } 
            }
            else
            {
                $orchestra_template = DB::table('orchestra_template')->select('*')->where('item_id','4')->get();
                $data['orchestra_template']=$orchestra_template{0}->template_file;
                $data['orchestra_template_id']=4;
                
                $data['orchestra_wash_template_front']="/storage/app/template/Orchestra/WCL/sticker1.svg";
                $data['orchestra_wash_template_back']="/storage/app/template/Orchestra/WCL/sticker2.svg";
                $data['orchestra_wash_template_middle']="/storage/app/template/Orchestra/WCL/sticker3.svg";
                $data['orchestra_wash_template_id']=4;
				
				$factory_code = $data['order_details']{0}->FactoryCode;
				$customer_id = DB::table('customers')->select('*')->where('factory_code',$factory_code)->get();
				
				$customer_active = DB::table('customers')->select('*')->where('factory_code',$factory_code)->where('is_active',1)->get();
				
				if(count($customer_active) > 0)
				{
					$data['customer_active'] = "active";
				}
				else
				{
					$data['customer_active'] = "inactive";
				}
				

				if(count($customer_id) > 0)
				{
					$carton_moq = DB::table('price_taxation')->select('*')->where('customer_id',$customer_id{0}->id)->where('buyer_id',1)->where('template_id',4)->get();

					if(count($carton_moq) > 0)
					{
						$data['wash_moq']=$carton_moq{0}->moq;
					}
					else
					{
						$data['wash_moq']=1;
					}


				}
				else
				{
					$data['wash_moq']=1;
				}
                
                $orchestra_template_front = DB::table('orchestra_template')->select('*')->where('item_id','4')->where('disney', 0)->get();
                $data['orchestra_template_front']=$orchestra_template_front;
				
				
				 $treeviewData= DB::table("orchestra_item_master")->select('id',DB::raw("(CASE WHEN ref_no = '' THEN item_name ELSE CONCAT(item_name,' - ',ref_no) END) as text"),'parent_id')->orWhere('id','103')->orWhere('tag_name','hang')->orWhere('id','104')->orWhere('id','105')->orderBy('item_name', 'asc')->get()->toArray();
            if($treeviewData){
                        $childs = array();
                        foreach($treeviewData as &$item) 
                        {
                        $childs[$item->parent_id][] = &$item;
                        unset($item);
                        }
                        foreach($treeviewData as &$item) 
                        {
                            if (isset($childs[$item->id]))
                            {
                                $item->children = $childs[$item->id];
                                unset($item);
                            }
                        }
                        $tree = $childs[0];
                        $data['treefinalData']=$tree;

               } 
            }
        
         // $treeviewData= DB::table("orchestra_item_master")->select('id','item_name as text','parent_id')->orWhere('id','6')->orWhere('parent_id','6')->orWhere('id','1')->get()->toArray();
         
        
        $treeviewspecial= DB::table("orchestra_item_master")->select('id',DB::raw("(CASE WHEN ref_no = '' THEN item_name ELSE CONCAT(item_name,' - ',ref_no) END) as text"),'parent_id')->orWhere('id','103')->orWhere('id','104')->orWhere('id','105')->orWhere('tag_name','special')->orderBy('item_name', 'asc')->get()->toArray();
            if($treeviewspecial){
                        $childs = array();
                        foreach($treeviewspecial as &$item) 
                        {
                        $childs[$item->parent_id][] = &$item;
                        unset($item);
                        }
                        foreach($treeviewspecial as &$item) 
                        {
                            if (isset($childs[$item->id]))
                            {
                                $item->children = $childs[$item->id];
                                unset($item);
                            }
                        }
                        $tree = $childs[0];
                        $data['treeviewspecial']=$tree;

               }
		
		 $treeviewheat_transfer= DB::table("orchestra_item_master")->select('id',DB::raw("(CASE WHEN ref_no = '' THEN item_name ELSE CONCAT(item_name,' - ',ref_no) END) as text"),'parent_id')->orWhere('id','1')->orWhere('id','62')->orWhere('id','63')->orWhere('tag_name','heat_transfer')->orderBy('item_name', 'asc')->get()->toArray();
            if($treeviewheat_transfer){
                        $childs = array();
                        foreach($treeviewheat_transfer as &$item) 
                        {
                        $childs[$item->parent_id][] = &$item;
                        unset($item);
                        }
                        foreach($treeviewheat_transfer as &$item) 
                        {
                            if (isset($childs[$item->id]))
                            {
                                $item->children = $childs[$item->id];
                                unset($item);
                            }
                        }
                        $tree = $childs[0];
                        $data['treeviewheat_transfer']=$tree;

               }
		
		$treeviewmain_label= DB::table("orchestra_item_master")->select('id',DB::raw("(CASE WHEN ref_no = '' THEN item_name ELSE CONCAT(item_name,' - ',ref_no) END) as text"),'parent_id')->orWhere('id','1')->orWhere('id','2')->orWhere('id','10')->orWhere('id','13')->orWhere('tag_name','main')->orWhere('tag_name','MS')->orWhere('tag_name','main_size')->orderBy('item_name', 'asc')->get()->toArray();
            if($treeviewmain_label){
                        $childs = array();
                        foreach($treeviewmain_label as &$item) 
                        {
                        $childs[$item->parent_id][] = &$item;
                        unset($item);
                        }
                        foreach($treeviewmain_label as &$item) 
                        {
                            if (isset($childs[$item->id]))
                            {
                                $item->children = $childs[$item->id];
                                unset($item);
                            }
                        }
                        $tree = $childs[0];
                        $data['treeviewmain_label']=$tree;

               }
		
		$treeviewsize_label= DB::table("orchestra_item_master")->select('id',DB::raw("(CASE WHEN ref_no = '' THEN item_name ELSE CONCAT(item_name,' - ',ref_no) END) as text"),'parent_id')->orWhere('id','1')->orWhere('id','2')->orWhere('id','10')->orWhere('id','13')->orWhere('tag_name','size')->orWhere('tag_name','MS')->orWhere('tag_name','main_size')->orderBy('item_name', 'asc')->get()->toArray();
            if($treeviewsize_label){
                        $childs = array();
                        foreach($treeviewsize_label as &$item) 
                        {
                        $childs[$item->parent_id][] = &$item;
                        unset($item);
                        }
                        foreach($treeviewsize_label as &$item) 
                        {
                            if (isset($childs[$item->id]))
                            {
                                $item->children = $childs[$item->id];
                                unset($item);
                            }
                        }
                        $tree = $childs[0];
                        $data['treeviewsize_label']=$tree;

               }
		
//		$treeviewwash= DB::table("orchestra_item_master")->select('id',DB::raw("(CASE WHEN ref_no IS NULL THEN item_name ELSE CONCAT(item_name,' - ',ref_no) END) as text"),'parent_id')->orWhere('parent_id','120')->orWhere('id','77')->orWhere('id','4')->orWhere('id','120')->orderBy('item_name', 'asc')->get()->toArray();
//		//print_r($treeviewwash);
//            if($treeviewwash){
//                        $childs = array();
//                        foreach($treeviewwash as &$item) 
//                        {
//                        $childs[$item->parent_id][] = &$item;
//                        unset($item);
//                        }
//                        foreach($treeviewwash as &$item) 
//                        {
//                            if (isset($childs[$item->id]))
//                            {
//                                $item->children = $childs[$item->id];
//                                unset($item);
//                            }
//                        }
//                        $tree = $childs[0];
//                        $data['treeviewwash']=$tree;
//
//               }
        
//        $treeviewmain= DB::table("orchestra_item_master")->select('id',DB::raw("(CASE WHEN ref_no IS NULL THEN item_name ELSE CONCAT(item_name,' - ',ref_no) END) as text"),'parent_id')->orWhere('id','4')->orWhere('id','76')->orWhere('tag_name','mainsize')->orderBy('item_name', 'asc')->get()->toArray();
//            if($treeviewmain){
//                        $childs = array();
//                        foreach($treeviewmain as &$item) 
//                        {
//                        $childs[$item->parent_id][] = &$item;
//                        unset($item);
//                        }
//                        foreach($treeviewmain as &$item) 
//                        {
//                            if (isset($childs[$item->id]))
//                            {
//                                $item->children = $childs[$item->id];
//                                unset($item);
//                            }
//                        }
//                        $tree = $childs[0];
//                        $data['treeviewmain']=$tree;
//
//               }
		
        $code=$data['order_details']{0}->Family_Code;
		
        $data['orchestra_hang_tag_orders']=DB::table('orchestra_hang_tag_orders')->select('*',DB::raw("'Hang Tag' as \"TemplateName\""))->where('uvc',$uvc)->where('status','!=','Order Cancelled')->get();
		
		$data['orchestra_hang_tag_orders_id']=DB::table('orchestra_hang_tag_orders')->where('uvc',$uvc)->where('status','!=','Order Cancelled')->pluck('id');
		
        if(count($data['orchestra_hang_tag_orders']) > 0)
        {
            $data['orchestra_hang_tag_orders_qty']=DB::table('orchestra_hang_tag_orders_qty')->select('*')->whereIn('oht_id',$data['orchestra_hang_tag_orders_id'])->orderBy('orchestra_hang_tag_orders_qty.parcel_id','asc')->get();
        }
        else
        {
            $data['orchestra_hang_tag_orders_qty']="";
        }
		
         $data['orchestra_special_tag_orders']=DB::table('orchestra_special_tag_orders')->select('*',DB::raw("'Special Tag' as \"TemplateName\""))->where('uvc',$uvc)->where('status','!=','Order Cancelled')->get();
		
		$data['orchestra_special_tag_orders_id']=DB::table('orchestra_special_tag_orders')->where('uvc',$uvc)->where('status','!=','Order Cancelled')->pluck('id');
		
        if(count($data['orchestra_special_tag_orders']) > 0)
        {
            $data['orchestra_special_tag_orders_qty']=DB::table('orchestra_special_tag_orders_qty')->select('*')->whereIn('ost_id',$data['orchestra_special_tag_orders_id'])->get();
        }
        else
        {
            $data['orchestra_special_tag_orders_qty']="";
        }     
		
		$data['orchestra_heat_transfer_orders']=DB::table('orchestra_heat_transfer_orders')->select('*',DB::raw("'Heat Transfer' as \"TemplateName\""))->where('uvc',$uvc)->where('status','!=','Order Cancelled')->get();
		
		$data['orchestra_heat_transfer_orders_id']=DB::table('orchestra_heat_transfer_orders')->where('uvc',$uvc)->where('status','!=','Order Cancelled')->pluck('id');
		
        if(count($data['orchestra_heat_transfer_orders']) > 0)
        {
            $data['orchestra_heat_transfer_orders_qty']=DB::table('orchestra_heat_transfer_orders_qty')->select('*')->whereIn('oht_id',$data['orchestra_heat_transfer_orders_id'])->orderBy('orchestra_heat_transfer_orders_qty.parcel_id','asc')->get();
        }
        else
        {
            $data['orchestra_heat_transfer_orders_qty']="";
        }
		
		$data['orchestra_main_label_orders']=DB::table('orchestra_main_label_orders')->select('*',DB::raw("'Main Label' as \"TemplateName\""))->where('uvc',$uvc)->where('status','!=','Order Cancelled')->get();
		
		$data['orchestra_main_label_orders_id']=DB::table('orchestra_main_label_orders')->where('uvc',$uvc)->where('status','!=','Order Cancelled')->pluck('id');
		
        if(count($data['orchestra_main_label_orders']) > 0)
        {
            $data['orchestra_main_label_orders_qty']=DB::table('orchestra_main_label_orders_qty')->select('*')->whereIn('oml_id',$data['orchestra_main_label_orders_id'])->get();
        }
        else
        {
            $data['orchestra_main_label_orders_qty']="";
        }
        
		$data['orchestra_size_label_orders']=DB::table('orchestra_size_label_orders')->select('*',DB::raw("'Size Label' as \"TemplateName\""))->where('uvc',$uvc)->where('status','!=','Order Cancelled')->get();
		
		$data['orchestra_size_label_orders_id']=DB::table('orchestra_size_label_orders')->where('uvc',$uvc)->where('status','!=','Order Cancelled')->pluck('id');
        if(count($data['orchestra_size_label_orders']) > 0)
        {
            $data['orchestra_size_label_orders_qty']=DB::table('orchestra_size_label_orders_qty')->select('*')->whereIn('osl_id',$data['orchestra_size_label_orders_id'])->orderBy('orchestra_size_label_orders_qty.parcel_id','asc')->get();
        }
        else
        {
            $data['orchestra_size_label_orders_qty']="";
        }
		
//        $data['orchestra_main_label_orders']=DB::table('orchestra_main_label_orders')->select('*',DB::raw("'Main Label' as \"TemplateName\""))->where('uvc',$uvc)->get();
//        foreach($data['orchestra_main_label_orders'] as $value)
//        {
//            $orchestra_main_label_orders_qty=DB::table('orchestra_main_label_orders_qty')->select('*')->where('oml_id',$value->id)->get();
//            $value->qty=$orchestra_main_label_orders_qty;
//        }
        
       if($data['order_details']{0}->FactoryCode == "HF-FREEUSD")
	   {
				$data['orchestra_wash_care_label_orders']=DB::table('orchestra_wash_care_label_orders')->select(DB::raw('distinct on ("card_flag") "card_flag"'),'*','orchestra_wash_care_label_orders.template_name as TemplateName')->where('uvc',$uvc)->where('status','!=','Order Cancelled')->orderBy('orchestra_wash_care_label_orders.card_flag','asc')->orderBy('orchestra_wash_care_label_orders.po','asc')->get();
		   
		   $data['orchestra_wash_care_label_orders_po']=DB::table('orchestra_wash_care_label_orders')->select('*','orchestra_wash_care_label_orders.template_name as TemplateName')->where('uvc',$uvc)->where('status','!=','Order Cancelled')->orderBy('orchestra_wash_care_label_orders.po','asc')->get();
	   }
	   else
	   {
			$data['orchestra_wash_care_label_orders']=DB::table('orchestra_wash_care_label_orders')->select('*','orchestra_wash_care_label_orders.template_name as TemplateName')->where('uvc',$uvc)->where('status','!=','Order Cancelled')->orderBy('orchestra_wash_care_label_orders.po','asc')->get();
	   }
        foreach($data['orchestra_wash_care_label_orders'] as $value1)
        {
            $orchestra_wash_label_orders_qty=DB::table('orchestra_wash_care_label_orders_qty')->select('*')->where('owcl_id',$value1->id)->get();
            $value1->qty=$orchestra_wash_label_orders_qty;
            
             $orchestra_wash_care_composition=DB::table('orchestra_wash_care_composition')->leftJoin('orchestra_compositions','orchestra_compositions.id','=','orchestra_wash_care_composition.composition_id')->leftJoin('orchestra_parts','orchestra_parts.id','=','orchestra_wash_care_composition.parts_id')->select('orchestra_wash_care_composition.*','orchestra_compositions.french','orchestra_compositions.english','orchestra_compositions.spanish','orchestra_compositions.portuguese','orchestra_compositions.italian','orchestra_compositions.dutch','orchestra_compositions.german','orchestra_compositions.russian','orchestra_compositions.chinese','orchestra_compositions.arabic','orchestra_compositions.greek','orchestra_compositions.turkish','orchestra_parts.french as parts_french','orchestra_parts.english as parts_english','orchestra_parts.spanish as parts_spanish','orchestra_parts.portuguese as parts_portuguese','orchestra_parts.italian as parts_italian','orchestra_parts.dutch as parts_dutch','orchestra_parts.german as parts_german','orchestra_parts.russian as parts_russian','orchestra_parts.chinese as parts_chinese','orchestra_parts.arabic as parts_arabic','orchestra_parts.greek as parts_greek','orchestra_parts.turkish as parts_turkish')->orderBy('orchestra_wash_care_composition.id','asc')->where('owcl_id',$value1->id)->get();
             $value1->composition=$orchestra_wash_care_composition;
            $care_instructions=array();
            foreach(explode(',',$value1->care_instruction_id) as $value2)
            {
                array_push($care_instructions,$value2);
            }
            $value1->care_instruction=$care_instructions;
        }
		        $factory = $data['order_details']{0}->FactoryCode;
		  		$data['customers']=DB::table('customers')->join('customer_buyers','customer_buyers.customer_id','=','customers.id')->where('customers.factory_code',$factory)->where('customer_buyers.buyer_id',1)->select('*')->get();
				if(count($data['customers']) > 0)
				{
					$cust_id = $data['customers']{0}->id;
					$data['merchandisers']=DB::table('merchandisers')->where('buyer_id',1)->select('*')->get();
				}
				else
				{
					$data['merchandisers']="";
				}
				
                $data['orchestra_care_symbols'] = DB::table('orchestra_care_symbols')->where('is_enabled',1)->select('*')->get();
                $data['orchestra_composition'] = DB::table('orchestra_compositions')->where('is_enabled',1)->select('*')->get();
                $data['orchestra_countries'] = DB::table('orchestra_countries')->where('is_enabled',1)->select('*')->get();
                $data['orchestra_care_instruction'] = DB::table('orchestra_care_instruction')->where('is_enabled',1)->select('*')->get(); 
                $data['orchestra_parts'] = DB::table('orchestra_parts')->where('is_enabled',1)->select('*')->get();
                $pro_family = DB::table('orchestra_product_family')->where('is_enabled',1)->select('*')->where('code',$code)->get();
				if(count($pro_family) > 0)
				{
					$data['product_family'] = DB::table('orchestra_product_family')->select('*')->where('code',$code)->get();
				}
				else
				{
					$data['product_family'] ="";
				}
                echo json_encode($data);
    }
	
	/**
     * Show the sticker orders details.
     *
     * @return Json Value
     */
	
	
    public function stickerOrderDetails(Request $request)
    {
        $po=$request->po;
        $uvc=$request->uvc;
        
         $data['orchestra_parcel_quantity_details'] = DB::table('orchestra_parcel_quantity_details')->select(DB::raw('distinct on ("UVC_TCO") "UVC_TCO"'),'UVC','Size','BarcodeForPricesTags','Quantity','Color')->where('BuyerPONo',$po)->get();
        
         $data['orchestra_parcel_details']= DB::table('orchestra_parcel_details as alias')->leftJoin('orchestra_color_code','orchestra_color_code.code','alias.Color_Code')->select(DB::raw('distinct on (alias."Size") "Size"'),DB::raw("(SELECT SUM(\"Quantity\") FROM orchestra_parcel_details WHERE \"Size\" = alias.\"Size\" and \"BuyerPONo\"='".$po."') as \"Sum\""),'alias.*','Standard_Code as ParcelCode','orchestra_color_code.french as colour_Code')->where('BuyerPONo',$po)->groupBy('alias.id','orchestra_color_code.id','BuyerPONo')->get();
		
		$sorted = $data['orchestra_parcel_details']->sortBy('id');
		
		$data['orchestra_parcel_details'] = $sorted->values()->all();
        
		$data['color_wcl'] = DB::table('orchestra_parcel_details')->select('orchestra_parcel_details.Color_Code')->where('orchestra_parcel_details.UVC',$uvc)->groupBy('orchestra_parcel_details.Color_Code') ->orderBy('orchestra_parcel_details.Color_Code','asc')->get();
		
        $data['orchestra_age_group']= DB::table('orchestra_age_group')->where('is_enabled',1)->select('*')->get();      
        
       $data['orchestra_solid_carton_sticker_orders']=DB::table('orchestra_solid_carton_sticker_orders')->select('*')->where('uvc',$uvc)->where('po',$po)->where('status','!=','Order Cancelled')->get();

        if(count($data['orchestra_solid_carton_sticker_orders']) > 0)
        {
            $data['carton_id']=$data['orchestra_solid_carton_sticker_orders'][0]->id;
        }
        else
        {
            $data['carton_id']="null";
        }
        foreach($data['orchestra_solid_carton_sticker_orders'] as $value)
        {
            $orchestra_solid_carton_sticker_orders_qty=DB::table('orchestra_solid_carton_sticker_orders_qty')->select('*')->where('oscs_id',$value->id)->get();
            $value->qty=$orchestra_solid_carton_sticker_orders_qty;
        }
		
        
        $data['orchestra_solid_poly_bag_sticker_orders']=DB::table('orchestra_solid_poly_bag_sticker_orders')->select('*')->where('uvc',$uvc)->where('po',$po)->where('status','!=','Order Cancelled')->get();
        if(count($data['orchestra_solid_poly_bag_sticker_orders']) > 0)
        {
             $data['poly_id']=$data['orchestra_solid_poly_bag_sticker_orders'][0]->id;
        }
        else
        {
             $data['poly_id']="null";
        }
        foreach($data['orchestra_solid_poly_bag_sticker_orders'] as $value1)
        {
            $orchestra_solid_poly_bag_sticker_orders_qty=DB::table('orchestra_solid_poly_bag_sticker_orders_qty')->select('*')->where('ospbs_id',$value1->id)->get();
            $value1->qty=$orchestra_solid_poly_bag_sticker_orders_qty;
        }
        
        $data['orchestra_standard_carton_sticker_orders']=DB::table('orchestra_standard_carton_sticker_orders')->select('*')->where('uvc',$uvc)->where('po',$po)->where('status','!=','Order Cancelled')->get();
        if(count($data['orchestra_standard_carton_sticker_orders']) > 0)
        {
            $data['std_carton_id']=$data['orchestra_standard_carton_sticker_orders'][0]->id;
        }
        else
        {
            $data['std_carton_id']="null";
        }
        
        $data['orchestra_standard_poly_bag_sticker_orders']=DB::table('orchestra_standard_poly_bag_sticker_orders')->select('*')->where('uvc',$uvc)->where('po',$po)->where('status','!=','Order Cancelled')->get();
        if(count($data['orchestra_standard_poly_bag_sticker_orders']) > 0)
        {
             $data['std_poly_id']=$data['orchestra_standard_poly_bag_sticker_orders'][0]->id;
        }
        else
        {
             $data['std_poly_id']="null";
        }
        
        if($data['orchestra_parcel_details']{0}->IsSTD == 1)
        {
            $orchestra_template = DB::table('orchestra_template')->select('*')->where('item_id','101')->get();
            $data['carton_template']=$orchestra_template{0}->template_file;
            $data['carton_template_id']=101;
			
			$factory_code = $data['orchestra_parcel_details']{0}->FactoryCode;
			$customer_id = DB::table('customers')->select('*')->where('factory_code',$factory_code)->get();
			
			$customer_active = DB::table('customers')->select('*')->where('factory_code',$factory_code)->where('is_active',1)->get();
				
			if(count($customer_active) > 0)
			{
				$data['customer_active'] = "active";
			}
			else
			{
				$data['customer_active'] = "inactive";
			}
			
			if(count($customer_id) > 0)
			{
                $carton_moq = DB::table('price_taxation')->select('*')->where('customer_id',$customer_id{0}->id)->where('buyer_id',1)->where('template_id',101)->get();
                
                if(count($carton_moq) > 0)
				{
					$data['carton_moq']=$carton_moq{0}->moq;
				}
				else
				{
					$data['carton_moq']=1;
				}
			}
			else
			{
				$data['carton_moq']=1;
			}
			
            $orchestra_template = DB::table('orchestra_template')->select('*')->where('item_id','102')->get();
            $data['polybag_template']=$orchestra_template{0}->template_file;
            $data['template_name']="Standard";
            $data['polybag_template_id']=102;
			if(count($customer_id) > 0)
			{
                $polybag_moq = DB::table('price_taxation')->select('*')->where('customer_id',$customer_id{0}->id)->where('buyer_id',1)->where('template_id',102)->get();
                
                if(count($polybag_moq) > 0)
				{
					$data['polybag_moq']=$polybag_moq{0}->moq;
				}
				else
				{
					$data['polybag_moq']=1;
				}
			}
			else
			{
				$data['polybag_moq']=1;
			}
        }
        else
        {
            $orchestra_template = DB::table('orchestra_template')->select('*')->where('item_id','99')->get();
            $data['carton_template']=$orchestra_template{0}->template_file;
            $data['carton_template_id']=99;
			
			$factory_code = $data['orchestra_parcel_details']{0}->FactoryCode;
			$customer_id = DB::table('customers')->select('*')->where('factory_code',$factory_code)->get();
			
			$customer_active = DB::table('customers')->select('*')->where('factory_code',$factory_code)->where('is_active',1)->get();
				
			if(count($customer_active) > 0)
			{
				$data['customer_active'] = "active";
			}
			else
			{
				$data['customer_active'] = "inactive";
			}
			
			if(count($customer_id) > 0)
			{
				$carton_moq = DB::table('price_taxation')->select('*')->where('customer_id',$customer_id{0}->id)->where('buyer_id',1)->where('template_id',99)->get();
				
				if(count($carton_moq) > 0)
				{
					$data['carton_moq']=$carton_moq{0}->moq;
				}
				else
				{
					$data['carton_moq']=1;
				}
			
				
			}
			else
			{
				$data['carton_moq']=1;
			}
			
			
            $orchestra_template = DB::table('orchestra_template')->select('*')->where('item_id','100')->get();
            $data['polybag_template']=$orchestra_template{0}->template_file;
            $data['template_name']="Solid";
            $data['polybag_template_id']=100;
			
			if(count($customer_id) > 0)
			{
				$polybag_moq = DB::table('price_taxation')->select('*')->where('customer_id',$customer_id{0}->id)->where('buyer_id',1)->where('template_id',100)->get();

				if(count($polybag_moq) > 0)
				{
					$data['polybag_moq']=$polybag_moq{0}->moq;
				}
				else
				{
					$data['polybag_moq']=1;
				}
			}
			else
			{
				$data['polybag_moq']=1;
			}
			
            
        }
        
        echo json_encode($data);
           
    }
	
	/**
     * This Function is used to get the Composition Details.
     *
     * @return Json Value
     */
	
    public function compositionDetails(Request $request)
    {
         $lang=$request->lang;
        
         $data = DB::table('orchestra_compositions')->select('*')->whereIn('english',$lang)->get();
        
         echo json_encode($data);
           
    } 
	
	/**
     * This Function is used to save the Hang Tag Details.
     *
     * @return Void
     */
	
    public function setHangTag(Request $request)
    {
        //$delivery_date = $request->delivery_date;
        $template_front_id = $request->template_front_id;
        $template_back_id = $request->template_back_id;
        $tot_qty_table = explode(',',$request->tot_qty_table);
        $uvc_tco = explode(',',$request->uvc_tco);
        $barcode = explode(',',$request->barcode);
        $size = explode(',',$request->size);
        $size_id = explode(',',$request->size_id);
        $main_id = explode(',',$request->main_id);
        $uvc =$request->uvc;
        $parcel_id =$request->parcel_id;
        $colour = $request->colour;
        $season_code =$request->season_code;
        $id =$request->id;
		$factory = DB::table("orchestra_parcel_details")->join('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('UVC',$uvc)->select('customers.*')->get();
		
		if(count($factory) > 0)
		{
			
			$pi_required=$factory{0}->pi_required;
			if($pi_required == 1)
			{
				$pi_status="PI Approval Pending";
				if($factory{0}->po_required == 1)
				{
					$po_status="PO Pending";
				}
				else
				{
					$po_status="";
				}
				
			}
			else
			{
				$po_status="PO Pending";
				$pi_status="";
			}
			$status="Order Approved";
		}
		else
		{
			$status="";
			$po_status="";
			$pi_status="";
		}
		
        if($id != "null")
        {
            // get quantity_table ids.
            $quantity_table_ids = DB::table('orchestra_hang_tag_orders_qty')->select('id')->where('oht_id',$id)->get();

            HangTagOrders::where('id',$id)
                ->update([
                    'uvc' => $uvc,
                    'template_front_id'      => $template_front_id,
                    'template_back_id'      => $template_back_id,
                    'svg_path'    => "", 
                    'status'    => $status,
                    'customer_id'    => $factory{0}->id,
                    'orchestra_parcel_details_id'    => $parcel_id,
                    'season_code'    => $season_code,
                    'pi_status'           => $pi_status,
                    'po_status'           => $po_status,
                ]);
            $deleted = HangTagOrdersQty::where('oht_id', '=', $id)
                        ->delete();
            $i=0;
            foreach($tot_qty_table as $value)
            {
                    HangTagOrdersQty::create([
                                'oht_id' => $id,
                                'size'      => $size[$i],
                                'uvc_tco'      => $uvc_tco[$i],
                                'barcode'      => $barcode[$i],
                                'parcel_id'      => $main_id[$i],
                                'quantity'    => $value
                        ]);
                    $i++;
            }
        
            DB::table("orchestra_parcel_details")->where('UVC',$uvc)
                    ->update([
                        'order_status' => "Order Placed"

            ]);

            // fetch erp_order_details table details for each quantity_table ids.
            foreach($quantity_table_ids as $quantity_table_id)
            {
                $quantity_id = $quantity_table_id->id;
                $erp_order_details[] = DB::table('erp_order_details')->where('order_qnty_table_name','ilike', 'orchestra_hang_tag_orders_qty')->join('erp_status', 'erp_status.id', 'erp_order_details.status')->where('order_qnty_table_id','=', $quantity_id)->select('erp_order_details.id','erp_status.status_name', 'erp_order_details.erp_order_id')->first();
            }
        
            if(!is_null($erp_order_details[0]))
            {
                $erp_details_status =  $erp_order_details[0]->status_name;
                $erp_order_id =  $erp_order_details[0]->erp_order_id;
                
                foreach($erp_order_details as $erp_order_detail){
                    // delete from history table and insert new rows.
                    $history_table_id =  DB::table('history')->where('erp_order_details_id',$erp_order_detail->id)->value('id');
                    DB::table('event_history')->where('history_id',$history_table_id)->delete();
                    DB::table('history')->where('id',$history_table_id)->delete();
                    // delete from erp_order_details table
                    DB::table('erp_order_details')->where('id',$erp_order_detail->id)->delete();
                }
                
                /** @var array [Pass array of values to updateOrchestraErpOrderDetails function] */
                $data_array = [];   
                $orchestra_buyer_id               = DB::table('buyers')->select('id')->where('buyer_name','=','Orchestra')->first();
                $data_array['main_table']         = 'orchestra_hang_tag_orders';
                $data_array['main_table_id']      = $id;
                $data_array['quantity_table']     = 'orchestra_hang_tag_orders_qty';
                $data_array['quantity_id_field']  = 'oht_id';
                $data_array['buyer_id']           = $orchestra_buyer_id->id;
                $data_array['erp_details_status'] = $erp_details_status;
                $data_array['erp_order_id']       = $erp_order_id;

                // update erp_order_details and erp_orders table 
                $result = $this->edit_erp_obj->updateOrchestraErpOrderDetails($data_array);
            }
            else
            {
                $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
        }
        else
        {
			foreach($colour as $value1)
            {
				 $inserted = HangTagOrders::create([
										'uvc' => $uvc,
										'template_front_id'      => $template_front_id,
										'template_back_id'      => $template_back_id,
										'svg_path'    => "", 
										'status'    => $status,
										'customer_id'    => $factory{0}->id,
										'orchestra_parcel_details_id'    => $parcel_id,
										'season_code'    => $season_code,
										'pi_status'           => $pi_status,
										'po_status'           => $po_status,
					 					'color_code'          => $value1['Color_Code'],
					 					'template_type'       => "single"
							 ]);
				$i=0;
				foreach($tot_qty_table as $value)
				{
					if($value1['Color_Code'] == $size_id[$i])
					{
					 	HangTagOrdersQty::create([
										'oht_id' => $inserted->id,
										'size'      => $size[$i],
										'uvc_tco'      => $uvc_tco[$i],
										'barcode'      => $barcode[$i],
										'parcel_id'      => $main_id[$i],
										'quantity'    => $value
						]);
					}
					$i++;
				}
			}
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)
                        ->update([
                          'order_status' => "Order Placed"

            ]);

            $result = response()->json(['error'=> false,'message' =>'success','id'=>$inserted->id],200);
        }

        return $result;
           
    }
	
	
	/**
     * This Function is used to remove Hang Tag Details.
     *
     * @return Void
     */
	public function removeHangTag(Request $request)
	{
		$id =$request->id;
		
	    HangTagOrders::where('id',$id)
                ->update([
                    'status' => "Order Cancelled"
                ]);
		
		$hang_tag = HangTagOrders::where('id',$id)->select('*')->first();
        
        HangTagOrders::where('uvc',$hang_tag->uvc)
                ->update([
                    'status' => "Order Cancelled"
                ]);
		
		$orchestra_hang = HangTagOrders::where('uvc',$hang_tag->uvc)->where('status','!=','Order Cancelled')->where('season_code',$hang_tag->season_code)->select('*')->get();
		
		$orchestra_main = MainLabelOrders::where('uvc',$hang_tag->uvc)->where('status','!=','Order Cancelled')->where('season_code',$hang_tag->season_code)->select('*')->get();
		
		$orchestra_wash = WashLabelOrders::where('uvc',$hang_tag->uvc)->where('status','!=','Order Cancelled')->where('season_code',$hang_tag->season_code)->select('*')->get();
		
		$orchestra_special = SpecialTagOrders::where('uvc',$hang_tag->uvc)->where('status','!=','Order Cancelled')->where('season_code',$hang_tag->season_code)->select('*')->get();
		
		$orchestra_heat = HeatTransferOrders::where('uvc',$hang_tag->uvc)->where('status','!=','Order Cancelled')->where('season_code',$hang_tag->season_code)->select('*')->get();
		
		$orchestra_size = SizeLabelOrders::where('uvc',$hang_tag->uvc)->where('status','!=','Order Cancelled')->where('season_code',$hang_tag->season_code)->select('*')->get();
		
		$update_po_disable = count($orchestra_hang) + count($orchestra_main) + count($orchestra_wash) + count($orchestra_special) + count($orchestra_heat) + count($orchestra_size);
		
		$result = response()->json(['error'=> false,'message' =>'success','update_po_disable' => $update_po_disable],200);
		
		return $result;
	}
	/**
     * This Function is used to delete the Main Label Details.
     *
     * @return Void
     */
	
    public function removeMainLabel(Request $request)
    {
        $id =$request->id;
		
	    MainLabelOrders::where('id',$id)
                ->update([
                    'status' => "Order Cancelled"
                ]);
		
		$main_label = MainLabelOrders::where('id',$id)->select('*')->first();
        
        MainLabelOrders::where('uvc',$main_label->uvc)
                ->update([
                    'status' => "Order Cancelled"
                ]);
		
		$orchestra_hang = HangTagOrders::where('uvc',$main_label->uvc)->where('status','!=','Order Cancelled')->where('season_code',$main_label->season_code)->select('*')->get();
		
		$orchestra_main = MainLabelOrders::where('uvc',$main_label->uvc)->where('status','!=','Order Cancelled')->where('season_code',$main_label->season_code)->select('*')->get();
		
		$orchestra_wash = WashLabelOrders::where('uvc',$main_label->uvc)->where('status','!=','Order Cancelled')->where('season_code',$main_label->season_code)->select('*')->get();
		
		$orchestra_special = SpecialTagOrders::where('uvc',$main_label->uvc)->where('status','!=','Order Cancelled')->where('season_code',$main_label->season_code)->select('*')->get();
		
		$orchestra_heat = HeatTransferOrders::where('uvc',$main_label->uvc)->where('status','!=','Order Cancelled')->where('season_code',$main_label->season_code)->select('*')->get();
		
		$orchestra_size = SizeLabelOrders::where('uvc',$main_label->uvc)->where('status','!=','Order Cancelled')->where('season_code',$main_label->season_code)->select('*')->get();
		
		$update_po_disable = count($orchestra_hang) + count($orchestra_main) + count($orchestra_wash) + count($orchestra_special) + count($orchestra_heat) + count($orchestra_size);
		
		$result = response()->json(['error'=> false,'message' =>'success','update_po_disable' => $update_po_disable],200);
		
		return $result;
    }
	/**
     * This Function is used to delete the Main Label Details.
     *
     * @return Void
     */
	
    public function removeWCL(Request $request)
    {
        $id =$request->id;
		
	    WashLabelOrders::where('id',$id)
                ->update([
                    'status' => "Order Cancelled",
					'order_cancelled_at' => $this->current_date
                ]);
		
		$wcl = DB::table("orchestra_wash_care_label_orders")->where('id',$id)->select('*')->first();
		
		  $order_qty_details  = DB::table("orchestra_wash_care_label_orders_qty")->where('owcl_id', $wcl->id)
              ->select('id', DB::raw("'orchestra_wash_care_label_orders_qty' as \"table_name\""))->get();
		
		  $order_status_id  = DB::table('orders_status')->select('id')->where('status_name', 'ilike', $wcl->status)->first();

		  $customer_data  = DB::table('customers')->select('pi_required', 'po_required')->where('id', $wcl->customer_id)->first();

		  if ($customer_data->pi_required == 1 && $customer_data->po_required== 0) 
		  {
			$purchase_order_no     = $wcl->pi_id;
		  }
		  else if($customer_data->po_required== 1)
		  {
			$purchase_order_no     = $wcl->po_number;
		  }         
		
		$buyers  = DB::table('buyers')->select('*')->where('buyer_name', 'Orchestra')->first();
		
		$erp_orders = Erp::where('buyer_id',$buyers->id)->where('customer_id',$wcl->customer_id)->where('po_no', $purchase_order_no)->first();
		
		  if ($erp_orders) 
		  {
			$erp_id = $erp_orders->id;

			// Get Erp status table id for 'Moved To JC Queue' status.
			$erp_status_id = DB::table('erp_status')->where('status_name', 'ilike', 'Cancelled')->value('id');
			// update order status in erp_order_details table
			foreach ($order_qty_details as $key => $value) 
			{
			  $erp_order_qty = ErpOrderDetails::where('order_qnty_table_name',$value->table_name)->where('order_qnty_table_id',$value->id)->first();

			  if ($erp_order_qty) 
			  {
				  
				ErpOrderDetails::where('order_qnty_table_id', $value->id)
				->where('order_qnty_table_name', $value->table_name)
				->where('erp_order_id', $erp_id)
				->update([
						  'status'  => $erp_status_id,
						  'order_status_id'  => $order_status_id->id
						]);

				/** Update order cancelled status erp orders table(iff, all the orders in the order details table have same status. ie, after cancelling all orders) */

				$erp_order_table_status = DB::table('erp_order_details')
				->where('erp_order_details.erp_order_id', $erp_id)
				->pluck('status')->all();

				if(count(array_unique($erp_order_table_status)) === 1)
				{
				  DB::table('erp_orders')
					->where('erp_orders.id', $erp_id)
					->update([
					  'status'  => $erp_order_table_status[0]
					]);
				}
			  }
			}

		  }
		
		$wcl = DB::table("orchestra_wash_care_label_orders")->where('id',$id)->select('*')->first();
		
		$orchestra_hang = HangTagOrders::where('uvc',$wcl->uvc)->where('status','!=','Order Cancelled')->where('season_code',$wcl->season_code)->select('*')->get();
		
		$orchestra_main = MainLabelOrders::where('uvc',$wcl->uvc)->where('status','!=','Order Cancelled')->where('season_code',$wcl->season_code)->select('*')->get();
		
		$orchestra_wash = WashLabelOrders::where('uvc',$wcl->uvc)->where('status','!=','Order Cancelled')->where('season_code',$wcl->season_code)->select('*')->get();
		
		$orchestra_special = SpecialTagOrders::where('uvc',$wcl->uvc)->where('status','!=','Order Cancelled')->where('season_code',$wcl->season_code)->select('*')->get();
		
		$orchestra_heat = HeatTransferOrders::where('uvc',$wcl->uvc)->where('status','!=','Order Cancelled')->where('season_code',$wcl->season_code)->select('*')->get();
		
		$orchestra_size = SizeLabelOrders::where('uvc',$wcl->uvc)->where('status','!=','Order Cancelled')->where('season_code',$wcl->season_code)->select('*')->get();
		
		$update_po_disable = count($orchestra_hang) + count($orchestra_main) + count($orchestra_wash) + count($orchestra_special) + count($orchestra_heat) + count($orchestra_size);
		
		$orchestra_wcl = DB::table("orchestra_wash_care_label_orders")->where('uvc',$wcl->uvc)->where('color_code',$wcl->color_code)->where('status','!=','Order Cancelled')->where('season_code',$wcl->season_code)->select('*')->get();
		
		$result = response()->json(['error'=> false,'message' =>'success','orchestra_wcl' => $orchestra_wcl,'update_po_disable' => $update_po_disable],200);
		
		return $result;
    }
	
	/**
     * This Function is used to remove Special Tag Details.
     *
     * @return Void
     */
	public function removeSpecialTag(Request $request)
	{
		$id =$request->id;
		
	    SpecialTagOrders::where('id',$id)
                ->update([
                    'status' => "Order Cancelled"
                ]);
		
		$special_tag = SpecialTagOrders::where('id',$id)->select('*')->first();
        
        SpecialTagOrders::where('uvc',$special_tag->uvc)
                ->update([
                    'status' => "Order Cancelled"
                ]);
		
		$orchestra_hang = HangTagOrders::where('uvc',$special_tag->uvc)->where('status','!=','Order Cancelled')->where('season_code',$special_tag->season_code)->select('*')->get();
		
		$orchestra_main = MainLabelOrders::where('uvc',$special_tag->uvc)->where('status','!=','Order Cancelled')->where('season_code',$special_tag->season_code)->select('*')->get();
		
		$orchestra_wash = WashLabelOrders::where('uvc',$special_tag->uvc)->where('status','!=','Order Cancelled')->where('season_code',$special_tag->season_code)->select('*')->get();
		
		$orchestra_special = SpecialTagOrders::where('uvc',$special_tag->uvc)->where('status','!=','Order Cancelled')->where('season_code',$special_tag->season_code)->select('*')->get();
		
		$orchestra_heat = HeatTransferOrders::where('uvc',$special_tag->uvc)->where('status','!=','Order Cancelled')->where('season_code',$special_tag->season_code)->select('*')->get();
		
		$orchestra_size = SizeLabelOrders::where('uvc',$special_tag->uvc)->where('status','!=','Order Cancelled')->where('season_code',$special_tag->season_code)->select('*')->get();
		
		$update_po_disable = count($orchestra_hang) + count($orchestra_main) + count($orchestra_wash) + count($orchestra_special) + count($orchestra_heat) + count($orchestra_size);
		
		$result = response()->json(['error'=> false,'message' =>'success','update_po_disable' => $update_po_disable],200);
		
		return $result;
	}
	
	/**
     * This Function is used to remove Special Tag Details.
     *
     * @return Void
     */
	public function removeHeatTransfer(Request $request)
	{
		$id =$request->id;
		
	    HeatTransferOrders::where('id',$id)
                ->update([
                    'status' => "Order Cancelled"
                ]);
		
		$heat_transfer = HeatTransferOrders::where('id',$id)->select('*')->first();
        
        HeatTransferOrders::where('uvc',$heat_transfer->uvc)
                ->update([
                    'status' => "Order Cancelled"
                ]);
		
		$orchestra_hang = HangTagOrders::where('uvc',$heat_transfer->uvc)->where('status','!=','Order Cancelled')->where('season_code',$heat_transfer->season_code)->select('*')->get();
		
		$orchestra_main = MainLabelOrders::where('uvc',$heat_transfer->uvc)->where('status','!=','Order Cancelled')->where('season_code',$heat_transfer->season_code)->select('*')->get();
		
		$orchestra_wash = WashLabelOrders::where('uvc',$heat_transfer->uvc)->where('status','!=','Order Cancelled')->where('season_code',$heat_transfer->season_code)->select('*')->get();
		
		$orchestra_special = SpecialTagOrders::where('uvc',$heat_transfer->uvc)->where('status','!=','Order Cancelled')->where('season_code',$heat_transfer->season_code)->select('*')->get();
		
		$orchestra_heat = HeatTransferOrders::where('uvc',$heat_transfer->uvc)->where('status','!=','Order Cancelled')->where('season_code',$heat_transfer->season_code)->select('*')->get();
		
		$orchestra_size = SizeLabelOrders::where('uvc',$heat_transfer->uvc)->where('status','!=','Order Cancelled')->where('season_code',$heat_transfer->season_code)->select('*')->get();
		
		$update_po_disable = count($orchestra_hang) + count($orchestra_main) + count($orchestra_wash) + count($orchestra_special) + count($orchestra_heat) + count($orchestra_size);
		
		$result = response()->json(['error'=> false,'message' =>'success','update_po_disable' => $update_po_disable],200);
		
		return $result;
	}
	/**
     * This Function is used to remove Special Tag Details.
     *
     * @return Void
     */
	public function removeSizeLabel(Request $request)
	{
		$id =$request->id;
		
	    SizeLabelOrders::where('id',$id)
                ->update([
                    'status' => "Order Cancelled"
                ]);
		
		$size_label = SizeLabelOrders::where('id',$id)->select('*')->first();
        
        SizeLabelOrders::where('uvc',$size_label->uvc)
                ->update([
                    'status' => "Order Cancelled"
                ]);
		
		$orchestra_hang = HangTagOrders::where('uvc',$size_label->uvc)->where('status','!=','Order Cancelled')->where('season_code',$size_label->season_code)->select('*')->get();
		
		$orchestra_main = MainLabelOrders::where('uvc',$size_label->uvc)->where('status','!=','Order Cancelled')->where('season_code',$size_label->season_code)->select('*')->get();
		
		$orchestra_wash = WashLabelOrders::where('uvc',$size_label->uvc)->where('status','!=','Order Cancelled')->where('season_code',$size_label->season_code)->select('*')->get();
		
		$orchestra_special = SpecialTagOrders::where('uvc',$size_label->uvc)->where('status','!=','Order Cancelled')->where('season_code',$size_label->season_code)->select('*')->get();
		
		$orchestra_heat = HeatTransferOrders::where('uvc',$size_label->uvc)->where('status','!=','Order Cancelled')->where('season_code',$size_label->season_code)->select('*')->get();
		
		$orchestra_size = SizeLabelOrders::where('uvc',$size_label->uvc)->where('status','!=','Order Cancelled')->where('season_code',$size_label->season_code)->select('*')->get();
		
		$update_po_disable = count($orchestra_hang) + count($orchestra_main) + count($orchestra_wash) + count($orchestra_special) + count($orchestra_heat) + count($orchestra_size);
		
		$result = response()->json(['error'=> false,'message' =>'success','update_po_disable' => $update_po_disable],200);
		
		return $result;
	}
	
	/**
     * This Function is used to remove Standard Stickers.
     *
     * @return Void
     */
	public function removeStandardStickers(Request $request)
	{
        $carton_id=$request->carton_id;
        $poly_id=$request->poly_id;
		
        StandardCartonStickers::where('id',$carton_id)
        ->update([
            'status' => "Order Cancelled"
        ]);

        StandardPolyStickers::where('id',$poly_id)
        ->update([
            'status' => "Order Cancelled"
        ]);
		
		$result = response()->json(['error'=> false,'message' =>'success'],200);
		
		return $result;
    }
     /**
     * This Function is used to remove Solid Stickers.
     *
     * @return Void
     */
	public function removeSolidStickers(Request $request)
	{
		$carton_id=$request->carton_id;
        $poly_id=$request->poly_id;
		
	    SolidCartonStickerOrders::where('id',$carton_id)
                ->update([
                    'status' => "Order Cancelled"
                ]);

        SolidPolyStickerOrders::where('id',$poly_id)
                ->update([
                    'status' => "Order Cancelled"
                ]);
		
		$result = response()->json(['error'=> false,'message' =>'success'],200);
		
		return $result;
	}
	
	/**
     * This Function is used to save the Heat Transfer Details.
     *
     * @return Void
     */
	
    public function setHeatTransfer(Request $request)
    {
        //$delivery_date = $request->delivery_date;
        $template_id = $request->template_id;
        $tot_qty_table = explode(',',$request->tot_qty_table);
        $uvc_tco = explode(',',$request->uvc_tco);
        $size = explode(',',$request->size);
        $size_id = explode(',',$request->size_id);
        $main_id = explode(',',$request->main_id);
        $uvc =$request->uvc;
        $parcel_id =$request->parcel_id;
        $colour = $request->colour;
        $season_code =$request->season_code;
        $id =$request->id;
		$factory = DB::table("orchestra_parcel_details")->join('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('UVC',$uvc)->select('customers.*')->get();
		 
		if(count($factory) > 0)
		{
			
			$pi_required=$factory{0}->pi_required;
			if($pi_required == 1)
			{
				$pi_status="PI Approval Pending";
				if($factory{0}->po_required == 1)
				{
					$po_status="PO Pending";
				}
				else
				{
					$po_status="";
				}
				
			}
			else
			{
				$po_status="PO Pending";
				$pi_status="";
			}
			$status="Order Approved";
		}
		else
		{
			$status="";
			$po_status="";
			$pi_status="";
		}
		
        if($id != "null")
        {
            // get quantity_table ids.
            $quantity_table_ids = DB::table('orchestra_heat_transfer_orders_qty')->select('id')->where('oht_id',$id)->get();
            HeatTransferOrders::where('id',$id)
                ->update([
                    'uvc' => $uvc,
                    'template_id'      => $template_id,
                    'svg_path'    => "", 
                    'status'    => $status,
                    'orchestra_parcel_details_id'    => $parcel_id,
                    'season_code'    => $season_code,
                    'customer_id'    => $factory{0}->id,
                    'pi_status'           => $pi_status,
                    'po_status'           => $po_status
                ]);
            $deleted = HeatTransferOrdersQty::where('oht_id', '=', $id)
                        ->delete();
            $i=0;
            foreach($tot_qty_table as $value)
            {
                        HeatTransferOrdersQty::create([
                                    'oht_id' => $id,
                                    'size'      => $size[$i],
                                    'uvc_tco'      => $uvc_tco[$i],
                                    'parcel_id'      => $main_id[$i],
                                    'quantity'    => $value
                            ]);
                $i++;
            }
        
            DB::table("orchestra_parcel_details")->where('UVC',$uvc)
                    ->update([
                        'order_status' => "Order Placed"

            ]);

            // fetch erp_order_details table details for each quantity_table ids.
            foreach($quantity_table_ids as $quantity_table_id)
            {
                $quantity_id = $quantity_table_id->id;
                $erp_order_details[] = DB::table('erp_order_details')->where('order_qnty_table_name','ilike', 'orchestra_heat_transfer_orders_qty')->join('erp_status', 'erp_status.id', 'erp_order_details.status')->where('order_qnty_table_id','=', $quantity_id)->select('erp_order_details.id','erp_status.status_name', 'erp_order_details.erp_order_id')->first();
            }
        
            if(!is_null($erp_order_details[0]))
            {
                $erp_details_status =  $erp_order_details[0]->status_name;
                $erp_order_id =  $erp_order_details[0]->erp_order_id;
                
                foreach($erp_order_details as $erp_order_detail){
                    // delete from history table and insert new rows.
                    $history_table_id =  DB::table('history')->where('erp_order_details_id',$erp_order_detail->id)->value('id');
                    DB::table('event_history')->where('history_id',$history_table_id)->delete();
                    DB::table('history')->where('id',$history_table_id)->delete();
                    // delete from erp_order_details table
                    DB::table('erp_order_details')->where('id',$erp_order_detail->id)->delete();
                }
                
                /** @var array [Pass array of values to updateOrchestraErpOrderDetails function] */
                $data_array = [];   
                $orchestra_buyer_id               = DB::table('buyers')->select('id')->where('buyer_name','=','Orchestra')->first();
                $data_array['main_table']         = 'orchestra_heat_transfer_orders';
                $data_array['main_table_id']      = $id;
                $data_array['quantity_table']     = 'orchestra_heat_transfer_orders_qty';
                $data_array['quantity_id_field']  = 'oht_id';
                $data_array['buyer_id']           = $orchestra_buyer_id->id;
                $data_array['erp_details_status'] = $erp_details_status;
                $data_array['erp_order_id']       = $erp_order_id;

                // update erp_order_details and erp_orders table 
                $result = $this->edit_erp_obj->updateOrchestraErpOrderDetails($data_array);
            }
            else
            {
                $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
        }
        else
        {
			foreach($colour as $value1)
            {
             	$inserted = HeatTransferOrders::create([
                                    'uvc' => $uvc,
									'template_id'      => $template_id,
									'svg_path'    => "", 
									'status'    => $status,
									'orchestra_parcel_details_id'    => $parcel_id,
									'season_code'    => $season_code,
									'customer_id'    => $factory{0}->id,
									'pi_status'           => $pi_status,
									'po_status'           => $po_status,
					 				'color_code'           => $value1['Color_Code'],
					 				'template_type'       => "single"
                         ]);
				$i=0;
				foreach($tot_qty_table as $value)
				{
					if($value1['Color_Code'] == $size_id[$i])
					{
					 	HeatTransferOrdersQty::create([
										'oht_id' => $inserted->id,
										'size'      => $size[$i],
										'uvc_tco'      => $uvc_tco[$i],
										'parcel_id'      => $main_id[$i],
										'quantity'    => $value
							 ]);
					}
					$i++;
				}
			}
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)
                        ->update([
                          'order_status' => "Order Placed"

            ]);

            $result = response()->json(['error'=> false,'message' =>'success','id'=>$inserted->id],200);
        }
        return $result;
           
    }
	
	/**
     * This Function is used to save the Main Label Details.
     *
     * @return Void
     */
	
    public function setMainLabel(Request $request)
    {
        //$delivery_date = $request->delivery_date;
        $template_id = $request->template_id;
        $tot_qty_table = $request->tot_qty_table;
        $uvc_tco = explode(',',$request->uvc_tco);
        $size = explode(',',$request->size);
        $uvc =$request->uvc;
        $parcel_id =$request->parcel_id;
        $season_code =$request->season_code;
        $colour =$request->colour;
        $id =$request->id;
		$factory = DB::table("orchestra_parcel_details")->join('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('UVC',$uvc)->select('customers.*')->get();
		//print_r($factory);
		if(count($factory) > 0)
		{
			
			$pi_required=$factory{0}->pi_required;
			if($pi_required == 1)
			{
				$pi_status="PI Approval Pending";
				if($factory{0}->po_required == 1)
				{
					$po_status="PO Pending";
				}
				else
				{
					$po_status="";
				}
				
			}
			else
			{
				$po_status="PO Pending";
				$pi_status="";
			}
			$status="Order Approved";
		}
		else
		{
			$status="";
			$po_status="";
			$pi_status="";
		}
		
        if($id != "null")
        {
            // get quantity_table ids.
            $quantity_table_ids = DB::table('orchestra_main_label_orders_qty')->select('id')->where('oml_id',$id)->get();
            MainLabelOrders::where('id',$id)
                ->update([
                    'uvc' => $uvc,
                    'template_id'      => $template_id,
                    'svg_path'    => "", 
                    'status'    => $status,
                    'orchestra_parcel_details_id'    => $parcel_id,
                    'season_code'    => $season_code,
                    'customer_id'    => $factory{0}->id,
                    'pi_status'           => $pi_status,
                    'po_status'           => $po_status,
                ]);
            $deleted = MainLabelOrdersQty::where('oml_id', '=', $id)
                        ->delete();
            
            
                    MainLabelOrdersQty::create([
                                    'oml_id' => $id,
                                    'size'      => "",
                                    'uvc_tco'      => "",
                                    'quantity'    => $tot_qty_table
                            ]);
        
            DB::table("orchestra_parcel_details")->where('UVC',$uvc)
                    ->update([
                        'order_status' => "Order Placed"

            ]);

            // fetch erp_order_details table details for each quantity_table ids.
            foreach($quantity_table_ids as $quantity_table_id)
            {
                $quantity_id = $quantity_table_id->id;
                $erp_order_details[] = DB::table('erp_order_details')->where('order_qnty_table_name','ilike', 'orchestra_main_label_orders_qty')->join('erp_status', 'erp_status.id', 'erp_order_details.status')->where('order_qnty_table_id','=', $quantity_id)->select('erp_order_details.id','erp_status.status_name', 'erp_order_details.erp_order_id')->first();
            }
        
            if(!is_null($erp_order_details[0]))
            {
                $erp_details_status =  $erp_order_details[0]->status_name;
                $erp_order_id =  $erp_order_details[0]->erp_order_id;
                
                foreach($erp_order_details as $erp_order_detail){
                    // delete from history table and insert new rows.
                    $history_table_id =  DB::table('history')->where('erp_order_details_id',$erp_order_detail->id)->value('id');
                    DB::table('event_history')->where('history_id',$history_table_id)->delete();
                    DB::table('history')->where('id',$history_table_id)->delete();
                    // delete from erp_order_details table
                    DB::table('erp_order_details')->where('id',$erp_order_detail->id)->delete();
                }
                
                /** @var array [Pass array of values to updateOrchestraErpOrderDetails function] */
                $data_array = [];   
                $orchestra_buyer_id               = DB::table('buyers')->select('id')->where('buyer_name','=','Orchestra')->first();
                $data_array['main_table']         = 'orchestra_main_label_orders';
                $data_array['main_table_id']      = $id;
                $data_array['quantity_table']     = 'orchestra_main_label_orders_qty';
                $data_array['quantity_id_field']  = 'oml_id';
                $data_array['buyer_id']           = $orchestra_buyer_id->id;
                $data_array['erp_details_status'] = $erp_details_status;
                $data_array['erp_order_id']       = $erp_order_id;

                // update erp_order_details and erp_orders table 
                $result = $this->edit_erp_obj->updateOrchestraErpOrderDetails($data_array);
            }
            else
            {
                $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
        }
        else
        {
			//print_r($colour[0]);
			$i=0;
			foreach($tot_qty_table as $value)
            {
             	$inserted = MainLabelOrders::create([
                                    'uvc' => $uvc,
									'template_id'      => $template_id,
									'svg_path'    => "", 
									'status'    => $status,
									'orchestra_parcel_details_id'    => $parcel_id,
									'season_code'    => $season_code,
									'color_code'    => $colour[$i]['Color_Code'],
									'customer_id'    => $factory{0}->id,
									'pi_status'           => $pi_status,
									'po_status'           => $po_status,
					 				'template_type'       => "ratio"
                         ]);
           
                 MainLabelOrdersQty::create([
                                    'oml_id' => $inserted->id,
                                    'size'      => "",
                                    'uvc_tco'      => "",
                                    'quantity'    => $value
                         ]);
                $i++;
            }
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)
                        ->update([
                          'order_status' => "Order Placed"

            ]);

            $result = response()->json(['error'=> false,'message' =>'success','id'=>$inserted->id],200);
        }

        return $result;
           
    }
	/**
     * This Function is used to save the Size Label Details.
     *
     * @return Void
     */
	
    public function setSizeLabel(Request $request)
    {
        //$delivery_date = $request->delivery_date;
        $template_id = $request->template_id;
        $tot_qty_table = explode(',',$request->tot_qty_table);
        $uvc_tco = explode(',',$request->uvc_tco);
        $size = explode(',',$request->size);
        $size_id = explode(',',$request->size_id);
        $main_id = explode(',',$request->main_id);
        $uvc =$request->uvc;
        $parcel_id =$request->parcel_id;
        $colour = $request->colour;
        $season_code =$request->season_code;
        $id =$request->id;
		$factory = DB::table("orchestra_parcel_details")->join('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('UVC',$uvc)->select('customers.*')->get();
		//print_r($factory);
		if(count($factory) > 0)
		{
			
			$pi_required=$factory{0}->pi_required;
			if($pi_required == 1)
			{
				$pi_status="PI Approval Pending";
				if($factory{0}->po_required == 1)
				{
					$po_status="PO Pending";
				}
				else
				{
					$po_status="";
				}
				
			}
			else
			{
				$po_status="PO Pending";
				$pi_status="";
			}
			$status="Order Approved";
		}
		else
		{
			$status="";
			$po_status="";
			$pi_status="";
		}
		
        if($id != "null")
        {

            // get quantity_table ids.
            $quantity_table_ids = DB::table('orchestra_size_label_orders_qty')->select('id')->where('osl_id',$id)->get();
            SizeLabelOrders::where('id',$id)
                ->update([
                    'uvc' => $uvc,
                    'template_id'      => $template_id,
                    'svg_path'    => "", 
                    'status'    => $status,
                    'orchestra_parcel_details_id'    => $parcel_id,
                    'season_code'    => $season_code,
                    'customer_id'    => $factory{0}->id,
                    'pi_status'           => $pi_status,
                    'po_status'           => $po_status
                ]);
            $deleted = SizeLabelOrdersQty::where('osl_id', '=', $id)
                        ->delete();
            $i=0;
            foreach($tot_qty_table as $value)
            {
                    SizeLabelOrdersQty::create([
                                    'osl_id' => $id,
                                    'size'      => $size[$i],
                                    'uvc_tco'      => $uvc_tco[$i],
                                    'parcel_id'      => $main_id[$i],
                                    'quantity'    => $value
                            ]);
                $i++;
            }
        
            DB::table("orchestra_parcel_details")->where('UVC',$uvc)
                    ->update([
                        'order_status' => "Order Placed"

            ]);

            // fetch erp_order_details table details for each quantity_table ids.
            foreach($quantity_table_ids as $quantity_table_id)
            {
                $quantity_id = $quantity_table_id->id;
                $erp_order_details[] = DB::table('erp_order_details')->where('order_qnty_table_name','ilike', 'orchestra_size_label_orders_qty')->join('erp_status', 'erp_status.id', 'erp_order_details.status')->where('order_qnty_table_id','=', $quantity_id)->select('erp_order_details.id','erp_status.status_name', 'erp_order_details.erp_order_id')->first();
            }
        
            if(!is_null($erp_order_details[0]))
            {
                $erp_details_status =  $erp_order_details[0]->status_name;
                $erp_order_id =  $erp_order_details[0]->erp_order_id;
                
                foreach($erp_order_details as $erp_order_detail){
                    // delete from history table and insert new rows.
                    $history_table_id =  DB::table('history')->where('erp_order_details_id',$erp_order_detail->id)->value('id');
                    DB::table('event_history')->where('history_id',$history_table_id)->delete();
                    DB::table('history')->where('id',$history_table_id)->delete();
                    // delete from erp_order_details table
                    DB::table('erp_order_details')->where('id',$erp_order_detail->id)->delete();
                }
                
                /** @var array [Pass array of values to updateOrchestraErpOrderDetails function] */
                $data_array = [];   
                $orchestra_buyer_id               = DB::table('buyers')->select('id')->where('buyer_name','=','Orchestra')->first();
                $data_array['main_table']         = 'orchestra_size_label_orders';
                $data_array['main_table_id']      = $id;
                $data_array['quantity_table']     = 'orchestra_size_label_orders_qty';
                $data_array['quantity_id_field']  = 'osl_id';
                $data_array['buyer_id']           = $orchestra_buyer_id->id;
                $data_array['erp_details_status'] = $erp_details_status;
                $data_array['erp_order_id']       = $erp_order_id;

                // update erp_order_details and erp_orders table 
                $result = $this->edit_erp_obj->updateOrchestraErpOrderDetails($data_array);
            }
            else
            {
                $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
        }
        else
        {
			foreach($colour as $value1)
            {
             $inserted = SizeLabelOrders::create([
                                    'uvc' => $uvc,
									'template_id'      => $template_id,
									'svg_path'    => "", 
									'status'    => $status,
									'orchestra_parcel_details_id'    => $parcel_id,
									'season_code'    => $season_code,
									'customer_id'    => $factory{0}->id,
									'pi_status'           => $pi_status,
									'po_status'           => $po_status,
					 				'color_code'           => $value1['Color_Code'],
					 				'template_type'       => "single"
                         ]);
            $i=0;
            foreach($tot_qty_table as $value)
            {
				if($value1['Color_Code'] == $size_id[$i])
				{
                 		SizeLabelOrdersQty::create([
                                    'osl_id' => $inserted->id,
                                    'size'      => $size[$i],
                                    'uvc_tco'      => $uvc_tco[$i],
									'parcel_id'      => $main_id[$i],
                                    'quantity'    => $value
                         ]);
				}
                $i++;
            }
			}
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)
                        ->update([
                          'order_status' => "Order Placed"

            ]);

            $result = response()->json(['error'=> false,'message' =>'success','id'=>$inserted->id],200);
        }

        return $result; 
    }
	
	/**
     * This Function is used to save the Special Tag Details.
     *
     * @return Void
     */
	
    public function setSpecialTag(Request $request)
    {
        $delivery_date = $request->delivery_date;
        $template_front_id = $request->template_front_id;
        $tot_qty_table = $request->tot_qty_table;
		//$final_qty =$request->tot_qty_table;
        $uvc_tco = explode(',',$request->uvc_tco);
        $size = explode(',',$request->size);
        $parcel_id =$request->parcel_id;
        $season_code =$request->season_code;
		$colour =$request->colour;
        $uvc =$request->uvc;
        $id =$request->id;
		$factory = DB::table("orchestra_parcel_details")->join('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('UVC',$uvc)->select('customers.*')->get();
		if(count($factory) > 0)
		{
			
			$pi_required=$factory{0}->pi_required;
			if($pi_required == 1)
			{
				$pi_status="PI Approval Pending";
				if($factory{0}->po_required == 1)
				{
					$po_status="PO Pending";
				}
				else
				{
					$po_status="";
				}
				
			}
			else
			{
				$po_status="PO Pending";
				$pi_status="";
			}
			$status="Order Approved";
		}
		else
		{
			$status="";
			$po_status="";
			$pi_status="";
		}
        if($id != "null")
        {
            // get quantity_table ids.
            $quantity_table_ids = DB::table('orchestra_special_tag_orders_qty')->select('id')->where('ost_id',$id)->get();

            SpecialTagOrders::where('id',$id)
                ->update([
                    'uvc' => $uvc,
                    'template_id'      => $template_front_id, 
                    'svg_path'    => "",
                    'final_qty'    => $tot_qty_table, 
                    'customer_id'    => $factory{0}->id,
                    'status'    => $status,
                    'orchestra_parcel_details_id'    => $parcel_id,
                    'season_code'    => $season_code,
                    'pi_status'           => $pi_status,
                    'po_status'           => $po_status,
                ]);
            $deleted = SpecialTagOrdersQty::where('ost_id', '=', $id)
                        ->delete();
            
            SpecialTagOrdersQty::create([
                    'ost_id' => $id,
                    //'size'      => $size[$i],
                    //'uvc_tco'      => $uvc_tco[$i],
                    'quantity'    => $tot_qty_table
                ]);
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)
                        ->update([
                          'order_status' => "Order Placed"

            ]);

            // fetch erp_order_details table details for each quantity_table ids.
            foreach($quantity_table_ids as $quantity_table_id)
            {
                $quantity_id = $quantity_table_id->id;
                $erp_order_details[] = DB::table('erp_order_details')->where('order_qnty_table_name','ilike', 'orchestra_special_tag_orders_qty')->join('erp_status', 'erp_status.id', 'erp_order_details.status')->where('order_qnty_table_id','=', $quantity_id)->select('erp_order_details.id','erp_status.status_name', 'erp_order_details.erp_order_id')->first();
            }
        
            if(!is_null($erp_order_details[0]))
            {
                $erp_details_status =  $erp_order_details[0]->status_name;
                $erp_order_id =  $erp_order_details[0]->erp_order_id;
                
                foreach($erp_order_details as $erp_order_detail){
                    // delete from history table and insert new rows.
                    $history_table_id =  DB::table('history')->where('erp_order_details_id',$erp_order_detail->id)->value('id');
                    DB::table('event_history')->where('history_id',$history_table_id)->delete();
                    DB::table('history')->where('id',$history_table_id)->delete();
                    // delete from erp_order_details table
                    DB::table('erp_order_details')->where('id',$erp_order_detail->id)->delete();
                }
                
                /** @var array [Pass array of values to updateOrchestraErpOrderDetails function] */
                $data_array = [];   
                $orchestra_buyer_id               = DB::table('buyers')->select('id')->where('buyer_name','=','Orchestra')->first();
                $data_array['main_table']         = 'orchestra_special_tag_orders';
                $data_array['main_table_id']      = $id;
                $data_array['quantity_table']     = 'orchestra_special_tag_orders_qty';
                $data_array['quantity_id_field']  = 'ost_id';
                $data_array['buyer_id']           = $orchestra_buyer_id->id;
                $data_array['erp_details_status'] = $erp_details_status;
                $data_array['erp_order_id']       = $erp_order_id;

                // update erp_order_details and erp_orders table 
                $result = $this->edit_erp_obj->updateOrchestraErpOrderDetails($data_array);
            }
            else
            {
                $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
        }
        else
        {
			$i=0;
            foreach($tot_qty_table as $value)
            {
             	$inserted = SpecialTagOrders::create([
                                    'uvc' => $uvc,
                                    'template_id'      => $template_front_id, 
                                    'svg_path'    => "", 
				 					'final_qty'    => $value,
                        			'customer_id'    => $factory{0}->id,
                                    'status'    => $status,
									'orchestra_parcel_details_id'    => $parcel_id,
									'color_code'    => $colour[$i]['Color_Code'],
									'season_code'    => $season_code,
				 					'pi_status'           => $pi_status,
				 					'po_status'           => $po_status,
					 				'template_type'       => "ratio"
                         ]);
            
                 SpecialTagOrdersQty::create([
                                    'ost_id' => $inserted->id,
                                    //'size'      => $size[$i],
                                    //'uvc_tco'      => $uvc_tco[$i],
                                    'quantity'    => $value
                         ]);
                $i++;
            }
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)
                        ->update([
                          'order_status' => "Order Placed"

            ]);
            $result = response()->json(['error'=> false,'message' =>'success','id'=>$inserted->id],200);
        }

        return $result;
           
    } 
	
	/**
     * This Function is used to save the Solid Sticker Details.
     *
     * @return Void
     */
	
    public function setSolidStickers(Request $request)
    {
       // $delivery_date = $request->delivery_date;
        $carton_template_id = $request->carton_template_id; 
        $polybag_template_id = $request->polybag_template_id;
        $tot_qty_poly = explode(',',$request->tot_qty_poly);
        $tot_qty_carton = explode(',',$request->tot_qty_carton); 
        $size = explode(',',$request->size);
        $barcode = explode(',',$request->barcode);
        $uvc_tco = explode(',',$request->uvc_tco);
        $uvc =$request->uvc;
        $parcel_id =$request->parcel_id;
        $season_code =$request->season_code;
        $color_code =$request->color_code;
        $height =$request->height; 
        $width =$request->width; 
        $age =$request->age;
        $po =$request->po;
        $carton_id =$request->carton_id; 
        $poly_id =$request->poly_id; 
        $group_id =$request->group_id;
		$factory = DB::table("orchestra_parcel_details")->join('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('UVC',$uvc)->select('customers.*')->get();
		if(count($factory) > 0)
		{
			
			$pi_required=$factory{0}->pi_required;
			if($pi_required == 1)
			{
				$pi_status="PI Approval Pending";
				if($factory{0}->po_required == 1)
				{
					$po_status="PO Pending";
				}
				else
				{
					$po_status="";
				}
				
			}
			else
			{
				$po_status="PO Pending";
				$pi_status="";
			}
			$status="Order Approved";
		}
		else
		{
			$status="";
			$po_status="";
			$pi_status="";
		}
        if($poly_id != "null")
        {
            SolidPolyStickerOrders::where('id',$poly_id)
                   ->update([
                        'uvc'              => $uvc,
                        'template_id'      => $polybag_template_id,
                        'po'               => $po,
                        'height'           => $height,  
                        'customer_id'    => $factory{0}->id,
                        'svg_path'         => "", 
                        'status'           => $status,
						'orchestra_parcel_details_id'    => $parcel_id,
						'season_code'    => $season_code,
						'color_code'    => $color_code,
					    'pi_status'           => $pi_status,
				 		'po_status'           => $po_status,
                        'group_id'    => $age,
                  ]);
            
                 $deleted = SolidPolyStickerOrdersQty::where('ospbs_id', '=', $poly_id)
                            ->delete();
            
                $j=0;
                foreach($tot_qty_poly as $value)
                {
                     SolidPolyStickerOrdersQty::create([
                                        'ospbs_id' => $poly_id,
                                        'size'      => $size[$j],
                                        'barcode'      => $barcode[$j],
                                        'uvc_tco'      => $uvc_tco[$j],
                                        'quantity'    => $value,
                                        'quantity_sets'    => $value
                             ]);
                    $j++;
                }
			
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)->where('BuyerPONo',$po)
                        ->update([
                          'sticker_order_status' => "Order Placed"

            ]);
			$insert_poly_id = "";
        }
        else
        {
              $inserted_poly = SolidPolyStickerOrders::create([
                                'uvc' => $uvc,
                                'template_id'      => $polybag_template_id,
                                'po'               => $po,
                                'height'           => $height,
				  				'customer_id'    => $factory{0}->id, 
                                'svg_path'    => "", 
                                'status'    => $status,
								'orchestra_parcel_details_id'    => $parcel_id,
								'season_code'    => $season_code,
								'color_code'    => $color_code,
				  				'pi_status'           => $pi_status,
				 				'po_status'           => $po_status,
                                'group_id'    => $age,
					 			'template_type'       => "single"
                     ]);
                $j=0;
                foreach($tot_qty_poly as $value)
                {
                     SolidPolyStickerOrdersQty::create([
                                        'ospbs_id' => $inserted_poly->id,
                                        'size'      => $size[$j],
                                        'barcode'      => $barcode[$j],
                                        'uvc_tco'      => $uvc_tco[$j],
                                        'quantity'    => $value,
                                        'quantity_sets'    => $value
                             ]);
                    $j++;
                }
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)->where('BuyerPONo',$po)
                        ->update([
                          'sticker_order_status' => "Order Placed"

            ]);
			$insert_poly_id = $inserted_poly->id;
        }
        if($carton_id != "null")
        {
              SolidCartonStickerOrders::where('id',$carton_id)
                   ->update([
                        'uvc'              => $uvc,
                        'template_id'      => $carton_template_id,
                        'po'               => $po,
                        'height'           => $height,
					   	'width'           => $width,
                        'customer_id'    => $factory{0}->id,
                        'svg_path'         => "", 
                        'status'           => $status,
						'orchestra_parcel_details_id'    => $parcel_id,
						'season_code'    => $season_code,
						'color_code'    => $color_code,
					    'pi_status'           => $pi_status,
				 		'po_status'           => $po_status,
                        'group_id'    => $age,
                  ]);
                $deleted = SolidCartonStickerOrdersQty::where('oscs_id', '=', $carton_id)
                            ->delete();
                $i=0;
                foreach($tot_qty_carton as $value)
                {
                     SolidCartonStickerOrdersQty::create([
                                        'oscs_id' => $carton_id,
                                        'size'      => $size[$i],
                                        'barcode'      => $barcode[$i],
                                        'uvc_tco'      => $uvc_tco[$i],
                                        'quantity'      => $value,
                                        'quantity_sets'    => $value
                             ]);
                    $i++;
                }
            
            DB::table("orchestra_parcel_details")->where('UVC',$uvc)->where('BuyerPONo',$po)
                        ->update([
                          'sticker_order_status' => "Order Placed"

            ]);
			$insert_carton_id = "";
        }
        else
        {
               $inserted = SolidCartonStickerOrders::create([
                                'uvc' => $uvc,
                                'template_id'      => $carton_template_id,
                                'po'               => $po,
                                'height'           => $height, 
				   				'width'           => $width,
                        		'customer_id'    => $factory{0}->id,
                                'svg_path'    => "", 
                                'status'    => $status,
								'orchestra_parcel_details_id'    => $parcel_id,
								'season_code'    => $season_code,
								'color_code'    => $color_code,
				   				'pi_status'           => $pi_status,
				 				'po_status'           => $po_status,
                                'group_id'    => $age,
					 			'template_type'       => "single"
                     ]);
                $i=0;
                foreach($tot_qty_carton as $value)
                {
                     SolidCartonStickerOrdersQty::create([
                                        'oscs_id' => $inserted->id,
                                        'size'      => $size[$i],
                                        'barcode'      => $barcode[$i],
                                        'uvc_tco'      => $uvc_tco[$i],
                                        'quantity'    => $value,
                                        'quantity_sets'    => $value
                             ]);
                    $i++;
                }   
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)->where('BuyerPONo',$po)
                        ->update([
                          'sticker_order_status' => "Order Placed"

            ]);
			$insert_carton_id = $inserted->id;
        }
        return response()->json(['error'=> false,'message' =>'success','poly_id' => $insert_poly_id,'carton_id' => $insert_carton_id],200);

           
    }
	
	/**
     * This Function is used to save the Standard Sticker Details.
     *
     * @return Void
     */
	
    public function setStandardStickers(Request $request)
    {
        //$delivery_date = $request->delivery_date;
        $carton_template_id = $request->carton_template_id; 
        $size = explode(',',$request->size);
        $barcode = explode(',',$request->barcode);
        $uvc_tco = explode(',',$request->uvc_tco);
		$size_break = explode(',',$request->size_break);
        $polybag_template_id = $request->polybag_template_id;
        $uvc =$request->uvc;
        $parcel_id =$request->parcel_id;
        $season_code =$request->season_code;
        $color_code =$request->color_code;
        $height =$request->height; 
        $width =$request->width; 
        $age =$request->age;
        $po =$request->po;
        $carton_id =$request->carton_id; 
        $poly_id =$request->poly_id; 
        $group_id =$request->group_id;
        
        $no_of_carton =$request->no_of_carton;
        $total_polybag =$request->total_polybag;
        $final_order_qty_sets =$request->final_order_qty_sets;
        $order_qty =$request->order_qty;
        $final_order_qty =$request->final_order_qty;
		
		$factory = DB::table("orchestra_parcel_details")->join('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('UVC',$uvc)->select('customers.*')->get();
		if(count($factory) > 0)
		{
			
			$pi_required=$factory{0}->pi_required;
			if($pi_required == 1)
			{
				$pi_status="PI Approval Pending";
				if($factory{0}->po_required == 1)
				{
					$po_status="PO Pending";
				}
				else
				{
					$po_status="";
				}
				
			}
			else
			{
				$po_status="PO Pending";
				$pi_status="";
			}
			$status="Order Approved";
		}
		else
		{
			$status="";
			$po_status="";
			$pi_status="";
		}
        
        if($poly_id != "null")
        {
            StandardPolyStickers::where('id',$poly_id)
                   ->update([
                        'uvc'              => $uvc,
                        'template_id'      => $polybag_template_id,
                        'po'               => $po,
                        'height'           => $height, 
                        'customer_id'    => $factory{0}->id,
                        'no_of_carton'    => $no_of_carton, 
                        'total_polybag'    => $total_polybag, 
                        'final_order_qty_sets'    => $final_order_qty_sets, 
                        'order_qty'    => $order_qty, 
                        'final_order_qty'    => $final_order_qty, 
                        'svg_path'         => "", 
                        'status'           => $status,
						'orchestra_parcel_details_id'    => $parcel_id,
						'season_code'    => $season_code,
						'color_code'    => $color_code,
					   	'pi_status'           => $pi_status,
					   	'po_status'           => $po_status,
                        'group_id'    => $age,
                  ]);
			
					$deleted = StandardPolyStickersQty::where('ospbs_id', '=', $poly_id)
                            ->delete();
            
                 $j=0;
				 StandardPolyStickersQty::create([
									'ospbs_id' => $poly_id,
									'size'      => "",
									'barcode'      => $barcode[0],
									'uvc_tco'      => "",
									'size_break'      => "",
									'quantity'    => $final_order_qty,
									'quantity_sets'    => $final_order_qty
				 ]);

			DB::table("orchestra_parcel_details")->where('UVC',$uvc)->where('BuyerPONo',$po)
                        ->update([
                          'sticker_order_status' => "Order Placed"

            ]);
			$insert_poly_id = "";
        }
        else
        {
              $inserted_poly = StandardPolyStickers::create([
                                'uvc' => $uvc,
                                'template_id'      => $polybag_template_id,
                                'po'               => $po,
                                'height'           => $height, 
				  				'customer_id'    => $factory{0}->id,
                                'no_of_carton'    => $no_of_carton, 
                                'total_polybag'    => $total_polybag, 
                                'final_order_qty_sets'    => $final_order_qty_sets, 
                                'order_qty'    => $order_qty, 
                                'final_order_qty'    => $final_order_qty,
                                'svg_path'    => "", 
                                'status'    => $status,
								'orchestra_parcel_details_id'    => $parcel_id,
								'season_code'    => $season_code,
								'color_code'    => $color_code,
						  		'pi_status'           => $pi_status,
								'po_status'           => $po_status,
                                'group_id'    => $age,
					 			'template_type'       => "ratio"
                     ]);
				$j=0;
				StandardPolyStickersQty::create([
									'ospbs_id' => $inserted_poly->id,
									'size'      => "",
									'barcode'      => $barcode[0],
									'uvc_tco'      => "",
									'size_break'      => "",
									'quantity'    => $final_order_qty,
									'quantity_sets'    => $final_order_qty
				]);
			
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)->where('BuyerPONo',$po)
                        ->update([
                          'sticker_order_status' => "Order Placed"

            ]);
			$insert_poly_id = $inserted_poly->id;
                
        }
        if($carton_id != "null")
        {
              StandardCartonStickers::where('id',$carton_id)
                   ->update([
                        'uvc'              => $uvc,
                        'template_id'      => $carton_template_id,
                        'po'               => $po,
                        'height'           => $height,
					   	'width'           => $width,
                        'customer_id'    => $factory{0}->id,
                        'no_of_carton'    => $no_of_carton, 
                        'total_polybag'    => $total_polybag, 
                        'final_order_qty_sets'    => $final_order_qty_sets, 
                        'order_qty'    => $order_qty, 
                        'final_order_qty'    => $final_order_qty,
                        'svg_path'         => "", 
                        'status'           => $status,
						'orchestra_parcel_details_id'    => $parcel_id,
						'season_code'    => $season_code,
						'color_code'    => $color_code,
					    'pi_status'           => $pi_status,
					   	'po_status'           => $po_status,
                        'group_id'    => $age,
                  ]);
			
				$deleted = StandardCartonStickersQty::where('oscs_id', '=', $carton_id)
                            ->delete();
				 $j=0;
				 StandardCartonStickersQty::create([
									'oscs_id' => $carton_id,
									'size'      => "",
									'barcode'      => $barcode[0],
									'uvc_tco'      => "",
									'quantity'    => $final_order_qty_sets,
									'quantity_sets'    => $final_order_qty_sets
				 ]);
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)->where('BuyerPONo',$po)
                        ->update([
                          'sticker_order_status' => "Order Placed"

            ]);
			$insert_carton_id = "";
        }
        else
        {
               $inserted = StandardCartonStickers::create([
                                'uvc' => $uvc,
                                'template_id'      => $carton_template_id,
                                'po'               => $po,
                                'height'           => $height,
				   				'width'           => $width,
				   				'customer_id'    => $factory{0}->id,
                                'no_of_carton'    => $no_of_carton, 
                                'total_polybag'    => $total_polybag, 
                                'final_order_qty_sets'    => $final_order_qty_sets, 
                                'order_qty'    => $order_qty, 
                                'final_order_qty'    => $final_order_qty,
                                'svg_path'    => "", 
                                'status'    => $status,
								'orchestra_parcel_details_id'    => $parcel_id,
								'season_code'    => $season_code,
								'color_code'    => $color_code,
				   				'pi_status'           => $pi_status,
				   				'po_status'           => $po_status,
                                'group_id'    => $age,
					 			'template_type'       => "ratio"
                     ]);
			
				$j=0;
				StandardCartonStickersQty::create([
									'oscs_id' => $inserted->id,
									'size'      => "",
									'barcode'      => $barcode[0],
									'uvc_tco'      => "",
									'quantity'    => $final_order_qty_sets,
									'quantity_sets'    => $final_order_qty_sets
				]);
			
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)->where('BuyerPONo',$po)
                        ->update([
                          'sticker_order_status' => "Order Placed"

            ]);
			$insert_carton_id = $inserted->id;
        }
        return response()->json(['error'=> false,'message' =>'success','poly_id' => $insert_poly_id,'carton_id' => $insert_carton_id],200);

           
    }

	public function setSolidStickersOs(Request $request)
	{
        $tot_qty_carton = explode(',',$request->tot_qty_carton); 
        $size = explode(',',$request->size);
		$barcode = explode(',',$request->barcode);
        $uvc_tco = explode(',',$request->uvc_tco);
        $uvc =$request->uvc;
        $parcel_id =$request->parcel_id;
        $season_code =$request->season_code;
        $height =$request->height; 
        $width =$request->width; 
        $age =$request->age;
        $carton_id =$request->carton_id;
		
		$factory = DB::table("orchestra_parcel_details")->join('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('UVC',$uvc)->select('customers.*')->get();
		if(count($factory) > 0)
		{
			
			$pi_required=$factory{0}->pi_required;
			if($pi_required == 1)
			{
				$pi_status="PI Approval Pending";
				if($factory{0}->po_required == 1)
				{
					$po_status="PO Pending";
				}
				else
				{
					$po_status="";
				}
				
			}
			else
			{
				$po_status="PO Pending";
				$pi_status="";
			}
			$status="Order Approved";
		}
		else
		{
			$status="";
			$po_status="";
			$pi_status="";
        }
        
		// get quantity_table ids.
        $quantity_table_ids = DB::table('orchestra_solid_carton_sticker_orders_qty')->select('id')->where('oscs_id',$carton_id)->get();

		SolidCartonStickerOrders::where('id',$carton_id)
                   ->update([
                        'uvc'              => $uvc,
                        'height'           => $height,
					    'width'           => $width,
                        'customer_id'    => $factory{0}->id,
                        'svg_path'         => "", 
                        'status'           => $status,
						'orchestra_parcel_details_id'    => $parcel_id,
						'season_code'    => $season_code,
					    'pi_status'           => $pi_status,
				 		'po_status'           => $po_status,
                        'group_id'    => $age,
		  ]);
		$deleted = SolidCartonStickerOrdersQty::where('oscs_id', '=', $carton_id)
					->delete();
		$i=0;
		foreach($tot_qty_carton as $value)
		{
			 SolidCartonStickerOrdersQty::create([
								'oscs_id' => $carton_id,
								'size'      => $size[$i],
								'barcode'      => $barcode[$i],
								'uvc_tco'      => $uvc_tco[$i],
								'quantity'      => $value,
								'quantity_sets'    => $value
					 ]);
			$i++;
        }
        
        // fetch erp_order_details table details for each quantity_table ids.
        foreach($quantity_table_ids as $quantity_table_id)
        {
            $quantity_id = $quantity_table_id->id;
            $erp_order_details[] = DB::table('erp_order_details')->where('order_qnty_table_name','ilike', 'orchestra_solid_carton_sticker_orders_qty')->join('erp_status', 'erp_status.id', 'erp_order_details.status')->where('order_qnty_table_id','=', $quantity_id)->select('erp_order_details.id','erp_status.status_name', 'erp_order_details.erp_order_id')->first();
        }
    
        if(!is_null($erp_order_details[0]))
        {
            $erp_details_status =  $erp_order_details[0]->status_name;
            $erp_order_id =  $erp_order_details[0]->erp_order_id;
            
            foreach($erp_order_details as $erp_order_detail){
                // delete from history table and insert new rows.
                $history_table_id =  DB::table('history')->where('erp_order_details_id',$erp_order_detail->id)->value('id');
                DB::table('event_history')->where('history_id',$history_table_id)->delete();
                DB::table('history')->where('id',$history_table_id)->delete();
                // delete from erp_order_details table
                DB::table('erp_order_details')->where('id',$erp_order_detail->id)->delete();
            }
            
            /** @var array [Pass array of values to updateOrchestraErpOrderDetails function] */
            $data_array = [];   
            $orchestra_buyer_id               = DB::table('buyers')->select('id')->where('buyer_name','=','Orchestra')->first();
            $data_array['main_table']         = 'orchestra_solid_carton_sticker_orders';
            $data_array['main_table_id']      = $carton_id;
            $data_array['quantity_table']     = 'orchestra_solid_carton_sticker_orders_qty';
            $data_array['quantity_id_field']  = 'oscs_id';
            $data_array['buyer_id']           = $orchestra_buyer_id->id;
            $data_array['erp_details_status'] = $erp_details_status;
            $data_array['erp_order_id']       = $erp_order_id;

            // update erp_order_details and erp_orders table 
            $this->edit_erp_obj->updateOrchestraErpOrderDetails($data_array);
        }
        return response()->json(['error'=> false,'message' =>'success'],200);
    }
    

	public function setSolidPolyBagStickersOs(Request $request)
	{
        $tot_qty_poly = explode(',',$request->tot_qty_poly); 
        $size = explode(',',$request->size);
        $barcode = explode(',',$request->barcode);
        $uvc_tco = explode(',',$request->uvc_tco);
        $uvc =$request->uvc;
        $parcel_id =$request->parcel_id;
        $season_code =$request->season_code;
        $height =$request->height; 
        $age =$request->age;
        $poly_id =$request->poly_id;
        
        // get quantity_table ids.
        $quantity_table_ids = DB::table('orchestra_solid_poly_bag_sticker_orders_qty')->select('id')->where('ospbs_id',$poly_id)->get();
		
		SolidPolyStickerOrders::where('id',$poly_id)
                   ->update([
                        'height'           => $height,
                        'group_id'    => $age,
		  ]);
		$deleted = SolidPolyStickerOrdersQty::where('ospbs_id', '=', $poly_id)
					->delete();
		$i=0;
		foreach($tot_qty_poly as $value)
		{
			SolidPolyStickerOrdersQty::create([
								'ospbs_id' => $poly_id,
								'size'      => $size[$i],
								'barcode'      => $barcode[$i],
								'uvc_tco'      => $uvc_tco[$i],
								'quantity'      => $value,
								'quantity_sets'    => $value
					 ]);
			$i++;
        }
        
        // fetch erp_order_details table details for each quantity_table ids.
        foreach($quantity_table_ids as $quantity_table_id)
        {
            $quantity_id = $quantity_table_id->id;
            $erp_order_details[] = DB::table('erp_order_details')->where('order_qnty_table_name','ilike', 'orchestra_solid_poly_bag_sticker_orders_qty')->join('erp_status', 'erp_status.id', 'erp_order_details.status')->where('order_qnty_table_id','=', $quantity_id)->select('erp_order_details.id','erp_status.status_name', 'erp_order_details.erp_order_id')->first();
        }
    
        if(!is_null($erp_order_details[0]))
        {
            $erp_details_status =  $erp_order_details[0]->status_name;
            $erp_order_id =  $erp_order_details[0]->erp_order_id;
            
            foreach($erp_order_details as $erp_order_detail){
                // delete from history table and insert new rows.
                $history_table_id =  DB::table('history')->where('erp_order_details_id',$erp_order_detail->id)->value('id');
                DB::table('event_history')->where('history_id',$history_table_id)->delete();
                DB::table('history')->where('id',$history_table_id)->delete();
                // delete from erp_order_details table
                DB::table('erp_order_details')->where('id',$erp_order_detail->id)->delete();
            }
            
            /** @var array [Pass array of values to updateOrchestraErpOrderDetails function] */
            $data_array = [];   
            $orchestra_buyer_id               = DB::table('buyers')->select('id')->where('buyer_name','=','Orchestra')->first();
            $data_array['main_table']         = 'orchestra_solid_poly_bag_sticker_orders';
            $data_array['main_table_id']      = $poly_id;
            $data_array['quantity_table']     = 'orchestra_solid_poly_bag_sticker_orders_qty';
            $data_array['quantity_id_field']  = 'ospbs_id';
            $data_array['buyer_id']           = $orchestra_buyer_id->id;
            $data_array['erp_details_status'] = $erp_details_status;
            $data_array['erp_order_id']       = $erp_order_id;

            // update erp_order_details and erp_orders table 
            $this->edit_erp_obj->updateOrchestraErpOrderDetails($data_array);
        }
        return response()->json(['error'=> false,'message' =>'success'],200);
    }
    

	public function setStandardCartonStickersOs(Request $request)
	{
        $uvc =$request->uvc;
		$size = explode(',',$request->size);
        $barcode = explode(',',$request->barcode);
        $uvc_tco = explode(',',$request->uvc_tco);
        $parcel_id =$request->parcel_id;
        $season_code =$request->season_code;
        $height =$request->height; 
        $width =$request->width; 
        $age =$request->age;
        $carton_id =$request->carton_id;
        $no_of_carton =$request->no_of_carton;
        $total_polybag =$request->total_polybag;
        $final_order_qty_sets =$request->final_order_qty_sets;

		// get quantity_table ids.
        $quantity_table_ids = DB::table('orchestra_standard_carton_sticker_orders_qty')->select('id')->where('oscs_id',$carton_id)->get();

		StandardCartonStickers::where('id',$carton_id)
                   ->update([
                        'height'           => $height,
					    'width'           => $width,
                        'no_of_carton'    => $no_of_carton, 
                        'total_polybag'    => $total_polybag, 
                        'final_order_qty_sets'    => $final_order_qty_sets, 
                        'group_id'    => $age,
                  ]);
			
        $deleted = StandardCartonStickersQty::where('oscs_id', '=', $carton_id)
                    ->delete();
    
        $j=0;
		StandardCartonStickersQty::create([
						'oscs_id' => $carton_id,
						'size'      => "",
						'barcode'      => $barcode[0],
						'uvc_tco'      => "",
						'quantity'    => $final_order_qty_sets,
						'quantity_sets'    => $final_order_qty_sets
		]);

        // fetch erp_order_details table details for each quantity_table ids.
        foreach($quantity_table_ids as $quantity_table_id)
        {
            $quantity_id = $quantity_table_id->id;
            $erp_order_details[] = DB::table('erp_order_details')->where('order_qnty_table_name','ilike', 'orchestra_standard_carton_sticker_orders_qty')->join('erp_status', 'erp_status.id', 'erp_order_details.status')->where('order_qnty_table_id','=', $quantity_id)->select('erp_order_details.id','erp_status.status_name', 'erp_order_details.erp_order_id')->first();
        }
    
        if(!is_null($erp_order_details[0]))
        {
            $erp_details_status =  $erp_order_details[0]->status_name;
            $erp_order_id =  $erp_order_details[0]->erp_order_id;
            
            foreach($erp_order_details as $erp_order_detail){
                // delete from history table and insert new rows.
                $history_table_id =  DB::table('history')->where('erp_order_details_id',$erp_order_detail->id)->value('id');
                DB::table('event_history')->where('history_id',$history_table_id)->delete();
                DB::table('history')->where('id',$history_table_id)->delete();
                // delete from erp_order_details table
                DB::table('erp_order_details')->where('id',$erp_order_detail->id)->delete();
            }
            
            /** @var array [Pass array of values to updateOrchestraErpOrderDetails function] */
            $data_array = [];   
            $orchestra_buyer_id               = DB::table('buyers')->select('id')->where('buyer_name','=','Orchestra')->first();
            $data_array['main_table']         = 'orchestra_standard_carton_sticker_orders';
            $data_array['main_table_id']      = $carton_id;
            $data_array['quantity_table']     = 'orchestra_standard_carton_sticker_orders_qty';
            $data_array['quantity_id_field']  = 'oscs_id';
            $data_array['buyer_id']           = $orchestra_buyer_id->id;
            $data_array['erp_details_status'] = $erp_details_status;
            $data_array['erp_order_id']       = $erp_order_id;

            // update erp_order_details and erp_orders table 
            $this->edit_erp_obj->updateOrchestraErpOrderDetails($data_array);
        }
        return response()->json(['error'=> false,'message' =>'success'],200);
			
    }
    

	public function setStandardPolyBagStickersOs(Request $request)
	{
        $uvc =$request->uvc;
        $size = explode(',',$request->size);
        $barcode = explode(',',$request->barcode);
        $uvc_tco = explode(',',$request->uvc_tco);
        $parcel_id =$request->parcel_id;
        $season_code =$request->season_code;
        $height =$request->height; 
        $age =$request->age;
        $poly_id =$request->poly_id;
        $final_order_qty_sets =$request->final_order_qty_sets;

        // get quantity_table ids.
        $quantity_table_ids = DB::table('orchestra_standard_poly_bag_sticker_orders_qty')->select('id')->where('ospbs_id',$poly_id)->get();
		
		StandardPolyStickers::where('id',$poly_id)
                   ->update([
                        'final_order_qty_sets'    => $final_order_qty_sets, 
                        'group_id'    => $age,
                  ]);
			
        $deleted = StandardPolyStickersQty::where('ospbs_id', '=', $poly_id)
                ->delete();


         $j=0;
		 StandardPolyStickersQty::create([
							'ospbs_id' => $poly_id,
							'size'      => "",
							'barcode'      => $barcode[0],
							'uvc_tco'      => "",
							'quantity'    => $final_order_qty_sets,
							'quantity_sets'    => $final_order_qty_sets
		 ]);

        // fetch erp_order_details table details for each quantity_table ids.
        foreach($quantity_table_ids as $quantity_table_id)
        {
            $quantity_id = $quantity_table_id->id;
            $erp_order_details[] = DB::table('erp_order_details')->where('order_qnty_table_name','ilike', 'orchestra_standard_poly_bag_sticker_orders_qty')->join('erp_status', 'erp_status.id', 'erp_order_details.status')->where('order_qnty_table_id','=', $quantity_id)->select('erp_order_details.id','erp_status.status_name', 'erp_order_details.erp_order_id')->first();
        }
    
        if(!is_null($erp_order_details[0]))
        {
            $erp_details_status =  $erp_order_details[0]->status_name;
            $erp_order_id =  $erp_order_details[0]->erp_order_id;
            
            foreach($erp_order_details as $erp_order_detail){
                // delete from history table and insert new rows.
                $history_table_id =  DB::table('history')->where('erp_order_details_id',$erp_order_detail->id)->value('id');
                DB::table('event_history')->where('history_id',$history_table_id)->delete();
                DB::table('history')->where('id',$history_table_id)->delete();
                // delete from erp_order_details table
                DB::table('erp_order_details')->where('id',$erp_order_detail->id)->delete();
            }
            
            /** @var array [Pass array of values to updateOrchestraErpOrderDetails function] */
            $data_array = [];   
            $orchestra_buyer_id               = DB::table('buyers')->select('id')->where('buyer_name','=','Orchestra')->first();
            $data_array['main_table']         = 'orchestra_standard_poly_bag_sticker_orders';
            $data_array['main_table_id']      = $poly_id;
            $data_array['quantity_table']     = 'orchestra_standard_poly_bag_sticker_orders_qty';
            $data_array['quantity_id_field']  = 'ospbs_id';
            $data_array['buyer_id']           = $orchestra_buyer_id->id;
            $data_array['erp_details_status'] = $erp_details_status;
            $data_array['erp_order_id']       = $erp_order_id;

            // update erp_order_details and erp_orders table 
            $this->edit_erp_obj->updateOrchestraErpOrderDetails($data_array);
        }
        return response()->json(['error'=> false,'message' =>'success'],200);
	}
	
	/**
     * This Function is used to save the Wash Care Label Details.
     *
     * @return Void
     */
	
    public function setWashLabel(Request $request)
    {
        
        //$delivery_date = $request->delivery_date;
        $template_front_id = $request->template_front_id; 
        $template_name = $request->template_name;
        $tot_qty_table = explode(',',$request->tot_qty_table);
        $uvc_tco = explode(',',$request->uvc_tco);
        $barcode = explode(',',$request->barcode);
        $size = explode(',',$request->size);
        $uvc =$request->uvc;
        $po_select =$request->po_select;
        $id =$request->id;
		$card_flag =$request->card_flag;
        $parcel_id =$request->parcel_id;
        $season_code =$request->season_code;
        $washing_select = $request->washing_select; 
        $whitening_select = $request->whitening_select; 
        $dryer_select = $request->dryer_select; 
        $ironing_select= $request->ironing_select; 
        $cleaning_select= $request->cleaning_select; 
        $merchandisers= $request->merchandisers; 
        $made_in_select = $request->made_in_select; 
        $care_instruction_select = $request->care_instruction_select; 
		$color_code = $request->color_code; 
		
		$factory = DB::table("orchestra_parcel_details")->join('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('UVC',$uvc)->select('customers.*')->get();
		
		if(count($factory) > 0)
		{
			
			$pi_required=$factory{0}->pi_required;
			if($pi_required == 1)
			{
				$pi_status="PI Approval Pending";
				if($factory{0}->po_required == 1)
				{
					$po_status="PO Pending";
				}
				else
				{
					$po_status="";
				}
				
			}
			else
			{
				$po_status="PO Pending";
				$pi_status="";
			}
			
		}
		else
		{
			
			$po_status="";
			$pi_status="";
		}
		
        if($id != "null")
        {
			$po_wcl = WashLabelOrders::where('uvc',$uvc)->where('season_code',$season_code)->where('po',$po_select)->where('status','!=','Order Cancelled')->select('*')->get();

			
			if(count($po_wcl) > 0)
			{
				WashLabelOrders::where('id',$id)
                   ->update([
                        'uvc' => $uvc,
                        'template_id'      => $template_front_id,
                        //'merchandisers_id'      => $merchandisers,
                        'template_name'      => $template_name,
                        'washing_id'      => $washing_select,
                        'whitening_id'      => $whitening_select,
                        'dryer_id'      => $dryer_select,
                        'ironing_id'      => $ironing_select,
                        'cleaning_id'      => $cleaning_select,
                        'made_in_id'      => $made_in_select,
					    'color_code'      => $color_code,
					    'card_flag'      => $card_flag,
                        'po'    => $po_select,
                        'care_instruction_id'      => $care_instruction_select,
                        'customer_id'    => $factory{0}->id,
                        'svg_path'    => "", 
                        'status'    => "Buyer Approval Pending",
						'orchestra_parcel_details_id'    => $parcel_id,
						'season_code'    => $season_code,
					    'pi_status'           => $pi_status,
					   	'po_status'           => $po_status,
                  ]);
                $deleted = WashLabelOrdersQty::where('owcl_id', '=', $id)
                            ->delete();
                $i=0;
                foreach($tot_qty_table as $value)
                {
                     WashLabelOrdersQty::create([
                                        'owcl_id' => $id,
                                        'size'      => $size[$i],
                                        'uvc_tco'      => $uvc_tco[$i],
                                        'barcode'      => $barcode[$i],
                                        'quantity'    => $value
                             ]);
                    $i++;
                }
         $deleted = WashCareComposition::where('owcl_id', '=', $id)
                            ->delete();
         $k=1;
         $composition = json_decode($request->composition);
            foreach($composition as $key => $value)
            {
                foreach($value as $key1 => $value1)
                {
                    $exp_val = explode('_',$value1);
                    if($exp_val[0] == "Select Option")
                    {
                        $parts_id=0;
                    }
                    else
                    {
                        $parts_id=$exp_val[0];
                    }
                    $orchestra_compositions = DB::table('orchestra_compositions')->select('*')->where('english',$exp_val[1])->get();
                    $composition_id=$orchestra_compositions{0}->id;
                    $percent=$exp_val[2];
                    WashCareComposition::create([
                                        'owcl_id' => $id,
                                        'parts_id'      => $parts_id,
                                        'composition_id'      => $composition_id,
                                        'percentage'    => $percent,
                                        'page_id'    => $k,
                             ]);
                }
                $k++;
            }
			
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)
                        ->update([
                          'order_status' => "Order Placed"

            ]);
			return $id;
			}
			else
			{
				$inserted = WashLabelOrders::create([
                                'uvc' => $uvc,
                                'template_id'      => $template_front_id,
                                'merchandisers_id'      => $merchandisers,
                                'template_name'      => $template_name,
                                'washing_id'      => $washing_select,
                                'whitening_id'      => $whitening_select,
                                'dryer_id'      => $dryer_select,
                                'ironing_id'      => $ironing_select,
                                'cleaning_id'      => $cleaning_select,
                                'made_in_id'      => $made_in_select,
				   				'color_code'      => $color_code,
								'card_flag'      => $card_flag,
				   				'po'      => $po_select,
                                'care_instruction_id'      => $care_instruction_select,
                        		'customer_id'    => $factory{0}->id,
                                'svg_path'    => "", 
                                'status'    => "Buyer Approval Pending",
								'orchestra_parcel_details_id'    => $parcel_id,
								'season_code'    => $season_code,
				   				'pi_status'           => $pi_status,
				   				'po_status'           => $po_status,
                     ]);
                $i=0;
                foreach($tot_qty_table as $value)
                {
                     WashLabelOrdersQty::create([
                                        'owcl_id' => $inserted->id,
                                        'size'      => $size[$i],
                                        'uvc_tco'      => $uvc_tco[$i],
                                        'barcode'      => $barcode[$i],
                                        'quantity'    => $value
                             ]);
                    $i++;
                }
         $k=1;
         $composition = json_decode($request->composition);
            foreach($composition as $key => $value)
            {
                foreach($value as $key1 => $value1)
                {
                    $exp_val = explode('_',$value1);
                    if($exp_val[0] == "Select Option")
                    {
                        $parts_id=0;
                    }
                    else
                    {
                        $parts_id=$exp_val[0];
                    }
                    $orchestra_compositions = DB::table('orchestra_compositions')->select('*')->where('english',$exp_val[1])->get();
                    $composition_id=$orchestra_compositions{0}->id;
                    $percent=$exp_val[2];
                    WashCareComposition::create([
                                        'owcl_id' => $inserted->id,
                                        'parts_id'      => $parts_id,
                                        'composition_id'      => $composition_id,
                                        'percentage'    => $percent,
                                        'page_id'    => $k,
                             ]);
                }
                $k++;
            }
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)
                        ->update([
                          'order_status' => "Order Placed"

            ]);
			return  $inserted->id;
			}
              
        }
        else
        {
               $inserted = WashLabelOrders::create([
                                'uvc' => $uvc,
                                'template_id'      => $template_front_id,
                                'merchandisers_id'      => $merchandisers,
                                'template_name'      => $template_name,
                                'washing_id'      => $washing_select,
                                'whitening_id'      => $whitening_select,
                                'dryer_id'      => $dryer_select,
                                'ironing_id'      => $ironing_select,
                                'cleaning_id'      => $cleaning_select,
                                'made_in_id'      => $made_in_select,
				   				'color_code'      => $color_code,
				   				'card_flag'      => $card_flag,
				   				'po'      => $po_select,
                                'care_instruction_id'      => $care_instruction_select,
                        		'customer_id'    => $factory{0}->id,
                                'svg_path'    => "", 
                                'status'    => "Buyer Approval Pending",
								'orchestra_parcel_details_id'    => $parcel_id,
								'season_code'    => $season_code,
				   				'pi_status'           => $pi_status,
				   				'po_status'           => $po_status,
                     ]);
                $i=0;
                foreach($tot_qty_table as $value)
                {
                     WashLabelOrdersQty::create([
                                        'owcl_id' => $inserted->id,
                                        'size'      => $size[$i],
                                        'uvc_tco'      => $uvc_tco[$i],
                                        'barcode'      => $barcode[$i],
                                        'quantity'    => $value
                             ]);
                    $i++;
                }
         $k=1;
         $composition = json_decode($request->composition);
            foreach($composition as $key => $value)
            {
                foreach($value as $key1 => $value1)
                {
                    $exp_val = explode('_',$value1);
                    if($exp_val[0] == "Select Option")
                    {
                        $parts_id=0;
                    }
                    else
                    {
                        $parts_id=$exp_val[0];
                    }
                    $orchestra_compositions = DB::table('orchestra_compositions')->select('*')->where('english',$exp_val[1])->get();
                    $composition_id=$orchestra_compositions{0}->id;
                    $percent=$exp_val[2];
                    WashCareComposition::create([
                                        'owcl_id' => $inserted->id,
                                        'parts_id'      => $parts_id,
                                        'composition_id'      => $composition_id,
                                        'percentage'    => $percent,
                                        'page_id'    => $k,
                             ]);
                }
                $k++;
            }
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)
                        ->update([
                          'order_status' => "Order Placed"

            ]);
			return  $inserted->id;
        }
       
           
    }
    public function setWashLabelOs(Request $request)
    {
        
        //$delivery_date = $request->delivery_date;
        $template_front_id = $request->template_front_id; 
        $template_name = $request->template_name;
        $tot_qty_table = explode(',',$request->tot_qty_table);
        $uvc_tco = explode(',',$request->uvc_tco);
        $barcode = explode(',',$request->barcode);
        $size = explode(',',$request->size);
        $uvc =$request->uvc;
        $po_select =$request->po_select;
        $id =$request->id;
		$card_flag =$request->card_flag;
        $parcel_id =$request->parcel_id;
        $season_code =$request->season_code;
        $washing_select = $request->washing_select; 
        $whitening_select = $request->whitening_select; 
        $dryer_select = $request->dryer_select; 
        $ironing_select= $request->ironing_select; 
        $cleaning_select= $request->cleaning_select; 
        $merchandisers= $request->merchandisers; 
        $made_in_select = $request->made_in_select; 
        $care_instruction_select = $request->care_instruction_select; 
		$color_code = $request->color_code; 
		
		$factory = DB::table("orchestra_parcel_details")->join('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('UVC',$uvc)->select('customers.*')->get();
		
		if(count($factory) > 0)
		{
			
			$pi_required=$factory{0}->pi_required;
			if($pi_required == 1)
			{
				$pi_status="PI Approval Pending";
				if($factory{0}->po_required == 1)
				{
					$po_status="PO Pending";
				}
				else
				{
					$po_status="";
				}
				
			}
			else
			{
				$po_status="PO Pending";
				$pi_status="";
			}
			
		}
		else
		{
			
			$po_status="";
			$pi_status="";
		}
		
        if($id != "null")
        {
                
            // get quantity_table ids.
            $quantity_table_ids = DB::table('orchestra_wash_care_label_orders_qty')->select('id')->where('owcl_id',$id)->get();
        
            WashLabelOrders::where('id',$id)
            ->update([
                    'uvc' => $uvc,
                    'template_id'      => $template_front_id,
                    //'merchandisers_id'      => $merchandisers,
                    'template_name'      => $template_name,
                    'washing_id'      => $washing_select,
                    'whitening_id'      => $whitening_select,
                    'dryer_id'      => $dryer_select,
                    'ironing_id'      => $ironing_select,
                    'cleaning_id'      => $cleaning_select,
                    'made_in_id'      => $made_in_select,
                    'color_code'      => $color_code,
                    'card_flag'      => $card_flag,
                    'po'    => $po_select,
                    'care_instruction_id'      => $care_instruction_select,
                    'customer_id'    => $factory{0}->id,
                    'svg_path'    => "", 
                    'status'    => "Buyer Approval Pending",
                    'orchestra_parcel_details_id'    => $parcel_id,
                    'season_code'    => $season_code,
                    'pi_status'           => $pi_status,
                    'po_status'           => $po_status,
            ]);
            $deleted = WashLabelOrdersQty::where('owcl_id', '=', $id)
                        ->delete();
            $i=0;
            foreach($tot_qty_table as $value)
            {
                WashLabelOrdersQty::create([
                                    'owcl_id' => $id,
                                    'size'      => $size[$i],
									'uvc_tco'      => $uvc_tco[$i],
									'barcode'      => $barcode[$i],
                                    'quantity'    => $value
                        ]);
                $i++;
            }
            $deleted = WashCareComposition::where('owcl_id', '=', $id)
                                ->delete();
            $k=1;
            $composition = json_decode($request->composition);
            foreach($composition as $key => $value)
            {
                foreach($value as $key1 => $value1)
                {
                    $exp_val = explode('_',$value1);
                    if($exp_val[0] == "Select Option")
                    {
                        $parts_id=0;
                    }
                    else
                    {
                        $parts_id=$exp_val[0];
                    }
                    $orchestra_compositions = DB::table('orchestra_compositions')->select('*')->where('english',$exp_val[1])->get();
                    $composition_id=$orchestra_compositions{0}->id;
                    $percent=$exp_val[2];
                    WashCareComposition::create([
                                        'owcl_id' => $id,
                                        'parts_id'      => $parts_id,
                                        'composition_id'      => $composition_id,
                                        'percentage'    => $percent,
                                        'page_id'    => $k,
                             ]);
                }
                $k++;
            }
			
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)
                        ->update([
                          'order_status' => "Order Placed"

            ]);

            // fetch erp_order_details table details for each quantity_table ids.
            foreach($quantity_table_ids as $quantity_table_id)
            {
                $quantity_id = $quantity_table_id->id;
                $erp_order_details[] = DB::table('erp_order_details')->where('order_qnty_table_name','ilike', 'orchestra_wash_care_label_orders_qty')->join('erp_status', 'erp_status.id', 'erp_order_details.status')->where('order_qnty_table_id','=', $quantity_id)->select('erp_order_details.id','erp_status.status_name', 'erp_order_details.erp_order_id')->first();
            }
        
            if(!is_null($erp_order_details[0]))
            {
                $erp_details_status =  $erp_order_details[0]->status_name;
                $erp_order_id =  $erp_order_details[0]->erp_order_id;
                
                foreach($erp_order_details as $erp_order_detail){
                    // delete from history table and insert new rows.
                    $history_table_id =  DB::table('history')->where('erp_order_details_id',$erp_order_detail->id)->value('id');
                    DB::table('event_history')->where('history_id',$history_table_id)->delete();
                    DB::table('history')->where('id',$history_table_id)->delete();
                    // delete from erp_order_details table
                    DB::table('erp_order_details')->where('id',$erp_order_detail->id)->delete();
                }
                
                /** @var array [Pass array of values to updateOrchestraErpOrderDetails function] */
                $data_array = [];   
                $orchestra_buyer_id               = DB::table('buyers')->select('id')->where('buyer_name','=','Orchestra')->first();
                $data_array['main_table']         = 'orchestra_wash_care_label_orders';
                $data_array['main_table_id']      = $id;
                $data_array['quantity_table']     = 'orchestra_wash_care_label_orders_qty';
                $data_array['quantity_id_field']  = 'owcl_id';
                $data_array['buyer_id']           = $orchestra_buyer_id->id;
                $data_array['erp_details_status'] = $erp_details_status;
                $data_array['erp_order_id']       = $erp_order_id;

                // update erp_order_details and erp_orders table 
                $this->edit_erp_obj->updateOrchestraErpOrderDetails($data_array);
            }
			return $id;
              
        }
        else
        {
               $inserted = WashLabelOrders::create([
                                'uvc' => $uvc,
                                'template_id'      => $template_front_id,
                                'merchandisers_id'      => $merchandisers,
                                'template_name'      => $template_name,
                                'washing_id'      => $washing_select,
                                'whitening_id'      => $whitening_select,
                                'dryer_id'      => $dryer_select,
                                'ironing_id'      => $ironing_select,
                                'cleaning_id'      => $cleaning_select,
                                'made_in_id'      => $made_in_select,
				   				'color_code'      => $color_code,
				   				'card_flag'      => $card_flag,
				   				'po'      => $po_select,
                                'care_instruction_id'      => $care_instruction_select,
                        		'customer_id'    => $factory{0}->id,
                                'svg_path'    => "", 
                                'status'    => "Buyer Approval Pending",
								'orchestra_parcel_details_id'    => $parcel_id,
								'season_code'    => $season_code,
				   				'pi_status'           => $pi_status,
				   				'po_status'           => $po_status,
                     ]);
                $i=0;
                foreach($tot_qty_table as $value)
                {
                     WashLabelOrdersQty::create([
                                        'owcl_id' => $inserted->id,
                                        'size'      => $size[$i],
                                        'uvc_tco'      => $uvc_tco[$i],
                                        'barcode'      => $barcode[$i],
                                        'quantity'    => $value
                             ]);
                    $i++;
                }
         $k=1;
         $composition = json_decode($request->composition);
            foreach($composition as $key => $value)
            {
                foreach($value as $key1 => $value1)
                {
                    $exp_val = explode('_',$value1);
                    if($exp_val[0] == "Select Option")
                    {
                        $parts_id=0;
                    }
                    else
                    {
                        $parts_id=$exp_val[0];
                    }
                    $orchestra_compositions = DB::table('orchestra_compositions')->select('*')->where('english',$exp_val[1])->get();
                    $composition_id=$orchestra_compositions{0}->id;
                    $percent=$exp_val[2];
                    WashCareComposition::create([
                                        'owcl_id' => $inserted->id,
                                        'parts_id'      => $parts_id,
                                        'composition_id'      => $composition_id,
                                        'percentage'    => $percent,
                                        'page_id'    => $k,
                             ]);
                }
                $k++;
            }
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)
                        ->update([
                          'order_status' => "Order Placed"

            ]);
			return  $inserted->id;
        }
       
           
    }
	/**
     * method to get WCL details for the selected PO
     * @param Request $request [po,parcel_id, season_code,uvc_id]
     * @author <Gowtham>
     */
	public function poSelectWcl(Request $request)
	{
		$po      = $request->po;
		$parcel_id    = $request->parcel_id;
		$season_code    = $request->season_code; 
		$uvc_id    = $request->uvc_id;
		$card_flag    = $request->card_flag;
		
	    $data['orchestra_wash_care_label_orders'] = DB::table('orchestra_wash_care_label_orders')->select('*')->where('uvc',$uvc_id)->where('season_code',$season_code)->where('po',$po)->where('status','!=','Order Cancelled')->where('card_flag',$card_flag)->get();

		
		if(count($data['orchestra_wash_care_label_orders']) > 0)
		{
	    	$data['orchestra_wash_care_label_orders_qty'] = DB::table('orchestra_wash_care_label_orders_qty')->select('*')->where('owcl_id',$data['orchestra_wash_care_label_orders']{0}->id)->get();
		}
		else
		{
			$data['orchestra_wash_care_label_orders_qty'] = "";
		}
		
		echo json_encode($data);
	}
	
    /**
     * method to set the purchase order number to the respective order
     * @param Request $request [table_name,po_number, id]
     */
    public function setPONumber(Request $request)
    {
        try
        {
            $erp_obj        = new ErpOrders();
            $po_number      = $request->po_number;
			$template_id    = $request->id;
            $table_name     = $request->tableName; 

            $po_details     = DB::table($table_name)->where('id',$template_id)
                                ->select('po_file')->first();

            // check order status in erp_oorders table.                    
            $data_array = [];
            $data_array['id'] = $request->id;
            $data_array['table_name'] = $table_name;
            $class_obj = new ERPOrderStatusCheck();
            $erp_order_details = $class_obj->OrchestraERPStatus($data_array);
            
            if($erp_order_details && $erp_order_details[0] != null)
            {
                if($erp_order_details[0]->status_name != 'Hold For JC Queue' &&  $erp_order_details[0]->status_name != 'Moved To ERP' )
                {
                    return response()->json(['error'=> false,'message' =>'Order status is '.$erp_order_details[0]->status_name. '. PO number edit is not allowed.'],403);
                }
                
            }
            
            if ($po_details->po_file === null || $po_details->po_file === "")
            {
                $po_status = "PO Pending";
            }
            else
            {
                $po_status = "PO Uploaded";
            }

            $data = DB::table($table_name)->where('id',$template_id)
                    ->update([
                        'po_number' => $po_number,
                        'po_status' => $po_status,
                        'po_uploaded_at' => Carbon::now('Asia/Kolkata')->toDateTimeString()
                        ]);
            if ($po_status == "PO Uploaded") {
                /** @var array [Pass array of values to updateOrchestraErpOrders function] */
                $request_array = [];
                $request_array['id'] = $template_id;
                $request_array['tableName'] = $table_name; 
                $request_array['buyer_id'] = $request->buyer_id; 
                $request_array['created_by'] = Auth::guard('web_customer')->id();
                $erp_obj->updateOrchestraErpOrders($request_array);
            }

            return response()->json(['error'=> false,'message' =>'Purchase Order Number added Successfully'],200);
            
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    /**
     * method to set the po number and pdf file for an order
     * @param po_number and pdf (pdf is optional)
     * @return \Illuminate\Http\Response
     */
    public function setCustomerPOFile(Request $request)
    {
        try 
        {
            $erp_obj    = new ErpOrders();
            $table_name = $request->tableName;
            $row_id     = $request->id;
            $po_file    = $request->file('file');
            $path       = $request->file('file')->getRealPath();
            $filename   = $request->file('file')->getClientOriginalName();
            $destination_path = '/storage/app/public/po-files/';
            $path       ='storage/app/public/po-files/';
            $Orname     = $po_file->getClientOriginalName();
            $filename   = str_random(6).'_'.preg_replace('/#[^\s]+/','',preg_replace('/\s+/', '-', $Orname));
            $po_file->move($path,$filename);
            $destination_path_insert=$destination_path.$filename;

            $po_details = DB::table($table_name)->where('id',$row_id)->select('po_number')->first();

            $data_array = [];
            $data_array['id'] = $row_id;
            $data_array['table_name'] = $table_name;
            $class_obj = new ERPOrderStatusCheck();
            $erp_order_details = $class_obj->OrchestraERPStatus($data_array);

            if($erp_order_details && $erp_order_details[0] != null)
            {
                if($erp_order_details[0]->status_name != 'Hold For JC Queue' &&  $erp_order_details[0]->status_name != 'Moved To ERP' )
                {
                    return response()->json(['error'=> false,'message' =>'Order status is '.$erp_order_details[0]->status_name. '. PO file upload is not allowed.'],403);
                }
                
            }
            
            if ($po_details->po_number === null || $po_details->po_number === "")
            {
                $po_status = "PO Pending";
            }
            else
            {
                $po_status = "PO Uploaded";
            }
            $data   = DB::table($table_name)->where('id',$row_id)
                        ->update([
                          'po_file' => $destination_path_insert,
                          'po_status' => $po_status,
                          'po_uploaded_at' => Carbon::now('Asia/Kolkata')->toDateTimeString()

                        ]);
            if ($po_status == "PO Uploaded") {
                /** @var array [Pass array of values to updateOrchestraErpOrders function] */
                $request_array = [];
                $request_array['id'] = $row_id;
                $request_array['tableName'] = $table_name; 
                $request_array['buyer_id'] = $request['buyer_id']; 
                $request_array['created_by'] = Auth::guard('web_customer')->id();

                $erp_obj->updateOrchestraErpOrders($request_array);
            }
            if ($data)
            {
               return response()->json(['error'=> false,'message' =>'success','po_file'=>$destination_path_insert],200);
            }
            else
            {
                return response()->json(['error'=> true,'message' =>'Error'],400);
            }   
        }
        catch (Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        } 
        return $result;
    }
	 /**
     * method to get all the details for proforma invoice generation
     * @param  Request $request [table_name,id]
     * @return [type]           [description]
     * @author <Praveen>
     */
    public function getOrchestraPI(Request $request)
    {
        try
        {
            $pi_obj = new ProformaInvoice();
            $pi_status = $request->pi_status;

            if ($pi_status === "true")
            {
                $data   = $pi_obj->getApprovedOrchestraPI($request);
            }
            else
            {
                $data   = $pi_obj->getOrchestraPIDetails($request);
            }
            $result = response()->json(['error'=> false,'message' =>'success','data'=>$data],200);
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

    /**
     * method to approve the proforma invoice
     * @param  Request $request [description]
     * @return [type]           [description]
     * @author <Praveen>
     */
    public function approveOrchestraPI(Request $request)
    {
        try
        {
            $row_id      =   $request->id;
            $table_name  =   $request->table_name;
            $buyer_id    =   $request->buyer_id;
            $billamnt    =   $request->billamnt;
            $template_id =   $request->template_id; 
            
            $pi_obj      =   new ProformaInvoice();
            $data        =   $pi_obj->getOrchestraPIDetails($request);
            unset($data['price_list']);
            $attachment =   $pi_obj->getOrchestraAttachment($data,$request);
            
            $erpOrders   =   [];
            $total_qnty  =   0 ; 
            $sub_total   =   0 ;
            $erp_obj     =   new ErpOrders();

            if ($data)
            {
                if ($data['item_details'][0]->order_flag=="reOrder") 
                {
                    $rep_order_id = str_replace(' ', '', $data['item_details'][0]->template_name)."_".$row_id;
                }
                else
                {
                    $rep_order_id = 0;
                }
                
                $ship_address = $data['ship_to']->address.','.$data['ship_to']->country;
                $bill_address = $data['bill_to']->address.','.$data['bill_to']->country;

                $pi_details = PI::create([
                    'buyer_id'         => $buyer_id,
                    'company_id'       => $data['company_details']->id,
                    'customer_id'      => $data['bank_details']->id,
                    'buyer_po_no'      => $data['bank_details']->po,
                    'uvc_style_no'     => $data['bank_details']->uvc,
                    'ref_no'           => $data['bank_details']->ref_no,
                    'shipping_address' => $ship_address,
                    'billing_address'  => $bill_address,
                    'place_of_supply'  => $data['bank_details']->place_of_supply,
                    'revision_no'      => $data['item_details'][0]->pi_rev_no,
                    'rep_order_id'     => $rep_order_id,
                    'pi_file'          => $attachment
                    
                ]);

                foreach ($data['item_details'] as $key => $value)
                {
                    $unit_cost = $value->price;
                    $tax = $value->tax;
                    $total_qnty+= $value->quantity;
                    $sub_total += $value->quantity *$unit_cost;

                    ProfomaInvoiceItemDetails::create([
                        'pi_id'       => $pi_details->id,
                        'material'    => $value->material_name,
                        'size'        => $value->size,
                        'template_id' => $value->item_id,
                        'ref_no'      => $value->ref_no,
                        'style'       => $value->uvc,
                        'quantity'    => $value->quantity,
                        'unit_cost'   => $unit_cost,
                        'tax'         => $tax
                    ]);
                }
                PI::where('id',$pi_details->id)
                            ->update([
                              'total_quantity'   => $total_qnty,
                              'sub_total'        => $sub_total,
                              'bill_amount'      => $billamnt
                            ]);
            }

            $approved_data   = DB::table($table_name)
                        ->where('id',$row_id)
                        ->update([
                            'pi_status' => "PI Approved",
                            'pi_id' => $pi_details->id,
                            'pi_file' => $attachment, 
                            'pi_approved_at' => Carbon::now('Asia/Kolkata')->toDateTimeString()
                        ]);
            $erpOrders['tableName'] = $table_name;
            $erpOrders['id']        = $row_id;
            $erpOrders['buyer_id']  = $buyer_id;   
            $erpOrders['created_by'] = Auth::guard('web_customer')->id();
            $customer_data  = DB::table('customers')->where('id',$data['bank_details']->id)->first();

            if ($customer_data->pi_required == 1 && $customer_data->po_required== 0) {
                $erp_obj->updateOrchestraErpOrders($erpOrders);
            } 

            if ($approved_data)
            {
               $result = response()->json(['error'=> false,'message' =>'success'    ],200);
            }
            else
            {
                $result = response()->json(['error'=> true,'message' =>'Error'],400);
            }
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
	
    /**
     * method to reject the proforma invoice
     * @param  Request $request [description]
     * @return [type]           [description]
     * @author <Praveen>
     */
    public function rejectOrchestraPI(Request $request)
    {
        try
        {
            $row_id     =   $request->id;
            $table_name =   $request->table_name;
            $table_data =   DB::table($table_name)->where('id',$row_id)->first();
            $data = DB::table($table_name)
                        ->where('id',$row_id)
                         ->update([
                          'pi_status' => "PI Rejected",
                          'pi_rev_no' => $table_data->pi_rev_no+1
                        ]);
            if ($data)
            {
               $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
            else
            {
                $result = response()->json(['error'=> true,'message' =>'Error'],400);
            }
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

    /**
     * Download Orchestra PI file
     * @param  Request $request [description]
     * @return [type]           [description]
     * @author <Praveen>
     */
    public function downloadOrchestraPI(Request $request)
    {
         try {
             $id         =  $request['id'];
             $table_name =  $request['table_name'];            
             $result     =  DB::table($table_name)->where('id',$id)->value('pi_file');   
         }  
         catch (\Exception $e)
         {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
         }
         return $result;
    }
	
	
	/**
     * This Function is used to delete the Extra Wash Care Label Details.
     *
     * @return Void
     */
    public function removeWashLabel(Request $request)
    {
         $deleted = WashLabelOrders::where('id', '=', $request->id)->delete();
    }

    /**
     * This Function is used to get the existing artworks for populating in the modal.
     *
     * @return Void
     */
	
    public function getExistingLabelOrdersPo(Request $request)
    {
		 $uvc        =   $request->uvc;
        $data['orchestra_hang_tag_orders']=DB::table('orchestra_hang_tag_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_hang_tag_orders.template_front_id')->where('orchestra_hang_tag_orders.status','!=','Order Cancelled')->select('orchestra_hang_tag_orders.*','orchestra_item_master.company_id',DB::raw("'Hang Tag' as \"TemplateName\""),DB::raw("'Hang Tag' as \"TemplateType\""),DB::raw("'orchestra_hang_tag_orders' as \"type\""))->where('uvc',$uvc)->get();

        $data['orchestra_special_tag_orders']=DB::table('orchestra_special_tag_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_special_tag_orders.template_id')->where('orchestra_special_tag_orders.status','!=','Order Cancelled')->select('orchestra_special_tag_orders.*','orchestra_item_master.company_id',DB::raw("'Special Tag' as \"TemplateName\""),DB::raw("'Special Tag' as \"TemplateType\""),DB::raw("'orchestra_special_tag_orders' as \"type\""))->where('uvc',$uvc)->get();

        $data['orchestra_main_label_orders']=DB::table('orchestra_main_label_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_main_label_orders.template_id')->where('orchestra_main_label_orders.status','!=','Order Cancelled')->select('orchestra_main_label_orders.*','orchestra_item_master.company_id',DB::raw("'Main Label' as \"TemplateName\""),DB::raw("'Main Label' as \"TemplateType\""),DB::raw("'orchestra_main_label_orders' as \"type\""))->where('uvc',$uvc)->get();
		
		$data['orchestra_size_label_orders']=DB::table('orchestra_size_label_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_size_label_orders.template_id')->where('orchestra_size_label_orders.status','!=','Order Cancelled')->select('orchestra_size_label_orders.*','orchestra_item_master.company_id',DB::raw("'Size Label' as \"TemplateName\""),DB::raw("'Size Label' as \"TemplateType\""),DB::raw("'orchestra_size_label_orders' as \"type\""))->where('uvc',$uvc)->get();
		
		$data['orchestra_heat_transfer_orders']=DB::table('orchestra_heat_transfer_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_heat_transfer_orders.template_id')->where('orchestra_heat_transfer_orders.status','!=','Order Cancelled')->select('orchestra_heat_transfer_orders.*','orchestra_item_master.company_id',DB::raw("'Heat Transfer' as \"TemplateName\""),DB::raw("'Heat Transfer' as \"TemplateType\""),DB::raw("'orchestra_heat_transfer_orders' as \"type\""))->where('uvc',$uvc)->get();

        $data['orchestra_wash_care_label_orders']=DB::table('orchestra_wash_care_label_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_wash_care_label_orders.template_id')->where('orchestra_wash_care_label_orders.status','!=','Order Cancelled')->select('orchestra_wash_care_label_orders.*','orchestra_item_master.company_id','orchestra_wash_care_label_orders.template_name as TemplateName',DB::raw("'Wash Care Labels' as \"TemplateType\""),DB::raw("'orchestra_wash_care_label_orders' as \"type\""))->where('uvc',$uvc)->get();

		 echo json_encode($data);
    }

    /**
     * This Function is used to get the existing artworks for populating in the modal.
     *
     * @return Void
     */
	
    public function getExistingStandardStickerOrdersPo(Request $request)
    {
		 $uvc        =   $request->uvc;
	
		 $data['orchestra_standard_carton_sticker_orders']=DB::table('orchestra_standard_carton_sticker_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_standard_carton_sticker_orders.template_id')->where('orchestra_standard_carton_sticker_orders.status','!=','Order Cancelled')->select('orchestra_standard_carton_sticker_orders.*','orchestra_item_master.company_id', DB::raw("'Standard Carton Stickers' as \"TemplateName\""),DB::raw("'Standard Carton Stickers' as \"TemplateType\""),DB::raw("'orchestra_standard_carton_sticker_orders' as \"type\""))->where('uvc',$uvc)->get();
		
		 $data['orchestra_standard_poly_bag_sticker_orders']=DB::table('orchestra_standard_poly_bag_sticker_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_standard_poly_bag_sticker_orders.template_id')->where('orchestra_standard_poly_bag_sticker_orders.status','!=','Order Cancelled')->select('orchestra_standard_poly_bag_sticker_orders.*','orchestra_item_master.company_id', DB::raw("'Standard Poly Bag Stickers' as \"TemplateName\""),DB::raw("'Standard Poly Bag Stickers' as \"TemplateType\""),DB::raw("'orchestra_standard_poly_bag_sticker_orders' as \"type\""))->where('uvc',$uvc)->get();
		
		 echo json_encode($data);
    } 

   /**
     * This Function is used to get the existing artworks for populating in the modal.
     *
     * @return Void
     */
	
    public function getExistingSolidStickerOrdersPo(Request $request)
    {
		 $uvc        =   $request->uvc;
		
		 $data['orchestra_solid_carton_sticker_orders']=DB::table('orchestra_solid_carton_sticker_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_solid_carton_sticker_orders.template_id')->where('orchestra_solid_carton_sticker_orders.status','!=','Order Cancelled')->select('orchestra_solid_carton_sticker_orders.*','orchestra_item_master.company_id', DB::raw("'Solid Carton Stickers' as \"TemplateName\""),DB::raw("'Solid Carton Stickers' as \"TemplateType\""),DB::raw("'orchestra_solid_carton_sticker_orders' as \"type\""))->where('uvc',$uvc)->get();
		
         $data['orchestra_solid_poly_bag_sticker_orders']=DB::table('orchestra_solid_poly_bag_sticker_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_solid_poly_bag_sticker_orders.template_id')->where('orchestra_solid_poly_bag_sticker_orders.status','!=','Order Cancelled')->select('orchestra_solid_poly_bag_sticker_orders.*','orchestra_item_master.company_id', DB::raw("'Solid Poly Bag Stickers' as \"TemplateName\""),DB::raw("'Solid Poly Bag Stickers' as \"TemplateType\""),DB::raw("'orchestra_solid_poly_bag_sticker_orders' as \"type\""))->where('uvc',$uvc)->get();
		
		 echo json_encode($data);
    } 

    /**
     * This Function is used to get the existing artworks for populating in the modal.
     *
     * @return Void
     */
	
    public function getExistingLabelOrderStatusPo(Request $request)
    {
		 $uvc        =   $request->uvc;
          $data['orchestra_hang_tag_orders']=DB::table('orchestra_hang_tag_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_hang_tag_orders.template_front_id')->where('orchestra_hang_tag_orders.status','!=','Order Cancelled')->select('orchestra_hang_tag_orders.*','orchestra_item_master.company_id', DB::raw("'Hang Tag' as \"TemplateName\""),DB::raw("'Hang Tag' as \"TemplateType\""),DB::raw("'orchestra_hang_tag_orders' as \"tableName\""))->where('uvc',$uvc)->get();
		
		  $data['orchestra_special_tag_orders']=DB::table('orchestra_special_tag_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_special_tag_orders.template_id')->where('orchestra_special_tag_orders.status','!=','Order Cancelled')->select('orchestra_special_tag_orders.*','orchestra_item_master.company_id', DB::raw("'Special Tag' as \"TemplateName\""),DB::raw("'Special Tag' as \"TemplateType\""),DB::raw("'orchestra_special_tag_orders' as \"tableName\""))->where('uvc',$uvc)->get();
		
		  $data['orchestra_main_label_orders']=DB::table('orchestra_main_label_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_main_label_orders.template_id')->where('orchestra_main_label_orders.status','!=','Order Cancelled')->select('orchestra_main_label_orders.*','orchestra_item_master.company_id',DB::raw("'Main Label' as \"TemplateName\""),DB::raw("'Main Label' as \"TemplateType\""),DB::raw("'orchestra_main_label_orders' as \"tableName\""))->where('uvc',$uvc)->get();
		
		  $data['orchestra_size_label_orders']=DB::table('orchestra_size_label_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_size_label_orders.template_id')->where('orchestra_size_label_orders.status','!=','Order Cancelled')->select('orchestra_size_label_orders.*','orchestra_item_master.company_id',DB::raw("'Size Label' as \"TemplateName\""),DB::raw("'Size Label' as \"TemplateType\""),DB::raw("'orchestra_size_label_orders' as \"tableName\""))->where('uvc',$uvc)->get();		
		
		  $data['orchestra_heat_transfer_orders']=DB::table('orchestra_heat_transfer_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_heat_transfer_orders.template_id')->where('orchestra_heat_transfer_orders.status','!=','Order Cancelled')->select('orchestra_heat_transfer_orders.*','orchestra_item_master.company_id',DB::raw("'Heat Transfer' as \"TemplateName\""),DB::raw("'Heat Transfer' as \"TemplateType\""),DB::raw("'orchestra_heat_transfer_orders' as \"tableName\""))->where('uvc',$uvc)->get();
		  $data['orchestra_wash_care_label_orders']=DB::table('orchestra_wash_care_label_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_wash_care_label_orders.template_id')->where('orchestra_wash_care_label_orders.status','!=','Order Cancelled')->select('orchestra_wash_care_label_orders.*','orchestra_item_master.company_id','orchestra_wash_care_label_orders.template_name as TemplateName',DB::raw("'Wash Care Labels' as \"TemplateType\""),DB::raw("'orchestra_wash_care_label_orders' as \"tableName\""))->where('uvc',$uvc)->get();
		
		  $data['orchestra_solid_carton_sticker_orders']=DB::table('orchestra_solid_carton_sticker_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_solid_carton_sticker_orders.template_id')->where('orchestra_solid_carton_sticker_orders.status','!=','Order Cancelled')->select('orchestra_solid_carton_sticker_orders.*','orchestra_item_master.company_id',DB::raw("'Solid Carton Stickers' as \"TemplateName\""),DB::raw("'Solid Carton Stickers' as \"TemplateType\""),DB::raw("'orchestra_solid_carton_sticker_orders' as \"tableName\""))->where('uvc',$uvc)->get();
		
		  $data['orchestra_solid_poly_bag_sticker_orders']=DB::table('orchestra_solid_poly_bag_sticker_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_solid_poly_bag_sticker_orders.template_id')->where('orchestra_solid_poly_bag_sticker_orders.status','!=','Order Cancelled')->select('orchestra_solid_poly_bag_sticker_orders.*','orchestra_item_master.company_id',DB::raw("'Solid Poly Bag Stickers' as \"TemplateName\""),DB::raw("'Solid Poly Bag Stickers' as \"TemplateType\""),DB::raw("'orchestra_solid_poly_bag_sticker_orders' as \"tableName\""))->where('uvc',$uvc)->get();
		
		 $data['orchestra_standard_carton_sticker_orders']=DB::table('orchestra_standard_carton_sticker_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_standard_carton_sticker_orders.template_id')->where('orchestra_standard_carton_sticker_orders.status','!=','Order Cancelled')->select('orchestra_standard_carton_sticker_orders.*','orchestra_item_master.company_id',DB::raw("'Standard Carton Stickers' as \"TemplateName\""),DB::raw("'Standard Carton Stickers' as \"TemplateType\""),DB::raw("'orchestra_standard_carton_sticker_orders' as \"tableName\""))->where('uvc',$uvc)->get();
		
		 $data['orchestra_standard_poly_bag_sticker_orders']=DB::table('orchestra_standard_poly_bag_sticker_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_standard_poly_bag_sticker_orders.template_id')->where('orchestra_standard_poly_bag_sticker_orders.status','!=','Order Cancelled')->select('orchestra_standard_poly_bag_sticker_orders.*','orchestra_item_master.company_id',DB::raw("'Standard Poly Bag Stickers' as \"TemplateName\""),DB::raw("'Standard Poly Bag Stickers' as \"TemplateType\""),DB::raw("'orchestra_standard_poly_bag_sticker_orders' as \"tableName\""))->where('uvc',$uvc)->get();
		
		
		 echo json_encode($data);
    } 

    /**
     * This Function is used to map the existing PO to new artworks.
     *
     * @return Void
     */
	public function mapLabelOrdersPo(Request $request)
    {
		$erp_obj   =   new ErpOrders();
		 $id        =   explode(',',$request->id);
		 $po        =   $request->po;
		 $po_file        =   $request->po_file;
		
		foreach($id as $val)
		{
				$value    =  explode('-',$val);
			
				$tesco_buyer_id = DB::table('buyers')->select('id')->where('buyer_name','=','Orchestra')->first();
				$request_array = [];
				$request_array['id'] = $value[1];
				$request_array['tableName'] = $value[0]; 
				$request_array['buyer_id'] = $tesco_buyer_id->id;  
				$request_array['created_by'] = Auth::guard('web')->id();
			
				DB::table($value[0])->where('id',$value[1])
                ->update([
                    'po_number' => $po,
                    'po_status' => "PO Uploaded",
                    'po_file' => $po_file,
                    'po_uploaded_at' => Carbon::now('Asia/Kolkata')->toDateTimeString()
                ]);
			
				$erp_obj->updateOrchestraErpOrders($request_array);
		}
		return response()->json(['error'=> false,'message' =>'success'],200);
    }
	/**
     * This Function is used to update the po number and po file to the new artworks.
     *
     * @return Void
     */
	public function updateLabelOrdersPO(Request $request)
    {
        try
        {  
            $erp_obj   =   new ErpOrders();
            $id        =   explode(',',$request->id);
            $po        =   $request->po;
            $po_file   =   $request->po_file;
           
            $destination_path_insert = "";
            $destination_path_db = "";
            $tesco_buyer_id = DB::table('buyers')->select('id')->where('buyer_name','=','Orchestra')->first();

            if( $request->hasFile('po_file'))
            {
                $file = $request->file('po_file');
                $destination_path = 'storage/app/public/po-files/';
                $destination_path_db = '/storage/app/public/po-files/';
                $Orname = $file->getClientOriginalName();
                $filename = str_random(6).'_'.preg_replace('/#[^\s]+/','',preg_replace('/\s+/', '-', $Orname));
                $file->move($destination_path,$filename);
                $destination_path_insert = $destination_path_db.$filename;
                
                foreach($id as $val)
                {
                    $value    =  explode('-',$val);

                    DB::table($value[0])->where('id',$value[1])
                        ->update([
                            'po_number' => $po,
                            'po_file' => $destination_path_insert,
                            'po_status' => "PO Uploaded",
                            'po_uploaded_at' => Carbon::now('Asia/Kolkata')->toDateTimeString()
                        ]);

                    /** @var array [Pass array of values to updateOrchestraErpOrders function] */
                    $request_array = [];
                    $request_array['id'] = $value[1];
                    $request_array['tableName'] = $value[0]; 
                    $request_array['buyer_id'] = $tesco_buyer_id->id; 
                    $request_array['created_by'] = Auth::guard('web_customer')->id();

                    $erp_obj->updateOrchestraErpOrders($request_array);
                }
            }
            else
            {
                foreach($id as $val)
                {
                    $value = explode('-',$val);

                    DB::table($value[0])->where('id',$value[1])
                        ->update([
                            'po_number' => $po,
                            'po_status' => "PO Uploaded",
                            'po_uploaded_at' => Carbon::now('Asia/Kolkata')->toDateTimeString()
                        ]);
                    
                    /** @var array [Pass array of values to updateOrchestraErpOrders function] */
                    $request_array = [];
                    $request_array['id'] = $value[1];
                    $request_array['tableName'] = $value[0]; 
                    $request_array['buyer_id'] = $tesco_buyer_id->id; 
                    $request_array['created_by'] = Auth::guard('web_customer')->id();

                    $erp_obj->updateOrchestraErpOrders($request_array);
                }
            }
            $result = response()->json(['error'=> false,'message' =>'success'],200);
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

/**
     * This Function is used to Show the UVC Number in reorder page.
     *
     * @return json value
     */
	public function reOrder(Request $request)
    {
              $data['orchestra_hang_tag_orders'] = DB::table('orchestra_hang_tag_orders')->select('*')->where('status',"Order Approved")->get();
			  $data['orchestra_main_label_orders'] = DB::table('orchestra_main_label_orders')->select('*')->where('status',"Order Approved")->get();
			  $data['orchestra_special_tag_orders'] = DB::table('orchestra_special_tag_orders')->select('*')->where('status',"Order Approved")->get();
			  $data['orchestra_wash_care_label_orders'] = DB::table('orchestra_wash_care_label_orders')->select('*')->where('status',"Order Approved")->get();
			  $data['orchestra_solid_carton_sticker_orders'] = DB::table('orchestra_solid_carton_sticker_orders')->select('*')->where('status',"Order Approved")->get();
			  $data['orchestra_solid_poly_bag_sticker_orders'] = DB::table('orchestra_solid_poly_bag_sticker_orders')->select('*')->where('status',"Order Approved")->get();
			  $data['orchestra_standard_carton_sticker_orders'] = DB::table('orchestra_standard_carton_sticker_orders')->select('*')->where('status',"Order Approved")->get();
			  $data['orchestra_standard_poly_bag_sticker_orders'] = DB::table('orchestra_standard_poly_bag_sticker_orders')->select('*')->where('status',"Order Approved")->get();
              echo json_encode($data);
    }
	/**
     * This Function is used to list all the artwork for particular uvc number.
     *
     * @return json value
     */
	public function reOrderArtwork(Request $request)
    {
          $uvc        =   $request->uvc;
          $data['orchestra_hang_tag_orders']=DB::table('orchestra_hang_tag_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_hang_tag_orders.template_front_id')->select('orchestra_hang_tag_orders.*','orchestra_item_master.company_id',DB::raw('"Hang Tag" as TemplateName'),DB::raw('"Label" as Labeltype'),DB::raw('"Hang Tag" as TemplateType'),DB::raw('"orchestra_hang_tag_orders" as type'))->where('uvc',$uvc)->get();
		
		 $data['orchestra_special_tag_orders']=DB::table('orchestra_special_tag_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_special_tag_orders.template_id')->select('orchestra_special_tag_orders.*','orchestra_item_master.company_id',DB::raw('"Special Tag" as TemplateName'),DB::raw('"Label" as Labeltype'),DB::raw('"Special Tag" as TemplateType'),DB::raw('"orchestra_special_tag_orders" as type'))->where('uvc',$uvc)->get();
		
		$data['orchestra_main_label_orders']=DB::table('orchestra_main_label_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_main_label_orders.template_id')->select('orchestra_main_label_orders.*','orchestra_item_master.company_id',DB::raw("'Main Label' as \"TemplateName\""),DB::raw('"Label" as Labeltype'),DB::raw('"Main Label" as TemplateType'),DB::raw('"orchestra_main_label_orders" as type'))->where('uvc',$uvc)->get();
		
		  $data['orchestra_wash_care_label_orders']=DB::table('orchestra_wash_care_label_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_wash_care_label_orders.template_id')->select('orchestra_wash_care_label_orders.*','orchestra_item_master.company_id','orchestra_wash_care_label_orders.template_name as TemplateName',DB::raw('"Label" as Labeltype'),DB::raw('"Wash Care Labels" as TemplateType'),DB::raw('"orchestra_wash_care_label_orders" as type'))->where('uvc',$uvc)->get();
		
		  $data['orchestra_solid_carton_sticker_orders']=DB::table('orchestra_solid_carton_sticker_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_solid_carton_sticker_orders.template_id')->select('orchestra_solid_carton_sticker_orders.*','orchestra_item_master.company_id',DB::raw('"Solid Carton Stickers" as TemplateName'),DB::raw('"Sticker" as Labeltype'),DB::raw('"Solid Carton Stickers" as TemplateType'),DB::raw('"orchestra_solid_carton_sticker_orders" as type'))->where('uvc',$uvc)->get();
		
		  $data['orchestra_solid_poly_bag_sticker_orders']=DB::table('orchestra_solid_poly_bag_sticker_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_solid_poly_bag_sticker_orders.template_id')->select('orchestra_solid_poly_bag_sticker_orders.*','orchestra_item_master.company_id',DB::raw('"Solid Poly Bag Stickers" as TemplateName'),DB::raw('"Sticker" as Labeltype'),DB::raw('"Solid Poly Bag Stickers" as TemplateType'),DB::raw('"orchestra_solid_poly_bag_sticker_orders" as type'))->where('uvc',$uvc)->get();
		
		 $data['orchestra_standard_carton_sticker_orders']=DB::table('orchestra_standard_carton_sticker_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_standard_carton_sticker_orders.template_id')->select('orchestra_standard_carton_sticker_orders.*','orchestra_item_master.company_id',DB::raw('"Standard Carton Stickers" as TemplateName'),DB::raw('"Sticker" as Labeltype'),DB::raw('"Standard Carton Stickers" as TemplateType'),DB::raw('"orchestra_standard_carton_sticker_orders" as type'))->where('uvc',$uvc)->get();
		
		 $data['orchestra_standard_poly_bag_sticker_orders']=DB::table('orchestra_standard_poly_bag_sticker_orders')->join('orchestra_item_master','orchestra_item_master.id','orchestra_standard_poly_bag_sticker_orders.template_id')->select('orchestra_standard_poly_bag_sticker_orders.*','orchestra_item_master.company_id',DB::raw('"Standard Poly Bag Stickers" as TemplateName'),DB::raw('"Sticker" as Labeltype'),DB::raw('"Standard Poly Bag Stickers" as TemplateType'),DB::raw('"orchestra_standard_poly_bag_sticker_orders" as type'))->where('uvc',$uvc)->get();
		
		
		 echo json_encode($data);
    }
	/**
     * This Function is used to get the artwork and quantity information.
     *
     * @return json value
     */
	public function extraQuantity(Request $request)
    {
          $type         =   $request->type;
		  $id        =   $request->id;
		  if($type == "orchestra_hang_tag_orders")
		  {
			  $data['qty']=DB::table($type.'_qty')->where('oht_id',$id)->select('*')->get();
			  $data['template']=DB::table($type)->where('id',$id)->select('*')->get();
			  $data['template_front']=DB::table('orchestra_template')->where('item_id',$data['template']{0}->template_front_id)->where('position','front')->select('*')->get();
			  $data['template_back']=DB::table('orchestra_template')->where('item_id',$data['template']{0}->template_front_id)->where('position','back')->select('*')->get();
		  }
		  else if($type == "orchestra_special_tag_orders")
		  {
			  $data['template']=DB::table($type)->where('id',$id)->select('*')->get();
			  $data['template_file']=DB::table('orchestra_template')->where('item_id',$data['template']{0}->template_id)->select('*')->get();
		  }
		  else if($type == "orchestra_main_label_orders")
		  {
			  $data['qty']=DB::table($type.'_qty')->where('oml_id',$id)->select('*')->get();
			  $data['template']=DB::table($type)->where('id',$id)->select('*')->get();
			  $data['template_file']=DB::table('orchestra_template')->where('item_id',$data['template']{0}->template_id)->select('*')->get();
		  }
		  else if($type == "orchestra_wash_care_label_orders")
		  {
			  $data['qty']=DB::table($type.'_qty')->where('owcl_id',$id)->select('*')->get();
			  $data['template']=DB::table($type)->where('id',$id)->select('*')->get();
			  $data['template_file']=DB::table('orchestra_template')->where('item_id',$data['template']{0}->template_id)->select('*')->get();
		  }
          else if($type == "orchestra_solid_carton_sticker_orders")
		  {
			  $data['qty']=DB::table($type.'_qty')->where('oscs_id',$id)->select('*')->get();
			  $data['template']=DB::table($type)->where('id',$id)->select('*')->get();
			  $data['template_file']=DB::table('orchestra_template')->where('id',$data['template']{0}->template_id)->select('*')->get();
		  } 
		  else if($type == "orchestra_solid_poly_bag_sticker_orders")
		  {
			  $data['qty']=DB::table($type.'_qty')->where('ospbs_id',$id)->select('*')->get();
			  $data['template']=DB::table($type)->where('id',$id)->select('*')->get();
			  $data['template_file']=DB::table('orchestra_template')->where('id',$data['template']{0}->template_id)->select('*')->get();
		  } 
		  else if($type == "orchestra_standard_carton_sticker_orders")
		  {
			  $data['template']=DB::table($type)->where('id',$id)->select('*')->get();
			  $data['template_file']=DB::table('orchestra_template')->where('id',$data['template']{0}->template_id)->select('*')->get();
		  } 
		  else if($type == "orchestra_standard_poly_bag_sticker_orders")
		  {
			  $data['template']=DB::table($type)->where('id',$id)->select('*')->get();
			  $data['template_file']=DB::table('orchestra_template')->where('id',$data['template']{0}->template_id)->select('*')->get();
		  }
          echo json_encode($data);
    }
    /**
     * method to get erp status of this order
     * @param  Request $request [description]
     * @return ERP_Status          [description]
     * @author Praveen <praveen@wizroots.com>
     */
    public function checkERPStatus(Request $request)
    {
        try
        {   
            $data_array = [];
            $data_array['id'] = $request->id;
            $data_array['table_name'] = $request->table_name;
            $class_obj = new ERPOrderStatusCheck();
            $erp_order_details = $class_obj->OrchestraERPStatus($data_array);
            
            $result = response()->json(['error'=> false,'message' =>'success','erp_details' => $erp_order_details],200);
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
   /**
     * method to get the details about a particular artwork
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function editArtwork(Request $request)
    {
        try
        {
            $row_id     =   $request->id;
            $table_name =   $request->table_name;
            $erp_order_details = array();
            $order_details  =   DB::table($table_name)->where('id',$row_id)
                            ->select('*')->first();
			
            $result_str  =  str_replace("_"," ",str_replace("_orders","",$table_name));
            $qnty_id = '';
            foreach (explode(' ', $result_str) as $word)
            {
                $qnty_id .= strtolower($word[0]);
            }
			
            $quantity_details = DB::table($table_name.'_qty')
                                ->where($qnty_id.'_id',$order_details->id)->orderBy('id')->get();
			$template_file=DB::table('orchestra_template')->where('item_id',$order_details->template_id)->select('*')->get();

            $moq     =    DB::table('price_taxation')->where('template_id',$order_details->template_id)->where('customer_id',$order_details->customer_id)->where('buyer_id',1)->value('moq');

            // fetch erp_order_details table status
            foreach($quantity_details as $quantity)
            {
               $quantity_id = $quantity->id;
               $erp_order_details[] = DB::table('erp_order_details')->where('order_qnty_table_name','ilike', $table_name.'_quanty')->join('erp_status', 'erp_status.id', 'erp_order_details.status')->where('order_qnty_table_id','=', $quantity_id)->select('erp_order_details.id','erp_status.status_name')->first();
            }

            if ($order_details && $quantity_details)
            {
                $result = response()->json(['error'=> false,'message' =>'success','order_details'=>$order_details,'quantity_details'=>$quantity_details,'template_file'=>$template_file,'moq' => $moq, 'erp_details' => $erp_order_details],200);
            }
            else
            {
                $result = response()->json(['error'=> true,'message'=>'Error'],400);
            }
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
}

