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
          
          $tp_parcel_details = TempOrchestraParcelDetails::select(DB::raw('distinct on ("UVC") "UVC"'), 'SupplierName',  'Theme', 'Season_Code', DB::raw("'temp_orchestra_parcel_details' as \"table\""), DB::raw("'PO Conflicting' as \"Changes\""))->groupBy('UVC', 'id')->get()->toArray();

          $tp_parcel_qty_details = TempOrchestraParcelQuantityDetails::select(DB::raw('distinct on ("UVC") "UVC"'), 'Supplier_Name as SupplierName', 'Season_Code', 'Theme', DB::raw("'temp_orchestra_parcel_quantity_details' as \"table\""), DB::raw("'Price Conflicting' as \"Changes\""))->groupBy('UVC', 'id')->get()->toArray();

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

        // check for price data missing for SKU Code(In PO CSV)
        $parcel_details = OrchestraParcelDetails::select(DB::raw('distinct on (orchestra_parcel_quantity_details."UVC_TCO") orchestra_parcel_quantity_details."UVC_TCO"'))->join('orchestra_parcel_quantity_details', 'orchestra_parcel_quantity_details.UVC_TCO', 'orchestra_parcel_details.UVC_TCO')->groupBy('orchestra_parcel_quantity_details.UVC_TCO')->get()->toArray();

        $UVC_TCO_array1 = [];
        foreach ($parcel_details as $key => $value) {
          array_push($UVC_TCO_array1, $value['UVC_TCO']);
          unset($value);
        }
         $parcel_details_array = OrchestraParcelDetails::select(DB::raw('distinct on (orchestra_parcel_details."UVC_TCO") orchestra_parcel_details."UVC_TCO"'), 'UVC', 'UVC_TCO', 'BuyerPONo', 'Season_Code', 'SupplierName', DB::raw("'Price Details Missing ' as \"file_status\""))->whereNotIn('UVC_TCO', $UVC_TCO_array1)->get()->toArray();
         
          return View::make('Admin/Upload CSV/partial-csv-data', compact(['parcel_details_array']));
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
     * Get order details by UVC value.
     * @author <Praveen>
     * @param Request $request
     * @return void
     */
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

    /**
     * Confirm order conflicts in temp table
     * @author <Praveen>
     * @param Request $request
     * @return void
     */
    public function confirmUVCOrders(Request $request)
    {
      try
      {
        $data_array = $request->data_array;
        $uvc = $data_array[0]['uvc'];
        $temp_table = $data_array[0]['table'];

        foreach ($data_array as $value) 
        {
           $temp_table = $value['table'];
           $po         = $value['po'];
           $uvc        = $value['uvc'];
           $uvc_tco    = $value['uvc_tco'];
           $main_table = str_replace("temp_", "", $temp_table);

           // delete from main table 
           DB::table($main_table)->where('BuyerPONo', $po)->where('UVC', $uvc)->where('UVC_TCO', $uvc_tco)->delete();

           // get data from temp table
           $temp_table_data = DB::table($temp_table)->where('BuyerPONo', $po)->where('UVC', $uvc)->where('UVC_TCO', $uvc_tco)->get()->toArray();

           if($main_table === 'orchestra_parcel_details')
           {
              foreach ($temp_table_data as $orchestra_parcel_details_value) {

                OrchestraParcelDetails::create((array)$orchestra_parcel_details_value);

              }
              unset($orchestra_parcel_details_value);
           }
           else   
           {
             foreach ($temp_table_data as $temp_table_value) {

                OrchestraParcelQuantityDetails::create((array)$temp_table_value);
                
             }
             unset($temp_table_value);
           }

           // delete data from temp table
           DB::table($temp_table)->where('BuyerPONo', $po)->where('UVC', $uvc)->where('UVC_TCO', $uvc_tco)->delete();

        }
        unset($value);
        return response()->json(['error'=>'false','message' =>'Order Changes Has Been Confirmed.', 'uvc' => $uvc, 'table' => $temp_table]);

      }
      catch (\Exception $e)
      {
        return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }  
    }


    /**
     * Cancel order conflicts in temp table
     * @author <Praveen>
     * @param Request $request
     * @return void
     */
    public function cancelUVCOrders(Request $request)
    {
      try
      {
        $data_array = $request->data_array;

        $uvc = $data_array[0]['uvc'];
        $temp_table = $data_array[0]['table'];

        foreach ($data_array as $key => $value) 
        {
           $temp_table = $value['table'];
           $po         = $value['po'];
           $uvc        = $value['uvc'];
           $uvc_tco    = $value['uvc_tco'];

           // delete data from temp table
           DB::table($temp_table)->where('BuyerPONo', $po)->where('UVC', $uvc)->where('UVC_TCO', $uvc_tco)->delete();

        }
        unset($value);
        return response()->json(['error'=>'false','message' =>'Order Changes Has Been Cancelled.', 'uvc' => $uvc, 'table' => $temp_table]);

      }
      catch (\Exception $e)
      {
        return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }  
    }

    /**
     * Get order changes page using ajax for back button functionality
     * @author <Praveen>
     * @return void
     */
    public function orderChangesPage()
    {
      try
      {
        $tp_parcel_details = TempOrchestraParcelDetails::select(DB::raw('distinct on ("UVC") "UVC"'), 'SupplierName',  'Theme', 'Season_Code', DB::raw("'temp_orchestra_parcel_details' as \"table\""), DB::raw("'PO Conflicting' as \"Changes\""))->groupBy('UVC', 'id')->get()->toArray();

        $tp_parcel_qty_details = TempOrchestraParcelQuantityDetails::select(DB::raw('distinct on ("UVC") "UVC"'), 'Supplier_Name as SupplierName', 'Season_Code', 'Theme', DB::raw("'temp_orchestra_parcel_quantity_details' as \"table\""), DB::raw("'Price Conflicting' as \"Changes\""))->groupBy('UVC', 'id')->get()->toArray();

        $result_array = array_merge($tp_parcel_qty_details, $tp_parcel_details);

        return response()->json(['error'=>'false','message' =>'Success', 'result_array' => $result_array]);

      }
      catch (\Exception $e)
      {
        return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }  
    }

    /**
     * Get order changes data for comparison
     * @author <Praveen>
     * @param Request $request
     * @return void
     */
    public function getOrderChangesData(Request $request)
    {
      try
      {
        $uvc        = $request->uvc;
        $temp_table = $request->table;
        $uvc_tco    = $request->uvc_tco;
        $po         = $request->po;
        $main_table = str_replace("temp_", "", $temp_table);

        if($temp_table === 'temp_orchestra_parcel_details')
        {
          // get data from temp table
          $data['temp_data'] = DB::table($temp_table)->select('*', DB::raw("'false' as \"price_details\""))->where('BuyerPONo', $po)->where('UVC', $uvc)->where('UVC_TCO', $uvc_tco)->get();

          // get data from temp table
          $data['main_data'] = DB::table($main_table)->select('*', DB::raw("'false' as \"price_details\""))->where('BuyerPONo', $po)->where('UVC', $uvc)->where('UVC_TCO', $uvc_tco)->get();
        }   
        else    
        {
          // get data from temp table
          $data['temp_data'] = DB::table($temp_table)->select('*', DB::raw("'true' as \"price_details\""))->where('BuyerPONo', $po)->where('UVC', $uvc)->where('UVC_TCO', $uvc_tco)->get();

          // get data from temp table
          $data['main_data'] = DB::table($main_table)->select('*', DB::raw("'true' as \"price_details\""))->where('BuyerPONo', $po)->where('UVC', $uvc)->where('UVC_TCO', $uvc_tco)->get();
        }


        return response()->json(['error'=>'false','message' =>'Success', 'data' => $data]);

      }
      catch (\Exception $e)
      {
        return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }  
    }
}
