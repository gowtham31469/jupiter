<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\OrchestraParcelDetails;
use App\Classes\Uploads;
use DB;
use App\Customer;
use App\DumpOrchestraPOCsv;

use Illuminate\Support\Facades\Log;

class ProcessOrchestraPOData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      DB::connection()->disableQueryLog();

      $po_data = DumpOrchestraPOCsv::with('price_data')->get();
        // DB::table('dump_orchestra_po_csv')->orderBy('SKU CODE')->chunk(5000, function ($po_data) {
            $upload_obj = new Uploads();

            // $customer_data = $po_data->whereNotIn('SUPPLIER EMAIL', ['_','',null])->unique('SUPPLIER CODE');

            // $upload_obj->createCustomer($customer_data);

            foreach ($po_data as $value) {
                  
                  if (DB::table('orchestra_parcel_details')->select("id")->where('UVC', trim($value->{'REFERENCE CODE'}))->exists())
                  {
                    $parcel_details_po = DB::table('orchestra_parcel_details')->select("id")->where([
                                                                        ['UVC', trim($value->{'REFERENCE CODE'})], 
                                                                        ['BuyerPONo', trim($value->PO)],
                                                                      ])->exists();
                    if ($parcel_details_po)
                    {  
                      $parcel_details_sku_code = DB::table('orchestra_parcel_details')->select("id")->where([
                                                                                ['UVC', trim($value->{'REFERENCE CODE'})],
                                                                                ['BuyerPONo', trim($value->PO)],
                                                                                ['SKU_Code', trim($value->{'SKU CODE'})],
                                                                              ])->exists();
                      if($parcel_details_sku_code)
                      {
                        $parcel_details_last_date = DB::table('orchestra_parcel_details')->select("id")->where([
                                                                                    ['UVC', trim($value->{'REFERENCE CODE'})],
                                                                                    ['BuyerPONo', trim($value->PO)],
                                                                                    ['SKU_Code', trim($value->{'SKU CODE'})],
                                                                                    ['Po_Change_Date', '<>', trim($value->{'PO CHANGE DATE'})],
                                                                                  ])->exists();
          
                        if($parcel_details_last_date)
                        {
                          // collision. update temp parcel details table
                           $upload_obj->updateOrchestraTempParcelDetails($value);
                           continue;
                        }
                      }
                      else
                      {
                        $parcel_details_last_date = DB::table('orchestra_parcel_details')->select("id")->where([
                                                                                    ['UVC', trim($value->{'REFERENCE CODE'})],
                                                                                    ['BuyerPONo', trim($value->PO)],
                                                                                    ['Po_Change_Date','<>',trim($value->{'PO CHANGE DATE'})],
                                                                                  ])->exists();
          
                        if($parcel_details_last_date)
                        {
                          //collision. update temp parcel details table 
                           $upload_obj->updateOrchestraTempParcelDetails($value);
                           continue;
                        }
                        else 
                        {
                          $upload_obj->insertOrchestraParcelDetails($value);
                          continue;
                        }
                      }
                    }
                    else
                    {
                      $upload_obj->insertOrchestraParcelDetails($value);
                      continue;
                    }
                  }
                  else
                  {
                    $upload_obj->insertOrchestraParcelDetails($value);
                    continue;
                  }
              
              foreach ($value->price_data as $key => $price_row) {
                // get po details from orchestra_parcel_details for this sku code.
                $parcel_details_data = DB::table('orchestra_parcel_details')->where('SKU_Code', trim($price_row->{'SKU CODE'}))
                ->select(
                'SKU_Code',
                'FactoryCode',
                'BuyerPONo',
                'Theme',
                'Quantity',
                'Color_Code',
                'UVC',
                'Size_Label',
                'Po_Status_Code',
                'Po_Status_Code_Description'
                )
                ->first();

                // check sku code already exists or not 
                if (DB::table('orchestra_parcel_quantity_details')->select("id")->where('UVC_TCO', trim($price_row->{'SKU CODE'}))->exists() && $parcel_details_data) {

                    // check PO details missing for this sku code (ie, PO number = null).
                    $po_missing = DB::table('orchestra_parcel_quantity_details')->select("id")->where([
                        ['UVC_TCO', trim($price_row->{'SKU CODE'})],
                        ['BuyerPONo', null],
                    ])->exists();

                    if ($po_missing) {
                        // get po data for current uvc_tco value and update in parcel_quantity_details table.
                            $upload_obj->updateOrchestraParcelQuantityPODetails($parcel_details_data);
                            continue;
                    }

                    // check price details already exists for this sku code with different Country_Code.
                    $diff_order = DB::table('orchestra_parcel_quantity_details')->select("id")->where([
                        ['UVC_TCO', trim($price_row->{'SKU CODE'})],
                        ['Country_Code', trim($price_row->{'COUNTRY CODE'})],
                    ])->exists();

                    if ($diff_order) {
                        // check price details already exists with same UVC_TCO ,COUNTRY CODE & different Last_Change_Date
                        $quantity_date = DB::table('orchestra_parcel_quantity_details')->select("id")->where([
                            ['UVC_TCO', trim($price_row->{'SKU CODE'})],
                            ['Country_Code', trim($price_row->{'COUNTRY CODE'})],
                            ['Last_Change_Date', '<>', trim($price_row->LAST_CHANGE_DATE)],
                        ])->exists();

                        if ($quantity_date) {
                            // collision. update temp orchestra parcel quantity details table 
                            $upload_obj->updateOrchestraTempParcelQuantityDetails($price_row, $parcel_details_data);
                            continue;
                        }
                    } else {
                        // check price details already exists with same UVC_TCO & Last_Change_Date
                        $quantity_date = DB::table('orchestra_parcel_quantity_details')->select("id")->where([
                            ['UVC_TCO', trim($price_row->{'SKU CODE'})],
                            ['Last_Change_Date', trim($price_row->LAST_CHANGE_DATE)],
                        ])->exists();

                        // insert price details, iff order with same sku code, country code and last change date doesn't exists.
                        if ($quantity_date) {
                            // insert price details in orchestra_parcel_quantity_details, iff po details exists for this sku code and COUNTRY CODE.
                          
                            $upload_obj->insertOrchestraParcelQuantityDetails($price_row, $parcel_details_data);
                            continue;

                        } else {
                            // collision. update temp orchestra parcel quantity details table 
                            $upload_obj->updateOrchestraTempParcelQuantityDetails($price_row, $parcel_details_data);
                            continue;
                        }
                    }
                } else {
                    // insert price details in orchestra_parcel_quantity_details, iff po details exists for this sku code and COUNTRY CODE.
                    
                    $upload_obj->insertOrchestraParcelQuantityDetails($price_row, $parcel_details_data);
                    continue;
                    
                }
                
              }
            }
        // });
    }
}
