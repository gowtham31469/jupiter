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
          $get_sub_module = $module_var->getSubModules(33);
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
     * method to upload the item details csv
     * @return [type] [description]
     */
    public function uploadLabelDetails(Request $request)
    {
      try
      {
        $upload_obj = new Uploads();
        if($request->hasFile('file'))
        {
            ini_set('max_execution_time', 1000);
            $delimiter=';';
            $path = $request->file('file')->getRealPath();
            $filename = $request->file('file')->getClientOriginalName();
            $extension  = $request->file('file')->getClientOriginalExtension();
            $filename = strtok($filename, " ");
            if (preg_match('/ItemDetails_ORCHESTRA/',$filename))
            {
                // $data = Excel::load($path, function($reader) {})->get();
                $data =  $upload_obj->csv_to_array($path,$delimiter);
                $data_exists = OrchestraItemDetails::select()->get();
                $upload_obj->isCustomerExists($data);
                if (count($data_exists)>0)
                {
                  foreach ($data as $key => $value)
                  {
                    $collection_exixts = DB::table('orchestra_item_details')->where('Collection',$data[$key]['Collection'])->select('*')->get();
                    if (count($collection_exixts)>0)
                    {
                      $order_uvc = DB::table('orchestra_item_details')->where('Collection',$data[$key]['Collection'])->where('UVC',$data[$key]['UVC'])->select('*')->get();
                      if (count($order_uvc)>0)
                      {
                        $order_po = DB::table('orchestra_item_details')->where('Collection',$data[$key]['Collection'])->where('UVC',$data[$key]['UVC'])->where('BuyerPONo',$data[$key]['BuyerPONo'])->select('*')->get();
                        if (count($order_po)>0)
                        {
                          $order_version = DB::table('orchestra_item_details')->where('Collection',$data[$key]['Collection'])->where('UVC',$data[$key]['UVC'])->where('BuyerPONo',$data[$key]['BuyerPONo'])->where('VersionNumber',$data[$key]['VersionNumber'])->select('*')->get();
                          // $order_edition = DB::table('orchestra_item_details')->where('Collection',$data[$key]['Collection'])->where('UVC',$data[$key]['UVC'])->where('BuyerPONo',$data[$key]['BuyerPONo'])->where('EditionNumber',$data[$key]['EditionNumber'])->select('*')->get();
                          // || count($order_edition)>0
                          // ->where('EditionNumber',$data[$key]['EditionNumber'])
                          if (count($order_version)>0)
                          {
                              $order_size = DB::table('orchestra_item_details')->where('Collection',$data[$key]['Collection'])->where('UVC',$data[$key]['UVC'])->where('BuyerPONo',$data[$key]['BuyerPONo'])->where('VersionNumber',$data[$key]['VersionNumber'])->where('Size',$data[$key]['Size'])->select('*')->get();
                              if (count($order_size)>0)
                              {

                                 $upload_obj->updateOrchestraItemDetails($order_size,$data,$key);

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
                    else
                    {
                      $upload_obj->insertOrchestraItemDetails($data,$key);
                    }
                  }
                  $result = response()->json(['error' =>false,'message' => 'Uploaded Successfully'], 200);
                }
                else
                {
                  foreach ($data as $key => $value)
                  { 
                    $upload_obj->insertOrchestraItemDetails($data,$key);
                  }
                  $result = response()->json(['error' =>false,'message' => 'Uploaded Successfully'], 200);
                }
            }
            else
            {
              $result = response()->json(['error' =>true,'message' => 'Invalid File'], 403);
            }
        }
        else
        {
          $result = response()->json(['error' =>true,'message' => 'File missing'], 403);
        }
      }
      catch (\Exception $e)
      {
        $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
      return $result;
    }
    
    public function getCSVUpdate()
    {
      try
      {
          $updates =  DB::table('temp_orchestra_item_details')->get();
          $result = response()->json(['error' =>false,'message' => 'Uploaded Successfully','updates'=>$updates], 200);
      }
      catch (Exception $e)
      {
        $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }

      return $result;
      
    }


    /**
     * method to upload the parcel details csv
     * @return [type] [description]
     */
    public function uploadParcelDetailsFileOne(Request $request)
    {
      try
      {
        $upload_obj = new Uploads();
        ini_set('max_execution_time', 1000);
        $delimiter = ";";
        $path     = $request->file('file')->getRealPath();
        $filename = $request->file('file')->getClientOriginalName();
        $extension  = $request->file('file')->getClientOriginalExtension();
        // $data  = Excel::load($path, function($reader) {})->get();
        $filename = strtok($filename, " ");
        $data     =  $upload_obj->csv_to_array($path,$delimiter);
        
          if (preg_match('/ParcelDetails_File1_ORCHESTRA/',$filename))
          {
            $data_exists = OrchestraParcelDetails::select()->get();
            if (count($data_exists)>0)
            {
              foreach ($data as $key => $value)
              {
                  $parcel_uvc = DB::table('orchestra_parcel_details')->where('UVC',$data[$key]['UVC'])->select('*')->get();
                  if (count($parcel_uvc)>0)
                  {
                    $parcel_po = DB::table('orchestra_parcel_details')->where('UVC',$data[$key]['UVC'])->where('BuyerPONo',$data[$key]['BuyerPONo'])->select('*')->get();
                    if (count($parcel_po)>0)
                    {
                      $parcel_version = DB::table('orchestra_parcel_details')->where('UVC',$data[$key]['UVC'])->where('BuyerPONo',$data[$key]['BuyerPONo'])->where('VersionNumber',$data[$key]['VersionNumber'])->select('*')->get();
                      if (count($parcel_version)>0)
                      {
                        $parcel_edition = DB::table('orchestra_parcel_details')->where('UVC',$data[$key]['UVC'])->where('BuyerPONo',$data[$key]['BuyerPONo'])->where('VersionNumber',$data[$key]['VersionNumber'])->where('EditionNumber',$data[$key]['EditionNumber'])->select('*')->get();
                        if (count($parcel_edition))
                        {
                          $parcel_std = DB::table('orchestra_parcel_details')->where('UVC',$data[$key]['UVC'])->where('BuyerPONo',$data[$key]['BuyerPONo'])->where('VersionNumber',$data[$key]['VersionNumber'])->where('EditionNumber',$data[$key]['EditionNumber'])->where('IsSTD',$data[$key]['IsSTD'])->select('*')->get();
                          if (count($parcel_std)>0)
                          {
                            // foreach ($parcel_std as $vals)
                            // {
                            //   if ($vals->IsSTD == 1)
                            //   {
                            //     DB::table('orchestra_parcel_quantity_details')
                            //      ->where('UVC',$data[$key]['UVC'])
                            //      ->where('BuyerPONo',$data[$key]['BuyerPONo'])
                            //      ->where('VersionNumber',$data[$key]['VersionNumber'])
                            //      ->where('EditionNumber',$data[$key]['EditionNumber'])
                            //      ->delete();
                            //   }
                              $upload_obj->updateOrchestraParcelDetails($data,$key);
                            // }
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
                  else
                  {
                    $upload_obj->insertOrchestraParcelDetails($data,$key);
                  }
                }
                $result = response()->json(['error' =>false,'message' => 'Uploaded Successfully'], 200);
              }
              else
              {
                foreach ($data as $key=> $value)
                {
                  $upload_obj->insertOrchestraParcelDetails($data,$key);
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

      /**
     * method to upload the parcel details file 2 csv
     * @return [type] [description]
     */
    public function uploadParcelDetailsFileTwo(Request $request)
    {
      try
      {
        $upload_obj = new Uploads();
        ini_set('max_execution_time', 1000);
        $delimiter = ";";
        $path     = $request->file('file')->getRealPath();
        $filename = $request->file('file')->getClientOriginalName();
        $extension  = $request->file('file')->getClientOriginalExtension();
        // $data  = Excel::load($path, function($reader) {})->get();
        $filename = strtok($filename, " ");
        $data     =  $upload_obj->csv_to_array($path,$delimiter);
       
          if (preg_match('/ParcelDetails_File2_ORCHESTRA/',$filename))
          {
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
