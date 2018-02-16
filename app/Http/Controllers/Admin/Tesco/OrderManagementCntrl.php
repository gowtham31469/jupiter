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
use App\TescoStickerOrdersSizeDetails;
use App\TescoPolybagCartonStickerOrders;
use App\TescoStickerOrdersCount;
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
                           ->select('tesco_item_master.id','tesco_template.template_name','tesco_template.template_file')
                           ->join('tesco_item_master','tesco_template.item_id','=','tesco_item_master.id')
                           ->where('tesco_item_master.id','=',$value)
                           ->first();
           }

           /* Fetch tesco brands,departments and suppliers details */
           $tesco_brand = DB::table('tesco_brand')->select('brand','id')->get();
           $tesco_department = DB::table('tesco_department')->select('department','id')->get();
           $tesco_suppliers = DB::table('tesco_suppliers')->select('supplier_name','id','supplier_code')->get();
           
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
     * Method to create tesco order from purchase order using Tabul-Java
     * @return \Illuminate\Http\Response
     */
    public function createOrderByTabula($path) {
      try {

        //Read the tabula configuration from config/tabula.php.
        $command  = Config::get('tabula.command');
        $java     = Config::get('tabula.java');
        $output   = shell_exec('java -jar '.$command.$path);
        return $output;
        // $process = new Process(java -jar '.$command.$path);
        // $process->run();
        // // executes after the command finishes
        // if (!$process->isSuccessful()) {
        //     throw new ProcessFailedException($process);
        // }
        //return $process->getOutput();
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
     * Store CE ratio TCL1,TCL2 and TSL1 template orders
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function setCERatioTCL1TCL2TSL1Orders(Request $request)
    {
        try {
            /** [ Creating new object for TescoOrders() class ] */
            $tesco_order_obj = new TescoOrders();
            $tesco_order     = $tesco_order_obj->createTescoOrder($request);
            $tesco_order_id  = $tesco_order->id; 
            $templates       = explode(',',$request['templates']);

            $inserted = TescoStickerOrdersSizeDetails::create([
                          'tesco_order_id'   => $tesco_order_id,
                          'orms_tpn'         => $request['orms_tpn'],
                          'itf_barcode'      => $request['itf_code'],
                          'cases_per_carton' => $request['cases_per_carton'],
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
     * Store CE ratio THL1 template orders
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function setCERatioTHL1Orders(Request $request)
    {
        try {
            /** [ Creating new object for TescoOrders() class ] */
            $tesco_order_obj = new TescoOrders();
            $tesco_order = $tesco_order_obj->createTescoOrder($request);
            $tesco_order_id = $tesco_order->id;  

            $inserted = TescoStickerOrdersSizeDetails::create([
                          'tesco_order_id'   => $tesco_order_id,
                          'orms_tpn'         => $request['orms_tpn'],
                          'itf_barcode'      => $request['itf_code'],
                          'cases_per_carton' => $request['cases_per_carton'],
                          'thl1_count'       => $request['ratio_thl1_count'],
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
     * Store CE single TCL4, TSL3 template orders
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function setCESingleTCL4TSL3Orders(Request $request)
    {
        try {
            /** [ Creating new object for TescoOrders() class ] */
            $tesco_order_obj = new TescoOrders();
            $tesco_order = $tesco_order_obj->createTescoOrder($request);
            $tesco_order_id = $tesco_order->id;  
            
            $orms_tpn            = explode(',',$request['orms_tpn']);
            $ean_code            = explode(',',$request['ean_code']);
            $case_size           = explode(',',$request['case_size']);
            $size                = explode(',',$request['size']);
            $single_tcl4_count   = explode(',',$request['single_tcl4_count']);
            $single_tsl3_count   = explode(',',$request['single_tsl3_count']);

            $i = 0;
            foreach($orms_tpn as $value)
            {
                TescoStickerOrdersSizeDetails::create([
                    'tesco_order_id'   => $tesco_order_id,
                    'orms_tpn'         => $value,
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
    /**
     * Store CE single THL3 template orders
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function setCESingleTHL3Orders(Request $request)
    {
        try {
            /** [ Creating new object for TescoOrders() class ] */
            $tesco_order_obj = new TescoOrders();
            $tesco_order = $tesco_order_obj->createTescoOrder($request);
            $tesco_order_id = $tesco_order->id;  

            $orms_tpn            = explode(',',$request['orms_tpn']);
            $ean_code            = explode(',',$request['ean_code']);
            $case_size           = explode(',',$request['case_size']);
            $size                = explode(',',$request['size']);
            $single_thl3_count   = explode(',',$request['single_thl3_count']);

            $i = 0;
            foreach($orms_tpn as $value)
            {
                TescoStickerOrdersSizeDetails::create([
                    'tesco_order_id'   => $tesco_order_id,
                    'orms_tpn'         => $value,
                    'ean_barcode'      => $ean_code[$i],
                    'case_size'        => $case_size[$i],
                    'size'             => $size[$i],
                    'thl3_count'       => $single_thl3_count[$i],
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
    /**
     * Store UK ratio TCL1,TCL2 and TSL1 template orders
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function setUKRatioTCL1TCL2TSL1Orders(Request $request)
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
     * Store UK ratio THL1 template orders
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function setUKRatioTHL1Orders(Request $request)
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
                          'thl1_count'       => $request['ratio_thl1_count'],
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
     * Store UK single TCL4, TSL3 template orders
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function setUKSingleTCL4TSL3Orders(Request $request)
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
    /**
     * Store UK single THL3 template orders
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function setUKSingleTHL3Orders(Request $request)
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
            $single_thl3_count   = explode(',',$request['single_thl3_count']);

            $i = 0;
            foreach($tpnd as $value)
            {
                TescoStickerOrdersSizeDetails::create([
                    'tesco_order_id'   => $tesco_order_id,
                    'tpnd'             => $value,
                    'ean_barcode'      => $ean_code[$i],
                    'case_size'        => $case_size[$i],
                    'size'             => $size[$i],
                    'thl3_count'       => $single_thl3_count[$i],
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
