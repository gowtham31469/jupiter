<?php

namespace App\Http\Controllers\Admin\Orchestra;

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
            $data['buyer'] =DB::table('buyers')->select('*')->get();
            echo json_encode($data);
    } 
    public function backStickerOrders()
    {
            $data['orders'] = DB::table('orchestra_parcel_details')->select('id','UVC','BuyerPONo','SupplierName','Theme','Quantity','IsSTD as Type')->get();
            $data['buyer'] =DB::table('buyers')->select('*')->get();
            echo json_encode($data);
    }
    public function Orders()
    {
            $data['sticker_orders'] = DB::table('orchestra_parcel_details')->select('id','UVC',DB::raw('"Null" as Season'),'BuyerPONo','SupplierName','NomApple as Style_Name','Quantity','IsSTD as Type',DB::raw('"Stickers" as OrderType'),DB::raw('"Null" as Disney'))->get();
            $data['label_orders'] = DB::table('orchestra_item_details')->select('id','UVC','Collection as Season',DB::raw('"-" as BuyerPONo'),'SupplierName','Description','Style_Name','Disney',DB::raw('"Null" as Type'),'Quantity',DB::raw('"Labels" as OrderType'))->groupBy('UVC')->get();
            echo json_encode($data);
    }
    public function labelOrderDetails(Request $request)
    {
            $uvc=$request->uvc;
            $data['order_details'] = DB::table('orchestra_item_details') ->leftJoin('orchestra_size_chart','orchestra_item_details.Size','=','orchestra_size_chart.age')->select('*',DB::raw("sum(Quantity) as Sum"))->where('UVC',$uvc)->groupBy('Size')->get();
            $data['barcode'] = DB::table('orchestra_item_details')->select('*')->where('UVC',$uvc)->groupBy('BuyerPONo')->get();
        
            $total_qty = DB::table('orchestra_item_details')->select('*',DB::raw("sum(Quantity) as total_qty"))->where('UVC',$uvc)->groupBy('UVC')->get();
            $data['total_qty']=$total_qty{0}->total_qty;
            if($total_qty{0}->Disney == 1)
            {
                $orchestra_template = DB::table('orchestra_template')->select('*')->where('id','2')->get();
                $data['orchestra_template']=$orchestra_template{0}->template_file;
                $data['orchestra_template_id']=2;
                
                $orchestra_template_front = DB::table('orchestra_template')->select('*')->where('item_id','2')->where('Disney','1')->get();
                $data['orchestra_template_front']=$orchestra_template_front;
            }
            else
            {
                $orchestra_template = DB::table('orchestra_template')->select('*')->where('id','1')->get();
                $data['orchestra_template']=$orchestra_template{0}->template_file;
                $data['orchestra_template_id']=1;
                
                $orchestra_template_front = DB::table('orchestra_template')->select('*')->where('item_id','2')->where('Disney','0')->get();
                $data['orchestra_template_front']=$orchestra_template_front;
            }
        
         // $treeviewData= DB::table("orchestra_item_master")->select('id','item_name as text','parent_id')->orWhere('id','6')->orWhere('parent_id','6')->orWhere('id','1')->get()->toArray();
          $treeviewData= DB::table("orchestra_item_master")->select('id','item_name as text','parent_id')->orWhere('id','5')->orWhere('tag_name','hang')->orWhere('id','1')->get()->toArray();
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
        
        $treeviewspecial= DB::table("orchestra_item_master")->select('id','item_name as text','parent_id')->orWhere('id','6')->orWhere('parent_id','6')->orWhere('id','1')->get()->toArray();
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
        
        $treeviewmain= DB::table("orchestra_item_master")->select('id','item_name as text','parent_id')->orWhere('id','4')->orWhere('id','76')->orWhere('tag_name','mainsize')->get()->toArray();
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
        
                $code=substr($uvc, -3, 2);
                $data['orchestra_hang_tag_orders']=DB::table('orchestra_hang_tag_orders')->select('*')->where('uvc',$uvc)->get();
        if(count($data['orchestra_hang_tag_orders']) > 0)
        {
            $data['orchestra_hang_tag_orders_qty']=DB::table('orchestra_hang_tag_orders_qty')->select('*')->where('oht_id',$data['orchestra_hang_tag_orders']{0}->id)->get();
        }
        else
        {
            $data['orchestra_hang_tag_orders_qty']="";
        }
         $data['orchestra_special_tag_orders']=DB::table('orchestra_special_tag_orders')->select('*')->where('uvc',$uvc)->get();
        if(count($data['orchestra_special_tag_orders']) > 0)
        {
            $data['orchestra_special_tag_orders_qty']=DB::table('orchestra_special_tag_orders_qty')->select('*')->where('ost_id',$data['orchestra_special_tag_orders']{0}->id)->get();
        }
        else
        {
            $data['orchestra_special_tag_orders_qty']="";
        }
                
                $data['orchestra_care_symbols'] = DB::table('orchestra_care_symbols')->select('*')->get();
                $data['orchestra_composition'] = DB::table('orchestra_compositions')->select('*')->get();
                $data['orchestra_countries'] = DB::table('orchestra_countries')->select('*')->get();
                $data['orchestra_care_instruction'] = DB::table('orchestra_care_instruction')->select('*')->get(); 
                $data['orchestra_parts'] = DB::table('orchestra_parts')->select('*')->get();
                $data['product_family'] = DB::table('orchestra_product_family')->select('*')->where('code',$code)->get();
                echo json_encode($data);
    }
    public function stickerOrderDetails(Request $request)
    {
         $po=$request->po;
        
         $data['orchestra_parcel_quantity_details'] = DB::table('orchestra_parcel_quantity_details')->select('*')->where('BuyerPONo',$po)->get();
        
         $data['orchestra_parcel_details']= DB::table('orchestra_parcel_details')->select('*')->where('BuyerPONo',$po)->groupBy('BuyerPONo')->get();
        
        $data['orchestra_age_group']= DB::table('orchestra_age_group')->select('*')->get();
        
        
        
        if($data['orchestra_parcel_details']{0}->IsSTD == 1)
        {
            $orchestra_template = DB::table('orchestra_template')->select('*')->where('item_id','4')->get();
            $data['carton_template']=$orchestra_template{0}->template_file;
            $orchestra_template = DB::table('orchestra_template')->select('*')->where('item_id','3')->get();
            $data['polybag_template']=$orchestra_template{0}->template_file;
            $data['template_name']="Standard";
        }
        else
        {
            $orchestra_template = DB::table('orchestra_template')->select('*')->where('item_id','6')->get();
            $data['carton_template']=$orchestra_template{0}->template_file;
            $orchestra_template = DB::table('orchestra_template')->select('*')->where('item_id','5')->get();
            $data['polybag_template']=$orchestra_template{0}->template_file;
            $data['template_name']="Solid";
            
        }
        
        echo json_encode($data);
           
    }   
    public function compositionDetails(Request $request)
    {
         $lang=$request->lang;
        
         $data = DB::table('orchestra_compositions')->select('*')->whereIn('english',$lang)->get();
        
         echo json_encode($data);
           
    } 
    public function setHangTag(Request $request)
    {
        $delivery_date = $request->delivery_date;
        $template_front_id = $request->template_front_id;
        $template_back_id = $request->template_back_id;
        $tot_qty_table = explode(',',$request->tot_qty_table);
        $uvc_tco = explode(',',$request->uvc_tco);
        $size = explode(',',$request->size);
        $uvc =$request->uvc;
        $inserted = HangTagOrders::create([
                                'uvc' => $uvc,
                                'template_front_id'      => $template_front_id,
                                'template_back_id'      => $template_back_id,
                                'delivery_date'    => $delivery_date, 
                                'svg_path'    => "", 
                                'status'    => "Approved",
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
    public function setSpecialTag(Request $request)
    {
        $delivery_date = $request->delivery_date;
        $template_front_id = $request->template_front_id;
        $tot_qty_table = explode(',',$request->tot_qty_table);
        $uvc_tco = explode(',',$request->uvc_tco);
        $size = explode(',',$request->size);
        $uvc =$request->uvc;
        $inserted = SpecialTagOrders::create([
                                'uvc' => $uvc,
                                'template_id'      => $template_front_id,
                                'delivery_date'    => $delivery_date, 
                                'svg_path'    => "", 
                                'status'    => "Approved",
                     ]);
        $i=0;
        foreach($tot_qty_table as $value)
        {
             SpecialTagOrdersQty::create([
                                'ost_id' => $inserted->id,
                                'size'      => $size[$i],
                                'uvc_tco'      => $uvc_tco[$i],
                                'quantity'    => $value
                     ]);
            $i++;
        }
           
    }
}

