<?php

namespace App\Http\Controllers\Admin\Orchestra;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;   
use App\Classes\Modules;
use App\Classes\ProfomaInvoice;
use Response;
use Excel;
use App\HangTagOrders;
use App\ProfomaInvoice as PI;
use App\ProfomaInvoiceItemDetails;
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
use View;
use DB;

class OrderManagementCntrl extends Controller
{
    private $modules_permission;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the order dashboard.
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
    
    /*public function labelOrders()
    {
            $orders = DB::table('orchestra_item_details')->select('id','UVC','Collection as Season','SupplierName','Description','Theme','Style_Name','Disney','Quantity')->groupBy('UVC')->get();
            echo json_encode($orders);
    } 
    public function backLabelOrders()
    {
            $data['orders'] = DB::table('orchestra_item_details')->select('id','UVC','Collection as Season','SupplierName','Description','Theme','Style_Name','Disney','Quantity')->groupBy('UVC')->get();
            $data['buyer'] =DB::table('buyers')->select('*')->get();
            echo json_encode($data);
    } 
    public function backStickerOrders()
    {
            $data['orders'] = DB::table('orchestra_parcel_details')->select('id','UVC','BuyerPONo','SupplierName','Theme','Quantity','IsSTD as Type')->get();
            $data['buyer'] =DB::table('buyers')->select('*')->get();
            echo json_encode($data);
    }*/
	
	
	 /**
     * Show the orders List.
     *
     * @return Json Value
     */
	
    public function Orders()
    {
            $data['sticker_orders'] = DB::table('orchestra_parcel_details')->select('id','UVC',DB::raw('"-" as Season'),'BuyerPONo','SupplierName','NomApple as Style_Name','Quantity','IsSTD as Type',DB::raw('"Stickers" as OrderType'),DB::raw('"-" as Disney'))->get();
            $data['label_orders'] = DB::table('orchestra_item_details')->select('id','UVC','Collection as Season',DB::raw('"-" as BuyerPONo'),'SupplierName','Description','Style_Name','Disney',DB::raw('"-" as Type'),'Quantity',DB::raw('"Labels" as OrderType'))->groupBy('UVC')->get();
            echo json_encode($data);
    } 
	
	/**
     * Show the orders status List.
     *
     * @return Json Value
     */
	
    public function orderStatus()
    {
            $data['orchestra_hang_tag_orders'] = DB::table('orchestra_hang_tag_orders')->join('orchestra_item_details','orchestra_item_details.UVC','=','orchestra_hang_tag_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_hang_tag_orders.template_front_id')->leftjoin('customers','customers.factory_code','=','orchestra_item_details.FactoryCode')->select('orchestra_hang_tag_orders.uvc as UVC',DB::raw('"Hang Tag" as TemplateName'),DB::raw('"-" as BuyerPONo'),DB::raw('"Hang Tag" as ArtworkType'),'orchestra_item_details.SupplierName as SupplierName','orchestra_item_details.Collection as Season',DB::raw("DATE_FORMAT(orchestra_hang_tag_orders.created_at,'%d-%m-%Y') as OrderDate"),DB::raw("DATE_FORMAT(orchestra_hang_tag_orders.delivery_date,'%d-%m-%Y') as DeliveryDate"),'orchestra_hang_tag_orders.status as Status','orchestra_hang_tag_orders.pi_status as PI Status','orchestra_hang_tag_orders.po_status as PO Status','orchestra_hang_tag_orders.po_number as PONumber', 'customers.pi_required','orchestra_hang_tag_orders.id',DB::raw('"orchestra_hang_tag_orders" as tableName'),DB::raw('"orchestra_hang_tag_orders_qty" as qntytable'),'template_front_id as template_id')->groupBy('orchestra_item_details.UVC')->get();
		
            $data['orchestra_special_tag_orders'] = DB::table('orchestra_special_tag_orders')->join('orchestra_item_details','orchestra_item_details.UVC','=','orchestra_special_tag_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_special_tag_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_item_details.FactoryCode')->select('orchestra_special_tag_orders.uvc as UVC',DB::raw('"Special Tag" as TemplateName'),DB::raw('"-" as BuyerPONo'),DB::raw('"SpecialTag" as ArtworkType'),'orchestra_item_details.SupplierName as SupplierName','orchestra_item_details.Collection as Season',DB::raw("DATE_FORMAT(orchestra_special_tag_orders.created_at,'%d-%m-%Y') as OrderDate"),DB::raw("DATE_FORMAT(orchestra_special_tag_orders.delivery_date,'%d-%m-%Y') as DeliveryDate"),'orchestra_special_tag_orders.status as Status','orchestra_special_tag_orders.pi_status as PI Status','orchestra_special_tag_orders.po_status as PO Status','orchestra_item_details.Collection as Season','orchestra_special_tag_orders.id','orchestra_special_tag_orders.po_number as PONumber', 'customers.pi_required',DB::raw('"orchestra_special_tag_orders" as tableName'),DB::raw('"orchestra_special_tag_orders_qty" as qntytable'),'template_id')->groupBy('orchestra_item_details.UVC')->get();
     
               
            $data['orchestra_main_label_orders'] = DB::table('orchestra_main_label_orders')->join('orchestra_item_details','orchestra_item_details.UVC','=','orchestra_main_label_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_main_label_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_item_details.FactoryCode')->select('orchestra_main_label_orders.uvc as UVC','orchestra_main_label_orders.template_name as TemplateName',DB::raw('"-" as BuyerPONo'),DB::raw('"Main & Size Label" as ArtworkType'),'orchestra_item_details.SupplierName as SupplierName','orchestra_item_details.Collection as Season',DB::raw("DATE_FORMAT(orchestra_main_label_orders.created_at,'%d-%m-%Y') as OrderDate"),DB::raw("DATE_FORMAT(orchestra_main_label_orders.delivery_date,'%d-%m-%Y') as DeliveryDate"),'orchestra_main_label_orders.status as Status','orchestra_main_label_orders.pi_status as PI Status','orchestra_main_label_orders.po_status as PO Status','orchestra_item_details.Collection as Season','template_id','orchestra_main_label_orders.id',DB::raw('"orchestra_main_label_orders" as tableName'),'orchestra_main_label_orders.po_number as PONumber', 'customers.pi_required',DB::raw('"orchestra_main_label_orders_qty" as qntytable'))->groupBy('orchestra_item_details.UVC')->groupBy('orchestra_main_label_orders.id')->get();

            $data['orchestra_wash_care_label_orders'] = DB::table('orchestra_wash_care_label_orders')->join('orchestra_item_details','orchestra_item_details.UVC','=','orchestra_wash_care_label_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_wash_care_label_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_item_details.FactoryCode')->select('orchestra_wash_care_label_orders.uvc as UVC','orchestra_wash_care_label_orders.template_name as TemplateName',DB::raw('"-" as BuyerPONo'),DB::raw('"WashCareLabel" as ArtworkType'),'orchestra_item_details.SupplierName as SupplierName','orchestra_item_details.Collection as Season',DB::raw("DATE_FORMAT(orchestra_wash_care_label_orders.created_at,'%d-%m-%Y') as OrderDate"),DB::raw("DATE_FORMAT(orchestra_wash_care_label_orders.delivery_date,'%d-%m-%Y') as DeliveryDate"),'orchestra_wash_care_label_orders.status as Status','orchestra_wash_care_label_orders.pi_status as PI Status','orchestra_wash_care_label_orders.po_status as PO Status','orchestra_item_details.Collection as Season','orchestra_wash_care_label_orders.id',DB::raw('"orchestra_wash_care_label_orders" as tableName'),'template_id','orchestra_wash_care_label_orders.po_number as PONumber', 'customers.pi_required',DB::raw('"orchestra_wash_care_label_orders_qty" as qntytable'))->groupBy('orchestra_item_details.UVC')->groupBy('orchestra_wash_care_label_orders.id')->get();

            $data['orchestra_solid_carton_sticker_orders'] = DB::table('orchestra_solid_carton_sticker_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_solid_carton_sticker_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_solid_carton_sticker_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->select('orchestra_solid_carton_sticker_orders.uvc as UVC',DB::raw('"Solid Carton Stickers" as TemplateName'),'orchestra_parcel_details.BuyerPONo',DB::raw('"Solid Carton Stickers" as ArtworkType'),'orchestra_parcel_details.SupplierName as SupplierName',DB::raw("DATE_FORMAT(orchestra_solid_carton_sticker_orders.created_at,'%d-%m-%Y') as OrderDate"),DB::raw("DATE_FORMAT(orchestra_solid_carton_sticker_orders.delivery_date,'%d-%m-%Y') as DeliveryDate"),'orchestra_solid_carton_sticker_orders.status as Status','orchestra_solid_carton_sticker_orders.pi_status as PI Status','orchestra_solid_carton_sticker_orders.po_status as PO Status','orchestra_solid_carton_sticker_orders.id',DB::raw('"-" as Season'),DB::raw('"orchestra_solid_carton_sticker_orders" as tableName'),'template_id','orchestra_solid_carton_sticker_orders.po_number as PONumber', 'customers.pi_required',DB::raw('"orchestra_solid_carton_sticker_orders_qty" as qntytable'))->where('orchestra_parcel_details.IsSTD',0)->groupBy('orchestra_parcel_details.UVC')->get();
              
            $data['orchestra_solid_poly_bag_sticker_orders'] = DB::table('orchestra_solid_poly_bag_sticker_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_solid_poly_bag_sticker_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_solid_poly_bag_sticker_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->select('orchestra_solid_poly_bag_sticker_orders.uvc as UVC',DB::raw('"Solid Poly Bag Stickers" as TemplateName'),'orchestra_parcel_details.BuyerPONo',DB::raw('"Solid Poly Bag Stickers" as ArtworkType'),'orchestra_parcel_details.SupplierName as SupplierName',DB::raw("DATE_FORMAT(orchestra_solid_poly_bag_sticker_orders.created_at,'%d-%m-%Y') as OrderDate"),DB::raw("DATE_FORMAT(orchestra_solid_poly_bag_sticker_orders.delivery_date,'%d-%m-%Y') as DeliveryDate"),'orchestra_solid_poly_bag_sticker_orders.status as Status','orchestra_solid_poly_bag_sticker_orders.pi_status as PI Status','orchestra_solid_poly_bag_sticker_orders.po_status as PO Status','orchestra_solid_poly_bag_sticker_orders.id',DB::raw('"-" as Season'),DB::raw('"orchestra_solid_poly_bag_sticker_orders" as tableName'),'template_id','orchestra_solid_poly_bag_sticker_orders.po_number as PONumber', 'customers.pi_required',DB::raw('"orchestra_solid_poly_bag_sticker_order_qty" as qntytable'))->where('orchestra_parcel_details.IsSTD',0)->groupBy('orchestra_parcel_details.UVC')->get();
    
            $data['orchestra_standard_carton_sticker_orders'] = DB::table('orchestra_standard_carton_sticker_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_standard_carton_sticker_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_standard_carton_sticker_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->select('orchestra_standard_carton_sticker_orders.uvc as UVC',DB::raw('"Standard Carton Stickers" as TemplateName'),'orchestra_parcel_details.BuyerPONo',DB::raw('"Standard Carton Stickers" as ArtworkType'),'orchestra_parcel_details.SupplierName as SupplierName',DB::raw("DATE_FORMAT(orchestra_standard_carton_sticker_orders.created_at,'%d-%m-%Y') as OrderDate"),DB::raw("DATE_FORMAT(orchestra_standard_carton_sticker_orders.delivery_date,'%d-%m-%Y') as DeliveryDate"),'orchestra_standard_carton_sticker_orders.status as Status','orchestra_standard_carton_sticker_orders.pi_status as PI Status','orchestra_standard_carton_sticker_orders.po_status as PO Status','orchestra_standard_carton_sticker_orders.id',DB::raw('"-" as Season'),DB::raw('"orchestra_standard_carton_sticker_orders" as tableName'),'template_id','orchestra_standard_carton_sticker_orders.po_number as PONumber', 'customers.pi_required',DB::raw('"orchestra_standard_carton_sticker_orders" as qntytable'))->where('orchestra_parcel_details.IsSTD',1)->groupBy('orchestra_parcel_details.UVC')->get();

            $data['orchestra_standard_polybag_sticker_orders'] = DB::table('orchestra_standard_polybag_sticker_orders')->join('orchestra_parcel_details','orchestra_parcel_details.UVC','=','orchestra_standard_polybag_sticker_orders.uvc')->join('orchestra_item_master','orchestra_item_master.id','=','orchestra_standard_polybag_sticker_orders.template_id')->leftjoin('customers','customers.factory_code','=','orchestra_parcel_details.FactoryCode')->select('orchestra_standard_polybag_sticker_orders.uvc as UVC',DB::raw('"Standard Poly Bag Stickers" as TemplateName'),'orchestra_parcel_details.BuyerPONo',DB::raw('"Standard Poly Bag Stickers" as ArtworkType'),'orchestra_parcel_details.SupplierName as SupplierName',DB::raw("DATE_FORMAT(orchestra_standard_polybag_sticker_orders.created_at,'%d-%m-%Y') as OrderDate"),DB::raw("DATE_FORMAT(orchestra_standard_polybag_sticker_orders.delivery_date,'%d-%m-%Y') as DeliveryDate"),'orchestra_standard_polybag_sticker_orders.status as Status','orchestra_standard_polybag_sticker_orders.pi_status as PI Status','orchestra_standard_polybag_sticker_orders.po_status as PO Status','orchestra_standard_polybag_sticker_orders.id',DB::raw('"-" as Season'),DB::raw('"orchestra_standard_polybag_sticker_orders" as tableName'),'template_id','orchestra_standard_polybag_sticker_orders.po_number as PONumber', 'customers.pi_required',DB::raw('"orchestra_standard_polybag_sticker_orders" as qntytable'))->where('orchestra_parcel_details.IsSTD',1)->groupBy('orchestra_parcel_details.UVC')->get();

     
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
            $data['order_details'] = DB::table('orchestra_item_details') ->leftJoin('orchestra_size_chart','orchestra_item_details.Size','=','orchestra_size_chart.age')->select('*',DB::raw("sum(Quantity) as Sum"))->where('UVC',$uvc)->groupBy('Size')->orderBy('orchestra_item_details.id')->get();
		
            $data['barcode'] = DB::table('orchestra_item_details')->select('*')->where('UVC',$uvc)->groupBy('BuyerPONo')->get();
        
            $total_qty = DB::table('orchestra_item_details')->select('*',DB::raw("sum(Quantity) as total_qty"))->where('UVC',$uvc)->groupBy('UVC')->get();
            $data['total_qty']=$total_qty{0}->total_qty;
            if($total_qty{0}->Disney == 1)
            {
                $orchestra_template = DB::table('orchestra_template')->select('*')->where('id','2')->get();
                $data['orchestra_template']=$orchestra_template{0}->template_file;
                $data['orchestra_template_id']=2;
                
                $data['orchestra_wash_template']="/storage/app/template/orchestra_wcl_disney.svg";
                $data['orchestra_wash_template_id']=2;
                
                $orchestra_template_front = DB::table('orchestra_template')->select('*')->where('item_id','2')->where('Disney','1')->get();
                $data['orchestra_template_front']=$orchestra_template_front;
				
				 $treeviewData= DB::table("orchestra_item_master")->select('id',DB::raw('IF(ref_no = "", item_name, CONCAT(item_name," -  ",ref_no)) as text'),'parent_id')->orWhere('id','5')->orWhere('tag_name','Disney')->orWhere('id','1')->orderBy('item_name', 'asc')->get()->toArray();
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
                $orchestra_template = DB::table('orchestra_template')->select('*')->where('id','1')->get();
                $data['orchestra_template']=$orchestra_template{0}->template_file;
                $data['orchestra_template_id']=1;
                
                $data['orchestra_wash_template']="/storage/app/template/orchestra_wcl.svg";
                $data['orchestra_wash_template_id']=1;
                
                $orchestra_template_front = DB::table('orchestra_template')->select('*')->where('item_id','2')->where('Disney','0')->get();
                $data['orchestra_template_front']=$orchestra_template_front;
				
				 $treeviewData= DB::table("orchestra_item_master")->select('id',DB::raw('IF(ref_no = "", item_name, CONCAT(item_name," -  ",ref_no)) as text'),'parent_id')->orWhere('id','5')->orWhere('tag_name','hang')->orWhere('id','1')->orderBy('item_name', 'asc')->get()->toArray();
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
         
        
        $treeviewspecial= DB::table("orchestra_item_master")->select('id',DB::raw('IF(ref_no = "", item_name, CONCAT(item_name," -  ",ref_no)) as text'),'parent_id')->orWhere('id','6')->orWhere('parent_id','6')->orWhere('id','1')->orderBy('item_name', 'asc')->get()->toArray();
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
		
		$treeviewwash= DB::table("orchestra_item_master")->select('id',DB::raw('IF(ref_no = "", item_name, CONCAT(item_name," -  ",ref_no)) as text'),'parent_id')->orWhere('parent_id','120')->orWhere('id','77')->orWhere('id','4')->orWhere('id','120')->orderBy('item_name', 'asc')->get()->toArray();
		//print_r($treeviewwash);
            if($treeviewwash){
                        $childs = array();
                        foreach($treeviewwash as &$item) 
                        {
                        $childs[$item->parent_id][] = &$item;
                        unset($item);
                        }
                        foreach($treeviewwash as &$item) 
                        {
                            if (isset($childs[$item->id]))
                            {
                                $item->children = $childs[$item->id];
                                unset($item);
                            }
                        }
                        $tree = $childs[0];
                        $data['treeviewwash']=$tree;

               }
        
        $treeviewmain= DB::table("orchestra_item_master")->select('id',DB::raw('IF(ref_no = "", item_name, CONCAT(item_name," -  ",ref_no)) as text'),'parent_id')->orWhere('id','4')->orWhere('id','76')->orWhere('tag_name','mainsize')->orderBy('item_name', 'asc')->get()->toArray();
            if($treeviewmain){
                        $childs = array();
                        foreach($treeviewmain as &$item) 
                        {
                        $childs[$item->parent_id][] = &$item;
                        unset($item);
                        }
                        foreach($treeviewmain as &$item) 
                        {
                            if (isset($childs[$item->id]))
                            {
                                $item->children = $childs[$item->id];
                                unset($item);
                            }
                        }
                        $tree = $childs[0];
                        $data['treeviewmain']=$tree;

               }
        
                $code=substr($uvc, -4, 2);
                $data['orchestra_hang_tag_orders']=DB::table('orchestra_hang_tag_orders')->select('*',DB::raw('"Hang Tag" as TemplateName'))->where('uvc',$uvc)->get();
        if(count($data['orchestra_hang_tag_orders']) > 0)
        {
            $data['orchestra_hang_tag_orders_qty']=DB::table('orchestra_hang_tag_orders_qty')->select('*')->where('oht_id',$data['orchestra_hang_tag_orders']{0}->id)->get();
        }
        else
        {
            $data['orchestra_hang_tag_orders_qty']="";
        }
         $data['orchestra_special_tag_orders']=DB::table('orchestra_special_tag_orders')->select('*',DB::raw('"Special Tag" as TemplateName'))->where('uvc',$uvc)->get();
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
		  		$data['customers']=DB::table('customers')->where('factory_code',$factory)->select('*')->get();
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
                $data['product_family'] = DB::table('orchestra_product_family')->select('*')->where('code',$code)->get();
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
        
         $data['orchestra_parcel_quantity_details'] = DB::table('orchestra_parcel_quantity_details')->select('*')->where('BuyerPONo',$po)->get();
        
         $data['orchestra_parcel_details']= DB::table('orchestra_parcel_details')->select('*')->where('BuyerPONo',$po)->groupBy('BuyerPONo')->get();
        
        $data['orchestra_age_group']= DB::table('orchestra_age_group')->select('*')->get();      
        
        $data['orchestra_solid_carton_sticker_orders']=DB::table('orchestra_solid_carton_sticker_orders')->select('*')->where('uvc',$uvc)->get();
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
        
        $data['orchestra_solid_poly_bag_sticker_orders']=DB::table('orchestra_solid_poly_bag_sticker_orders')->select('*')->where('uvc',$uvc)->get();
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
        
        $data['orchestra_standard_carton_sticker_orders']=DB::table('orchestra_standard_carton_sticker_orders')->select('*')->where('uvc',$uvc)->get();
        if(count($data['orchestra_standard_carton_sticker_orders']) > 0)
        {
            $data['std_carton_id']=$data['orchestra_standard_carton_sticker_orders'][0]->id;
        }
        else
        {
            $data['std_carton_id']="null";
        }
        
        $data['orchestra_standard_polybag_sticker_orders']=DB::table('orchestra_standard_polybag_sticker_orders')->select('*')->where('uvc',$uvc)->get();
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
            $orchestra_template = DB::table('orchestra_template')->select('*')->where('item_id','4')->get();
            $data['carton_template']=$orchestra_template{0}->template_file;
            $data['carton_template_id']=$orchestra_template{0}->id;
            $orchestra_template = DB::table('orchestra_template')->select('*')->where('item_id','3')->get();
            $data['polybag_template']=$orchestra_template{0}->template_file;
            $data['template_name']="Standard";
            $data['polybag_template_id']=$orchestra_template{0}->id;
        }
        else
        {
            $orchestra_template = DB::table('orchestra_template')->select('*')->where('item_id','6')->get();
            $data['carton_template']=$orchestra_template{0}->template_file;
            $data['carton_template_id']=$orchestra_template{0}->id;
            $orchestra_template = DB::table('orchestra_template')->select('*')->where('item_id','5')->get();
            $data['polybag_template']=$orchestra_template{0}->template_file;
            $data['template_name']="Solid";
            $data['polybag_template_id']=$orchestra_template{0}->id;
            
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
        $size = explode(',',$request->size);
        $uvc =$request->uvc;
        $id =$request->id;
		$factory = DB::table("orchestra_item_details")->join('customers','customers.factory_code','=','orchestra_item_details.FactoryCode')->where('UVC',$uvc)->select('*')->get();
		if(count($factory) > 0)
		{
			
			$pi_required=$factory{0}->pi_required;
			if($pi_required == 1)
			{
				$pi_status="PI Approval Pending";
				if($factory{0}->po_required == 1)
				{
					$po_status="PO Approval Pending";
				}
				else
				{
					$po_status="";
				}
				
			}
			else
			{
				$po_status="PO Approval Pending";
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
        }
        else
        {
             $inserted = HangTagOrders::create([
                                    'uvc' => $uvc,
                                    'template_front_id'      => $template_front_id,
                                    'template_back_id'      => $template_back_id,
                                    'svg_path'    => "", 
                                    'status'    => $status,
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
		$factory = DB::table("orchestra_item_details")->join('customers','customers.factory_code','=','orchestra_item_details.FactoryCode')->where('UVC',$uvc)->select('*')->get();
		if(count($factory) > 0)
		{
			
			$pi_required=$factory{0}->pi_required;
			if($pi_required == 1)
			{
				$pi_status="PI Approval Pending";
				if($factory{0}->po_required == 1)
				{
					$po_status="PO Approval Pending";
				}
				else
				{
					$po_status="";
				}
				
			}
			else
			{
				$po_status="PO Approval Pending";
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
		$factory = DB::table("orchestra_item_details")->join('customers','customers.factory_code','=','orchestra_item_details.FactoryCode')->where('UVC',$uvc)->select('*')->get();
		
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
					$po_status="PO Approval Pending";
				}
				else
				{
					$po_status="";
				}
				
			}
			else
			{
				$po_status="PO Approval Pending";
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
		$factory = DB::table("orchestra_item_details")->join('customers','customers.factory_code','=','orchestra_item_details.FactoryCode')->where('UVC',$uvc)->select('*')->get();
		if(count($factory) > 0)
		{
			
			$pi_required=$factory{0}->pi_required;
			if($pi_required == 1)
			{
				$pi_status="PI Approval Pending";
				if($factory{0}->po_required == 1)
				{
					$po_status="PO Approval Pending";
				}
				else
				{
					$po_status="";
				}
				
			}
			else
			{
				$po_status="PO Approval Pending";
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
		
		$factory = DB::table("orchestra_item_details")->join('customers','customers.factory_code','=','orchestra_item_details.FactoryCode')->where('UVC',$uvc)->select('*')->get();
		if(count($factory) > 0)
		{
			
			$pi_required=$factory{0}->pi_required;
			if($pi_required == 1)
			{
				$pi_status="PI Approval Pending";
				if($factory{0}->po_required == 1)
				{
					$po_status="PO Approval Pending";
				}
				else
				{
					$po_status="";
				}
				
			}
			else
			{
				$po_status="PO Approval Pending";
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
        $washing_select = $request->washing_select; 
        $whitening_select = $request->whitening_select; 
        $dryer_select = $request->dryer_select; 
        $ironing_select= $request->ironing_select; 
        $cleaning_select= $request->cleaning_select; 
        $merchandisers= $request->merchandisers; 
        $made_in_select = $request->made_in_select; 
        $care_instruction_select = $request->care_instruction_select; 
		
		$factory = DB::table("orchestra_item_details")->join('customers','customers.factory_code','=','orchestra_item_details.FactoryCode')->where('UVC',$uvc)->select('*')->get();
		
		if(count($factory) > 0)
		{
			
			$pi_required=$factory{0}->pi_required;
			if($pi_required == 1)
			{
				$pi_status="PI Approval Pending";
				if($factory{0}->po_required == 1)
				{
					$po_status="PO Approval Pending";
				}
				else
				{
					$po_status="";
				}
				
			}
			else
			{
				$po_status="PO Approval Pending";
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
        }
       
           
    }
    /**
     * method to set the purchase order number to the respective order
     * @param Request $request [table_name,po_number]
     */
    public function setPONumber(Request $request)
    {
        try
        {
            $po_number  =   $request->po_number;
            $id         =   $request->id;
            $table_name =   $request->table_name;
            $data       = DB::table($table_name)->where('id',$id)
                        ->update([
                          'po_number' => $po_number,
                          'po_status' => "PO Approved"
                        ]);
            if (count($data)>0)
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
     * method to set the po number and pdf file for an order
     * @param po_number and pdf (pdf is optional)
     * @return \Illuminate\Http\Response
     */
    public function setCustomerPOFile(Request $request)
    {
        try 
        {
            $table_name = $request->tableName;
            $row_id     = $request->rowID;
            $po_file = $request->file('file');
            $path = $request->file('file')->getRealPath();
            $filename = $request->file('file')->getClientOriginalName();
            $destination_path='/storage/app/public/po-files/';
            $path='storage/app/public/po-files/';
            $Orname = $po_file->getClientOriginalName();
            $filename= str_random(6).'_'.preg_replace('/\s+/', '-', $Orname);
            $po_file->move($path,$filename);
            $destination_path_insert=$destination_path.$filename;
            $data   = DB::table($table_name)->where('id',$row_id)
                        ->update([
                          'po_file' => $destination_path_insert
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
        catch (Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        } 
        return $result;
    }
    /**
     * method to get all the details for profoma invoice generation
     * @param  Request $request [table_name,id]
     * @return [type]           [description]
     */
    public function getProfomaInvoice(Request $request)
    {
        try
        {
            $pi_obj = new ProfomaInvoice();
            $data   = $pi_obj->getPIDetails($request);
            $result = response()->json(['error'=> false,'message' =>'success','data'=>$data],200);
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

    /**
     * method to approve the profoma invoice
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function approveProfomaInvice(Request $request)
    {
        try
        {
            $row_id     =   $request->id;
            $table_name =   $request->table_name;
            $total_qnty =   0 ; 
            $sub_total  =   0 ;
            $pi_obj     =   new ProfomaInvoice();
            $data       =   $pi_obj->getPIDetails($request);
            if (count($data)>0)
            {
                
                $pi_details = PI::create([
                    'company_id' => $data['company_details']->id,
                    'customer_id'      => $data['bank_details']->id,
                    'shipping_address' => $data['ship_to']->address,
                    'billing_address'  => $data['bill_to']->address,
                    'place_of_supply'  => $data['bank_details']->place_of_supply
                    
                ]);
                foreach ($data['item_details'] as $key => $value)
                {
                    $total_qnty+= $value->quantity;
                    $sub_total += $value->quantity *$value->unit_price;
                    ProfomaInvoiceItemDetails::create([
                        'pi_id'     => $pi_details->id,
                        'material'  => $value->material_name,
                        'size'      => $value->size,
                        'style'     => $value->style,
                        'quantity'  => $value->quantity,
                        'unit_cost' => $value->unit_price
                    ]);
                }
                PI::where('id',$pi_details->id)
                            ->update([
                              'total_quantity'   => $total_qnty,
                              'sub_total'        => $sub_total,
                              'tax'              => $data['item_details'][0]->tax,
                              'bill_amount'      => $sub_total*$data['item_details'][0]->tax
                            ]);
            }
            
            $data       =   DB::table($table_name)
                            ->where('id',$row_id)
                            ->update([
                              'pi_status' => "PI Approved"
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
     * method to reject the profoma invoice
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function rejectProfomaInvice(Request $request)
    {
        try
        {
            $row_id     =   $request->id;
            $table_name =   $request->table_name;
            $data       =   DB::table($table_name)
                            ->where('id',$row_id)
                            ->update([
                              'pi_status' => "PI Rejected"
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
     * This Function is used to delete the Extra Main and size Label Details.
     *
     * @return Void
     */
	
    public function removeMainLabel(Request $request)
    {
         $deleted = MainLabelOrders::where('id', '=', $request->id)->delete();
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
          $data['orchestra_hang_tag_orders']=DB::table('orchestra_hang_tag_orders')->select('*',DB::raw('"Hang Tag" as TemplateName'),DB::raw('"Hang Tag" as TemplateType'),DB::raw('"orchestra_hang_tag_orders" as type'))->where('uvc',$uvc)->get();
		 $data['orchestra_special_tag_orders']=DB::table('orchestra_special_tag_orders')->select('*',DB::raw('"Special Tag" as TemplateName'),DB::raw('"Special Tag" as TemplateType'),DB::raw('"orchestra_special_tag_orders" as type'))->where('uvc',$uvc)->get();
		$data['orchestra_main_label_orders']=DB::table('orchestra_main_label_orders')->select('*','orchestra_main_label_orders.template_name as TemplateName',DB::raw('"Main and Size Labels" as TemplateType'),DB::raw('"orchestra_main_label_orders" as type'))->where('uvc',$uvc)->get();
		  $data['orchestra_wash_care_label_orders']=DB::table('orchestra_wash_care_label_orders')->select('*','orchestra_wash_care_label_orders.template_name as TemplateName',DB::raw('"Wash Care Labels" as TemplateType'),DB::raw('"orchestra_wash_care_label_orders" as type'))->where('uvc',$uvc)->get();
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
          $data['orchestra_hang_tag_orders']=DB::table('orchestra_hang_tag_orders')->select('*',DB::raw('"Hang Tag" as TemplateName'),DB::raw('"Hang Tag" as TemplateType'),DB::raw('"orchestra_hang_tag_orders" as type'))->where('uvc',$uvc)->get();
		 $data['orchestra_special_tag_orders']=DB::table('orchestra_special_tag_orders')->select('*',DB::raw('"Special Tag" as TemplateName'),DB::raw('"Special Tag" as TemplateType'),DB::raw('"orchestra_special_tag_orders" as type'))->where('uvc',$uvc)->get();
		$data['orchestra_main_label_orders']=DB::table('orchestra_main_label_orders')->select('*','orchestra_main_label_orders.template_name as TemplateName',DB::raw('"Main and Size Labels" as TemplateType'),DB::raw('"orchestra_main_label_orders" as type'))->where('uvc',$uvc)->get();
		  $data['orchestra_wash_care_label_orders']=DB::table('orchestra_wash_care_label_orders')->select('*','orchestra_wash_care_label_orders.template_name as TemplateName',DB::raw('"Wash Care Labels" as TemplateType'),DB::raw('"orchestra_wash_care_label_orders" as type'))->where('uvc',$uvc)->get();
		
		  $data['orchestra_solid_carton_sticker_orders']=DB::table('orchestra_solid_carton_sticker_orders')->select('*',DB::raw('"Solid Carton Stickers" as TemplateName'),DB::raw('"Solid Carton Stickers" as TemplateType'),DB::raw('"orchestra_solid_carton_sticker_orders" as type'))->where('uvc',$uvc)->get();
		  $data['orchestra_solid_poly_bag_sticker_orders']=DB::table('orchestra_solid_poly_bag_sticker_orders')->select('*',DB::raw('"Solid Poly Bag Stickers" as TemplateName'),DB::raw('"Solid Poly Bag Stickers" as TemplateType'),DB::raw('"orchestra_solid_poly_bag_sticker_orders" as type'))->where('uvc',$uvc)->get();
		 $data['orchestra_standard_carton_sticker_orders']=DB::table('orchestra_standard_carton_sticker_orders')->select('*',DB::raw('"Standard Carton Stickers" as TemplateName'),DB::raw('"Standard Carton Stickers" as TemplateType'),DB::raw('"orchestra_standard_carton_sticker_orders" as type'))->where('uvc',$uvc)->get();
		 $data['orchestra_standard_polybag_sticker_orders']=DB::table('orchestra_standard_polybag_sticker_orders')->select('*',DB::raw('"Standard Poly Bag Stickers" as TemplateName'),DB::raw('"Standard Poly Bag Stickers" as TemplateType'),DB::raw('"orchestra_standard_polybag_sticker_orders" as type'))->where('uvc',$uvc)->get();
		
		
		 echo json_encode($data);
    } 
	/**
     * This Function is used to map the existing PO to new artworks.
     *
     * @return Void
     */
	public function putExistingLabelOrdersPo(Request $request)
    {
		 $id        =   explode(',',$request->id);
		 $po        =   $request->po;
		
		foreach($id as $val)
		{
				$value    =  explode('-',$val);
			
				DB::table($value[0])->where('id',$value[1])
                   ->update([
                        'po_number' => $po
                  ]);
		}
		echo true;
    }
	/**
     * This Function is used to update the po number and po file to the new artworks.
     *
     * @return Void
     */
	public function putExistingLabelOrdersPoNew(Request $request)
    {
		 $id        =   explode(',',$request->id);
		 $po        =   $request->po;
		 $po_file   =   $request->po_file;
		
		
		$destination_path_insert="";
		$destination_path_db="";
		if( $request->hasFile('po_file')){
			$file= $request->file('po_file');
			$destination_path='storage/app/public/po-files/';
			 $destination_path_db='/storage/app/public/po-files/';
			$Orname = $file->getClientOriginalName();
			$filename= str_random(6).'_'.$Orname;
			$file->move($destination_path,$filename);
			$destination_path_insert=$destination_path_db.$filename;
			
			foreach($id as $val)
			{
					$value    =  explode('-',$val);

					DB::table($value[0])->where('id',$value[1])
					   ->update([
							'po_number' => $po,
							'po_file' => $destination_path_insert,
					  ]);
			}
		}
		else
		{
			foreach($id as $val)
			{
					$value    =  explode('-',$val);

					DB::table($value[0])->where('id',$value[1])
					   ->update([
							'po_number' => $po
					  ]);
			}
		}
		
	
		echo true;
    }
}

