<?php

namespace App\Http\Controllers\Customer\Orchestra;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;   
use App\Classes\Modules;
use Response;
use App\HangTagOrders;
use App\HangTagOrdersQty;
use App\SpecialTagOrders;
use App\SpecialTagOrdersQty;
use App\MainLabelOrders;
use App\MainLabelOrdersQty;
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
use View;
use DB;
use Auth;
use App\Classes\ERPOrderStatusCheck;

class OrderController extends Controller
{
    private $modules_permission;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('customer_auth');
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
    
    public function labelOrders()
    {
            $orders = DB::table('orchestra_item_details')->select('id','UVC','Collection as Season','SupplierName','Description','Theme','Style_Name','Disney','Quantity')->groupBy('UVC')->get();
            echo json_encode($orders);
    } 
    public function backLabelOrders()
    {
            $data['orders'] = DB::table('orchestra_item_details')->select('id','UVC','Collection as Season','SupplierName','Description','Theme','Style_Name','Disney','Quantity')->groupBy('UVC')->get();
            $data['buyer'] =DB::table('buyers')->select('*')->where('is_active','=',1)->get();
            echo json_encode($data);
    } 
    public function backStickerOrders()
    {
            $data['orders'] = DB::table('orchestra_parcel_details')->select('id','UVC','BuyerPONo','SupplierName','Theme','Quantity','IsSTD as Type')->get();
            $data['buyer'] =DB::table('buyers')->select('*')->where('is_active','=',1)->get();
            echo json_encode($data);
    }
    public function Orders()
    {
		 $company_id = Auth::guard('web_customer')->user()->company_id;
		
		
		 $customers = DB::table('customers')->select('*')->where('id',$company_id)->get();
		
		 $data['sticker_orders'] = DB::table('orchestra_parcel_details')->select(DB::raw('distinct on ("UVC") "UVC"'),'id',DB::raw("'-' as \"Season\""),'BuyerPONo','SupplierName','NomApple as Style_Name','Quantity','IsSTD as Type',DB::raw("'Stickers' as \"OrderType\""),'sticker_order_status as Order Status',DB::raw("'-' as \"Disney\""))->where('orchestra_parcel_details.FactoryCode', $customers{0}->factory_code)->groupBy('id','UVC')->get();

        $data['label_orders'] = DB::table('orchestra_parcel_details')->select(DB::raw('distinct on ("UVC") "UVC"'),'id','Season_Code as Season',DB::raw("'-' as \"BuyerPONo\""),'SupplierName','Theme as Description','NomApple as Style_Name','Licence_Code as Disney',DB::raw("'-' as \"Type\""),'Quantity',DB::raw("'Labels' as \"OrderType\""),'order_status as Order Status')->where('orchestra_parcel_details.FactoryCode', $customers{0}->factory_code)->groupBy('id','UVC')->get();
        
        echo json_encode($data);
    } 
    public function orchestraOrderStatus()
    {
            $customer_id = Auth::guard('web_customer')->user()->company_id;
            $ddmmyy="DD-MM-YYYY";
			$actions='-';
            $data['orchestra_hang_tag_orders'] = DB::table('orchestra_hang_tag_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_hang_tag_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_hang_tag_orders.template_front_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->select('orchestra_parcel_details.UVC',DB::raw("'-' as Season"),DB::raw("'-' as \"BuyerPONo\""),DB::raw("'Hang Tag' as \"ArtworkType\""),DB::raw("'Hang Tag' as \"TemplateName\""),'customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_hang_tag_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_hang_tag_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_hang_tag_orders.status as Artwork Status','orchestra_hang_tag_orders.pi_status as PI Status','orchestra_hang_tag_orders.po_status as PO Status','orchestra_hang_tag_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_hang_tag_orders' as \"tableName\""),'orchestra_hang_tag_orders.id  as tableID','orchestra_hang_tag_orders.po_file','customers.pi_required','customers.po_required','orchestra_hang_tag_orders.template_front_id as template_id','orchestra_hang_tag_orders.order_flag','customers.delivery_day_count','orchestra_hang_tag_orders.is_order_count_empty')->where('customers.id', $customer_id)->groupBy('orchestra_parcel_details.UVC','orchestra_parcel_details.SupplierName','orchestra_hang_tag_orders.created_at','orchestra_hang_tag_orders.delivery_date','orchestra_hang_tag_orders.status','orchestra_hang_tag_orders.pi_status','orchestra_hang_tag_orders.status','orchestra_hang_tag_orders.po_status','orchestra_hang_tag_orders.po_number','customers.pi_required','customers.id','orchestra_hang_tag_orders.id')->get();
		
            $data['orchestra_special_tag_orders'] = DB::table('orchestra_special_tag_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_special_tag_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_special_tag_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->select('orchestra_parcel_details.UVC',DB::raw("'-' as Season"),DB::raw("'-' as \"BuyerPONo\""),DB::raw("'Special Tag' as \"ArtworkType\""),DB::raw("'Special Tag' as \"TemplateName\""),'customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_special_tag_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_special_tag_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_special_tag_orders.status as Artwork Status','orchestra_special_tag_orders.pi_status as PI Status','orchestra_special_tag_orders.po_status as PO Status','orchestra_special_tag_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_special_tag_orders' as \"tableName\""),'orchestra_special_tag_orders.id  as tableID','orchestra_special_tag_orders.po_file','customers.pi_required','customers.po_required','orchestra_special_tag_orders.template_id','orchestra_special_tag_orders.order_flag','customers.delivery_day_count','orchestra_special_tag_orders.is_order_count_empty')->where('customers.id', $customer_id)->groupBy('orchestra_parcel_details.UVC','orchestra_parcel_details.SupplierName','orchestra_special_tag_orders.created_at','orchestra_special_tag_orders.delivery_date','orchestra_special_tag_orders.status','orchestra_special_tag_orders.pi_status','orchestra_special_tag_orders.status','orchestra_special_tag_orders.po_status','orchestra_special_tag_orders.po_number','customers.pi_required','customers.id','orchestra_special_tag_orders.id')->get();
     
               
            $data['orchestra_main_label_orders'] = DB::table('orchestra_main_label_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_main_label_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_main_label_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->select('orchestra_parcel_details.UVC',DB::raw("'-' as Season"),DB::raw("'-' as \"BuyerPONo\""),DB::raw("'Main & Size Label' as \"ArtworkType\""),'orchestra_main_label_orders.template_name as TemplateName','customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_main_label_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_main_label_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_main_label_orders.status as Artwork Status','orchestra_main_label_orders.pi_status as PI Status','orchestra_main_label_orders.po_status as PO Status','orchestra_main_label_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_main_label_orders' as \"tableName\""),'orchestra_main_label_orders.id  as tableID','orchestra_main_label_orders.po_file','customers.pi_required','customers.po_required','orchestra_main_label_orders.template_id','orchestra_main_label_orders.order_flag','customers.delivery_day_count','orchestra_main_label_orders.is_order_count_empty')->where('customers.id', $customer_id)->groupBy('orchestra_parcel_details.UVC','orchestra_parcel_details.SupplierName','orchestra_main_label_orders.created_at','orchestra_main_label_orders.delivery_date','orchestra_main_label_orders.status','orchestra_main_label_orders.pi_status','orchestra_main_label_orders.status','orchestra_main_label_orders.po_status','orchestra_main_label_orders.po_number','customers.pi_required','customers.id','orchestra_main_label_orders.id')->get();

            $data['orchestra_wash_care_label_orders'] = DB::table('orchestra_wash_care_label_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_wash_care_label_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_wash_care_label_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->select('orchestra_parcel_details.UVC',DB::raw("'-' as Season"),DB::raw("'-' as \"BuyerPONo\""),DB::raw("'WashCareLabel' as \"ArtworkType\""),'orchestra_wash_care_label_orders.template_name as TemplateName','customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_wash_care_label_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_wash_care_label_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_wash_care_label_orders.status as Artwork Status','orchestra_wash_care_label_orders.pi_status as PI Status','orchestra_wash_care_label_orders.po_status as PO Status','orchestra_wash_care_label_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_wash_care_label_orders' as \"tableName\""),'orchestra_wash_care_label_orders.id  as tableID','orchestra_wash_care_label_orders.po_file','customers.pi_required','customers.po_required','orchestra_wash_care_label_orders.template_id','orchestra_wash_care_label_orders.order_flag','customers.delivery_day_count','orchestra_wash_care_label_orders.is_order_count_empty')->where('customers.id', $customer_id)->groupBy('orchestra_parcel_details.UVC','orchestra_parcel_details.SupplierName','orchestra_wash_care_label_orders.created_at','orchestra_wash_care_label_orders.delivery_date','orchestra_wash_care_label_orders.status','orchestra_wash_care_label_orders.pi_status','orchestra_wash_care_label_orders.status','orchestra_wash_care_label_orders.po_status','orchestra_wash_care_label_orders.po_number','customers.pi_required','customers.id','orchestra_wash_care_label_orders.id')->get();

            $data['orchestra_solid_carton_sticker_orders'] = DB::table('orchestra_solid_carton_sticker_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_solid_carton_sticker_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_solid_carton_sticker_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->select(DB::raw('distinct on (orchestra_solid_carton_sticker_orders.uvc,orchestra_solid_carton_sticker_orders.po) "UVC"'),DB::raw("'-' as Season"),DB::raw("'-' as \"BuyerPONo\""),DB::raw("'Solid Carton Stickers' as \"ArtworkType\""),DB::raw("'Solid Carton Stickers' as \"TemplateName\""),'customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_solid_carton_sticker_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_solid_carton_sticker_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_solid_carton_sticker_orders.status as Artwork Status','orchestra_solid_carton_sticker_orders.pi_status as PI Status','orchestra_solid_carton_sticker_orders.po_status as PO Status','orchestra_solid_carton_sticker_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_solid_carton_sticker_orders' as \"tableName\""),'orchestra_solid_carton_sticker_orders.id  as tableID','orchestra_solid_carton_sticker_orders.po_file','customers.pi_required','customers.po_required','orchestra_solid_carton_sticker_orders.template_id','orchestra_solid_carton_sticker_orders.order_flag','customers.delivery_day_count','orchestra_solid_carton_sticker_orders.is_order_count_empty')->where('orchestra_parcel_details.IsSTD',0)->where('customers.id', $customer_id)->groupBy('orchestra_solid_carton_sticker_orders.uvc','orchestra_parcel_details.UVC','orchestra_solid_carton_sticker_orders.po','orchestra_parcel_details.SupplierName','orchestra_solid_carton_sticker_orders.created_at','orchestra_solid_carton_sticker_orders.delivery_date','orchestra_solid_carton_sticker_orders.status','orchestra_solid_carton_sticker_orders.pi_status','orchestra_solid_carton_sticker_orders.status','orchestra_solid_carton_sticker_orders.po_status','orchestra_solid_carton_sticker_orders.po_number','customers.pi_required','customers.id','orchestra_solid_carton_sticker_orders.id')->get();
              
            $data['orchestra_solid_poly_bag_sticker_orders'] = DB::table('orchestra_solid_poly_bag_sticker_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_solid_poly_bag_sticker_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_solid_poly_bag_sticker_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->select(DB::raw('distinct on (orchestra_solid_poly_bag_sticker_orders.uvc,orchestra_solid_poly_bag_sticker_orders.po) "UVC"'),DB::raw("'-' as Season"),DB::raw("'-' as \"BuyerPONo\""),DB::raw("'Solid Poly Bag Stickers' as \"ArtworkType\""),DB::raw("'Solid Poly Bag Stickers' as \"TemplateName\""),'customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_solid_poly_bag_sticker_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_solid_poly_bag_sticker_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_solid_poly_bag_sticker_orders.status as Artwork Status','orchestra_solid_poly_bag_sticker_orders.pi_status as PI Status','orchestra_solid_poly_bag_sticker_orders.po_status as PO Status','orchestra_solid_poly_bag_sticker_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_solid_poly_bag_sticker_orders' as \"tableName\""),'orchestra_solid_poly_bag_sticker_orders.id  as tableID','orchestra_solid_poly_bag_sticker_orders.po_file','customers.pi_required','customers.po_required','orchestra_solid_poly_bag_sticker_orders.template_id','orchestra_solid_poly_bag_sticker_orders.order_flag','customers.delivery_day_count','orchestra_solid_poly_bag_sticker_orders.is_order_count_empty')->where('orchestra_parcel_details.IsSTD',0)->where('customers.id', $customer_id)->groupBy('orchestra_solid_poly_bag_sticker_orders.uvc','orchestra_parcel_details.UVC','orchestra_solid_poly_bag_sticker_orders.po','orchestra_parcel_details.SupplierName','orchestra_solid_poly_bag_sticker_orders.created_at','orchestra_solid_poly_bag_sticker_orders.delivery_date','orchestra_solid_poly_bag_sticker_orders.status','orchestra_solid_poly_bag_sticker_orders.pi_status','orchestra_solid_poly_bag_sticker_orders.po_status','orchestra_solid_poly_bag_sticker_orders.po_number','orchestra_solid_poly_bag_sticker_orders.id','customers.pi_required','customers.id')->get();
    
            $data['orchestra_standard_carton_sticker_orders'] = DB::table('orchestra_standard_carton_sticker_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_standard_carton_sticker_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_standard_carton_sticker_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->select(DB::raw('distinct on (orchestra_standard_carton_sticker_orders.uvc,orchestra_standard_carton_sticker_orders.po) "UVC"'),DB::raw("'-' as Season"),DB::raw("'-' as \"BuyerPONo\""),DB::raw("'Standard Carton Stickers' as \"ArtworkType\""),DB::raw("'Standard Carton Stickers' as \"TemplateName\""),'customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_standard_carton_sticker_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_standard_carton_sticker_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_standard_carton_sticker_orders.status as Artwork Status','orchestra_standard_carton_sticker_orders.pi_status as PI Status','orchestra_standard_carton_sticker_orders.po_status as PO Status','orchestra_standard_carton_sticker_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_standard_carton_sticker_orders' as \"tableName\""),'orchestra_standard_carton_sticker_orders.id  as tableID','orchestra_standard_carton_sticker_orders.po_file','customers.pi_required','customers.po_required','orchestra_standard_carton_sticker_orders.template_id','orchestra_standard_carton_sticker_orders.order_flag','customers.delivery_day_count','orchestra_standard_carton_sticker_orders.is_order_count_empty')->where('orchestra_parcel_details.IsSTD',1)->where('customers.id', $customer_id)->groupBy('orchestra_standard_carton_sticker_orders.uvc','orchestra_parcel_details.UVC','orchestra_standard_carton_sticker_orders.po','orchestra_parcel_details.SupplierName','orchestra_standard_carton_sticker_orders.created_at','orchestra_standard_carton_sticker_orders.delivery_date','orchestra_standard_carton_sticker_orders.status','orchestra_standard_carton_sticker_orders.pi_status','orchestra_standard_carton_sticker_orders.po_status','orchestra_standard_carton_sticker_orders.po_number','orchestra_standard_carton_sticker_orders.id','customers.pi_required','customers.id')->get();

            $data['orchestra_standard_polybag_sticker_orders'] = DB::table('orchestra_standard_polybag_sticker_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_standard_polybag_sticker_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_standard_polybag_sticker_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->select(DB::raw('distinct on (orchestra_standard_polybag_sticker_orders.uvc,orchestra_standard_polybag_sticker_orders.po) "UVC"'),DB::raw("'-' as Season"),DB::raw("'-' as \"BuyerPONo\""),DB::raw("'Standard Poly Bag Stickers' as \"ArtworkType\""),DB::raw("'Standard Poly Bag Stickers' as \"TemplateName\""),'customers.id as customer_id','orchestra_parcel_details.SupplierName as SupplierName',DB::raw("to_char(orchestra_standard_polybag_sticker_orders.created_at,"."'".$ddmmyy."'".") as \"OrderDate\""),DB::raw("to_char(orchestra_standard_polybag_sticker_orders.delivery_date,"."'".$ddmmyy."'".") as \"DeliveryDate\""),'orchestra_standard_polybag_sticker_orders.status as Artwork Status','orchestra_standard_polybag_sticker_orders.pi_status as PI Status','orchestra_standard_polybag_sticker_orders.po_status as PO Status','orchestra_standard_polybag_sticker_orders.po_number as PO Number',DB::raw("'".$actions."'".' as "Actions"'),DB::raw("'orchestra_standard_polybag_sticker_orders' as \"tableName\""),'orchestra_standard_polybag_sticker_orders.id  as tableID','orchestra_standard_polybag_sticker_orders.po_file','customers.pi_required','customers.po_required','orchestra_standard_polybag_sticker_orders.template_id','orchestra_standard_polybag_sticker_orders.order_flag','customers.delivery_day_count','orchestra_standard_polybag_sticker_orders.is_order_count_empty')->where('orchestra_parcel_details.IsSTD',1)->where('customers.id', $customer_id)->groupBy('orchestra_standard_polybag_sticker_orders.uvc','orchestra_parcel_details.UVC','orchestra_standard_polybag_sticker_orders.po','orchestra_parcel_details.SupplierName','orchestra_standard_polybag_sticker_orders.created_at','orchestra_standard_polybag_sticker_orders.delivery_date','orchestra_standard_polybag_sticker_orders.status','orchestra_standard_polybag_sticker_orders.pi_status','orchestra_standard_polybag_sticker_orders.po_status','orchestra_standard_polybag_sticker_orders.po_number','orchestra_standard_polybag_sticker_orders.id','customers.pi_required','customers.id')->get();

     
            echo json_encode($data);
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
                                        ->orderBy('orchestra_parcel_details.id')
                                        ->get();

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

            $data['barcode'] = DB::table('orchestra_parcel_details')->select('*')->where('UVC',$uvc)->groupBy('id','BuyerPONo')->get();
        
           $total_qty = DB::table('orchestra_parcel_details')->select('*',DB::raw("sum(\"Quantity\") as total_qty"))->where('UVC',$uvc)->groupBy('id','UVC')->get();
            $data['total_qty']=$total_qty{0}->total_qty;
            // Licence_Code = DI for disney
            if($total_qty{0}->Licence_Code === 'DI')
            {
                $orchestra_template = DB::table('orchestra_template')->select('*')->where('id','5')->get();
                $data['orchestra_template']=$orchestra_template{0}->template_file;
                $data['orchestra_template_id']=5;
                
                $data['orchestra_wash_template_front']="/storage/app/template/Orchestra/WCL/sticker1-disney.svg";
                $data['orchestra_wash_template_back']="/storage/app/template/Orchestra/WCL/sticker2.svg";
                $data['orchestra_wash_template_middle']="/storage/app/template/Orchestra/WCL/sticker3.svg";
				
                $data['orchestra_wash_template_id']=5;
                
                $orchestra_template_front = DB::table('orchestra_template')->select('*')->where('item_id','2')->where('disney',1)->get();
                $data['orchestra_template_front']=$orchestra_template_front;
				
				 $treeviewData= DB::table("orchestra_item_master")->select('id',DB::raw("(CASE WHEN ref_no IS NULL THEN item_name ELSE CONCAT(item_name,' - ',ref_no) END) as text"),'parent_id')->orWhere('id','78')->orWhere('tag_name','disney')->orderBy('item_name', 'asc')->get()->toArray();
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
                $orchestra_template = DB::table('orchestra_template')->select('*')->where('id','4')->get();
                $data['orchestra_template']=$orchestra_template{0}->template_file;
                $data['orchestra_template_id']=4;
                
                $data['orchestra_wash_template_front']="/storage/app/template/Orchestra/WCL/sticker1.svg";
                $data['orchestra_wash_template_back']="/storage/app/template/Orchestra/WCL/sticker2.svg";
                $data['orchestra_wash_template_middle']="/storage/app/template/Orchestra/WCL/sticker3.svg";
                $data['orchestra_wash_template_id']=4;
                
                $orchestra_template_front = DB::table('orchestra_template')->select('*')->where('item_id','2')->where('disney', 0)->get();
                $data['orchestra_template_front']=$orchestra_template_front;
				
				
				 $treeviewData= DB::table("orchestra_item_master")->select('id',DB::raw("(CASE WHEN ref_no IS NULL THEN item_name ELSE CONCAT(item_name,' - ',ref_no) END) as text"),'parent_id')->orWhere('id','78')->orWhere('tag_name','hang')->orWhere('id','79')->orWhere('id','80')->orderBy('item_name', 'asc')->get()->toArray();
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
         
        
        $treeviewspecial= DB::table("orchestra_item_master")->select('id',DB::raw("(CASE WHEN ref_no IS NULL THEN item_name ELSE CONCAT(item_name,' - ',ref_no) END) as text"),'parent_id')->orWhere('id','78')->orWhere('id','79')->orWhere('id','80')->orWhere('tag_name','special')->orderBy('item_name', 'asc')->get()->toArray();
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
                $data['orchestra_hang_tag_orders']=DB::table('orchestra_hang_tag_orders')->select('*',DB::raw("'Hang Tag' as \"TemplateName\""))->where('uvc',$uvc)->get();
        if(count($data['orchestra_hang_tag_orders']) > 0)
        {
            $data['orchestra_hang_tag_orders_qty']=DB::table('orchestra_hang_tag_orders_qty')->select('*')->where('oht_id',$data['orchestra_hang_tag_orders']{0}->id)->get();
        }
        else
        {
            $data['orchestra_hang_tag_orders_qty']="";
        }
         $data['orchestra_special_tag_orders']=DB::table('orchestra_special_tag_orders')->select('*',DB::raw("'Special Tag' as \"TemplateName\""))->where('uvc',$uvc)->get();
        if(count($data['orchestra_special_tag_orders']) > 0)
        {
            $data['orchestra_special_tag_orders_qty']=DB::table('orchestra_special_tag_orders_qty')->select('*')->where('ost_id',$data['orchestra_special_tag_orders']{0}->id)->get();
        }
        else
        {
            $data['orchestra_special_tag_orders_qty']="";
        }
        
        $data['orchestra_main_label_orders']=DB::table('orchestra_main_label_orders')->select('*','orchestra_main_label_orders.template_name as TemplateName')->where('uvc',$uvc)->get();
        foreach($data['orchestra_main_label_orders'] as $value)
        {
            $orchestra_main_label_orders_qty=DB::table('orchestra_main_label_orders_qty')->select('*')->where('oml_id',$value->id)->get();
            $value->qty=$orchestra_main_label_orders_qty;
        }
        
        $data['orchestra_wash_care_label_orders']=DB::table('orchestra_wash_care_label_orders')->select('*','orchestra_wash_care_label_orders.template_name as TemplateName')->where('uvc',$uvc)->get();
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
					$data['merchandisers']=DB::table('merchandisers')->select('*')->get();
				}
				else
				{
					$data['merchandisers']="";
				}
				
                $data['orchestra_care_symbols'] = DB::table('orchestra_care_symbols')->select('*')->get();
                $data['orchestra_composition'] = DB::table('orchestra_compositions')->select('*')->get();
                $data['orchestra_countries'] = DB::table('orchestra_countries')->select('*')->get();
                $data['orchestra_care_instruction'] = DB::table('orchestra_care_instruction')->select('*')->get(); 
                $data['orchestra_parts'] = DB::table('orchestra_parts')->select('*')->get();
		 		$pro_family = DB::table('orchestra_product_family')->select('*')->where('code',$code)->get();
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
        
         $data['orchestra_parcel_details']= DB::table('orchestra_parcel_details')->select('*','Standard_Code as ParcelCode')->where('BuyerPONo',$po)->groupBy('id','BuyerPONo')->get();
        
        $data['orchestra_age_group']= DB::table('orchestra_age_group')->select('*')->get();      
        
        $data['orchestra_solid_carton_sticker_orders']=DB::table('orchestra_solid_carton_sticker_orders')->select('*')->where('uvc',$uvc)->where('po',$po)->get();
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
        
        $data['orchestra_solid_poly_bag_sticker_orders']=DB::table('orchestra_solid_poly_bag_sticker_orders')->select('*')->where('uvc',$uvc)->where('po',$po)->get();
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
            $orchestra_solid_poly_bag_sticker_orders_qty=DB::table('orchestra_solid_poly_bag_sticker_order_qty')->select('*')->where('ospbs_id',$value1->id)->get();
            $value1->qty=$orchestra_solid_poly_bag_sticker_orders_qty;
        }
        
        $data['orchestra_standard_carton_sticker_orders']=DB::table('orchestra_standard_carton_sticker_orders')->select('*')->where('uvc',$uvc)->where('po',$po)->get();
        if(count($data['orchestra_standard_carton_sticker_orders']) > 0)
        {
            $data['std_carton_id']=$data['orchestra_standard_carton_sticker_orders'][0]->id;
        }
        else
        {
            $data['std_carton_id']="null";
        }
        
        $data['orchestra_standard_polybag_sticker_orders']=DB::table('orchestra_standard_polybag_sticker_orders')->select('*')->where('uvc',$uvc)->where('po',$po)->get();
        if(count($data['orchestra_standard_polybag_sticker_orders']) > 0)
        {
             $data['std_poly_id']=$data['orchestra_standard_polybag_sticker_orders'][0]->id;
        }
        else
        {
             $data['std_poly_id']="null";
        }
        
        if($data['orchestra_parcel_details']{0}->IsSTD == 1)
        {
            $orchestra_template = DB::table('orchestra_template')->select('*')->where('item_id','179')->get();
            $data['carton_template']=$orchestra_template{0}->template_file;
            $data['carton_template_id']=179;
			
			$factory_code = $data['orchestra_parcel_details']{0}->FactoryCode;
			$customer_id = DB::table('customers')->select('*')->where('factory_code',$factory_code)->get();
			if(count($customer_id) > 0)
			{
                $carton_moq = DB::table('price_taxation')->select('*')->where('customer_id',$customer_id{0}->id)->where('buyer_id',1)->where('template_id',179)->get();
                
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
			
            $orchestra_template = DB::table('orchestra_template')->select('*')->where('item_id','180')->get();
            $data['polybag_template']=$orchestra_template{0}->template_file;
            $data['template_name']="Standard";
            $data['polybag_template_id']=180;
			if(count($customer_id) > 0)
			{
                $polybag_moq = DB::table('price_taxation')->select('*')->where('customer_id',$customer_id{0}->id)->where('buyer_id',1)->where('template_id',180)->get();
                
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
            $orchestra_template = DB::table('orchestra_template')->select('*')->where('item_id','177')->get();
            $data['carton_template']=$orchestra_template{0}->template_file;
            $data['carton_template_id']=177;
			
			$factory_code = $data['orchestra_parcel_details']{0}->FactoryCode;
			$customer_id = DB::table('customers')->select('*')->where('factory_code',$factory_code)->get();
			
			if(count($customer_id) > 0)
			{
				$carton_moq = DB::table('price_taxation')->select('*')->where('customer_id',$customer_id{0}->id)->where('buyer_id',1)->where('template_id',177)->get();
			
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
			
			
            $orchestra_template = DB::table('orchestra_template')->select('*')->where('item_id','178')->get();
            $data['polybag_template']=$orchestra_template{0}->template_file;
            $data['template_name']="Solid";
            $data['polybag_template_id']=178;
			
			if(count($customer_id) > 0)
			{
				$polybag_moq = DB::table('price_taxation')->select('*')->where('customer_id',$customer_id{0}->id)->where('buyer_id',1)->where('template_id',178)->get();

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
        $size = explode(',',$request->size);
        $uvc =$request->uvc;
		$cm =$request->cm;
		$in =$request->in;
        $id =$request->id;
		$factory = DB::table("orchestra_parcel_details")->join('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('UVC',$uvc)->select('*')->get();
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
			$status="Approved";
		}
		else
		{
			$status="";
			$po_status="";
			$pi_status="";
		}
		
        if($id != "null")
        {
            HangTagOrders::where('id',$id)
                   ->update([
                        'uvc' => $uvc,
                        'template_front_id'      => $template_front_id,
                        'template_back_id'      => $template_back_id,
                        'svg_path'    => "", 
                        'status'    => $status,
					    'cm'    => $cm,
					    'in'    => $in,
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
                                        'quantity'    => $value
                             ]);
                    $i++;
                }
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)
                        ->update([
                          'order_status' => "Order Placed"

            ]);
        }
        else
        {
             $inserted = HangTagOrders::create([
                                    'uvc' => $uvc,
                                    'template_front_id'      => $template_front_id,
                                    'template_back_id'      => $template_back_id,
                                    'svg_path'    => "", 
                                    'status'    => $status,
				 					'cm'    => $cm,
				 					'in'    => $in,
				 					'pi_status'           => $pi_status,
				 					'po_status'           => $po_status,
                         ]);
            $i=0;
            foreach($tot_qty_table as $value)
            {
                 HangTagOrdersQty::create([
                                    'oht_id' => $inserted->id,
                                    'size'      => $size[$i],
                                    'uvc_tco'      => $uvc_tco[$i],
                                    'quantity'    => $value
                         ]);
                $i++;
            }
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)
                        ->update([
                          'order_status' => "Order Placed"

            ]);
        }
           
    }
	
	
	/**
     * This Function is used to save the Special Tag Details.
     *
     * @return Void
     */
	
    public function setSpecialTag(Request $request)
    {
        //$delivery_date = $request->delivery_date;
        $template_front_id = $request->template_front_id;
        //$tot_qty_table = explode(',',$request->tot_qty_table);
		$tot_qty_table =$request->tot_qty_table;
        $uvc_tco = explode(',',$request->uvc_tco);
        $size = explode(',',$request->size);
        $uvc =$request->uvc;
        $id =$request->id;
		$factory = DB::table("orchestra_parcel_details")->join('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('UVC',$uvc)->select('*')->get();
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
			$status="Approved";
		}
		else
		{
			$status="";
			$po_status="";
			$pi_status="";
		}
        if($id != "null")
        {
            SpecialTagOrders::where('id',$id)
                   ->update([
                        'uvc' => $uvc,
                        'template_id'      => $template_front_id, 
                        'svg_path'    => "",
					    'final_qty'    => $tot_qty_table, 
                        'status'    => $status,
					    'pi_status'           => $pi_status,
				 		'po_status'           => $po_status,
                  ]);
//                $deleted = SpecialTagOrdersQty::where('ost_id', '=', $id)
//                            ->delete();
//                $i=0;
//                foreach($tot_qty_table as $value)
//                {
//                     SpecialTagOrdersQty::create([
//                                        'ost_id' => $id,
//                                        'size'      => $size[$i],
//                                        'uvc_tco'      => $uvc_tco[$i],
//                                        'quantity'    => $value
//                             ]);
//                    $i++;
//                }
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)
                        ->update([
                          'order_status' => "Order Placed"

            ]);
        }
        else
        {
             $inserted = SpecialTagOrders::create([
                                    'uvc' => $uvc,
                                    'template_id'      => $template_front_id, 
                                    'svg_path'    => "", 
				 					'final_qty'    => $tot_qty_table,
                                    'status'    => $status,
				 					'pi_status'           => $pi_status,
				 					'po_status'           => $po_status,
                         ]);
//            $i=0;
//            foreach($tot_qty_table as $value)
//            {
//                 SpecialTagOrdersQty::create([
//                                    'ost_id' => $inserted->id,
//                                    'size'      => $size[$i],
//                                    'uvc_tco'      => $uvc_tco[$i],
//                                    'quantity'    => $value
//                         ]);
//                $i++;
//            }
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)
                        ->update([
                          'order_status' => "Order Placed"

            ]);
        }
           
    } 
	
	/**
     * This Function is used to save the Main and Size Labels Details.
     *
     * @return Void
     */
	
    public function setMainLabel(Request $request)
    {
        //$delivery_date = $request->delivery_date;
        $template_front_id = $request->template_front_id; 
        $template_name = $request->template_name;
        $tot_qty_table = explode(',',$request->tot_qty_table);
        $uvc_tco = explode(',',$request->uvc_tco);
        $size = explode(',',$request->size);
		$main_label_count = explode(',',$request->main_label_count);
        $uvc =$request->uvc;
		$main_label_qty =$request->main_label_qty;
        $id =$request->id;
		$main_flag =$request->main_flag;
		$factory = DB::table("orchestra_parcel_details")->join('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('UVC',$uvc)->select('*')->get();
		
		if($main_flag == "size")
		{
			$tots="";
		}
		else
		{
			$tots=$main_label_qty;
		}
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
			$status="Approved";
		}
		else
		{
			$status="";
			$po_status="";
			$pi_status="";
		}
        if($id != "null")
        {
              MainLabelOrders::where('id',$id)
                   ->update([
                        'uvc' => $uvc,
                        'template_id'      => $template_front_id,
                        'template_name'      => $template_name,
                        'svg_path'    => "", 
					    'final_qty'    => $tots, 
					    'total_flag'    => $main_flag, 
                        'status'    => $status,
					    'pi_status'           => $pi_status,
				 		'po_status'           => $po_status,
                  ]);
                $deleted = MainLabelOrdersQty::where('oml_id', '=', $id)
                            ->delete();
                $i=0;
                foreach($tot_qty_table as $value)
                {
                     MainLabelOrdersQty::create([
                                        'oml_id' => $id,
                                        'size'      => $size[$i],
                                        'uvc_tco'      => $uvc_tco[$i],
                                        'quantity'    => $value,
						 				'main_qty'=>$main_label_count[$i]
                             ]);
                    $i++;
                }
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)
                        ->update([
                          'order_status' => "Order Placed"

            ]);
        }
        else
        {
               $inserted = MainLabelOrders::create([
                                'uvc' => $uvc,
                                'template_id'      => $template_front_id,
                                'template_name'      => $template_name,
				   				'final_qty'    => $tots, 
				   				'total_flag'    => $main_flag, 
                                'svg_path'    => "", 
                                'status'    => $status,
				   				'pi_status'           => $pi_status,
				 				'po_status'           => $po_status,
                     ]);
                $i=0;
                foreach($tot_qty_table as $value)
                {
                     MainLabelOrdersQty::create([
                                        'oml_id' => $inserted->id,
                                        'size'      => $size[$i],
                                        'uvc_tco'      => $uvc_tco[$i],
                                        'quantity'    => $value,
						 				'main_qty'=>$main_label_count[$i]
                             ]);
                    $i++;
                }
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)
                        ->update([
                          'order_status' => "Order Placed"

            ]);
        }
       
           
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
        $uvc =$request->uvc;
        $height =$request->height; 
        $age =$request->age;
        $po =$request->po;
        $carton_id =$request->carton_id; 
        $poly_id =$request->poly_id; 
        $group_id =$request->group_id;
		$factory = DB::table("orchestra_parcel_details")->join('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('UVC',$uvc)->select('*')->get();
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
			$status="Approved";
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
                        'svg_path'         => "", 
                        'status'           => $status,
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
                                        'quantity'    => $value,
                                        'quantity_sets'    => $value
                             ]);
                    $j++;
                }
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)->where('BuyerPONo',$po)
                        ->update([
                          'sticker_order_status' => "Order Placed"

            ]);
        }
        else
        {
              $inserted_poly = SolidPolyStickerOrders::create([
                                'uvc' => $uvc,
                                'template_id'      => $polybag_template_id,
                                'po'               => $po,
                                'height'           => $height, 
                                'svg_path'    => "", 
                                'status'    => $status,
				  				'pi_status'           => $pi_status,
				 				'po_status'           => $po_status,
                                'group_id'    => $age,
                     ]);
                $j=0;
                foreach($tot_qty_poly as $value)
                {
                     SolidPolyStickerOrdersQty::create([
                                        'ospbs_id' => $inserted_poly->id,
                                        'size'      => $size[$j],
                                        'quantity'    => $value,
                                        'quantity_sets'    => $value
                             ]);
                    $j++;
                }
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)->where('BuyerPONo',$po)
                        ->update([
                          'sticker_order_status' => "Order Placed"

            ]);
        }
        if($carton_id != "null")
        {
              SolidCartonStickerOrders::where('id',$carton_id)
                   ->update([
                        'uvc'              => $uvc,
                        'template_id'      => $carton_template_id,
                        'po'               => $po,
                        'height'           => $height, 
                        'svg_path'         => "", 
                        'status'           => $status,
					    'pi_status'           => $pi_status,
				 		'po_status'           => $po_status,
                  ]);
                $deleted = SolidCartonStickerOrdersQty::where('oscs_id', '=', $carton_id)
                            ->delete();
                $i=0;
                foreach($tot_qty_carton as $value)
                {
                     SolidCartonStickerOrdersQty::create([
                                        'oscs_id' => $carton_id,
                                        'size'      => $size[$i],
                                        'quantity'      => $value,
                                        'quantity_sets'    => $value
                             ]);
                    $i++;
                }
			
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)->where('BuyerPONo',$po)
                        ->update([
                          'sticker_order_status' => "Order Placed"

            ]);
            
            
        }
        else
        {
               $inserted = SolidCartonStickerOrders::create([
                                'uvc' => $uvc,
                                'template_id'      => $carton_template_id,
                                'po'               => $po,
                                'height'           => $height, 
                                'svg_path'    => "", 
                                'status'    => $status,
				   				'pi_status'           => $pi_status,
				 				'po_status'           => $po_status,
                     ]);
                $i=0;
                foreach($tot_qty_carton as $value)
                {
                     SolidCartonStickerOrdersQty::create([
                                        'oscs_id' => $inserted->id,
                                        'size'      => $size[$i],
                                        'quantity'    => $value,
                                        'quantity_sets'    => $value
                             ]);
                    $i++;
                }    
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)->where('BuyerPONo',$po)
                        ->update([
                          'sticker_order_status' => "Order Placed"

            ]);
        }
       
           
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
        $polybag_template_id = $request->polybag_template_id;
        $uvc =$request->uvc;
        $height =$request->height; 
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
		
		$factory = DB::table("orchestra_parcel_details")->join('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('UVC',$uvc)->select('*')->get();
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
			$status="Approved";
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
                        'no_of_carton'    => $no_of_carton, 
                        'total_polybag'    => $total_polybag, 
                        'final_order_qty_sets'    => $final_order_qty_sets, 
                        'order_qty'    => $order_qty, 
                        'final_order_qty'    => $final_order_qty, 
                        'svg_path'         => "", 
                        'status'           => $status,
					   	'pi_status'           => $pi_status,
					   	'po_status'           => $po_status,
                        'group_id'    => $age,
                  ]);
			
					$deleted = StandardPolyStickersQty::where('ospbs_id', '=', $poly_id)
                            ->delete();
            
               
                     StandardPolyStickersQty::create([
                                        'ospbs_id' => $poly_id,
                                        'size'      => "",
                                        'quantity'    => $total_polybag,
                                        'quantity_sets'    => $final_order_qty_sets
                     ]);
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)->where('BuyerPONo',$po)
                        ->update([
                          'sticker_order_status' => "Order Placed"

            ]);
        }
        else
        {
              $inserted_poly = StandardPolyStickers::create([
                                'uvc' => $uvc,
                                'template_id'      => $polybag_template_id,
                                'po'               => $po,
                                'height'           => $height, 
                                'no_of_carton'    => $no_of_carton, 
                                'total_polybag'    => $total_polybag, 
                                'final_order_qty_sets'    => $final_order_qty_sets, 
                                'order_qty'    => $order_qty, 
                                'final_order_qty'    => $final_order_qty,
                                'svg_path'    => "", 
                                'status'    => $status,
						  		'pi_status'           => $pi_status,
								'po_status'           => $po_status,
                                'group_id'    => $age,
                     ]);
			
				StandardPolyStickersQty::create([
                                        'ospbs_id' => $inserted_poly->id,
                                        'size'      => "",
                                        'quantity'    => $total_polybag,
                                        'quantity_sets'    => $final_order_qty_sets
                             ]);
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)->where('BuyerPONo',$po)
                        ->update([
                          'sticker_order_status' => "Order Placed"

            ]);
                
        }
        if($carton_id != "null")
        {
              StandardCartonStickers::where('id',$carton_id)
                   ->update([
                        'uvc'              => $uvc,
                        'template_id'      => $carton_template_id,
                        'po'               => $po,
                        'height'           => $height, 
                        'no_of_carton'    => $no_of_carton, 
                        'total_polybag'    => $total_polybag, 
                        'final_order_qty_sets'    => $final_order_qty_sets, 
                        'order_qty'    => $order_qty, 
                        'final_order_qty'    => $final_order_qty,
                        'svg_path'         => "", 
                        'status'           => $status,
					    'pi_status'           => $pi_status,
					   	'po_status'           => $po_status,
                  ]);
			
			$deleted = StandardCartonStickersQty::where('oscs_id', '=', $carton_id)
                            ->delete();
            
               
                     StandardCartonStickersQty::create([
                                        'oscs_id' => $carton_id,
                                        'size'      => "",
                                        'quantity'    => $order_qty,
                                        'quantity_sets'    => $final_order_qty
                     ]);
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)->where('BuyerPONo',$po)
                        ->update([
                          'sticker_order_status' => "Order Placed"

            ]);
        }
        else
        {
               $inserted = StandardCartonStickers::create([
                                'uvc' => $uvc,
                                'template_id'      => $carton_template_id,
                                'po'               => $po,
                                'height'           => $height, 
                                'no_of_carton'    => $no_of_carton, 
                                'total_polybag'    => $total_polybag, 
                                'final_order_qty_sets'    => $final_order_qty_sets, 
                                'order_qty'    => $order_qty, 
                                'final_order_qty'    => $final_order_qty,
                                'svg_path'    => "", 
                                'status'    => $status,
				   				'pi_status'           => $pi_status,
				   				'po_status'           => $po_status,
                     ]);
			
				StandardCartonStickersQty::create([
                                        'oscs_id' => $inserted->id,
                                        'size'      => "",
                                        'quantity'    => $order_qty,
                                        'quantity_sets'    => $final_order_qty
                      ]);
			DB::table("orchestra_parcel_details")->where('UVC',$uvc)->where('BuyerPONo',$po)
                        ->update([
                          'sticker_order_status' => "Order Placed"

            ]);
        }
       
           
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
        $size = explode(',',$request->size);
        $uvc =$request->uvc;
        $id =$request->id;
		$cm =$request->cm;
		$in =$request->in;
        $washing_select = $request->washing_select; 
        $whitening_select = $request->whitening_select; 
        $dryer_select = $request->dryer_select; 
        $ironing_select= $request->ironing_select; 
        $cleaning_select= $request->cleaning_select; 
        $merchandisers= $request->merchandisers; 
        $made_in_select = $request->made_in_select; 
        $care_instruction_select = $request->care_instruction_select; 
		
		$factory = DB::table("orchestra_parcel_details")->join('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->where('UVC',$uvc)->select('*')->get();
		
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
              WashLabelOrders::where('id',$id)
                   ->update([
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
                        'care_instruction_id'      => $care_instruction_select,
                        'svg_path'    => "", 
					    'cm'    => $cm, 
					    'in'    => $in, 
                        'status'    => "Buyer Approval Pending",
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
                                'care_instruction_id'      => $care_instruction_select,
                                'svg_path'    => "", 
				   				'cm'    => $cm, 
				   				'in'    => $in, 
                                'status'    => "Buyer Approval Pending",
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
        }
       
           
    }
    public function removeMainLabel(Request $request)
    {
         $deleted = MainLabelOrders::where('id', '=', $request->id)->delete();
    }
    public function removeWashLabel(Request $request)
    {
         $deleted = WashLabelOrders::where('id', '=', $request->id)->delete();
    }
}

