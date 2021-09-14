<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\OrchestraParcelDetails;
use App\OrchestraParcelQuantityDetails;
use App\Classes\Uploads;
use DB;

use Illuminate\Support\Facades\Log;
class ProcessOrchestraPriceData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $price_data;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($price_data)
    {
        $this->price_data = $price_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::connection()->disableQueryLog();
        $upload_obj = new Uploads();
        
        foreach ($this->price_data as $value) {
            // get po details from orchestra_parcel_details for this sku code.
            $parcel_details_data = DB::table('orchestra_parcel_details')->where('SKU_Code', trim($value->{'SKU CODE'}))
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
            if (DB::table('orchestra_parcel_quantity_details')->select("id")->where('UVC_TCO', trim($value->{'SKU CODE'}))->exists() && $parcel_details_data) {

                // check PO details missing for this sku code (ie, PO number = null).
                $po_missing = DB::table('orchestra_parcel_quantity_details')->select("id")->where([
                    ['UVC_TCO', trim($value->{'SKU CODE'})],
                    ['BuyerPONo', null],
                ])->exists();

                if ($po_missing) {
                    // get po data for current uvc_tco value and update in parcel_quantity_details table.
                        $upload_obj->updateOrchestraParcelQuantityPODetails($parcel_details_data);
                        continue;
                }

                // check price details already exists for this sku code with different Country_Code.
                $diff_order = DB::table('orchestra_parcel_quantity_details')->select("id")->where([
                    ['UVC_TCO', trim($value->{'SKU CODE'})],
                    ['Country_Code', trim($value->{'COUNTRY CODE'})],
                ])->exists();

                if ($diff_order) {
                    // check price details already exists with same UVC_TCO ,COUNTRY CODE & different Last_Change_Date
                    $quantity_date = DB::table('orchestra_parcel_quantity_details')->select("id")->where([
                        ['UVC_TCO', trim($value->{'SKU CODE'})],
                        ['Country_Code', trim($value->{'COUNTRY CODE'})],
                        ['Last_Change_Date', '<>', trim($value->LAST_CHANGE_DATE)],
                    ])->exists();

                    if ($quantity_date) {
                        // collision. update temp orchestra parcel quantity details table 
                        $upload_obj->updateOrchestraTempParcelQuantityDetails($value, $parcel_details_data);
                        continue;
                    }
                } else {
                    // check price details already exists with same UVC_TCO & Last_Change_Date
                    $quantity_date = DB::table('orchestra_parcel_quantity_details')->select("id")->where([
                        ['UVC_TCO', trim($value->{'SKU CODE'})],
                        ['Last_Change_Date', trim($value->LAST_CHANGE_DATE)],
                    ])->exists();

                    // insert price details, iff order with same sku code, country code and last change date doesn't exists.
                    if ($quantity_date) {
                        // insert price details in orchestra_parcel_quantity_details, iff po details exists for this sku code and COUNTRY CODE.
                       
                        $upload_obj->insertOrchestraParcelQuantityDetails($value, $parcel_details_data);
                        continue;

                    } else {
                        // collision. update temp orchestra parcel quantity details table 
                        $upload_obj->updateOrchestraTempParcelQuantityDetails($value, $parcel_details_data);
                        continue;
                    }
                }
            } else {
                // insert price details in orchestra_parcel_quantity_details, iff po details exists for this sku code and COUNTRY CODE.
                
                $upload_obj->insertOrchestraParcelQuantityDetails($value, $parcel_details_data);
                continue;
                
            }
        }
    }
}
