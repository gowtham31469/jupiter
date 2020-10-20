<?php
namespace App\Classes;
use App\TescoTCL1Orders;
use App\TescoTCL2Orders;
use App\TescoTCL4Orders;
use App\TescoTHL1Orders;
use App\TescoTHL3Orders;
use App\TescoTSL1Orders;
use App\TescoTSL3Orders;
/**
 * Class to update the tesco template orders while saving.
 * @author [Praveen] <[praveen@wizroots.com]>
 */
class SaveTescoOrdersUpdateQuery
{
	/**
	 * method to update the saved tesco orders
	 * @param  [type] $request, $order_id,$template_id,$customer_id,$created_by,
	 * @return number of rows updated.
	 */
	public function updateSavedTescoOrder($request,$order_id,$template_id,$customer_id,$created_by, $order_model)
	{
		try {
              $result = $order_model::where('id',$order_id)
                        ->update([
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
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}

}
