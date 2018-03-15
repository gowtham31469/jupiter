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
use App\Classes\TescoCombinedPOUpload;
use App\Classes\TescoPOUploadTemplatesSwitch;
use App\Classes\TescoOrders;
use App\Classes\SaveTescoOrders;
use PDF;
use Carbon\Carbon;

class OrderController extends Controller
{

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
     * Function to get tesco order page
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function Orders()
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
                                  ->where('item_id',$exp)
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

             /* Fetch all template details by id */
             foreach ($templates as $value) 
             {
                 $template_details[] = DB::table('tesco_template')
                             ->select('tesco_item_master.id','tesco_template.template_name','tesco_template.template_file','company.name as company')
                             ->join('tesco_item_master','tesco_template.item_id','=','tesco_item_master.id')
                             ->join('company','company.id','=','tesco_item_master.company_id')
                             ->where('tesco_item_master.id','=',$value)
                             ->first();
             }

             /* Fetch tesco brands,departments and suppliers details */
             $tesco_brand = DB::table('tesco_brand')->select('brand','id')->get();
             $tesco_department = DB::table('tesco_department')->select('department','id')->get();
             $tesco_suppliers = DB::table('customers')->select('company_name','id')->where('is_active','=','1')->where('customers.id','=',Auth::guard('web_customer')->id())->get();
             
             return response()->json(['templates' => $template_details,'tesco_brand' => $tesco_brand,'tesco_department' => $tesco_department,'tesco_suppliers' => $tesco_suppliers]);
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
               $size = DB::table('tesco_size_chart')->select('uk as size')->where('po_size','=',$size)->get();
           }
           else
           {
               $size = DB::table('tesco_size_chart')->select('ce as size')->where('po_size','=',$size)->get();
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
            $tesco_order_obj = new TescoOrders();
            
            $orms_tpn         = explode(',',$request['orms_tpn']);
            $itf_code         = explode(',',$request['itf_code']);
            $cases_per_carton = explode(',',$request['cases_per_carton']);
            $templates        = $request['templates'];
            $last_insertions  = [];
            $already_exists   = [];

            $customer_details = DB::table("customers")->where('id','=',$request['supplier'])->select('pi_required','delivery_date_required','delivery_day_count')->first();
            $delivery_date_req= $customer_details->delivery_date_required;
            $pi_required      = $customer_details->pi_required;
            $day_count        = $customer_details->delivery_day_count;
            $ordered_date     = Carbon::now('Asia/Kolkata')->addDay($day_count);
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
                    if (array_filter($count_TCL1)) 
                    {
                        $is_order_count_empty = 0;
                    }
                    else
                    {
                        $is_order_count_empty = 1;
                    }

                    /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
                    $is_exists = TescoTCL1Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','<>','Saved')->where('order_status','<>','Saved')->first();
                    if($is_exists)
                    {
                       /** [ Create array of already exists artworks ] */
                       array_push($already_exists,'TCL 1');
                    }
                    else
                    {
                      /** [ Invoking createTCL1Order function using class instance ] */
                       $tesco_order     = $tesco_order_obj->createTCL1Order($request,$template_id,$pi_required,$is_order_count_empty);

                       /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                       if($request['is_insert'] == 'true')
                       {
                         $tesco_order_id  = $tesco_order->id;
                         $i = 0;
                         foreach ($orms_tpn as $tpn) 
                         {
                           TescoTCL1OrdersQuantity::create([
                                'tcl1_order_id'    => $tesco_order_id,
                                'orms_tpn'         => $tpn,
                                'itf_barcode'      => $itf_code[$i],
                                'cases_per_carton' => $cases_per_carton[$i],
                                'count'            => $count_TCL1[$i]
                              ]);
                           $i++;
                         }
                         /** [ Get tesco order id, that has artwork count > 0] */
                           $tesco_orders_id = DB::table('tesco_tcl1_orders')
                                    ->select('tesco_tcl1_orders.id')
                                    ->join('tesco_tcl1_orders_quanty','tesco_tcl1_orders_quanty.tcl1_order_id','tesco_tcl1_orders.id')
                                    ->where('tesco_tcl1_orders.id','=',$tesco_order_id)
                                    ->where('tesco_tcl1_orders_quanty.count','<>',0)
                                    ->groupBy('tesco_tcl1_orders_quanty.id')
                                    ->get();
                           if(count($tesco_orders_id) > 0)
                           { 
                            $templates_inserted = array('TCL 1' => $tesco_orders_id{0}->id);
                           }
                           else
                           {
                            $templates_inserted = [];
                           }
                       }
                       else
                       {
                         TescoTCL1OrdersQuantity::where('tcl1_order_id',$request['tcl1_last_id'])
                            ->delete();
                         $i = 0;
                         foreach ($orms_tpn as $tpn) 
                         {
                           TescoTCL1OrdersQuantity::create([
                                'tcl1_order_id'    => $request['tcl1_last_id'],
                                'orms_tpn'         => $tpn,
                                'itf_barcode'      => $itf_code[$i],
                                'cases_per_carton' => $cases_per_carton[$i],
                                'count'            => $count_TCL1[$i]
                              ]);
                           $i++;
                         }
                           /** [ Get tesco order id, that has artwork count > 0] */
                           $tesco_orders_id = DB::table('tesco_tcl1_orders')
                                    ->select('tesco_tcl1_orders.id')
                                    ->join('tesco_tcl1_orders_quanty','tesco_tcl1_orders_quanty.tcl1_order_id','tesco_tcl1_orders.id')
                                    ->where('tesco_tcl1_orders.id','=',$request['tcl1_last_id'])
                                    ->where('tesco_tcl1_orders_quanty.count','<>',0)
                                    ->groupBy('tesco_tcl1_orders_quanty.id')
                                    ->get();
                           if(count($tesco_orders_id) > 0)
                           { 
                            $templates_inserted = array('TCL 1' => $tesco_orders_id{0}->id);
                           }
                           else
                           {
                            $templates_inserted = [];
                           }
                       }
                       /** [ Return array of inserted id ] */
                       $last_insertions = array_merge($last_insertions,$templates_inserted);
                    }
                   
                }
                else if($template['template_name'] == "TCL 2")
                {
                  /** check all entries of array equal to FALSE(ie. 0) */
                    if (array_filter($count_TCL2)) 
                    {
                        $is_order_count_empty = 0;
                    }
                    else
                    {
                        $is_order_count_empty = 1;
                    }

                    /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
                    $is_exists = TescoTCL2Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','<>','Saved')->where('order_status','<>','Saved')->first();
                    if($is_exists)
                    {
                      /** [ Create array of already exists artworks ] */
                       array_push($already_exists,'TCL 2');
                    }
                    else
                    {
                         /** [ Invoking createTCL2Order function using class instance ] */
                         $tesco_order     = $tesco_order_obj->createTCL2Order($request,$template_id,$pi_required,$is_order_count_empty);

                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                         if($request['is_insert'] == 'true')
                         {
                           $tesco_order_id  = $tesco_order->id;
                           $i = 0;
                           foreach ($orms_tpn as $tpn) 
                           {
                             TescoTCL2OrdersQuantity::create([
                                  'tcl2_order_id'    => $tesco_order_id,
                                  'orms_tpn'         => $tpn,
                                  'itf_barcode'      => $itf_code[$i],
                                  'cases_per_carton' => $cases_per_carton[$i],
                                  'count'            => $count_TCL2[$i]
                                ]);
                             $i++;
                           }

                           /** [ Get tesco order id, that has artwork count > 0] */
                           $tesco_orders_id = DB::table('tesco_tcl2_orders')
                                    ->select('tesco_tcl2_orders.id')
                                    ->join('tesco_tcl2_orders_quanty','tesco_tcl2_orders_quanty.tcl2_order_id','tesco_tcl2_orders.id')
                                    ->where('tesco_tcl2_orders.id','=',$tesco_order_id)
                                    ->where('tesco_tcl2_orders_quanty.count','<>',0)
                                    ->groupBy('tesco_tcl2_orders_quanty.id')
                                    ->get();
                           if(count($tesco_orders_id) > 0)
                           { 
                             $templates_inserted = array('TCL 2' => $tesco_orders_id{0}->id);
                           }
                           else
                           {
                             $templates_inserted = [];
                           }
                         }
                         else
                         {
                           TescoTCL2OrdersQuantity::where('tcl2_order_id',$request['tcl2_last_id'])
                              ->delete();
                           $i = 0;
                           foreach ($orms_tpn as $tpn) 
                           {
                             TescoTCL2OrdersQuantity::create([
                                  'tcl2_order_id'    => $request['tcl2_last_id'],
                                  'orms_tpn'         => $tpn,
                                  'itf_barcode'      => $itf_code[$i],
                                  'cases_per_carton' => $cases_per_carton[$i],
                                  'count'            => $count_TCL2[$i]
                                ]);
                             $i++;
                           }

                           /** [ Get tesco order id, that has artwork count > 0] */
                           $tesco_orders_id = DB::table('tesco_tcl2_orders')
                                    ->select('tesco_tcl2_orders.id')
                                    ->join('tesco_tcl2_orders_quanty','tesco_tcl2_orders_quanty.tcl2_order_id','tesco_tcl2_orders.id')
                                    ->where('tesco_tcl2_orders.id','=',$request['tcl2_last_id'])
                                    ->where('tesco_tcl2_orders_quanty.count','<>',0)
                                    ->groupBy('tesco_tcl2_orders_quanty.id')
                                    ->get();
                           if(count($tesco_orders_id) > 0)
                           { 
                             $templates_inserted = array('TCL 2' => $tesco_orders_id{0}->id);
                           }
                           else
                           {
                             $templates_inserted = [];
                           }
                         }

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                   }
                }
                else
                {
                  /** check all entries of array equal to FALSE(ie. 0) */
                    if (array_filter($count_TSL1)) 
                    {
                        $is_order_count_empty = 0;
                    }
                    else
                    {
                        $is_order_count_empty = 1;
                    }

                    /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
                    $is_exists = TescoTSL1Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','<>','Saved')->where('order_status','<>','Saved')->first();
                    if($is_exists)
                    {
                      /** [ Create array of already exists artworks ] */
                       array_push($already_exists,'TSL 1');
                    }
                    else
                    {
                        /** [ Invoking createTSL1Order function using class instance ] */
                         $tesco_order     = $tesco_order_obj->createTSL1Order($request,$template_id,$pi_required,$is_order_count_empty);
                         
                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                         if($request['is_insert'] == 'true')
                         {
                           $tesco_order_id  = $tesco_order->id;
                           $i = 0;
                           foreach ($orms_tpn as $tpn) 
                           {
                             TescoTSL1OrdersQuantity::create([
                                  'tsl1_order_id'    => $tesco_order_id,
                                  'orms_tpn'         => $tpn,
                                  'itf_barcode'      => $itf_code[$i],
                                  'cases_per_carton' => $cases_per_carton[$i],
                                  'count'            => $count_TSL1[$i]
                                ]);
                             $i++;
                           }

                           /** [ Get tesco order id, that has artwork count > 0] */
                           $tesco_orders_id = DB::table('tesco_tsl1_orders')
                                    ->select('tesco_tsl1_orders.id')
                                    ->join('tesco_tsl1_orders_quanty','tesco_tsl1_orders_quanty.tsl1_order_id','tesco_tsl1_orders.id')
                                    ->where('tesco_tsl1_orders.id','=',$tesco_order_id)
                                    ->where('tesco_tsl1_orders_quanty.count','<>',0)
                                    ->groupBy('tesco_tsl1_orders_quanty.id')
                                    ->get();
                           if(count($tesco_orders_id) > 0)
                           { 
                             $templates_inserted = array('TSL 1' => $tesco_orders_id{0}->id);
                           }
                           else
                           {
                            $templates_inserted = [];
                           }
                         }
                         else
                         {
                           TescoTSL1OrdersQuantity::where('tsl1_order_id',$request['tsl1_last_id'])
                              ->delete();
                           $i = 0;
                           foreach ($orms_tpn as $tpn) 
                           {
                             TescoTSL1OrdersQuantity::create([
                                  'tsl1_order_id'    => $request['tsl1_last_id'],
                                  'orms_tpn'         => $tpn,
                                  'itf_barcode'      => $itf_code[$i],
                                  'cases_per_carton' => $cases_per_carton[$i],
                                  'count'            => $count_TSL1[$i]
                                ]);
                             $i++;
                           }

                           /** [ Get tesco order id, that has artwork count > 0] */
                           $tesco_orders_id = DB::table('tesco_tsl1_orders')
                                    ->select('tesco_tsl1_orders.id')
                                    ->join('tesco_tsl1_orders_quanty','tesco_tsl1_orders_quanty.tsl1_order_id','tesco_tsl1_orders.id')
                                    ->where('tesco_tsl1_orders.id','=',$request['tsl1_last_id'])
                                    ->where('tesco_tsl1_orders_quanty.count','<>',0)
                                    ->groupBy('tesco_tsl1_orders_quanty.id')
                                    ->get();
                           if(count($tesco_orders_id) > 0)
                           { 
                             $templates_inserted = array('TSL 1' => $tesco_orders_id{0}->id);
                           }
                           else
                           {
                            $templates_inserted = [];
                           }
                         }

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                    }
                }
              }
              else
              {
                 $count_THL1       = explode(',',$request['count_THL1']);

                 /** check all entries of array equal to FALSE(ie. 0) */
                  if (array_filter($count_THL1)) 
                  {
                      $is_order_count_empty = 0;
                  }
                  else
                  {
                      $is_order_count_empty = 1;
                  }

                  /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
                  $is_exists = TescoTHL1Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','<>','Saved')->first();
                  if($is_exists)
                  {
                    /** [ Create array of already exists artworks ] */
                    array_push($already_exists,'THL 1');
                  }
                  else
                  {
                     /** [ Invoking createTHL1Order function using class instance ] */
                     $tesco_order     = $tesco_order_obj->createTHL1Order($request,$template_id,$pi_required,$is_order_count_empty);

                     /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                     if($request['is_insert'] == 'true')
                     {
                       $tesco_order_id  = $tesco_order->id;
                       $i = 0;
                       foreach ($orms_tpn as $tpn) 
                       {
                         TescoTHL1OrdersQuantity::create([
                              'thl1_order_id'    => $tesco_order_id,
                              'orms_tpn'         => $tpn,
                              'itf_barcode'      => $itf_code[$i],
                              'cases_per_carton' => $cases_per_carton[$i],
                              'count'            => $count_THL1[$i]
                            ]);
                         $i++;
                       }

                       /** [ Get tesco order id, that has artwork count > 0] */
                       $tesco_orders_id = DB::table('tesco_thl1_orders')
                                  ->select('tesco_thl1_orders.id')
                                  ->join('tesco_thl1_orders_quanty','tesco_thl1_orders_quanty.thl1_order_id','tesco_thl1_orders.id')
                                  ->where('tesco_thl1_orders.id','=',$tesco_order_id)
                                  ->where('tesco_thl1_orders_quanty.count','<>',0)
                                  ->groupBy('tesco_thl1_orders_quanty.id')
                                  ->get();
                       if(count($tesco_orders_id) > 0)
                       { 
                         $templates_inserted = array('THL 1' => $tesco_orders_id{0}->id);
                       }
                       else
                       {
                        $templates_inserted = [];
                       }
                     }
                     else
                     {
                       TescoTHL1OrdersQuantity::where('thl1_order_id',$request['thl1_last_id'])
                          ->delete();
                       $i = 0;
                       foreach ($orms_tpn as $tpn) 
                       {
                         TescoTHL1OrdersQuantity::create([
                              'thl1_order_id'    => $request['thl1_last_id'],
                              'orms_tpn'         => $tpn,
                              'itf_barcode'      => $itf_code[$i],
                              'cases_per_carton' => $cases_per_carton[$i],
                              'count'            => $count_THL1[$i]
                            ]);
                         $i++;
                       }

                       /** [ Get tesco order id, that has artwork count > 0] */
                       $tesco_orders_id = DB::table('tesco_thl1_orders')
                                  ->select('tesco_thl1_orders.id')
                                  ->join('tesco_thl1_orders_quanty','tesco_thl1_orders_quanty.thl1_order_id','tesco_thl1_orders.id')
                                  ->where('tesco_thl1_orders.id','=',$request['thl1_last_id'])
                                  ->where('tesco_thl1_orders_quanty.count','<>',0)
                                  ->groupBy('tesco_thl1_orders_quanty.id')
                                  ->get();
                       if(count($tesco_orders_id) > 0)
                       { 
                         $templates_inserted = array('THL 1' => $tesco_orders_id{0}->id);
                       }
                       else
                       {
                        $templates_inserted = [];
                       }
                     }

                     /** [ Return array of inserted id ] */
                     $last_insertions = array_merge($last_insertions,$templates_inserted);
                 }
              }
            }
            
            return response()->json(['error'=> true,'message' =>'success','last_insertions' => $last_insertions,'delivery_date_required' => $delivery_date_req,'ordered_date' => $ordered_date,'already_exists' => $already_exists],200);
           

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
            $tesco_order_obj = new TescoOrders();
            
            $orms_tpn         = explode(',',$request['orms_tpn']);
            $ean_code         = explode(',',$request['ean_code']);
            $case_size        = explode(',',$request['case_size']);
            $size             = explode(',',$request['size']);
            $templates        = $request['templates'];
            $last_insertions  = [];
            $already_exists   = [];

            $customer_details = DB::table("customers")->where('id','=',$request['supplier'])->select('pi_required','delivery_date_required','delivery_day_count')->first();
            $delivery_date_req= $customer_details->delivery_date_required;
            $pi_required      = $customer_details->pi_required;
            $day_count        = $customer_details->delivery_day_count;
            $ordered_date     = Carbon::now('Asia/Kolkata')->addDay($day_count);

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
                    if (array_filter($count_TCL4)) 
                    {
                        $is_order_count_empty = 0;
                    }
                    else
                    {
                        $is_order_count_empty = 1;
                    }

                    /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
                    $is_exists = TescoTCL4Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','<>','Saved')->first();
                    if($is_exists)
                    {
                      /** [ Create array of already exists artworks ] */
                       array_push($already_exists,'TCL 4');
                    }
                    else
                    {
                         /** [ Invoking createTCL4Order function using class instance ] */
                         $tesco_order     = $tesco_order_obj->createTCL4Order($request,$template_id,$pi_required,$is_order_count_empty);
                         
                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                         if($request['is_insert'] == 'true')
                         {
                           $tesco_order_id  = $tesco_order->id;
                           $i = 0;
                           foreach ($orms_tpn as $tpn) 
                           {
                             TescoTCL4OrdersQuantity::create([
                                  'tcl4_order_id'    => $tesco_order_id,
                                  'orms_tpn'         => $tpn,
                                  'ean_barcode'      => $ean_code[$i],
                                  'size'             => $size[$i],
                                  'case_size'        => $case_size[$i],
                                  'count'            => $count_TCL4[$i]
                                ]);
                             $i++;
                           }

                           /** [ Get tesco order id, that has artwork count > 0] */
                           $tesco_orders_id = DB::table('tesco_tcl4_orders')
                                    ->select('tesco_tcl4_orders.id')
                                    ->join('tesco_tcl4_orders_quanty','tesco_tcl4_orders_quanty.tcl4_order_id','tesco_tcl4_orders.id')
                                    ->where('tesco_tcl4_orders.id','=',$tesco_order_id)
                                    ->where('tesco_tcl4_orders_quanty.count','<>',0)
                                    ->groupBy('tesco_tcl4_orders_quanty.id')
                                    ->get();
                           if(count($tesco_orders_id) > 0)
                           { 
                            $templates_inserted = array('TCL 4' => $tesco_orders_id{0}->id);
                           }
                           else
                           {
                            $templates_inserted = [];
                           }
                         }
                         else
                         {
                           TescoTCL4OrdersQuantity::where('tcl4_order_id',$request['tcl4_last_id'])
                              ->delete();
                           $i = 0;
                           foreach ($orms_tpn as $tpn) 
                           {
                             TescoTCL4OrdersQuantity::create([
                                  'tcl4_order_id'    => $request['tcl4_last_id'],
                                  'orms_tpn'         => $tpn,
                                  'ean_barcode'      => $ean_code[$i],
                                  'size'             => $size[$i],
                                  'case_size'        => $case_size[$i],
                                  'count'            => $count_TCL4[$i]
                                ]);
                             $i++;
                           }

                           /** [ Get tesco order id, that has artwork count > 0] */
                           $tesco_orders_id = DB::table('tesco_tcl4_orders')
                                    ->select('tesco_tcl4_orders.id')
                                    ->join('tesco_tcl4_orders_quanty','tesco_tcl4_orders_quanty.tcl4_order_id','tesco_tcl4_orders.id')
                                    ->where('tesco_tcl4_orders.id','=',$request['tcl4_last_id'])
                                    ->where('tesco_tcl4_orders_quanty.count','<>',0)
                                    ->groupBy('tesco_tcl4_orders_quanty.id')
                                    ->get();
                           if(count($tesco_orders_id) > 0)
                           { 
                            $templates_inserted = array('TCL 4' => $tesco_orders_id{0}->id);
                           }
                           else
                           {
                            $templates_inserted = [];
                           }
                         }

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                    }
                }
                else
                {
                   /** check all entries of array equal to FALSE(ie. 0) */
                    if (array_filter($count_TSL3)) 
                    {
                        $is_order_count_empty = 0;
                    }
                    else
                    {
                        $is_order_count_empty = 1;
                    }

                    /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
                    $is_exists = TescoTSL3Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','<>','Saved')->first();
                    if($is_exists)
                    {
                      /** [ Create array of already exists artworks ] */
                       array_push($already_exists,'TSL 3');
                    }
                    else
                    {
                         /** [ Invoking createTSL3Order function using class instance ] */
                         $tesco_order     = $tesco_order_obj->createTSL3Order($request,$template_id,$pi_required,$is_order_count_empty);
                         
                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                         if($request['is_insert'] == 'true')
                         {
                           $tesco_order_id  = $tesco_order->id;
                           $i = 0;
                           foreach ($orms_tpn as $tpn) 
                           {
                             TescoTSL3OrdersQuantity::create([
                                  'tsl3_order_id'    => $tesco_order_id,
                                  'orms_tpn'         => $tpn,
                                  'ean_barcode'      => $ean_code[$i],
                                  'size'             => $size[$i],
                                  'case_size'        => $case_size[$i],
                                  'count'            => $count_TSL3[$i]
                                ]);
                             $i++;
                           }

                           /** [ Get tesco order id, that has artwork count > 0] */
                           $tesco_orders_id = DB::table('tesco_tsl3_orders')
                                    ->select('tesco_tsl3_orders.id')
                                    ->join('tesco_tsl3_orders_quanty','tesco_tsl3_orders_quanty.tsl3_order_id','tesco_tsl3_orders.id')
                                    ->where('tesco_tsl3_orders.id','=',$tesco_order_id)
                                    ->where('tesco_tsl3_orders_quanty.count','<>',0)
                                    ->groupBy('tesco_tsl3_orders_quanty.id')
                                    ->get();
                           if(count($tesco_orders_id) > 0)
                           { 
                            $templates_inserted = array('TSL 3' => $tesco_orders_id{0}->id);
                           }
                           else
                           {
                            $templates_inserted = [];
                           }
                         }
                         else
                         {
                           TescoTSL3OrdersQuantity::where('tsl3_order_id',$request['tsl3_last_id'])
                              ->delete();
                           $i = 0;
                           foreach ($orms_tpn as $tpn) 
                           {
                             TescoTSL3OrdersQuantity::create([
                                  'tsl3_order_id'    => $request['tsl3_last_id'],
                                  'orms_tpn'         => $tpn,
                                  'ean_barcode'      => $ean_code[$i],
                                  'size'             => $size[$i],
                                  'case_size'        => $case_size[$i],
                                  'count'            => $count_TSL3[$i]
                                ]);
                             $i++;
                           }

                           /** [ Get tesco order id, that has artwork count > 0] */
                           $tesco_orders_id = DB::table('tesco_tsl3_orders')
                                    ->select('tesco_tsl3_orders.id')
                                    ->join('tesco_tsl3_orders_quanty','tesco_tsl3_orders_quanty.tsl3_order_id','tesco_tsl3_orders.id')
                                    ->where('tesco_tsl3_orders.id','=',$request['tsl3_last_id'])
                                    ->where('tesco_tsl3_orders_quanty.count','<>',0)
                                    ->groupBy('tesco_tsl3_orders_quanty.id')
                                    ->get();
                           if(count($tesco_orders_id) > 0)
                           { 
                            $templates_inserted = array('TSL 3' => $tesco_orders_id{0}->id);
                           }
                           else
                           {
                            $templates_inserted = [];
                           }
                         }

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                    }
                }
              }
              else
              {
                 $count_THL3       = explode(',',$request['count_THL3']);

                 /** check all entries of array equal to FALSE(ie. 0) */
                    if (array_filter($count_THL3)) 
                    {
                        $is_order_count_empty = 0;
                    }
                    else
                    {
                        $is_order_count_empty = 1;
                    }

                    /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
                    $is_exists = TescoTHL3Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','<>','Saved')->first();
                    if($is_exists)
                    {
                      /** [ Create array of already exists artworks ] */
                       array_push($already_exists,'THL 3');
                    }
                    else
                    {
                         /** [ Invoking createTHL3Order function using class instance ] */
                         $tesco_order     = $tesco_order_obj->createTHL3Order($request,$template_id,$pi_required,$is_order_count_empty);
                         
                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                         if($request['is_insert'] == 'true')
                         {
                           $tesco_order_id  = $tesco_order->id;
                           $i = 0;
                           foreach ($orms_tpn as $tpn) 
                           {
                             TescoTHL3OrdersQuantity::create([
                                  'thl3_order_id'    => $tesco_order_id,
                                  'orms_tpn'         => $tpn,
                                  'ean_barcode'      => $ean_code[$i],
                                  'size'             => $size[$i],
                                  'case_size'        => $case_size[$i],
                                  'count'            => $count_THL3[$i]
                                ]);
                             $i++;
                           }

                           /** [ Get tesco order id, that has artwork count > 0] */
                           $tesco_orders_id = DB::table('tesco_thl3_orders')
                                      ->select('tesco_thl3_orders.id')
                                      ->join('tesco_thl3_orders_quanty','tesco_thl3_orders_quanty.thl3_order_id','tesco_thl3_orders.id')
                                      ->where('tesco_thl3_orders.id','=',$tesco_order_id)
                                      ->where('tesco_thl3_orders_quanty.count','<>',0)
                                      ->groupBy('tesco_thl3_orders_quanty.id')
                                      ->get();
                           if(count($tesco_orders_id) > 0)
                           { 
                            $templates_inserted = array('THL 3' => $tesco_orders_id{0}->id);
                           }
                           else
                           {
                            $templates_inserted = [];
                           }
                         }
                         else
                         {
                           TescoTHL3OrdersQuantity::where('thl3_order_id',$request['thl3_last_id'])
                              ->delete();
                           $i = 0;
                           foreach ($orms_tpn as $tpn) 
                           {
                             TescoTHL3OrdersQuantity::create([
                                  'thl3_order_id'    => $request['thl3_last_id'],
                                  'orms_tpn'         => $tpn,
                                  'ean_barcode'      => $ean_code[$i],
                                  'size'             => $size[$i],
                                  'case_size'        => $case_size[$i],
                                  'count'            => $count_THL3[$i]
                                ]);
                             $i++;
                           }

                           /** [ Get tesco order id, that has artwork count > 0] */
                           $tesco_orders_id = DB::table('tesco_thl3_orders')
                                      ->select('tesco_thl3_orders.id')
                                      ->join('tesco_thl3_orders_quanty','tesco_thl3_orders_quanty.thl3_order_id','tesco_thl3_orders.id')
                                      ->where('tesco_thl3_orders.id','=',$request['thl3_last_id'])
                                      ->where('tesco_thl3_orders_quanty.count','<>',0)
                                      ->groupBy('tesco_thl3_orders_quanty.id')
                                      ->get();
                           if(count($tesco_orders_id) > 0)
                           { 
                            $templates_inserted = array('THL 3' => $tesco_orders_id{0}->id);
                           }
                           else
                           {
                            $templates_inserted = [];
                           }
                         }
                         
                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                    }
              }
            }
            
           return response()->json(['error'=> false,'message' =>'success','last_insertions' => $last_insertions,'delivery_date_required' => $delivery_date_req,'ordered_date' => $ordered_date,'already_exists' => $already_exists],200);

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
            $tesco_order_obj = new TescoOrders();
            
            $tpnd             = explode(',',$request['tpnd']);
            $itf_code         = explode(',',$request['itf_code']);
            $units_per_carton = explode(',',$request['units_per_carton']);
            $templates        = $request['templates'];
            $last_insertions  = [];
            $already_exists   = [];

            $customer_details = DB::table("customers")->where('id','=',$request['supplier'])->select('pi_required','delivery_date_required','delivery_day_count')->first();
            $delivery_date_req= $customer_details->delivery_date_required;
            $pi_required      = $customer_details->pi_required;
            $day_count        = $customer_details->delivery_day_count;
            $ordered_date     = Carbon::now('Asia/Kolkata')->addDay($day_count);

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
                    if (array_filter($count_TCL1)) 
                    {
                        $is_order_count_empty = 0;
                    }
                    else
                    {
                        $is_order_count_empty = 1;
                    }

                    /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
                    $is_exists = TescoTCL1Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','<>','Saved')->first();
                    if($is_exists)
                    {
                      /** [ Create array of already exists artworks ] */
                       array_push($already_exists,'TCL 1');
                    }
                    else
                    {
                         /** [ Invoking createTCL1Order function using class instance ] */
                         $tesco_order     = $tesco_order_obj->createTCL1Order($request,$template_id,$pi_required,$is_order_count_empty);
                         
                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                         if($request['is_insert'] == 'true')
                         {
                           $tesco_order_id  = $tesco_order->id;
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

                           /** [ Get tesco order id, that has artwork count > 0] */
                           $tesco_orders_id = DB::table('tesco_tcl1_orders')
                                    ->select('tesco_tcl1_orders.id')
                                    ->join('tesco_tcl1_orders_quanty','tesco_tcl1_orders_quanty.tcl1_order_id','tesco_tcl1_orders.id')
                                    ->where('tesco_tcl1_orders.id','=',$tesco_order_id)
                                    ->where('tesco_tcl1_orders_quanty.count','<>',0)
                                    ->groupBy('tesco_tcl1_orders_quanty.id')
                                    ->get();
                           if(count($tesco_orders_id) > 0)
                           { 
                            $templates_inserted = array('TCL 1' => $tesco_orders_id{0}->id);
                           }
                           else
                           {
                            $templates_inserted = [];
                           }
                         }
                         else
                         {
                           TescoTCL1OrdersQuantity::where('tcl1_order_id',$request['tcl1_last_id'])
                              ->delete();
                           $i = 0;
                           foreach ($tpnd as $value) 
                           {
                             TescoTCL1OrdersQuantity::create([
                                  'tcl1_order_id'    => $request['tcl1_last_id'],
                                  'tpnd'             => $value,
                                  'itf_barcode'      => $itf_code[$i],
                                  'units_per_carton' => $units_per_carton[$i],
                                  'count'            => $count_TCL1[$i]
                                ]);
                             $i++;
                           }

                           /** [ Get tesco order id, that has artwork count > 0] */
                           $tesco_orders_id = DB::table('tesco_tcl1_orders')
                                    ->select('tesco_tcl1_orders.id')
                                    ->join('tesco_tcl1_orders_quanty','tesco_tcl1_orders_quanty.tcl1_order_id','tesco_tcl1_orders.id')
                                    ->where('tesco_tcl1_orders.id','=',$request['tcl1_last_id'])
                                    ->where('tesco_tcl1_orders_quanty.count','<>',0)
                                    ->groupBy('tesco_tcl1_orders_quanty.id')
                                    ->get();
                           if(count($tesco_orders_id) > 0)
                           { 
                            $templates_inserted = array('TCL 1' => $tesco_orders_id{0}->id);
                           }
                           else
                           {
                            $templates_inserted = [];
                           }
                         }

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                    }
                }
                else if($template['template_name'] == "TCL 2")
                {
                    /** check all entries of array equal to FALSE(ie. 0) */
                    if (array_filter($count_TCL2)) 
                    {
                        $is_order_count_empty = 0;
                    }
                    else
                    {
                        $is_order_count_empty = 1;
                    }

                    /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
                    $is_exists = TescoTCL2Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','<>','Saved')->first();
                    if($is_exists)
                    {
                      /** [ Create array of already exists artworks ] */
                       array_push($already_exists,'TCL 2');
                    }
                    else
                    {
                         /** [ Invoking createTCL2Order function using class instance ] */
                         $tesco_order     = $tesco_order_obj->createTCL2Order($request,$template_id,$pi_required,$is_order_count_empty);
                         
                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                         if($request['is_insert'] == 'true')
                         {
                           $tesco_order_id  = $tesco_order->id;
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

                           /** [ Get tesco order id, that has artwork count > 0] */
                           $tesco_orders_id = DB::table('tesco_tcl2_orders')
                                    ->select('tesco_tcl2_orders.id')
                                    ->join('tesco_tcl2_orders_quanty','tesco_tcl2_orders_quanty.tcl2_order_id','tesco_tcl2_orders.id')
                                    ->where('tesco_tcl2_orders.id','=',$tesco_order_id)
                                    ->where('tesco_tcl2_orders_quanty.count','<>',0)
                                    ->groupBy('tesco_tcl2_orders_quanty.id')
                                    ->get();
                           if(count($tesco_orders_id) > 0)
                           { 
                            $templates_inserted = array('TCL 2' => $tesco_orders_id{0}->id);
                           }
                           else
                           {
                            $templates_inserted = [];
                           }
                         }
                         else
                         {
                           TescoTCL2OrdersQuantity::where('tcl2_order_id',$request['tcl2_last_id'])
                              ->delete();
                           $i = 0;
                           foreach ($tpnd as $value) 
                           {
                             TescoTCL2OrdersQuantity::create([
                                  'tcl2_order_id'    => $request['tcl2_last_id'],
                                  'tpnd'             => $value,
                                  'itf_barcode'      => $itf_code[$i],
                                  'units_per_carton' => $units_per_carton[$i],
                                  'count'            => $count_TCL2[$i]
                                ]);
                             $i++;
                           }

                           /** [ Get tesco order id, that has artwork count > 0] */
                           $tesco_orders_id = DB::table('tesco_tcl2_orders')
                                    ->select('tesco_tcl2_orders.id')
                                    ->join('tesco_tcl2_orders_quanty','tesco_tcl2_orders_quanty.tcl2_order_id','tesco_tcl2_orders.id')
                                    ->where('tesco_tcl2_orders.id','=',$request['tcl2_last_id'])
                                    ->where('tesco_tcl2_orders_quanty.count','<>',0)
                                    ->groupBy('tesco_tcl2_orders_quanty.id')
                                    ->get();
                           if(count($tesco_orders_id) > 0)
                           { 
                            $templates_inserted = array('TCL 2' => $tesco_orders_id{0}->id);
                           }
                           else
                           {
                            $templates_inserted = [];
                           }
                         }

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                    }
                }
                else
                {
                  /** check all entries of array equal to FALSE(ie. 0) */
                  if (array_filter($count_TSL1)) 
                  {
                      $is_order_count_empty = 0;
                  }
                  else
                  {
                      $is_order_count_empty = 1;
                  }

                  /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
                  $is_exists = TescoTSL1Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','<>','Saved')->first();
                  if($is_exists)
                  {
                    /** [ Create array of already exists artworks ] */
                    array_push($already_exists,'TSL 1');
                  }
                  else
                  {
                     /** [ Invoking createTSL1Order function using class instance ] */
                     $tesco_order     = $tesco_order_obj->createTSL1Order($request,$template_id,$pi_required,$is_order_count_empty);
                     
                     /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                     if($request['is_insert'] == 'true')
                     {
                       $tesco_order_id  = $tesco_order->id;
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

                       /** [ Get tesco order id, that has artwork count > 0] */
                       $tesco_orders_id = DB::table('tesco_tsl1_orders')
                                ->select('tesco_tsl1_orders.id')
                                ->join('tesco_tsl1_orders_quanty','tesco_tsl1_orders_quanty.tsl1_order_id','tesco_tsl1_orders.id')
                                ->where('tesco_tsl1_orders.id','=',$tesco_order_id)
                                ->where('tesco_tsl1_orders_quanty.count','<>',0)
                                ->groupBy('tesco_tsl1_orders_quanty.id')
                                ->get();
                       if(count($tesco_orders_id) > 0)
                       { 
                        $templates_inserted = array('TSL 1' => $tesco_orders_id{0}->id);
                       }
                       else
                       {
                        $templates_inserted = [];
                       }
                     }
                     else
                     {
                       TescoTSL1OrdersQuantity::where('tsl1_order_id',$request['tsl1_last_id'])
                          ->delete();
                       $i = 0;
                       foreach ($tpnd as $value) 
                       {
                         TescoTSL1OrdersQuantity::create([
                              'tsl1_order_id'    => $request['tsl1_last_id'],
                              'tpnd'             => $value,
                              'itf_barcode'      => $itf_code[$i],
                              'units_per_carton' => $units_per_carton[$i],
                              'count'            => $count_TSL1[$i]
                            ]);
                         $i++;
                       }

                       /** [ Get tesco order id, that has artwork count > 0] */
                       $tesco_orders_id = DB::table('tesco_tsl1_orders')
                                ->select('tesco_tsl1_orders.id')
                                ->join('tesco_tsl1_orders_quanty','tesco_tsl1_orders_quanty.tsl1_order_id','tesco_tsl1_orders.id')
                                ->where('tesco_tsl1_orders.id','=',$request['tsl1_last_id'])
                                ->where('tesco_tsl1_orders_quanty.count','<>',0)
                                ->groupBy('tesco_tsl1_orders_quanty.id')
                                ->get();
                       if(count($tesco_orders_id) > 0)
                       { 
                        $templates_inserted = array('TSL 1' => $tesco_orders_id{0}->id);
                       }
                       else
                       {
                        $templates_inserted = [];
                       }
                     }
                     
                     /** [ Return array of inserted id ] */
                     $last_insertions = array_merge($last_insertions,$templates_inserted);
                   }
                }
              }
              else
              {
                 $count_THL1       = explode(',',$request['count_THL1']);

                 /** check all entries of array equal to FALSE(ie. 0) */
                  if (array_filter($count_THL1)) 
                  {
                      $is_order_count_empty = 0;
                  }
                  else
                  {
                      $is_order_count_empty = 1;
                  }

                  /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
                  $is_exists = TescoTHL1Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','<>','Saved')->first();
                  if($is_exists)
                  {
                    /** [ Create array of already exists artworks ] */
                    array_push($already_exists,'THL 1');
                  }
                  else
                  {
                       /** [ Invoking createTHL1Order function using class instance ] */
                       $tesco_order     = $tesco_order_obj->createTHL1Order($request,$template_id,$pi_required,$is_order_count_empty);
                       
                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                         if($request['is_insert'] == 'true')
                         {
                           $tesco_order_id  = $tesco_order->id;
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

                           /** [ Get tesco order id, that has artwork count > 0] */
                           $tesco_orders_id = DB::table('tesco_thl1_orders')
                                      ->select('tesco_thl1_orders.id')
                                      ->join('tesco_thl1_orders_quanty','tesco_thl1_orders_quanty.thl1_order_id','tesco_thl1_orders.id')
                                      ->where('tesco_thl1_orders.id','=',$tesco_order_id)
                                      ->where('tesco_thl1_orders_quanty.count','<>',0)
                                      ->groupBy('tesco_thl1_orders_quanty.id')
                                      ->get();
                             if(count($tesco_orders_id) > 0)
                             { 
                              $templates_inserted = array('THL 1' => $tesco_orders_id{0}->id);
                             }
                             else
                             {
                              $templates_inserted = [];
                             }
                         }
                         else
                         {
                           TescoTHL1OrdersQuantity::where('thl1_order_id',$request['thl1_last_id'])
                              ->delete();
                           $i = 0;
                           foreach ($tpnd as $value) 
                           {
                             TescoTHL1OrdersQuantity::create([
                                  'thl1_order_id'    => $request['thl1_last_id'],
                                  'tpnd'             => $value,
                                  'itf_barcode'      => $itf_code[$i],
                                  'units_per_carton' => $units_per_carton[$i],
                                  'count'            => $count_THL1[$i]
                                ]);
                             $i++;
                           }

                           /** [ Get tesco order id, that has artwork count > 0] */
                           $tesco_orders_id = DB::table('tesco_thl1_orders')
                                      ->select('tesco_thl1_orders.id')
                                      ->join('tesco_thl1_orders_quanty','tesco_thl1_orders_quanty.thl1_order_id','tesco_thl1_orders.id')
                                      ->where('tesco_thl1_orders.id','=',$request['thl1_last_id'])
                                      ->where('tesco_thl1_orders_quanty.count','<>',0)
                                      ->groupBy('tesco_thl1_orders_quanty.id')
                                      ->get();
                             if(count($tesco_orders_id) > 0)
                             { 
                              $templates_inserted = array('THL 1' => $tesco_orders_id{0}->id);
                             }
                             else
                             {
                              $templates_inserted = [];
                             }
                         }

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                    }
              }
            }
            
           return response()->json(['error'=> false,'message' =>'success','last_insertions' => $last_insertions,'delivery_date_required' => $delivery_date_req,'ordered_date' => $ordered_date,'already_exists' => $already_exists],200);

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
            $tesco_order_obj = new TescoOrders();
            
            $tpnd             = explode(',',$request['tpnd']);
            $ean_code         = explode(',',$request['ean_code']);
            $case_size        = explode(',',$request['case_size']);
            $size             = explode(',',$request['size']);
            $templates        = $request['templates'];
            $last_insertions  = [];
            $already_exists   = [];

            $customer_details = DB::table("customers")->where('id','=',$request['supplier'])->select('pi_required','delivery_date_required','delivery_day_count')->first();
            $delivery_date_req= $customer_details->delivery_date_required;
            $pi_required      = $customer_details->pi_required;
            $day_count        = $customer_details->delivery_day_count;
            $ordered_date     = Carbon::now('Asia/Kolkata')->addDay($day_count);
            
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
                      if (array_filter($count_TCL4)) 
                      {
                          $is_order_count_empty = 0;
                      }
                      else
                      {
                          $is_order_count_empty = 1;
                      }

                      /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
                      $is_exists = TescoTCL4Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','<>','Saved')->first();
                      if($is_exists)
                      {
                        /** [ Create array of already exists artworks ] */
                        array_push($already_exists,'TCL 4');
                      }
                      else
                      {
                         /** [ Invoking createTCL4Order function using class instance ] */
                         $tesco_order     = $tesco_order_obj->createTCL4Order($request,$template_id,$pi_required,$is_order_count_empty);
                         
                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                         if($request['is_insert'] == 'true')
                         {
                             $tesco_order_id  = $tesco_order->id;
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

                             /** [ Get tesco order id, that has artwork count > 0] */
                             $tesco_orders_id = DB::table('tesco_tcl4_orders')
                                      ->select('tesco_tcl4_orders.id')
                                      ->join('tesco_tcl4_orders_quanty','tesco_tcl4_orders_quanty.tcl4_order_id','tesco_tcl4_orders.id')
                                      ->where('tesco_tcl4_orders.id','=',$tesco_order_id)
                                      ->where('tesco_tcl4_orders_quanty.count','<>',0)
                                      ->groupBy('tesco_tcl4_orders_quanty.id')
                                      ->get();
                             if(count($tesco_orders_id) > 0)
                             { 
                              $templates_inserted = array('TCL 4' => $tesco_orders_id{0}->id);
                             }
                             else
                             {
                              $templates_inserted = [];
                             }
                         }
                         else
                         {
                             TescoTCL4OrdersQuantity::where('tcl4_order_id',$request['tcl4_last_id'])
                                ->delete();
                             $i = 0;
                             foreach ($tpnd as $value) 
                             {
                               TescoTCL4OrdersQuantity::create([
                                    'tcl4_order_id'    => $request['tcl4_last_id'],
                                    'tpnd'             => $value,
                                    'ean_barcode'      => $ean_code[$i],
                                    'size'             => $size[$i],
                                    'case_size'        => $case_size[$i],
                                    'count'            => $count_TCL4[$i]
                                  ]);
                               $i++;
                             }

                             /** [ Get tesco order id, that has artwork count > 0] */
                             $tesco_orders_id = DB::table('tesco_tcl4_orders')
                                      ->select('tesco_tcl4_orders.id')
                                      ->join('tesco_tcl4_orders_quanty','tesco_tcl4_orders_quanty.tcl4_order_id','tesco_tcl4_orders.id')
                                      ->where('tesco_tcl4_orders.id','=',$request['tcl4_last_id'])
                                      ->where('tesco_tcl4_orders_quanty.count','<>',0)
                                      ->groupBy('tesco_tcl4_orders_quanty.id')
                                      ->get();
                             if(count($tesco_orders_id) > 0)
                             { 
                              $templates_inserted = array('TCL 4' => $tesco_orders_id{0}->id);
                             }
                             else
                             {
                              $templates_inserted = [];
                             }
                         }

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                    }
                }
                else
                {
                      /** check all entries of array equal to FALSE(ie. 0) */
                      if (array_filter($count_TSL3)) 
                      {
                          $is_order_count_empty = 0;
                      }
                      else
                      {
                          $is_order_count_empty = 1;
                      }

                      /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
                      $is_exists = TescoTSL3Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','<>','Saved')->first();
                      if($is_exists)
                      {
                        /** [ Create array of already exists artworks ] */
                        array_push($already_exists,'TSL 3');
                      }
                      else
                      {
                         /** [ Invoking createTSL3Order function using class instance ] */
                         $tesco_order     = $tesco_order_obj->createTSL3Order($request,$template_id,$pi_required,$is_order_count_empty);
                         
                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                         if($request['is_insert'] == 'true')
                         {
                             $tesco_order_id  = $tesco_order->id;
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

                             /** [ Get tesco order id, that has artwork count > 0] */
                             $tesco_orders_id = DB::table('tesco_tsl3_orders')
                                      ->select('tesco_tsl3_orders.id')
                                      ->join('tesco_tsl3_orders_quanty','tesco_tsl3_orders_quanty.tsl3_order_id','tesco_tsl3_orders.id')
                                      ->where('tesco_tsl3_orders.id','=',$tesco_order_id)
                                      ->where('tesco_tsl3_orders_quanty.count','<>',0)
                                      ->groupBy('tesco_tsl3_orders_quanty.id')
                                      ->get();
                             if(count($tesco_orders_id) > 0)
                             { 
                              $templates_inserted = array('TSL 3' => $tesco_orders_id{0}->id);
                             }
                             else
                             {
                              $templates_inserted = [];
                             }
                         }
                         else
                         {
                             TescoTSL3OrdersQuantity::where('tsl3_order_id',$request['tsl3_last_id'])
                                ->delete();
                             $i = 0;
                             foreach ($tpnd as $value) 
                             {
                               TescoTSL3OrdersQuantity::create([
                                    'tsl3_order_id'    => $request['tsl3_last_id'],
                                    'tpnd'             => $value,
                                    'ean_barcode'      => $ean_code[$i],
                                    'size'             => $size[$i],
                                    'case_size'        => $case_size[$i],
                                    'count'            => $count_TSL3[$i]
                                  ]);
                               $i++;
                             }

                             /** [ Get tesco order id, that has artwork count > 0] */
                             $tesco_orders_id = DB::table('tesco_tsl3_orders')
                                      ->select('tesco_tsl3_orders.id')
                                      ->join('tesco_tsl3_orders_quanty','tesco_tsl3_orders_quanty.tsl3_order_id','tesco_tsl3_orders.id')
                                      ->where('tesco_tsl3_orders.id','=',$request['tsl3_last_id'])
                                      ->where('tesco_tsl3_orders_quanty.count','<>',0)
                                      ->groupBy('tesco_tsl3_orders_quanty.id')
                                      ->get();
                             if(count($tesco_orders_id) > 0)
                             { 
                              $templates_inserted = array('TSL 3' => $tesco_orders_id{0}->id);
                             }
                             else
                             {
                              $templates_inserted = [];
                             }
                         }

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                      }
                }
              }
              else
              {
                 $count_THL3       = explode(',',$request['count_THL3']);

                 /** check all entries of array equal to FALSE(ie. 0) */
                  if (array_filter($count_THL3)) 
                  {
                      $is_order_count_empty = 0;
                  }
                  else
                  {
                      $is_order_count_empty = 1;
                  }

                  /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
                  $is_exists = TescoTHL3Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','<>','Saved')->first();
                  if($is_exists)
                  {
                    /** [ Create array of already exists artworks ] */
                    array_push($already_exists,'THL 3');
                  }
                  else
                  {
                       /** [ Invoking createTHL3Order function using class instance ] */
                       $tesco_order     = $tesco_order_obj->createTHL3Order($request,$template_id,$pi_required,$is_order_count_empty);
                       
                       /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                       if($request['is_insert'] == 'true')
                       {
                           $tesco_order_id  = $tesco_order->id;
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

                           /** [ Get tesco order id, that has artwork count > 0] */
                           $tesco_orders_id = DB::table('tesco_thl3_orders')
                                      ->select('tesco_thl3_orders.id')
                                      ->join('tesco_thl3_orders_quanty','tesco_thl3_orders_quanty.thl3_order_id','tesco_thl3_orders.id')
                                      ->where('tesco_thl3_orders.id','=',$tesco_order_id)
                                      ->where('tesco_thl3_orders_quanty.count','<>',0)
                                      ->groupBy('tesco_thl3_orders_quanty.id')
                                      ->get();
                           if(count($tesco_orders_id) > 0)
                           { 
                            $templates_inserted = array('THL 3' => $tesco_orders_id{0}->id);
                           }
                           else
                           {
                            $templates_inserted = [];
                           }
                       }
                       else
                       {
                           TescoTHL3OrdersQuantity::where('thl3_order_id',$request['thl3_last_id'])
                              ->delete();
                           $i = 0;
                           foreach ($tpnd as $value) 
                           {
                             TescoTHL3OrdersQuantity::create([
                                  'thl3_order_id'    => $request['thl3_last_id'],
                                  'tpnd'             => $value,
                                  'ean_barcode'      => $ean_code[$i],
                                  'size'             => $size[$i],
                                  'case_size'        => $case_size[$i],
                                  'count'            => $count_THL3[$i]
                                ]);
                             $i++;
                           }

                           /** [ Get tesco order id, that has artwork count > 0] */
                           $tesco_orders_id = DB::table('tesco_thl3_orders')
                                      ->select('tesco_thl3_orders.id')
                                      ->join('tesco_thl3_orders_quanty','tesco_thl3_orders_quanty.thl3_order_id','tesco_thl3_orders.id')
                                      ->where('tesco_thl3_orders.id','=',$request['thl3_last_id'])
                                      ->where('tesco_thl3_orders_quanty.count','<>',0)
                                      ->groupBy('tesco_thl3_orders_quanty.id')
                                      ->get();
                           if(count($tesco_orders_id) > 0)
                           { 
                            $templates_inserted = array('THL 3' => $tesco_orders_id{0}->id);
                           }
                           else
                           {
                            $templates_inserted = [];
                           }
                       }

                       /** [ Return array of inserted id ] */
                       $last_insertions = array_merge($last_insertions,$templates_inserted);
                  }
              }
            }
            
           return response()->json(['error'=> false,'message' =>'success','last_insertions' => $last_insertions,'delivery_date_required' => $delivery_date_req,'ordered_date' => $ordered_date,'already_exists' => $already_exists],200);

        } catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
    /**
     * Function to upload single buyer po file for tesco
     * @author <Praveen>
     * @return boolean true or false
     */
    public function uploadSingleOrdersPO(Request $request)
    {
      
       try {
             /** [ Creating new object for TescoPOUploadTemplatesSwitch() class ] */
             $tesco_po_obj = new TescoPOUploadTemplatesSwitch(); 
             if($request['template_1'])
             {
                $response = $tesco_po_obj->singlePOUploadTemplate1Switch($request['template_1'],$request);
             }
             if ($request['template_2']) {
                $response = $tesco_po_obj->singlePOUploadTemplate2Switch($request['template_2'],$request);
             }
             if ($request['template_3']) {
                $response = $tesco_po_obj->singlePOUploadTemplate3Switch($request['template_3'],$request);
             }
             if (($response != '') || ($response != "Invalid Template")) 
             {
               $result = response()->json(['error'=> false,'message' =>'success'],200);
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
     * Function to upload combined buyer po file for tesco
     * @author <Praveen>
     * @return boolean true or false
     */
    public function uploadCombinedOrdersPO(Request $request)
    {
      
       try {
             /** [ Creating new object for TescoPOUploadTemplatesSwitch() class ] */
             $tesco_po_obj = new TescoPOUploadTemplatesSwitch(); 
              $po_file = $request['po_file'];
              $path = $po_file->getRealPath();
              $filename = $po_file->getClientOriginalName();
              $destination_path='/storage/app/public/po-files/';
              $path='storage/app/public/po-files/';
              $Orname = $po_file->getClientOriginalName();
              $filename= 'TESCO_PO_'.time().'_'.preg_replace('/\s+/', '-', $Orname);
              $po_file->move($path,$filename);
              $destination_path_insert=$destination_path.$filename;
             if($request['template_1'])
             {
                $response = $tesco_po_obj->combinedPOUploadTemplate1Switch($request['template_1'],$request,$destination_path_insert);
             }
             if ($request['template_2']) {
                $response = $tesco_po_obj->combinedPOUploadTemplate2Switch($request['template_2'],$request,$destination_path_insert);
             }
             if ($request['template_3']) {
                $response = $tesco_po_obj->combinedPOUploadTemplate3Switch($request['template_3'],$request,$destination_path_insert);
             }

             if (($response != '') || ($response != "Invalid Template")) 
             {
               $result = response()->json(['error'=> false,'message' =>'success'],200);
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
                      /** [ Invoking saveTCL1Order function using class instance ] */
                       $tesco_order     = $tesco_order_obj->saveTCL1Order($request,$template_id);

                       /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                       if($request['is_insert'] == 'true')
                       {
                         $tesco_order_id  = $tesco_order->id;
                         $templates_inserted = array('TCL 1' => $tesco_order_id);
                         $i = 0;
                         foreach ($orms_tpn as $tpn) 
                         {
                           TescoTCL1OrdersQuantity::create([
                                'tcl1_order_id'    => $tesco_order_id,
                                'orms_tpn'         => $tpn,
                                'itf_barcode'      => $itf_code[$i],
                                'cases_per_carton' => $cases_per_carton[$i],
                                'count'            => $count_TCL1[$i]
                              ]);
                           $i++;
                         }
                       }
                       else
                       {
                         TescoTCL1OrdersQuantity::where('tcl1_order_id',$request['tcl1_last_id'])
                            ->delete();
                         $i = 0;
                         foreach ($orms_tpn as $tpn) 
                         {
                           TescoTCL1OrdersQuantity::create([
                                'tcl1_order_id'    => $request['tcl1_last_id'],
                                'orms_tpn'         => $tpn,
                                'itf_barcode'      => $itf_code[$i],
                                'cases_per_carton' => $cases_per_carton[$i],
                                'count'            => $count_TCL1[$i]
                              ]);
                           $i++;
                         }
                         $templates_inserted = array('TCL 1' => $request['tcl1_last_id']);
                       }

                       /** [ Return array of inserted id ] */
                       $last_insertions = array_merge($last_insertions,$templates_inserted);  
                     
                  }
                  else if($template['template_name'] == "TCL 2")
                  {
                       /** [ Invoking saveTCL2Order function using class instance ] */
                       $tesco_order     = $tesco_order_obj->saveTCL2Order($request,$template_id);
                       
                       /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                       if($request['is_insert'] == 'true')
                       {
                           $tesco_order_id  = $tesco_order->id;
                           $templates_inserted = array('TCL 2' => $tesco_order_id);
                           $i = 0;
                           foreach ($orms_tpn as $tpn) 
                           {
                             TescoTCL2OrdersQuantity::create([
                                  'tcl2_order_id'    => $tesco_order_id,
                                  'orms_tpn'         => $tpn,
                                  'itf_barcode'      => $itf_code[$i],
                                  'cases_per_carton' => $cases_per_carton[$i],
                                  'count'            => $count_TCL2[$i]
                                ]);
                             $i++;
                           }
                       }
                       else
                       {
                          TescoTCL2OrdersQuantity::where('tcl2_order_id',$request['tcl2_last_id'])
                            ->delete();
                           $i = 0;
                           foreach ($orms_tpn as $tpn) 
                           {
                             TescoTCL2OrdersQuantity::create([
                                  'tcl2_order_id'    => $request['tcl2_last_id'],
                                  'orms_tpn'         => $tpn,
                                  'itf_barcode'      => $itf_code[$i],
                                  'cases_per_carton' => $cases_per_carton[$i],
                                  'count'            => $count_TCL2[$i]
                                ]);
                             $i++;
                           }
                           $templates_inserted = array('TCL 2' => $request['tcl2_last_id']);
                       }

                       /** [ Return array of inserted id ] */
                       $last_insertions = array_merge($last_insertions,$templates_inserted);
                  }
                  else
                  {
                      /** [ Invoking saveTSL1Order function using class instance ] */
                       $tesco_order     = $tesco_order_obj->saveTSL1Order($request,$template_id);
                       
                       /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                       if($request['is_insert'] == 'true')
                       {
                           $tesco_order_id  = $tesco_order->id;
                           $templates_inserted = array('TSL 1' => $tesco_order_id);
                           $i = 0;
                           foreach ($orms_tpn as $tpn) 
                           {
                             TescoTSL1OrdersQuantity::create([
                                  'tsl1_order_id'    => $tesco_order_id,
                                  'orms_tpn'         => $tpn,
                                  'itf_barcode'      => $itf_code[$i],
                                  'cases_per_carton' => $cases_per_carton[$i],
                                  'count'            => $count_TSL1[$i]
                                ]);
                             $i++;
                           }
                       }
                       else
                       {
                           TescoTSL1OrdersQuantity::where('tsl1_order_id',$request['tsl1_last_id'])
                            ->delete();
                           $i = 0;
                           foreach ($orms_tpn as $tpn) 
                           {
                             TescoTSL1OrdersQuantity::create([
                                  'tsl1_order_id'    => $request['tsl1_last_id'],
                                  'orms_tpn'         => $tpn,
                                  'itf_barcode'      => $itf_code[$i],
                                  'cases_per_carton' => $cases_per_carton[$i],
                                  'count'            => $count_TSL1[$i]
                                ]);
                             $i++;
                           }
                          $templates_inserted = array('TSL 1' => $request['tsl1_last_id']);
                       }

                       /** [ Return array of inserted id ] */
                       $last_insertions = array_merge($last_insertions,$templates_inserted);
                  }
                }
                else
                {
                   $count_THL1       = explode(',',$request['count_THL1']);

                   /** [ Invoking createTHL1Order function using class instance ] */
                   $tesco_order     = $tesco_order_obj->saveTHL1Order($request,$template_id);
                   
                   /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                   if($request['is_insert'] == 'true')
                   {
                     $tesco_order_id  = $tesco_order->id;
                     $templates_inserted = array('THL 1' => $tesco_order_id);
                     $i = 0;
                     foreach ($orms_tpn as $tpn) 
                     {
                       TescoTHL1OrdersQuantity::create([
                            'thl1_order_id'    => $tesco_order_id,
                            'orms_tpn'         => $tpn,
                            'itf_barcode'      => $itf_code[$i],
                            'cases_per_carton' => $cases_per_carton[$i],
                            'count'            => $count_THL1[$i]
                          ]);
                       $i++;
                     }
                   }
                   else
                   {
                     TescoTHL1OrdersQuantity::where('thl1_order_id',$request['thl1_last_id'])
                            ->delete();
                     $i = 0;
                     foreach ($orms_tpn as $tpn) 
                     {
                       TescoTHL1OrdersQuantity::create([
                            'thl1_order_id'    => $request['thl1_last_id'],
                            'orms_tpn'         => $tpn,
                            'itf_barcode'      => $itf_code[$i],
                            'cases_per_carton' => $cases_per_carton[$i],
                            'count'            => $count_THL1[$i]
                          ]);
                       $i++;
                     }
                     $templates_inserted = array('THL 1' => $request['thl1_last_id']);
                   }

                   /** [ Return array of inserted id ] */
                   $last_insertions = array_merge($last_insertions,$templates_inserted);
                }
              }
            return response()->json(['error'=> true,'message' =>'success','last_insertions' => $last_insertions],200);
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

            foreach ($templates as $template) 
            {
              $template_id      = $template['id'];
              if($template['template_name'] != "THL 3")
              {
                $count_TCL4       = explode(',',$request['count_TCL4']);
                $count_TSL3       = explode(',',$request['count_TSL3']);
                
                if($template['template_name'] == "TCL 4")
                {
                   /** [ Invoking saveTCL4Order function using class instance ] */
                   $tesco_order     = $tesco_order_obj->saveTCL4Order($request,$template_id);

                   /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                   if($request['is_insert'] == 'true')
                   {
                     $tesco_order_id  = $tesco_order->id;
                     $i = 0;
                     foreach ($orms_tpn as $tpn) 
                     {
                       TescoTCL4OrdersQuantity::create([
                            'tcl4_order_id'    => $tesco_order_id,
                            'orms_tpn'         => $tpn,
                            'ean_barcode'      => $ean_code[$i],
                            'size'             => $size[$i],
                            'case_size'        => $case_size[$i],
                            'count'            => $count_TCL4[$i]
                          ]);
                       $i++;
                     }

                     $templates_inserted = array('TCL 4' => $tesco_order_id);
                   }
                   else
                   {
                     TescoTCL4OrdersQuantity::where('tcl4_order_id',$request['tcl4_last_id'])
                        ->delete();
                     $i = 0;
                     foreach ($orms_tpn as $tpn) 
                     {
                       TescoTCL4OrdersQuantity::create([
                            'tcl4_order_id'    => $request['tcl4_last_id'],
                            'orms_tpn'         => $tpn,
                            'ean_barcode'      => $ean_code[$i],
                            'size'             => $size[$i],
                            'case_size'        => $case_size[$i],
                            'count'            => $count_TCL4[$i]
                          ]);
                       $i++;
                     }

                     $templates_inserted = array('TCL 4' => $request['tcl4_last_id']);
                   }

                   /** [ Return array of inserted id ] */
                   $last_insertions = array_merge($last_insertions,$templates_inserted);
                }
                else
                {
                   /** [ Invoking saveTSL3Order function using class instance ] */
                   $tesco_order     = $tesco_order_obj->saveTSL3Order($request,$template_id);
                   
                   /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                   if($request['is_insert'] == 'true')
                   {
                     $tesco_order_id  = $tesco_order->id;
                     $i = 0;
                     foreach ($orms_tpn as $tpn) 
                     {
                       TescoTSL3OrdersQuantity::create([
                            'tsl3_order_id'    => $tesco_order_id,
                            'orms_tpn'         => $tpn,
                            'ean_barcode'      => $ean_code[$i],
                            'size'             => $size[$i],
                            'case_size'        => $case_size[$i],
                            'count'            => $count_TSL3[$i]
                          ]);
                       $i++;
                     }

                     $templates_inserted = array('TSL 3' => $tesco_order_id);
                   }
                   else
                   {
                     TescoTSL3OrdersQuantity::where('tsl3_order_id',$request['tsl3_last_id'])
                        ->delete();
                     $i = 0;
                     foreach ($orms_tpn as $tpn) 
                     {
                       TescoTSL3OrdersQuantity::create([
                            'tsl3_order_id'    => $request['tsl3_last_id'],
                            'orms_tpn'         => $tpn,
                            'ean_barcode'      => $ean_code[$i],
                            'size'             => $size[$i],
                            'case_size'        => $case_size[$i],
                            'count'            => $count_TSL3[$i]
                          ]);
                       $i++;
                     }

                     $templates_inserted = array('TSL 3' => $request['tsl3_last_id']);
                   }

                   /** [ Return array of inserted id ] */
                   $last_insertions = array_merge($last_insertions,$templates_inserted);
                }
              }
              else
              {
                 $count_THL3       = explode(',',$request['count_THL3']);

                 /** [ Invoking saveTHL3Order function using class instance ] */
                 $tesco_order     = $tesco_order_obj->saveTHL3Order($request,$template_id);
                 
                 /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                 if($request['is_insert'] == 'true')
                 {
                   $tesco_order_id  = $tesco_order->id;
                   $i = 0;
                   foreach ($orms_tpn as $tpn) 
                   {
                     TescoTHL3OrdersQuantity::create([
                          'thl3_order_id'    => $tesco_order_id,
                          'orms_tpn'         => $tpn,
                          'ean_barcode'      => $ean_code[$i],
                          'size'             => $size[$i],
                          'case_size'        => $case_size[$i],
                          'count'            => $count_THL3[$i]
                        ]);
                     $i++;
                   }

                   $templates_inserted = array('THL 3' => $tesco_order_id);
                 }
                 else
                 {
                   TescoTHL3OrdersQuantity::where('thl3_order_id',$request['thl3_last_id'])
                      ->delete();
                   $i = 0;
                   foreach ($orms_tpn as $tpn) 
                   {
                     TescoTHL3OrdersQuantity::create([
                          'thl3_order_id'    => $request['thl3_last_id'],
                          'orms_tpn'         => $tpn,
                          'ean_barcode'      => $ean_code[$i],
                          'size'             => $size[$i],
                          'case_size'        => $case_size[$i],
                          'count'            => $count_THL3[$i]
                        ]);
                     $i++;
                   }

                    $templates_inserted = array('THL 3' => $request['thl3_last_id']);
                 }
                 
                 /** [ Return array of inserted id ] */
                 $last_insertions = array_merge($last_insertions,$templates_inserted);
              }
            }
            
           return response()->json(['error'=> false,'message' =>'success','last_insertions' => $last_insertions],200);
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
                         /** [ Invoking saveTCL1Order function using class instance ] */
                         $tesco_order     = $tesco_order_obj->saveTCL1Order($request,$template_id);
                         
                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                         if($request['is_insert'] == 'true')
                         {
                           $tesco_order_id  = $tesco_order->id;
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

                           $templates_inserted = array('TCL 1' => $tesco_order_id);
                         }
                         else
                         {
                           TescoTCL1OrdersQuantity::where('tcl1_order_id',$request['tcl1_last_id'])
                              ->delete();
                           $i = 0;
                           foreach ($tpnd as $value) 
                           {
                             TescoTCL1OrdersQuantity::create([
                                  'tcl1_order_id'    => $request['tcl1_last_id'],
                                  'tpnd'             => $value,
                                  'itf_barcode'      => $itf_code[$i],
                                  'units_per_carton' => $units_per_carton[$i],
                                  'count'            => $count_TCL1[$i]
                                ]);
                             $i++;
                           }

                          $templates_inserted = array('TCL 1' => $request['tcl1_last_id']);
                         }

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                }
                else if($template['template_name'] == "TCL 2")
                {
                         /** [ Invoking saveTCL2Order function using class instance ] */
                         $tesco_order     = $tesco_order_obj->saveTCL2Order($request,$template_id);
                         
                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                         if($request['is_insert'] == 'true')
                         {
                           $tesco_order_id  = $tesco_order->id;
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

                           $templates_inserted = array('TCL 2' => $tesco_order_id);
                         }
                         else
                         {
                           TescoTCL2OrdersQuantity::where('tcl2_order_id',$request['tcl2_last_id'])
                              ->delete();
                           $i = 0;
                           foreach ($tpnd as $value) 
                           {
                             TescoTCL2OrdersQuantity::create([
                                  'tcl2_order_id'    => $request['tcl2_last_id'],
                                  'tpnd'             => $value,
                                  'itf_barcode'      => $itf_code[$i],
                                  'units_per_carton' => $units_per_carton[$i],
                                  'count'            => $count_TCL2[$i]
                                ]);
                             $i++;
                           }

                           $templates_inserted = array('TCL 2' => $request['tcl2_last_id']);
                         }

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                }
                else
                {
                     /** [ Invoking saveTSL1Order function using class instance ] */
                     $tesco_order     = $tesco_order_obj->saveTSL1Order($request,$template_id);
                     
                     /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                     if($request['is_insert'] == 'true')
                     {
                       $tesco_order_id  = $tesco_order->id;
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

                       $templates_inserted = array('TSL 1' => $tesco_order_id);
                     }
                     else
                     {
                       TescoTSL1OrdersQuantity::where('tsl1_order_id',$request['tsl1_last_id'])
                          ->delete();
                       $i = 0;
                       foreach ($tpnd as $value) 
                       {
                         TescoTSL1OrdersQuantity::create([
                              'tsl1_order_id'    => $request['tsl1_last_id'],
                              'tpnd'             => $value,
                              'itf_barcode'      => $itf_code[$i],
                              'units_per_carton' => $units_per_carton[$i],
                              'count'            => $count_TSL1[$i]
                            ]);
                         $i++;
                       }

                       $templates_inserted = array('TSL 1' => $request['tsl1_last_id']);
                     }
                     
                     /** [ Return array of inserted id ] */
                     $last_insertions = array_merge($last_insertions,$templates_inserted);
                }
              }
              else
              {
                 $count_THL1       = explode(',',$request['count_THL1']);

                 /** [ Invoking saveTHL1Order function using class instance ] */
                 $tesco_order     = $tesco_order_obj->saveTHL1Order($request,$template_id);
                 
                   /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                   if($request['is_insert'] == 'true')
                   {
                     $tesco_order_id  = $tesco_order->id;
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
                   
                     $templates_inserted = array('THL 1' => $tesco_order_id);
                   }
                   else
                   {
                     TescoTHL1OrdersQuantity::where('thl1_order_id',$request['thl1_last_id'])
                        ->delete();
                     $i = 0;
                     foreach ($tpnd as $value) 
                     {
                       TescoTHL1OrdersQuantity::create([
                            'thl1_order_id'    => $request['thl1_last_id'],
                            'tpnd'             => $value,
                            'itf_barcode'      => $itf_code[$i],
                            'units_per_carton' => $units_per_carton[$i],
                            'count'            => $count_THL1[$i]
                          ]);
                       $i++;
                     }

                     $templates_inserted = array('THL 1' => $request['thl1_last_id']);
                   }

                   /** [ Return array of inserted id ] */
                   $last_insertions = array_merge($last_insertions,$templates_inserted);
              }
            }
            
           return response()->json(['error'=> false,'message' =>'success','last_insertions' => $last_insertions],200);
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

            foreach ($templates as $template) 
            {
              $template_id      = $template['id'];
              if($template['template_name'] != "THL 3")
              {
                $count_TCL4       = explode(',',$request['count_TCL4']);
                $count_TSL3       = explode(',',$request['count_TSL3']);
                
                if($template['template_name'] == "TCL 4")
                {
                         /** [ Invoking saveTCL4Order function using class instance ] */
                         $tesco_order     = $tesco_order_obj->saveTCL4Order($request,$template_id);
                         
                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                         if($request['is_insert'] == 'true')
                         {
                             $tesco_order_id  = $tesco_order->id;
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

                             $templates_inserted = array('TCL 4' => $tesco_order_id);
                         }
                         else
                         {
                             TescoTCL4OrdersQuantity::where('tcl4_order_id',$request['tcl4_last_id'])
                                ->delete();
                             $i = 0;
                             foreach ($tpnd as $value) 
                             {
                               TescoTCL4OrdersQuantity::create([
                                    'tcl4_order_id'    => $request['tcl4_last_id'],
                                    'tpnd'             => $value,
                                    'ean_barcode'      => $ean_code[$i],
                                    'size'             => $size[$i],
                                    'case_size'        => $case_size[$i],
                                    'count'            => $count_TCL4[$i]
                                  ]);
                               $i++;
                             }

                             $templates_inserted = array('TCL 4' => $request['tcl4_last_id']);
                         }

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                }
                else
                {
                     /** [ Invoking saveTSL3Order function using class instance ] */
                         $tesco_order     = $tesco_order_obj->saveTSL3Order($request,$template_id);
                         
                         /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                         if($request['is_insert'] == 'true')
                         {
                             $tesco_order_id  = $tesco_order->id;
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

                             $templates_inserted = array('TSL 3' => $tesco_order_id);
                         }
                         else
                         {
                             TescoTSL3OrdersQuantity::where('tsl3_order_id',$request['tsl3_last_id'])
                                ->delete();
                             $i = 0;
                             foreach ($tpnd as $value) 
                             {
                               TescoTSL3OrdersQuantity::create([
                                    'tsl3_order_id'    => $request['tsl3_last_id'],
                                    'tpnd'             => $value,
                                    'ean_barcode'      => $ean_code[$i],
                                    'size'             => $size[$i],
                                    'case_size'        => $case_size[$i],
                                    'count'            => $count_TSL3[$i]
                                  ]);
                               $i++;
                             }

                             $templates_inserted = array('TSL 3' => $request['tsl3_last_id']);
                         }

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                }
              }
              else
              {
                 $count_THL3       = explode(',',$request['count_THL3']);

                 /** [ Invoking saveTHL3Order function using class instance ] */
                       $tesco_order     = $tesco_order_obj->saveTHL3Order($request,$template_id);
                       
                       /** Insert quantity details first time. If data already exits, delete old values and insert new values**/
                       if($request['is_insert'] == 'true')
                       {
                           $tesco_order_id  = $tesco_order->id;
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

                           $templates_inserted = array('THL 3' => $tesco_order_id);
                       }
                       else
                       {
                           TescoTHL3OrdersQuantity::where('thl3_order_id',$request['thl3_last_id'])
                              ->delete();
                           $i = 0;
                           foreach ($tpnd as $value) 
                           {
                             TescoTHL3OrdersQuantity::create([
                                  'thl3_order_id'    => $request['thl3_last_id'],
                                  'tpnd'             => $value,
                                  'ean_barcode'      => $ean_code[$i],
                                  'size'             => $size[$i],
                                  'case_size'        => $case_size[$i],
                                  'count'            => $count_THL3[$i]
                                ]);
                             $i++;
                           }

                           $templates_inserted = array('THL 3' => $request['thl3_last_id']);
                       }

                       /** [ Return array of inserted id ] */
                       $last_insertions = array_merge($last_insertions,$templates_inserted);
              }
            }
            
           return response()->json(['error'=> false,'message' =>'success','last_insertions' => $last_insertions],200);

        } catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
}
