<?php

namespace App\Http\Controllers\Admin\Tesco;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Classes\Modules;
use Response;
use View;
use DB;
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
use App\TescoWashcareOrders;
use App\TescoWashcareOrdersQuantity;
use App\TescoWashcareOrdersComponentFibreMapping;
use App\TescoWashcareOrdersComponentMapping;
use App\Classes\TescoOrders;
use App\Classes\SaveTescoOrders;
use App\Classes\CancelTescoOrders;
use App\TescoPrimarySize;
use PDF;
use Auth;
use Carbon\Carbon;
use App\Classes\ErpOrders;
use Intervention\Image\ImageManagerStatic as Image;
use App\OrderManagementHistory;
use App\Classes\StoreOrderManagementHistory;
use App\FinancialYearResetDate;
use Session;

class OrderManagementCntrl extends Controller
{
    private $modules_permission;
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
    private $year;
    private $ratio_pack_type;
    private $order_history_obj;
    private $financial_year_date;
    private $financial_year_start;
    private $financial_year_end;
    private $buyer_id;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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
        $this->tesco_washcare_orders_type = "tesco_washcare_orders";

        // get the current time
        $this->current_date = Carbon::now('Asia/Kolkata')->toDateTimeString();

        // get year as "2020"
        $this->year = Carbon::parse($this->current_date)->format('Y');

        $this->ratio_pack_type = ['RatA', 'RatB', 'RatC', 'RatD', 'RatE', 'RatF', 'RatG', 'RatH', 'RatI', 'RatJ', 'RatK', 'RatL', 'RatM', 'RatN', 'RatO', 'RatP', 'RatQ', 'RatR', 'RatS', 'RatT', 'RatU', 'RatV', 'RatW', 'RatX', 'RatY', 'RatZ'];

        $this->order_history_obj = new StoreOrderManagementHistory();

        $this->financial_year_date = FinancialYearResetDate::value('financial_year_date');
          
       // format financial year date as "yyyy-dd-mm" 
       $this->financial_year_date_format = $this->year.'-'.$this->financial_year_date;
   
       	// get the current date in "dd-mm-yyyy" format
        $this->today_date = Carbon::now('Asia/Kolkata')->format('Y-m-d');
        
        if($this->today_date < $this->financial_year_date_format){
          $this->financial_year_start = Carbon::parse($this->financial_year_date_format)->subYear()->toDateString();
          $this->financial_year_end = Carbon::parse($this->financial_year_date_format)->subDay()->toDateString();
        } else {
          $this->financial_year_start = $this->financial_year_date_format;
          $this->financial_year_end = Carbon::parse($this->financial_year_date_format)->addYear()->subDay()->toDateString();
        }
        
        $this->buyer_id = DB::table("buyers")->where('buyer_name','ilike','Tesco')->pluck('id')->first();
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
                $module_var = new Modules();
                $is_allowed = $module_var->permittedModules();
                if($is_allowed)
                {
                    $top_mod = "Order Management";
                    $get_main_module = $module_var->getMainModules();
                    View::composer('layouts.Admin.navbar', function($view) use ($get_main_module) {
                        return $view->with(['modules' => $get_main_module]);
                    });
                    $get_sub_module = $module_var->getSubModules(10);
                    return view('Admin/Tesco/order-management');
                }
                else
                {
                     return redirect('/admin/dashboard');
                }
        } catch (\Exception $e)
         {
                return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
         }
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
                           ->select('tesco_item_master.id','tesco_template.template_name','tesco_template.template_file','tesco_item_master.is_qrcode_required','company.name as company')
                           ->join('tesco_item_master','tesco_template.item_id','=','tesco_item_master.id')
                           ->join('company','company.id','=','tesco_item_master.company_id')
                           ->where('tesco_item_master.id','=',$value)
                           ->first();
           }

           /* Fetch tesco brands,departments and suppliers details */
           $tesco_brand = DB::table('tesco_brand')->select('brand','id')->where('is_enabled','=',1)->get();
           $tesco_department = DB::table('tesco_department')->select('department','id')->where('is_enabled','=',1)->get();
           $tesco_suppliers = DB::table('tesco_suppliers')
                              ->select('tesco_suppliers.supplier_name','tesco_suppliers.id')
                              ->join('customer_metadata','customer_metadata.customer_metadata_id','=','tesco_suppliers.id')
                              ->join('customer_profile','customer_profile.company_id','=','customer_metadata.customer_id')
                              ->where('is_enabled','=',1)
                              ->groupBy('tesco_suppliers.id')
                              ->get();
           
           return response()->json(['templates' => $template_details,'tesco_brand' => $tesco_brand,'tesco_department' => $tesco_department,'tesco_suppliers' => $tesco_suppliers]);
       } catch (\Exception $e) {
           return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
       }
    }
    /**
     * Function to get tesco order page
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function Orders(Request $request)
    {
      $year = explode('-', $request->year);
      $from_date = $year[0].'-'.$this->financial_year_date;
      $to_date =Carbon::parse($from_date)->addYear()->subDay()->toDateString(); 
      
      $data['tesco_tcl1_orders'] = DB::table('tesco_tcl1_orders')->select('*',DB::raw("'".$this->tcl1."'".' as "TemplateName"'))->where('order_flag', 'newOrder')->where('order_status','!=', 'Order Cancelled')->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->get();
      $data['tesco_tcl2_orders'] = DB::table('tesco_tcl2_orders')->select('*',DB::raw("'".$this->tcl2."'".' as "TemplateName"'))->where('order_flag', 'newOrder')->where('order_status','!=', 'Order Cancelled')->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->get();
      $data['tesco_tcl4_orders'] = DB::table('tesco_tcl4_orders')->select('*',DB::raw("'".$this->tcl4."'".' as "TemplateName"'))->where('order_flag', 'newOrder')->where('order_status','!=', 'Order Cancelled')->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->get();
      $data['tesco_thl1_orders'] = DB::table('tesco_thl1_orders')->select('*',DB::raw("'".$this->thl1."'".' as "TemplateName"'))->where('order_flag', 'newOrder')->where('order_status','!=','Order Cancelled')->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->get();
      $data['tesco_thl3_orders'] = DB::table('tesco_thl3_orders')->select('*',DB::raw("'".$this->thl3."'".' as "TemplateName"'))->where('order_flag', 'newOrder')->where('order_status','!=', 'Order Cancelled')->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->get();
      $data['tesco_tsl1_orders'] = DB::table('tesco_tsl1_orders')->select('*',DB::raw("'".$this->tsl1."'".' as "TemplateName"'))->where('order_flag', 'newOrder')->where('order_status','!=', 'Order Cancelled')->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->get();
      $data['tesco_tsl3_orders'] = DB::table('tesco_tsl3_orders')->select('*',DB::raw("'".$this->tsl3."'".' as "TemplateName"'))->where('order_flag', 'newOrder')->where('order_status','!=', 'Order Cancelled')->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->get();

    $data['tesco_washcare_orders'] = DB::table('tesco_washcare_orders')
    ->select('tesco_washcare_orders.id', 'tesco_washcare_orders.template_id', 'tesco_washcare_orders.zone', 'tesco_washcare_orders.template_type', 'tesco_washcare_orders.po_number', 'tesco_washcare_orders.uk_style_ref', 'tesco_washcare_orders.ce_style_ref', 'tesco_washcare_orders.order_status', 'tesco_item_master.item_name as TemplateName')
    ->join('tesco_item_master', 'tesco_item_master.id', 'tesco_washcare_orders.template_id')
    ->where('tesco_washcare_orders.order_flag', 'newOrder')
    ->where('tesco_washcare_orders.order_status', '!=', 'Order Cancelled')
    ->whereBetween('tesco_washcare_orders.created_at', [$from_date, $to_date])
    ->orderBy('tesco_washcare_orders.id', 'desc')
    ->get();

      $data['tesco_size_department'] = DB::table('tesco_size_department')->pluck('department_name', 'id')->all();
      echo json_encode($data);
    }
    /**
     * Method to create tesco order from purchase order
     * @return \Illuminate\Http\Response
     */
    public function createOrderByPDFx(Request $request) {
      try {
        if ($request->hasFile('purchase_order')) {
                $file = $request->file('purchase_order');
                $name = $file->getClientOriginalName();
                $path =  $file->move('storage/app/TESCO/purchaseOrder', $name);
                $ch   = curl_init();
                $post = file_get_contents($path);
                curl_setopt($ch, CURLOPT_URL, "http://pdfx.cs.man.ac.uk" );
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
                curl_setopt($ch, CURLOPT_POST,           1 );
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/pdf'));
                $result = curl_exec($ch);
                print_r($result);

        } else {
          return "File not found!!";
        }
      } catch (\Exception $e) {
        return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
      
    }

    /**
     * Method to create tesco order from purchase order
     * @return \Illuminate\Http\Response
     */
    public function createOrderByPDFMiner(Request $request) {
      try {

        //Read the path variables from pdfminer config.
        $python  = Config::get('pdfminer.python');
        $script  = Config::get('pdfminer.script');
        $command = Config::get('pdfminer.command');

        //Check the file has been uploaded or not
        if($request->hasFile('purchase_order')) {
          $file = $request->file('purchase_order');
          $name = $file->getClientOriginalName();
          $path =  $file->move('storage/app/TESCO/purchaseOrder', $name);
          $process = new Process($python.' '.$script.' '.$command.' '.$path);
          $process->run();

          // executes after the command finishes
          if (!$process->isSuccessful()) {
              throw new ProcessFailedException($process);
          }
          //Call the tabula method to extract the quantity table.
          $table = $this->createOrderByTabula($path);
          return response()->json(['xml' =>$process->getOutput(),'json' => $table]);
        } else {
          return "File not found!!";
        }
      } catch (\Exception $e) {
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
              return response()->json(['error' => 'Unrecognized ORMS file', 'path' => $orms_path], 400);
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
            $tesco_order_obj = new TescoOrders();
            
            $orms_tpn         = explode(',',$request['orms_tpn']);
            $itf_code         = explode(',',$request['itf_code']);
            $cases_per_carton = explode(',',$request['cases_per_carton']);
            $qr_code          = explode('|',$request['qr_code']);
            $templates        = $request['templates'];
            $last_insertions  = [];
            $already_exists   = [];
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
            
            /** [Get comapanies super admin id as created by customer id] */
            $customer_profile_id = DB::table("customer_profile")->where('company_id','=',$tesco_main_customer_id->customer_id)->where('role_id','=',1)->select('id')->first();
            /** Check if super admin exists or not  */
            if($customer_profile_id)
            {
              $created_by = $customer_profile_id->id;
            }
            else
            {
              $created_by = 0;
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
                        
                        /** [ last inserted tesco order id ] */
                        $templates_inserted = array('TCL 1' => $tesco_order_id);
                       
                       /** [ Return array of inserted id ] */
                       $last_insertions = array_merge($last_insertions,$templates_inserted);
                       $quantity_empty  = $is_order_count_empty == 1?array('TCL 1'):[];
                       $zero_quantity  = array_merge($zero_quantity,$quantity_empty);
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

                          /** [ last inserted tesco order id ] */
                          $templates_inserted = array('TCL 2' => $tesco_order_id);

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                         $quantity_empty  = $is_order_count_empty == 1?array('TCL 2'):[];
                         $zero_quantity  = array_merge($zero_quantity,$quantity_empty);
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

                          /** [ last inserted tesco order id ] */
                          $templates_inserted = array('TSL 1' => $tesco_order_id);

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                         $quantity_empty  = $is_order_count_empty == 1?array('TSL 1'):[];
                         $zero_quantity  = array_merge($zero_quantity,$quantity_empty);
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

                      /** [ last inserted tesco order id ] */
                      $templates_inserted = array('THL 1' => $tesco_order_id);

                     /** [ Return array of inserted id ] */
                     $last_insertions = array_merge($last_insertions,$templates_inserted);
                     $quantity_empty  = $is_order_count_empty == 1?array('THL 1'):[];
                     $zero_quantity  = array_merge($zero_quantity,$quantity_empty);
                 }
              }
            }
            
            return response()->json(['error'=> true,'message' =>'success','last_insertions' => $last_insertions,'delivery_date_required' => $delivery_date_req,'ordered_date' => $ordered_date,'already_exists' => $already_exists,'po_required' => $po_required,'pi_required' => $pi_required, 'zero_quantity' => $zero_quantity],200);
           

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
            $qr_code          = explode('|',$request['qr_code']);
            $templates        = $request['templates'];
            $last_insertions  = [];
            $already_exists   = [];
            $zero_quantity= [];

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
            
            /** [Get comapanies super admin id as created by customer id] */
            $customer_profile_id = DB::table("customer_profile")->where('company_id','=',$tesco_main_customer_id->customer_id)->where('role_id','=',1)->select('id')->first();
            /** Check if super admin exists or not  */
            if($customer_profile_id)
            {
              $created_by = $customer_profile_id->id;
            }
            else
            {
              $created_by = 0;
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

                          /** [ last inserted tesco order id ] */
                          $templates_inserted = array('TCL 4' => $tesco_order_id);

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                         $quantity_empty  = $is_order_count_empty == 1?array('TCL 4'):[];
                         $zero_quantity  = array_merge($zero_quantity,$quantity_empty);
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

                          /** [ last inserted tesco order id ] */
                          $templates_inserted = array('TSL 3' => $tesco_order_id);

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                         $quantity_empty  = $is_order_count_empty == 1?array('TSL 3'):[];
                         $zero_quantity  = array_merge($zero_quantity,$quantity_empty);
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

                          /** [ last inserted tesco order id ] */
                          $templates_inserted = array('THL 3' => $tesco_order_id);
                         
                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                         $quantity_empty  = $is_order_count_empty == 1?array('THL 3'):[];
                         $zero_quantity  = array_merge($zero_quantity,$quantity_empty);
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
            $tesco_order_obj = new TescoOrders();
            
            $tpnd             = explode(',',$request['tpnd']);
            $itf_code         = explode(',',$request['itf_code']);
            $units_per_carton = explode(',',$request['units_per_carton']);
            $templates        = $request['templates'];
            $last_insertions  = [];
            $already_exists   = [];
            $zero_quantity= [];

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

            /** [Get comapanies super admin id as created by customer id] */
            $customer_profile_id = DB::table("customer_profile")->where('company_id','=',$tesco_main_customer_id->customer_id)->where('role_id','=',1)->select('id')->first();
            /** Check if super admin exists or not  */
            if($customer_profile_id)
            {
              $created_by = $customer_profile_id->id;
            }
            else
            {
              $created_by = 0;
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

                          /** [ last inserted tesco order id ] */
                          $templates_inserted = array('TCL 1' => $tesco_order_id);

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                         $quantity_empty  = $is_order_count_empty == 1?array('TCL 1'):[];
                         $zero_quantity  = array_merge($zero_quantity,$quantity_empty);
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

                          /** [ last inserted tesco order id ] */
                          $templates_inserted = array('TCL 2' => $tesco_order_id);

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                         $quantity_empty  = $is_order_count_empty == 1?array('TCL 2'):[];
                         $zero_quantity  = array_merge($zero_quantity,$quantity_empty);
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

                      /** [ last inserted tesco order id ] */
                      $templates_inserted = array('TSL 1' => $tesco_order_id);
                     
                     /** [ Return array of inserted id ] */
                     $last_insertions = array_merge($last_insertions,$templates_inserted);
                     $quantity_empty  = $is_order_count_empty == 1?array('TSL 1'):[];
                     $zero_quantity  = array_merge($zero_quantity,$quantity_empty);
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

                          /** [ last inserted tesco order id ] */
                          $templates_inserted = array('THL 1' => $tesco_order_id);

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                         $quantity_empty  = $is_order_count_empty == 1?array('THL 1'):[];
                         $zero_quantity  = array_merge($zero_quantity,$quantity_empty);
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
            $tesco_order_obj = new TescoOrders();
            
            $tpnd             = explode(',',$request['tpnd']);
            $ean_code         = explode(',',$request['ean_code']);
            $case_size        = explode(',',$request['case_size']);
            $size             = explode(',',$request['size']);
            $templates        = $request['templates'];
            $last_insertions  = [];
            $already_exists   = [];
            $zero_quantity= [];

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

            /** [Get comapanies super admin id as created by customer id] */
            $customer_profile_id = DB::table("customer_profile")->where('company_id','=',$tesco_main_customer_id->customer_id)->where('role_id','=',1)->select('id')->first();
            /** Check if super admin exists or not  */
            if($customer_profile_id)
            {
              $created_by = $customer_profile_id->id;
            }
            else
            {
              $created_by = 0;
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

                          /** [ last inserted tesco order id ] */
                          $templates_inserted = array('TCL 4' => $tesco_order_id);

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                         $quantity_empty  = $is_order_count_empty == 1?array('TCL 4'):[];
                         $zero_quantity  = array_merge($zero_quantity,$quantity_empty);
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

                         /** [ last inserted tesco order id ] */
                         $templates_inserted = array('TSL 3' => $tesco_order_id);

                         /** [ Return array of inserted id ] */
                         $last_insertions = array_merge($last_insertions,$templates_inserted);
                         $quantity_empty  = $is_order_count_empty == 1?array('TSL 3'):[];
                         $zero_quantity  = array_merge($zero_quantity,$quantity_empty);
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

                       /** [ last inserted tesco order id ] */
                       $templates_inserted = array('THL 3' => $tesco_order_id);

                       /** [ Return array of inserted id ] */
                       $last_insertions = array_merge($last_insertions,$templates_inserted);
                       $quantity_empty  = $is_order_count_empty == 1?array('THL 3'):[];
                       $zero_quantity  = array_merge($zero_quantity,$quantity_empty);
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
   * Print Preview For Stickers
   *
   * @return pdf
   */
  
     public function printDivTesco(Request $request)
    {
		 $id=$request->id;
		 $db=$request->db;
		
		 $create = DB::table($db)
					->select('*')
					->where('id',$id)
					->get();
		 $this->created_date = $create{0}->created_at;
		  
		 $admin_id= Auth::guard('web')->id(); 
		 
		 $company_id = DB::table($db)
					->select('*')
					->where('id',$id)
					->get();
		
		 $customer = DB::table("customers")
					->select('*')
					->where('id',$company_id{0}->tesco_supplier_id)
					->get();
		 $customer_name = $customer{0}->short_name;
		  
		 $this->customer_name = $customer_name;
		
		 $this->template_name = $request->name;
		 $this->template_zone = $request->zone;
		  if($request->name == "TCL1-sticker")
		  {
			  $this->template_desc = "RATIO STICKER";
		  }
		  else if($request->name == "TCL2-sticker")
	      {
			   $this->template_desc = "RATIO BD STICKER"; 
		  }
		  else if($request->name == "TCL4-sticker")
	      {
			   $this->template_desc =  "SINGLES BD STICKER";
		  }
		  else if($request->name == "THL1-sticker")
	      {
			    $this->template_desc = "RATIO STICKER";
		  }
		  else if($request->name == "THL3-sticker")
	      {
			   $this->template_desc = "SINGLES RATIO BD STICKER";
		  }
		  $this->tesco_po_number = $request->tesco_po_number;
		  $this->style = $request->style;
		  $this->tesco_description = $request->tesco_description;
		  
				 // Custom Header
		PDF::setHeaderCallback(function($pdf) {
			$pdf->SetY(0);
		$name=str_replace("-sticker", "", $this->template_name);
		$zone=$this->template_zone;
			$tesco_po_number=$this->tesco_po_number;
			$style=$this->style;
			$tesco_description=$this->template_desc;
			$date=date_create($this->created_date);
			$created_date =  date_format($date,"d.m.Y");
						 $image_file= K_PATH_IMAGES.'logo_example.jpg';
					$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="50.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
<title>Qt Svg Document</title>
<desc>Generated with Qt</desc>
<defs>
</defs>
<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
</g>
<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal" style="&#10;    /* border-radius: 27px; */&#10;">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L666,65 L666,66 L499,66 L499,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L500,65 L500,144 L499,144 L499,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L821,65 L821,66 L665,66 L665,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L969,65 L969,66 L820,66 L820,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,65 L969,65 L969,105 L968,105 L968,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,104 L969,104 L969,144 L968,144 L968,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,182 L821,182 L821,183 L499,183 L499,182"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L500,143 L500,183 L499,183 L499,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,182 L969,182 L969,183 L820,183 L820,182"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,143 L969,143 L969,183 L968,183 L968,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L666,143 L666,144 L499,144 L499,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,144 L665,144 L665,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,105 L665,105 L665,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L666,104 L666,143 L665,143 L665,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,143 L820,143 L820,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,144 L820,144 L820,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L821,143 L821,144 L499,144 L499,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L1016,212 L1016,213 L8,213 L8,212"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,213 L1016,213 L1016,214 L8,214 L8,213"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L8,214 L9,213 L9,212 L8,212"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,213 L1015,214 L1016,214 L1016,212 L1015,213"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L1016,216 L1016,217 L8,217 L8,216"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,217 L1016,217 L1016,218 L8,218 L8,217"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L8,218 L9,217 L9,216 L8,216"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,217 L1015,218 L1016,218 L1016,216 L1015,217"/>
</g>
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="56cm" height="37cm" version="1.1">
    <image xlink:href="'.public_path('/images/jupiter.jpg').'" x="174" y="66" height="65px" width="116px" style=""/>
</svg>
<g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Jupiter Garments Accessories</text>

</g>
<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO. : '.$tesco_po_number.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Style: '.$style.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REF: '.$name.' - '.$zone.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Date: '.$created_date.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Size: 127x80mm</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Description: '.$tesco_description.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Rev.no:</text>
</g>
</g>
</svg>';
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);

		
				});
		
				// Custom Footer
				PDF::setFooterCallback(function($pdf) {
					$tesco_po_number=$this->tesco_po_number;
					$customer_name=$this->customer_name;
						// Position at 15 mm from bottom
						$pdf->SetY(-50);
					    $pdf->SetX(9.5);
						// Set font
						//$pdf->SetFont('frankgothcnbt');
						// Page number
					$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188mm" height="65.2667mm" viewBox="0 0 1360 168" version="1.2" baseProfile="tiny">
<title>Qt Svg Document</title>
<desc>Generated with Qt</desc>
<defs>
</defs>
<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L1352,52 L1352,53 L8,53 L8,52"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,53 L1352,53 L1352,54 L8,54 L8,53"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L8,54 L9,53 L9,52 L8,52"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,53 L1351,54 L1352,54 L1352,52 L1351,53"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L1352,56 L1352,57 L8,57 L8,56"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,57 L1352,57 L1352,58 L8,58 L8,57"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L8,58 L9,57 L9,56 L8,56"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,57 L1351,58 L1352,58 L1352,56 L1351,57"/>
</g>
<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M385,79 L386,79 L386,157 L385,157 L385,79"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M764,79 L765,79 L765,157 L764,157 L764,79"/>
</g>
<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">Customer: '.$customer_name.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO No. : '.$tesco_po_number.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">Approved by: </text>
</g>
<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
</g>
<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">Comments if any:</text>
</g>
</g>
</svg>';
					
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
				});
				$svgarr = explode(',_',$request->svg);
				$name=$request->name;
		        $svg=urldecode($request->svg);
				PDF::setImageScale(1);
				PDF::SetFont('frankgothcnbt');
				PDF::AddPage();
				$i=0;
				$yy=60;
				$qr_y=67.5;
				$j=0;
		 		$qr_count=0;
				PDF::SetXY(10, 50);
		
				$qr_flag=$request->qr_flag;
				if($name == "TCL1-sticker")
				{
					$tcl1_id=$request->tcl1_id;

          $qr_code = DB::table('tesco_tcl1_orders_quanty')->where('tcl1_order_id',$tcl1_id)->select('*')->orderBy('id', 'asc')->get();
          $qr_code_details = DB::table('tesco_tcl1_orders')->where('id',$tcl1_id)->select('*')->orderBy('id', 'asc')->get();

				}
				else if($name == "TCL2-sticker")
				{
					$tcl2_id=$request->tcl2_id;

          $qr_code = DB::table('tesco_tcl2_orders_quanty')->where('tcl2_order_id',$tcl2_id)->select('*')->orderBy('id', 'asc')->get();
          $qr_code_details = DB::table('tesco_tcl2_orders')->where('id',$tcl2_id)->select('*')->orderBy('id', 'asc')->get();
				}

				else if($name == "TCL4-sticker")
				{
					$tcl4_id=$request->tcl4_id;

					$qr_code = DB::table('tesco_tcl4_orders_quanty')->where('tcl4_order_id',$tcl4_id)->select('*')->orderBy('id', 'asc')->get();
          $qr_code_details = DB::table('tesco_tcl4_orders')->where('id',$tcl4_id)->select('*')->orderBy('id', 'asc')->get();
				}
				else if($name == "THL1-sticker")
				{
					$thl1_id=$request->thl1_id;

					$qr_code = DB::table('tesco_thl1_orders_quanty')->where('thl1_order_id',$thl1_id)->select('*')->orderBy('id', 'asc')->get();
          $qr_code_details = DB::table('tesco_thl1_orders')->where('id',$thl1_id)->select('*')->orderBy('id', 'asc')->get();
				}
				else if($name == "THL3-sticker")
				{
					$thl3_id=$request->thl3_id;

					$qr_code = DB::table('tesco_thl3_orders_quanty')->where('thl3_order_id',$thl3_id)->select('*')->orderBy('id', 'asc')->get();
          $qr_code_details = DB::table('tesco_thl3_orders')->where('id',$thl3_id)->select('*')->orderBy('id', 'asc')->get();
				}
		
				foreach($svgarr as $value)
				{
					$svg=urldecode($value);
					PDF::SetFillColor(255,255,255);
					if($i == 2)
					{
						$yy=60;
						$qr_y=67.5;
						PDF::AddPage();
						PDF::RoundedRect(15, 60, 127, 80, 0, '0000');
						$i = 0;
					}
					else if($i == 0)
					{
						PDF::RoundedRect(15, 60, 127, 80, 0, '0000');
						$yy=60;
						$qr_y=67.5;
					}
					else
					{
						PDF::RoundedRect(15, 160, 127, 80, 0, '0000');
            if($name == "TCL4-sticker")
            {
              //print_r($company_id{0}->template_id);
              if($company_id{0}->template_id == 28 || $order_details{0}->template_id == 31)
              {
                $yy += 77;
              }
              else
              {
                $yy += 100; 
              }
              
            }
            else
            {
              $yy += 100;  
            }
						$qr_y+=100;
					}
					
			
					
					$svg=urldecode($value);
					file_put_contents(public_path('/storage/app/template/big_sticker'.$j.'.svg'), $svg);
					
					$html = '<img style="width:480px;height:302.36220472px;" src="'.public_path('/storage/app/template/big_sticker'.$j.'.svg').'">';
					PDF::writeHTMLCell(127, 80, 15, $yy+1, $html, '', 0, 0, false, 'L', false);
				
					if($qr_flag == 1)
			{
				// new style
				$style = array(
					'border' => false,
					'padding' => 0,
					'bgcolor' => false
				);
				if($name == "TCL1-sticker")
				{
         
          $lasttpn = substr($qr_code[$qr_count]->orms_tpn , -3);
          $qr_check = $qr_code_details{0}->po_number.",".$qr_code[$qr_count]->itf_barcode."/".$lasttpn.",".$qr_code[$qr_count]->orms_tpn;
          if($qr_check == $qr_code[$qr_count]->qr_code)
          {
            PDF::write2DBarcode($qr_code[$qr_count]->qr_code, 'QRCODE,Q', 88, 65, 17, 17, $style, 'N');
          }
				}
				else if($name == "TCL2-sticker")
				{
          $lasttpn = substr($qr_code[$qr_count]->orms_tpn , -3);
          $qr_check = $qr_code_details{0}->po_number.",".$qr_code[$qr_count]->itf_barcode."/".$lasttpn.",".$qr_code[$qr_count]->orms_tpn;
          if($qr_check == $qr_code[$qr_count]->qr_code)
          {
            PDF::write2DBarcode($qr_code[$qr_count]->qr_code, 'QRCODE,Q', 89.5, 69, 17, 17, $style, 'N');
          }
				}
				else if($name == "TCL4-sticker")
				{
          $lasttpn = substr($qr_code[$qr_count]->orms_tpn , -3);
          $qr_check = $qr_code_details{0}->po_number.",".$qr_code[$qr_count]->ean_barcode."/".$lasttpn.",".$qr_code[$qr_count]->orms_tpn;
          if($qr_check == $qr_code[$qr_count]->qr_code)
          {
            PDF::write2DBarcode($qr_code[$qr_count]->qr_code, 'QRCODE,Q', 89, $qr_y+1.5, 17, 17, $style, 'N');
          }
				}
				else if($name == "THL1-sticker")
				{
          $lasttpn = substr($qr_code[$qr_count]->orms_tpn , -3);
          $qr_check = $qr_code_details{0}->po_number.",".$qr_code[$qr_count]->itf_barcode."/".$lasttpn.",".$qr_code[$qr_count]->orms_tpn;
          if($qr_check == $qr_code[$qr_count]->qr_code)
          {
            PDF::write2DBarcode($qr_code[$qr_count]->qr_code, 'QRCODE,Q', 89, 66, 17, 17, $style, 'N');
          }
				}
				else if($name == "THL3-sticker")
				{
          $lasttpn = substr($qr_code[$qr_count]->orms_tpn , -3);
          $qr_check = $qr_code_details{0}->po_number.",".$qr_code[$qr_count]->ean_barcode."/".$lasttpn.",".$qr_code[$qr_count]->orms_tpn;
          if($qr_check == $qr_code[$qr_count]->qr_code)
          {
            PDF::write2DBarcode($qr_code[$qr_count]->qr_code, 'QRCODE,Q', 90, $qr_y-2, 17, 17, $style, 'N');
          }
				}
				
			}
					
					$i++;
					$j++;
					$qr_count++;
					
				}
		
				$k=0;
				if(count($svgarr) == $j)
				{
					foreach($svgarr as $value)
					{
						unlink(public_path('/storage/app/template/big_sticker'.$k.'.svg'));
						$k++;
					}
				}
				PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
				print_r(url('/storage/app/template/'.$name.'.pdf'));
    }  
  
	
	/**
   * Print Preview For Stickers
   *
   * @return pdf
   */
    
    public function generateDivTesco(Request $request)
    {
            $svgarr = explode(',_',$request->svg);
            $qr_tpndarr = explode(',_',$request->qr_tpnd_fin);
        $name=$request->name;
        $svg=urldecode($request->svg);
        define ('PDF_FONT_NAME_MAIN', 'frankgothcnbt');
        define ('PDF_FONT_NAME_DATA', 'frankgothcnbt');
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
        PDF::SetFont('frankgothcnbt');
		PDF::SetFont('franklingothicdemicond');
        PDF::SetAutoPageBreak(TRUE);
        $i=0;
		
		$qr_flag=$request->qr_flag;
		if($name == "TCL1-sticker")
		{
			$tcl1_id=$request->tcl1_id;
			
			$qr_code = DB::table('tesco_tcl1_orders_quanty')->where('tcl1_order_id',$tcl1_id)->select('*')->orderBy('id', 'asc')->get();
      $qr_code_details = DB::table('tesco_tcl1_orders')->where('id',$tcl1_id)->select('*')->orderBy('id', 'asc')->get();
			
		}
		else if($name == "TCL2-sticker")
		{
			$tcl2_id=$request->tcl2_id;
			
			$qr_code = DB::table('tesco_tcl2_orders_quanty')->where('tcl2_order_id',$tcl2_id)->select('*')->orderBy('id', 'asc')->get();
      $qr_code_details = DB::table('tesco_tcl2_orders')->where('id',$tcl2_id)->select('*')->orderBy('id', 'asc')->get();
		}
		
		else if($name == "TCL4-sticker")
		{
			$tcl4_id=$request->tcl4_id;
			
			$qr_code = DB::table('tesco_tcl4_orders_quanty')->where('tcl4_order_id',$tcl4_id)->select('*')->orderBy('id', 'asc')->get();
      $qr_code_details = DB::table('tesco_tcl4_orders')->where('id',$tcl4_id)->select('*')->orderBy('id', 'asc')->get();
		}
		else if($name == "THL1-sticker")
		{
			$thl1_id=$request->thl1_id;
			
			$qr_code = DB::table('tesco_thl1_orders_quanty')->where('thl1_order_id',$thl1_id)->select('*')->orderBy('id', 'asc')->get();
      $qr_code_details = DB::table('tesco_thl1_orders')->where('id',$thl1_id)->select('*')->orderBy('id', 'asc')->get();
		}
		else if($name == "THL3-sticker")
		{
			$thl3_id=$request->thl3_id;
			
			$qr_code = DB::table('tesco_thl3_orders_quanty')->where('thl3_order_id',$thl3_id)->select('*')->orderBy('id', 'asc')->get();
      $qr_code_details = DB::table('tesco_thl3_orders')->where('id',$thl3_id)->select('*')->orderBy('id', 'asc')->get();
		}
		
        foreach($svgarr as $value)
        {
             PDF::SetMargins(0, 0, 0, false);
             PDF::AddPage('L', array(80,127));
             $svg=urldecode($value);
             PDF::setImageScale(1.66);
             PDF::ImageSVG('@' . $svg, $x=0, $y=0, $w=127, $h=80, $link='', $align='', $palign='', $border=0, $fitonpage=true);
            if($name != "TCL2-sticker" && $name != "TCL4-sticker")
            {
                 if($name != "THL3-sticker")
                 {
                     if($qr_flag == 1)
					 {
						 if($name == "THL1-sticker")
                 		 {
							 PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), 6, 54, 50, 16, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
						 }
						 else
						 {
							 PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), 6, 54, 50, 16, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
						 }
						 
					 }
					 else
					 {
						 PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), 12, 52, 50, 16, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
					 }
                 }
                else
                {
					if($qr_flag == 1)
					{
						PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'),-4, 53.5, 75, 16, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
					}
					else
					{
						PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), -2, 52, 75, 16, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
					}
                     
                }
            }
			
			if($qr_flag == 1)
			{
				// new style
				$style = array(
					'border' => false,
					'padding' => 0,
					'bgcolor' => false
        );
        if($qr_tpndarr[$i] == $qr_code[$i]->orms_tpn)
        {
            if($name == "TCL1-sticker")
            {
              $lasttpn = substr($qr_code[$i]->orms_tpn , -3);
              $qr_check = $qr_code_details{0}->po_number.",".$qr_code[$i]->itf_barcode."/".$lasttpn.",".$qr_code[$i]->orms_tpn;
              if($qr_check == $qr_code[$i]->qr_code)
              {
                PDF::write2DBarcode($qr_code[$i]->qr_code, 'QRCODE,Q', 73.5, 5, 17, 17, $style, 'N');
              }
            }
            else if($name == "TCL2-sticker")
            {
              $lasttpn = substr($qr_code[$i]->orms_tpn , -3);
              $qr_check = $qr_code_details{0}->po_number.",".$qr_code[$i]->itf_barcode."/".$lasttpn.",".$qr_code[$i]->orms_tpn;
              if($qr_check == $qr_code[$i]->qr_code)
              {
                PDF::write2DBarcode($qr_code[$i]->qr_code, 'QRCODE,Q', 74.5, 8.5, 17, 17, $style, 'N');
              }
            }
            else if($name == "TCL4-sticker")
            {
              $lasttpn = substr($qr_code[$i]->orms_tpn , -3);
              $qr_check = $qr_code_details{0}->po_number.",".$qr_code[$i]->ean_barcode."/".$lasttpn.",".$qr_code[$i]->orms_tpn;
              if($qr_check == $qr_code[$i]->qr_code)
              {
                PDF::write2DBarcode($qr_code[$i]->qr_code, 'QRCODE,Q', 74, 8.5, 17, 17, $style, 'N');
              }
            }
            else if($name == "THL1-sticker")
            {
              $lasttpn = substr($qr_code[$i]->orms_tpn , -3);
              $qr_check = $qr_code_details{0}->po_number.",".$qr_code[$i]->itf_barcode."/".$lasttpn.",".$qr_code[$i]->orms_tpn;
              if($qr_check == $qr_code[$i]->qr_code)
              {
                PDF::write2DBarcode($qr_code[$i]->qr_code, 'QRCODE,Q', 74, 5, 17, 17, $style, 'N');
              }
            }
            else if($name == "THL3-sticker")
            {
              $lasttpn = substr($qr_code[$i]->orms_tpn , -3);
              $qr_check = $qr_code_details{0}->po_number.",".$qr_code[$i]->ean_barcode."/".$lasttpn.",".$qr_code[$i]->orms_tpn;
              if($qr_check == $qr_code[$i]->qr_code)
              {
                PDF::write2DBarcode($qr_code[$i]->qr_code, 'QRCODE,Q', 75, 5, 17, 17, $style, 'N');
              }
            }
        }
				
			}
			
// new style
//			$style = array(
//				'border' => false,
//				'padding' => 0,
//				'bgcolor' => false
//			);
//			PDF::write2DBarcode('Gowtham', 'QRCODE,Q', 100, 50, 23, 23, $style, 'N');
            
             $i++;
        }
        if($name != "TCL4-sticker" && $name != "TCL2-sticker")
		{
			$j=0;
			if(count($svgarr) == $i)
			{
				foreach($svgarr as $value)
				{
					unlink(public_path('/storage/app/template/barcode_'.$j.'.png'));
					$j++;
				}
			}
		}
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
		 $id=$request->id;
		 $db=$request->db;
		
		 $create = DB::table($db)
					->select('*')
					->where('id',$id)
					->get();
		 $this->created_date = $create{0}->created_at;
		
		
		 $admin_id= Auth::guard('web')->id(); 
		 
		 $company_id = DB::table($db)
					->select('*')
					->where('id',$id)
					->get();
		
		 $customer = DB::table("customers")
					->select('*')
					->where('id',$company_id{0}->tesco_supplier_id)
					->get();
		 $customer_name = $customer{0}->short_name;
		  
		 $this->customer_name = $customer_name;
		
		 $this->template_name = $request->name;
		 $this->template_zone = $request->zone;
		  if($request->name == "TSL1-sticker")
		  {
			  $this->template_desc = "RATIO POLYBAG STICKER";
		  }
		  else if($request->name == "TSL3-sticker")
	      {
			   $this->template_desc = "SINGLE POLYBAG STICKER "; 
		  }
		  $this->tesco_po_number = $request->tesco_po_number;
		  $this->style = $request->style;
		  $this->tesco_description = $request->tesco_description;
		  
				 // Custom Header
		PDF::setHeaderCallback(function($pdf) {
		$name=str_replace("-sticker", "", $this->template_name);
		$zone=$this->template_zone;
			$tesco_po_number=$this->tesco_po_number;
			$style=$this->style;
			$tesco_description=$this->template_desc;
			$date=date_create($this->created_date);
			$created_date =  date_format($date,"d.m.Y");
						 $image_file= K_PATH_IMAGES.'logo_example.jpg';
					$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="50.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
<title>Qt Svg Document</title>
<desc>Generated with Qt</desc>
<defs>
</defs>
<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
</g>
<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal" style="&#10;    /* border-radius: 27px; */&#10;">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L666,65 L666,66 L499,66 L499,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L500,65 L500,144 L499,144 L499,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L821,65 L821,66 L665,66 L665,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L969,65 L969,66 L820,66 L820,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,65 L969,65 L969,105 L968,105 L968,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,104 L969,104 L969,144 L968,144 L968,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,182 L821,182 L821,183 L499,183 L499,182"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L500,143 L500,183 L499,183 L499,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,182 L969,182 L969,183 L820,183 L820,182"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,143 L969,143 L969,183 L968,183 L968,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L666,143 L666,144 L499,144 L499,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,144 L665,144 L665,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,105 L665,105 L665,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L666,104 L666,143 L665,143 L665,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,143 L820,143 L820,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,144 L820,144 L820,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L821,143 L821,144 L499,144 L499,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L1016,212 L1016,213 L8,213 L8,212"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,213 L1016,213 L1016,214 L8,214 L8,213"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L8,214 L9,213 L9,212 L8,212"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,213 L1015,214 L1016,214 L1016,212 L1015,213"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L1016,216 L1016,217 L8,217 L8,216"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,217 L1016,217 L1016,218 L8,218 L8,217"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L8,218 L9,217 L9,216 L8,216"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,217 L1015,218 L1016,218 L1016,216 L1015,217"/>
</g>
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="56cm" height="37cm" version="1.1">
    <image xlink:href="'.public_path('/images/jupiter.jpg').'" x="174" y="66" height="65px" width="116px" style=""/>
</svg>
<g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Jupiter Garments Accessories</text>

</g>
<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO. : '.$tesco_po_number.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Style: '.$style.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REF: '.$name.' - '.$zone.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Date: '.$created_date.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Size: 40x30mm</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Description: '.$tesco_description.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Rev.no:</text>
</g>
</g>
</svg>';
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);

		
				});
		
				// Custom Footer
				PDF::setFooterCallback(function($pdf) {
		$tesco_po_number=$this->tesco_po_number;
					$customer_name=$this->customer_name;
						// Position at 15 mm from bottom
						$pdf->SetY(-50);
					    $pdf->SetX(9.5);
						// Set font
						//$pdf->SetFont('frankgothcnbt');
						// Page number
					$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188mm" height="65.2667mm" viewBox="0 0 1360 168" version="1.2" baseProfile="tiny">
<title>Qt Svg Document</title>
<desc>Generated with Qt</desc>
<defs>
</defs>
<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L1352,52 L1352,53 L8,53 L8,52"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,53 L1352,53 L1352,54 L8,54 L8,53"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L8,54 L9,53 L9,52 L8,52"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,53 L1351,54 L1352,54 L1352,52 L1351,53"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L1352,56 L1352,57 L8,57 L8,56"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,57 L1352,57 L1352,58 L8,58 L8,57"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L8,58 L9,57 L9,56 L8,56"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,57 L1351,58 L1352,58 L1352,56 L1351,57"/>
</g>
<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M385,79 L386,79 L386,157 L385,157 L385,79"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M764,79 L765,79 L765,157 L764,157 L764,79"/>
</g>
<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">Customer: '.$customer_name.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO No. : '.$tesco_po_number.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">Approved by: </text>
</g>
<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
</g>
<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">Comments if any:</text>
</g>
</g>
</svg>';
					
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
				});
		$svgarr = explode(',_',$request->svg);
		$name=$request->name;
        $svg=urldecode($request->svg);
        PDF::setImageScale(1);
        PDF::SetFont('frankgothcnbt');	
		PDF::AddPage();
		$i=0;
		$yy=0;
		$j=0;
		PDF::SetXY(10, 50);
		foreach($svgarr as $value)
		{
			$svg=urldecode($value);
			PDF::SetFillColor(255,255,255);
			if($i == 3 || $i == 6 || $i == 9 || $i == 15 || $i == 18)
			{
				$xx=15;
				$yy+=50;
				PDF::RoundedRect($xx , $yy - 1 , 42, 32, 0, '0000');
			}
			else if($i == 0)
			{
				$yy=50;
				$xx=15;
				PDF::RoundedRect($xx , $yy - 1 , 42, 32, 0, '0000');
			}
			else if($i == 12)
			{
				PDF::AddPage();
				$xx=15;
				$yy=50;
				PDF::RoundedRect($xx , $yy - 1 , 42, 32, 0, '0000');
			}
			else 
			{
				$xx+=70;
				PDF::RoundedRect($xx , $yy - 1 , 42, 32, 0, '0000');
			}
			
			
			
			file_put_contents(public_path('/storage/app/template/small_sticker'.$j.'.svg'), $svg);
			
			$html = '<img style="width:151.18110236px;height:113.38582677px;" src="'.public_path('/storage/app/template/small_sticker'.$j.'.svg').'">';
			PDF::writeHTMLCell(42, 32, $xx, $yy, $html, '', 0, 0, false, 'L', false);
			
//			
//			
//			file_put_contents(public_path('/storage/app/template/demo_'.$j.'.svg'), $svg);
//			shell_exec("inkscape -z -e ".public_path('/storage/app/template/demo_'.$j.'.png')." -w 1024 -h 653 ".public_path('/storage/app/template/demo_'.$j.'.svg')."");
//			PDF::Image(public_path('/storage/app/template/demo_'.$j.'.png'), $xx, $yy, 40, 30, 'png', '', '', true, 300, '', false, false, 1, false, false, false);
			$i++;
			$j++;

		}
		
		$k=0;
		if(count($svgarr) == $j)
		{
			foreach($svgarr as $value)
			{
				unlink(public_path('/storage/app/template/small_sticker'.$k.'.svg'));
				$k++;
			}
		}
		
       
        PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
        print_r(url('/storage/app/template/'.$name.'.pdf'));
    } 
	
	 /**
   * Print Preview For Stickers
   *
   * @return pdf
   */
  
    public function generateDivTescoSmall(Request $request)
    {
		$svgarr = explode(',_',$request->svg);
    	$name=$request->name;
        $svg=urldecode($request->svg);
        define ('PDF_FONT_NAME_MAIN', 'frankgothcnbt');
        define ('PDF_FONT_NAME_DATA', 'frankgothcnbt');
        PDF::setImageScale(1.66);
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
        PDF::SetFont('frankgothcnbt');
		PDF::SetFont('myriad');
		PDF::SetAutoPageBreak(TRUE);
		$i=0;
		foreach($svgarr as $value)
		{
			 PDF::AddPage('L', array(30,80));
			 PDF::SetMargins(0, 0, 0, false);
			
			 $svg=urldecode($value);
			
			
			
			 PDF::ImageSVG('@' . $svg, $x=0, $y=0, $w=40, $h=30, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			 PDF::ImageSVG('@' . $svg, $x=40, $y=0, $w=40, $h=30, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			 if($name == "TSL3-sticker")
			 {
				 PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), 0.9, 2, 36.5, 13, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
			
				PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), 40.9, 2, 36.5, 13, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
			 }
			 else
			 {
//				 $img = Image::make(public_path('/storage/app/template/barcode_'.$i.'.png'));
//
//				// now you are able to resize the instance
//				$img->resize(150, 50);
//
//				// finally we save the image as a new file
//				$img->save(public_path('/storage/app/template/barcode_RESIZE.png'));
				 
				 PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), -1.1, 2,41.5, 13, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
			
				PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), 38.7, 2, 41.5, 13, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
			 }
			
			
			
			
//			PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), -1.7, 2, 42, 13, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
//			
//			PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), 39.7, 2, 42, 13, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
//			
			$i++;
    }
    $j=0;
		if(count($svgarr) == $i)
		{
			foreach($svgarr as $value)
			{
				unlink(public_path('/storage/app/template/barcode_'.$j.'.png'));
				$j++;
			}
		}
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

        if(Session::get('fiscal-year') != null) {
          $year = explode('-', Session::get('fiscal-year'));
          $from_date = $year[0].'-'.$this->financial_year_date;
          $to_date =Carbon::parse($from_date)->addYear()->subDay()->toDateString(); 
        } else {
          $from_date = $this->financial_year_start;
          $to_date =$this->financial_year_end; 
        }
        
        $data['tesco_tcl1_orders'] = DB::table('tesco_tcl1_orders')->whereNotIn('order_status', ["Saved","Order Placed","Order Cancelled"])->whereBetween('created_at', [$from_date, $to_date])->select('*',DB::raw("'stickers' as \"type\""))->get();

    		$data['tesco_tcl2_orders'] = DB::table('tesco_tcl2_orders')->whereNotIn('order_status', ["Saved","Order Placed","Order Cancelled"])->whereBetween('created_at', [$from_date, $to_date])->select('*',DB::raw("'stickers' as \"type\""))->get();

    		$data['tesco_tcl4_orders'] = DB::table('tesco_tcl4_orders')->whereNotIn('order_status', ["Saved","Order Placed","Order Cancelled"])->whereBetween('created_at', [$from_date, $to_date])->select('*',DB::raw("'stickers' as \"type\""))->get();

    		$data['tesco_thl1_orders'] = DB::table('tesco_thl1_orders')->whereNotIn('order_status', ["Saved","Order Placed","Order Cancelled"])->whereBetween('created_at', [$from_date, $to_date])->select('*',DB::raw("'stickers' as \"type\""))->get();

    		$data['tesco_thl3_orders'] = DB::table('tesco_thl3_orders')->whereNotIn('order_status', ["Saved","Order Placed","Order Cancelled"])->whereBetween('created_at', [$from_date, $to_date])->select('*',DB::raw("'stickers' as \"type\""))->get();

    		$data['tesco_tsl1_orders'] = DB::table('tesco_tsl1_orders')->whereNotIn('order_status', ["Saved","Order Placed","Order Cancelled"])->whereBetween('created_at', [$from_date, $to_date])->select('*',DB::raw("'stickers' as \"type\""))->get();
        
        $data['tesco_tsl3_orders'] = DB::table('tesco_tsl3_orders')->whereNotIn('order_status', ["Saved","Order Placed","Order Cancelled"])->whereBetween('created_at', [$from_date, $to_date])->select('*',DB::raw("'stickers' as \"type\""))->get();
        
        $data['tesco_washcare_orders'] = DB::table('tesco_washcare_orders')->whereNotIn('order_status', ["Saved","Order Placed","Order Cancelled"])->whereBetween('created_at', [$from_date, $to_date])->select('*',DB::raw("'labels' as \"type\""))->get();

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
	  
        $data['tesco_tcl1_orders'] = DB::table('tesco_tcl1_orders')
										->where(function ($query) use ($style) {
											$query->where('uk_style_ref',$style)
											->orWhere('ce_style_ref',$style);
										})
										->whereNotIn('order_status', ['Saved', 'Order Cancelled'])
										->select(DB::raw('max("tesco_tcl1_orders"."id") as id'),DB::raw('max("tesco_tcl1_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_tcl1_orders.ce_style_ref','tesco_tcl1_orders.uk_style_ref','tesco_tcl1_orders.zone','tesco_tcl1_orders.po_number',DB::raw("'".$this->tcl1."'".' as "TemplateName"'),DB::raw("'".$this->tcl1_type."'".' as "type"'))->groupBy('tesco_tcl1_orders.uk_style_ref')->groupBy('tesco_tcl1_orders.ce_style_ref')->groupBy('tesco_tcl1_orders.zone')->groupBy('tesco_tcl1_orders.po_number')->get();
	  
	  
    $data['tesco_tcl2_orders'] = DB::table('tesco_tcl2_orders')
										->where(function ($query) use ($style) {
											$query->where('uk_style_ref',$style)
											->orWhere('ce_style_ref',$style);
										})
										->whereNotIn('order_status', ['Saved', 'Order Cancelled'])
										->select(DB::raw('max("tesco_tcl2_orders"."id") as id'),DB::raw('max("tesco_tcl2_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_tcl2_orders.ce_style_ref','tesco_tcl2_orders.uk_style_ref','tesco_tcl2_orders.zone','tesco_tcl2_orders.po_number',DB::raw("'".$this->tcl2."'".' as "TemplateName"'),DB::raw("'".$this->tcl2_type."'".' as "type"'))->groupBy('tesco_tcl2_orders.uk_style_ref')->groupBy('tesco_tcl2_orders.ce_style_ref')->groupBy('tesco_tcl2_orders.zone')->groupBy('tesco_tcl2_orders.po_number')->get();
	  
	  
    $data['tesco_tcl4_orders'] = DB::table('tesco_tcl4_orders')
										->where(function ($query) use ($style) {
											$query->where('uk_style_ref',$style)
											->orWhere('ce_style_ref',$style);
										})
										->whereNotIn('order_status', ['Saved', 'Order Cancelled'])
										->select(DB::raw('max("tesco_tcl4_orders"."id") as id'),DB::raw('max("tesco_tcl4_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_tcl4_orders.ce_style_ref','tesco_tcl4_orders.uk_style_ref','tesco_tcl4_orders.zone','tesco_tcl4_orders.po_number',DB::raw("'".$this->tcl4."'".' as "TemplateName"'),DB::raw("'".$this->tcl4_type."'".' as "type"'))->groupBy('tesco_tcl4_orders.uk_style_ref')->groupBy('tesco_tcl4_orders.ce_style_ref')->groupBy('tesco_tcl4_orders.zone')->groupBy('tesco_tcl4_orders.po_number')->get();
	  
	  
    $data['tesco_thl1_orders'] = DB::table('tesco_thl1_orders')
										->where(function ($query) use ($style) {
											$query->where('uk_style_ref',$style)
											->orWhere('ce_style_ref',$style);
										})
										->whereNotIn('order_status', ['Saved', 'Order Cancelled'])
										->select(DB::raw('max("tesco_thl1_orders"."id") as id'),DB::raw('max("tesco_thl1_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_thl1_orders.ce_style_ref','tesco_thl1_orders.uk_style_ref','tesco_thl1_orders.zone','tesco_thl1_orders.po_number',DB::raw("'".$this->thl1."'".' as "TemplateName"'),DB::raw("'".$this->thl1_type."'".' as "type"'))->groupBy('tesco_thl1_orders.uk_style_ref')->groupBy('tesco_thl1_orders.ce_style_ref')->groupBy('tesco_thl1_orders.zone')->groupBy('tesco_thl1_orders.po_number')->get();
	  
	  
    $data['tesco_thl3_orders'] = DB::table('tesco_thl3_orders')
										->where(function ($query) use ($style) {
											$query->where('uk_style_ref',$style)
											->orWhere('ce_style_ref',$style);
										})
										->whereNotIn('order_status', ['Saved', 'Order Cancelled'])
										->select(DB::raw('max("tesco_thl3_orders"."id") as id'),DB::raw('max("tesco_thl3_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_thl3_orders.ce_style_ref','tesco_thl3_orders.uk_style_ref','tesco_thl3_orders.zone','tesco_thl3_orders.po_number',DB::raw("'".$this->thl3."'".' as "TemplateName"'),DB::raw("'".$this->thl3_type."'".' as "type"'))->groupBy('tesco_thl3_orders.uk_style_ref')->groupBy('tesco_thl3_orders.ce_style_ref')->groupBy('tesco_thl3_orders.zone')->groupBy('tesco_thl3_orders.po_number')->get();
	  
	  
    $data['tesco_tsl1_orders'] = DB::table('tesco_tsl1_orders')
										->where(function ($query) use ($style) {
											$query->where('uk_style_ref',$style)
											->orWhere('ce_style_ref',$style);
										})
										->whereNotIn('order_status', ['Saved', 'Order Cancelled'])
										->select(DB::raw('max("tesco_tsl1_orders"."id") as id'),DB::raw('max("tesco_tsl1_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_tsl1_orders.ce_style_ref','tesco_tsl1_orders.uk_style_ref','tesco_tsl1_orders.zone','tesco_tsl1_orders.po_number',DB::raw("'".$this->tsl1."'".' as "TemplateName"'),DB::raw("'".$this->tsl1_type."'".' as "type"'))->groupBy('tesco_tsl1_orders.uk_style_ref')->groupBy('tesco_tsl1_orders.ce_style_ref')->groupBy('tesco_tsl1_orders.zone')->groupBy('tesco_tsl1_orders.po_number')->get();
	  
	  
    $data['tesco_tsl3_orders'] = DB::table('tesco_tsl3_orders')
										->where(function ($query) use ($style) {
											$query->where('uk_style_ref',$style)
											->orWhere('ce_style_ref',$style);
										})
										->whereNotIn('order_status', ['Saved', 'Order Cancelled'])
                    ->select(DB::raw('max("tesco_tsl3_orders"."id") as id'),DB::raw('max("tesco_tsl3_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_tsl3_orders.ce_style_ref','tesco_tsl3_orders.uk_style_ref','tesco_tsl3_orders.zone','tesco_tsl3_orders.po_number',DB::raw("'".$this->tsl3."'".' as "TemplateName"'),DB::raw("'".$this->tsl3_type."'".' as "type"'))->groupBy('tesco_tsl3_orders.uk_style_ref')->groupBy('tesco_tsl3_orders.ce_style_ref')->groupBy('tesco_tsl3_orders.zone')->groupBy('tesco_tsl3_orders.po_number')->get();
                    
    $data['tesco_washcare_orders'] = DB::table('tesco_washcare_orders')
                    ->join('tesco_template','tesco_washcare_orders.template_id','=','tesco_template.item_id')
										->where(function ($query) use ($style) {
                      $style_split = explode("/",$style);
                      if(count($style_split) > 1)
                      {
                        $query->where('uk_style_ref',$style_split[0])
                        ->where('ce_style_ref',$style_split[1]);
                      }
										})
										->whereNotIn('order_status', ['Saved', 'Order Cancelled'])
                    ->select(DB::raw('max("tesco_washcare_orders"."id") as id'),DB::raw('max("tesco_washcare_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_washcare_orders.ce_style_ref','tesco_washcare_orders.uk_style_ref','tesco_washcare_orders.zone','tesco_washcare_orders.po_number',DB::raw('tesco_template.template_name as "TemplateName"'),DB::raw("'".$this->tesco_washcare_orders_type."'".' as "type"'))->groupBy('tesco_washcare_orders.uk_style_ref')->groupBy('tesco_washcare_orders.ce_style_ref')->groupBy('tesco_washcare_orders.zone')->groupBy('tesco_washcare_orders.po_number','tesco_template.template_name')->get();
                    
	  
	 
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

		$data['tesco_tcl4_orders'] = DB::table('tesco_tcl4_orders')
										->where(function ($query) use ($style) {
											$query->where('uk_style_ref',$style)
											->orWhere('ce_style_ref',$style);
										})
										->whereNotIn('order_status', ['Saved', 'Order Cancelled'])
										->select(DB::raw('max("tesco_tcl4_orders"."id") as id'),DB::raw('max("tesco_tcl4_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_tcl4_orders.ce_style_ref','tesco_tcl4_orders.uk_style_ref','tesco_tcl4_orders.zone','tesco_tcl4_orders.po_number',DB::raw("'".$this->tcl4."'".' as "TemplateName"'),DB::raw("'".$this->tcl4_type."'".' as "type"'))->groupBy('tesco_tcl4_orders.uk_style_ref')->groupBy('tesco_tcl4_orders.ce_style_ref')->groupBy('tesco_tcl4_orders.zone')->groupBy('tesco_tcl4_orders.po_number')->get();


		$data['tesco_thl3_orders'] = DB::table('tesco_thl3_orders')
										->where(function ($query) use ($style) {
											$query->where('uk_style_ref',$style)
											->orWhere('ce_style_ref',$style);
										})
										->whereNotIn('order_status', ['Saved', 'Order Cancelled'])
										->select(DB::raw('max("tesco_thl3_orders"."id") as id'),DB::raw('max("tesco_thl3_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_thl3_orders.ce_style_ref','tesco_thl3_orders.uk_style_ref','tesco_thl3_orders.zone','tesco_thl3_orders.po_number',DB::raw("'".$this->thl3."'".' as "TemplateName"'),DB::raw("'".$this->thl3_type."'".' as "type"'))->groupBy('tesco_thl3_orders.uk_style_ref')->groupBy('tesco_thl3_orders.ce_style_ref')->groupBy('tesco_thl3_orders.zone')->groupBy('tesco_thl3_orders.po_number')->get();



		$data['tesco_tsl3_orders'] = DB::table('tesco_tsl3_orders')
										->where(function ($query) use ($style) {
											$query->where('uk_style_ref',$style)
											->orWhere('ce_style_ref',$style);
										})
										->whereNotIn('order_status', ['Saved', 'Order Cancelled'])
                    ->select(DB::raw('max("tesco_tsl3_orders"."id") as id'),DB::raw('max("tesco_tsl3_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_tsl3_orders.ce_style_ref','tesco_tsl3_orders.uk_style_ref','tesco_tsl3_orders.zone','tesco_tsl3_orders.po_number',DB::raw("'".$this->tsl3."'".' as "TemplateName"'),DB::raw("'".$this->tsl3_type."'".' as "type"'))->groupBy('tesco_tsl3_orders.uk_style_ref')->groupBy('tesco_tsl3_orders.ce_style_ref')->groupBy('tesco_tsl3_orders.zone')->groupBy('tesco_tsl3_orders.po_number')->get();
                    
    $data['tesco_washcare_orders'] = DB::table('tesco_washcare_orders')
                    ->join('tesco_template','tesco_washcare_orders.template_id','=','tesco_template.item_id')
										->where(function ($query) use ($style) {
                      $style_split = explode("/",$style);
                      if(count($style_split) > 1)
                      {
                        $query->where('uk_style_ref',$style_split[0])
                        ->where('ce_style_ref',$style_split[1]);
                      }
										})
										->whereNotIn('order_status', ['Saved', 'Order Cancelled'])
                    ->select(DB::raw('max("tesco_washcare_orders"."id") as id'),DB::raw('max("tesco_washcare_orders"."is_order_count_empty") as is_order_count_empty'),'tesco_washcare_orders.ce_style_ref','tesco_washcare_orders.uk_style_ref','tesco_washcare_orders.zone','tesco_washcare_orders.po_number',DB::raw('tesco_template.template_name as "TemplateName"'),DB::raw("'".$this->tesco_washcare_orders_type."'".' as "type"'))->groupBy('tesco_washcare_orders.uk_style_ref')->groupBy('tesco_washcare_orders.ce_style_ref')->groupBy('tesco_washcare_orders.zone')->groupBy('tesco_washcare_orders.po_number','tesco_template.template_name')->get();


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
	  
	  $data['tesco_item_master']=DB::table('tesco_item_master')->where('id',$data['template']{0}->template_id)->select('*')->get(); 
	  
	  $data['moq']=DB::table('price_taxation')->where('template_id',$data['template']{0}->template_id)->where('customer_id',$data['template']{0}->tesco_supplier_id)->where('buyer_id',2)->select('*')->get();
	  
     
          echo json_encode($data);
    }

    public function extraQuantityLabelTescoRepeatOrder(Request $request)
    {
      

      // dd($caremeta->getCareMetadata()['symbols']);

      $type         =   $request->type;
      $id           =   $request->id;
      $type_id      =   explode('_',$type);
      
      $data['qty']=DB::table($type.'_quanty')->where($type_id[1].'_order_id',$id)->select('*')->get();

      $data['orders']=DB::table('tesco_washcare_orders')->where('id',$id)->select('*')->get();
    
      $data['template']=DB::table($type)
                        ->join('tesco_suppliers','tesco_suppliers.id','=',$type.'.tesco_metadata_supplier_id')
                        ->where($type.'.id',$id)
                        ->select($type.'.*','tesco_suppliers.supplier_name')->get();
    
      $data['template_file']=DB::table('tesco_template')->where('item_id',$data['template']{0}->template_id)->select('*')->get();     
    
      $data['tesco_item_master']=DB::table('tesco_item_master')->where('id',$data['template']{0}->template_id)->select('*')->get(); 
      
      $data['moq']=DB::table('price_taxation')->where('template_id',$data['template']{0}->template_id)->where('customer_id',$data['template']{0}->tesco_supplier_id)->where('buyer_id',2)->select('*')->get();

      $size_category_json = json_decode($data['qty']{0}->selected_secondary_sizes);

      $size_category =array();
      $data['size_category_text'] =array();
      foreach($size_category_json as $sizes)
      {
        if($sizes->id != "" && $sizes->id != null)
        {
          array_push($size_category,$sizes->id);
          array_push($data['size_category_text'],$sizes->secondary_size);
        }
        
      }


      $data['tesco_sizelists'] = TescoPrimarySize::with(['secSizeChart' => function ($query) use ($size_category) {
          $query->whereIn('tesco_secondary_size_chart.tesco_secondary_size_category_id', $size_category);
      }])
        ->where('tesco_primary_size.tesco_size_department_id', $data['orders']{0}->primary_size_id)
        ->orderBy('tesco_primary_size.id')
        ->get();

        // dd($size_category,$data['tesco_sizelists']);
    
      $data['composition'] =  TescoWashcareOrdersComponentMapping::with('fibers')
            ->where('washcare_order_id',$request->id)
            ->get();
            // ->with('quantities')
            // ->with('garment_components')
            // ->get();
      echo json_encode($data);
    }

    public function extraQuantityLabelTesco(Request $request)
    {
      $type         =   $request->type;
      $id           =   $request->id;
      $type_id      =   explode('_',$type);
      
      $data['qty']=DB::table($type.'_quanty')->where($type_id[1].'_order_id',$id)->select('*')->get();

      $data['orders']=DB::table('tesco_washcare_orders')->where('id',$id)->select('*')->get();
    
      $data['template']=DB::table($type)
                        ->join('tesco_suppliers','tesco_suppliers.id','=',$type.'.tesco_metadata_supplier_id')
                        ->where($type.'.id',$id)
                        ->select($type.'.*','tesco_suppliers.supplier_name')->get();
    
      $data['template_file']=DB::table('tesco_template')->where('item_id',$data['template']{0}->template_id)->select('*')->get();     
	  
      $data['tesco_item_master']=DB::table('tesco_item_master')->where('id',$data['template']{0}->template_id)->select('*')->get(); 
      
      $data['moq']=DB::table('price_taxation')->where('template_id',$data['template']{0}->template_id)->where('customer_id',$data['template']{0}->tesco_supplier_id)->where('buyer_id',2)->select('*')->get();

      $size_category_json = json_decode($data['qty']{0}->selected_secondary_sizes);

      $size_category =array();
      $data['size_category_text'] =array();
      foreach($size_category_json as $sizes)
      {
        if($sizes->id != "" && $sizes->id != null)
        {
          array_push($size_category,$sizes->id);
          array_push($data['size_category_text'],$sizes->secondary_size);
        }
        
      }


      $data['tesco_sizelists'] = TescoPrimarySize::with(['secSizeChart' => function ($query) use ($size_category) {
          $query->whereIn('tesco_secondary_size_chart.tesco_secondary_size_category_id', $size_category);
      }])
        ->where('tesco_primary_size.tesco_size_department_id', $data['orders']{0}->primary_size_id)
        ->orderBy('tesco_primary_size.id')
        ->get();
	  
     
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
        $newTasks->total_qty = array_sum($counts);
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
        $newTasks->total_qty = array_sum($counts);
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
        $newTasks->total_qty = array_sum($counts);
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
        $newTasks->total_qty = array_sum($counts);
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
        $newTasks->total_qty = array_sum($counts);
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
        $newTasks->total_qty = array_sum($counts);
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
        $newTasks->total_qty = array_sum($counts);
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


      if($type == "tesco_washcare_orders")
			{
				$tasks = TescoWashcareOrders::find($id);
				$newTasks = $tasks->replicate(['order_id']);
        $newTasks->save();
        
        $template_data = DB::table('tesco_item_master')
        ->join('tesco_template', 'tesco_item_master.id', 'tesco_template.item_id')
        ->join('reports_template_level_name', 'reports_template_level_name.id', 'tesco_template.name_in_reports')
        ->where('tesco_item_master.id', $tasks->template_id)
        ->select('tesco_item_master.item_name', 'reports_template_level_name.name as name_in_reports', 'tesco_item_master.company_id')
        ->first();

        $max_id = TescoWashcareOrders::max('id');

        $order_id = $template_data->company_id == 1?"JGA_".str_replace(" ","",$template_data->item_name)."00".($max_id+1):"SP_".str_replace(" ","",$template_data->item_name)."00".($max_id+1);

        $newTasks->order_id = $order_id;
        $newTasks->save();
				
				$factory=DB::table('tesco_washcare_orders')->join('customers','customers.id','=','tesco_washcare_orders.tesco_supplier_id')->where('tesco_washcare_orders.id',$id)->select('*')->get();
				
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
        $newTasks->total_qty = array_sum($counts);
				$newTasks->save();
				
				$data['tesco']=DB::table('tesco_washcare_orders_quanty')->where('washcare_order_id',$id)->select('*')->get();
				$i=0;
				foreach($data['tesco'] as $value)
				{
					$tasks = TescoWashcareOrdersQuantity::find($value->id);
					$newTask = $tasks->replicate();
					$newTask->save();
					$newTask->washcare_order_id = $newTasks->id;
					$newTask->quantity = $counts[$i];
					$newTask->save();
					$i++;
        }
        
        $data['tesco_component']=DB::table('tesco_washcare_orders_component_mapping')->where('washcare_order_id',$id)->select('*')->get();
				foreach($data['tesco_component'] as $value)
				{
					$tasks = TescoWashcareOrdersComponentMapping::find($value->id);
					$newTas = $tasks->replicate();
					$newTas->save();
					$newTas->washcare_order_id = $newTasks->id;
          $newTas->save();
          
          $data['tesco_fibre']=DB::table('tesco_washcare_orders_component_fibre_mapping')->where('washcare_component_id',$tasks->id)->select('*')->get();
          foreach($data['tesco_fibre'] as $tesco_fibre)
				  {
            $tasks = TescoWashcareOrdersComponentFibreMapping::find($tesco_fibre->id);
            $newTa = $tasks->replicate();
            $newTa->save();
            $newTa->washcare_component_id = $newTas->id;
            $newTa->save();
          }
        }
        
      }
      
      /** Insert order data into history table */
      $order_data = [];

      $style_ref = strtolower($newTasks->zone).'_style_ref';

      $order_data['season_code'] = $newTasks->season_code;

      $order_data['po_number'] = $newTasks->po_number;

      $order_data['ocn_no'] = $newTasks->ocn_number;

      $order_data['style'] = $newTasks->$style_ref;

      $order_data['customer_id'] = $newTasks->tesco_supplier_id;

      $order_data['template_id'] = $newTasks->template_id;

      $order_data['order_id'] = $newTasks->id;

      $order_data['po_status'] = $po_status;

      $order_data['pi_status'] = $pi_status;

      $order_data['order_status_id'] = 2;

      $order_data['edit_permission'] = 0;

      $order_data['tesco_label'] = false;

      $this->order_history_obj->storeOrdersHistory($order_data);

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
          $newTasks->total_qty = array_sum($counts);
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
        $newTasks->total_qty = array_sum($counts);
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
        $newTasks->total_qty = array_sum($counts);
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
        $newTasks->total_qty = array_sum($counts);
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
        $newTasks->total_qty = array_sum($counts);
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
        $newTasks->total_qty = array_sum($counts);
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
		  	//	$newTasks->ocn_number =$ocn_number;
		  	//	$newTasks->manufacture_date =$manufacture_date;
				$newTasks->purchase_order_no = "";
				$newTasks->po_file = null;
        $newTasks->order_flag = "repeatOrder";
        $newTasks->total_qty = array_sum($counts);
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
      
      /** Insert order data into history table */
      $order_data = [];

      $style_ref = strtolower($newTasks->zone).'_style_ref';

      $order_data['season_code'] = $newTasks->season_code;

      $order_data['po_number'] = $po;

      $order_data['ocn_no'] = $ocn_number;

      $order_data['style'] = $newTasks->$style_ref;

      $order_data['customer_id'] = $newTasks->tesco_supplier_id;

      $order_data['template_id'] = $newTasks->template_id;

      $order_data['order_id'] = $newTasks->id;

      $order_data['po_status'] = $po_status;

      $order_data['pi_status'] = $pi_status;

      $order_data['order_status_id'] = 2;

      $order_data['edit_permission'] = 0;
      
      $order_data['tesco_label'] = false;

      $this->order_history_obj->storeOrdersHistory($order_data);

		    echo $data;
    }
    /**
     * Function to upload combined buyer po file for tesco
     * @author <Praveen>
     * @return boolean true or false
     */
    public function uploadCombinedOrdersPO(Request $request)
    {
      try {
         $erp_obj        = new ErpOrders();
         $updated_erp_table_array = [];
         $template_ids  = explode(',',$request['template_id']);
         $template      = explode(',',$request['templates']);
        
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
                $tesco_tcl1_orders = DB::table('tesco_tcl1_orders')->where('po_number',$request->po_number)->where($request->zone.'_style_ref',$request->style_number)->where('order_flag','newOrder')->where('order_status','!=', 'Order Cancelled')->select('*',DB::raw("'".$this->tcl1."'".' as "TemplateName"'),DB::raw("'".$this->tcl1_type."'".' as "type"'))->first();
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
        } else {

            $data['order_details']  = TescoWashcareOrders::with([
              'quantities',
              'garment_components'
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
              $qr_code          = explode('|',$request['qr_code']);
              $templates        = $request['templates'];
              $last_insertions  = [];
              $already_exists   = [];

              /** Check if supplier value empty or not */
              if($request['supplier'])
              {
                $tesco_main_customer_id     = DB::table("customer_metadata")->where('customer_metadata_id','=',$request['supplier'])->select('customer_id')->first();
                /** [Get comapanies super admin id as created by customer id] */
                $customer_profile_id = DB::table("customer_profile")->where('company_id','=',$tesco_main_customer_id->customer_id)->where('role_id','=',1)->select('id')->first();
                /** Check if super admin exists or not  */
                if($customer_profile_id)
                {
                  $created_by = $customer_profile_id->id;
                }
                else
                {
                  $created_by = 0;
                }
                
              }
              else
              {
                $created_by = 0;
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
            return response()->json(['error'=> false,'message' =>'success','last_insertions' => $last_insertions,'already_exists' => $already_exists],200);
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
            $qr_code          = explode('|',$request['qr_code']);
            $templates        = $request['templates'];
            $last_insertions  = [];
            $already_exists   = [];

            /** Check if supplier value empty or not */
            if($request['supplier'])
            {
              $tesco_main_customer_id     = DB::table("customer_metadata")->where('customer_metadata_id','=',$request['supplier'])->select('customer_id')->first();
              /** [Get comapanies super admin id as created by customer id] */
              $customer_profile_id = DB::table("customer_profile")->where('company_id','=',$tesco_main_customer_id->customer_id)->where('role_id','=',1)->select('id')->first();
              /** Check if super admin exists or not  */
              if($customer_profile_id)
              {
                $created_by = $customer_profile_id->id;
              }
              else
              {
                $created_by = 0;
              }
            }
            else
            {
              $created_by = 0;
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

            /** Check if supplier value empty or not */
            if($request['supplier'])
            {
              $tesco_main_customer_id     = DB::table("customer_metadata")->where('customer_metadata_id','=',$request['supplier'])->select('customer_id')->first();
              /** [Get comapanies super admin id as created by customer id] */
              $customer_profile_id = DB::table("customer_profile")->where('company_id','=',$tesco_main_customer_id->customer_id)->where('role_id','=',1)->select('id')->first();
              /** Check if super admin exists or not  */
              if($customer_profile_id)
              {
                $created_by = $customer_profile_id->id;
              }
              else
              {
                $created_by = 0;
              }
            }
            else
            {
              $created_by = 0;
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

            /** Check if supplier value empty or not */
            if($request['supplier'])
            {
              $tesco_main_customer_id     = DB::table("customer_metadata")->where('customer_metadata_id','=',$request['supplier'])->select('customer_id')->first();
              /** [Get comapanies super admin id as created by customer id] */
              $customer_profile_id = DB::table("customer_profile")->where('company_id','=',$tesco_main_customer_id->customer_id)->where('role_id','=',1)->select('id')->first();
              /** Check if super admin exists or not  */
              if($customer_profile_id)
              {
                $created_by = $customer_profile_id->id;
              }
              else
              {
                $created_by = 0;
              }
            }
            else
            {
              $created_by = 0;
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
     * Function to get tesco template minimum order quantity value based on customer selected.
     * @author <Praveen>
     * @param [array] $[templates] [<templates ids>], [string] $[supplier] [<supplier id>],[string] $[buyer_id] [<buyer id>]
     * @return equivalent moq value array
     */
    public function getTescoTemplateMoq(Request $request)
    {
       try {
             $templates = explode(',',$request['templates']);
             $supplier  = $request['supplier'];
             $buyer_id  = $request['buyer_id'];

             /**  [Get parent customer id from tesco_suppliers metadata id] */
             $customer_id = DB::table("customer_metadata")->where('customer_metadata_id','=',$request['supplier'])->select('customer_id')->first();
             $moq_array = array();
             $moq_value = array();
             foreach ($templates as $template_id) 
             {
                $temp_name = DB::table('tesco_item_master')
                         ->select('tesco_item_master.item_name')
                         ->where('id','=',$template_id)
                         ->first();
                $moq = DB::table('price_taxation')
                         ->select('price_taxation.moq')
                         ->where('template_id','=',$template_id)
                         ->where('customer_id','=',$customer_id->customer_id)
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
           
           return response()->json(['error'=> false,'message' =>'success','moq' => $moq_array]);
       } catch (\Exception $e) {
           return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
       }
    } 
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
	
	 /**
     * This Function is used to generate barcode using java.
     *
     * @return path of barcode
     */
  	public function generateBarcodeTsl1(Request $request)
    {
		$name=$request['name'];
		$type=$request['type'];
		$barcode=$request['barcode'];
		
		$tabula       = Config::get('tabula.bar_jar');
		
		$info    = shell_exec('java -jar '.$tabula.' '.$barcode.' '.public_path('/storage/app/template/'.$name.'.png').' '.$type.' 33 13');
        $info    = json_decode($info, true);
		return $info;
    }
	/**
     * This Function is used to generate barcode using java.
     *
     * @return path of barcode
     */
  	public function generateBarcodeTsl3(Request $request)
    {
		$name=$request['name'];
		$type=$request['type'];
		$barcode=$request['barcode'];
		
		$tabula       = Config::get('tabula.bar_jar');
		
		$info    = shell_exec('java -jar '.$tabula.' '.$barcode.' '.public_path('/storage/app/template/'.$name.'.png').' '.$type.' 33 13');
        $info    = json_decode($info, true);
		return $info;
    }
	/**
     * This Function is used to generate barcode using java.
     *
     * @return path of barcode
     */
  	public function generateBarcodeEanBig(Request $request)
    {
		$name=$request['name'];
		$type=$request['type'];
		$barcode=$request['barcode'];
		
		$tabula       = Config::get('tabula.bar_jar_big');
		
		$info    = shell_exec('java -jar '.$tabula.' '.$barcode.' '.public_path('/storage/app/template/'.$name.'.png').' '.$type.' 50 16');
        $info    = json_decode($info, true);
		return $info;
    }
	/**
     * This Function is used to generate barcode using java.
     *
     * @return path of barcode
     */
  	public function generateBarcodeItfBig(Request $request)
    {
		$name=$request['name'];
		$type=$request['type'];
		$barcode=$request['barcode'];
		
		$tabula       = Config::get('tabula.bar_jar_big');
		
		$info    = shell_exec('java -jar '.$tabula.' '.$barcode.' '.public_path('/storage/app/template/'.$name.'.png').' '.$type.' 50 16');
        $info    = json_decode($info, true);
		return $info;
    }
	public function pdfboxDivTescoSmall(Request $request)
    {
		$name=$request['name'];
		$svg=$request['svg'];
		
		$tabula       = Config::get('tabula.pdf_jar');
	
		
		$info    = shell_exec('java -jar '.$tabula.' "'.$svg.'" '.public_path('/storage/app/template/pdfbox/'.$name.'.pdf').' 40 30 true');
        $info    = json_decode($info, true);
		
		return $info;
    }
	
/*	public function pdfboxDivTescoSmall(Request $request)
   {
		$name=$request['name'];
		$svg=$request['svg'];
		
		$tabula       = Config::get('tabula.pdf_jar');
		
		$svgarr = explode(',_',$request->svg);
   	$j=0;
		$pdfbox=array();
		foreach($svgarr as $value)
		{
			array_push($pdfbox,'/storage/app/template/demo_pdf_box_'.$j.'.svg');
			$svg=urldecode($value);
			file_put_contents(public_path('/storage/app/template/demo_pdf_box_'.$j.'.svg'), '<?xml version="1.0" encoding="utf-8"?>');
			file_put_contents(public_path('/storage/app/template/demo_pdf_box_'.$j.'.svg'), $svg);
			$j++;
		}
		$string = implode(",_",$pdfbox);
		print_r($string);
		
		$info    = shell_exec('java -jar '.$tabula.' "'.$string.'" '.public_path('/storage/app/template/pdfbox/'.$name.'.pdf').' 40 30 true');
       $info    = json_decode($info, true);
		
		return $info;
   }*/
}
