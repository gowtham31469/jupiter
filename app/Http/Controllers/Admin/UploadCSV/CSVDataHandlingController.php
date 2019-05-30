<?php

namespace App\Http\Controllers\Admin\UploadCSV;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Modules;
use Response;
use View;
use App\OrchestraParcelDetails;
use App\OrchestraParcelQuantityDetails;
use App\TempOrchestraParcelDetails;
use App\TempOrchestraParcelQuantityDetails;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Classes\Uploads;

class CSVDataHandlingController extends Controller
{
    /**
     * Create a new controller instance.
     * @author <Praveen>
     * @return void
     */
    public function __construct()
    {
      $this->middleware('auth');
    }
    /**
     * Show the csv conflicts page.
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function conflictsCSV()
    {
      try
      {
        $module_var = new Modules();
        $is_allowed = $module_var->permittedModules();
        if($is_allowed)
        {
          $get_main_module = $module_var->getMainModules();
          $get_sub_module = $module_var->getSubModules(34);
          
          View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
              return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
          });
          
          $tp_parcel_details = TempOrchestraParcelDetails::select(DB::raw('distinct on ("UVC") "UVC"'), 'SupplierName',  'Theme', 'Season_Code', DB::raw("'temp_orchestra_parcel_quantity_details' as \"table\""))->groupBy('UVC', 'id')->get()->toArray();

          $tp_parcel_qty_details = TempOrchestraParcelQuantityDetails::select(DB::raw('distinct on ("UVC") "UVC"'), 'Supplier_Name as SupplierName', 'Season_Code', 'Theme', DB::raw("'temp_orchestra_parcel_details' as \"table\""))->groupBy('UVC', 'id')->get()->toArray();

          $result_array = array_merge($tp_parcel_qty_details, $tp_parcel_details);

          return View::make('Admin/Upload CSV/csv-conflicts', compact(['result_array']));
        }
        else
        {
          return redirect('/admin/dashboard');
        }
      }
      catch (\Exception $e)
      {
        return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }  
    }

     /**
     * Show the csv partial data page
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function partialCSV()
    {
      try
      {
        $module_var = new Modules();
        $is_allowed = $module_var->permittedModules();
        if($is_allowed)
        {
          $get_main_module = $module_var->getMainModules();
          $get_sub_module = $module_var->getSubModules(34);
          
          View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
              return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
          });
          return view('Admin/Upload CSV/partial-csv-data');
        }
        else
        {
          return redirect('/admin/dashboard');
        }
      }
      catch (\Exception $e)
      {
        return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }  
    }

    public function getDataByUVC(Request $request)
    {
      try
      {
        $uvc = $request->uvc;
        $table = $request->table_name;

        if($table === 'temp_orchestra_parcel_details')
        {
          $data = DB::table($table)->select(DB::raw('distinct on ("UVC_TCO") "UVC_TCO"'),'BuyerPONo', 'UVC_TCO', 'Season_Code', 'Size', 'Quantity', 'Po_Status_Code_Description', DB::raw("'PO Conflicting' as \"Changes\""), DB::raw("'temp_orchestra_parcel_details' as \"table\""))->where('UVC', $uvc)->groupBy('UVC_TCO', 'id')->get();
        }
        else   
        {
          $data = DB::table($table)->select(DB::raw('distinct on ("UVC_TCO") "UVC_TCO"'),'BuyerPONo', 'UVC_TCO', 'Season_Code', 'Size', 'Quantity', 'Po_Status_Code_Description', DB::raw("'Price Conflicting' as \"Changes\""), DB::raw("'temp_orchestra_parcel_quantity_details' as \"table\""))->where('UVC', $uvc)->groupBy('UVC_TCO', 'id')->get();
        }
        
        return response()->json(['error'=>'false','message' =>'success', 'data' => $data, 'uvc' => $uvc]);

      }
      catch (\Exception $e)
      {
        return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }  
    }
}
