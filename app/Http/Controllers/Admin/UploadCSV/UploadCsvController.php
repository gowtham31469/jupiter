<?php

namespace App\Http\Controllers\Admin\UploadCSV;

use Illuminate\Http\Request;
use Excel;
use App\Http\Controllers\Controller;
use App\Classes\Modules;
use Response;
use View;
use App\OrchestraItemDetails;
use App\OrchestraParcelDetails;
use App\OrchestraParcelQuantityDetails;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Classes\Uploads;

class UploadCsvController extends Controller
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
     * Show the upload csv home page.
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        $upload_obj = new Uploads();
        ini_set('max_execution_time', 5000);
        $delimiter = ";";
        $path     = $request->file('file')->getRealPath();
        $filename = $request->file('file')->getClientOriginalName();
        $extension  = $request->file('file')->getClientOriginalExtension();
        $filename = strtok($filename, " ");
        
          if (preg_match('/ORC_M3_PO_FILE/',$filename))
          {
            $data     =  $upload_obj->csv_to_array($path,$delimiter);
            $item_details_data_exists = OrchestraItemDetails::select()->get();
            $parcel_details_data_exists = OrchestraParcelDetails::select()->get();

            /** If there is no collision, Insert into item details table otherwise update temp item details table*/
            if (count($item_details_data_exists) > 0)
            {
              foreach ($data as $key => $value)
              {
                  $item_details_uvc = DB::table('orchestra_item_details')->where('UVC',trim($data[$key]['REFERENCE CODE']))->select('*')->get();
                  if (count($item_details_uvc)>0)
                  {
                    $item_details_po = DB::table('orchestra_item_details')->where('UVC',trim($data[$key]['REFERENCE CODE']))->where('BuyerPONo',trim($data[$key]['PO']))->select('*')->get();
                    if (count($item_details_po)>0)
                    {  
                      $item_details_sku_code = DB::table('orchestra_item_details')->where('UVC',trim($data[$key]['REFERENCE CODE']))->where('BuyerPONo',trim($data[$key]['PO']))->where('UVC_TCO',trim($data[$key]['SKU CODE']))->select('*')->get();
                      if(count($item_details_sku_code)>0)
                      {
                        $item_details_last_date = DB::table('orchestra_item_details')->where('UVC',trim($data[$key]['REFERENCE CODE']))->where('BuyerPONo',trim($data[$key]['PO']))->where('UVC_TCO',trim($data[$key]['SKU CODE']))->where('Po_Change_Date','<>',trim($data[$key]['PO CHANGE DATE']))->select('*')->get();
                        if(count($item_details_last_date)>0)
                        {
                          // collision. update temp item details table
                          //$upload_obj->updateOrchestraItemDetails($data,$key);
                        }
                      }
                      else
                      {
                        $item_details_last_date = DB::table('orchestra_item_details')->where('UVC',trim($data[$key]['REFERENCE CODE']))->where('BuyerPONo',trim($data[$key]['PO']))->where('Po_Change_Date','<>',trim($data[$key]['PO CHANGE DATE']))->select('*')->get();
                        if(count($item_details_last_date)>0)
                        {
                          //collision. update temp item details table
                          $upload_obj->updateOrchestraItemDetails($data,$key);
                        }
                        else 
                        {
                          $upload_obj->insertOrchestraItemDetails($data,$key);
                        }
                      }
                    }
                    else
                    {
                      $upload_obj->insertOrchestraItemDetails($data,$key);
                    }
                  }
                  else
                  {
                    $upload_obj->insertOrchestraItemDetails($data,$key);
                  }
                }
            }
            else
            {
              foreach ($data as $key=> $value)
              {
                $upload_obj->insertOrchestraItemDetails($data,$key);
              }
              
            }

            /** If there is no collision, Insert into parcel details table otherwise update temp parcel details table*/
            if (count($parcel_details_data_exists) > 0)
            {
              foreach ($data as $key => $value)
              {
                  $parcel_details_uvc = DB::table('orchestra_parcel_details')->where('UVC',trim($data[$key]['REFERENCE CODE']))->select('*')->get();
                  if (count($parcel_details_uvc)>0)
                  {
                    $parcel_details_po = DB::table('orchestra_parcel_details')->where('UVC',trim($data[$key]['REFERENCE CODE']))->where('BuyerPONo',trim($data[$key]['PO']))->select('*')->get();
                    if (count($parcel_details_po)>0)
                    {  
                      $parcel_details_sku_code = DB::table('orchestra_parcel_details')->where('UVC',trim($data[$key]['REFERENCE CODE']))->where('BuyerPONo',trim($data[$key]['PO']))->where('SKU_Code',trim($data[$key]['SKU CODE']))->select('*')->get();
                      if(count($parcel_details_sku_code)>0)
                      {
                        $parcel_details_last_date = DB::table('orchestra_parcel_details')->where('UVC',trim($data[$key]['REFERENCE CODE']))->where('BuyerPONo',trim($data[$key]['PO']))->where('SKU_Code',trim($data[$key]['SKU CODE']))->where('Po_Change_Date','<>',trim($data[$key]['PO CHANGE DATE']))->select('*')->get();
                        if(count($parcel_details_last_date)>0)
                        {
                          // collision. update temp parcel details table
                          //$upload_obj->updateOrchestraParcelDetails($data,$key);
                        }
                      }
                      else
                      {
                        $parcel_details_last_date = DB::table('orchestra_parcel_details')->where('UVC',trim($data[$key]['REFERENCE CODE']))->where('BuyerPONo',trim($data[$key]['PO']))->where('Po_Change_Date','<>',trim($data[$key]['PO CHANGE DATE']))->select('*')->get();
                        if(count($parcel_details_last_date)>0)
                        {
                          //collision. update temp parcel details table 
                         // $upload_obj->updateOrchestraParcelDetails($data,$key);
                        }
                        else 
                        {
                          $upload_obj->insertOrchestraParcelDetails($data,$key);
                        }
                      }
                    }
                    else
                    {
                      $upload_obj->insertOrchestraParcelDetails($data,$key);
                    }
                  }
                  else
                  {
                    $upload_obj->insertOrchestraParcelDetails($data,$key);
                  }
                }
            }
            else
            {
              foreach ($data as $key=> $value)
              {
                $upload_obj->insertOrchestraParcelDetails($data,$key);
              }
              
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
        $upload_obj = new Uploads();
        ini_set('max_execution_time', 5000);
        $delimiter = ";";
        $path     = $request->file('file')->getRealPath();
        $filename = $request->file('file')->getClientOriginalName();
        $extension  = $request->file('file')->getClientOriginalExtension();
        $filename = strtok($filename, " ");
       
        // validate correct file or not
        if (preg_match('/ORC_M3_PRICE_FILE/',$filename))
        {
          $data     =  $upload_obj->csv_to_array($path,$delimiter);
          
          // Foreach begins
          foreach ($data as $key=> $value)
          {
            // get po details from orchestra_parcel_details for this sku code.
            $parcel_details_data = DB::table('orchestra_parcel_details')->where('SKU_Code', 'ilike',trim($data[$key]['SKU CODE']))->select('*')->first();

            // check sku code already exists or not 
            $sku_exists = DB::table('orchestra_parcel_quantity_details')->where('UVC_TCO',trim($data[$key]['SKU CODE']))->select('*')->get();
            if(count($sku_exists) > 0)
            {
              // check price details already exists for this sku code with different Country_Code.
              $diff_order = DB::table('orchestra_parcel_quantity_details')->where('UVC_TCO',trim($data[$key]['SKU CODE']))->where('Country_Code',trim($data[$key]['COUNTRY CODE']))->select('*')->get();
              if (count($diff_order) > 0)
              {
                // check price details already exists with same UVC_TCO ,COUNTRY CODE & different Last_Change_Date
                $quantity_date = DB::table('orchestra_parcel_quantity_details')->where('UVC_TCO',trim($data[$key]['SKU CODE']))->where('Country_Code',trim($data[$key]['COUNTRY CODE']))->where('Last_Change_Date','<>',trim($data[$key]['LAST_CHANGE_DATE']))->select('*')->get();

                if(count($quantity_date) > 0)
                {
                  // collision. update temp orchestra parcel quantity details table 
                  //$upload_obj->updateOrchestraParcelQuantityDetails($value);
                }
                
              }
              else
              {
                // check price details already exists with same UVC_TCO & Last_Change_Date
                $quantity_date = DB::table('orchestra_parcel_quantity_details')->where('UVC_TCO',trim($data[$key]['SKU CODE']))->where('Last_Change_Date',trim($data[$key]['LAST_CHANGE_DATE']))->select('*')->get();
                
                // insert price details, iff order with same sku code, country code and last change date doesn't exists.
                if(count($quantity_date) > 0)
                {
                  // insert price details in orchestra_parcel_quantity_details, iff po details exists for this sku code and COUNTRY CODE.
                  // if($parcel_details_data)
                  // {
                    $upload_obj->insertOrchestraParcelQuantityDetails($data, $key, $parcel_details_data);
                  //}
                }
                else 
                {
                  // collision. update temp orchestra parcel quantity details table 
                  //$upload_obj->updateOrchestraParcelQuantityDetails($value);
                }
              }
            }
            else 
            {
              // insert price details in orchestra_parcel_quantity_details, iff po details exists for this sku code and COUNTRY CODE.
              // if($parcel_details_data)
              // {
                $upload_obj->insertOrchestraParcelQuantityDetails($data, $key, $parcel_details_data);
              //}
            }
            
            // get po details from orchestra_item_details for this sku code.
            $item_details_data   = DB::table('orchestra_item_details')->where('UVC_TCO', 'ilike',trim($data[$key]['SKU CODE']))->select('*')->get();

            // check sku code already exists or not 
            $sku_exists = DB::table('orchestra_item_details')->where('UVC_TCO',trim($data[$key]['SKU CODE']))->select('*')->get();

            if(count($sku_exists) > 0)
            {
              // check price details already exists for this sku code with different last_change_date.
              $diff_order = DB::table('orchestra_item_details')->where('UVC_TCO',trim($data[$key]['SKU CODE']))->whereNotIn('Last_Change_Date',[trim($data[$key]['LAST_CHANGE_DATE']), 'Null', 'null', 'NULL'])->select('*')->get();
              if (count($diff_order) > 0)
              {
                // collision. update temp orchestra item details table 
                $upload_obj->updateOrchestraItemDetails($value);
              }
              else
              {
                // check price details already exists with same last_change_date & COUNTRY CODE
                /*  $quantity_date = DB::table('orchestra_item_details')->where('UVC_TCO',trim($data[$key]['SKU CODE']))->where('Last_Change_Date',trim($data[$key]['LAST_CHANGE_DATE']))->select('*')->get(); */
                
                // insert price details, iff order with same sku code, country code and last change date doesn't exists.
                /* if(count($quantity_date) === 0)
                { */
                  // update price details in orchestra_item_details table.
                  if($item_details_data)
                  {
                    $upload_obj->updateOrchestraItemPriceDetails($data, $key, $item_details_data);
                  }
                /* } */
              }
            }
            else  
            {
              // insert price details without po details.
            }
          }
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
