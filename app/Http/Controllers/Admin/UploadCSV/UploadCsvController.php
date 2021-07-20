<?php

namespace App\Http\Controllers\Admin\UploadCSV;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use App\OrchestraParcelDetails;
use App\OrchestraParcelQuantityDetails;
use App\Classes\Uploads;
use App\Classes\Modules;
use App\TescoCountries;
use App\TescoComponents;
use App\TescoFabricLists;
use App\TescoKidsWarningStatements;
use App\TescoAdditionalInstructions;
use Carbon\Carbon;
use DB;


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


    public function uploadTranslation()
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
          return view('Admin/Upload CSV/upload-translation');
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

    public function uploadSizeChart()
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
          return view('Admin/Upload CSV/upload-size-chart');
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


    public function uploadSizeChartCsv(Request $request)
    {
      try
      {
        ini_set('max_execution_time', 5000);
        $delimiter = ";";
        $path     = $request->file('file')->getRealPath();
        $filename = strtok($request->file('file')->getClientOriginalName(), " ");

          if (preg_match('/UK_CE_PRIMARY/',$filename))
          {
            $rows= \Excel::load($path, function($reader) {
                $reader->noHeading();
                $reader->skipRows(3); // skip first three rows as they contain headings
            })->get();
            $rows = $rows->toArray();

            DB::statement('TRUNCATE TABLE tesco_primary_size RESTART IDENTITY CASCADE');
            DB::statement('TRUNCATE TABLE tesco_secondary_size_chart RESTART IDENTITY CASCADE');

            foreach ($rows as $key => $value)
            {
                $department = $value[0];
                $uk_size = $value[1];
                $ce_size = $value[2];
                $category1 = preg_replace('/[^A-Za-z0-9\-]/', '', $value[5]);
                $category1_value = $value[9];
                $category2 = preg_replace('/[^A-Za-z0-9\-]/', '', $value[10]);
                $category2_value = $value[14];
                $category3 = preg_replace('/[^A-Za-z0-9\-]/', '', $value[15]);
                $category3_value = $value[19];

                $tesco_size_department = DB::table('tesco_size_department')->select('*')->where('department_name',$department)->get();

                if(count($tesco_size_department) > 0)
                {
                  $tesco_primary_size = array(
                    'tesco_size_department_id' => $tesco_size_department{0}->id, 'department_title' => $tesco_size_department{0}->department_name, 'uk_size' => $uk_size, 'ce_size' => $ce_size, 'is_flag' => 1, 'created_at'=> Carbon::now()
                  );

                  $tesco_secondary_size_category1 = DB::table('tesco_secondary_size_category')->select('*')->where('category_name',$category1)->get();
                  $tesco_secondary_size_category2 = DB::table('tesco_secondary_size_category')->select('*')->where('category_name',$category2)->get();
                  $tesco_secondary_size_category3 = DB::table('tesco_secondary_size_category')->select('*')->where('category_name',$category3)->get();

                  $count_sizes = count($tesco_secondary_size_category1) + count($tesco_secondary_size_category2) + count($tesco_secondary_size_category3);
                  if($count_sizes != 0)
                  {
                    $tesco_primary_size_id = DB::table('tesco_primary_size')->insertGetId($tesco_primary_size);

                  }
                  
                  if(count($tesco_secondary_size_category1) > 0)
                  {
                    $tesco_secondary_size_chart1 = array('tesco_primary_size_id' => $tesco_primary_size_id, 'tesco_secondary_size_category_id' => $tesco_secondary_size_category1{0}->id, 'secondary_size' => $category1_value, 'created_at'=> Carbon::now());

                    $tesco_secondary_size_chart_id1 = DB::table('tesco_secondary_size_chart')->insert($tesco_secondary_size_chart1);
                  }

                  if(count($tesco_secondary_size_category2) > 0)
                  {
                    $tesco_secondary_size_chart2 = array('tesco_primary_size_id' => $tesco_primary_size_id, 'tesco_secondary_size_category_id' => $tesco_secondary_size_category2{0}->id, 'secondary_size' => $category2_value, 'created_at'=> Carbon::now());

                    $tesco_secondary_size_chart_id2 = DB::table('tesco_secondary_size_chart')->insert($tesco_secondary_size_chart2);
                  }

                  if(count($tesco_secondary_size_category3) > 0)
                  {
                    $tesco_secondary_size_chart3 = array('tesco_primary_size_id' => $tesco_primary_size_id, 'tesco_secondary_size_category_id' => $tesco_secondary_size_category3{0}->id, 'secondary_size' => $category3_value, 'created_at'=> Carbon::now());

                    $tesco_secondary_size_chart_id3 = DB::table('tesco_secondary_size_chart')->insert($tesco_secondary_size_chart3);
                  }

                }
            }

            $result = response()->json(['error' =>false,'message' => 'Size Chart File Uploaded Successfully'], 200);
            
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


    public function uploadTranslationCsv(Request $request)
    {
      try
      {
        ini_set('max_execution_time', 5000);
        $delimiter = ";";
        $path     = $request->file('file')->getRealPath();
        $filename = strtok($request->file('file')->getClientOriginalName(), " ");

          if (preg_match('/Clothing/',$filename))
          {
              $data = \Excel::load($path)->get();

              if($data->count()){
                  foreach ($data as $key => $value) {
                    if($value->category == "COUNTRIES")
                    {
                      if($value->english !=  null && $value->english !=  "" )
                      {
                        $country_arr[] = ['english' => $value->english, 'polish' => $value->polish, 'czech' => $value->czech, 'slovak' => $value->slovak, 'hungarian' => $value->hungarian,'is_enabled' => 1, 'created_at'=> Carbon::now()];
                      }
                    }
                    else if($value->category == "PRODUCT DESCRIPTION")
                    {
                      if($value->english !=  null && $value->english !=  "" )
                      {
                        $comp_arr[] = ['english' => $value->english, 'polish' => $value->polish, 'czech' => $value->czech, 'slovak' => $value->slovak, 'hungarian' => $value->hungarian,'is_enabled' => 1, 'created_at'=> Carbon::now()];
                      }
                    }
                    else if($value->category == "FIBRE NAMES")
                    {
                      if($value->subcategory == "COMPOSITION")
                      {
                        if($value->english !=  null && $value->english !=  "" )
                        {
                          $comp_arr[] = ['english' => $value->english, 'polish' => $value->polish, 'czech' => $value->czech, 'slovak' => $value->slovak, 'hungarian' => $value->hungarian,'is_enabled' => 1, 'created_at'=> Carbon::now()];
                        }
                      }
                      else
                      {
                        if($value->english !=  null && $value->english !=  "" )
                        {
                          $fibre_arr[] = ['english' => $value->english, 'polish' => $value->polish, 'czech' => $value->czech, 'slovak' => $value->slovak, 'hungarian' => $value->hungarian,'is_enabled' => 1, 'created_at'=> Carbon::now()];
                        }
                      }
                    }
                    else if($value->category == "CARE INSTRUCTIONS")
                    {
                      if($value->english !=  null && $value->english !=  "" )
                      {
                        $care_arr[] = ['english' => $value->english, 'polish' => $value->polish, 'czech' => $value->czech, 'slovak' => $value->slovak, 'hungarian' => $value->hungarian,'is_enabled' => 1, 'created_at'=> Carbon::now()];
                      }
                    }
                    else if($value->category == "EN17 WARNINGS")
                    {
                      if($value->english !=  null && $value->english !=  "" )
                      {
                        $kids_arr[] = ['english' => $value->english, 'polish' => $value->polish, 'czech' => $value->czech, 'slovak' => $value->slovak, 'hungarian' => $value->hungarian,'is_enabled' => 1, 'created_at'=> Carbon::now()];
                      }
                    }
                    else if($value->english == "part of set")
                    {
                      $care_arr[] = ['english' => $value->english, 'polish' => $value->polish, 'czech' => $value->czech, 'slovak' => $value->slovak, 'hungarian' => $value->hungarian,'is_enabled' => 1, 'created_at'=> Carbon::now()];
                    }
                    else if($value->english == "tiny baby")
                    {
                      $tiny = ['english' => $value->english, 'polish' => $value->polish, 'czech' => $value->czech, 'slovak' => $value->slovak, 'hungarian' => $value->hungarian,'is_enabled' => 1, 'created_at'=> Carbon::now()];

                      DB::table('tesco_translation')
                          ->where('id', 1)
                          ->update($tiny);
                    }
                    else if($value->english == "newborn")
                    {
                      $newborn = ['english' => $value->english, 'polish' => $value->polish, 'czech' => $value->czech, 'slovak' => $value->slovak, 'hungarian' => $value->hungarian,'is_enabled' => 1, 'created_at'=> Carbon::now()];

                      DB::table('tesco_translation')
                          ->where('id', 2)
                          ->update($newborn);
                    }
                    else if($value->english == "up to 1 mth")
                    {
                      $up1m = ['english' => $value->english, 'polish' => $value->polish, 'czech' => $value->czech, 'slovak' => $value->slovak, 'hungarian' => $value->hungarian,'is_enabled' => 1, 'created_at'=> Carbon::now()];

                      DB::table('tesco_translation')
                          ->where('id', 3)
                          ->update($up1m);
                    }
                    else if($value->english == "up to 3 mths")
                    {
                      $up3m = ['english' => $value->english, 'polish' => $value->polish, 'czech' => $value->czech, 'slovak' => $value->slovak, 'hungarian' => $value->hungarian,'is_enabled' => 1, 'created_at'=> Carbon::now()];

                      DB::table('tesco_translation')
                          ->where('id', 4)
                          ->update($up3m);
                    }
                    else if($value->english == "one size")
                    {
                      $onesize = ['english' => $value->english, 'polish' => $value->polish, 'czech' => $value->czech, 'slovak' => $value->slovak, 'hungarian' => $value->hungarian,'is_enabled' => 1, 'created_at'=> Carbon::now()];

                      DB::table('tesco_translation')
                          ->where('id', 5)
                          ->update($onesize);
                    }
                  }

                 //print_r($arr);
                  if(!empty($country_arr)){
                      DB::statement('TRUNCATE TABLE tesco_countries RESTART IDENTITY CASCADE');
                      DB::table('tesco_countries')->insert($country_arr);
                  }

                  if(!empty($comp_arr)){
                    DB::statement('TRUNCATE TABLE tesco_components RESTART IDENTITY CASCADE');
                    DB::table('tesco_components')->insert($comp_arr);
                  }

                  if(!empty($fibre_arr)){
                    DB::statement('TRUNCATE TABLE tesco_fabriclists RESTART IDENTITY CASCADE');
                    DB::table('tesco_fabriclists')->insert($fibre_arr);
                  }

                  if(!empty($care_arr)){
                    DB::statement('TRUNCATE TABLE tesco_care_additional_instructions RESTART IDENTITY CASCADE');
                    DB::table('tesco_care_additional_instructions')->insert($care_arr);
                  }

                  if(!empty($kids_arr)){
                    DB::statement('TRUNCATE TABLE tesco_kids_warning_statements RESTART IDENTITY CASCADE');
                    DB::table('tesco_kids_warning_statements')->insert($kids_arr);
                  }
                  $result = response()->json(['error' =>false,'message' => 'Translation File Uploaded Successfully'], 200);
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
