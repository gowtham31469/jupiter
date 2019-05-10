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
use Carbon\Carbon;
use \Datetime;

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

          $csv_updates =  DB::table('temp_orchestra_item_details')->get();
          return view('Admin/Upload CSV/home',['csv_updates' => $csv_updates]);
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
        ini_set('max_execution_time', 2000);
        $delimiter = ";";
        $path     = $request->file('file')->getRealPath();
        $filename = $request->file('file')->getClientOriginalName();
        $extension  = $request->file('file')->getClientOriginalExtension();
        $filename = strtok($filename, " ");
        
          if (preg_match('/ORC_M3_PO_FILE_BU-A-ORCIN/',$filename))
          {
            $data     =  $upload_obj->csv_to_array($path,$delimiter);
            $item_details_data_exists = OrchestraItemDetails::select()->get();
            $parcel_details_data_exists = OrchestraParcelDetails::select()->get();

            /** If there is no collision, Insert into item details table otherwise update temp item details table*/
            if (count($item_details_data_exists) > 0)
            {
              foreach ($data as $key => $value)
              {
                  $item_details_uvc = DB::table('orchestra_item_details')->where('UVC',$data[$key]['REFERENCE CODE'])->select('*')->get();
                  if (count($item_details_uvc)>0)
                  {
                    $item_details_po = DB::table('orchestra_item_details')->where('UVC',$data[$key]['REFERENCE CODE'])->where('BuyerPONo',$data[$key]['PO'])->select('*')->get();
                    if (count($item_details_po)>0)
                    {
                          
                      $upload_obj->updateOrchestraItemDetails($data,$key);
                          
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
                    $parcel_details_uvc = DB::table('orchestra_parcel_details')->where('UVC',$data[$key]['REFERENCE CODE'])->select('*')->get();
                    if (count($parcel_details_uvc)>0)
                    {
                      $parcel_details_po = DB::table('orchestra_parcel_details')->where('UVC',$data[$key]['REFERENCE CODE'])->where('BuyerPONo',$data[$key]['PO'])->select('*')->get();
                      if (count($parcel_details_po)>0)
                      {
                            
                        $upload_obj->updateOrchestraParcelDetails($data,$key);
                            
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
              $result = response()->json(['error' =>false,'message' => 'Uploaded Successfully'], 200);
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
        ini_set('max_execution_time', 2000);
        $delimiter = ";";
        $path     = $request->file('file')->getRealPath();
        $filename = $request->file('file')->getClientOriginalName();
        $extension  = $request->file('file')->getClientOriginalExtension();
        $filename = strtok($filename, " ");
       
          if (preg_match('/ORC_M3_PRICE_FILE_BU-A-ORCIN/',$filename))
          {
           return  $data     =  $upload_obj->csv_to_array($path,$delimiter);
            $data_exists = OrchestraParcelQuantityDetails::select()->get();

              if (count($data_exists)>0)
              {
                foreach ($data as $key=> $value)
                {
                  $quantity_uvc = DB::table('orchestra_parcel_quantity_details')->where('UVC',$data[$key]['UVC'])->select('*')->get();
                if (count($quantity_uvc)>0)
                {
                  $quantity_po = DB::table('orchestra_parcel_quantity_details')->where('UVC',$data[$key]['UVC'])->where('BuyerPONo',$data[$key]['BuyerPONo'])->select('*')->get();
                  if (count($quantity_po)>0)
                  {
                    $quantity_version = DB::table('orchestra_parcel_quantity_details')->where('UVC',$data[$key]['UVC'])->where('BuyerPONo',$data[$key]['BuyerPONo'])->where('VersionNumber',$data[$key]['VersionNumber'])->select('*')->get();
                    if (count($quantity_version)>0)
                    {
                      $quantity_edition = DB::table('orchestra_parcel_quantity_details')->where('UVC',$data[$key]['UVC'])->where('BuyerPONo',$data[$key]['BuyerPONo'])->where('VersionNumber',$data[$key]['VersionNumber'])->where('EditionNumber',$data[$key]['EditionNumber'])->select('*')->get();
                      if (count($quantity_edition)>0)
                      {
                        $quantity_size = DB::table('orchestra_parcel_quantity_details')->where('UVC',$data[$key]['UVC'])->where('BuyerPONo',$data[$key]['BuyerPONo'])->where('VersionNumber',$data[$key]['VersionNumber'])->where('EditionNumber',$data[$key]['EditionNumber'])->where('Size',$data[$key]['Size'])->select('*')->get();
                        if (count($quantity_size)>0)
                        {
                          // $upload_obj->updateOrchestraParcelQuantityDetails($value);
                        }
                        else
                        {
                          $upload_obj->insertOrchestraParcelQuantityDetails($data,$key);
                        }
                      }
                      else
                      {
                        $upload_obj->insertOrchestraParcelQuantityDetails($data,$key);
                      }
                    }
                    else
                    {
                      $upload_obj->insertOrchestraParcelQuantityDetails($data,$key);
                    }
                  }
                  else
                  { 
                    $upload_obj->insertOrchestraParcelQuantityDetails($data,$key);
                  }
                }
                else
                {
                  $upload_obj->insertOrchestraParcelQuantityDetails($data,$key);
                }
              }
              $result = response()->json(['error' =>false,'message' => 'Uploaded Successfully'], 200);
              }
              else
              {
                foreach ($data as $key=> $value)
                {
                  OrchestraParcelQuantityDetails::create([
                      'FactoryCode'     => $data[$key]['FactoryCode'],
                      'EditionNumber' => $data[$key]['EditionNumber'],
                      'VersionNumber' => $data[$key]['VersionNumber'],
                      'BuyerPONo' => $data[$key]['BuyerPONo'],
                      'Theme' => $data[$key]['Theme'],
                      'UVC_TCO' => $data[$key]['UVC_TCO'],
                      'Quantity' => $data[$key]['Quantity'],
                      'Color' => $data[$key]['Color'],
                      'UVC' => $data[$key]['UVC'],
                      'Size' => $data[$key]['Size'],
                      'BarcodeForPricesTags' => $data[$key]['BarcodeForPricesTags']
                    ]);
                }
              $result = response()->json(['error' =>false,'message' => 'Uploaded Successfully'], 200);
              }
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
