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
use App\Classes\TescoOrders;

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
                           ->select('tesco_item_master.id','tesco_template.template_name','tesco_template.template_file','company.name as company')
                           ->join('tesco_item_master','tesco_template.item_id','=','tesco_item_master.id')
                           ->join('company','company.id','=','tesco_item_master.company_id')
                           ->where('tesco_item_master.id','=',$value)
                           ->first();
           }

           /* Fetch tesco brands,departments and suppliers details */
           $tesco_brand = DB::table('tesco_brand')->select('brand','id')->get();
           $tesco_department = DB::table('tesco_department')->select('department','id')->get();
           $tesco_suppliers = DB::table('customers')->select('company_name','id')->where('is_active','=','1')->get();
           $tesco_size = DB::table('tesco_size_chart')->select('po_size','id')->get();
           
           return response()->json(['templates' => $template_details,'tesco_brand' => $tesco_brand,'tesco_department' => $tesco_department,'tesco_suppliers' => $tesco_suppliers,'tesco_size' => $tesco_size]);
       } catch (\Exception $e) {
           return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
       }
    }
    /**
     * Function to get tesco order page
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function Orders()
    {
            echo json_encode("tesco");
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
     * Method to create tesco order from purchase order using Tabula-Java
     * @return \Illuminate\Http\Response
     */
    public function createOrderByTabula(Request $request) {
      try {
        //Check the file has been uploaded or not
        if($request->hasFile('purchase_order')) {
          $file = $request->file('purchase_order');
          $name = $file->getClientOriginalName();
          $path = $file->move('storage/app/TESCO/purchaseOrder', $name);
          //Check the type of PO
          $po_type = $this->checkPOType($path);
          $data = '';
          switch ($po_type) {
            case 'SINGLE':
              $data = $this->cropPDF($path, 'single');
              break;
            case 'RATIO PACK  A':
              $data = $this->cropPDF($path, 'ratio');
              break; 
            case 'RATIO PACK  A PACK  B':
              $data = $this->cropPDF($path);
              break; 
            case 'SINGLES':
              $data = $this->cropPDF($path);
              break;
            
            default:
              return response()->json(['error' => 'Unknown PO Type']);
              break;
          }
          
          return response()->json(['details' =>$data, 'type' => $po_type]);
        } else {
          return "File not found!!";
        }
      } catch (\Exception $e) {
        return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
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
        $type = $type[0]['data'][0][0]['text'];
        return $type;
    }

    /**
     * Method to get the appropriate dimensions from the PDF
     * @param  [path] $file File to be cropped
     * @param  [str] $type Purchase Order type
     * @return [str]       JSON extracted string
     */
    public function cropPDF($file, $type) {
      //Read the tabula configuration from config/tabula.php.
      $tabula      = Config::get('tabula.tabula_jar');
      $quantity    = Config::get('tabula.'.$type.'_quantity');
      $information = Config::get('tabula.'.$type.'_info');
      //Execute tabula method to extract the quantity table.
      $info    = shell_exec('java -jar '.$tabula.$information.$file);
      $table   = shell_exec('java -jar '.$tabula.$quantity.$file);
      return ['info' => $info, 'table' => $table];
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
            // $tesco_order     = $tesco_order_obj->createTescoOrder($request);
            // $tesco_order_id  = $tesco_order->id; 

            
            $orms_tpn         = explode(',',$request['orms_tpn']);
            $itf_code         = explode(',',$request['itf_code']);
            $cases_per_carton = explode(',',$request['cases_per_carton']);
            $templates        = $request['templates'];
            foreach ($templates as $template) 
            {
              if($template['template_name'] != "THL 1")
              {
                
                $count_TCL1       = explode(',',$request['count_TCL1']);
                $count_TCL2       = explode(',',$request['count_TCL2']);
                $count_TSL1       = explode(',',$request['count_TSL1']);
                if($template['template_name'] != "TCL 1")
                {
                   $tesco_order     = $tesco_order_obj->createTCL1oOrder($request);
                   $tesco_order_id  = $tesco_order->id;
                   $result = TescoTCL1OrdersQuantity::create([
                          'order_id'             => $order_id,
                          'zone'                 => $request['zone'],
                          'template_type'        => $request['type'],
                          'po_number'            => $request['po_number'],
                          'uk_style_ref'         => $request['uk_style'],
                          'ce_style_ref'         => $request['ce_style'],
                          'season_code'          => $request['season_code'],
                          'short_description'    => $request['description'],
                          'eqos_site_code'       => $request['eqos'],
                          'manufacture_date'     => $request['manufacture_date'],
                          'tesco_department_id'  => $request['department'],
                          'tesco_brand_id'       => $request['brand'],
                          'tesco_supplier_id'    => $request['supplier'],
                          'rfid_compliant'       => $request['rfid'],
                          'tag_at_source'        => $request['tag_at_source'],
                          'order_status'         => "Submitted",
                          'artwork_status'       => "Submitted",
                          'print_status'         => "Submitted"
                        ]); 
                }
                else if($template['template_name'] != "TCL 2")
                {

                }
                else
                {

                }
              }
              else
              {
                $count_THL1       = explode(',',$request['count_THL1']);
                return $count_THL1;
              }
            }
            
            
            // if($inserted != '')
            // {
            //   $result = response()->json(['error'=> false,'message' =>'success'],200);
            // }
            // else
            // {
            //   $result = response()->json(['error'=> true,'message' =>'Error'],400);
            // }

        } catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        // return $result;
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
            // $tesco_order_obj = new TescoOrders();
            // $tesco_order = $tesco_order_obj->createTescoOrder($request);
            // $tesco_order_id = $tesco_order->id;  
            
            $counts       = $request['counts'];
            $templates    = $request['templates'];
            $quantity     = $request['quantity'];

            return $templates[0];
            foreach($quantity as $value)
            {
                // $size_details = TescoStickerOrdersSizeDetails::create([
                //                 'tesco_order_id'   => $tesco_order_id,
                //                 'orms_tpn'         => $value['orms_tpn'],
                //                 'ean_barcode'      => $value['ean_code'],
                //                 'case_size'        => $value['case_size'],
                //                 'size'             => $value['size']
                //             ]);
                // $size_table_id = $size_details->id;
                // for ($i=0; $i < ; $i++) 
                // { 
                // }
                // $i = 0;
                // foreach ($counts as $count) 
                // {
                //   $count_insert = TescoStickerOrdersCount::create([
                //               'tesco_order_id'   => $size_table_id,
                //               'template_name'    => $templates[$i],
                //               'count'            => $count,
                //               'order_status'     => "Submitted",
                //               'artwork_status'   => "Submitted",
                //               'print_status'     => "Submitted"
                //             ]);
                //   $i++;
                // }
            }
            
            $result = response()->json(['error'=> false,'message' =>'success'],200);

        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    /**
     * Store UK ratio  templates orders
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function setUKRatioOrders(Request $request)
    {
        try {
            /** [ Creating new object for TescoOrders() class ] */
            $tesco_order_obj = new TescoOrders();
            $tesco_order = $tesco_order_obj->createTescoOrder($request);
            $tesco_order_id = $tesco_order->id;  
            $inserted = TescoStickerOrdersSizeDetails::create([
                          'tesco_order_id'   => $tesco_order_id,
                          'tpnd'             => $request['tpnd'],
                          'itf_barcode'      => $request['itf_code'],
                          'units_per_carton' => $request['units_per_carton'],
                          'tcl1_count'       => $request['ratio_tcl1_count'],
                          'tcl2_count'       => $request['ratio_tcl2_count'],
                          'tsl1_count'       => $request['ratio_tsl1_count'],
                          'order_status'     => "Submitted",
                          'artwork_status'   => "Submitted",
                          'print_status'     => "Submitted"
                        ]);
            if($inserted != '')
            {
              $result = response()->json(['error'=> false,'message' =>'success'],200);
            }
            else
            {
              $result = response()->json(['error'=> true,'message' =>'Error'],400);
            }

        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    /**
     * Store UK single  templates orders
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function setUKSingleOrders(Request $request)
    {
        try {
            /** [ Creating new object for TescoOrders() class ] */
            $tesco_order_obj = new TescoOrders();
            $tesco_order = $tesco_order_obj->createTescoOrder($request);
            $tesco_order_id = $tesco_order->id; 
            
            $tpnd                = explode(',',$request['tpnd']);
            $ean_code            = explode(',',$request['ean_code']);
            $case_size           = explode(',',$request['case_size']);
            $size                = explode(',',$request['size']);
            $single_tcl4_count   = explode(',',$request['single_tcl4_count']);
            $single_tsl3_count   = explode(',',$request['single_tsl3_count']);

            $i = 0;
            foreach($tpnd as $value)
            {
                TescoStickerOrdersSizeDetails::create([
                    'tesco_order_id'   => $tesco_order_id,
                    'tpnd'             => $value,
                    'ean_barcode'      => $ean_code[$i],
                    'case_size'        => $case_size[$i],
                    'size'             => $size[$i],
                    'tcl4_count'       => $single_tcl4_count[$i],
                    'tsl3_count'       => $single_tsl3_count[$i],
                    'order_status'     => "Submitted",
                    'artwork_status'   => "Submitted",
                    'print_status'     => "Submitted"
                ]);
                $i++;
            }
            
            $result = response()->json(['error'=> false,'message' =>'success'],200);

        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
}
