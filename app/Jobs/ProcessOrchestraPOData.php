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
        DB::table('dump_orchestra_po_csv')->orderBy('SKU CODE')->chunk(5000, function ($po_data) {
            $upload_obj = new Uploads();

            $customer_data = $po_data->whereNotIn('SUPPLIER EMAIL', ['_','',null])->unique('SUPPLIER CODE');

            $upload_obj->createCustomer($customer_data);

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
              unset($value);
            }
        });
    }
}
