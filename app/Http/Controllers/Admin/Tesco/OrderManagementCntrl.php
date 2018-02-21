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
              //Check for Ratio Pack B
              $packB = $this->checkPackB($path);
              $data  = $this->cropPDF($path, 'ratio');
              if($packB) {
                $data['ratioPackB'] = $packB;
              }
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
     * Method to check Ratio Pack B exist or not
     * @param  [path] $file File to be cropped
     * @return [str]       JSON extracted string
     */
    public function checkPackB($file) {
      //Read the tabula configuration from config/tabula.php.
      $tabula      = Config::get('tabula.tabula_jar');
      $check_packB = Config::get('tabula.check_packB');
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
            foreach ($templates as $template) 
            {
              if($template['template_name'] != "THL 1")
              {
                
                $count_TCL1       = explode(',',$request['count_TCL1']);
                $count_TCL2       = explode(',',$request['count_TCL2']);
                $count_TSL1       = explode(',',$request['count_TSL1']);
                if($template['template_name'] == "TCL 1")
                {
                   $tesco_order     = $tesco_order_obj->createTCL1oOrder($request);
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
                }
                else if($template['template_name'] == "TCL 2")
                {
                   $tesco_order     = $tesco_order_obj->createTCL2oOrder($request);
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
                }
                else
                {
                   $tesco_order     = $tesco_order_obj->createTSL1oOrder($request);
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
                }
              }
              else
              {
                 $count_THL1       = explode(',',$request['count_THL1']);
                 $tesco_order     = $tesco_order_obj->createTHL1oOrder($request);
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
              }
            }
            
           return response()->json(['error'=> false,'message' =>'success'],200);

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
            foreach ($templates as $template) 
            {
              if($template['template_name'] != "THL 3")
              {
                
                $count_TCL4       = explode(',',$request['count_TCL4']);
                $count_TSL3       = explode(',',$request['count_TSL3']);
                
                if($template['template_name'] == "TCL 4")
                {
                   $tesco_order     = $tesco_order_obj->createTCL4oOrder($request);
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
                }
                else
                {
                   $tesco_order     = $tesco_order_obj->createTSL3oOrder($request);
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
                }
              }
              else
              {
                 $count_THL3       = explode(',',$request['count_THL3']);
                 $tesco_order     = $tesco_order_obj->createTHL3oOrder($request);
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
              }
            }
            
           return response()->json(['error'=> false,'message' =>'success'],200);

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
            foreach ($templates as $template) 
            {
              if($template['template_name'] != "THL 1")
              {
                
                $count_TCL1       = explode(',',$request['count_TCL1']);
                $count_TCL2       = explode(',',$request['count_TCL2']);
                $count_TSL1       = explode(',',$request['count_TSL1']);
                if($template['template_name'] == "TCL 1")
                {
                   $tesco_order     = $tesco_order_obj->createTCL1oOrder($request);
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
                }
                else if($template['template_name'] == "TCL 2")
                {
                   $tesco_order     = $tesco_order_obj->createTCL2oOrder($request);
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
                }
                else
                {
                   $tesco_order     = $tesco_order_obj->createTSL1oOrder($request);
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
                }
              }
              else
              {
                 $count_THL1       = explode(',',$request['count_THL1']);
                 $tesco_order     = $tesco_order_obj->createTHL1oOrder($request);
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
              }
            }
            
           return response()->json(['error'=> false,'message' =>'success'],200);

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
            foreach ($templates as $template) 
            {
              if($template['template_name'] != "THL 3")
              {
                
                $count_TCL4       = explode(',',$request['count_TCL4']);
                $count_TSL3       = explode(',',$request['count_TSL3']);
                
                if($template['template_name'] == "TCL 4")
                {
                   $tesco_order     = $tesco_order_obj->createTCL4oOrder($request);
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
                }
                else
                {
                   $tesco_order     = $tesco_order_obj->createTSL3oOrder($request);
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
                }
              }
              else
              {
                 $count_THL3       = explode(',',$request['count_THL3']);
                 $tesco_order     = $tesco_order_obj->createTHL3oOrder($request);
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
              }
            }
            
           return response()->json(['error'=> false,'message' =>'success'],200);

        } catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
}
