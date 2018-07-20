<?php
namespace App\Classes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;
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
	 * method to update the tesco TCL1 orders
	 * @param  [type] $request, $order_id,$template_id
	 * @return number of rows updated.
	 */
	public function updateTCL1Order($request,$order_id,$template_id,$customer_id,$created_by)
	{
		try {
              $result = TescoTCL1Orders::where('id',$order_id)
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
                          'artwork_status'       => "Saved",
                          'print_status'         => "Saved",
                          'template_id'          => $template_id,
                          'pi_status'            => "",
                          'po_status'            => "",
                          'order_placed_by'      => $created_by
                        ]);
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}
  /**
   * method to update the tesco TCL2 orders
   * @param  [type] $request, $order_id,$template_id
   * @return number of rows updated.
   */
  public function updateTCL2Order($request,$order_id,$template_id,$customer_id,$created_by)
  {
    try {
            $result = TescoTCL2Orders::where('id',$order_id)
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
                          'artwork_status'       => "Saved",
                          'print_status'         => "Saved",
                          'template_id'          => $template_id,
                          'pi_status'            => "",
                          'po_status'            => "",
                          'order_placed_by'      => $created_by
                        ]);
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
  /**
   * method to update the tesco TSL1 orders
   * @param  [type] $request, $order_id,$template_id
   * @return number of rows updated.
   */
  public function updateTSL1Order($request,$order_id,$template_id,$customer_id,$created_by)
  {
    try {
            $result = TescoTSL1Orders::where('id',$order_id)
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
                          'artwork_status'       => "Saved",
                          'print_status'         => "Saved",
                          'template_id'          => $template_id,
                          'pi_status'            => "",
                          'po_status'            => "",
                          'order_placed_by'      => $created_by
                        ]);
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
  /**
   * method to update the tesco THL1 orders
   * @param  [type] $request, $order_id,$template_id
   * @return number of rows updated.
   */
  public function updateTHL1Order($request,$order_id,$template_id,$customer_id,$created_by)
  {
    try {
              $result = TescoTHL1Orders::where('id',$order_id)
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
                          'artwork_status'       => "Saved",
                          'print_status'         => "Saved",
                          'template_id'          => $template_id,
                          'pi_status'            => "",
                          'po_status'            => "",
                          'order_placed_by'      => $created_by
                        ]);
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
  /**
   * method to update the tesco TCL4 orders
   * @param  [type] $request, $order_id,$template_id
   * @return number of rows updated.
   */
  public function updateTCL4Order($request,$order_id,$template_id,$customer_id,$created_by)
  {
    try {
              $result = TescoTCL4Orders::where('id',$order_id)
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
                            'artwork_status'       => "Saved",
                            'print_status'         => "Saved",
                            'template_id'          => $template_id,
                            'pi_status'            => "",
                            'po_status'            => "",
                            'is_order_count_empty' => "",
                            'order_placed_by'      => $created_by
                          ]);
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
  /**
   * method to update the tesco TSL3 orders
   * @param  [type] $request, $order_id,$template_id
   * @return number of rows updated.
   */
  public function updateTSL3Order($request,$order_id,$template_id,$customer_id,$created_by)
  {
    try {
              $result = TescoTSL3Orders::where('id',$order_id)
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
                          'artwork_status'       => "Saved",
                          'print_status'         => "Saved",
                          'template_id'          => $template_id,
                          'pi_status'            => "",
                          'po_status'            => "",
                          'order_placed_by'      => $created_by
                        ]);
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
  /**
   * method to update the tesco THL3 orders
   * @param  [type] $request, $order_id,$template_id
   * @return number of rows updated.
   */
  public function updateTHL3Order($request,$order_id,$template_id,$customer_id,$created_by)
  {
    try {
              $result = TescoTHL3Orders::where('id',$order_id)
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
                          'artwork_status'       => "Saved",
                          'print_status'         => "Saved",
                          'template_id'          => $template_id,
                          'pi_status'            => "",
                          'po_status'            => "",
                          'order_placed_by'      => $created_by
                        ]);
            
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
}
