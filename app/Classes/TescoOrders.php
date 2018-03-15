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
   * Class to store the tesco template orders
   * @author [Praveen] <[praveen@wizroots.com]>
   */
class TescoOrders
{
	/**
	 * method to store the tesco TCL1 orders
	 * @param  [type] $request, $template_id
	 * @return tesco order table id (inserted_id)
	 */
	public function createTCL1Order($request,$template_id,$pi_required,$is_order_count_empty)
	{
		try {
            
            /** [ pi_required for this customer or not. If required then pi_staus will be "PI Approval Pending"  ] */
            if($pi_required == 1)
            {
              $pi_status = "PI Approval Pending";
            }
            else
            {
              $pi_status = "";
            }
            /** [ Get company id for template ] */
            $company_id = DB::table('tesco_item_master')->select('company_id')->where('id','=',$template_id)->first();
            /** [ Get orders table maximum id value for creating order id] */
            $max_id = TescoTCL1Orders::max('id');
            if($company_id->company_id == 1)
            {
              $order_id = "JGA_TCL100".($max_id+1);
            }
            else
            {
              $order_id = "SP_TCL100".($max_id+1);
            }
            /** [ Check if price is available for this template ] */
            $price = DB::table('price_taxation')->select('price')->where('id','=',$template_id)->where('customer_id','=',$request['supplier'])->first();

            /** Insert order details first time. If data already exits, update old values**/
            if($request['is_insert'] == 'true')
            {
              $result = TescoTCL1Orders::create([
                          'order_id'             => $order_id,
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
                          'order_status'         => "Order Placed",
                          'artwork_status'       => "Order Placed",
                          'print_status'         => "Order Placed",
                          'template_id'          => $template_id,
                          'pi_status'            => $pi_status,
                          'po_status'            => "PO Pending",
                          'is_order_count_empty' => $is_order_count_empty
                        ]);
            }
            else
            {
              $result = TescoTCL1Orders::where('id',$request['tcl1_last_id'])
                        ->update([
                          'order_id'             => $order_id,
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
                          'order_status'         => "Order Placed",
                          'artwork_status'       => "Order Placed",
                          'print_status'         => "Order Placed",
                          'template_id'          => $template_id,
                          'pi_status'            => $pi_status,
                          'po_status'            => "PO Pending",
                          'is_order_count_empty' => $is_order_count_empty
                        ]);
            }
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}
  /**
   * method to store the tesco TCL2 orders
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function createTCL2Order($request,$template_id,$pi_required,$is_order_count_empty)
  {
    try {
            /** [ pi_required for this customer or not. If required then pi_staus will be "PI Approval Pending"  ] */
            if($pi_required == 1)
            {
              $pi_status = "PI Approval Pending";
            }
            else
            {
              $pi_status = "";
            }
            /** [ Get company id for template ] */
            $company_id = DB::table('tesco_item_master')->select('company_id')->where('id','=',$template_id)->first();
            /** [ Get orders table maximum id value for creating order id] */
            $max_id = TescoTCL2Orders::max('id');
            if($company_id->company_id == 1)
            {
              $order_id = "JGA_TCL200".($max_id+1);
            }
            else
            {
              $order_id = "SP_TCL200".($max_id+1);
            }
            /** [ Check if price is available for this template ] */
            $price = DB::table('price_taxation')->select('price')->where('id','=',$template_id)->where('customer_id','=',$request['supplier'])->first();

            /** Insert order details first time. If data already exits, update old values**/
            if($request['is_insert'] == 'true')
            {
              $result = TescoTCL2Orders::create([
                          'order_id'             => $order_id,
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
                          'order_status'         => "Order Placed",
                          'artwork_status'       => "Order Placed",
                          'print_status'         => "Order Placed",
                          'template_id'          => $template_id,
                          'pi_status'            => $pi_status,
                          'po_status'            => "PO Pending",
                          'is_order_count_empty' => $is_order_count_empty
                        ]);
            }
            else
            {
              $result = TescoTCL2Orders::where('id',$request['tcl2_last_id'])
                        ->update([
                          'order_id'             => $order_id,
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
                          'order_status'         => "Order Placed",
                          'artwork_status'       => "Order Placed",
                          'print_status'         => "Order Placed",
                          'template_id'          => $template_id,
                          'pi_status'            => $pi_status,
                          'po_status'            => "PO Pending",
                          'is_order_count_empty' => $is_order_count_empty
                        ]);
            }
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
  /**
   * method to store the tesco TSL1 orders
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function createTSL1Order($request,$template_id,$pi_required,$is_order_count_empty)
  {
    try {
            /** [ pi_required for this customer or not. If required then pi_staus will be "PI Approval Pending"  ] */
            if($pi_required == 1)
            {
              $pi_status = "PI Approval Pending";
            }
            else
            {
              $pi_status = "";
            }
            /** [ Get company id for template ] */
            $company_id = DB::table('tesco_item_master')->select('company_id')->where('id','=',$template_id)->first();
            /** [ Get orders table maximum id value for creating order id] */
            $max_id = TescoTSL1Orders::max('id');
            if($company_id->company_id == 1)
            {
              $order_id = "JGA_TSL100".($max_id+1);
            }
            else
            {
              $order_id = "SP_TSL100".($max_id+1);
            }
            /** [ Check if price is available for this template ] */
            $price = DB::table('price_taxation')->select('price')->where('id','=',$template_id)->where('customer_id','=',$request['supplier'])->first();

            /** Insert order details first time. If data already exits, update old values**/
            if($request['is_insert'] == 'true')
            {
              $result = TescoTSL1Orders::create([
                          'order_id'             => $order_id,
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
                          'order_status'         => "Order Placed",
                          'artwork_status'       => "Order Placed",
                          'print_status'         => "Order Placed",
                          'template_id'          => $template_id,
                          'pi_status'            => $pi_status,
                          'po_status'            => "PO Pending",
                          'is_order_count_empty' => $is_order_count_empty
                        ]);
            }
            else
            {
              $result = TescoTSL1Orders::where('id',$request['tsl1_last_id'])
                       ->update([
                          'order_id'             => $order_id,
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
                          'order_status'         => "Order Placed",
                          'artwork_status'       => "Order Placed",
                          'print_status'         => "Order Placed",
                          'template_id'          => $template_id,
                          'pi_status'            => $pi_status,
                          'po_status'            => "PO Pending",
                          'is_order_count_empty' => $is_order_count_empty
                        ]);
            }
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
  /**
   * method to store the tesco THL1 orders
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function createTHL1Order($request,$template_id,$pi_required,$is_order_count_empty)
  {
    try {
            /** [ pi_required for this customer or not. If required then pi_staus will be "PI Approval Pending"  ] */
            if($pi_required == 1)
            {
              $pi_status = "PI Approval Pending";
            }
            else
            {
              $pi_status = "";
            }
            /** [ Get company id for template ] */
            $company_id = DB::table('tesco_item_master')->select('company_id')->where('id','=',$template_id)->first();
            /** [ Get orders table maximum id value for creating order id] */
            $max_id = TescoTHL1Orders::max('id');
            if($company_id->company_id == 1)
            {
              $order_id = "JGA_THL100".($max_id+1);
            }
            else
            {
              $order_id = "SP_THL100".($max_id+1);
            }
            /** [ Check if price is available for this template ] */
            $price = DB::table('price_taxation')->select('price')->where('id','=',$template_id)->where('customer_id','=',$request['supplier'])->first();

            /** Insert order details first time. If data already exits, update old values**/
            if($request['is_insert'] == 'true')
            {
              $result = TescoTHL1Orders::create([
                          'order_id'             => $order_id,
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
                          'order_status'         => "Order Placed",
                          'artwork_status'       => "Order Placed",
                          'print_status'         => "Order Placed",
                          'template_id'          => $template_id,
                          'pi_status'            => $pi_status,
                          'po_status'            => "PO Pending",
                          'is_order_count_empty' => $is_order_count_empty
                        ]);
            }
            else
            {
              $result = TescoTHL1Orders::where('id',$request['thl1_last_id'])
                        ->update([
                          'order_id'             => $order_id,
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
                          'order_status'         => "Order Placed",
                          'artwork_status'       => "Order Placed",
                          'print_status'         => "Order Placed",
                          'template_id'          => $template_id,
                          'pi_status'            => $pi_status,
                          'po_status'            => "PO Pending",
                          'is_order_count_empty' => $is_order_count_empty
                        ]);
            }
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
  /**
   * method to store the tesco TCL4 orders
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function createTCL4Order($request,$template_id,$pi_required,$is_order_count_empty)
  {
    try {
            /** [ pi_required for this customer or not. If required then pi_staus will be "PI Approval Pending"  ] */
            if($pi_required == 1)
            {
              $pi_status = "PI Approval Pending";
            }
            else
            {
              $pi_status = "";
            }
           /** [ Get company id for template ] */
            $company_id = DB::table('tesco_item_master')->select('company_id')->where('id','=',$template_id)->first();
            /** [ Get orders table maximum id value for creating order id] */
            $max_id = TescoTCL4Orders::max('id');
            if($company_id->company_id == 1)
            {
              $order_id = "JGA_TCL400".($max_id+1);
            }
            else
            {
              $order_id = "SP_TCL400".($max_id+1);
            }
            /** [ Check if price is available for this template ] */
            $price = DB::table('price_taxation')->select('price')->where('id','=',$template_id)->where('customer_id','=',$request['supplier'])->first();

            /** Insert order details first time. If data already exits, update old values**/
            if($request['is_insert'] == 'true')
            {
              $result = TescoTCL4Orders::create([
                          'order_id'             => $order_id,
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
                          'order_status'         => "Order Placed",
                          'artwork_status'       => "Order Placed",
                          'print_status'         => "Order Placed",
                          'template_id'          => $template_id,
                          'pi_status'            => $pi_status,
                          'po_status'            => "PO Pending",
                          'is_order_count_empty' => $is_order_count_empty
                        ]);
            }
            else
            {
              $result = TescoTCL4Orders::where('id',$request['tcl4_last_id'])
                          ->update([
                          'order_id'             => $order_id,
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
                          'order_status'         => "Order Placed",
                          'artwork_status'       => "Order Placed",
                          'print_status'         => "Order Placed",
                          'template_id'          => $template_id,
                          'pi_status'            => $pi_status,
                          'po_status'            => "PO Pending",
                          'is_order_count_empty' => $is_order_count_empty
                        ]);
            }
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
  /**
   * method to store the tesco TSL3 orders
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function createTSL3Order($request,$template_id,$pi_required,$is_order_count_empty)
  {
    try {
            /** [ pi_required for this customer or not. If required then pi_staus will be "PI Approval Pending"  ] */
            if($pi_required == 1)
            {
              $pi_status = "PI Approval Pending";
            }
            else
            {
              $pi_status = "";
            }
            /** [ Get company id for template ] */
            $company_id = DB::table('tesco_item_master')->select('company_id')->where('id','=',$template_id)->first();
            /** [ Get orders table maximum id value for creating order id] */
            $max_id = TescoTSL3Orders::max('id');
            if($company_id->company_id == 1)
            {
              $order_id = "JGA_TSL300".($max_id+1);
            }
            else
            {
              $order_id = "SP_TSL300".($max_id+1);
            }
            /** [ Check if price is available for this template ] */
            $price = DB::table('price_taxation')->select('price')->where('id','=',$template_id)->where('customer_id','=',$request['supplier'])->first();

            /** Insert order details first time. If data already exits, update old values**/
            if($request['is_insert'] == 'true')
            {
              $result = TescoTSL3Orders::create([
                          'order_id'             => $order_id,
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
                          'order_status'         => "Order Placed",
                          'artwork_status'       => "Order Placed",
                          'print_status'         => "Order Placed",
                          'template_id'          => $template_id,
                          'pi_status'            => $pi_status,
                          'po_status'            => "PO Pending",
                          'is_order_count_empty' => $is_order_count_empty
                        ]);
            }
            else
            {
              $result = TescoTSL3Orders::where('id',$request['tsl3_last_id'])
                          ->update([
                          'order_id'             => $order_id,
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
                          'order_status'         => "Order Placed",
                          'artwork_status'       => "Order Placed",
                          'print_status'         => "Order Placed",
                          'template_id'          => $template_id,
                          'pi_status'            => $pi_status,
                          'po_status'            => "PO Pending",
                          'is_order_count_empty' => $is_order_count_empty
                        ]);
            }
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
  /**
   * method to store the tesco THL3 orders
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function createTHL3Order($request,$template_id,$pi_required,$is_order_count_empty)
  {
    try {
            /** [ pi_required for this customer or not. If required then pi_staus will be "PI Approval Pending"  ] */
            if($pi_required == 1)
            {
              $pi_status = "PI Approval Pending";
            }
            else
            {
              $pi_status = "";
            }
            /** [ Get company id for template ] */
            $company_id = DB::table('tesco_item_master')->select('company_id')->where('id','=',$template_id)->first();
            /** [ Get orders table maximum id value for creating order id] */
            $max_id = TescoTHL3Orders::max('id');
            if($company_id->company_id == 1)
            {
              $order_id = "JGA_THL300".($max_id+1);
            }
            else
            {
              $order_id = "SP_THL300".($max_id+1);
            }
            /** [ Check if price is available for this template ] */
            $price = DB::table('price_taxation')->select('price')->where('id','=',$template_id)->where('customer_id','=',$request['supplier'])->first();
            
            /** Insert order details first time. If data already exits, update old values**/
            if($request['is_insert'] == 'true')
            {
              $result = TescoTHL3Orders::create([
                          'order_id'             => $order_id,
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
                          'order_status'         => "Order Placed",
                          'artwork_status'       => "Order Placed",
                          'print_status'         => "Order Placed",
                          'template_id'          => $template_id,
                          'pi_status'            => $pi_status,
                          'po_status'            => "PO Pending",
                          'is_order_count_empty' => $is_order_count_empty
                        ]);
            }
            else
            {
              $result = TescoTHL3Orders::where('id',$request['thl3_last_id'])
                          ->update([
                          'order_id'             => $order_id,
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
                          'order_status'         => "Order Placed",
                          'artwork_status'       => "Order Placed",
                          'print_status'         => "Order Placed",
                          'template_id'          => $template_id,
                          'pi_status'            => $pi_status,
                          'po_status'            => "PO Pending",
                          'is_order_count_empty' => $is_order_count_empty
                        ]);
            }
            
        } catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
}
