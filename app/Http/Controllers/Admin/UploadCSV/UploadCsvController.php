<?php

namespace App\Http\Controllers\Admin\UploadCSV;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use App\OrchestraParcelDetails;
use App\OrchestraParcelQuantityDetails;
use App\Classes\Uploads;
use App\Classes\Modules;

class UploadCsvController extends Controller
{
    /**
     * Create a new controller instance.
     * @author <Praveen>
     * @return void
     */
    public function __construct(Modules $module, Uploads $upload)
    {
      $this->middleware('auth');
      $this->module = $module;
      $this->upload = $upload;
    }
    /**
     * Show the upload csv home page.
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      try
      {
        $is_allowed = $this->module->permittedModules();
        if($is_allowed)
        {
          $get_main_module = $this->module->getMainModules();
          $get_sub_module = $this->module->getSubModules(34);
          
          View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
              return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
          });
          return view('Admin/Upload CSV/home');
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
     * method to upload the the orchestra po csv file
     * @return [boolean] [success or fail]
     * @author Praveen <praveen@wizroots.com>
     */
    public function uploadPOCSVFile(Request $request)
    {
      try
      {
        ini_set('max_execution_time', 5000);
        $delimiter = ";";
        $path     = $request->file('file')->getRealPath();
        $filename = strtok($request->file('file')->getClientOriginalName(), " ");
        
          if (preg_match('/ORC_M3_PO_FILE/',$filename))
          {
            $data     =  $this->upload->csv_to_array($path,$delimiter);

            $parcel_details_data_exists = OrchestraParcelDetails::exists();
            
            $this->upload->isCustomerExists($data);

            /** If there is no collision, Insert into parcel details table otherwise update temp parcel details table*/
            if ($parcel_details_data_exists)
            {
               foreach ($data as $value)
               {
                  $parcel_details_uvc = OrchestraParcelDetails::where('UVC', '=', trim($value['REFERENCE CODE']))->exists();
                  if ($parcel_details_uvc)
                  {
                    $parcel_details_po = OrchestraParcelDetails::where([
                                                                        ['UVC', '=', trim($value['REFERENCE CODE'])], 
                                                                        ['BuyerPONo', '=', trim($value['PO'])],
                                                                      ])->exists();
                    if ($parcel_details_po)
                    {  
                      $parcel_details_sku_code = OrchestraParcelDetails::where([
                                                                                ['UVC', '=', trim($value['REFERENCE CODE'])],
                                                                                ['BuyerPONo', '=', trim($value['PO'])],
                                                                                ['SKU_Code', '=', trim($value['SKU CODE'])],
                                                                              ])->exists();
                      if($parcel_details_sku_code)
                      {
                        $parcel_details_last_date = OrchestraParcelDetails::where([
                                                                                    ['UVC', '=', trim($value['REFERENCE CODE'])],
                                                                                    ['BuyerPONo', '=', trim($value['PO'])],
                                                                                    ['SKU_Code', '=', trim($value['SKU CODE'])],
                                                                                    ['Po_Change_Date', '<>', trim($value['PO CHANGE DATE'])],
                                                                                 ])->exists();

                        if($parcel_details_last_date)
                        {
                          // collision. update temp parcel details table
                          $this->upload->updateOrchestraTempParcelDetails($value);
                        }
                      }
                      else
                      {
                        $parcel_details_last_date = OrchestraParcelDetails::where([
                                                                                    ['UVC', '=', trim($value['REFERENCE CODE'])],
                                                                                    ['BuyerPONo', '=', trim($value['PO'])],
                                                                                    ['Po_Change_Date','<>',trim($value['PO CHANGE DATE'])],
                                                                                 ])->exists();

                        if($parcel_details_last_date)
                        {
                          //collision. update temp parcel details table 
                          $this->upload->updateOrchestraTempParcelDetails($value);
                        }
                        else 
                        {
                          $this->upload->insertOrchestraParcelDetails($value);
                        }
                      }
                    }
                    else
                    {
                      $this->upload->insertOrchestraParcelDetails($value);
                    }
                  }
                  else
                  {
                    $this->upload->insertOrchestraParcelDetails($value);
                  }

                  // check PO details missing for this sku code (ie, PO number = null).
                  $po_missing = OrchestraParcelQuantityDetails::where([
                                                                        ['UVC_TCO', '=', trim($value['SKU CODE'])],
                                                                        ['BuyerPONo', '=', null],
                                                                      ])->exists();

                  if($po_missing)
                  {
                    // get po details from orchestra_parcel_details for this sku code.
                    $parcel_details_data = OrchestraParcelDetails::where('SKU_Code', trim($value['SKU CODE']))->select('*')->first();

                    // get po data for current uvc_tco value and update in parcel_quantity_details table.
                    $this->upload->updateOrchestraParcelQuantityPODetails($parcel_details_data);                 

                  }
                }
                unset($value);
            }
            else
            {
              foreach ($data as $value)
              {
                $this->upload->insertOrchestraParcelDetails($value);

                // check PO details missing for this sku code (ie, PO number = null).
                $po_missing = OrchestraParcelQuantityDetails::where([
                                                                      ['UVC_TCO', '=', trim($value['SKU CODE'])], 
                                                                      ['BuyerPONo', '=', null],
                                                                    ])->exists();

                if($po_missing)
                {
                  // get po details from orchestra_parcel_details for this sku code.
                  $parcel_details_data = OrchestraParcelDetails::where('SKU_Code', trim($value['SKU CODE']))->select('*')->first();

                  // get po data for current uvc_tco value and update in parcel_quantity_details table.
                  $this->upload->updateOrchestraParcelQuantityPODetails($parcel_details_data);                 

                }

              }
              unset($value);
              
            }
            $result = response()->json(['error' =>false,'message' => 'PO CSV File Uploaded Successfully'], 200);
          }
          else
          {
            $result = response()->json(['error' =>true,'message' => 'Invalid File'], 403);
          }
      }
      catch (\Exception $e)
      {
          $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
      return $result;
    }

    /**
     * method to upload the orchestra price csv file
     * @return [type] [description]
     */
    public function uploadPriceCSVFile(Request $request)
    {
      try
      {
        ini_set('max_execution_time', 5000);
        $delimiter = ";";
        $path     = $request->file('file')->getRealPath();
        $filename = strtok($request->file('file')->getClientOriginalName(), " ");
       
        // validate correct file or not
        if (preg_match('/ORC_M3_PRICE_FILE/',$filename))
        {
          $data     =  $this->upload->csv_to_array($path,$delimiter);
          
          // Foreach begins
          foreach ($data as $value)
          {
            // get po details from orchestra_parcel_details for this sku code.
            $parcel_details_data = OrchestraParcelDetails::where('SKU_Code', trim($value['SKU CODE']))->select('*')->first();

            // check sku code already exists or not 
            $sku_exists = OrchestraParcelQuantityDetails::where('UVC_TCO', trim($value['SKU CODE']))->exists();
            if($sku_exists)
            {

              // check PO details missing for this sku code (ie, PO number = null).
              $po_missing = OrchestraParcelQuantityDetails::where([
                                                                    ['UVC_TCO', '=', trim($value['SKU CODE'])],
                                                                    ['BuyerPONo', '=', null],
                                                                  ])->exists();

              if($po_missing)
              {
                // get po data for current uvc_tco value and update in parcel_quantity_details table.
                if($parcel_details_data)
                {
                  $this->upload->updateOrchestraParcelQuantityPODetails($parcel_details_data);  
                }               

              }

              // check price details already exists for this sku code with different Country_Code.
              $diff_order = OrchestraParcelQuantityDetails::where([
                                                                    ['UVC_TCO', '=', trim($value['SKU CODE'])],
                                                                    ['Country_Code', '=', trim($value['COUNTRY CODE'])],
                                                                  ])->exists();

              if ($diff_order)
              {
                // check price details already exists with same UVC_TCO ,COUNTRY CODE & different Last_Change_Date
                $quantity_date = OrchestraParcelQuantityDetails::where([
                                                                          ['UVC_TCO', '=', trim($value['SKU CODE'])],
                                                                          ['Country_Code', '=', trim($value['COUNTRY CODE'])],
                                                                          ['Last_Change_Date', '<>', trim($value['LAST_CHANGE_DATE'])],
                                                                        ])->exists();

                if($quantity_date)
                {
                  // collision. update temp orchestra parcel quantity details table 
                  $this->upload->updateOrchestraTempParcelQuantityDetails($value, $parcel_details_data);
                }
                
              }
              else
              {
                // check price details already exists with same UVC_TCO & Last_Change_Date
                $quantity_date = OrchestraParcelQuantityDetails::where([
                                                                        ['UVC_TCO', '=', trim($value['SKU CODE'])],
                                                                        ['Last_Change_Date', '=', trim($value['LAST_CHANGE_DATE'])],
                                                                      ])->exists();
                
                // insert price details, iff order with same sku code, country code and last change date doesn't exists.
                if($quantity_date)
                {
                  // insert price details in orchestra_parcel_quantity_details, iff po details exists for this sku code and COUNTRY CODE.
                  // if($parcel_details_data)
                  // {
                    $this->upload->insertOrchestraParcelQuantityDetails($value, $parcel_details_data);
                  //}
                }
                else 
                {
                  // collision. update temp orchestra parcel quantity details table 
                  $this->upload->updateOrchestraTempParcelQuantityDetails($value, $parcel_details_data);
                }
              }
            }
            else 
            {
              // insert price details in orchestra_parcel_quantity_details, iff po details exists for this sku code and COUNTRY CODE.
              // if($parcel_details_data)
              // {
                $this->upload->insertOrchestraParcelQuantityDetails($value, $parcel_details_data);
              //}
            }
          }
          unset($value);
          // end of foreach

          $result = response()->json(['error' =>false,'message' => 'Price CSV File Uploaded Successfully'], 200);
        }
        else
        {
          $result = response()->json(['error' =>true,'message' => 'Invalid File'], 403);
        }
      }
      catch (\Exception $e)
      {
          $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
      return $result;
    }
    
}
