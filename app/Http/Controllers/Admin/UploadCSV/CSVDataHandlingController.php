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
        }

         $parcel_details_array = OrchestraParcelDetails::select(DB::raw('distinct on (orchestra_parcel_details."UVC_TCO") orchestra_parcel_details."UVC_TCO"'), 'UVC', 'UVC_TCO', 'BuyerPONo', 'Season_Code', 'SupplierName', DB::raw("'Price Details Missing ' as \"file_status\""))->whereNotIn('UVC_TCO', $UVC_TCO_array1)->get()->toArray();

         // check for po data missing for SKU Code(In price CSV)
         $parcel_qty_details = OrchestraParcelQuantityDetails::select(DB::raw('distinct on (orchestra_parcel_details."UVC_TCO") orchestra_parcel_details."UVC_TCO"'))->join('orchestra_parcel_details', 'orchestra_parcel_quantity_details.UVC_TCO', 'orchestra_parcel_details.UVC_TCO')->groupBy('orchestra_parcel_details.UVC_TCO')->get()->toArray();

         $UVC_TCO_array2 = [];
         foreach ($parcel_qty_details as $key => $value) {
           array_push($UVC_TCO_array2, $value['UVC_TCO']);
         }

         $parcel_qty_details_array = OrchestraParcelQuantityDetails::select(DB::raw('distinct on (orchestra_parcel_quantity_details."UVC_TCO") orchestra_parcel_quantity_details."UVC_TCO"'), 'UVC', 'UVC_TCO', 'BuyerPONo', 'Season_Code', 'Supplier_Name as SupplierName', DB::raw("'PO Details Missing' as \"file_status\""))->whereNotIn('UVC_TCO', $UVC_TCO_array2)->get()->toArray();

          $result_array = array_merge($parcel_qty_details_array, $parcel_details_array);

          return View::make('Admin/Upload CSV/partial-csv-data', compact(['result_array']));
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
        $uvc = $data_array{0}['uvc'];
        $temp_table = $data_array{0}['table'];

        foreach ($data_array as $key => $value) 
        {
           $temp_table = $value['table'];
           $po         = $value['po'];
           $uvc        = $value['uvc'];
           $uvc_tco    = $value['uvc_tco'];
           $main_table = str_replace("temp_", "", $temp_table);

           // delete from main table 
           DB::table($main_table)->where('BuyerPONo', $po)->where('UVC', $uvc)->where('UVC_TCO', $uvc_tco)->delete();

           // get data from temp table
           $temp_table_data = DB::table($temp_table)->where('BuyerPONo', $po)->where('UVC', $uvc)->where('UVC_TCO', $uvc_tco)->get();

           if($main_table === 'orchestra_parcel_details')
           {
              foreach ($temp_table_data as $key => $value) {
                OrchestraParcelDetails::create([
                  'FactoryCode'                        => $value->FactoryCode,
                  'SupplierName'                       => $value->SupplierName,
                  'SupplierAddress'			               => $value->SupplierAddress,
                  'SupplierEmail'                      => $value->SupplierEmail,
                  'SupplierCountry'                    => $value->SupplierCountry,
                  'BuyerPONo'                          => $value->BuyerPONo,
                  'UVC'                                => $value->UVC,
                  'Theme'                              => $value->Theme,
                  'IsSTD'                              => $value->IsSTD,
                  'Quantity'                           => $value->Quantity,
                  'NomApple'                           => $value->NomApple,
                  'Season_Code'                        => $value->Season_Code,
                  'SKU_Code'                           => $value->SKU_Code,
                  'Po_Line_Number'                     => $value->Po_Line_Number,
                  'Po_Line_Suffix'                     => $value->Po_Line_Suffix,
                  'Po_Status_Code'                     => $value->Po_Status_Code,
                  'Po_Status_Code_Description'         => $value->Po_Status_Code_Description,
                  'Style_Code'                         => $value->Style_Code,
                  'Activity_Code'                      => $value->Activity_Code,
                  'Activity_Label'                     => $value->Activity_Label,
                  'Universe_Code'                      => $value->Universe_Code,
                  'Universe_Label'                     => $value->Universe_Label,
                  'Department_Code'                    => $value->Department_Code,
                  'Department_Label'                   => $value->Department_Label,
                  'Family_Code'                        => $value->Family_Code,
                  'Family_Label'                       => $value->Family_Label,
                  'Sub_Family_Code'                    => $value->Sub_Family_Code,
                  'Sub_Family_Label'                   => $value->Sub_Family_Label,
                  'Theme_Code'                         => $value->Theme_Code,
                  'Brand_Code'                         => $value->Brand_Code,
                  'Brand_Label'                        => $value->Brand_Label,
                  'Description_Fr'                     => $value->Description_Fr,
                  'Buyer_Email'                        => $value->Buyer_Email,
                  'Agent_Code'                         => $value->Agent_Code,
                  'Agent_Name'                         => $value->Agent_Name,
                  'Agent_Email'                        => $value->Agent_Email,
                  'Agent_Address'                      => $value->Agent_Address,
                  'Agent_Country'                      => $value->Agent_Country,
                  'Supplier_Code'                      => $value->Supplier_Code,
                  'Factory_Name'                       => $value->Factory_Name,
                  'Factory_Email'                      => $value->Factory_Email,
                  'Factory_Address'                    => $value->Factory_Address,
                  'Made_In_Code'                       => $value->Made_In_Code,
                  'Made_In_Label'                      => $value->Made_In_Label,
                  'First_ETD_SHIPMENT_DATE'            => $value->First_ETD_SHIPMENT_DATE,
                  'Shipment_Destination_Code'          => $value->Shipment_Destination_Code,
                  'Shipment_Destination_Label'         => $value->Shipment_Destination_Label,
                  'Incoterms_Code'                     => $value->Incoterms_Code,
                  'Incoterms_City_Label'               => $value->Incoterms_City_Label,
                  'Sku_Barcode'                        => $value->Sku_Barcode,
                  'Set_'                               => $value->Set_,
                  'Number_Of_Pieces'                   => $value->Number_Of_Pieces,
                  'Color_Code'                         => $value->Color_Code,
                  'Color_Label'                        => $value->Color_Label,
                  'Standard_Code'                      => $value->Standard_Code,
                  'Standard_Barcode'                   => $value->Standard_Barcode,
                  'Size_Range_Code'                    => $value->Size_Range_Code,
                  'Size_Range_Label'                   => $value->Size_Range_Label,
                  'Size_Code'                          => $value->Size_Code,
                  'Size_Label'                         => $value->Size_Label,
                  'Size_Breakdown'                     => $value->Size_Breakdown,
                  'Total_Standard_Solid_Parcel_Number' => $value->Total_Standard_Solid_Parcel_Number,
                  'Product_Type'                       => $value->Product_Type,
                  'Licence_Code'                       => $value->Licence_Code,
                  'Licence_Label'                      => $value->Licence_Label,
                  'Licence_Character_Code'             => $value->Licence_Character_Code,
                  'Licence_Character_Label'            => $value->Licence_Character_Label,
                  'Start_Print_Date'                   => $value->Start_Print_Date,
                  'Composition_Label'                  => $value->Composition_Label,
                  'Po_Change_Date'                     => $value->Po_Change_Date,
                  'Purchase_Price'                     => $value->Purchase_Price,
                  'Po_Amount'                          => $value->Po_Amount, 
                  'Typologie'                          => $value->Typologie,
                  'Payment_Condition_Code'             => $value->Payment_Condition_Code,
                  'Payment_Terms'                      => $value->Payment_Terms,
                  'Payment_Terms_Label'                => $value->Payment_Terms_Label,
                  'BarcodeForPricesTags'               => $value->BarcodeForPricesTags,
                  'UVC_TCO'                            => $value->UVC_TCO,
                  'Size'                               => $value->Size,
                  'ParcelBarCode'                      => $value->ParcelBarCode
                  ]);
              }
           }
           else   
           {
             foreach ($temp_table_data as $key => $value) 
             {
                OrchestraParcelQuantityDetails::create([
                  'FactoryCode' 	       		    => $value->FactoryCode,
                  'BuyerPONo'            		    => $value->BuyerPONo,
                  'Theme'                		    => $value->Theme,
                  'UVC_TCO'              		    => $value->UVC_TCO,
                  'Quantity'             		    => $value->Quantity,
                  'Color'                		    => $value->Color,
                  'UVC'                  		    => $value->UVC,
                  'Size'                 		    => $value->Size,
                  'BarcodeForPricesTags' 		    => $value->BarcodeForPricesTags,
                  'Country_Code'         		    => $value->Country_Code,
                  'Season_Code'          		    => $value->Season_Code,
                  'Currency'             		    => $value->Currency,
                  'Price'                		    => $value->Price,
                  'Club_Price'           		    => $value->Club_Price,
                  'Nom_Appel'            		    => $value->Nom_Appel,
                  'Last_Change_Date'     		    => $value->Last_Change_Date,
                  'Supplier_Code'        		    => $value->Supplier_Code,
                  'Supplier_Name'        		    => $value->Supplier_Name,
                  'Po_Status_Code'              => $value->Po_Status_Code,
                  'Po_Status_Code_Description'  => $value->Po_Status_Code_Description
                  ]);
             }
           }

           // delete data from temp table
           DB::table($temp_table)->where('BuyerPONo', $po)->where('UVC', $uvc)->where('UVC_TCO', $uvc_tco)->delete();

        }
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

        $uvc = $data_array{0}['uvc'];
        $temp_table = $data_array{0}['table'];

        foreach ($data_array as $key => $value) 
        {
           $temp_table = $value['table'];
           $po         = $value['po'];
           $uvc        = $value['uvc'];
           $uvc_tco    = $value['uvc_tco'];
           $main_table = str_replace("temp_", "", $temp_table);

           // delete data from temp table
           DB::table($temp_table)->where('BuyerPONo', $po)->where('UVC', $uvc)->where('UVC_TCO', $uvc_tco)->delete();

        }
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
