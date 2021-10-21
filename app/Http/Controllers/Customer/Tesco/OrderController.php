<?php

namespace App\Http\Controllers\Customer\Tesco;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
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
use App\Classes\TescoOrders;
use App\Classes\SaveTescoOrders;
use App\Classes\ApproveTescoOrders;
use App\Classes\CancelTescoOrders;
use PDF;
use Carbon\Carbon;
use App\Classes\ErpOrders;
use App\OrderManagementHistory;
use App\FinancialYearResetDate;
use App\TescoWashcareOrders;

class OrderController extends Controller
{
   
    private $tcl1;
    private $tcl2;
    private $tcl4;
    private $thl1;
    private $thl3;
    private $tsl1;
    private $tsl3;
    private $tcl1_type;
    private $tcl2_type;
    private $tcl4_type;
    private $thl1_type;
    private $thl3_type;
    private $tsl1_type;
    private $tsl3_type;
    private $current_date;
    private $ratio_pack_type;
    private $financial_year_date;
    private $buyer_id;
    
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
       $this->tcl5 = "TCL 5";
       $this->tsl2 = "TSL 2";

       $this->tcl1_type = "tesco_tcl1_orders";
       $this->tcl2_type = "tesco_tcl2_orders";
       $this->tcl4_type = "tesco_tcl4_orders";
       $this->thl1_type = "tesco_thl1_orders";
       $this->thl3_type = "tesco_thl3_orders";
       $this->tsl1_type = "tesco_tsl1_orders";
       $this->tsl3_type = "tesco_tsl3_orders";
       $this->tcl5_type = "tesco_tcl5_orders";
       $this->tsl2_type = "tesco_tsl2_orders";
       $this->tesco_washcare_orders_type = "tesco_washcare_orders";

       // get the current time
       $this->current_date = Carbon::now('Asia/Kolkata')->toDateTimeString();

       $this->ratio_pack_type = ['RatA', 'RatB', 'RatC', 'RatD', 'RatE', 'RatF', 'RatG', 'RatH', 'RatI', 'RatJ', 'RatK', 'RatL', 'RatM', 'RatN', 'RatO', 'RatP', 'RatQ', 'RatR', 'RatS', 'RatT', 'RatU', 'RatV', 'RatW', 'RatX', 'RatY', 'RatZ'];

       $this->financial_year_date = FinancialYearResetDate::value('financial_year_date');

       $this->buyer_id = DB::table("buyers")->where('buyer_name','ilike','Tesco')->pluck('id')->first();

    }

    /**
     * Function to get tesco order page
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function Orders(Request $request)
    {
	    $customer_id = Auth::guard('web_customer')->id();
      $year = explode('-', $request->year);
      $from_date = $year[0].'-'.$this->financial_year_date;
      $to_date =Carbon::parse($from_date)->addYear()->subDay()->toDateString();
		
	  $customer_role_id = DB::table('customer_profile')->select('*')->where('id',$customer_id)->first();
	  if($customer_role_id->role_id == 1)
	  {
		  $data['tesco_tcl1_orders'] = DB::table('tesco_tcl1_orders')->select('*',DB::raw("'".$this->tcl1."'".' as "TemplateName"'))->where('tesco_supplier_id','=',$customer_role_id->company_id)->where('order_flag', 'newOrder')->where('order_status','!=', 'Order Cancelled')->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->get();

		  $data['tesco_tcl2_orders'] = DB::table('tesco_tcl2_orders')->select('*',DB::raw("'".$this->tcl2."'".' as "TemplateName"'))->where('tesco_supplier_id','=',$customer_role_id->company_id)->where('order_flag', 'newOrder')->where('order_status','!=', 'Order Cancelled')->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->get();

		  $data['tesco_tcl4_orders'] = DB::table('tesco_tcl4_orders')->select('*',DB::raw("'".$this->tcl4."'".' as "TemplateName"'))->where('tesco_supplier_id','=',$customer_role_id->company_id)->where('order_flag', 'newOrder')->where('order_status','!=', 'Order Cancelled')->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->get();

		  $data['tesco_thl1_orders'] = DB::table('tesco_thl1_orders')->select('*',DB::raw("'".$this->thl1."'".' as "TemplateName"'))->where('tesco_supplier_id','=',$customer_role_id->company_id)->where('order_flag', 'newOrder')->where('order_status','!=', 'Order Cancelled')->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->get();

		  $data['tesco_thl3_orders'] = DB::table('tesco_thl3_orders')->select('*',DB::raw("'".$this->thl3."'".' as "TemplateName"'))->where('tesco_supplier_id','=',$customer_role_id->company_id)->where('order_flag', 'newOrder')->where('order_status','!=', 'Order Cancelled')->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->get();

		  $data['tesco_tsl1_orders'] = DB::table('tesco_tsl1_orders')->select('*',DB::raw("'".$this->tsl1."'".' as "TemplateName"'))->where('tesco_supplier_id','=',$customer_role_id->company_id)->where('order_flag', 'newOrder')->where('order_status','!=', 'Order Cancelled')->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->get();

      $data['tesco_tsl3_orders'] = DB::table('tesco_tsl3_orders')->select('*',DB::raw("'".$this->tsl3."'".' as "TemplateName"'))->where('tesco_supplier_id','=',$customer_role_id->company_id)->where('order_flag', 'newOrder')->where('order_status','!=', 'Order Cancelled')->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->get();

      $data['tesco_tsl2_orders'] = DB::table('tesco_tsl2_orders')->select('*',DB::raw("'".$this->tsl2."'".' as "TemplateName"'))->where('tesco_supplier_id','=',$customer_role_id->company_id)->where('order_flag', 'newOrder')->where('order_status','!=', 'Order Cancelled')->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->get();

      $data['tesco_tcl5_orders'] = DB::table('tesco_tcl5_orders')->select('*',DB::raw("'".$this->tcl5."'".' as "TemplateName"'))->where('tesco_supplier_id','=',$customer_role_id->company_id)->where('order_flag', 'newOrder')->where('order_status','!=', 'Order Cancelled')->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->get();

      
      
      $data['tesco_washcare_orders'] = DB::table('tesco_washcare_orders')
      ->select('tesco_washcare_orders.id', 'tesco_washcare_orders.template_id', 'tesco_washcare_orders.zone', 'tesco_washcare_orders.template_type', 'tesco_washcare_orders.po_number', 'tesco_washcare_orders.uk_style_ref', 'tesco_washcare_orders.ce_style_ref', 'tesco_washcare_orders.order_status', 'tesco_item_master.item_name as TemplateName')
      ->join('tesco_item_master', 'tesco_item_master.id', 'tesco_washcare_orders.template_id')
      ->where('tesco_washcare_orders.order_flag', 'newOrder')
      ->where('tesco_washcare_orders.order_status', '!=', 'Order Cancelled')
      ->whereBetween('tesco_washcare_orders.created_at', [$from_date, $to_date])
      ->orderBy('tesco_washcare_orders.id', 'desc')
      ->get();
	  }
	  else
	  {
		  $data['tesco_tcl1_orders'] = DB::table('tesco_tcl1_orders')->select('*',DB::raw("'".$this->tcl1."'".' as "TemplateName"'))->where('tesco_supplier_id','=',$customer_role_id->company_id)->where('order_placed_by','=',Auth::guard('web_customer')->id())->where('order_flag', 'newOrder')->where('order_status','!=', 'Order Cancelled')->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->get();

		  $data['tesco_tcl2_orders'] = DB::table('tesco_tcl2_orders')->select('*',DB::raw("'".$this->tcl2."'".' as "TemplateName"'))->where('tesco_supplier_id','=',$customer_role_id->company_id)->where('order_placed_by','=',Auth::guard('web_customer')->id())->where('order_flag', 'newOrder')->where('order_status','!=', 'Order Cancelled')->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->get();

		  $data['tesco_tcl4_orders'] = DB::table('tesco_tcl4_orders')->select('*',DB::raw("'".$this->tcl4."'".' as "TemplateName"'))->where('tesco_supplier_id','=',$customer_role_id->company_id)->where('order_placed_by','=',Auth::guard('web_customer')->id())->where('order_flag', 'newOrder')->where('order_status','!=', 'Order Cancelled')->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->get();

		  $data['tesco_thl1_orders'] = DB::table('tesco_thl1_orders')->select('*',DB::raw("'".$this->thl1."'".' as "TemplateName"'))->where('tesco_supplier_id','=',$customer_role_id->company_id)->where('order_placed_by','=',Auth::guard('web_customer')->id())->where('order_flag', 'newOrder')->where('order_status','!=', 'Order Cancelled')->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->get();

		  $data['tesco_thl3_orders'] = DB::table('tesco_thl3_orders')->select('*',DB::raw("'".$this->thl3."'".' as "TemplateName"'))->where('tesco_supplier_id','=',$customer_role_id->company_id)->where('order_placed_by','=',Auth::guard('web_customer')->id())->where('order_flag', 'newOrder')->where('order_status','!=', 'Order Cancelled')->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->get();

		  $data['tesco_tsl1_orders'] = DB::table('tesco_tsl1_orders')->select('*',DB::raw("'".$this->tsl1."'".' as "TemplateName"'))->where('tesco_supplier_id','=',$customer_role_id->company_id)->where('order_placed_by','=',Auth::guard('web_customer')->id())->where('order_flag', 'newOrder')->where('order_status','!=', 'Order Cancelled')->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->get();

      $data['tesco_tsl3_orders'] = DB::table('tesco_tsl3_orders')->select('*',DB::raw("'".$this->tsl3."'".' as "TemplateName"'))->where('tesco_supplier_id','=',$customer_role_id->company_id)->where('order_placed_by','=',Auth::guard('web_customer')->id())->where('order_flag', 'newOrder')->where('order_status','!=', 'Order Cancelled')->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->get();

      $data['tesco_tsl2_orders'] = DB::table('tesco_tsl2_orders')->select('*',DB::raw("'".$this->tsl2."'".' as "TemplateName"'))->where('tesco_supplier_id','=',$customer_role_id->company_id)->where('order_placed_by','=',Auth::guard('web_customer')->id())->where('order_flag', 'newOrder')->where('order_status','!=', 'Order Cancelled')->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->get();

      $data['tesco_tcl5_orders'] = DB::table('tesco_tcl5_orders')->select('*',DB::raw("'".$this->tcl5."'".' as "TemplateName"'))->where('tesco_supplier_id','=',$customer_role_id->company_id)->where('order_placed_by','=',Auth::guard('web_customer')->id())->where('order_flag', 'newOrder')->where('order_status','!=', 'Order Cancelled')->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->get();
      
      $data['tesco_washcare_orders'] = DB::table('tesco_washcare_orders')
      ->select('tesco_washcare_orders.id', 'tesco_washcare_orders.template_id', 'tesco_washcare_orders.zone', 'tesco_washcare_orders.template_type', 'tesco_washcare_orders.po_number', 'tesco_washcare_orders.uk_style_ref', 'tesco_washcare_orders.ce_style_ref', 'tesco_washcare_orders.order_status', 'tesco_item_master.item_name as TemplateName')
      ->join('tesco_item_master', 'tesco_item_master.id', 'tesco_washcare_orders.template_id')
      ->where('tesco_washcare_orders.order_flag', 'newOrder')
      ->where('tesco_washcare_orders.order_status', '!=', 'Order Cancelled')
      ->where('order_placed_by', Auth::guard('web_customer')->id())
      ->whereBetween('tesco_washcare_orders.created_at', [$from_date, $to_date])
      ->orderBy('tesco_washcare_orders.id', 'desc')
      ->get();
    }
    
    $data['tesco_size_department'] = DB::table('tesco_size_department')
    ->join('tesco_primary_size', 'tesco_size_department_id', 'tesco_size_department.id')
    ->where('tesco_primary_size.is_flag', 1)
    ->pluck('tesco_size_department.department_name', 'tesco_size_department.id')
      ->all();
      
      echo json_encode($data);
    }

    /**
     * Function to generate tesco templates preview
     * @author <Praveen>
     * @return tesco template details
     */
      public function templateView(Request $request)
      {
           $template_id = $request['template_id'];
           $exp_temp = explode(',',$template_id);
           $view=array();
           foreach($exp_temp as $exp)
           {
              $treeviewData= DB::table("tesco_template")
                                  ->select('tesco_template.*','tesco_item_master.ref_no','company.name as company_name')
                                  ->join('tesco_item_master','tesco_item_master.id','=','tesco_template.item_id')
                                  ->join('company','company.id','=','tesco_item_master.company_id')
                                  ->where('item_id',$exp)->orderBy('tesco_template.id','asc')
                                  ->get();
               array_push($view,$treeviewData);
           }
           
           return json_encode($view);
      }

      /**
       * Function to get tesco templates details by id's
       * @author <Praveen>
       * @return tesco template details
       */
      public function getTescoTemplateDetails(Request $request)
      {
         try {
             $templates = explode(',',$request['template_id']);
             $id = Auth::guard('web_customer')->id();
             $buyer_id = $request['buyer_id'];
             $customer = DB::table('customer_profile')
                          ->select('*')
                          ->where('customer_profile.id','=',$id)
                          ->first();
             $moq_array = array();
             $moq_value = array();
             /* Fetch all template details by id */
             foreach ($templates as $template_id) 
             {
                 $template_details[] = DB::table('tesco_template')
                             ->select('tesco_item_master.id','tesco_template.template_name','tesco_template.template_file','tesco_item_master.is_qrcode_required','company.name as company')
                             ->join('tesco_item_master','tesco_template.item_id','=','tesco_item_master.id')
                             ->join('company','company.id','=','tesco_item_master.company_id')
                             ->where('tesco_item_master.id','=',$template_id)
                             ->first();
                 
                    $temp_name = DB::table('tesco_item_master')
                             ->select('tesco_item_master.item_name')
                             ->where('id','=',$template_id)
                             ->first();
                    $moq = DB::table('price_taxation')
                             ->select('price_taxation.moq')
                             ->where('template_id','=',$template_id)
                             ->where('customer_id','=',$customer->company_id)
                             ->where('buyer_id','=',$buyer_id)
                             ->first();
                    if($moq)
                    {
                      $moq_value[$temp_name->item_name] = $moq->moq;
                    }
                    else
                    {
                      $moq_value[$temp_name->item_name] = 0;
                    }
             }
             array_push($moq_array,$moq_value);

             /* Fetch tesco brands,departments and suppliers details */
             $tesco_brand = DB::table('tesco_brand')->select('brand','id')->where('is_enabled','=',1)->get();
             $tesco_department = DB::table('tesco_department')->select('department','id')->where('is_enabled','=',1)->get();
             $tesco_suppliers = DB::table('tesco_suppliers')
                                      ->select('tesco_suppliers.supplier_name','tesco_suppliers.id')
                                      ->where('is_enabled','=',1)
                                      ->join('customer_metadata','customer_metadata.customer_metadata_id','tesco_suppliers.id')
                                      ->where('customer_metadata.customer_id','=',$customer->company_id)
                                      ->get();
             
             return response()->json(['templates' => $template_details,'tesco_brand' => $tesco_brand,'tesco_department' => $tesco_department,'tesco_suppliers' => $tesco_suppliers,'moq' => $moq_array]);
         } catch (\Exception $e) {
             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
         }
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
               $size = DB::table('tesco_size_chart')->select('uk as size')->where('po_size', 'ilike', $size)->where('is_enabled','=',1)->get();
           }
           else
           {
               $size = DB::table('tesco_size_chart')->select('ce as size')->where('po_size', 'ilike', $size)->where('is_enabled','=',1)->get();
           }
           
           return response()->json(['size_chart' => $size]);
       } catch (\Exception $e) {
           return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
       }
    }
    /**
     * Store CE ratio template orders
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function setCERatioOrders(Request $request)
    {
        try {
            /** [ Creating new object for TescoOrders() class ] */
            $tesco_order_obj  = new TescoOrders();
            
            $orms_tpn         = explode(',',$request['orms_tpn']);
            $itf_code         = explode(',',$request['itf_code']);
            $cases_per_carton = explode(',',$request['cases_per_carton']);
            $templates        = $request['templates'];
            $last_insertions  = [];
            $already_exists   = [];
            $created_by       = $request['created_by'];
            $zero_quantity    = [];
            $qr_code          = explode('|',$request['qr_code']);

            $tesco_main_customer_id = DB::table("customer_metadata")->where('customer_metadata_id','=',$request['supplier'])->select('customer_id')->first();
            
            if($tesco_main_customer_id)
            {
              $customer_id = $tesco_main_customer_id->customer_id;
              $customer_details = DB::table("customers")->where('id','=',$customer_id)->select('pi_required','delivery_date_required','delivery_day_count','po_required')->first();  
              $delivery_date_req= $customer_details->delivery_date_required; 
              $pi_required      = $customer_details->pi_required; 
              $po_required      = $customer_details->po_required; 
              $day_count        = $customer_details->delivery_day_count; 
              $ordered_date     = Carbon::now('Asia/Kolkata')->addDay($day_count);
            } 
            else
            {
              $customer_id      = NULL;
              $delivery_date_req= 0; 
              $pi_required      = 0; 
              $po_required      = 0; 
              $day_count        = 0; 
              $ordered_date     = Carbon::now('Asia/Kolkata')->addDay($day_count);
            }
            foreach ($templates as $template) 
            {
              $template_id      = $template['id'];
              if($template['template_name'] != "THL 1")
              {
                $count_TCL1       = explode(',',$request['count_TCL1']);
                $count_TCL2       = explode(',',$request['count_TCL2']);
                $count_TSL1       = explode(',',$request['count_TSL1']);

                if($template['template_name'] == "TCL 1")
                {
                  /** check all entries of array equal to FALSE(ie. 0) */
                   $is_order_count_empty = (array_sum($count_TCL1)>0)?0:1;

                    /** [ Check if order already placed for this CE style ] */
                    $is_exists = TescoTCL1Orders::select('id')->Where('ce_style_ref','=',$request['ce_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();

                    if($is_exists)
                    {
                       /** [ Create array of already exists artworks ] */
                       array_push($already_exists,'TCL 1');
                    }
                    else
                    {
                      $order_status  = $is_order_count_empty === 1?'Order Pending':'Order Placed';
                      $edit_permission  = $is_order_count_empty === 1?1:0;
                      /** [ Invoking createTescoOrder function using class instance ] */
                       $tesco_order     = $tesco_order_obj->createTescoOrder($request,$template_id,$pi_required,$is_order_count_empty,$customer_id,$created_by,$po_required, $order_status, $edit_permission);

                       /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                       $tesco_order_id  = $tesco_order->id;
                        TescoTCL1OrdersQuantity::where('tcl1_order_id',$tesco_order_id)
                          ->delete();
                        $i = 0;
                        foreach ($orms_tpn as $tpn) 
                        {
                          TescoTCL1OrdersQuantity::create([
                              'tcl1_order_id'    => $tesco_order_id,
                              'orms_tpn'         => $tpn,
                              'itf_barcode'      => $itf_code[$i],
                              'cases_per_carton' => $cases_per_carton[$i],
                              'count'            => $count_TCL1[$i],
                              'ratio_pack_type'  => $this->ratio_pack_type[$i],
                              'qr_code'          => $qr_code[$i]
                            ]);
                          $i++;
                        }
                        
                        /** [ tesco order id, that has artwork count > 0] */
                        $templates_inserted = array('TCL 1' => $tesco_order_id);

                       /** [ Return array of inserted id ] */
                       $last_insertions = array_merge($last_insertions,$templates_inserted);
                       $quantity_empty  = $is_order_count_empty == 1?array('TCL 1'):[];
                       $zero_quantity   = array_merge($zero_quantity,$quantity_empty);
                    }
                   
                }
                else if($template['template_name'] == "TCL 2")
                {
                  /** check all entries of array equal to FALSE(ie. 0) */
                  $is_order_count_empty = (array_sum($count_TCL2)>0)?0:1;

                    /** [ Check if order already placed for this CE style ] */
                    $is_exists = TescoTCL2Orders::select('id')->Where('ce_style_ref','=',$request['ce_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                    if($is_exists)
                    {
                      /** [ Create array of already exists artworks ] */
                       array_push($already_exists,'TCL 2');
                    }
                    else
                    {
                         $order_status  = $is_order_count_empty === 1?'Order Pending':'Order Placed';
                         $edit_permission  = $is_order_count_empty === 1?1:0;
                         /** [ Invoking createTescoOrder function using class instance ] */
                         $tesco_order     = $tesco_order_obj->createTescoOrder($request,$template_id,$pi_required,$is_order_count_empty,$customer_id,$created_by,$po_required, $order_status, $edit_permission);

                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                         $tesco_order_id  = $tesco_order->id;
                         TescoTCL2OrdersQuantity::where('tcl2_order_id',$tesco_order_id)
                            ->delete();
                         $i = 0;
                         foreach ($orms_tpn as $tpn) 
                         {
                           TescoTCL2OrdersQuantity::create([
                                'tcl2_order_id'    => $tesco_order_id,
                                'orms_tpn'         => $tpn,
                                'itf_barcode'      => $itf_code[$i],
                                'cases_per_carton' => $cases_per_carton[$i],
                                'count'            => $count_TCL2[$i],
                                'ratio_pack_type'  => $this->ratio_pack_type[$i],
                                'qr_code'          => $qr_code[$i]
                              ]);
                           $i++;
                         }

                         /** [ tesco order id, that has artwork count > 0] */
                         $templates_inserted = array('TCL 2' => $tesco_order_id);

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                         $quantity_empty  = $is_order_count_empty == 1?array('TCL 2'):[];
                         $zero_quantity   = array_merge($zero_quantity,$quantity_empty);
                   }
                }
                else
                {
                  /** check all entries of array equal to FALSE(ie. 0) */
                    $is_order_count_empty = (array_sum($count_TSL1)>0)?0:1;

                    /** [ Check if order already placed for this CE style ] */
                    $is_exists = TescoTSL1Orders::select('id')->Where('ce_style_ref','=',$request['ce_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                    if($is_exists)
                    {
                      /** [ Create array of already exists artworks ] */
                       array_push($already_exists,'TSL 1');
                    }
                    else
                    {
                        $order_status  = $is_order_count_empty === 1?'Order Pending':'Order Placed';
                        $edit_permission  = $is_order_count_empty === 1?1:0;
                        /** [ Invoking createTescoOrder function using class instance ] */
                         $tesco_order     = $tesco_order_obj->createTescoOrder($request,$template_id,$pi_required,$is_order_count_empty,$customer_id,$created_by,$po_required, $order_status, $edit_permission);
                         
                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                         $tesco_order_id  = $tesco_order->id;
                           TescoTSL1OrdersQuantity::where('tsl1_order_id',$tesco_order_id)
                              ->delete();
                           $i = 0;
                           foreach ($orms_tpn as $tpn) 
                           {
                             TescoTSL1OrdersQuantity::create([
                                  'tsl1_order_id'    => $tesco_order_id,
                                  'orms_tpn'         => $tpn,
                                  'itf_barcode'      => $itf_code[$i],
                                  'cases_per_carton' => $cases_per_carton[$i],
                                  'count'            => $count_TSL1[$i],
                                  'ratio_pack_type'  => $this->ratio_pack_type[$i],
                                  'qr_code'          => $qr_code[$i]
                                ]);
                             $i++;
                           }

                           /** [ tesco order id, that has artwork count > 0] */
                           $templates_inserted = array('TSL 1' => $tesco_order_id);

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                         $quantity_empty  = $is_order_count_empty == 1?array('TSL 1'):[];
                         $zero_quantity   = array_merge($zero_quantity,$quantity_empty);
                    }
                }
              }
              else
              {
                 $count_THL1       = explode(',',$request['count_THL1']);

                 /** check all entries of array equal to FALSE(ie. 0) */
                  $is_order_count_empty = (array_sum($count_THL1)>0)?0:1;

                  /** [ Check if order already placed for this CE style ] */
                  $is_exists = TescoTHL1Orders::select('id')->Where('ce_style_ref','=',$request['ce_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                  if($is_exists)
                  {
                    /** [ Create array of already exists artworks ] */
                    array_push($already_exists,'THL 1');
                  }
                  else
                  {
                    $order_status  = $is_order_count_empty === 1?'Order Pending':'Order Placed';
                    $edit_permission  = $is_order_count_empty === 1?1:0;
                     /** [ Invoking createTescoOrder function using class instance ] */
                     $tesco_order     = $tesco_order_obj->createTescoOrder($request,$template_id,$pi_required,$is_order_count_empty,$customer_id,$created_by,$po_required, $order_status, $edit_permission);

                     /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                     $tesco_order_id  = $tesco_order->id;
                     TescoTHL1OrdersQuantity::where('thl1_order_id',$tesco_order_id)
                        ->delete();
                     $i = 0;
                     foreach ($orms_tpn as $tpn) 
                     {
                       TescoTHL1OrdersQuantity::create([
                            'thl1_order_id'    => $tesco_order_id,
                            'orms_tpn'         => $tpn,
                            'itf_barcode'      => $itf_code[$i],
                            'cases_per_carton' => $cases_per_carton[$i],
                            'count'            => $count_THL1[$i],
                            'ratio_pack_type'  => $this->ratio_pack_type[$i],
                            'qr_code'          => $qr_code[$i]
                          ]);
                       $i++;
                     }

                     /** [ tesco order id, that has artwork count > 0] */
                     $templates_inserted = array('THL 1' => $tesco_order_id);

                     /** [ Return array of inserted id ] */
                     $last_insertions = array_merge($last_insertions,$templates_inserted);
                     $quantity_empty  = $is_order_count_empty == 1?array('THL 1'):[];
                     $zero_quantity   = array_merge($zero_quantity,$quantity_empty);
                 }
              }
            }
            
            return response()->json(['error'=> true,'message' =>'success','last_insertions' => $last_insertions,'delivery_date_required' => $delivery_date_req,'ordered_date' => $ordered_date,'already_exists' => $already_exists,'po_required' => $po_required ,'pi_required'=>$pi_required, 'zero_quantity' => $zero_quantity],200);
           

        } catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
    /**
     * Store CE single template orders
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function setCESingleOrders(Request $request)
    {
        try {
            /** [ Creating new object for TescoOrders() class ] */
            $tesco_order_obj  = new TescoOrders();
            
            $orms_tpn         = explode(',',$request['orms_tpn']);
            $ean_code         = explode(',',$request['ean_code']);
            $case_size        = explode(',',$request['case_size']);
            $size             = explode(',',$request['size']);
            $templates        = $request['templates'];
            $last_insertions  = [];
            $already_exists   = [];
            $created_by       = $request['created_by'];
            $zero_quantity    = []; 
            $qr_code          = explode('|',$request['qr_code']);

            $tesco_main_customer_id = DB::table("customer_metadata")->where('customer_metadata_id','=',$request['supplier'])->select('customer_id')->first();
            
            if($tesco_main_customer_id)
            {
              $customer_id = $tesco_main_customer_id->customer_id;
              $customer_details = DB::table("customers")->where('id','=',$customer_id)->select('pi_required','delivery_date_required','delivery_day_count','po_required')->first();  
              $delivery_date_req= $customer_details->delivery_date_required; 
              $pi_required      = $customer_details->pi_required; 
              $po_required      = $customer_details->po_required; 
              $day_count        = $customer_details->delivery_day_count; 
              $ordered_date     = Carbon::now('Asia/Kolkata')->addDay($day_count);
            } 
            else
            {
              $customer_id      = NULL;
              $delivery_date_req= 0; 
              $pi_required      = 0; 
              $po_required      = 0; 
              $day_count        = 0; 
              $ordered_date     = Carbon::now('Asia/Kolkata')->addDay($day_count);
            }

            foreach ($templates as $template) 
            {
              $template_id      = $template['id'];
              if($template['template_name'] != "THL 3")
              {
                $count_TCL4       = explode(',',$request['count_TCL4']);
                $count_TSL3       = explode(',',$request['count_TSL3']);
                
                if($template['template_name'] == "TCL 4")
                {
                  /** check all entries of array equal to FALSE(ie. 0) */
                   $is_order_count_empty = (array_sum($count_TCL4)>0)?0:1;

                    /** [ Check if order already placed for this CE style ] */
                    $is_exists = TescoTCL4Orders::select('id')->Where('ce_style_ref','=',$request['ce_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                    if($is_exists)
                    {
                      /** [ Create array of already exists artworks ] */
                       array_push($already_exists,'TCL 4');
                    }
                    else
                    {
                        $order_status  = $is_order_count_empty === 1?'Order Pending':'Order Placed';
                        $edit_permission  = $is_order_count_empty === 1?1:0;
                         /** [ Invoking createTescoOrder function using class instance ] */
                         $tesco_order     = $tesco_order_obj->createTescoOrder($request,$template_id,$pi_required,$is_order_count_empty,$customer_id,$created_by,$po_required, $order_status, $edit_permission);
                         
                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                         $tesco_order_id  = $tesco_order->id;
                         TescoTCL4OrdersQuantity::where('tcl4_order_id',$tesco_order_id)
                            ->delete();
                         $i = 0;
                         foreach ($orms_tpn as $tpn) 
                         {
                           TescoTCL4OrdersQuantity::updateOrCreate(['tcl4_order_id' => $tesco_order_id, 'size' => $size[$i]], [
                                'orms_tpn'         => $tpn,
                                'ean_barcode'      => $ean_code[$i],
                                'case_size'        => $case_size[$i],
                                'count'            => $count_TCL4[$i],
                                'qr_code'          => $qr_code[$i]
                              ]);
                           $i++;
                         }

                         /** [ tesco order id, that has artwork count > 0] */
                         $templates_inserted = array('TCL 4' => $tesco_order_id);

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                         $quantity_empty  = $is_order_count_empty == 1?array('TCL 4'):[];
                         $zero_quantity   = array_merge($zero_quantity,$quantity_empty);
                    }
                }
                else
                {
                   /** check all entries of array equal to FALSE(ie. 0) */
                    $is_order_count_empty = (array_sum($count_TSL3)>0)?0:1;

                    /** [ Check if order already placed for this CE style ] */
                    $is_exists = TescoTSL3Orders::select('id')->Where('ce_style_ref','=',$request['ce_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                    if($is_exists)
                    {
                      /** [ Create array of already exists artworks ] */
                       array_push($already_exists,'TSL 3');
                    }
                    else
                    {
                         $order_status  = $is_order_count_empty === 1?'Order Pending':'Order Placed';
                         $edit_permission  = $is_order_count_empty === 1?1:0;
                         /** [ Invoking createTescoOrder function using class instance ] */
                         $tesco_order     = $tesco_order_obj->createTescoOrder($request,$template_id,$pi_required,$is_order_count_empty,$customer_id,$created_by,$po_required, $order_status, $edit_permission);
                         
                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                         $tesco_order_id  = $tesco_order->id;
                         TescoTSL3OrdersQuantity::where('tsl3_order_id',$tesco_order_id)
                            ->delete();
                         $i = 0;
                         foreach ($orms_tpn as $tpn) 
                         {
                           TescoTSL3OrdersQuantity::updateOrCreate(['tsl3_order_id' => $tesco_order_id, 'size' => $size[$i]], [
                                'orms_tpn'         => $tpn,
                                'ean_barcode'      => $ean_code[$i],
                                'case_size'        => $case_size[$i],
                                'count'            => $count_TSL3[$i],
                                'qr_code'          => $qr_code[$i]
                              ]);
                           $i++;
                         }

                         /** [ tesco order id, that has artwork count > 0] */
                         $templates_inserted = array('TSL 3' => $tesco_order_id);

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                         $quantity_empty  = $is_order_count_empty == 1?array('TSL 3'):[];
                         $zero_quantity   = array_merge($zero_quantity,$quantity_empty);
                    }
                }
              }
              else
              {
                 $count_THL3       = explode(',',$request['count_THL3']);

                 /** check all entries of array equal to FALSE(ie. 0) */
                  $is_order_count_empty = (array_sum($count_THL3)>0)?0:1;

                    /** [ Check if order already placed for this CE style ] */
                    $is_exists = TescoTHL3Orders::select('id')->Where('ce_style_ref','=',$request['ce_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                    if($is_exists)
                    {
                      /** [ Create array of already exists artworks ] */
                       array_push($already_exists,'THL 3');
                    }
                    else
                    {
                         $order_status  = $is_order_count_empty === 1?'Order Pending':'Order Placed';
                         $edit_permission  = $is_order_count_empty === 1?1:0;
                         /** [ Invoking createTescoOrder function using class instance ] */
                         $tesco_order     = $tesco_order_obj->createTescoOrder($request,$template_id,$pi_required,$is_order_count_empty,$customer_id,$created_by,$po_required, $order_status, $edit_permission);
                         
                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                         $tesco_order_id  = $tesco_order->id;
                         TescoTHL3OrdersQuantity::where('thl3_order_id',$tesco_order_id)
                            ->delete();
                         $i = 0;
                         foreach ($orms_tpn as $tpn) 
                         {
                           TescoTHL3OrdersQuantity::updateOrCreate(['thl3_order_id' => $tesco_order_id, 'size' => $size[$i]], [
                                'orms_tpn'         => $tpn,
                                'ean_barcode'      => $ean_code[$i],
                                'case_size'        => $case_size[$i],
                                'count'            => $count_THL3[$i],
                                'qr_code'          => $qr_code[$i]
                              ]);
                           $i++;
                         }

                         /** [ tesco order id, that has artwork count > 0] */
                         $templates_inserted = array('THL 3' => $tesco_order_id);
                         
                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                         $quantity_empty  = $is_order_count_empty == 1?array('THL 3'):[];
                         $zero_quantity   = array_merge($zero_quantity,$quantity_empty);
                    }
              }
            }
            
           return response()->json(['error'=> false,'message' =>'success','last_insertions' => $last_insertions,'delivery_date_required' => $delivery_date_req,'ordered_date' => $ordered_date,'already_exists' => $already_exists,'po_required' => $po_required,'pi_required'=>$pi_required, 'zero_quantity' => $zero_quantity],200);

        } catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
    /**
     * Store UK ratio template orders
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function setUKRatioOrders(Request $request)
    {
        try {
            /** [ Creating new object for TescoOrders() class ] */
            $tesco_order_obj  = new TescoOrders();
            
            $tpnd             = explode(',',$request['tpnd']);
            $itf_code         = explode(',',$request['itf_code']);
            $units_per_carton = explode(',',$request['units_per_carton']);
            $templates        = $request['templates'];
            $last_insertions  = [];
            $already_exists   = [];
            $created_by       = $request['created_by'];
            $zero_quantity    = [];

            $tesco_main_customer_id = DB::table("customer_metadata")->where('customer_metadata_id','=',$request['supplier'])->select('customer_id')->first();
            
            if($tesco_main_customer_id)
            {
              $customer_id = $tesco_main_customer_id->customer_id;
              $customer_details = DB::table("customers")->where('id','=',$customer_id)->select('pi_required','delivery_date_required','delivery_day_count','po_required')->first();  
              $delivery_date_req= $customer_details->delivery_date_required; 
              $pi_required      = $customer_details->pi_required; 
              $po_required      = $customer_details->po_required; 
              $day_count        = $customer_details->delivery_day_count; 
              $ordered_date     = Carbon::now('Asia/Kolkata')->addDay($day_count);
            } 
            else
            {
              $customer_id      = NULL;
              $delivery_date_req= 0; 
              $pi_required      = 0; 
              $po_required      = 0; 
              $day_count        = 0; 
              $ordered_date     = Carbon::now('Asia/Kolkata')->addDay($day_count);
            }

            foreach ($templates as $template) 
            {
              $template_id      = $template['id'];
              if($template['template_name'] != "THL 1")
              {
                $count_TCL1       = explode(',',$request['count_TCL1']);
                $count_TCL2       = explode(',',$request['count_TCL2']);
                $count_TSL1       = explode(',',$request['count_TSL1']);

                if($template['template_name'] == "TCL 1")
                {
                  /** check all entries of array equal to FALSE(ie. 0) */
                    $is_order_count_empty = (array_sum($count_TCL1)>0)?0:1;

                    /** [ Check if order already placed for this UK style ] */
                    $is_exists = TescoTCL1Orders::select('id')->where('uk_style_ref','=',$request['uk_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                    if($is_exists)
                    {
                      /** [ Create array of already exists artworks ] */
                       array_push($already_exists,'TCL 1');
                    }
                    else
                    {
                         $order_status  = $is_order_count_empty === 1?'Order Pending':'Order Placed';
                         $edit_permission  = $is_order_count_empty === 1?1:0;
                         /** [ Invoking createTescoOrder function using class instance ] */
                         $tesco_order     = $tesco_order_obj->createTescoOrder($request,$template_id,$pi_required,$is_order_count_empty,$customer_id,$created_by,$po_required, $order_status, $edit_permission);
                         
                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                         $tesco_order_id  = $tesco_order->id;
                         TescoTCL1OrdersQuantity::where('tcl1_order_id',$tesco_order_id)
                            ->delete();
                         $i = 0;
                         foreach ($tpnd as $value) 
                         {
                           TescoTCL1OrdersQuantity::create([
                                'tcl1_order_id'    => $tesco_order_id,
                                'tpnd'             => $value,
                                'itf_barcode'      => $itf_code[$i],
                                'units_per_carton' => $units_per_carton[$i],
                                'count'            => $count_TCL1[$i],
                                'ratio_pack_type'  => $this->ratio_pack_type[$i]
                              ]);
                           $i++;
                         }

                         /** [ tesco order id, that has artwork count > 0] */
                         $templates_inserted = array('TCL 1' => $tesco_order_id);

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                         $quantity_empty  = $is_order_count_empty == 1?array('TCL 1'):[];
                         $zero_quantity   = array_merge($zero_quantity,$quantity_empty);
                    }
                }
                else if($template['template_name'] == "TCL 2")
                {
                    /** check all entries of array equal to FALSE(ie. 0) */
                    $is_order_count_empty = (array_sum($count_TCL2)>0)?0:1;

                    /** [ Check if order already placed for this UK style ] */
                    $is_exists = TescoTCL2Orders::select('id')->where('uk_style_ref','=',$request['uk_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                    if($is_exists)
                    {
                      /** [ Create array of already exists artworks ] */
                       array_push($already_exists,'TCL 2');
                    }
                    else
                    {
                         $order_status  = $is_order_count_empty === 1?'Order Pending':'Order Placed';
                         $edit_permission  = $is_order_count_empty === 1?1:0;
                         /** [ Invoking createTescoOrder function using class instance ] */
                         $tesco_order     = $tesco_order_obj->createTescoOrder($request,$template_id,$pi_required,$is_order_count_empty,$customer_id,$created_by,$po_required, $order_status, $edit_permission);
                         
                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                         $tesco_order_id  = $tesco_order->id;
                           TescoTCL2OrdersQuantity::where('tcl2_order_id',$tesco_order_id)
                              ->delete();
                           $i = 0;
                           foreach ($tpnd as $value) 
                           {
                             TescoTCL2OrdersQuantity::create([
                                  'tcl2_order_id'    => $tesco_order_id,
                                  'tpnd'             => $value,
                                  'itf_barcode'      => $itf_code[$i],
                                  'units_per_carton' => $units_per_carton[$i],
                                  'count'            => $count_TCL2[$i],
                                  'ratio_pack_type'  => $this->ratio_pack_type[$i]
                                ]);
                             $i++;
                           }

                           /** [ tesco order id, that has artwork count > 0] */
                           $templates_inserted = array('TCL 2' => $tesco_order_id);

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                         $quantity_empty  = $is_order_count_empty == 1?array('TCL 2'):[];
                         $zero_quantity   = array_merge($zero_quantity,$quantity_empty);
                    }
                }
                else
                {
                  /** check all entries of array equal to FALSE(ie. 0) */
                  $is_order_count_empty = (array_sum($count_TSL1)>0)?0:1;

                  /** [ Check if order already placed for this UK style ] */
                  $is_exists = TescoTSL1Orders::select('id')->where('uk_style_ref','=',$request['uk_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                  if($is_exists)
                  {
                    /** [ Create array of already exists artworks ] */
                    array_push($already_exists,'TSL 1');
                  }
                  else
                  {
                    $order_status  = $is_order_count_empty === 1?'Order Pending':'Order Placed';
                    $edit_permission  = $is_order_count_empty === 1?1:0;
                     /** [ Invoking createTescoOrder function using class instance ] */
                     $tesco_order     = $tesco_order_obj->createTescoOrder($request,$template_id,$pi_required,$is_order_count_empty,$customer_id,$created_by,$po_required, $order_status, $edit_permission);
                     
                     /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                     $tesco_order_id  = $tesco_order->id;
                     TescoTSL1OrdersQuantity::where('tsl1_order_id',$tesco_order_id)
                        ->delete();
                     $i = 0;
                     foreach ($tpnd as $value) 
                     {
                       TescoTSL1OrdersQuantity::create([
                            'tsl1_order_id'    => $tesco_order_id,
                            'tpnd'             => $value,
                            'itf_barcode'      => $itf_code[$i],
                            'units_per_carton' => $units_per_carton[$i],
                            'count'            => $count_TSL1[$i],
                            'ratio_pack_type'  => $this->ratio_pack_type[$i]
                          ]);
                       $i++;
                     }

                     /** [ tesco order id, that has artwork count > 0] */
                     $templates_inserted = array('TSL 1' => $tesco_order_id);
                     
                     /** [ Return array of inserted id ] */
                     $last_insertions = array_merge($last_insertions,$templates_inserted);
                     $quantity_empty  = $is_order_count_empty == 1?array('TSL 1'):[];
                     $zero_quantity   = array_merge($zero_quantity,$quantity_empty);
                   }
                }
              }
              else
              {
                 $count_THL1       = explode(',',$request['count_THL1']);

                 /** check all entries of array equal to FALSE(ie. 0) */
                  $is_order_count_empty = (array_sum($count_THL1)>0)?0:1;

                  /** [ Check if order already placed for this UK style ] */
                  $is_exists = TescoTHL1Orders::select('id')->where('uk_style_ref','=',$request['uk_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                  if($is_exists)
                  {
                    /** [ Create array of already exists artworks ] */
                    array_push($already_exists,'THL 1');
                  }
                  else
                  {
                       $order_status  = $is_order_count_empty === 1?'Order Pending':'Order Placed';
                       $edit_permission  = $is_order_count_empty === 1?1:0;
                       /** [ Invoking createTescoOrder function using class instance ] */
                       $tesco_order     = $tesco_order_obj->createTescoOrder($request,$template_id,$pi_required,$is_order_count_empty,$customer_id,$created_by,$po_required, $order_status, $edit_permission);
                       
                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                         $tesco_order_id  = $tesco_order->id;
                         TescoTHL1OrdersQuantity::where('thl1_order_id',$tesco_order_id)
                            ->delete();
                         $i = 0;
                         foreach ($tpnd as $value) 
                         {
                           TescoTHL1OrdersQuantity::create([
                                'thl1_order_id'    => $tesco_order_id,
                                'tpnd'             => $value,
                                'itf_barcode'      => $itf_code[$i],
                                'units_per_carton' => $units_per_carton[$i],
                                'count'            => $count_THL1[$i],
                                'ratio_pack_type'  => $this->ratio_pack_type[$i]
                              ]);
                           $i++;
                         }

                         /** [ tesco order id, that has artwork count > 0] */
                         $templates_inserted = array('THL 1' => $tesco_order_id);

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                         $quantity_empty  = $is_order_count_empty == 1?array('THL 1'):[];
                         $zero_quantity   = array_merge($zero_quantity,$quantity_empty);
                    }
              }
            }
            
           return response()->json(['error'=> false,'message' =>'success','last_insertions' => $last_insertions,'delivery_date_required' => $delivery_date_req,'ordered_date' => $ordered_date,'already_exists' => $already_exists,'po_required' => $po_required,'pi_required'=>$pi_required, 'zero_quantity' => $zero_quantity],200);

        } catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
    /**
     * Store UK single template orders
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function setUKSingleOrders(Request $request)
    {
        try {
            /** [ Creating new object for TescoOrders() class ] */
            $tesco_order_obj  = new TescoOrders();
            
            $tpnd             = explode(',',$request['tpnd']);
            $ean_code         = explode(',',$request['ean_code']);
            $case_size        = explode(',',$request['case_size']);
            $size             = explode(',',$request['size']);
            $templates        = $request['templates'];
            $last_insertions  = [];
            $already_exists   = [];
            $created_by       = $request['created_by'];
            $zero_quantity    = [];

            $tesco_main_customer_id = DB::table("customer_metadata")->where('customer_metadata_id','=',$request['supplier'])->select('customer_id')->first();
            
            if($tesco_main_customer_id)
            {
              $customer_id = $tesco_main_customer_id->customer_id;
              $customer_details = DB::table("customers")->where('id','=',$customer_id)->select('pi_required','delivery_date_required','delivery_day_count','po_required')->first();  
              $delivery_date_req= $customer_details->delivery_date_required; 
              $pi_required      = $customer_details->pi_required; 
              $po_required      = $customer_details->po_required; 
              $day_count        = $customer_details->delivery_day_count; 
              $ordered_date     = Carbon::now('Asia/Kolkata')->addDay($day_count);
            } 
            else
            {
              $customer_id      = NULL;
              $delivery_date_req= 0; 
              $pi_required      = 0; 
              $po_required      = 0; 
              $day_count        = 0; 
              $ordered_date     = Carbon::now('Asia/Kolkata')->addDay($day_count);
            }
            
            foreach ($templates as $template) 
            {
              $template_id      = $template['id'];
              if($template['template_name'] != "THL 3")
              {
                $count_TCL4       = explode(',',$request['count_TCL4']);
                $count_TSL3       = explode(',',$request['count_TSL3']);
                
                if($template['template_name'] == "TCL 4")
                {
                     /** check all entries of array equal to FALSE(ie. 0) */
                      $is_order_count_empty = (array_sum($count_TCL4)>0)?0:1;

                      /** [ Check if order already placed for this UK style ] */
                      $is_exists = TescoTCL4Orders::select('id')->where('uk_style_ref','=',$request['uk_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                      if($is_exists)
                      {
                        /** [ Create array of already exists artworks ] */
                        array_push($already_exists,'TCL 4');
                      }
                      else
                      {
                        $order_status  = $is_order_count_empty === 1?'Order Pending':'Order Placed';
                        $edit_permission  = $is_order_count_empty === 1?1:0;
                         /** [ Invoking createTescoOrder function using class instance ] */
                         $tesco_order     = $tesco_order_obj->createTescoOrder($request,$template_id,$pi_required,$is_order_count_empty,$customer_id,$created_by,$po_required, $order_status, $edit_permission);
                         
                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                         $tesco_order_id  = $tesco_order->id;
                         TescoTCL4OrdersQuantity::where('tcl4_order_id',$tesco_order_id)
                            ->delete();
                         $i = 0;
                         foreach ($tpnd as $value) 
                         {
                           TescoTCL4OrdersQuantity::updateOrCreate(['tcl4_order_id' => $tesco_order_id, 'size' => $size[$i]], [
                                'tpnd'             => $value,
                                'ean_barcode'      => $ean_code[$i],
                                'case_size'        => $case_size[$i],
                                'count'            => $count_TCL4[$i]
                              ]);
                           $i++;
                         }

                         /** [ tesco order id, that has artwork count > 0] */
                         $templates_inserted = array('TCL 4' => $tesco_order_id);

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                         $quantity_empty  = $is_order_count_empty == 1?array('TCL 4'):[];
                         $zero_quantity   = array_merge($zero_quantity,$quantity_empty);
                    }
                }
                else
                {
                      /** check all entries of array equal to FALSE(ie. 0) */
                      $is_order_count_empty = (array_sum($count_TSL3)>0)?0:1;

                      /** [ Check if order already placed for this UK style ] */
                      $is_exists = TescoTSL3Orders::select('id')->where('uk_style_ref','=',$request['uk_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                      if($is_exists)
                      {
                        /** [ Create array of already exists artworks ] */
                        array_push($already_exists,'TSL 3');
                      }
                      else
                      {
                        $order_status  = $is_order_count_empty === 1?'Order Pending':'Order Placed';
                        $edit_permission  = $is_order_count_empty === 1?1:0;
                         /** [ Invoking createTescoOrder function using class instance ] */
                         $tesco_order     = $tesco_order_obj->createTescoOrder($request,$template_id,$pi_required,$is_order_count_empty,$customer_id,$created_by,$po_required, $order_status, $edit_permission);
                         
                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                         $tesco_order_id  = $tesco_order->id;
                         TescoTSL3OrdersQuantity::where('tsl3_order_id',$tesco_order_id)
                            ->delete();
                         $i = 0;
                         foreach ($tpnd as $value) 
                         {
                           TescoTSL3OrdersQuantity::updateOrCreate(['tsl3_order_id' => $tesco_order_id, 'size' => $size[$i]], [
                                'tpnd'             => $value,
                                'ean_barcode'      => $ean_code[$i],
                                'case_size'        => $case_size[$i],
                                'count'            => $count_TSL3[$i]
                              ]);
                           $i++;
                         }

                         /** [ tesco order id, that has artwork count > 0] */
                         $templates_inserted = array('TSL 3' => $tesco_order_id);

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                         $quantity_empty  = $is_order_count_empty == 1?array('TSL 3'):[];
                         $zero_quantity   = array_merge($zero_quantity,$quantity_empty);
                      }
                }
              }
              else
              {
                 $count_THL3       = explode(',',$request['count_THL3']);

                 /** check all entries of array equal to FALSE(ie. 0) */
                   $is_order_count_empty = (array_sum($count_THL3)>0)?0:1;

                  /** [ Check if order already placed for this UK style ] */
                   $is_exists = TescoTHL3Orders::select('id')->where('uk_style_ref','=',$request['uk_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                  if($is_exists)
                  {
                    /** [ Create array of already exists artworks ] */
                    array_push($already_exists,'THL 3');
                  }
                  else
                  {
                       $order_status  = $is_order_count_empty === 1?'Order Pending':'Order Placed';
                       $edit_permission  = $is_order_count_empty === 1?1:0;
                       /** [ Invoking createTescoOrder function using class instance ] */
                       $tesco_order     = $tesco_order_obj->createTescoOrder($request,$template_id,$pi_required,$is_order_count_empty,$customer_id,$created_by,$po_required, $order_status, $edit_permission);
                       
                       /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                       $tesco_order_id  = $tesco_order->id;
                       TescoTHL3OrdersQuantity::where('thl3_order_id',$tesco_order_id)
                          ->delete();
                       $i = 0;
                       foreach ($tpnd as $value) 
                       {
                         TescoTHL3OrdersQuantity::updateOrCreate(['thl3_order_id' => $tesco_order_id, 'size' => $size[$i]], [
                              'tpnd'             => $value,
                              'ean_barcode'      => $ean_code[$i],
                              'case_size'        => $case_size[$i],
                              'count'            => $count_THL3[$i]
                            ]);
                         $i++;
                       }

                       /** [ tesco order id, that has artwork count > 0] */
                       $templates_inserted = array('THL 3' => $tesco_order_id);

                       /** [ Return array of inserted id ] */
                       $last_insertions = array_merge($last_insertions,$templates_inserted);
                       $quantity_empty  = $is_order_count_empty == 1?array('THL 3'):[];
                       $zero_quantity   = array_merge($zero_quantity,$quantity_empty);
                  }
              }
            }
            
           return response()->json(['error'=> false,'message' =>'success','last_insertions' => $last_insertions,'delivery_date_required' => $delivery_date_req,'ordered_date' => $ordered_date,'already_exists' => $already_exists,'po_required' => $po_required,'pi_required'=>$pi_required, 'zero_quantity' => $zero_quantity],200);

        } catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
    /**
     * Function to upload combined buyer po file for tesco
     * @author <Praveen>
     * @return boolean true or false
     */
    public function uploadCombinedOrdersPO(Request $request)
    {
      try {
         $erp_obj      = new ErpOrders();
         $updated_erp_table_array = [];
         $template_ids = explode(',',$request['template_id']);
         $template     = explode(',',$request['templates']);
        
         $po_file = $request['po_file'];
         $destination_path='/storage/app/public/po-files/';
         $path='storage/app/public/po-files/';
         $Orname = $po_file->getClientOriginalName();
         $filename= 'TESCO_PO_'.time().'_'.preg_replace('/#[^\s]+/','',preg_replace('/\s+/', '-', $Orname));
         $po_file->move($path,$filename);
         $destination_path_insert=$destination_path.$filename;
          foreach($template_ids as $key => $template_id)
          {
              if($template_id > 0)
              {
                //Dynamic function name and database model name for each templates
                $model_name = 'App\\'."Tesco".$template[$key]."Orders";
                $order_table_name = "tesco_".strtolower($template[$key])."_orders";
                $response = $model_name::where('id',$template_id)
                              ->update([
                              'po_status'         => "PO Uploaded",
                              'purchase_order_no' => $request['po_number'],
                              'po_file'           => $destination_path_insert,
                              'delivery_date'     => $request['delivery_date'],
                              'po_uploaded_at'    => $this->current_date
                            ]);
                /** @var array [Pass array of values to updateTescoErpOrders function] */
                $data_array = [];   
                $tesco_buyer_id = DB::table('buyers')->select('id')->where('buyer_name','=','Tesco')->first();
                $data_array['tableName'] = $order_table_name;
                $data_array['buyer_id'] = $tesco_buyer_id->id;
                $data_array['id'] = $template_id;
                
                $jsonResponse = $erp_obj->updateTescoErpOrders($data_array);

                $content = $jsonResponse->getContent();
                
                $updated_erp_table_array = json_decode($content, true);

                // update po upload status as "PO Uploaded" in order history table
                OrderManagementHistory::where([['order_table_name', $order_table_name], ['order_table_id', $template_id]])->update([
                  'po_status'   => 1,
                  'customer_po' => $request['po_number'],
                ]);
              }
          }

          $class_status = array_key_exists('message', $updated_erp_table_array)?$updated_erp_table_array['message']:'error';

          if ($class_status === 'success') 
          {
            $result = response()->json(['error'=> false,'message' =>'success', 'po_number' => $request['po_number']],200);
          }
          else
          {
           $result = response()->json(['error'=> true,'message' =>'Failed'],400);
          }
           
       } catch (\Exception $e) {
           $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
       }
       return $result;
    }
    /**
     * Save CE ratio template orders
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function saveCERatioOrders(Request $request)
    {
      try {
              /** [ Creating new object for SaveTescoOrders() class ] */
              $tesco_order_obj = new SaveTescoOrders();
          
              $orms_tpn         = explode(',',$request['orms_tpn']);
              $itf_code         = explode(',',$request['itf_code']);
              $cases_per_carton = explode(',',$request['cases_per_carton']);
              $templates        = $request['templates'];
              $last_insertions  = [];
              $already_exists   = [];
              $created_by       = $request['created_by'];
              $qr_code          = explode('|',$request['qr_code']);

              foreach ($templates as $template) 
              {
                $template_id      = $template['id'];
                if($template['template_name'] != "THL 1")
                {
                  $count_TCL1       = explode(',',$request['count_TCL1']);
                  $count_TCL2       = explode(',',$request['count_TCL2']);
                  $count_TSL1       = explode(',',$request['count_TSL1']);

                  if($template['template_name'] == "TCL 1")
                  {
                    /** [ Check if order already placed for this CE style ] */
                   $is_exists = TescoTCL1Orders::select('id')->Where('ce_style_ref','=',$request['ce_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                    if($is_exists)
                    {
                      /** [ Create array of already exists artworks ] */
                      array_push($already_exists,'TCL 1');
                    }
                    else
                    {
                      /** [ Invoking saveTescoOrder function using class instance ] */
                       $tesco_order     = $tesco_order_obj->saveTescoOrder($request,$template_id,$created_by);

                       /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                       $tesco_order_id  = $tesco_order->id;
                         TescoTCL1OrdersQuantity::where('tcl1_order_id',$tesco_order_id)
                            ->delete();
                         $templates_inserted = array('TCL 1' => $tesco_order_id);
                         $i = 0;
                         foreach ($orms_tpn as $tpn) 
                         {
                           TescoTCL1OrdersQuantity::create([
                                'tcl1_order_id'    => $tesco_order_id,
                                'orms_tpn'         => $tpn,
                                'itf_barcode'      => $itf_code[$i],
                                'cases_per_carton' => $cases_per_carton[$i],
                                'count'            => $count_TCL1[$i],
                                'qr_code'          => $qr_code[$i]
                              ]);
                           $i++;
                         }

                       /** [ Return array of inserted id ] */
                       $last_insertions = array_merge($last_insertions,$templates_inserted);
                    }
                     
                  }
                  else if($template['template_name'] == "TCL 2")
                  {
                    /** [ Check if order already placed for this CE style ] */
                   $is_exists = TescoTCL2Orders::select('id')->Where('ce_style_ref','=',$request['ce_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                    if($is_exists)
                    {
                      /** [ Create array of already exists artworks ] */
                      array_push($already_exists,'TCL 2');
                    }
                    else
                    {
                      /** [ Invoking saveTescoOrder function using class instance ] */
                       $tesco_order     = $tesco_order_obj->saveTescoOrder($request,$template_id,$created_by);
                       
                       /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                        $tesco_order_id  = $tesco_order->id;
                        TescoTCL2OrdersQuantity::where('tcl2_order_id',$tesco_order_id)
                        ->delete();
                        $templates_inserted = array('TCL 2' => $tesco_order_id);
                        $i = 0;
                        foreach ($orms_tpn as $tpn) 
                        {
                          TescoTCL2OrdersQuantity::create([
                              'tcl2_order_id'    => $tesco_order_id,
                              'orms_tpn'         => $tpn,
                              'itf_barcode'      => $itf_code[$i],
                              'cases_per_carton' => $cases_per_carton[$i],
                              'count'            => $count_TCL2[$i],
                              'qr_code'          => $qr_code[$i]
                            ]);
                          $i++;
                        }

                       /** [ Return array of inserted id ] */
                       $last_insertions = array_merge($last_insertions,$templates_inserted);
                    }
                       
                  }
                  else
                  {
                    /** [ Check if order already placed for this CE style ] */
                   $is_exists = TescoTSL1Orders::select('id')->Where('ce_style_ref','=',$request['ce_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                    if($is_exists)
                    {
                      /** [ Create array of already exists artworks ] */
                      array_push($already_exists,'TSL 1');
                    }
                    else
                    {
                      /** [ Invoking saveTescoOrder function using class instance ] */
                       $tesco_order     = $tesco_order_obj->saveTescoOrder($request,$template_id,$created_by);
                       
                       /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                        $tesco_order_id  = $tesco_order->id;
                        TescoTSL1OrdersQuantity::where('tsl1_order_id',$tesco_order_id)
                        ->delete();
                        $templates_inserted = array('TSL 1' => $tesco_order_id);
                        $i = 0;
                        foreach ($orms_tpn as $tpn) 
                        {
                          TescoTSL1OrdersQuantity::create([
                              'tsl1_order_id'    => $tesco_order_id,
                              'orms_tpn'         => $tpn,
                              'itf_barcode'      => $itf_code[$i],
                              'cases_per_carton' => $cases_per_carton[$i],
                              'count'            => $count_TSL1[$i],
                              'qr_code'          => $qr_code[$i]
                            ]);
                          $i++;
                        }

                       /** [ Return array of inserted id ] */
                       $last_insertions = array_merge($last_insertions,$templates_inserted);
                    }
                      
                  }
                }
                else
                {
                   $count_THL1       = explode(',',$request['count_THL1']);

                   /** [ Check if order already placed for this CE style ] */
                   $is_exists = TescoTHL1Orders::select('id')->Where('ce_style_ref','=',$request['ce_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                    if($is_exists)
                    {
                      /** [ Create array of already exists artworks ] */
                      array_push($already_exists,'THL 1');
                    }
                    else
                    {
                      /** [ Invoking saveTescoOrder function using class instance ] */
                       $tesco_order     = $tesco_order_obj->saveTescoOrder($request,$template_id,$created_by);
                       
                       /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                        $tesco_order_id  = $tesco_order->id;
                        TescoTHL1OrdersQuantity::where('thl1_order_id',$tesco_order_id)
                              ->delete();
                        $templates_inserted = array('THL 1' => $tesco_order_id);
                        $i = 0;
                        foreach ($orms_tpn as $tpn) 
                        {
                          TescoTHL1OrdersQuantity::create([
                              'thl1_order_id'    => $tesco_order_id,
                              'orms_tpn'         => $tpn,
                              'itf_barcode'      => $itf_code[$i],
                              'cases_per_carton' => $cases_per_carton[$i],
                              'count'            => $count_THL1[$i],
                              'qr_code'          => $qr_code[$i]
                            ]);
                          $i++;
                        }

                       /** [ Return array of inserted id ] */
                       $last_insertions = array_merge($last_insertions,$templates_inserted);
                    }
                }
              }
            return response()->json(['error'=> true,'message' =>'success','last_insertions' => $last_insertions,'already_exists' => $already_exists],200);
        } catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
    /**
     * Save CE single template orders
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function saveCESingleOrders(Request $request)
    {
        try {
          /** [ Creating new object for SaveTescoOrders() class ] */
            $tesco_order_obj = new SaveTescoOrders();
            
            $orms_tpn         = explode(',',$request['orms_tpn']);
            $ean_code         = explode(',',$request['ean_code']);
            $case_size        = explode(',',$request['case_size']);
            $size             = explode(',',$request['size']);
            $templates        = $request['templates'];
            $last_insertions  = [];
            $already_exists   = [];
            $created_by       = $request['created_by'];
            $qr_code          = explode('|',$request['qr_code']);

            foreach ($templates as $template) 
            {
              $template_id      = $template['id'];
              if($template['template_name'] != "THL 3")
              {
                $count_TCL4       = explode(',',$request['count_TCL4']);
                $count_TSL3       = explode(',',$request['count_TSL3']);
                
                if($template['template_name'] == "TCL 4")
                {
                  /** [ Check if order already placed for this CE style ] */
                     $is_exists = TescoTCL4Orders::select('id')->Where('ce_style_ref','=',$request['ce_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                      if($is_exists)
                      {
                        /** [ Create array of already exists artworks ] */
                        array_push($already_exists,'TCL 4');
                      }
                      else
                      {
                        /** [ Invoking saveTescoOrder function using class instance ] */
                         $tesco_order     = $tesco_order_obj->saveTescoOrder($request,$template_id,$created_by);

                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                          $tesco_order_id  = $tesco_order->id;
                          TescoTCL4OrdersQuantity::where('tcl4_order_id',$tesco_order_id)
                            ->delete();
                          $templates_inserted = array('TCL 4' => $tesco_order_id);
                          $i = 0;
                          foreach ($orms_tpn as $tpn) 
                          {
                            TescoTCL4OrdersQuantity::create([
                                'tcl4_order_id'    => $tesco_order_id,
                                'orms_tpn'         => $tpn,
                                'ean_barcode'      => $ean_code[$i],
                                'size'             => $size[$i],
                                'case_size'        => $case_size[$i],
                                'count'            => $count_TCL4[$i],
                                'qr_code'          => $qr_code[$i]
                              ]);
                            $i++;
                          }

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                      }
                }
                else
                {
                  /** [ Check if order already placed for this CE style ] */
                  $is_exists = TescoTSL3Orders::select('id')->Where('ce_style_ref','=',$request['ce_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                    if($is_exists)
                    {
                      /** [ Create array of already exists artworks ] */
                      array_push($already_exists,'TSL 3');
                    }
                    else
                    {
                      /** [ Invoking saveTescoOrder function using class instance ] */
                       $tesco_order     = $tesco_order_obj->saveTescoOrder($request,$template_id,$created_by);
                       
                       /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                        $tesco_order_id  = $tesco_order->id;
                        TescoTSL3OrdersQuantity::where('tsl3_order_id',$tesco_order_id)
                          ->delete();
                        $templates_inserted = array('TSL 3' => $tesco_order_id);
                        $i = 0;
                        foreach ($orms_tpn as $tpn) 
                        {
                          TescoTSL3OrdersQuantity::create([
                              'tsl3_order_id'    => $tesco_order_id,
                              'orms_tpn'         => $tpn,
                              'ean_barcode'      => $ean_code[$i],
                              'size'             => $size[$i],
                              'case_size'        => $case_size[$i],
                              'count'            => $count_TSL3[$i],
                              'qr_code'          => $qr_code[$i]
                            ]);
                          $i++;
                        }

                       /** [ Return array of inserted id ] */
                       $last_insertions = array_merge($last_insertions,$templates_inserted);
                    }
                }
              }
              else
              {
                 $count_THL3       = explode(',',$request['count_THL3']);

                 /** [ Check if order already placed for this CE style ] */
                 $is_exists = TescoTHL3Orders::select('id')->Where('ce_style_ref','=',$request['ce_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                  if($is_exists)
                  {
                    /** [ Create array of already exists artworks ] */
                    array_push($already_exists,'THL 3');
                  }
                  else
                  {
                    /** [ Invoking saveTescoOrder function using class instance ] */
                     $tesco_order     = $tesco_order_obj->saveTescoOrder($request,$template_id,$created_by);
                     
                     /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                     $tesco_order_id  = $tesco_order->id;
                     TescoTHL3OrdersQuantity::where('thl3_order_id',$tesco_order_id)
                        ->delete();
                     $templates_inserted = array('THL 3' => $tesco_order_id);
                     $i = 0;
                     foreach ($orms_tpn as $tpn) 
                     {
                       TescoTHL3OrdersQuantity::create([
                            'thl3_order_id'    => $tesco_order_id,
                            'orms_tpn'         => $tpn,
                            'ean_barcode'      => $ean_code[$i],
                            'size'             => $size[$i],
                            'case_size'        => $case_size[$i],
                            'count'            => $count_THL3[$i],
                            'qr_code'          => $qr_code[$i]
                          ]);
                       $i++;
                     }
                     
                     /** [ Return array of inserted id ] */
                     $last_insertions = array_merge($last_insertions,$templates_inserted);
                  }
              }
            }
            
           return response()->json(['error'=> false,'message' =>'success','last_insertions' => $last_insertions,'already_exists' => $already_exists],200);
        } catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
    /**
     * Save UK ratio template orders
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function saveUKRatioOrders(Request $request)
    {
        try {
            
          /** [ Creating new object for SaveTescoOrders() class ] */
            $tesco_order_obj = new SaveTescoOrders();
            
            $tpnd             = explode(',',$request['tpnd']);
            $itf_code         = explode(',',$request['itf_code']);
            $units_per_carton = explode(',',$request['units_per_carton']);
            $templates        = $request['templates'];
            $last_insertions  = [];
            $already_exists   = [];
            $created_by       = $request['created_by'];
            foreach ($templates as $template) 
            {
              $template_id      = $template['id'];
              if($template['template_name'] != "THL 1")
              {
                $count_TCL1       = explode(',',$request['count_TCL1']);
                $count_TCL2       = explode(',',$request['count_TCL2']);
                $count_TSL1       = explode(',',$request['count_TSL1']);

                if($template['template_name'] == "TCL 1")
                {
                  /** [ Check if order already placed for this UK style ] */
                  $is_exists = TescoTCL1Orders::select('id')->where('uk_style_ref','=',$request['uk_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                  if($is_exists)
                  {
                    /** [ Create array of already exists artworks ] */
                    array_push($already_exists,'TCL 1');
                  }
                  else
                  {
                     /** [ Invoking saveTescoOrder function using class instance ] */
                       $tesco_order     = $tesco_order_obj->saveTescoOrder($request,$template_id,$created_by);
                       
                       /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                        $tesco_order_id  = $tesco_order->id;
                        TescoTCL1OrdersQuantity::where('tcl1_order_id',$tesco_order_id)
                          ->delete();
                        $templates_inserted = array('TCL 1' => $tesco_order_id);
                        $i = 0;
                        foreach ($tpnd as $value) 
                        {
                          TescoTCL1OrdersQuantity::create([
                              'tcl1_order_id'    => $tesco_order_id,
                              'tpnd'             => $value,
                              'itf_barcode'      => $itf_code[$i],
                              'units_per_carton' => $units_per_carton[$i],
                              'count'            => $count_TCL1[$i]
                            ]);
                          $i++;
                        }

                       /** [ Return array of inserted id ] */
                       $last_insertions = array_merge($last_insertions,$templates_inserted);
                  }   
                }
                else if($template['template_name'] == "TCL 2")
                {
                  /** [ Check if order already placed for this UK style ] */
                  $is_exists = TescoTCL2Orders::select('id')->where('uk_style_ref','=',$request['uk_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                  if($is_exists)
                  {
                    /** [ Create array of already exists artworks ] */
                    array_push($already_exists,'TCL 2');
                  }
                  else
                  {
                      /** [ Invoking saveTescoOrder function using class instance ] */
                       $tesco_order     = $tesco_order_obj->saveTescoOrder($request,$template_id,$created_by);
                       
                       /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                       $tesco_order_id  = $tesco_order->id;
                       TescoTCL2OrdersQuantity::where('tcl2_order_id',$tesco_order_id)
                          ->delete();
                       $templates_inserted = array('TCL 2' => $tesco_order_id);
                       $i = 0;
                       foreach ($tpnd as $value) 
                       {
                         TescoTCL2OrdersQuantity::create([
                              'tcl2_order_id'    => $tesco_order_id,
                              'tpnd'             => $value,
                              'itf_barcode'      => $itf_code[$i],
                              'units_per_carton' => $units_per_carton[$i],
                              'count'            => $count_TCL2[$i]
                            ]);
                         $i++;
                       }

                       /** [ Return array of inserted id ] */
                       $last_insertions = array_merge($last_insertions,$templates_inserted);
                  }      
                }
                else
                {
                  /** [ Check if order already placed for this UK style ] */
                  $is_exists = TescoTSL1Orders::select('id')->where('uk_style_ref','=',$request['uk_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                  if($is_exists)
                  {
                    /** [ Create array of already exists artworks ] */
                    array_push($already_exists,'TSL 1');
                  }
                  else
                  {
                    /** [ Invoking saveTescoOrder function using class instance ] */
                     $tesco_order     = $tesco_order_obj->saveTescoOrder($request,$template_id,$created_by);
                     
                     /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                     $tesco_order_id  = $tesco_order->id;
                     TescoTSL1OrdersQuantity::where('tsl1_order_id',$tesco_order_id)
                        ->delete();
                     $templates_inserted = array('TSL 1' => $tesco_order_id);
                     $i = 0;
                     foreach ($tpnd as $value) 
                     {
                       TescoTSL1OrdersQuantity::create([
                            'tsl1_order_id'    => $tesco_order_id,
                            'tpnd'             => $value,
                            'itf_barcode'      => $itf_code[$i],
                            'units_per_carton' => $units_per_carton[$i],
                            'count'            => $count_TSL1[$i]
                          ]);
                       $i++;
                     }
                     
                     /** [ Return array of inserted id ] */
                     $last_insertions = array_merge($last_insertions,$templates_inserted);
                  }  
                }
              }
              else
              {
                 $count_THL1       = explode(',',$request['count_THL1']);

                 /** [ Check if order already placed for this UK style ] */
                  $is_exists = TescoTHL1Orders::select('id')->where('uk_style_ref','=',$request['uk_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                  if($is_exists)
                  {
                    /** [ Create array of already exists artworks ] */
                    array_push($already_exists,'THL 1');
                  }
                  else
                  {
                    /** [ Invoking saveTescoOrder function using class instance ] */
                     $tesco_order     = $tesco_order_obj->saveTescoOrder($request,$template_id,$created_by);
                     
                       /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                        $tesco_order_id  = $tesco_order->id;
                        TescoTHL1OrdersQuantity::where('thl1_order_id',$tesco_order_id)
                          ->delete();
                        $templates_inserted = array('THL 1' => $tesco_order_id);
                        $i = 0;
                        foreach ($tpnd as $value) 
                        {
                          TescoTHL1OrdersQuantity::create([
                              'thl1_order_id'    => $tesco_order_id,
                              'tpnd'             => $value,
                              'itf_barcode'      => $itf_code[$i],
                              'units_per_carton' => $units_per_carton[$i],
                              'count'            => $count_THL1[$i]
                            ]);
                          $i++;
                        }

                       /** [ Return array of inserted id ] */
                       $last_insertions = array_merge($last_insertions,$templates_inserted);
                  }
              }
            }
            
           return response()->json(['error'=> false,'message' =>'success','last_insertions' => $last_insertions,'already_exists' => $already_exists],200);
        } catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
    /**
     * Save UK single template orders
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function saveUKSingleOrders(Request $request)
    {
        try {
            /** [ Creating new object for SaveTescoOrders() class ] */
            $tesco_order_obj = new SaveTescoOrders();
            
            $tpnd             = explode(',',$request['tpnd']);
            $ean_code         = explode(',',$request['ean_code']);
            $case_size        = explode(',',$request['case_size']);
            $size             = explode(',',$request['size']);
            $templates        = $request['templates'];
            $last_insertions  = [];
            $already_exists   = [];
            $created_by       = $request['created_by'];
            foreach ($templates as $template) 
            {
              $template_id      = $template['id'];
              if($template['template_name'] != "THL 3")
              {
                $count_TCL4       = explode(',',$request['count_TCL4']);
                $count_TSL3       = explode(',',$request['count_TSL3']);
                
                if($template['template_name'] == "TCL 4")
                {
                  /** [ Check if order already placed for this UK style ] */
                  $is_exists = TescoTCL4Orders::select('id')->where('uk_style_ref','=',$request['uk_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                  if($is_exists)
                  {
                    /** [ Create array of already exists artworks ] */
                    array_push($already_exists,'TCL 4');
                  }
                  else
                  {
                    /** [ Invoking saveTescoOrder function using class instance ] */
                     $tesco_order     = $tesco_order_obj->saveTescoOrder($request,$template_id,$created_by);
                     
                     /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                      $tesco_order_id  = $tesco_order->id;
                      TescoTCL4OrdersQuantity::where('tcl4_order_id',$tesco_order_id)
                        ->delete();
                      $templates_inserted = array('TCL 4' => $tesco_order_id);
                      $i = 0;
                      foreach ($tpnd as $value) 
                      {
                        TescoTCL4OrdersQuantity::create([
                            'tcl4_order_id'    => $tesco_order_id,
                            'tpnd'             => $value,
                            'ean_barcode'      => $ean_code[$i],
                            'size'             => $size[$i],
                            'case_size'        => $case_size[$i],
                            'count'            => $count_TCL4[$i]
                          ]);
                        $i++;
                      }

                     /** [ Return array of inserted id ] */
                     $last_insertions = array_merge($last_insertions,$templates_inserted);
                  }      
                }
                else
                {
                  /** [ Check if order already placed for this UK style ] */
                  $is_exists = TescoTSL3Orders::select('id')->where('uk_style_ref','=',$request['uk_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                  if($is_exists)
                  {
                    /** [ Create array of already exists artworks ] */
                    array_push($already_exists,'TSL 3');
                  }
                  else
                  {
                    /** [ Invoking saveTescoOrder function using class instance ] */
                     $tesco_order     = $tesco_order_obj->saveTescoOrder($request,$template_id,$created_by);
                     
                     /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                      $tesco_order_id  = $tesco_order->id;
                      TescoTSL3OrdersQuantity::where('tsl3_order_id',$tesco_order_id)
                        ->delete();
                      $templates_inserted = array('TSL 3' => $tesco_order_id);
                      $i = 0;
                      foreach ($tpnd as $value) 
                      {
                        TescoTSL3OrdersQuantity::create([
                            'tsl3_order_id'    => $tesco_order_id,
                            'tpnd'             => $value,
                            'ean_barcode'      => $ean_code[$i],
                            'size'             => $size[$i],
                            'case_size'        => $case_size[$i],
                            'count'            => $count_TSL3[$i]
                          ]);
                        $i++;
                      }

                     /** [ Return array of inserted id ] */
                     $last_insertions = array_merge($last_insertions,$templates_inserted); 
                  }  
                }
              }
              else
              {
                 $count_THL3       = explode(',',$request['count_THL3']);

                 /** [ Check if order already placed for this UK style ] */
                  $is_exists = TescoTHL3Orders::select('id')->where('uk_style_ref','=',$request['uk_style'])->where('po_number','=',$request['po_number'])->whereNotIn('order_status', ['Saved', 'Order Cancelled'])->first();
                  if($is_exists)
                  {
                    /** [ Create array of already exists artworks ] */
                    array_push($already_exists,'THL 3');
                  }
                  else
                  {
                    /** [ Invoking saveTescoOrder function using class instance ] */
                     $tesco_order     = $tesco_order_obj->saveTescoOrder($request,$template_id,$created_by);
                     
                     /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                      $tesco_order_id  = $tesco_order->id;
                      TescoTHL3OrdersQuantity::where('thl3_order_id',$tesco_order_id)
                        ->delete();
                      $templates_inserted = array('THL 3' => $tesco_order_id);
                      $i = 0;
                      foreach ($tpnd as $value) 
                      {
                        TescoTHL3OrdersQuantity::create([
                            'thl3_order_id'    => $tesco_order_id,
                            'tpnd'             => $value,
                            'ean_barcode'      => $ean_code[$i],
                            'size'             => $size[$i],
                            'case_size'        => $case_size[$i],
                            'count'            => $count_THL3[$i]
                          ]);
                        $i++;
                      }

                     /** [ Return array of inserted id ] */
                     $last_insertions = array_merge($last_insertions,$templates_inserted);
                  }
              }
            }
            
           return response()->json(['error'=> false,'message' =>'success','last_insertions' => $last_insertions,'already_exists' => $already_exists],200);

        } catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }

    /**
     * Method to create Tesco Sticker Orders using PO upload.
     * @param  Request $request Formdata
     * @return [type] view
     */
    public function createStickerOrders(Request $request) {
      try {
        if($request->zone === 'UK') {
          //handle the order using createOrderForUK method
            return $this->createOrderForUK($request);
        } else if($request->zone === 'CE') {
          //handle the order using createOrderForCE method
            return $this->createOrderForCE($request);
        } else {
           return response()->json(['error' => 'Unknown Template Zone'], 400);
        }
      } catch (\Exception $e) {
        return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
    }

    /**
     * Method to create tesco order for UK zone from purchase order using Tabula-Java
     * @return \Illuminate\Http\Response
     */
    public function createOrderForUK($request) {
      try {
        //Check the file has been uploaded or not
        if($request->hasFile('purchase_order')) {
          $file = $request->file('purchase_order');
          $name = preg_replace("/[^A-Za-z0-9\_\-\.]/", '', $file->getClientOriginalName());
          $path = $file->move('storage/app/TESCO/purchaseOrder/UK', time().'_'.$name);
          //Read the Purchase Order Series
          $po_number = $this->getPOSeries($path);
          $po_series = explode('-', $po_number);
          if(count($po_series) < 2) {
            return response()->json(['error' => 'Unknown Purchase Order for UK'], 400);
          }
          $po_series = $po_series[0];
          //Check the type of PO
          $po_type = $this->checkPOType($path);
          $arr = explode(' ',trim($po_type));
          $po_type = strtolower($arr[0]);

          //Validate the Template and Purchase Order Series Number.
          switch ($request->template_name) {
            case 'TCL 1 , TCL 2 & TSL 1':
              if($po_type !== 'ratio' || $po_series !== '460') {
                return response()->json(['error' => 'Template and PO Mismatch', 'template' => 'TCL 1 , TCL 2 & TSL 1', 'po series' => $po_series, 'po type' => $po_type], 422);
              }
              break;
            case 'TCL 4 & TSL 3':
              if($po_type !== 'single' || $po_series !== '460') {
                return response()->json(['error' => 'Template and PO Mismatch', 'template' => 'TCL 4 & TSL 3', 'po series' => $po_series, 'po type' => $po_type], 422);
              }
              break;
            case 'THL 1':
              if($po_type !== 'ratio' || ($po_series !== '206' && $po_series !== '461')) {
                return response()->json(['error' => 'Template and PO Mismatch', 'template' => 'THL 1', 'po series' => $po_series, 'po type' => $po_type], 422);
              }
              break;
            case 'THL 3':
              if($po_type !== 'single' || ($po_series !== '206' && $po_series !== '461')) {
                return response()->json(['error' => 'Template and PO Mismatch', 'template' => 'THL 3', 'po series' => $po_series, 'po type' => $po_type], 422);
              }
              break;
            default:
              return response()->json(['error' => 'Unknown Template Name for UK'], 400);
              break;
          }
          
          switch ($po_type) {
            case 'single':
              $data = $this->cropPDF($path, 'single', 'UK');
              break;
            case 'ratio':
              //Check for Ratio Pack B
              $packB = $this->checkPackB($path , 'UK');
              $data  = $this->cropPDF($path, 'ratio', 'UK');
              if($packB) {
                $data['ratioPackB'] = $packB;
              }
              break;
            default:
              return response()->json(['error' => 'Unknown PO Type'], 400);
              break;
          }
          $data['supplier'] = $this->getSupplierInfo($path);  
          return response()->json(['details' =>$data, 'type' => $po_type, 'po_number' => $po_number], 200);
        } else {
          return response()->json(['error' => 'File not found'], 400);
        }
      } catch (\Exception $e) {
        return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
    }

        /**
     * Method to create tesco order for CE zone from purchase order using Tabula-Java
     * @return \Illuminate\Http\Response
     */
    public function createOrderForCE($request) {
      try {

          $output = array();
          $orms_data = array();
          $retek_data = array();
          $template_type = 'TCL';
          //Check the files has been uploaded or not
          if($request->hasFile('ce_orms_purchase_order') && $request->hasFile('ce_retek_purchase_order')) {
            //Move the orms file
            $orms = $request->file('ce_orms_purchase_order');
            $orms_filename = preg_replace("/[^A-Za-z0-9\_\-\.]/", '', $orms->getClientOriginalName());
            $orms_path = $orms->move('storage/app/TESCO/purchaseOrder/CE/ORMS', time().'_'.$orms_filename);

            //Move the retek file
            $retek = $request->file('ce_retek_purchase_order');
            $retek_filename = preg_replace("/[^A-Za-z0-9\_\-\.]/", '', $retek->getClientOriginalName());
            $retek_path = $retek->move('storage/app/TESCO/purchaseOrder/CE/RETEK', time().'_'.$retek_filename);

            //Parse ORMS File
            if(!$this->parseORMS($orms_path)) {
              return response()->json(['error' => 'Unrecognized ORMS file'], 400);
            }
            list($orms_no, $retek_no)  = $this->parseORMS($orms_path);
            $orms_data['order_number'] = trim($orms_no);
            $orms_data['retek_number'] = trim($retek_no);
            //Check the RETEK purchase order validation
            //Check the purchase order type whether it is Single or Ratio.
            $retek_data['retek_po_number'] = trim($this->getPOSeries($retek_path));
            //Compare RETEK PO number from both the sheets
            if($retek_data['retek_po_number'] !== $orms_data['retek_number']) {
              return response()->json(['error' => 'RETEK Purchase Order Number Mismatch'], 400);
            }
            $po_type   = $this->checkPOType($retek_path);
            $arr = explode(' ',trim($po_type));
            $retek_data['retek_po_type'] = strtolower($arr[0]);

            //Check for Packing Method to identify whether it is TCL or THL
            //If Packing Method is 'HANGING' then it is THL
            //If Packing Method is 'BOX' or something like that then it is TCL
            $isTHL = $this->checkPackingMethod($retek_path);
            if(gettype($isTHL) !== 'boolean') {
              return response()->json(['error' => $isTHL], 400);
            } else if($isTHL){
              $template_type = 'THL';
            }
            
            //Validate the Template and Packaging Method.
            switch ($request->template_name) {
              case 'TCL 1 , TCL 2 & TSL 1':
                if($retek_data['retek_po_type'] !== 'ratio' || $template_type !== 'TCL') {
                  return response()->json(['error' => 'Template and PO Mismatch', 'template' => 'TCL 1 , TCL 2 & TSL 1', 'template type' => $template_type, 'po type' => $retek_data['retek_po_type']], 422);
                }
                break;
              case 'TCL 4 & TSL 3':
                if($retek_data['retek_po_type'] !== 'single' || $template_type !== 'TCL') {
                  return response()->json(['error' => 'Template and PO Mismatch', 'template' => 'TCL 4 & TSL 3', 'template type' => $template_type, 'po type' => $retek_data['retek_po_type']], 422);
                }
                break;
              case 'THL 1':
                if($retek_data['retek_po_type'] !== 'ratio' || $template_type !== 'THL') {
                  return response()->json(['error' => 'Template and PO Mismatch', 'template' => 'THL 1', 'template type' => $template_type, 'po type' => $retek_data['retek_po_type']], 422);
                }
                break;
              case 'THL 3':
                if($retek_data['retek_po_type'] !== 'single' || $template_type !== 'THL') {
                  return response()->json(['error' => 'Template and PO Mismatch', 'template' => 'THL 3', 'template type' => $template_type, 'po type' => $retek_data['retek_po_type']], 422);
                }
                break;
              default:
                return response()->json(['error' => 'Unknown Template Name for CE'], 400);
                break;
            }

            switch ($retek_data['retek_po_type']) {
              case 'single':
                $orms_table = $this->parseORMSTable($orms_path, 'single');
                $orms_data['order_table']  = $orms_table;
                $retek_single_data = $this->cropPDF($retek_path, 'single', 'CE');
                $retek_data['info'] = $retek_single_data['info'];
                $retek_data['table'] = $retek_single_data['table'];
                break;
              case 'ratio':
                $orms_table = $this->parseORMSTable($orms_path, 'ratio');
                $orms_data['order_table']  = $orms_table;
                //$packB = $this->checkPackB($retek_path, 'CE');//Check for Ratio Pack B
                $ratio_table          = $this->extractCERatioTable($retek_path, 'CE');//Check for Ratio Pack B
                // $retek_ratio_data     = $this->cropPDF($retek_path, 'ratio', 'CE');
                $retek_ratio_data     = $this->cropVerticalPDF($retek_path, 'ratio', 'CE');
                $retek_data['info']   = json_encode($retek_ratio_data['info']);
                //$retek_data['table']  = $retek_ratio_data['table'];
                $retek_data['ratioPackA']  = json_encode($ratio_table[0]);
                //Parse Ratio Pack B
                //Not Required for CE
                // if(count($ratio_table) > 1) {
                //   $retek_data['ratioPackB'] = json_encode($ratio_table[1]);
                // }
                break;
              default:
                return response()->json(['error' => 'Unknown PO Type'], 400);
                break;
            }

            $retek_data['supplier'] = $this->getSupplierInfo($retek_path);
            $output['orms'] = $orms_data;
            $output['retek'] = $retek_data;
            return response()->json(['details' =>$output], 200);
          
          } else {
            return response()->json(['error' => 'File not found'], 400);
          }
        } catch (\Exception $e) {
          return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }

    /**
     * Method to parse the CE type purchase order
     * @param  [string] $file PDF file path.
     * @return [array]  ORMS PO parsed data
     */
    public function parseORMS($file) {
      //Read the tabula configuration from config/tabula.php.
        $tabula       = Config::get('tabula.tabula_jar');
        $orms_number  = Config::get('tabula.orms_number');
        $ce_orms_no   = json_decode(shell_exec('java -jar '.$tabula.$orms_number.$file), true);
        if (strpos($ce_orms_no[0]['data'][0][0]['text'], 'ORMS ORDER NUMBER:') === false) {
            return false;
        }
        $orms_po_number   = $ce_orms_no[0]['data'][0][0]['text'];
        $retek_po_number  = $ce_orms_no[0]['data'][1][0]['text'];
        $ce_orms_no       = explode(':', $orms_po_number);
        $ce_retek_no      = explode(':', $retek_po_number);
        return array($ce_orms_no[1], $ce_retek_no[1]);
    }

    /**
     * Method to parse the CE type purchase order
     * @param  [string] $file PDF file path.
     * @return [array]  ORMS PO parsed data
     */
    public function parseORMSTable($file, $type) {
      //Read the tabula configuration from config/tabula.php.
        $tabula       = Config::get('tabula.tabula_jar');
        $orms_table   = Config::get('tabula.orms_table_'.$type);
        $ce_orms_table    = json_decode(shell_exec('java -jar '.$tabula.$orms_table.$file), true);
        return $ce_orms_table[0]['data'];
    }

    /**
     * Method to parse the packing method to determine is it THL or TCL
     * @param  [string] $file PDF file path.
     * @return [array]  ORMS PO parsed data
     */
    public function checkPackingMethod($file) {
      //Read the tabula configuration from config/tabula.php.
        $tabula           = Config::get('tabula.tabula_jar');
        $packing_method   = Config::get('tabula.packing_method');
        $info_col_3      = json_decode(shell_exec('java -jar '.$tabula.$packing_method.$file), true);
        foreach ($info_col_3[0]['data'] as $key => $value) {
          foreach ($value as $i => $val) {
            if(strpos($val['text'], 'Packaging Method :') !== false) {
              $method = explode(':', $value[1]['text']);
              $method = (count($method) === 1) ? $value[$i+1]['text'] : trim($method[1]);
              if($method === 'HANGING') {
                return true;
              } else {
                return false;
              }
            }
          }

        }
        return 'Unable to process the packing method';
    }


    /**
     * Method to find the type of P.O uploaded.
     * @param  [string] $file PDF file path.
     * @return [string]       Type of the PDF
     */
    public function checkPOType($file) {
      //Read the tabula configuration from config/tabula.php.
        $tabula  = Config::get('tabula.tabula_jar');
        $po_type = Config::get('tabula.po_type');
        $type    = shell_exec('java -jar '.$tabula.$po_type.$file);
        $type    = json_decode($type, true);
        $type    = $type[0]['data'][0][0]['text'];
        return $type;
    }

    /**
     * Method to find the series of P.O uploaded.
     * @param  [string] $file PDF file path.
     * @return [string]       Type of the PDF
     */
    public function getPOSeries($file) {
      //Read the tabula configuration from config/tabula.php.
        $tabula    = Config::get('tabula.tabula_jar');
        $po_series = Config::get('tabula.po_series');
        $series    = shell_exec('java -jar '.$tabula.$po_series.$file);
        $series    = json_decode($series, true);
        $series    = $series[0]['data'][0][0]['text'];
        return $series;
    }

    /**
     * Method to get the appropriate dimensions from the PDF
     * @param  [path] $file File to be cropped
     * @param  [str]  $type Purchase Order type
     * @param  [str]  $zone Template Zone CE/UK
     * @return [str]       JSON extracted string
     */
    public function cropPDF($file, $type, $zone) {
      //Read the tabula configuration from config/tabula.php.
      $tabula      = Config::get('tabula.tabula_jar');
      $quantity    = Config::get('tabula.'.$zone.'_'.$type.'_quantity');
      $information = Config::get('tabula.'.$zone.'_'.$type.'_info');
      //Execute tabula method to extract the quantity table.
      $info    = shell_exec('java -jar '.$tabula.$information.$file);
      $table   = shell_exec('java -jar '.$tabula.$quantity.$file);
      return ['info' => $info, 'table' => $table];
    }

    /**
     * Method to get the info section from the PDF
     * @param  [path] $file File to be cropped
     * @param  [str]  $type Purchase Order type
     * @param  [str]  $zone Template Zone CE/UK
     * @return [str]  JSON extracted string
     */
    public function cropVerticalPDF($file, $type, $zone) {
      //Read the tabula configuration from config/tabula.php.
      $tabula      = Config::get('tabula.tabula_jar');
      $column_1    = Config::get('tabula.'.$zone.'_'.$type.'_info_col_1');
      $column_2    = Config::get('tabula.'.$zone.'_'.$type.'_info_col_2');
      //Execute tabula method to extract the quantity table.
      $info1    = shell_exec('java -jar '.$tabula.$column_1.$file);
      $info2    = shell_exec('java -jar '.$tabula.$column_2.$file);
      $info1    = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $info1), true);
      $info2    = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $info2), true);
      foreach ($info2[0]['data'] as $key => $value) {
        if($value[0]['text'] === 'Season Code :') {
            array_push($info1[0]['data'], $value);
        }
      }
      return ['info' => $info1];
    }

    /**
     * Method to check Ratio Pack B exist or not
     * @param  [path] $file File to be cropped
     * @return [str]       JSON extracted string
     */
    public function checkPackB($file, $zone) {
      //Read the tabula configuration from config/tabula.php.
      $tabula      = Config::get('tabula.tabula_jar');
      $check_packB = Config::get('tabula.'.$zone.'_check_packB');
      //Execute tabula method to extract the quantity table.
      $pack_b = shell_exec('java -jar '.$tabula.$check_packB.$file);
      $pack_b = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $pack_b), true);
      foreach ($pack_b as $key => $value) {
        if(count($value['data']) < 2) {
          unset($pack_b[$key]);
        }
      }
      $pack_b = array_values($pack_b); // 'reindex' array
      if(count($pack_b) > 1) {
        //return the ratio pack B row
        return json_encode($pack_b[1]['data'][5]);
      } else {
        return false;
      }
    }

    /**
     * Method to extract Ratio table from Page 2 for CE
     * @param  [path] $file File to be cropped
     * @return [str]       JSON extracted string
     */
    public function extractCERatioTable($file, $zone) {
      //Read the tabula configuration from config/tabula.php.
      $tabula      = Config::get('tabula.tabula_jar');
      $check_packB = Config::get('tabula.'.$zone.'_check_packB');
      //Execute tabula method to extract the quantity table.
      $pack_b = shell_exec('java -jar '.$tabula.$check_packB.$file);
      $pack_b = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $pack_b), true);
      $ratio_arr = array();
      foreach ($pack_b as $key => $data_arr) {
        foreach ($data_arr['data'] as $i => $val) {
          if($val[0]['text'] === 'Ratio Pack Description') {
            array_push($ratio_arr, $data_arr['data'][$i+1]);
          }
        }
      }
      if(count($ratio_arr) > 0) {
        return $ratio_arr;
      } else {
        return false;
      }
    }

    /**
     * Method to get the supplier number and name.
     * @param  [path] $file File to be cropped
     * @return [str]       JSON extracted string
     */
    public function getSupplierInfo($file) {
      //Read the tabula configuration from config/tabula.php.
      $tabula      = Config::get('tabula.tabula_jar');
      $sup_info    = Config::get('tabula.supplier_info');
      //Execute tabula method to extract the seller section.
      $sup_info = shell_exec('java -jar '.$tabula.$sup_info.$file);
      return $sup_info;
    }

    /**
     * Method to  fetch the order details.
     * @param  Request $request Purchase Order Numbers
     * @return [array]   Array of artworks
     */
    public function viewOrders(Request $request) {
      $artwork = explode(',', $request->artwork);
      try {
        $result = '';
        $data = [];
        if($request->is_joint_order === 'false') {
            if (in_array("TCL 1", $artwork)) {
                  $tesco_tcl1_orders = DB::table('tesco_tcl1_orders')->where('po_number',$request->po_number)->where($request->zone.'_style_ref',$request->style_number)->where('order_flag','newOrder')->where('order_status','!=', 'Order Cancelled')->select('*', DB::raw("'".$this->tcl1."'".' as "TemplateName"'),DB::raw("'".$this->tcl1_type."'".' as "type"'))->first();
                if($tesco_tcl1_orders) {
                  $data['tesco_tcl1_orders'] = $tesco_tcl1_orders;
                  $data['tesco_tcl1_orders']->order_quantity = DB::table('tesco_tcl1_orders_quanty')->where('tcl1_order_id',$tesco_tcl1_orders->id)->select('*')->orderBy('tesco_tcl1_orders_quanty.id')->get();
                }
            }
            
            if (in_array("TCL 2", $artwork)) {
              $tesco_tcl2_orders = DB::table('tesco_tcl2_orders')->where('po_number',$request->po_number)->where($request->zone.'_style_ref',$request->style_number)->where('order_flag','newOrder')->where('order_status','!=', 'Order Cancelled')->select('*',DB::raw("'".$this->tcl2."'".' as "TemplateName"'),DB::raw("'".$this->tcl2_type."'".' as "type"'))->first();
              if($tesco_tcl2_orders) {
                $data['tesco_tcl2_orders'] = $tesco_tcl2_orders;
                $data['tesco_tcl2_orders']->order_quantity = DB::table('tesco_tcl2_orders_quanty')->where('tcl2_order_id',$tesco_tcl2_orders->id)->select('*')->orderBy('tesco_tcl2_orders_quanty.id')->get();
              }
            }
            
            if (in_array("TCL 4", $artwork)) {
              $tesco_tcl4_orders = DB::table('tesco_tcl4_orders')->where('po_number',$request->po_number)->where($request->zone.'_style_ref',$request->style_number)->where('order_flag','newOrder')->where('order_status','!=', 'Order Cancelled')->select('*',DB::raw("'".$this->tcl4."'".' as "TemplateName"'),DB::raw("'".$this->tcl4_type."'".' as "type"'))->first();
              if($tesco_tcl4_orders) {
                $data['tesco_tcl4_orders'] = $tesco_tcl4_orders;
                $data['tesco_tcl4_orders']->order_quantity = DB::table('tesco_tcl4_orders_quanty')->where('tcl4_order_id',$tesco_tcl4_orders->id)->select('*')->orderBy('tesco_tcl4_orders_quanty.id')->get();
              }
            }
            
            if (in_array("THL 1", $artwork)) {
              $tesco_thl1_orders = DB::table('tesco_thl1_orders')->where('po_number',$request->po_number)->where($request->zone.'_style_ref',$request->style_number)->where('order_flag','newOrder')->where('order_status','!=', 'Order Cancelled')->select('*',DB::raw("'".$this->thl1."'".' as "TemplateName"'),DB::raw("'".$this->thl1_type."'".' as "type"'))->first();
              if($tesco_thl1_orders) {
                $data['tesco_thl1_orders'] = $tesco_thl1_orders;
                $data['tesco_thl1_orders']->order_quantity = DB::table('tesco_thl1_orders_quanty')->where('thl1_order_id',$tesco_thl1_orders->id)->select('*')->orderBy('tesco_thl1_orders_quanty.id')->get();
              }
            }
            
            
            if (in_array("THL 3", $artwork)) {
              $tesco_thl3_orders = DB::table('tesco_thl3_orders')->where('po_number',$request->po_number)->where($request->zone.'_style_ref',$request->style_number)->where('order_flag','newOrder')->where('order_status','!=', 'Order Cancelled')->select('*',DB::raw("'".$this->thl3."'".' as "TemplateName"'),DB::raw("'".$this->thl3_type."'".' as "type"'))->first();
              if($tesco_thl3_orders) {
                $data['tesco_thl3_orders'] = $tesco_thl3_orders;
                $data['tesco_thl3_orders']->order_quantity = DB::table('tesco_thl3_orders_quanty')->where('thl3_order_id',$tesco_thl3_orders->id)->select('*')->orderBy('tesco_thl3_orders_quanty.id')->get();
              }
            }
            
            if (in_array("TSL 1", $artwork)) {
              $tesco_tsl1_orders = DB::table('tesco_tsl1_orders')->where('po_number',$request->po_number)->where($request->zone.'_style_ref',$request->style_number)->where('order_flag','newOrder')->where('order_status','!=', 'Order Cancelled')->select('*',DB::raw("'".$this->tsl1."'".' as "TemplateName"'),DB::raw("'".$this->tsl1_type."'".' as "type"'))->first();
              if($tesco_tsl1_orders) {
                $data['tesco_tsl1_orders'] = $tesco_tsl1_orders;
                $data['tesco_tsl1_orders']->order_quantity = DB::table('tesco_tsl1_orders_quanty')->where('tsl1_order_id',$tesco_tsl1_orders->id)->select('*')->orderBy('tesco_tsl1_orders_quanty.id')->get();
              }
            }
            
            if (in_array("TSL 3", $artwork)) {
              $tesco_tsl3_orders = DB::table('tesco_tsl3_orders')->where('po_number',$request->po_number)->where($request->zone.'_style_ref',$request->style_number)->where('order_flag','newOrder')->where('order_status','!=', 'Order Cancelled')->select('*',DB::raw("'".$this->tsl3."'".' as "TemplateName"'),DB::raw("'".$this->tsl3_type."'".' as "type"'))->first();
              if($tesco_tsl3_orders) {
                $data['tesco_tsl3_orders'] = $tesco_tsl3_orders;
                $data['tesco_tsl3_orders']->order_quantity = DB::table('tesco_tsl3_orders_quanty')->where('tsl3_order_id',$tesco_tsl3_orders->id)->select('*')->orderBy('tesco_tsl3_orders_quanty.id')->get();
              }
            }

            if (in_array("TSL 2", $artwork)) {
              $tesco_tsl2_orders = DB::table('tesco_tsl2_orders')->where('po_number',$request->po_number)->where($request->zone.'_style_ref',$request->style_number)->where('order_flag','newOrder')->where('order_status','!=', 'Order Cancelled')->select('*',DB::raw("'".$this->tsl2."'".' as "TemplateName"'),DB::raw("'".$this->tsl2_type."'".' as "type"'))->first();
              if($tesco_tsl2_orders) {
                $data['tesco_tsl2_orders'] = $tesco_tsl2_orders;
                $data['tesco_tsl2_orders']->order_quantity = DB::table('tesco_tsl2_orders_quanty')->where('tsl2_order_id',$tesco_tsl2_orders->id)->select('*')->orderBy('tesco_tsl2_orders_quanty.id')->get();
              }
            }

            if (in_array("TCL 5", $artwork)) {
              $tesco_tcl5_orders = DB::table('tesco_tcl5_orders')->where('po_number',$request->po_number)->where($request->zone.'_style_ref',$request->style_number)->where('order_flag','newOrder')->where('order_status','!=', 'Order Cancelled')->select('*',DB::raw("'".$this->tcl5."'".' as "TemplateName"'),DB::raw("'".$this->tcl5_type."'".' as "type"'))->first();
              if($tesco_tcl5_orders) {
                $data['tesco_tcl5_orders'] = $tesco_tcl5_orders;
                $data['tesco_tcl5_orders']->order_quantity = DB::table('tesco_tcl5_orders_quanty')->where('tcl5_order_id',$tesco_tcl5_orders->id)->select('*')->orderBy('tesco_tcl5_orders_quanty.id')->get();
              }
            }
        } else {

          $data['order_details']  = TescoWashcareOrders::with([
            'quantities',
            'garment_components',
            'item_master'
          ])
          ->where([
            'tesco_washcare_orders.id' => $request->orderid
          ])
          ->first();
            
        } 
        $result = response()->json(['error'=> false,'result' => $data],200); 
      } catch (\Exception $e) {
         $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
      return $result;
    }

    /**
   * Print Preview For Stickers
   *
   * @return pdf
   */
  
    public function printDivTesco(Request $request)
    {
        
        //       // Custom Header
        //      PDF::setHeaderCallback(function($pdf) {
        //               $image_file= K_PATH_IMAGES.'logo_example.jpg';
        //               $pdf->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        //              // Set font
        //              $pdf->SetFont('frankgothcnbt');
        //              // Title
        //              $pdf->Cell(0, 15, 'Something new right here!!!', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        //              $html = '<span color="red">red</span> <span color="green">green</span> <span color="blue">blue</span><br /><span color="red">red</span> <span color="green">green</span> <span color="blue">blue</span>';
        //              $pdf->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 0, true, 'L', true);
        //
        //      });
        //
        //      // Custom Footer
        //      PDF::setFooterCallback(function($pdf) {
        //
        //              // Position at 15 mm from bottom
        //              $pdf->SetY(-15);
        //              // Set font
        //              $pdf->SetFont('frankgothcnbt');
        //              // Page number
        //              $pdf->Cell(0, 10, 'Page '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        //
        //      });
        
    $name=$request->name;
        $svg=urldecode($request->svg);
        define ('PDF_FONT_NAME_MAIN', 'frankgothcnbt');
        define ('PDF_FONT_NAME_DATA', 'frankgothcnbt');
        PDF::setImageScale(1);
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
        PDF::SetFont('frankgothcnbt');
        PDF::AddPage();
        //PDF::RoundedRect(10, 10, 150, 100, 0, '0000');
        PDF::ImageSVG('@' . $svg, $x=12.5, $y=12.5, $w=127, $h=80, $link='', $align='', $palign='', $border=1, $fitonpage=false);
        PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
        print_r(url('/storage/app/template/'.$name.'.pdf'));
    } 
  
  /**
   * Print Preview For Stickers
   *
   * @return pdf
   */
  
    public function printDivTescoSmall(Request $request)
    {
    $name=$request->name;
        $svg=urldecode($request->svg);
        define ('PDF_FONT_NAME_MAIN', 'frankgothcnbt');
        define ('PDF_FONT_NAME_DATA', 'frankgothcnbt');
        PDF::setImageScale(1);
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
        PDF::SetFont('frankgothcnbt');
        PDF::AddPage();
        //PDF::RoundedRect(10, 10, 150, 100, 0, '0000');
        PDF::ImageSVG('@' . $svg, $x=12.5, $y=12.5, $w=40, $h=30, $link='', $align='', $palign='', $border=1, $fitonpage=false);
        PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
        print_r(url('/storage/app/template/'.$name.'.pdf'));
    } 
  
  /**
     * This Function is used to Show the style number in reorder page.
     *
     * @return json value
     */
  public function reOrder(Request $request)
    {
        $data['tesco_tcl1_orders'] = DB::table('tesco_tcl1_orders')->select('*')->get();
        $data['tesco_tcl2_orders'] = DB::table('tesco_tcl2_orders')->select('*')->get();
        $data['tesco_tcl4_orders'] = DB::table('tesco_tcl4_orders')->select('*')->get();
        $data['tesco_thl1_orders'] = DB::table('tesco_thl1_orders')->select('*')->get();
        $data['tesco_thl3_orders'] = DB::table('tesco_thl3_orders')->select('*')->get();
        $data['tesco_tsl1_orders'] = DB::table('tesco_tsl1_orders')->select('*')->get();
        $data['tesco_tsl3_orders'] = DB::table('tesco_tsl3_orders')->select('*')->get();
        echo json_encode($data);
    }
  /**
     * This Function is used to list all the artwork for particular style number.
     *
     * @return json value
     */
  public function reOrderArtwork(Request $request)
    {
    $style=$request->style;
        $data['tesco_tcl1_orders'] = DB::table('tesco_tcl1_orders')->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->select('*',DB::raw("'".$this->tcl1."'".' as "TemplateName"'),DB::raw("'".$this->tcl1_type."'".' as "type"'))->groupBy('tesco_tcl1_orders.uk_style_ref')->groupBy('tesco_tcl1_orders.ce_style_ref')->groupBy('tesco_tcl1_orders.zone')->groupBy('tesco_tcl1_orders.po_number')->get();
      
      
    $data['tesco_tcl2_orders'] = DB::table('tesco_tcl2_orders')->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->select('*',DB::raw("'".$this->tcl2."'".' as "TemplateName"'),DB::raw("'".$this->tcl2_type."'".' as "type"'))->groupBy('tesco_tcl2_orders.uk_style_ref')->groupBy('tesco_tcl2_orders.ce_style_ref')->groupBy('tesco_tcl2_orders.zone')->groupBy('tesco_tcl2_orders.po_number')->get();
      
      
    $data['tesco_tcl4_orders'] = DB::table('tesco_tcl4_orders')->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->select('*',DB::raw("'".$this->tcl4."'".' as "TemplateName"'),DB::raw("'".$this->tcl4_type."'".' as "type"'))->groupBy('tesco_tcl4_orders.uk_style_ref')->groupBy('tesco_tcl4_orders.ce_style_ref')->groupBy('tesco_tcl4_orders.zone')->groupBy('tesco_tcl4_orders.po_number')->get();
      
      
    $data['tesco_thl1_orders'] = DB::table('tesco_thl1_orders')->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->select('*',DB::raw("'".$this->thl1."'".' as "TemplateName"'),DB::raw("'".$this->thl1_type."'".' as "type"'))->groupBy('tesco_thl1_orders.uk_style_ref')->groupBy('tesco_thl1_orders.ce_style_ref')->groupBy('tesco_thl1_orders.zone')->groupBy('tesco_thl1_orders.po_number')->get();
      
      
    $data['tesco_thl3_orders'] = DB::table('tesco_thl3_orders')->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->select('*',DB::raw("'".$this->thl3."'".' as "TemplateName"'),DB::raw("'".$this->thl3_type."'".' as "type"'))->groupBy('tesco_thl3_orders.uk_style_ref')->groupBy('tesco_thl3_orders.ce_style_ref')->groupBy('tesco_thl3_orders.zone')->groupBy('tesco_thl3_orders.po_number')->get();
      
      
    $data['tesco_tsl1_orders'] = DB::table('tesco_tsl1_orders')->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->select('*',DB::raw("'".$this->tsl1."'".' as "TemplateName"'),DB::raw("'".$this->tsl1_type."'".' as "type"'))->groupBy('tesco_tsl1_orders.uk_style_ref')->groupBy('tesco_tsl1_orders.ce_style_ref')->groupBy('tesco_tsl1_orders.zone')->groupBy('tesco_tsl1_orders.po_number')->get();
      
      
    $data['tesco_tsl3_orders'] = DB::table('tesco_tsl3_orders')->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->select('*',DB::raw("'".$this->tsl3."'".' as "TemplateName"'),DB::raw("'".$this->tsl3_type."'".' as "type"'))->groupBy('tesco_tsl3_orders.uk_style_ref')->groupBy('tesco_tsl3_orders.ce_style_ref')->groupBy('tesco_tsl3_orders.zone')->groupBy('tesco_tsl3_orders.po_number')->get();
      
     
    echo json_encode($data);
    }
    
    /**
     * This Function is used to list thl3,tcl4,tsl3 artwork for particular style number.
     *
     * @return json value
     */
    public function repeatOrderArtwork(Request $request)
    {
        $style=$request->style;

        $data['tesco_tcl4_orders'] = DB::table('tesco_tcl4_orders')->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->select('*',DB::raw("'".$this->tcl4."'".' as "TemplateName"'),DB::raw("'".$this->tcl4_type."'".' as "type"'))->groupBy('tesco_tcl4_orders.uk_style_ref')->groupBy('tesco_tcl4_orders.ce_style_ref')->groupBy('tesco_tcl4_orders.zone')->groupBy('tesco_tcl4_orders.po_number')->get();


        $data['tesco_thl3_orders'] = DB::table('tesco_thl3_orders')->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->select('*',DB::raw("'".$this->thl3."'".' as "TemplateName"'),DB::raw("'".$this->thl3_type."'".' as "type"'))->groupBy('tesco_thl3_orders.uk_style_ref')->groupBy('tesco_thl3_orders.ce_style_ref')->groupBy('tesco_thl3_orders.zone')->groupBy('tesco_thl3_orders.po_number')->get();



        $data['tesco_tsl3_orders'] = DB::table('tesco_tsl3_orders')->where('uk_style_ref',$style)->orWhere('ce_style_ref',$style)->select('*',DB::raw("'".$this->tsl3."'".' as "TemplateName"'),DB::raw("'".$this->tsl3_type."'".' as "type"'))->groupBy('tesco_tsl3_orders.uk_style_ref')->groupBy('tesco_tsl3_orders.ce_style_ref')->groupBy('tesco_tsl3_orders.zone')->groupBy('tesco_tsl3_orders.po_number')->get();


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
     
          echo json_encode($data);
    }
  /**
     * This Function is used to set the tesco reorder.
     *
     * @return json value
     */
  // public function setTescoReorder(Request $request)
  //   {
  //           $type         =   $request->type;
  //           $id           =   $request->id;
  //           $counts       =   explode(',',$request->counts);
  //           if($type == "tesco_tcl1_orders")
  //           {
  //               $tasks = TescoTCL1Orders::find($id);
  //               $newTasks = $tasks->replicate();
  //               $newTasks->save();
                
  //               $factory=DB::table('tesco_tcl1_orders')->join('customers','customers.id','=','tesco_tcl1_orders.tesco_supplier_id')->where('tesco_tcl1_orders.id',$id)->select('*')->get();
                
  //               $pi_required=$factory{0}->pi_required;
  //               if($pi_required == 1)
  //               {
  //                   $pi_status="PI Approval Pending";
  //                   if($factory{0}->po_required == 1)
  //                   {
  //                       $po_status="PO Pending";
  //                   }
  //                   else
  //                   {
  //                       $po_status="";
  //                   }

  //               }
  //               else
  //               {
  //                   $po_status="PO Pending";
  //                   $pi_status="";
  //               }
  //               $newTasks->po_status = $po_status;
  //               $newTasks->pi_status = $pi_status;
  //               $newTasks->purchase_order_no = "";
  //               $newTasks->po_file = "";
  //               $newTasks->order_flag = "reOrder";
  //               $newTasks->save();
                
  //               $data['tesco']=DB::table('tesco_tcl1_orders_quanty')->where('tcl1_order_id',$id)->select('*')->get();
  //               $i=0;
  //               foreach($data['tesco'] as $value)
  //               {
  //                   $tasks = TescoTCL1OrdersQuantity::find($value->id);
  //                   $newTask = $tasks->replicate();
  //                   $newTask->save();
  //                   $newTask->tcl1_order_id = $newTasks->id;
  //                   $newTask->count = $counts[$i];
  //                   $newTask->save();
  //                   $i++;
  //               }
  //           }
  //           if($type == "tesco_tcl2_orders")
  //           {
  //               $tasks = TescoTCL2Orders::find($id);
  //               $newTasks = $tasks->replicate();
  //               $newTasks->save();
                
  //               $factory=DB::table('tesco_tcl2_orders')->join('customers','customers.id','=','tesco_tcl2_orders.tesco_supplier_id')->where('tesco_tcl2_orders.id',$id)->select('*')->get();
                
  //               $pi_required=$factory{0}->pi_required;
  //               if($pi_required == 1)
  //               {
  //                   $pi_status="PI Approval Pending";
  //                   if($factory{0}->po_required == 1)
  //                   {
  //                       $po_status="PO Pending";
  //                   }
  //                   else
  //                   {
  //                       $po_status="";
  //                   }

  //               }
  //               else
  //               {
  //                   $po_status="PO Pending";
  //                   $pi_status="";
  //               }
  //               $newTasks->po_status = $po_status;
  //               $newTasks->pi_status = $pi_status;
  //               $newTasks->purchase_order_no = "";
  //               $newTasks->po_file = "";
  //               $newTasks->order_flag = "reOrder";
  //               $newTasks->save();
                
  //               $data['tesco']=DB::table('tesco_tcl2_orders_quanty')->where('tcl2_order_id',$id)->select('*')->get();
  //               $i=0;
  //               foreach($data['tesco'] as $value)
  //               {
  //                   $tasks = TescoTCL2OrdersQuantity::find($value->id);
  //                   $newTask = $tasks->replicate();
  //                   $newTask->save();
  //                   $newTask->tcl2_order_id = $newTasks->id;
  //                   $newTask->count = $counts[$i];
  //                   $newTask->save();
  //                   $i++;
  //               }
  //           }
  //           if($type == "tesco_tcl4_orders")
  //           {
  //               $tasks = TescoTCL4Orders::find($id);
  //               $newTasks = $tasks->replicate();
  //               $newTasks->save();
                
  //               $factory=DB::table('tesco_tcl4_orders')->join('customers','customers.id','=','tesco_tcl4_orders.tesco_supplier_id')->where('tesco_tcl4_orders.id',$id)->select('*')->get();
                
  //               $pi_required=$factory{0}->pi_required;
  //               if($pi_required == 1)
  //               {
  //                   $pi_status="PI Approval Pending";
  //                   if($factory{0}->po_required == 1)
  //                   {
  //                       $po_status="PO Pending";
  //                   }
  //                   else
  //                   {
  //                       $po_status="";
  //                   }

  //               }
  //               else
  //               {
  //                   $po_status="PO Pending";
  //                   $pi_status="";
  //               }
  //               $newTasks->po_status = $po_status;
  //               $newTasks->pi_status = $pi_status;
  //               $newTasks->purchase_order_no = "";
  //               $newTasks->po_file = "";
  //               $newTasks->order_flag = "reOrder";
  //               $newTasks->save();
                
  //               $data['tesco']=DB::table('tesco_tcl4_orders_quanty')->where('tcl4_order_id',$id)->select('*')->get();
  //               $i=0;
  //               foreach($data['tesco'] as $value)
  //               {
  //                   $tasks = TescoTCL4OrdersQuantity::find($value->id);
  //                   $newTask = $tasks->replicate();
  //                   $newTask->save();
  //                   $newTask->tcl4_order_id = $newTasks->id;
  //                   $newTask->count = $counts[$i];
  //                   $newTask->save();
  //                   $i++;
  //               }
  //           }
  //           if($type == "tesco_thl1_orders")
  //           {
  //               $tasks = TescoTHL1Orders::find($id);
  //               $newTasks = $tasks->replicate();
  //               $newTasks->save();
                
                
  //               $factory=DB::table('tesco_thl1_orders')->join('customers','customers.id','=','tesco_thl1_orders.tesco_supplier_id')->where('tesco_thl1_orders.id',$id)->select('*')->get();
                
  //               $pi_required=$factory{0}->pi_required;
  //               if($pi_required == 1)
  //               {
  //                   $pi_status="PI Approval Pending";
  //                   if($factory{0}->po_required == 1)
  //                   {
  //                       $po_status="PO Pending";
  //                   }
  //                   else
  //                   {
  //                       $po_status="";
  //                   }

  //               }
  //               else
  //               {
  //                   $po_status="PO Pending";
  //                   $pi_status="";
  //               }
  //               $newTasks->po_status = $po_status;
  //               $newTasks->pi_status = $pi_status;
  //               $newTasks->purchase_order_no = "";
  //               $newTasks->po_file = "";
  //               $newTasks->order_flag = "reOrder";
  //               $newTasks->save();
                
  //               $data['tesco']=DB::table('tesco_thl1_orders_quanty')->where('thl1_order_id',$id)->select('*')->get();
  //               $i=0;
  //               foreach($data['tesco'] as $value)
  //               {
  //                   $tasks = TescoTHL1OrdersQuantity::find($value->id);
  //                   $newTask = $tasks->replicate();
  //                   $newTask->save();
  //                   $newTask->thl1_order_id = $newTasks->id;
  //                   $newTask->count = $counts[$i];
  //                   $newTask->save();
  //                   $i++;
  //               }
  //           }
  //           if($type == "tesco_thl3_orders")
  //           {
  //               $tasks = TescoTHL3Orders::find($id);
  //               $newTasks = $tasks->replicate();
  //               $newTasks->save();
                
                
  //               $factory=DB::table('tesco_thl3_orders')->join('customers','customers.id','=','tesco_thl3_orders.tesco_supplier_id')->where('tesco_thl3_orders.id',$id)->select('*')->get();
                
  //               $pi_required=$factory{0}->pi_required;
  //               if($pi_required == 1)
  //               {
  //                   $pi_status="PI Approval Pending";
  //                   if($factory{0}->po_required == 1)
  //                   {
  //                       $po_status="PO Pending";
  //                   }
  //                   else
  //                   {
  //                       $po_status="";
  //                   }

  //               }
  //               else
  //               {
  //                   $po_status="PO Pending";
  //                   $pi_status="";
  //               }
  //               $newTasks->po_status = $po_status;
  //               $newTasks->pi_status = $pi_status;
  //               $newTasks->purchase_order_no = "";
  //               $newTasks->po_file = "";
  //               $newTasks->order_flag = "reOrder";
  //               $newTasks->save();
                
  //               $data['tesco']=DB::table('tesco_thl3_orders_quanty')->where('thl3_order_id',$id)->select('*')->get();
  //               $i=0;
  //               foreach($data['tesco'] as $value)
  //               {
  //                   $tasks = TescoTHL3OrdersQuantity::find($value->id);
  //                   $newTask = $tasks->replicate();
  //                   $newTask->save();
  //                   $newTask->thl3_order_id = $newTasks->id;
  //                   $newTask->count = $counts[$i];
  //                   $newTask->save();
  //                   $i++;
  //               }
  //           }
  //           if($type == "tesco_tsl1_orders")
  //           {
  //               $tasks = TescoTSL1Orders::find($id);
  //               $newTasks = $tasks->replicate();
  //               $newTasks->save();
                
                
  //               $factory=DB::table('tesco_tsl1_orders')->join('customers','customers.id','=','tesco_tsl1_orders.tesco_supplier_id')->where('tesco_tsl1_orders.id',$id)->select('*')->get();
                
  //               $pi_required=$factory{0}->pi_required;
  //               if($pi_required == 1)
  //               {
  //                   $pi_status="PI Approval Pending";
  //                   if($factory{0}->po_required == 1)
  //                   {
  //                       $po_status="PO Pending";
  //                   }
  //                   else
  //                   {
  //                       $po_status="";
  //                   }

  //               }
  //               else
  //               {
  //                   $po_status="PO Pending";
  //                   $pi_status="";
  //               }
  //               $newTasks->po_status = $po_status;
  //               $newTasks->pi_status = $pi_status;
  //               $newTasks->purchase_order_no = "";
  //               $newTasks->po_file = "";
  //               $newTasks->order_flag = "reOrder";
  //               $newTasks->save();
                
  //               $data['tesco']=DB::table('tesco_tsl1_orders_quanty')->where('tsl1_order_id',$id)->select('*')->get();
  //               $i=0;
  //               foreach($data['tesco'] as $value)
  //               {
  //                   $tasks = TescoTSL1OrdersQuantity::find($value->id);
  //                   $newTask = $tasks->replicate();
  //                   $newTask->save();
  //                   $newTask->tsl1_order_id = $newTasks->id;
  //                   $newTask->count = $counts[$i];
  //                   $newTask->save();
  //                   $i++;
  //               }
  //           }
  //           if($type == "tesco_tsl3_orders")
  //           {
  //               $tasks = TescoTSL3Orders::find($id);
  //               $newTasks = $tasks->replicate();
  //               $newTasks->save();
                
  //               $factory=DB::table('tesco_tsl3_orders')->join('customers','customers.id','=','tesco_tsl3_orders.tesco_supplier_id')->where('tesco_tsl3_orders.id',$id)->select('*')->get();
                
  //               $pi_required=$factory{0}->pi_required;
  //               if($pi_required == 1)
  //               {
  //                   $pi_status="PI Approval Pending";
  //                   if($factory{0}->po_required == 1)
  //                   {
  //                       $po_status="PO Pending";
  //                   }
  //                   else
  //                   {
  //                       $po_status="";
  //                   }

  //               }
  //               else
  //               {
  //                   $po_status="PO Pending";
  //                   $pi_status="";
  //               }
  //               $newTasks->po_status = $po_status;
  //               $newTasks->pi_status = $pi_status;
  //               $newTasks->purchase_order_no = "";
  //               $newTasks->po_file = "";
  //               $newTasks->order_flag = "reOrder";
  //               $newTasks->save();
                
  //               $data['tesco']=DB::table('tesco_tsl3_orders_quanty')->where('tsl3_order_id',$id)->select('*')->get();
  //               $i=0;
  //               foreach($data['tesco'] as $value)
  //               {
  //                   $tasks = TescoTSL3OrdersQuantity::find($value->id);
  //                   $newTask = $tasks->replicate();
  //                   $newTask->save();
  //                   $newTask->tsl3_order_id = $newTasks->id;
  //                   $newTask->count = $counts[$i];
  //                   $newTask->save();
  //                   $i++;
  //               }
  //           }   
  //           echo true;
  //   }
  /**
     * This Function is used to set the tesco repeat order.
     *
     * @return json value
     */
  // public function setTescoRepeatorder(Request $request)
  // {
  //     $type         =   $request->type;
  //     $id           =   $request->id;
  //     $po           =   $request->po;
  //     $counts       =   explode(',',$request->counts);
  //     if($type == "tesco_tcl1_orders")
  //     {
  //       $tasks = TescoTCL1Orders::find($id);
  //       $newTasks = $tasks->replicate();
  //       $newTasks->save();
        
  //       $factory=DB::table('tesco_tcl1_orders')->join('customers','customers.id','=','tesco_tcl1_orders.tesco_supplier_id')->where('tesco_tcl1_orders.id',$id)->select('*')->get();
        
  //       $pi_required=$factory{0}->pi_required;
  //               if($pi_required == 1)
  //               {
  //                 $pi_status="PI Approval Pending";
  //                 if($factory{0}->po_required == 1)
  //                 {
  //                   $po_status="PO Pending";
  //                 }
  //                 else
  //                 {
  //                   $po_status="";
  //                 }
  //               }
  //               else
  //               {
  //                   $po_status="PO Pending";
  //                   $pi_status="";
  //               }
  //               $newTasks->po_status = $po_status;
  //               $newTasks->pi_status = $pi_status;
  //               $newTasks->po_number =$po;
  //               $newTasks->purchase_order_no = "";
  //               $newTasks->po_file = "";
  //               $newTasks->order_flag = "repeatOrder";
  //               $newTasks->save();
                
                
  //               $data['tesco']=DB::table('tesco_tcl1_orders_quanty')->where('tcl1_order_id',$id)->select('*')->get();
  //               $i=0;
  //               foreach($data['tesco'] as $value)
  //               {
  //                   $tasks = TescoTCL1OrdersQuantity::find($value->id);
  //                   $newTask = $tasks->replicate();
  //                   $newTask->save();
  //                   $newTask->tcl1_order_id = $newTasks->id;
  //                   $newTask->count = $counts[$i];
  //                   $newTask->save();
  //                   $i++;
  //               }
  //           }
  //           if($type == "tesco_tcl2_orders")
  //           {
  //               $tasks = TescoTCL2Orders::find($id);
  //               $newTasks = $tasks->replicate();
  //               $newTasks->save();
                
  //               $factory=DB::table('tesco_tcl2_orders')->join('customers','customers.id','=','tesco_tcl2_orders.tesco_supplier_id')->where('tesco_tcl2_orders.id',$id)->select('*')->get();
                
  //               $pi_required=$factory{0}->pi_required;
  //               if($pi_required == 1)
  //               {
  //                   $pi_status="PI Approval Pending";
  //                   if($factory{0}->po_required == 1)
  //                   {
  //                       $po_status="PO Pending";
  //                   }
  //                   else
  //                   {
  //                       $po_status="";
  //                   }

  //               }
  //               else
  //               {
  //                   $po_status="PO Pending";
  //                   $pi_status="";
  //               }
  //               $newTasks->po_status = $po_status;
  //               $newTasks->pi_status = $pi_status;
  //               $newTasks->po_number =$po;
  //               $newTasks->purchase_order_no = "";
  //               $newTasks->po_file = "";
  //               $newTasks->order_flag = "repeatOrder";
  //               $newTasks->save();
                
  //               $data['tesco']=DB::table('tesco_tcl2_orders_quanty')->where('tcl2_order_id',$id)->select('*')->get();
  //               $i=0;
  //               foreach($data['tesco'] as $value)
  //               {
  //                   $tasks = TescoTCL2OrdersQuantity::find($value->id);
  //                   $newTask = $tasks->replicate();
  //                   $newTask->save();
  //                   $newTask->tcl2_order_id = $newTasks->id;
  //                   $newTask->count = $counts[$i];
  //                   $newTask->save();
  //                   $i++;
  //               }
  //           }
  //           if($type == "tesco_tcl4_orders")
  //           {
  //               $tasks = TescoTCL4Orders::find($id);
  //               $newTasks = $tasks->replicate();
  //               $newTasks->save();
                
                
  //               $factory=DB::table('tesco_tcl4_orders')->join('customers','customers.id','=','tesco_tcl4_orders.tesco_supplier_id')->where('tesco_tcl4_orders.id',$id)->select('*')->get();
                
  //               $pi_required=$factory{0}->pi_required;
  //               if($pi_required == 1)
  //               {
  //                   $pi_status="PI Approval Pending";
  //                   if($factory{0}->po_required == 1)
  //                   {
  //                       $po_status="PO Pending";
  //                   }
  //                   else
  //                   {
  //                       $po_status="";
  //                   }

  //               }
  //               else
  //               {
  //                   $po_status="PO Pending";
  //                   $pi_status="";
  //               }
  //               $newTasks->po_status = $po_status;
  //               $newTasks->pi_status = $pi_status;
  //               $newTasks->po_number =$po;
  //               $newTasks->purchase_order_no = "";
  //               $newTasks->po_file = "";
  //               $newTasks->order_flag = "repeatOrder";
  //               $newTasks->save();
                
  //               $data['tesco']=DB::table('tesco_tcl4_orders_quanty')->where('tcl4_order_id',$id)->select('*')->get();
  //               $i=0;
  //               foreach($data['tesco'] as $value)
  //               {
  //                   $tasks = TescoTCL4OrdersQuantity::find($value->id);
  //                   $newTask = $tasks->replicate();
  //                   $newTask->save();
  //                   $newTask->tcl4_order_id = $newTasks->id;
  //                   $newTask->count = $counts[$i];
  //                   $newTask->save();
  //                   $i++;
  //               }
  //           }
  //           if($type == "tesco_thl1_orders")
  //           {
  //               $tasks = TescoTHL1Orders::find($id);
  //               $newTasks = $tasks->replicate();
  //               $newTasks->save();
                
                
  //               $factory=DB::table('tesco_thl1_orders')->join('customers','customers.id','=','tesco_thl1_orders.tesco_supplier_id')->where('tesco_thl1_orders.id',$id)->select('*')->get();
                
  //               $pi_required=$factory{0}->pi_required;
  //               if($pi_required == 1)
  //               {
  //                   $pi_status="PI Approval Pending";
  //                   if($factory{0}->po_required == 1)
  //                   {
  //                       $po_status="PO Pending";
  //                   }
  //                   else
  //                   {
  //                       $po_status="";
  //                   }

  //               }
  //               else
  //               {
  //                   $po_status="PO Pending";
  //                   $pi_status="";
  //               }
  //               $newTasks->po_status = $po_status;
  //               $newTasks->pi_status = $pi_status;
  //               $newTasks->po_number =$po;
  //               $newTasks->purchase_order_no = "";
  //               $newTasks->po_file = "";
  //               $newTasks->order_flag = "repeatOrder";
  //               $newTasks->save();
                
  //               $data['tesco']=DB::table('tesco_thl1_orders_quanty')->where('thl1_order_id',$id)->select('*')->get();
  //               $i=0;
  //               foreach($data['tesco'] as $value)
  //               {
  //                   $tasks = TescoTHL1OrdersQuantity::find($value->id);
  //                   $newTask = $tasks->replicate();
  //                   $newTask->save();
  //                   $newTask->thl1_order_id = $newTasks->id;
  //                   $newTask->count = $counts[$i];
  //                   $newTask->save();
  //                   $i++;
  //               }
  //           }
  //           if($type == "tesco_thl3_orders")
  //           {
  //               $tasks = TescoTHL3Orders::find($id);
  //               $newTasks = $tasks->replicate();
  //               $newTasks->save();
                
                
  //               $factory=DB::table('tesco_thl3_orders')->join('customers','customers.id','=','tesco_thl3_orders.tesco_supplier_id')->where('tesco_thl3_orders.id',$id)->select('*')->get();
                
  //               $pi_required=$factory{0}->pi_required;
  //               if($pi_required == 1)
  //               {
  //                   $pi_status="PI Approval Pending";
  //                   if($factory{0}->po_required == 1)
  //                   {
  //                       $po_status="PO Pending";
  //                   }
  //                   else
  //                   {
  //                       $po_status="";
  //                   }

  //               }
  //               else
  //               {
  //                   $po_status="PO Pending";
  //                   $pi_status="";
  //               }
  //               $newTasks->po_status = $po_status;
  //               $newTasks->pi_status = $pi_status;
  //               $newTasks->po_number =$po;
  //               $newTasks->purchase_order_no = "";
  //               $newTasks->po_file = "";
  //               $newTasks->order_flag = "repeatOrder";
  //               $newTasks->save();
                
  //               $data['tesco']=DB::table('tesco_thl3_orders_quanty')->where('thl3_order_id',$id)->select('*')->get();
  //               $i=0;
  //               foreach($data['tesco'] as $value)
  //               {
  //                   $tasks = TescoTHL3OrdersQuantity::find($value->id);
  //                   $newTask = $tasks->replicate();
  //                   $newTask->save();
  //                   $newTask->thl3_order_id = $newTasks->id;
  //                   $newTask->count = $counts[$i];
  //                   $newTask->save();
  //                   $i++;
  //               }
  //           }
  //           if($type == "tesco_tsl1_orders")
  //           {
  //               $tasks = TescoTSL1Orders::find($id);
  //               $newTasks = $tasks->replicate();
  //               $newTasks->save();
                
                
  //               $factory=DB::table('tesco_tsl1_orders')->join('customers','customers.id','=','tesco_tsl1_orders.tesco_supplier_id')->where('tesco_tsl1_orders.id',$id)->select('*')->get();
                
  //               $pi_required=$factory{0}->pi_required;
  //               if($pi_required == 1)
  //               {
  //                   $pi_status="PI Approval Pending";
  //                   if($factory{0}->po_required == 1)
  //                   {
  //                       $po_status="PO Pending";
  //                   }
  //                   else
  //                   {
  //                       $po_status="";
  //                   }

  //               }
  //               else
  //               {
  //                   $po_status="PO Pending";
  //                   $pi_status="";
  //               }
  //               $newTasks->po_status = $po_status;
  //               $newTasks->pi_status = $pi_status;
  //               $newTasks->po_number =$po;
  //               $newTasks->purchase_order_no = "";
  //               $newTasks->po_file = "";
  //               $newTasks->order_flag = "repeatOrder";
  //               $newTasks->save();
                
  //               $data['tesco']=DB::table('tesco_tsl1_orders_quanty')->where('tsl1_order_id',$id)->select('*')->get();
  //               $i=0;
  //               foreach($data['tesco'] as $value)
  //               {
  //                   $tasks = TescoTSL1OrdersQuantity::find($value->id);
  //                   $newTask = $tasks->replicate();
  //                   $newTask->save();
  //                   $newTask->tsl1_order_id = $newTasks->id;
  //                   $newTask->count = $counts[$i];
  //                   $newTask->save();
  //                   $i++;
  //               }
  //           }
  //           if($type == "tesco_tsl3_orders")
  //           {
  //               $tasks = TescoTSL3Orders::find($id);
  //               $newTasks = $tasks->replicate();
  //               $newTasks->save();
                
                
  //               $factory=DB::table('tesco_tsl3_orders')->join('customers','customers.id','=','tesco_tsl3_orders.tesco_supplier_id')->where('tesco_tsl3_orders.id',$id)->select('*')->get();
                
  //               $pi_required=$factory{0}->pi_required;
  //               if($pi_required == 1)
  //               {
  //                   $pi_status="PI Approval Pending";
  //                   if($factory{0}->po_required == 1)
  //                   {
  //                       $po_status="PO Pending";
  //                   }
  //                   else
  //                   {
  //                       $po_status="";
  //                   }

  //               }
  //               else
  //               {
  //                   $po_status="PO Pending";
  //                   $pi_status="";
  //               }
  //               $newTasks->po_status = $po_status;
  //               $newTasks->pi_status = $pi_status;
  //               $newTasks->po_number =$po;
  //               $newTasks->purchase_order_no = "";
  //               $newTasks->po_file = "";
  //               $newTasks->order_flag = "repeatOrder";
  //               $newTasks->save();
                
  //               $data['tesco']=DB::table('tesco_tsl3_orders_quanty')->where('tsl3_order_id',$id)->select('*')->get();
  //               $i=0;
  //               foreach($data['tesco'] as $value)
  //               {
  //                   $tasks = TescoTSL3OrdersQuantity::find($value->id);
  //                   $newTask = $tasks->replicate();
  //                   $newTask->save();
  //                   $newTask->tsl3_order_id = $newTasks->id;
  //                   $newTask->count = $counts[$i];
  //                   $newTask->save();
  //                   $i++;
  //               }
  //           }   
  //           echo true;
  //   }

        /**
         * Cancel tesco order
         * @param \Illuminate\Http\Request
         * @author <Praveen>
         * @return \Illuminate\Http\Response
         */
        public function cancelTescoOrder(Request $request)
        {

            $cancel_order_obj = new CancelTescoOrders();
          
            $table_array = $request->data_array;
    
            if(sizeof($table_array) > 0) {
            
                $canceled_order = $cancel_order_obj->cancelTescoOrderMethod($table_array, $this->buyer_id);
                
            }
        
            $result = $canceled_order == "cancelled"?response()->json(['error'=> false,'message' =>'success'],200):response()->json(['error'=> true,'message' =>'Error'],400);
    
            return $result;

        }
}
