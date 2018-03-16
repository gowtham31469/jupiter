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
use App\Classes\SubmitTescoOrdersUpdateQuery;
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
            /** [ Creating new object for SubmitTescoOrdersUpdateQuery() class ] */
            $tesco_order_obj = new SubmitTescoOrdersUpdateQuery(); 

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
              /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
              $is_exists = TescoTCL1Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','=','Saved')->first();
            
              if($is_exists)
              {
                $existing_id = $is_exists->id;
                /** [ Invoking updateTCL1Order function using class instance ] */
                $tesco_order  = $tesco_order_obj->updateTCL1Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$existing_id);

                return $is_exists;
                
              }
              else
              {
                return TescoTCL1Orders::create([
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
            }
            else
            {
              /** [ Invoking updateTCL1Order function using class instance ] */
                $tesco_order  = $tesco_order_obj->updateTCL1Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$request['tcl1_last_id']);

                return $tesco_order;
            }
            
        } catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
	}
  /**
   * method to store the tesco TCL2 orders
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function createTCL2Order($request,$template_id,$pi_required,$is_order_count_empty)
  {
    try {
            /** [ Creating new object for SubmitTescoOrdersUpdateQuery() class ] */
            $tesco_order_obj = new SubmitTescoOrdersUpdateQuery();

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
              /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
              $is_exists = TescoTCL2Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','=','Saved')->first();
            
              if($is_exists)
              {
                $existing_id = $is_exists->id;
                /** [ Invoking updateTCL2Order function using class instance ] */
                $tesco_order  = $tesco_order_obj->updateTCL2Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$existing_id);

                return $is_exists;
                
              }
              else
              {
                return TescoTCL2Orders::create([
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
            }
            else
            {
              /** [ Invoking updateTCL2Order function using class instance ] */
                $tesco_order  = $tesco_order_obj->updateTCL2Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$request['tcl2_last_id']);

                return $tesco_order;
            }
            
        } catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }
  /**
   * method to store the tesco TSL1 orders
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function createTSL1Order($request,$template_id,$pi_required,$is_order_count_empty)
  {
    try {
            /** [ Creating new object for SubmitTescoOrdersUpdateQuery() class ] */
            $tesco_order_obj = new SubmitTescoOrdersUpdateQuery();

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
              /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
              $is_exists = TescoTSL1Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','=','Saved')->first();
            
              if($is_exists)
              {
                $existing_id = $is_exists->id;
                /** [ Invoking updateTSL1Order function using class instance ] */
                $tesco_order  = $tesco_order_obj->updateTSL1Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$existing_id);

                return $is_exists;
                
              }
              else
              {
                return TescoTSL1Orders::create([
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
              
            }
            else
            {
              /** [ Invoking updateTSL1Order function using class instance ] */
                $tesco_order  = $tesco_order_obj->updateTSL1Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$request['tsl1_last_id']);

              return $tesco_order;
            }
            
        } catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }
  /**
   * method to store the tesco THL1 orders
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function createTHL1Order($request,$template_id,$pi_required,$is_order_count_empty)
  {
    try {
            /** [ Creating new object for SubmitTescoOrdersUpdateQuery() class ] */
            $tesco_order_obj = new SubmitTescoOrdersUpdateQuery();

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
              /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
              $is_exists = TescoTHL1Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','=','Saved')->first();
            
              if($is_exists)
              {
                $existing_id = $is_exists->id;
                /** [ Invoking updateTHL1Order function using class instance ] */
                $tesco_order  = $tesco_order_obj->updateTHL1Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$existing_id);

                return $is_exists;
                
              }
              else
              {
                return TescoTHL1Orders::create([
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
            }
            else
            {
              /** [ Invoking updateTHL1Order function using class instance ] */
              $tesco_order  = $tesco_order_obj->updateTHL1Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$request['thl1_last_id']);

              return $tesco_order;
            }
            
        } catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }
  /**
   * method to store the tesco TCL4 orders
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function createTCL4Order($request,$template_id,$pi_required,$is_order_count_empty)
  {
    try {
            /** [ Creating new object for SubmitTescoOrdersUpdateQuery() class ] */
            $tesco_order_obj = new SubmitTescoOrdersUpdateQuery();

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
              /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
              $is_exists = TescoTCL4Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','=','Saved')->first();
            
              if($is_exists)
              {
                $existing_id = $is_exists->id;
                /** [ Invoking updateTCL4Order function using class instance ] */
                $tesco_order  = $tesco_order_obj->updateTCL4Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$existing_id);

                return $is_exists;
                
              }
              else
              {
                return TescoTCL4Orders::create([
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
            }
            else
            {
              /** [ Invoking updateTCL4Order function using class instance ] */
              $tesco_order  = $tesco_order_obj->updateTCL4Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$request['tcl4_last_id']);

              return $tesco_order;
            }
            
        } catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }
  /**
   * method to store the tesco TSL3 orders
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function createTSL3Order($request,$template_id,$pi_required,$is_order_count_empty)
  {
    try {
            /** [ Creating new object for SubmitTescoOrdersUpdateQuery() class ] */
            $tesco_order_obj = new SubmitTescoOrdersUpdateQuery();

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
              /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
              $is_exists = TescoTSL3Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','=','Saved')->first();
            
              if($is_exists)
              {
                $existing_id = $is_exists->id;
                /** [ Invoking updateTSL3Order function using class instance ] */
                $tesco_order  = $tesco_order_obj->updateTSL3Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$existing_id);

                return $is_exists;
                
              }
              else
              {
                return TescoTSL3Orders::create([
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
              
            }
            else
            {
              /** [ Invoking updateTSL3Order function using class instance ] */
              $tesco_order  = $tesco_order_obj->updateTSL3Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$request['tsl3_last_id']);

              return $tesco_order;
            }
            
        } catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }
  /**
   * method to store the tesco THL3 orders
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function createTHL3Order($request,$template_id,$pi_required,$is_order_count_empty)
  {
    try {
            /** [ Creating new object for SubmitTescoOrdersUpdateQuery() class ] */
            $tesco_order_obj = new SubmitTescoOrdersUpdateQuery();

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
              /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
              $is_exists = TescoTHL3Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','=','Saved')->first();
            
              if($is_exists)
              {
                $existing_id = $is_exists->id;
                /** [ Invoking updateTHL3Order function using class instance ] */
                $tesco_order  = $tesco_order_obj->updateTHL3Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$existing_id);

                return $is_exists;
                
              }
              else
              {
                return TescoTHL3Orders::create([
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
              
            }
            else
            {
              /** [ Invoking updateTHL3Order function using class instance ] */
              $tesco_order  = $tesco_order_obj->updateTHL3Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$request['thl3_last_id']);

              return $tesco_order;
            }
            
        } catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }
}
