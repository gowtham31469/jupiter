<?php

namespace App\Http\Controllers\Customer\Tesco;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Classes\Modules;
use Response;
use View;
use DB;
use Auth;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Config;
use App\TescoTCL1Orders;
use App\TescoTCL1OrdersQuantity;
use App\TescoTCL2Orders;
use App\TescoTCL2OrdersQuantity;
use App\TescoTCL4Orders;
use App\TescoTCL4OrdersQuantity;
use App\TescoTHL1Orders;
use App\TescoTHL1OrdersQuantity;
use App\TescoTHL3Orders;
use App\TescoTHL3OrdersQuantity;
use App\TescoTSL1Orders;
use App\TescoTSL1OrdersQuantity;
use App\TescoTSL3Orders;
use App\TescoTSL3OrdersQuantity;
use App\Classes\TescoCombinedPOUpload;
use App\Classes\TescoPOUploadTemplatesSwitch;
use App\Classes\TescoOrders;
use App\Classes\SaveTescoOrders;
use PDF;
use Carbon\Carbon;

class ReorderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('customer_auth');
		$this->tcl1 = "TCL 1";
        $this->tcl2 = "TCL 2";
        $this->tcl4 = "TCL 4";
        $this->thl1 = "THL 1";
        $this->thl3 = "THL 3";
        $this->tsl1 = "TSL 1";
        $this->tsl3 = "TSL 3";

        $this->tcl1_type = "tesco_tcl1_orders";
        $this->tcl2_type = "tesco_tcl2_orders";
        $this->tcl4_type = "tesco_tcl4_orders";
        $this->thl1_type = "tesco_thl1_orders";
        $this->thl3_type = "tesco_thl3_orders";
        $this->tsl1_type = "tesco_tsl1_orders";
        $this->tsl3_type = "tesco_tsl3_orders";
    }
	
 	/**
     * This Function is used to Show the style number in reorder page.
     *
     * @return json value
     */
  	public function reOrder(Request $request)
    {
		$customer_id = Auth::guard('web_customer')->id();
		
		
	  $customer_role_id = DB::table('customer_profile')->select('*')->where('id',$customer_id)->first();
	  if($customer_role_id->role_id == 1)
	  {
		$customer_id = Auth::guard('web_customer')->user()->id;

        $data['tesco_tcl1_orders'] = DB::table('tesco_tcl1_orders')->whereNotIn('order_status', ["Saved","Order Placed","Order Cancelled"])->where('tesco_supplier_id',$customer_role_id->company_id)->select('*')->get();

		$data['tesco_tcl2_orders'] = DB::table('tesco_tcl2_orders')->whereNotIn('order_status', ["Saved","Order Placed","Order Cancelled"])->where('tesco_supplier_id',$customer_role_id->company_id)->select('*')->get();

		$data['tesco_tcl4_orders'] = DB::table('tesco_tcl4_orders')->whereNotIn('order_status', ["Saved","Order Placed","Order Cancelled"])->where('tesco_supplier_id',$customer_role_id->company_id)->select('*')->get();

		$data['tesco_thl1_orders'] = DB::table('tesco_thl1_orders')->whereNotIn('order_status', ["Saved","Order Placed","Order Cancelled"])->where('tesco_supplier_id',$customer_role_id->company_id)->select('*')->get();

		$data['tesco_thl3_orders'] = DB::table('tesco_thl3_orders')->whereNotIn('order_status', ["Saved","Order Placed","Order Cancelled"])->where('tesco_supplier_id',$customer_role_id->company_id)->select('*')->get();

		$data['tesco_tsl1_orders'] = DB::table('tesco_tsl1_orders')->whereNotIn('order_status', ["Saved","Order Placed","Order Cancelled"])->where('tesco_supplier_id',$customer_role_id->company_id)->select('*')->get();

		$data['tesco_tsl3_orders'] = DB::table('tesco_tsl3_orders')->whereNotIn('order_status', ["Saved","Order Placed","Order Cancelled","Order Cancelled"])->where('tesco_supplier_id',$customer_role_id->company_id)->select('*')->get();
		echo json_encode($data);
	  }
	  else
	  {
		  $customer_id = Auth::guard('web_customer')->user()->id;
        $data['tesco_tcl1_orders'] = DB::table('tesco_tcl1_orders')->whereNotIn('order_status', ["Saved","Order Placed","Order Cancelled"])->where('tesco_supplier_id',$customer_role_id->company_id)->where('order_placed_by','=',$customer_id)->select('*')->get();

		$data['tesco_tcl2_orders'] = DB::table('tesco_tcl2_orders')->whereNotIn('order_status', ["Saved","Order Placed","Order Cancelled"])->where('tesco_supplier_id',$customer_role_id->company_id)->where('order_placed_by','=',$customer_id)->select('*')->get();

		$data['tesco_tcl4_orders'] = DB::table('tesco_tcl4_orders')->whereNotIn('order_status', ["Saved","Order Placed","Order Cancelled"])->where('tesco_supplier_id',$customer_role_id->company_id)->where('order_placed_by','=',$customer_id)->select('*')->get();

		$data['tesco_thl1_orders'] = DB::table('tesco_thl1_orders')->whereNotIn('order_status', ["Saved","Order Placed","Order Cancelled"])->where('tesco_supplier_id',$customer_role_id->company_id)->where('order_placed_by','=',$customer_id)->select('*')->get();

		$data['tesco_thl3_orders'] = DB::table('tesco_thl3_orders')->whereNotIn('order_status', ["Saved","Order Placed","Order Cancelled"])->where('tesco_supplier_id',$customer_role_id->company_id)->where('order_placed_by','=',$customer_id)->select('*')->get();

		$data['tesco_tsl1_orders'] = DB::table('tesco_tsl1_orders')->whereNotIn('order_status', ["Saved","Order Placed","Order Cancelled"])->where('tesco_supplier_id',$customer_role_id->company_id)->where('order_placed_by','=',$customer_id)->select('*')->get();

		$data['tesco_tsl3_orders'] = DB::table('tesco_tsl3_orders')->whereNotIn('order_status', ["Saved","Order Placed","Order Cancelled"])->where('tesco_supplier_id',$customer_role_id->company_id)->where('order_placed_by','=',$customer_id)->select('*')->get();
		echo json_encode($data);
	  }
		
    }
	/**
     * This Function is used to list all the artwork for particular style number.
     *
     * @return json value
     */
	public function reOrderArtwork(Request $request)
    {
		$customer_id = Auth::guard('web_customer')->id();
		
		
	  $customer_role_id = DB::table('customer_profile')->select('*')->where('id',$customer_id)->first();
	  if($customer_role_id->role_id == 1)
	  {
		$customer_id = Auth::guard('web_customer')->user()->id;
    	$style=$request->style;
        $data['tesco_tcl1_orders'] = DB::table('tesco_tcl1_orders')->where('tesco_tcl1_orders.order_status', '!=', 'Saved')->where('tesco_supplier_id',$customer_role_id->company_id)->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->select(DB::raw('max("tesco_tcl1_orders"."id") as id'),DB::raw('max("tesco_tcl1_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_tcl1_orders.ce_style_ref','tesco_tcl1_orders.uk_style_ref','tesco_tcl1_orders.zone','tesco_tcl1_orders.po_number',DB::raw("'".$this->tcl1."'".' as "TemplateName"'),DB::raw("'".$this->tcl1_type."'".' as "type"'))->groupBy('tesco_tcl1_orders.uk_style_ref')->groupBy('tesco_tcl1_orders.ce_style_ref')->groupBy('tesco_tcl1_orders.zone')->groupBy('tesco_tcl1_orders.po_number')->get();
	  

		$data['tesco_tcl2_orders'] = DB::table('tesco_tcl2_orders')->where('tesco_tcl2_orders.order_status', '!=', 'Saved')->where('tesco_supplier_id',$customer_role_id->company_id)->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->select(DB::raw('max("tesco_tcl2_orders"."id") as id'),DB::raw('max("tesco_tcl2_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_tcl2_orders.ce_style_ref','tesco_tcl2_orders.uk_style_ref','tesco_tcl2_orders.zone','tesco_tcl2_orders.po_number',DB::raw("'".$this->tcl2."'".' as "TemplateName"'),DB::raw("'".$this->tcl2_type."'".' as "type"'))->groupBy('tesco_tcl2_orders.uk_style_ref')->groupBy('tesco_tcl2_orders.ce_style_ref')->groupBy('tesco_tcl2_orders.zone')->groupBy('tesco_tcl2_orders.po_number')->get();


		$data['tesco_tcl4_orders'] = DB::table('tesco_tcl4_orders')->where('tesco_tcl4_orders.order_status', '!=', 'Saved')->where('tesco_supplier_id',$customer_role_id->company_id)->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->select(DB::raw('max("tesco_tcl4_orders"."id") as id'),DB::raw('max("tesco_tcl4_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_tcl4_orders.ce_style_ref','tesco_tcl4_orders.uk_style_ref','tesco_tcl4_orders.zone','tesco_tcl4_orders.po_number',DB::raw("'".$this->tcl4."'".' as "TemplateName"'),DB::raw("'".$this->tcl4_type."'".' as "type"'))->groupBy('tesco_tcl4_orders.uk_style_ref')->groupBy('tesco_tcl4_orders.ce_style_ref')->groupBy('tesco_tcl4_orders.zone')->groupBy('tesco_tcl4_orders.po_number')->get();


		$data['tesco_thl1_orders'] = DB::table('tesco_thl1_orders')->where('tesco_thl1_orders.order_status', '!=', 'Saved')->where('tesco_supplier_id',$customer_role_id->company_id)->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->select(DB::raw('max("tesco_thl1_orders"."id") as id'),DB::raw('max("tesco_thl1_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_thl1_orders.ce_style_ref','tesco_thl1_orders.uk_style_ref','tesco_thl1_orders.zone','tesco_thl1_orders.po_number',DB::raw("'".$this->thl1."'".' as "TemplateName"'),DB::raw("'".$this->thl1_type."'".' as "type"'))->groupBy('tesco_thl1_orders.uk_style_ref')->groupBy('tesco_thl1_orders.ce_style_ref')->groupBy('tesco_thl1_orders.zone')->groupBy('tesco_thl1_orders.po_number')->get();


		$data['tesco_thl3_orders'] = DB::table('tesco_thl3_orders')->where('tesco_thl3_orders.order_status', '!=', 'Saved')->where('tesco_supplier_id',$customer_role_id->company_id)->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->select(DB::raw('max("tesco_thl3_orders"."id") as id'),DB::raw('max("tesco_thl3_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_thl3_orders.ce_style_ref','tesco_thl3_orders.uk_style_ref','tesco_thl3_orders.zone','tesco_thl3_orders.po_number',DB::raw("'".$this->thl3."'".' as "TemplateName"'),DB::raw("'".$this->thl3_type."'".' as "type"'))->groupBy('tesco_thl3_orders.uk_style_ref')->groupBy('tesco_thl3_orders.ce_style_ref')->groupBy('tesco_thl3_orders.zone')->groupBy('tesco_thl3_orders.po_number')->get();


		$data['tesco_tsl1_orders'] = DB::table('tesco_tsl1_orders')->where('tesco_tsl1_orders.order_status', '!=', 'Saved')->where('tesco_supplier_id',$customer_role_id->company_id)->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->select(DB::raw('max("tesco_tsl1_orders"."id") as id'),DB::raw('max("tesco_tsl1_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_tsl1_orders.ce_style_ref','tesco_tsl1_orders.uk_style_ref','tesco_tsl1_orders.zone','tesco_tsl1_orders.po_number',DB::raw("'".$this->tsl1."'".' as "TemplateName"'),DB::raw("'".$this->tsl1_type."'".' as "type"'))->groupBy('tesco_tsl1_orders.uk_style_ref')->groupBy('tesco_tsl1_orders.ce_style_ref')->groupBy('tesco_tsl1_orders.zone')->groupBy('tesco_tsl1_orders.po_number')->get();


		$data['tesco_tsl3_orders'] = DB::table('tesco_tsl3_orders')->where('tesco_tsl3_orders.order_status', '!=', 'Saved')->where('tesco_supplier_id',$customer_role_id->company_id)->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->select(DB::raw('max("tesco_tsl3_orders"."id") as id'),DB::raw('max("tesco_tsl3_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_tsl3_orders.ce_style_ref','tesco_tsl3_orders.uk_style_ref','tesco_tsl3_orders.zone','tesco_tsl3_orders.po_number',DB::raw("'".$this->tsl3."'".' as "TemplateName"'),DB::raw("'".$this->tsl3_type."'".' as "type"'))->groupBy('tesco_tsl3_orders.uk_style_ref')->groupBy('tesco_tsl3_orders.ce_style_ref')->groupBy('tesco_tsl3_orders.zone')->groupBy('tesco_tsl3_orders.po_number')->get();
	  }
	  else
	  {
		 
		  $customer_id = Auth::guard('web_customer')->user()->id;
		  $style=$request->style;
        $data['tesco_tcl1_orders'] = DB::table('tesco_tcl1_orders')->where('tesco_tcl1_orders.order_status', '!=', 'Saved')->where('tesco_supplier_id',$customer_role_id->company_id)->where('order_placed_by','=',$customer_id)->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->where('order_placed_by','=',$customer_id)->select(DB::raw('max("tesco_tcl1_orders"."id") as id'),DB::raw('max("tesco_tcl1_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_tcl1_orders.ce_style_ref','tesco_tcl1_orders.uk_style_ref','tesco_tcl1_orders.zone','tesco_tcl1_orders.po_number',DB::raw("'".$this->tcl1."'".' as "TemplateName"'),DB::raw("'".$this->tcl1_type."'".' as "type"'))->groupBy('tesco_tcl1_orders.uk_style_ref')->groupBy('tesco_tcl1_orders.ce_style_ref')->groupBy('tesco_tcl1_orders.zone')->groupBy('tesco_tcl1_orders.po_number')->get();
	  

		$data['tesco_tcl2_orders'] = DB::table('tesco_tcl2_orders')->where('tesco_tcl2_orders.order_status', '!=', 'Saved')->where('tesco_supplier_id',$customer_role_id->company_id)->where('order_placed_by','=',$customer_id)->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->where('order_placed_by','=',$customer_id)->select(DB::raw('max("tesco_tcl2_orders"."id") as id'),DB::raw('max("tesco_tcl2_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_tcl2_orders.ce_style_ref','tesco_tcl2_orders.uk_style_ref','tesco_tcl2_orders.zone','tesco_tcl2_orders.po_number',DB::raw("'".$this->tcl2."'".' as "TemplateName"'),DB::raw("'".$this->tcl2_type."'".' as "type"'))->groupBy('tesco_tcl2_orders.uk_style_ref')->groupBy('tesco_tcl2_orders.ce_style_ref')->groupBy('tesco_tcl2_orders.zone')->groupBy('tesco_tcl2_orders.po_number')->get();


		$data['tesco_tcl4_orders'] = DB::table('tesco_tcl4_orders')->where('tesco_tcl4_orders.order_status', '!=', 'Saved')->where('tesco_supplier_id',$customer_role_id->company_id)->where('order_placed_by','=',$customer_id)->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->where('order_placed_by','=',$customer_id)->select(DB::raw('max("tesco_tcl4_orders"."id") as id'),DB::raw('max("tesco_tcl4_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_tcl4_orders.ce_style_ref','tesco_tcl4_orders.uk_style_ref','tesco_tcl4_orders.zone','tesco_tcl4_orders.po_number',DB::raw("'".$this->tcl4."'".' as "TemplateName"'),DB::raw("'".$this->tcl4_type."'".' as "type"'))->groupBy('tesco_tcl4_orders.uk_style_ref')->groupBy('tesco_tcl4_orders.ce_style_ref')->groupBy('tesco_tcl4_orders.zone')->groupBy('tesco_tcl4_orders.po_number')->get();


		$data['tesco_thl1_orders'] = DB::table('tesco_thl1_orders')->where('tesco_thl1_orders.order_status', '!=', 'Saved')->where('tesco_supplier_id',$customer_role_id->company_id)->where('order_placed_by','=',$customer_id)->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->where('order_placed_by','=',$customer_id)->select(DB::raw('max("tesco_thl1_orders"."id") as id'),DB::raw('max("tesco_thl1_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_thl1_orders.ce_style_ref','tesco_thl1_orders.uk_style_ref','tesco_thl1_orders.zone','tesco_thl1_orders.po_number',DB::raw("'".$this->thl1."'".' as "TemplateName"'),DB::raw("'".$this->thl1_type."'".' as "type"'))->groupBy('tesco_thl1_orders.uk_style_ref')->groupBy('tesco_thl1_orders.ce_style_ref')->groupBy('tesco_thl1_orders.zone')->groupBy('tesco_thl1_orders.po_number')->get();


		$data['tesco_thl3_orders'] = DB::table('tesco_thl3_orders')->where('tesco_thl3_orders.order_status', '!=', 'Saved')->where('tesco_supplier_id',$customer_role_id->company_id)->where('order_placed_by','=',$customer_id)->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->where('order_placed_by','=',$customer_id)->select(DB::raw('max("tesco_thl3_orders"."id") as id'),DB::raw('max("tesco_thl3_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_thl3_orders.ce_style_ref','tesco_thl3_orders.uk_style_ref','tesco_thl3_orders.zone','tesco_thl3_orders.po_number',DB::raw("'".$this->thl3."'".' as "TemplateName"'),DB::raw("'".$this->thl3_type."'".' as "type"'))->groupBy('tesco_thl3_orders.uk_style_ref')->groupBy('tesco_thl3_orders.ce_style_ref')->groupBy('tesco_thl3_orders.zone')->groupBy('tesco_thl3_orders.po_number')->get();


		$data['tesco_tsl1_orders'] = DB::table('tesco_tsl1_orders')->where('tesco_tsl1_orders.order_status', '!=', 'Saved')->where('tesco_supplier_id',$customer_role_id->company_id)->where('order_placed_by','=',$customer_id)->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->where('order_placed_by','=',$customer_id)->select(DB::raw('max("tesco_tsl1_orders"."id") as id'),DB::raw('max("tesco_tsl1_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_tsl1_orders.ce_style_ref','tesco_tsl1_orders.uk_style_ref','tesco_tsl1_orders.zone','tesco_tsl1_orders.po_number',DB::raw("'".$this->tsl1."'".' as "TemplateName"'),DB::raw("'".$this->tsl1_type."'".' as "type"'))->groupBy('tesco_tsl1_orders.uk_style_ref')->groupBy('tesco_tsl1_orders.ce_style_ref')->groupBy('tesco_tsl1_orders.zone')->groupBy('tesco_tsl1_orders.po_number')->get();


		$data['tesco_tsl3_orders'] = DB::table('tesco_tsl3_orders')->where('tesco_tsl3_orders.order_status', '!=', 'Saved')->where('tesco_supplier_id',$customer_role_id->company_id)->where('order_placed_by','=',$customer_id)->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->where('order_placed_by','=',$customer_id)->select(DB::raw('max("tesco_tsl3_orders"."id") as id'),DB::raw('max("tesco_tsl3_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_tsl3_orders.ce_style_ref','tesco_tsl3_orders.uk_style_ref','tesco_tsl3_orders.zone','tesco_tsl3_orders.po_number',DB::raw("'".$this->tsl3."'".' as "TemplateName"'),DB::raw("'".$this->tsl3_type."'".' as "type"'))->groupBy('tesco_tsl3_orders.uk_style_ref')->groupBy('tesco_tsl3_orders.ce_style_ref')->groupBy('tesco_tsl3_orders.zone')->groupBy('tesco_tsl3_orders.po_number')->get();
	  }
		


		echo json_encode($data);
    }
	
	/**
     * This Function is used to list thl3,tcl4,tsl3 artwork for particular style number.
     *
     * @return json value
     */
	public function repeatOrderArtwork(Request $request)
    {
		$customer_id = Auth::guard('web_customer')->id();
		
		
	  $customer_role_id = DB::table('customer_profile')->select('*')->where('id',$customer_id)->first();
	  if($customer_role_id->role_id == 1)
	  {
		  $customer_id = Auth::guard('web_customer')->user()->id;
		$style=$request->style;

		$data['tesco_tcl4_orders'] = DB::table('tesco_tcl4_orders')->where('tesco_tcl4_orders.order_status', '!=', 'Saved')->where('tesco_supplier_id',$customer_role_id->company_id)->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->select(DB::raw('max("tesco_tcl4_orders"."id") as id'),DB::raw('max("tesco_tcl4_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_tcl4_orders.ce_style_ref','tesco_tcl4_orders.uk_style_ref','tesco_tcl4_orders.zone','tesco_tcl4_orders.po_number',DB::raw("'".$this->tcl4."'".' as "TemplateName"'),DB::raw("'".$this->tcl4_type."'".' as "type"'))->groupBy('tesco_tcl4_orders.uk_style_ref')->groupBy('tesco_tcl4_orders.ce_style_ref')->groupBy('tesco_tcl4_orders.zone')->groupBy('tesco_tcl4_orders.po_number')->get();


		$data['tesco_thl3_orders'] = DB::table('tesco_thl3_orders')->where('tesco_thl3_orders.order_status', '!=', 'Saved')->where('tesco_supplier_id',$customer_role_id->company_id)->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->select(DB::raw('max("tesco_thl3_orders"."id") as id'),DB::raw('max("tesco_thl3_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_thl3_orders.ce_style_ref','tesco_thl3_orders.uk_style_ref','tesco_thl3_orders.zone','tesco_thl3_orders.po_number',DB::raw("'".$this->thl3."'".' as "TemplateName"'),DB::raw("'".$this->thl3_type."'".' as "type"'))->groupBy('tesco_thl3_orders.uk_style_ref')->groupBy('tesco_thl3_orders.ce_style_ref')->groupBy('tesco_thl3_orders.zone')->groupBy('tesco_thl3_orders.po_number')->get();



		$data['tesco_tsl3_orders'] = DB::table('tesco_tsl3_orders')->where('tesco_tsl3_orders.order_status', '!=', 'Saved')->where('tesco_supplier_id',$customer_role_id->company_id)->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->select(DB::raw('max("tesco_tsl3_orders"."id") as id'),DB::raw('max("tesco_tsl3_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_tsl3_orders.ce_style_ref','tesco_tsl3_orders.uk_style_ref','tesco_tsl3_orders.zone','tesco_tsl3_orders.po_number',DB::raw("'".$this->tsl3."'".' as "TemplateName"'),DB::raw("'".$this->tsl3_type."'".' as "type"'))->groupBy('tesco_tsl3_orders.uk_style_ref')->groupBy('tesco_tsl3_orders.ce_style_ref')->groupBy('tesco_tsl3_orders.zone')->groupBy('tesco_tsl3_orders.po_number')->get();
	  }
	  else
	  {
		  $customer_id = Auth::guard('web_customer')->user()->id;
		$style=$request->style;

		$data['tesco_tcl4_orders'] = DB::table('tesco_tcl4_orders')->where('tesco_tcl4_orders.order_status', '!=', 'Saved')->where('tesco_supplier_id',$customer_role_id->company_id)->where('order_placed_by','=',$customer_id)->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->where('order_placed_by','=',$customer_id)->select(DB::raw('max("tesco_tcl4_orders"."id") as id'),DB::raw('max("tesco_tcl4_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_tcl4_orders.ce_style_ref','tesco_tcl4_orders.uk_style_ref','tesco_tcl4_orders.zone','tesco_tcl4_orders.po_number',DB::raw("'".$this->tcl4."'".' as "TemplateName"'),DB::raw("'".$this->tcl4_type."'".' as "type"'))->groupBy('tesco_tcl4_orders.uk_style_ref')->groupBy('tesco_tcl4_orders.ce_style_ref')->groupBy('tesco_tcl4_orders.zone')->groupBy('tesco_tcl4_orders.po_number')->get();


		$data['tesco_thl3_orders'] = DB::table('tesco_thl3_orders')->where('tesco_thl3_orders.order_status', '!=', 'Saved')->where('tesco_supplier_id',$customer_role_id->company_id)->where('order_placed_by','=',$customer_id)->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->where('order_placed_by','=',$customer_id)->select(DB::raw('max("tesco_thl3_orders"."id") as id'),DB::raw('max("tesco_thl3_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_thl3_orders.ce_style_ref','tesco_thl3_orders.uk_style_ref','tesco_thl3_orders.zone','tesco_thl3_orders.po_number',DB::raw("'".$this->thl3."'".' as "TemplateName"'),DB::raw("'".$this->thl3_type."'".' as "type"'))->groupBy('tesco_thl3_orders.uk_style_ref')->groupBy('tesco_thl3_orders.ce_style_ref')->groupBy('tesco_thl3_orders.zone')->groupBy('tesco_thl3_orders.po_number')->get();



		$data['tesco_tsl3_orders'] = DB::table('tesco_tsl3_orders')->where('tesco_tsl3_orders.order_status', '!=', 'Saved')->where('tesco_supplier_id',$customer_role_id->company_id)->where('order_placed_by','=',$customer_id)->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->where('order_placed_by','=',$customer_id)->select(DB::raw('max("tesco_tsl3_orders"."id") as id'),DB::raw('max("tesco_tsl3_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_tsl3_orders.ce_style_ref','tesco_tsl3_orders.uk_style_ref','tesco_tsl3_orders.zone','tesco_tsl3_orders.po_number',DB::raw("'".$this->tsl3."'".' as "TemplateName"'),DB::raw("'".$this->tsl3_type."'".' as "type"'))->groupBy('tesco_tsl3_orders.uk_style_ref')->groupBy('tesco_tsl3_orders.ce_style_ref')->groupBy('tesco_tsl3_orders.zone')->groupBy('tesco_tsl3_orders.po_number')->get();
	  }
		


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
		  $id           =   $request->id;
		  $type_id      =   explode('_',$type);
		  $data['qty']=DB::table($type.'_quanty')->where($type_id[1].'_order_id',$id)->select('*')->get();

		  $data['template']=DB::table($type)->join('tesco_brand','tesco_brand.id','=',$type.'.tesco_brand_id')
			  ->join('tesco_department','tesco_department.id','=',$type.'.tesco_department_id')->join('tesco_suppliers','tesco_suppliers.id','=',$type.'.tesco_metadata_supplier_id')->where($type.'.id',$id)->select($type.'.*','tesco_suppliers.supplier_name','tesco_brand.brand','tesco_department.department')->get();

		  $data['template_file']=DB::table('tesco_template')->where('item_id',$data['template']{0}->template_id)->select('*')->get();
		  
	 	 $data['moq']=DB::table('price_taxation')->where('template_id',$data['template']{0}->template_id)->where('customer_id',$data['template']{0}->tesco_supplier_id)->where('buyer_id',2)->select('*')->get();
	  
     
          echo json_encode($data);
    }
	/**
     * This Function is used to set the tesco reorder.
     *
     * @return json value
     */
  	public function setTescoReorder(Request $request)
    {
            $type         =   $request->type;
		    $id           =   $request->id;
			$counts       =   explode(',',$request->counts);
			if($type == "tesco_tcl1_orders")
			{
				$tasks = TescoTCL1Orders::find($id);
				$newTasks = $tasks->replicate();
				$newTasks->save();
				
				$factory=DB::table('tesco_tcl1_orders')->join('customers','customers.id','=','tesco_tcl1_orders.tesco_supplier_id')->where('tesco_tcl1_orders.id',$id)->select('*')->get();
				
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
				$newTasks->po_status = $po_status;
				$newTasks->pi_status = $pi_status;
				$newTasks->purchase_order_no = "";
				$newTasks->po_file = null;
				$newTasks->order_flag = "reOrder";
				$newTasks->save();
				
				$data['tesco']=DB::table('tesco_tcl1_orders_quanty')->where('tcl1_order_id',$id)->select('*')->get();
				$i=0;
				foreach($data['tesco'] as $value)
				{
					$tasks = TescoTCL1OrdersQuantity::find($value->id);
					$newTask = $tasks->replicate();
					$newTask->save();
					$newTask->tcl1_order_id = $newTasks->id;
					$newTask->count = $counts[$i];
					$newTask->save();
					$i++;
				}
			}
			if($type == "tesco_tcl2_orders")
			{
				$tasks = TescoTCL2Orders::find($id);
				$newTasks = $tasks->replicate();
				$newTasks->save();
				
				$factory=DB::table('tesco_tcl2_orders')->join('customers','customers.id','=','tesco_tcl2_orders.tesco_supplier_id')->where('tesco_tcl2_orders.id',$id)->select('*')->get();
				
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
				$newTasks->po_status = $po_status;
				$newTasks->pi_status = $pi_status;
				$newTasks->purchase_order_no = "";
				$newTasks->po_file = null;
				$newTasks->order_flag = "reOrder";
				$newTasks->save();
				
				$data['tesco']=DB::table('tesco_tcl2_orders_quanty')->where('tcl2_order_id',$id)->select('*')->get();
				$i=0;
				foreach($data['tesco'] as $value)
				{
					$tasks = TescoTCL2OrdersQuantity::find($value->id);
					$newTask = $tasks->replicate();
					$newTask->save();
					$newTask->tcl2_order_id = $newTasks->id;
					$newTask->count = $counts[$i];
					$newTask->save();
					$i++;
				}
			}
			if($type == "tesco_tcl4_orders")
			{
				$tasks = TescoTCL4Orders::find($id);
				$newTasks = $tasks->replicate();
				$newTasks->save();
				
				$factory=DB::table('tesco_tcl4_orders')->join('customers','customers.id','=','tesco_tcl4_orders.tesco_supplier_id')->where('tesco_tcl4_orders.id',$id)->select('*')->get();
				
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
				$newTasks->po_status = $po_status;
				$newTasks->pi_status = $pi_status;
				$newTasks->purchase_order_no = "";
				$newTasks->po_file = null;
				$newTasks->order_flag = "reOrder";
				$newTasks->save();
				
				$data['tesco']=DB::table('tesco_tcl4_orders_quanty')->where('tcl4_order_id',$id)->select('*')->get();
				$i=0;
				foreach($data['tesco'] as $value)
				{
					$tasks = TescoTCL4OrdersQuantity::find($value->id);
					$newTask = $tasks->replicate();
					$newTask->save();
					$newTask->tcl4_order_id = $newTasks->id;
					$newTask->count = $counts[$i];
					$newTask->save();
					$i++;
				}
			}
			if($type == "tesco_thl1_orders")
			{
				$tasks = TescoTHL1Orders::find($id);
				$newTasks = $tasks->replicate();
				$newTasks->save();
				
				
				$factory=DB::table('tesco_thl1_orders')->join('customers','customers.id','=','tesco_thl1_orders.tesco_supplier_id')->where('tesco_thl1_orders.id',$id)->select('*')->get();
				
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
				$newTasks->po_status = $po_status;
				$newTasks->pi_status = $pi_status;
				$newTasks->purchase_order_no = "";
				$newTasks->po_file = null;
				$newTasks->order_flag = "reOrder";
				$newTasks->save();
				
				$data['tesco']=DB::table('tesco_thl1_orders_quanty')->where('thl1_order_id',$id)->select('*')->get();
				$i=0;
				foreach($data['tesco'] as $value)
				{
					$tasks = TescoTHL1OrdersQuantity::find($value->id);
					$newTask = $tasks->replicate();
					$newTask->save();
					$newTask->thl1_order_id = $newTasks->id;
					$newTask->count = $counts[$i];
					$newTask->save();
					$i++;
				}
			}
			if($type == "tesco_thl3_orders")
			{
				$tasks = TescoTHL3Orders::find($id);
				$newTasks = $tasks->replicate();
				$newTasks->save();
				
				
				$factory=DB::table('tesco_thl3_orders')->join('customers','customers.id','=','tesco_thl3_orders.tesco_supplier_id')->where('tesco_thl3_orders.id',$id)->select('*')->get();
				
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
				$newTasks->po_status = $po_status;
				$newTasks->pi_status = $pi_status;
				$newTasks->purchase_order_no = "";
				$newTasks->po_file = null;
				$newTasks->order_flag = "reOrder";
				$newTasks->save();
				
				$data['tesco']=DB::table('tesco_thl3_orders_quanty')->where('thl3_order_id',$id)->select('*')->get();
				$i=0;
				foreach($data['tesco'] as $value)
				{
					$tasks = TescoTHL3OrdersQuantity::find($value->id);
					$newTask = $tasks->replicate();
					$newTask->save();
					$newTask->thl3_order_id = $newTasks->id;
					$newTask->count = $counts[$i];
					$newTask->save();
					$i++;
				}
			}
			if($type == "tesco_tsl1_orders")
			{
				$tasks = TescoTSL1Orders::find($id);
				$newTasks = $tasks->replicate();
				$newTasks->save();
				
				
				$factory=DB::table('tesco_tsl1_orders')->join('customers','customers.id','=','tesco_tsl1_orders.tesco_supplier_id')->where('tesco_tsl1_orders.id',$id)->select('*')->get();
				
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
				$newTasks->po_status = $po_status;
				$newTasks->pi_status = $pi_status;
				$newTasks->purchase_order_no = "";
				$newTasks->po_file = null;
				$newTasks->order_flag = "reOrder";
				$newTasks->save();
				
				$data['tesco']=DB::table('tesco_tsl1_orders_quanty')->where('tsl1_order_id',$id)->select('*')->get();
				$i=0;
				foreach($data['tesco'] as $value)
				{
					$tasks = TescoTSL1OrdersQuantity::find($value->id);
					$newTask = $tasks->replicate();
					$newTask->save();
					$newTask->tsl1_order_id = $newTasks->id;
					$newTask->count = $counts[$i];
					$newTask->save();
					$i++;
				}
			}
			if($type == "tesco_tsl3_orders")
			{
				$tasks = TescoTSL3Orders::find($id);
				$newTasks = $tasks->replicate();
				$newTasks->save();
				
				$factory=DB::table('tesco_tsl3_orders')->join('customers','customers.id','=','tesco_tsl3_orders.tesco_supplier_id')->where('tesco_tsl3_orders.id',$id)->select('*')->get();
				
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
				$newTasks->po_status = $po_status;
				$newTasks->pi_status = $pi_status;
				$newTasks->purchase_order_no = "";
				$newTasks->po_file = null;
				$newTasks->order_flag = "reOrder";
				$newTasks->save();
				
				$data['tesco']=DB::table('tesco_tsl3_orders_quanty')->where('tsl3_order_id',$id)->select('*')->get();
				$i=0;
				foreach($data['tesco'] as $value)
				{
					$tasks = TescoTSL3OrdersQuantity::find($value->id);
					$newTask = $tasks->replicate();
					$newTask->save();
					$newTask->tsl3_order_id = $newTasks->id;
					$newTask->count = $counts[$i];
					$newTask->save();
					$i++;
				}
			}	
		    echo true;
    }
  /**
     * This Function is used to set the tesco repeat order.
     *
     * @return json value
     */
  public function setTescoRepeatorder(Request $request)
  {
      $type         =   $request->type;
      $id           =   $request->id;
      $po           =   $request->po;
	  $ocn_number   =   $request->ocn;
	  $manufacture_date  =   $request->manufacture_date;
	  $counts       =   explode(',',$request->counts);
      if($type == "tesco_tcl1_orders")
      {
        $tasks = TescoTCL1Orders::find($id);
        $newTasks = $tasks->replicate();
        $newTasks->save();
        
        $factory=DB::table('tesco_tcl1_orders')->join('customers','customers.id','=','tesco_tcl1_orders.tesco_supplier_id')->where('tesco_tcl1_orders.id',$id)->select('*')->get();
        
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
				$newTasks->po_status = $po_status;
				$newTasks->pi_status = $pi_status;
				$newTasks->po_number =$po;
		  		$newTasks->ocn_number =$ocn_number;
		  		$newTasks->manufacture_date =$manufacture_date;
				$newTasks->purchase_order_no = "";
				$newTasks->po_file = null;
		  		$newTasks->order_flag = "repeatOrder";
				$newTasks->save();
				
				
				$data['tesco']=DB::table('tesco_tcl1_orders_quanty')->where('tcl1_order_id',$id)->select('*')->get();
				$i=0;
				foreach($data['tesco'] as $value)
				{
					$tasks = TescoTCL1OrdersQuantity::find($value->id);
					$newTask = $tasks->replicate();
					$newTask->save();
					$newTask->tcl1_order_id = $newTasks->id;
					$newTask->count = $counts[$i];
					$newTask->save();
					$i++;
				}
		  	$data=DB::table($type)->where('id',$newTasks->id)->select('*',DB::raw("'".$this->tcl1."'".' as "TemplateName"'),DB::raw("'".$type."'".' as "type"'))->get();	
			}
			if($type == "tesco_tcl2_orders")
			{
				$tasks = TescoTCL2Orders::find($id);
				$newTasks = $tasks->replicate();
				$newTasks->save();
				
				$factory=DB::table('tesco_tcl2_orders')->join('customers','customers.id','=','tesco_tcl2_orders.tesco_supplier_id')->where('tesco_tcl2_orders.id',$id)->select('*')->get();
				
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
				$newTasks->po_status = $po_status;
				$newTasks->pi_status = $pi_status;
				$newTasks->po_number =$po;
		  		$newTasks->ocn_number =$ocn_number;
		  		$newTasks->manufacture_date =$manufacture_date;
				$newTasks->purchase_order_no = "";
				$newTasks->po_file = null;
				$newTasks->order_flag = "repeatOrder";
				$newTasks->save();
				
				$data['tesco']=DB::table('tesco_tcl2_orders_quanty')->where('tcl2_order_id',$id)->select('*')->get();
				$i=0;
				foreach($data['tesco'] as $value)
				{
					$tasks = TescoTCL2OrdersQuantity::find($value->id);
					$newTask = $tasks->replicate();
					$newTask->save();
					$newTask->tcl2_order_id = $newTasks->id;
					$newTask->count = $counts[$i];
					$newTask->save();
					$i++;
				}
				$data=DB::table($type)->where('id',$newTasks->id)->select('*',DB::raw("'".$this->tcl2."'".' as "TemplateName"'),DB::raw("'".$type."'".' as "type"'))->get();
			}
			if($type == "tesco_tcl4_orders")
			{
				$tasks = TescoTCL4Orders::find($id);
				$newTasks = $tasks->replicate();
				$newTasks->save();
				
				
				$factory=DB::table('tesco_tcl4_orders')->join('customers','customers.id','=','tesco_tcl4_orders.tesco_supplier_id')->where('tesco_tcl4_orders.id',$id)->select('*')->get();
				
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
				$newTasks->po_status = $po_status;
				$newTasks->pi_status = $pi_status;
				$newTasks->po_number =$po;
		  		$newTasks->ocn_number =$ocn_number;
		  		$newTasks->manufacture_date =$manufacture_date;
				$newTasks->purchase_order_no = "";
				$newTasks->po_file = null;
				$newTasks->order_flag = "repeatOrder";
				$newTasks->save();
				
				$data['tesco']=DB::table('tesco_tcl4_orders_quanty')->where('tcl4_order_id',$id)->select('*')->get();
				$i=0;
				foreach($data['tesco'] as $value)
				{
					$tasks = TescoTCL4OrdersQuantity::find($value->id);
					$newTask = $tasks->replicate();
					$newTask->save();
					$newTask->tcl4_order_id = $newTasks->id;
					$newTask->count = $counts[$i];
					$newTask->save();
					$i++;
				}
				$data=DB::table($type)->where('id',$newTasks->id)->select('*',DB::raw("'".$this->tcl4."'".' as "TemplateName"'),DB::raw("'".$type."'".' as "type"'))->get();
			}
			if($type == "tesco_thl1_orders")
			{
				$tasks = TescoTHL1Orders::find($id);
				$newTasks = $tasks->replicate();
				$newTasks->save();
				
				
				$factory=DB::table('tesco_thl1_orders')->join('customers','customers.id','=','tesco_thl1_orders.tesco_supplier_id')->where('tesco_thl1_orders.id',$id)->select('*')->get();
				
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
				$newTasks->po_status = $po_status;
				$newTasks->pi_status = $pi_status;
				$newTasks->po_number =$po;
		  		$newTasks->ocn_number =$ocn_number;
		  		$newTasks->manufacture_date =$manufacture_date;
				$newTasks->purchase_order_no = "";
				$newTasks->po_file = null;
				$newTasks->order_flag = "repeatOrder";
				$newTasks->save();
				
				$data['tesco']=DB::table('tesco_thl1_orders_quanty')->where('thl1_order_id',$id)->select('*')->get();
				$i=0;
				foreach($data['tesco'] as $value)
				{
					$tasks = TescoTHL1OrdersQuantity::find($value->id);
					$newTask = $tasks->replicate();
					$newTask->save();
					$newTask->thl1_order_id = $newTasks->id;
					$newTask->count = $counts[$i];
					$newTask->save();
					$i++;
				}
				$data=DB::table($type)->where('id',$newTasks->id)->select('*',DB::raw("'".$this->thl1."'".' as "TemplateName"'),DB::raw("'".$type."'".' as "type"'))->get();
			}
			if($type == "tesco_thl3_orders")
			{
				$tasks = TescoTHL3Orders::find($id);
				$newTasks = $tasks->replicate();
				$newTasks->save();
				
				
				$factory=DB::table('tesco_thl3_orders')->join('customers','customers.id','=','tesco_thl3_orders.tesco_supplier_id')->where('tesco_thl3_orders.id',$id)->select('*')->get();
				
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
				$newTasks->po_status = $po_status;
				$newTasks->pi_status = $pi_status;
				$newTasks->po_number =$po;
		  		$newTasks->ocn_number =$ocn_number;
		  		$newTasks->manufacture_date =$manufacture_date;
				$newTasks->purchase_order_no = "";
				$newTasks->po_file = null;
				$newTasks->order_flag = "repeatOrder";
				$newTasks->save();
				
				$data['tesco']=DB::table('tesco_thl3_orders_quanty')->where('thl3_order_id',$id)->select('*')->get();
				$i=0;
				foreach($data['tesco'] as $value)
				{
					$tasks = TescoTHL3OrdersQuantity::find($value->id);
					$newTask = $tasks->replicate();
					$newTask->save();
					$newTask->thl3_order_id = $newTasks->id;
					$newTask->count = $counts[$i];
					$newTask->save();
					$i++;
				}
				$data=DB::table($type)->where('id',$newTasks->id)->select('*',DB::raw("'".$this->thl3."'".' as "TemplateName"'),DB::raw("'".$type."'".' as "type"'))->get();
			}
			if($type == "tesco_tsl1_orders")
			{
				$tasks = TescoTSL1Orders::find($id);
				$newTasks = $tasks->replicate();
				$newTasks->save();
				
				
				$factory=DB::table('tesco_tsl1_orders')->join('customers','customers.id','=','tesco_tsl1_orders.tesco_supplier_id')->where('tesco_tsl1_orders.id',$id)->select('*')->get();
				
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
				$newTasks->po_status = $po_status;
				$newTasks->pi_status = $pi_status;
				$newTasks->po_number =$po;
		  		$newTasks->ocn_number =$ocn_number;
		  		$newTasks->manufacture_date =$manufacture_date;
				$newTasks->purchase_order_no = "";
				$newTasks->po_file = null;
				$newTasks->order_flag = "repeatOrder";
				$newTasks->save();
				
				$data['tesco']=DB::table('tesco_tsl1_orders_quanty')->where('tsl1_order_id',$id)->select('*')->get();
				$i=0;
				foreach($data['tesco'] as $value)
				{
					$tasks = TescoTSL1OrdersQuantity::find($value->id);
					$newTask = $tasks->replicate();
					$newTask->save();
					$newTask->tsl1_order_id = $newTasks->id;
					$newTask->count = $counts[$i];
					$newTask->save();
					$i++;
				}
				$data=DB::table($type)->where('id',$newTasks->id)->select('*',DB::raw("'".$this->tsl1."'".' as "TemplateName"'),DB::raw("'".$type."'".' as "type"'))->get();
			}
			if($type == "tesco_tsl3_orders")
			{
				$tasks = TescoTSL3Orders::find($id);
				$newTasks = $tasks->replicate();
				$newTasks->save();
				
				
				$factory=DB::table('tesco_tsl3_orders')->join('customers','customers.id','=','tesco_tsl3_orders.tesco_supplier_id')->where('tesco_tsl3_orders.id',$id)->select('*')->get();
				
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
				$newTasks->po_status = $po_status;
				$newTasks->pi_status = $pi_status;
				$newTasks->po_number =$po;
		  		$newTasks->ocn_number =$ocn_number;
		  		$newTasks->manufacture_date =$manufacture_date;
				$newTasks->purchase_order_no = "";
				$newTasks->po_file = null;
				$newTasks->order_flag = "repeatOrder";
				$newTasks->save();
				
				$data['tesco']=DB::table('tesco_tsl3_orders_quanty')->where('tsl3_order_id',$id)->select('*')->get();
				$i=0;
				foreach($data['tesco'] as $value)
				{
					$tasks = TescoTSL3OrdersQuantity::find($value->id);
					$newTask = $tasks->replicate();
					$newTask->save();
					$newTask->tsl3_order_id = $newTasks->id;
					$newTask->count = $counts[$i];
					$newTask->save();
					$i++;
				}
				$data=DB::table($type)->where('id',$newTasks->id)->select('*',DB::raw("'".$this->tsl3."'".' as "TemplateName"'),DB::raw("'".$type."'".' as "type"'))->get();
			}	
		    
			echo $data;
    }
	 /**
     * Function to get tesco tsl template UK , CE size chart based on size entered.
     * @author <Praveen>
     * @param [string] $[size] [<Size entered>], [string] $[zone] [<zone selected>]
     * @return equivalent uk,ce size for size entered
     */
    public function getTescoTSLSizechart(Request $request)
    {
       try {
             $zone = $request['zone'];
             $size = $request['size'];

           if($zone == 'UK')
           {
               $size = DB::table('tesco_size_chart')->select('uk as size')->where('po_size','=',$size)->where('is_enabled','=',1)->get();
           }
           else
           {
               $size = DB::table('tesco_size_chart')->select('ce as size')->where('po_size','=',$size)->where('is_enabled','=',1)->get();
           }
           
           return response()->json(['size_chart' => $size]);
       } catch (\Exception $e) {
           return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
       }
    }
	/**
     * This function is used to check exist Po exists or not
     * 
     * @return boolean values
     */
    public function editCheckExistPoRepeat(Request $request)
    {
		$template_name  = $request['template_name'];
		$uk_style	    = $request['uk_style'];
		$po_number	    = $request['po_number'];
		$ce_style	    = $request['ce_style'];
		$zone =$request['zone'];
		
		if($template_name == "TCL1")
		{
			$table_name="tesco_tcl1_orders";
			$qty_table_name="tesco_tcl1_orders_quanty";
		}
		else if($template_name == "TCL2")
		{
			$table_name="tesco_tcl2_orders";
			$qty_table_name="tesco_tcl2_orders_quanty";
		}
		else if($template_name == "TCL4")
		{
			$table_name="tesco_tcl4_orders";
			$qty_table_name="tesco_tcl4_orders_quanty";
		}
		else if($template_name == "TSL1")
		{
			$table_name="tesco_tsl1_orders";
			$qty_table_name="tesco_tsl1_orders_quanty";
		}
		else if($template_name == "TSL3")
		{
			$table_name="tesco_tsl3_orders";
			$qty_table_name="tesco_tsl3_orders_quanty";
		}
		else if($template_name == "THL1")
		{
			$table_name="tesco_thl1_orders";
			$qty_table_name="tesco_thl1_orders_quanty";
		}
		else if($template_name == "THL3")
		{
			$table_name="tesco_thl3_orders";
			$qty_table_name="tesco_thl3_orders_quanty";
		}
		
		if($zone == "CE")
		{
			$template_exists=DB::table($table_name)->where('po_number',$po_number)->where('ce_style_ref',$ce_style)->where('order_flag','!=','reOrder')->select('*')->get();
		}
		else
		{
			$template_exists=DB::table($table_name)->where('po_number',$po_number)->where('uk_style_ref',$uk_style)->where('order_flag','!=','reOrder')->select('*')->get();
		}
		
		if(count($template_exists) > 0) 
		{
			echo "false";
		}
		else
		{
			echo "true";
		}
		
		
	}
}