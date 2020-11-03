<?php
namespace App\Classes;
use DB;
use App\TescoTCL1Orders;
use App\TescoTCL2Orders;
use App\TescoTCL4Orders;
use App\TescoTHL1Orders;
use App\TescoTHL3Orders;
use App\TescoTSL1Orders;
use App\TescoTSL3Orders;
/**
 * Class to save the tesco template orders
 * @author [Praveen] <[praveen@wizroots.com]>
 */
class SaveTescoOrders
{
  /**
   * method to save the tesco orders
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function saveTescoOrder($request,$template_id,$created_by)
  {
    try {
           /** [ Get company id for template ] */
           $template_data = DB::table('tesco_item_master')->select('item_name')->where('id','=',$template_id)->first();

           //Dynamic function name and database model name for each templates
           $order_model = 'App\\'."Tesco".str_replace(" ","",$template_data->item_name)."Orders";

           if($request['supplier'] != '')
           {
             $customer_ids = DB::table("customer_metadata")->where('customer_metadata_id','=',$request['supplier'])->select('customer_id')->first();
             $customer_id = $customer_ids != ''?$customer_ids->customer_id:NULL;
           }
           else
           {
             $customer_id = NULL;
           }

            /** Insert order details first time. If data already exits, update old values**/
           return $order_model::updateOrCreate([strtolower($request['zone']).'_style_ref' => $request[strtolower($request['zone']).'_style'], 'po_number' => $request['po_number'], 'order_status' => 'Saved'],[
            'order_id'             => "",
            'zone'                 => $request['zone'],
            'template_type'        => $request['type'],
            'po_number'            => $request['po_number'],
            'uk_style_ref'         => $request['uk_style'],
            'ce_style_ref'         => $request['ce_style'],
            'season_code'          => $request['season_code'],
            'short_description'    => $request['description'],
            'eqos_site_code'       => $request['eqos'],
            'manufacture_date'     => $request['manufacture_date'],
            'tesco_department_id'  => $request['department'],
            'tesco_brand_id'       => $request['brand'],
            'tesco_supplier_id'    => $customer_id,
            'tesco_metadata_supplier_id' => $request['supplier'],
            'rfid_compliant'       => $request['rfid'],
            'tag_at_source'        => $request['tag_at_source'],
            'order_status'         => "Saved",
            'template_id'          => $template_id,
            'pi_status'            => "",
            'po_status'            => "",
            'order_placed_by'      => $created_by,
            'ocn_number'           => $request['ocn_no'],
            'full_description'     => $request['full_description']
          ]);

        } catch (\Exception $e)
        {
             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }
 
}
