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
   * Class to save the tesco template orders
   * @author [Praveen] <[praveen@wizroots.com]>
   */
class SaveTescoOrders
{
	/**
	 * method to save the tesco TCL1 orders
	 * @param  [type] $request, $template_id
	 * @return tesco order table id (inserted_id)
	 */
	public function saveTCL1Order($request,$template_id)
	{
		try {
           /** Insert order details first time. If data already exits, update old values**/
           if($request['is_insert'] == 'true')
           {
             $result = TescoTCL1Orders::create([
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
                          'tesco_supplier_id'    => $request['supplier'],
                          'rfid_compliant'       => $request['rfid'],
                          'tag_at_source'        => $request['tag_at_source'],
                          'order_status'         => "Saved",
                          'artwork_status'       => "Saved",
                          'print_status'         => "Saved",
                          'template_id'          => $template_id,
                          'pi_status'            => "",
                          'po_status'            => "",
                          'is_order_count_empty' => ""
                        ]);
           }
           else
           {
              $result = TescoTCL1Orders::where('id',$request['tcl1_last_id'])
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
                          'tesco_supplier_id'    => $request['supplier'],
                          'rfid_compliant'       => $request['rfid'],
                          'tag_at_source'        => $request['tag_at_source'],
                          'order_status'         => "Saved",
                          'artwork_status'       => "Saved",
                          'print_status'         => "Saved",
                          'template_id'          => $template_id,
                          'pi_status'            => "",
                          'po_status'            => "",
                          'is_order_count_empty' => ""
                        ]);
           }
            
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}
  /**
   * method to save the tesco TCL2 orders
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function saveTCL2Order($request,$template_id)
  {
    try {
           /** Insert order details first time. If data already exits, update old values**/
           if($request['is_insert'] == 'true')
           {
              $result = TescoTCL2Orders::create([
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
                          'tesco_supplier_id'    => $request['supplier'],
                          'rfid_compliant'       => $request['rfid'],
                          'tag_at_source'        => $request['tag_at_source'],
                          'order_status'         => "Saved",
                          'artwork_status'       => "Saved",
                          'print_status'         => "Saved",
                          'template_id'          => $template_id,
                          'pi_status'            => "",
                          'po_status'            => "",
                          'is_order_count_empty' => ""
                        ]);
           }
           else
           {
            $result = TescoTCL2Orders::where('id',$request['tcl2_last_id'])
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
                          'tesco_supplier_id'    => $request['supplier'],
                          'rfid_compliant'       => $request['rfid'],
                          'tag_at_source'        => $request['tag_at_source'],
                          'order_status'         => "Saved",
                          'artwork_status'       => "Saved",
                          'print_status'         => "Saved",
                          'template_id'          => $template_id,
                          'pi_status'            => "",
                          'po_status'            => "",
                          'is_order_count_empty' => ""
                        ]);
           }
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
  /**
   * method to save the tesco TSL1 orders
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function saveTSL1Order($request,$template_id)
  {
    try {
           /** Insert order details first time. If data already exits, update old values**/
           if($request['is_insert'] == 'true')
           {
              $result = TescoTSL1Orders::create([
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
                          'tesco_supplier_id'    => $request['supplier'],
                          'rfid_compliant'       => $request['rfid'],
                          'tag_at_source'        => $request['tag_at_source'],
                          'order_status'         => "Saved",
                          'artwork_status'       => "Saved",
                          'print_status'         => "Saved",
                          'template_id'          => $template_id,
                          'pi_status'            => "",
                          'po_status'            => "",
                          'is_order_count_empty' => ""
                        ]);
           }
           else
           {
            $result = TescoTSL1Orders::where('id',$request['tsl1_last_id'])
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
                          'tesco_supplier_id'    => $request['supplier'],
                          'rfid_compliant'       => $request['rfid'],
                          'tag_at_source'        => $request['tag_at_source'],
                          'order_status'         => "Saved",
                          'artwork_status'       => "Saved",
                          'print_status'         => "Saved",
                          'template_id'          => $template_id,
                          'pi_status'            => "",
                          'po_status'            => "",
                          'is_order_count_empty' => ""
                        ]);
           }
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
  /**
   * method to save the tesco THL1 orders
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function saveTHL1Order($request,$template_id)
  {
    try {
           /** Insert order details first time. If data already exits, update old values**/
           if($request['is_insert'] == 'true')
           {
            $result = TescoTHL1Orders::create([
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
                          'tesco_supplier_id'    => $request['supplier'],
                          'rfid_compliant'       => $request['rfid'],
                          'tag_at_source'        => $request['tag_at_source'],
                          'order_status'         => "Saved",
                          'artwork_status'       => "Saved",
                          'print_status'         => "Saved",
                          'template_id'          => $template_id,
                          'pi_status'            => "",
                          'po_status'            => "",
                          'is_order_count_empty' => ""
                        ]);
            }
            else
            {
              $result = TescoTHL1Orders::where('id',$request['thl1_last_id'])
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
                          'tesco_supplier_id'    => $request['supplier'],
                          'rfid_compliant'       => $request['rfid'],
                          'tag_at_source'        => $request['tag_at_source'],
                          'order_status'         => "Saved",
                          'artwork_status'       => "Saved",
                          'print_status'         => "Saved",
                          'template_id'          => $template_id,
                          'pi_status'            => "",
                          'po_status'            => "",
                          'is_order_count_empty' => ""
                        ]);
            }
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
  /**
   * method to save the tesco TCL4 orders
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function saveTCL4Order($request,$template_id)
  {
    try {
           /** Insert order details first time. If data already exits, update old values**/
           if($request['is_insert'] == 'true')
           {
              $result = TescoTCL4Orders::create([
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
                          'tesco_supplier_id'    => $request['supplier'],
                          'rfid_compliant'       => $request['rfid'],
                          'tag_at_source'        => $request['tag_at_source'],
                          'order_status'         => "Saved",
                          'artwork_status'       => "Saved",
                          'print_status'         => "Saved",
                          'template_id'          => $template_id,
                          'pi_status'            => "",
                          'po_status'            => "",
                          'is_order_count_empty' => ""
                        ]);
           }
           else
           {
              $result = TescoTCL4Orders::where('id',$request['tcl4_last_id'])
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
                            'tesco_supplier_id'    => $request['supplier'],
                            'rfid_compliant'       => $request['rfid'],
                            'tag_at_source'        => $request['tag_at_source'],
                            'order_status'         => "Saved",
                            'artwork_status'       => "Saved",
                            'print_status'         => "Saved",
                            'template_id'          => $template_id,
                            'pi_status'            => "",
                            'po_status'            => "",
                            'is_order_count_empty' => ""
                          ]);
           }
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
  /**
   * method to save the tesco TSL3 orders
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function saveTSL3Order($request,$template_id)
  {
    try {
            /** Insert order details first time. If data already exits, update old values**/
            if($request['is_insert'] == 'true')
            {
              $result = TescoTSL3Orders::create([
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
                          'tesco_supplier_id'    => $request['supplier'],
                          'rfid_compliant'       => $request['rfid'],
                          'tag_at_source'        => $request['tag_at_source'],
                          'order_status'         => "Saved",
                          'artwork_status'       => "Saved",
                          'print_status'         => "Saved",
                          'template_id'          => $template_id,
                          'pi_status'            => "",
                          'po_status'            => "",
                          'is_order_count_empty' => ""
                        ]);
            }
            else
            {
              $result = TescoTSL3Orders::where('id',$request['tsl3_last_id'])
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
                          'tesco_supplier_id'    => $request['supplier'],
                          'rfid_compliant'       => $request['rfid'],
                          'tag_at_source'        => $request['tag_at_source'],
                          'order_status'         => "Saved",
                          'artwork_status'       => "Saved",
                          'print_status'         => "Saved",
                          'template_id'          => $template_id,
                          'pi_status'            => "",
                          'po_status'            => "",
                          'is_order_count_empty' => ""
                        ]);
            }
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
  /**
   * method to save the tesco THL3 orders
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function saveTHL3Order($request,$template_id)
  {
    try {
            /** Insert order details first time. If data already exits, update old values**/
            if($request['is_insert'] == 'true')
            {
              return $result = TescoTHL3Orders::create([
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
                          'tesco_supplier_id'    => $request['supplier'],
                          'rfid_compliant'       => $request['rfid'],
                          'tag_at_source'        => $request['tag_at_source'],
                          'order_status'         => "Saved",
                          'artwork_status'       => "Saved",
                          'print_status'         => "Saved",
                          'template_id'          => $template_id,
                          'pi_status'            => "",
                          'po_status'            => "",
                          'is_order_count_empty' => ""
                        ]);
            }
            else
            {
              $result = TescoTHL3Orders::where('id',$request['thl3_last_id'])
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
                          'tesco_supplier_id'    => $request['supplier'],
                          'rfid_compliant'       => $request['rfid'],
                          'tag_at_source'        => $request['tag_at_source'],
                          'order_status'         => "Saved",
                          'artwork_status'       => "Saved",
                          'print_status'         => "Saved",
                          'template_id'          => $template_id,
                          'pi_status'            => "",
                          'po_status'            => "",
                          'is_order_count_empty' => ""
                        ]);
            }
            
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
}
