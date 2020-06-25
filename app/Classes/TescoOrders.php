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
use App\Classes\SubmitTescoOrdersUpdateQuery;
use Carbon\Carbon;
use App\Classes\EditErpOrders;
/**
 * Class to store the tesco template orders
 * @author [Praveen] <[praveen@wizroots.com]>
 */
class TescoOrders
{

  private $current_date;
  private $ratio_pack_type;
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      // get the current time
      $this->current_date = Carbon::now('Asia/Kolkata')->toDateTimeString();
      $this->ratio_pack_type = ['RatA', 'RatB', 'RatC', 'RatD', 'RatE', 'RatF', 'RatG', 'RatH', 'RatI', 'RatJ', 'RatK', 'RatL', 'RatM', 'RatN', 'RatO', 'RatP', 'RatQ', 'RatR', 'RatS', 'RatT', 'RatU', 'RatV', 'RatW', 'RatX', 'RatY', 'RatZ'];
  }

	/**
	 * method to store the tesco TCL1 orders
	 * @param  [type] $request, $template_id
	 * @return tesco order table id (inserted_id)
	 */
	public function createTCL1Order($request,$template_id,$pi_required,$is_order_count_empty,$customer_id,$created_by,$po_required)
	{
		try {
            /** [ Creating new object for SubmitTescoOrdersUpdateQuery() class ] */
            $tesco_order_obj = new SubmitTescoOrdersUpdateQuery(); 

            /** [ pi_required for this customer or not. If required then pi_staus will be "PI Approval Pending"  ] */
            $pi_status = $pi_required == 1?"PI Approval Pending":null;

            /** [ po_required for this customer or not. If required then po_staus will be "PO Pending"  ] */
            $po_status = $po_required == 1?"PO Pending":null;

            /** [ Get company id for template ] */
            $company_id = DB::table('tesco_item_master')->select('company_id')->where('id','=',$template_id)->first();
            /** [ Get orders table maximum id value for creating order id] */
            $max_id = TescoTCL1Orders::max('id');

            $order_id = $company_id->company_id == 1?"JGA_TCL100".($max_id+1):"SP_TCL100".($max_id+1);

            /** Insert order details first time. If data already exits, update old values**/
            if($request['is_insert'] == 'true')
            {
              /** [ Check if order already placed for this CE style OR UK style ] */
              if($request['zone'] == 'UK')
              {
                $is_exists = TescoTCL1Orders::select('id')->where('uk_style_ref','=',$request['uk_style'])->where('order_status','=','Saved')->where('po_number','=',$request['po_number'])->first();
              }
              else
              {
                $is_exists = TescoTCL1Orders::select('id')->Where('ce_style_ref','=',$request['ce_style'])->where('order_status','=','Saved')->where('po_number','=',$request['po_number'])->first();
              }
            
              if($is_exists)
              {
                $existing_id = $is_exists->id;
                /** [ Invoking updateTCL1Order function using class instance ] */
                $tesco_order  = $tesco_order_obj->updateTCL1Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$existing_id,$customer_id,$created_by,$po_status);

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
                          'tesco_supplier_id'    => $customer_id,
                          'tesco_metadata_supplier_id' => $request['supplier'],
                          'rfid_compliant'       => $request['rfid'],
                          'tag_at_source'        => $request['tag_at_source'],
                          'order_status'         => "Order Placed",
                          'template_id'          => $template_id,
                          'pi_status'            => $pi_status,
                          'po_status'            => $po_status,
                          'is_order_count_empty' => $is_order_count_empty,
                          'order_placed_by'      => $created_by,
                          'order_created_at'     => $this->current_date,
                          'ocn_number'           => $request['ocn_no'],
                          'full_description'     => $request['full_description']
                        ]);
              }
            }
            else
            {
              /** [ Invoking updateTCL1Order function using class instance ] */
                $tesco_order  = $tesco_order_obj->updateTCL1Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$request['tcl1_last_id'],$customer_id,$created_by,$po_status);

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
  public function createTCL2Order($request,$template_id,$pi_required,$is_order_count_empty,$customer_id,$created_by,$po_required)
  {
    try {
            /** [ Creating new object for SubmitTescoOrdersUpdateQuery() class ] */
            $tesco_order_obj = new SubmitTescoOrdersUpdateQuery();

            /** [ pi_required for this customer or not. If required then pi_staus will be "PI Approval Pending"  ] */
            $pi_status = $pi_required == 1?"PI Approval Pending":null;

            /** [ po_required for this customer or not. If required then po_staus will be "PO Pending"  ] */
            $po_status = $po_required == 1?"PO Pending":null;

            /** [ Get company id for template ] */
            $company_id = DB::table('tesco_item_master')->select('company_id')->where('id','=',$template_id)->first();
            /** [ Get orders table maximum id value for creating order id] */
            $max_id = TescoTCL2Orders::max('id');
            $order_id = $company_id->company_id == 1?"JGA_TCL200".($max_id+1):"SP_TCL200".($max_id+1);

            /** Insert order details first time. If data already exits, update old values**/
            if($request['is_insert'] == 'true')
            {
              /** [ Check if order already placed for this CE style OR UK style ] */
              if($request['zone'] == 'UK')
              {
                $is_exists = TescoTCL2Orders::select('id')->where('uk_style_ref','=',$request['uk_style'])->where('order_status','=','Saved')->where('po_number','=',$request['po_number'])->first();
              }
              else
              {
                $is_exists = TescoTCL2Orders::select('id')->Where('ce_style_ref','=',$request['ce_style'])->where('order_status','=','Saved')->where('po_number','=',$request['po_number'])->first();
              }
            
              if($is_exists)
              {
                $existing_id = $is_exists->id;
                /** [ Invoking updateTCL2Order function using class instance ] */
                $tesco_order  = $tesco_order_obj->updateTCL2Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$existing_id,$customer_id,$created_by,$po_status);

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
                          'tesco_supplier_id'    => $customer_id,
                          'tesco_metadata_supplier_id' => $request['supplier'],
                          'rfid_compliant'       => $request['rfid'],
                          'tag_at_source'        => $request['tag_at_source'],
                          'order_status'         => "Order Placed",
                          'template_id'          => $template_id,
                          'pi_status'            => $pi_status,
                          'po_status'            => $po_status,
                          'is_order_count_empty' => $is_order_count_empty,
                          'order_placed_by'      => $created_by,
                          'order_created_at'     => $this->current_date,
                          'ocn_number'           => $request['ocn_no'],
                          'full_description'     => $request['full_description']
                        ]);
              }
            }
            else
            {
              /** [ Invoking updateTCL2Order function using class instance ] */
                $tesco_order  = $tesco_order_obj->updateTCL2Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$request['tcl2_last_id'],$customer_id,$created_by,$po_status);

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
  public function createTSL1Order($request,$template_id,$pi_required,$is_order_count_empty,$customer_id,$created_by,$po_required)
  {
    try {
            /** [ Creating new object for SubmitTescoOrdersUpdateQuery() class ] */
            $tesco_order_obj = new SubmitTescoOrdersUpdateQuery();

            /** [ pi_required for this customer or not. If required then pi_staus will be "PI Approval Pending"  ] */
            $pi_status = $pi_required == 1?"PI Approval Pending":null;

            /** [ po_required for this customer or not. If required then po_staus will be "PO Pending"  ] */
            $po_status = $po_required == 1?"PO Pending":null;

            /** [ Get company id for template ] */
            $company_id = DB::table('tesco_item_master')->select('company_id')->where('id','=',$template_id)->first();
            /** [ Get orders table maximum id value for creating order id] */
            $max_id = TescoTSL1Orders::max('id');
            $order_id = $company_id->company_id == 1?"JGA_TSL100".($max_id+1):"SP_TSL100".($max_id+1);

            /** Insert order details first time. If data already exits, update old values**/
            if($request['is_insert'] == 'true')
            {
              /** [ Check if order already placed for this CE style OR UK style ] */
              if($request['zone'] == 'UK')
              {
                $is_exists = TescoTSL1Orders::select('id')->where('uk_style_ref','=',$request['uk_style'])->where('order_status','=','Saved')->where('po_number','=',$request['po_number'])->first();
              }
              else
              {
                $is_exists = TescoTSL1Orders::select('id')->Where('ce_style_ref','=',$request['ce_style'])->where('order_status','=','Saved')->where('po_number','=',$request['po_number'])->first();
              }
            
              if($is_exists)
              {
                $existing_id = $is_exists->id;
                /** [ Invoking updateTSL1Order function using class instance ] */
                $tesco_order  = $tesco_order_obj->updateTSL1Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$existing_id,$customer_id,$created_by,$po_status);

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
                          'tesco_supplier_id'    => $customer_id,
                          'tesco_metadata_supplier_id' => $request['supplier'],
                          'rfid_compliant'       => $request['rfid'],
                          'tag_at_source'        => $request['tag_at_source'],
                          'order_status'         => "Order Placed",
                          'template_id'          => $template_id,
                          'pi_status'            => $pi_status,
                          'po_status'            => $po_status,
                          'is_order_count_empty' => $is_order_count_empty,
                          'order_placed_by'      => $created_by,
                          'order_created_at'     => $this->current_date,
                          'ocn_number'           => $request['ocn_no'],
                          'full_description'     => $request['full_description']
                        ]);
              }
              
            }
            else
            {
              /** [ Invoking updateTSL1Order function using class instance ] */
                $tesco_order  = $tesco_order_obj->updateTSL1Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$request['tsl1_last_id'],$customer_id,$created_by,$po_status);

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
  public function createTHL1Order($request,$template_id,$pi_required,$is_order_count_empty,$customer_id,$created_by,$po_required)
  {
    try {
            /** [ Creating new object for SubmitTescoOrdersUpdateQuery() class ] */
            $tesco_order_obj = new SubmitTescoOrdersUpdateQuery();

            /** [ pi_required for this customer or not. If required then pi_staus will be "PI Approval Pending"  ] */
            $pi_status = $pi_required == 1?"PI Approval Pending":null;

            /** [ po_required for this customer or not. If required then po_staus will be "PO Pending"  ] */
            $po_status = $po_required == 1?"PO Pending":null;

            /** [ Get company id for template ] */
            $company_id = DB::table('tesco_item_master')->select('company_id')->where('id','=',$template_id)->first();
            /** [ Get orders table maximum id value for creating order id] */
            $max_id = TescoTHL1Orders::max('id');
            $order_id = $company_id->company_id == 1?"JGA_THL100".($max_id+1):"SP_THL100".($max_id+1);

            /** Insert order details first time. If data already exits, update old values**/
            if($request['is_insert'] == 'true')
            {
              /** [ Check if order already placed for this CE style OR UK style ] */
              if($request['zone'] == 'UK')
              {
                $is_exists = TescoTHL1Orders::select('id')->where('uk_style_ref','=',$request['uk_style'])->where('order_status','=','Saved')->where('po_number','=',$request['po_number'])->first();
              }
              else
              {
                $is_exists = TescoTHL1Orders::select('id')->Where('ce_style_ref','=',$request['ce_style'])->where('order_status','=','Saved')->where('po_number','=',$request['po_number'])->first();
              }
            
              if($is_exists)
              {
                $existing_id = $is_exists->id;
                /** [ Invoking updateTHL1Order function using class instance ] */
                $tesco_order  = $tesco_order_obj->updateTHL1Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$existing_id,$customer_id,$created_by,$po_status);

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
                          'tesco_supplier_id'    => $customer_id,
                          'tesco_metadata_supplier_id' => $request['supplier'],
                          'rfid_compliant'       => $request['rfid'],
                          'tag_at_source'        => $request['tag_at_source'],
                          'order_status'         => "Order Placed",
                          'template_id'          => $template_id,
                          'pi_status'            => $pi_status,
                          'po_status'            => $po_status,
                          'is_order_count_empty' => $is_order_count_empty,
                          'order_placed_by'      => $created_by,
                          'order_created_at'     => $this->current_date,
                          'ocn_number'           => $request['ocn_no'],
                          'full_description'     => $request['full_description']
                        ]);
              }
            }
            else
            {
              /** [ Invoking updateTHL1Order function using class instance ] */
              $tesco_order  = $tesco_order_obj->updateTHL1Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$request['thl1_last_id'],$customer_id,$created_by,$po_status);

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
  public function createTCL4Order($request,$template_id,$pi_required,$is_order_count_empty,$customer_id,$created_by,$po_required)
  {
    try {
            /** [ Creating new object for SubmitTescoOrdersUpdateQuery() class ] */
            $tesco_order_obj = new SubmitTescoOrdersUpdateQuery();

            /** [ pi_required for this customer or not. If required then pi_staus will be "PI Approval Pending"  ] */
            $pi_status = $pi_required == 1?"PI Approval Pending":null;

            /** [ po_required for this customer or not. If required then po_staus will be "PO Pending"  ] */
            $po_status = $po_required == 1?"PO Pending":null;

           /** [ Get company id for template ] */
            $company_id = DB::table('tesco_item_master')->select('company_id')->where('id','=',$template_id)->first();
            /** [ Get orders table maximum id value for creating order id] */
            $max_id = TescoTCL4Orders::max('id');
            $order_id = $company_id->company_id == 1?"JGA_TCL400".($max_id+1):"SP_TCL400".($max_id+1);

            /** Insert order details first time. If data already exits, update old values**/
            if($request['is_insert'] == 'true')
            {
              /** [ Check if order already placed for this CE style OR UK style ] */
              if($request['zone'] == 'UK')
              {
                $is_exists = TescoTCL4Orders::select('id')->where('uk_style_ref','=',$request['uk_style'])->where('order_status','=','Saved')->where('po_number','=',$request['po_number'])->first();
              }
              else
              {
                $is_exists = TescoTCL4Orders::select('id')->Where('ce_style_ref','=',$request['ce_style'])->where('order_status','=','Saved')->where('po_number','=',$request['po_number'])->first();
              }
            
              if($is_exists)
              {
                $existing_id = $is_exists->id;
                /** [ Invoking updateTCL4Order function using class instance ] */
                $tesco_order  = $tesco_order_obj->updateTCL4Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$existing_id,$customer_id,$created_by,$po_status);

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
                          'tesco_supplier_id'    => $customer_id,
                          'tesco_metadata_supplier_id' => $request['supplier'],
                          'rfid_compliant'       => $request['rfid'],
                          'tag_at_source'        => $request['tag_at_source'],
                          'order_status'         => "Order Placed",
                          'template_id'          => $template_id,
                          'pi_status'            => $pi_status,
                          'po_status'            => $po_status,
                          'is_order_count_empty' => $is_order_count_empty,
                          'order_placed_by'      => $created_by,
                          'order_created_at'     => $this->current_date,
                          'ocn_number'           => $request['ocn_no'],
                          'full_description'     => $request['full_description']
                        ]);
              }
            }
            else
            {
              /** [ Invoking updateTCL4Order function using class instance ] */
              $tesco_order  = $tesco_order_obj->updateTCL4Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$request['tcl4_last_id'],$customer_id,$created_by,$po_status);

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
  public function createTSL3Order($request,$template_id,$pi_required,$is_order_count_empty,$customer_id,$created_by,$po_required)
  {
    try {
            /** [ Creating new object for SubmitTescoOrdersUpdateQuery() class ] */
            $tesco_order_obj = new SubmitTescoOrdersUpdateQuery();

            /** [ pi_required for this customer or not. If required then pi_staus will be "PI Approval Pending"  ] */
            $pi_status = $pi_required == 1?"PI Approval Pending":null;

            /** [ po_required for this customer or not. If required then po_staus will be "PO Pending"  ] */
            $po_status = $po_required == 1?"PO Pending":null;

            /** [ Get company id for template ] */
            $company_id = DB::table('tesco_item_master')->select('company_id')->where('id','=',$template_id)->first();
            /** [ Get orders table maximum id value for creating order id] */
            $max_id = TescoTSL3Orders::max('id');
            $order_id = $company_id->company_id == 1?"JGA_TSL300".($max_id+1):"SP_TSL300".($max_id+1);

            /** Insert order details first time. If data already exits, update old values**/
            if($request['is_insert'] == 'true')
            {
              /** [ Check if order already placed for this CE style OR UK style ] */
              if($request['zone'] == 'UK')
              {
                $is_exists = TescoTSL3Orders::select('id')->where('uk_style_ref','=',$request['uk_style'])->where('order_status','=','Saved')->where('po_number','=',$request['po_number'])->first();
              }
              else
              {
                $is_exists = TescoTSL3Orders::select('id')->Where('ce_style_ref','=',$request['ce_style'])->where('order_status','=','Saved')->where('po_number','=',$request['po_number'])->first();
              }
            
              if($is_exists)
              {
                $existing_id = $is_exists->id;
                /** [ Invoking updateTSL3Order function using class instance ] */
                $tesco_order  = $tesco_order_obj->updateTSL3Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$existing_id,$customer_id,$created_by,$po_status);

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
                          'tesco_supplier_id'    => $customer_id,
                          'tesco_metadata_supplier_id' => $request['supplier'],
                          'rfid_compliant'       => $request['rfid'],
                          'tag_at_source'        => $request['tag_at_source'],
                          'order_status'         => "Order Placed",
                          'template_id'          => $template_id,
                          'pi_status'            => $pi_status,
                          'po_status'            => $po_status,
                          'is_order_count_empty' => $is_order_count_empty,
                          'order_placed_by'      => $created_by,
                          'order_created_at'     => $this->current_date,
                          'ocn_number'           => $request['ocn_no'],
                          'full_description'     => $request['full_description']
                        ]);
              }
              
            }
            else
            {
              /** [ Invoking updateTSL3Order function using class instance ] */
              $tesco_order  = $tesco_order_obj->updateTSL3Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$request['tsl3_last_id'],$customer_id,$created_by,$po_status);

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
  public function createTHL3Order($request,$template_id,$pi_required,$is_order_count_empty,$customer_id,$created_by,$po_required)
  {
    try {
            /** [ Creating new object for SubmitTescoOrdersUpdateQuery() class ] */
            $tesco_order_obj = new SubmitTescoOrdersUpdateQuery();

            /** [ pi_required for this customer or not. If required then pi_staus will be "PI Approval Pending"  ] */
            $pi_status = $pi_required == 1?"PI Approval Pending":null;

            /** [ po_required for this customer or not. If required then po_staus will be "PO Pending"  ] */
            $po_status = $po_required == 1?"PO Pending":null;

            /** [ Get company id for template ] */
            $company_id = DB::table('tesco_item_master')->select('company_id')->where('id','=',$template_id)->first();
            /** [ Get orders table maximum id value for creating order id] */
            $max_id = TescoTHL3Orders::max('id');
            $order_id = $company_id->company_id == 1?"JGA_THL300".($max_id+1):"SP_THL300".($max_id+1);
            
            /** Insert order details first time. If data already exits, update old values**/
            if($request['is_insert'] == 'true')
            {
              /** [ Check if order already placed for this CE style OR UK style ] */
              if($request['zone'] == 'UK')
              {
                $is_exists = TescoTHL3Orders::select('id')->where('uk_style_ref','=',$request['uk_style'])->where('order_status','=','Saved')->where('po_number','=',$request['po_number'])->first();
              }
              else
              {
                $is_exists = TescoTHL3Orders::select('id')->Where('ce_style_ref','=',$request['ce_style'])->where('order_status','=','Saved')->where('po_number','=',$request['po_number'])->first();
              }
            
              if($is_exists)
              {
                $existing_id = $is_exists->id;
                /** [ Invoking updateTHL3Order function using class instance ] */
                $tesco_order  = $tesco_order_obj->updateTHL3Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$existing_id,$customer_id,$created_by,$po_status);

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
                          'tesco_supplier_id'    => $customer_id,
                          'tesco_metadata_supplier_id' => $request['supplier'],
                          'rfid_compliant'       => $request['rfid'],
                          'tag_at_source'        => $request['tag_at_source'],
                          'order_status'         => "Order Placed",
                          'template_id'          => $template_id,
                          'pi_status'            => $pi_status,
                          'po_status'            => $po_status,
                          'is_order_count_empty' => $is_order_count_empty,
                          'order_placed_by'      => $created_by,
                          'order_created_at'     => $this->current_date,
                          'ocn_number'           => $request['ocn_no'],
                          'full_description'     => $request['full_description']
                        ]);
              }
              
            }
            else
            {
              /** [ Invoking updateTHL3Order function using class instance ] */
              $tesco_order  = $tesco_order_obj->updateTHL3Order($request,$order_id,$template_id,$pi_status,$is_order_count_empty,$request['thl3_last_id'],$customer_id,$created_by,$po_status);

              return $tesco_order;
            }
            
        } catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }

  /**
   * method to update the related  tesco order tables 
   * @param  [table name]
   * @param  [existing order deatils]
   * @param  [$request]
   * @param  [customer id]
   */
  public function updateTescoOrders($table_name,$temp_data,$request,$customer_id, $created_by)
  {
    try
    {
      $edit_erp_obj    = new EditErpOrders();
      $tpnd            = explode(',',$request['tpnd']);
      $barcode         = explode(',',$request['barcode']);
      $case_size       = explode(',',$request['size']);
      $count           = explode(',',$request['count']);
      $template_id     = $request['edit_id'];
      $template_name   = $request['template_name'];
      $zone            = $request['zone'];
      $qr_code         = explode('|', $request['qr_code']);
     
      if ($table_name == "tesco_tcl1_orders" || $table_name == "tesco_tcl2_orders" || $table_name == "tesco_tsl1_orders") {
          $table_array = array('tesco_tcl1_orders','tesco_tcl2_orders','tesco_tsl1_orders');
          $qnty_id_array = array('tesco_tcl1_orders_quanty','tesco_tcl2_orders_quanty','tesco_tsl1_orders_quanty');
          $template_array = array_combine($table_array, $qnty_id_array);
          $single_size    = $request['single_size'];
      }
      else if ($table_name == "tesco_tcl4_orders" || $table_name == "tesco_tsl3_orders") {
          $table_array = array('tesco_tcl4_orders','tesco_tsl3_orders');
          $qnty_id_array = array('tesco_tcl4_orders_quanty','tesco_tsl3_orders_quanty');
          $template_array = array_combine($table_array, $qnty_id_array);
          $single_size = explode(',',$request['single_size']);
      }
      unset($template_array[$table_name]);

      /* update tesco_templates quantity table.
       * ie, Update all references in the combination. While editing TSL1 details, Also update TCL1 and TCL2 details.
       */
      foreach ($template_array as $table => $qunty_tbl) 
      {
          $qnty_id =  str_replace("_orders","_order_id",str_replace("tesco_","",$table));
          $erp_order_details = array();
          $table_data = DB::table($table)->where('po_number',$temp_data->po_number)
                        ->where('uk_style_ref',$temp_data->uk_style_ref)
                        ->where('ce_style_ref',$temp_data->ce_style_ref)
                        ->where('order_flag',$temp_data->order_flag)
                        ->where('order_status', '<>', 'Order Cancelled')
                        ->where('tesco_supplier_id',$temp_data->tesco_supplier_id)->first();
                   
            DB::table($table)->where('po_number',$temp_data->po_number)
                ->where('uk_style_ref',$temp_data->uk_style_ref)
                ->where('ce_style_ref',$temp_data->ce_style_ref)
                ->where('order_flag',$temp_data->order_flag)
                ->where('order_status', '<>', 'Order Cancelled')
                ->update([
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
                    'ocn_number'           => $request['tesco_ocn'],
                  ]);
       // get quantity_table ids.
       $quantity_data = DB::table($qunty_tbl)
                       ->where($qnty_id,$table_data->id)
		   			   ->orderBy('id')
                       ->select('count','id')->get(); 
        
       $old_data = DB::table($qunty_tbl)
                      ->where($qnty_id,$table_data->id)->delete();
        
        // update quantity details(dynamic rows) for tempalate
        $i=0;              
        foreach($tpnd as $value)
        {
           $quantity = isset($quantity_data[$i])?$quantity_data[$i]->count:0;

            if(($template_name == "TCL1" && $zone=="CE") ||($template_name == "TCL2" && $zone=="CE") || ($template_name == "TSL1" && $zone=="CE"))
            {
                  DB::table($qunty_tbl)->insert([
                    'orms_tpn'           => $value,
                    'itf_barcode'        => $barcode[$i],
                    $qnty_id             => $table_data->id,
                    'cases_per_carton'   => $case_size[$i],
                    'size'               => null,
                    'count'              => $quantity,
                    'ratio_pack_type'    => $this->ratio_pack_type[$i],
                    'qr_code'            => $qr_code[$i]
                  ]);
            }
            else if(($template_name == "TCL4" && $zone=="CE") || ($template_name == "TSL3" && $zone=="CE"))
            {
                    DB::table($qunty_tbl)->insert([
                      'orms_tpn'        => $value,
                      'ean_barcode'     => $barcode[$i],
                      'case_size'       => $case_size[$i],
                      $qnty_id          => $table_data->id,
                      'size'            => $single_size[$i],
                      'count'           => $quantity,
                      'qr_code'         => $qr_code[$i]
                    ]);
            }
            else if(($template_name == "TCL4" && $zone=="UK") || ($template_name == "TSL3" && $zone=="UK"))
            {
                    DB::table($qunty_tbl)->insert([
                      'tpnd'           => $value,
                      'ean_barcode'    => $barcode[$i],
                      'case_size'      => $case_size[$i],
                      'size'           => $single_size[$i],
                      $qnty_id         => $table_data->id,
                      'count'          => $quantity
                  ]);
            }
            else if(($template_name == "TCL1" && $zone=="UK") ||($template_name == "TCL2" && $zone=="UK") || ($template_name == "TSL1" && $zone=="UK"))
            {
                    DB::table($qunty_tbl)->insert([
                      'tpnd'               => $value,
                      'itf_barcode'        => $barcode[$i],
                      'units_per_carton'   => $case_size[$i],
                      'size'               => null,
                      $qnty_id             => $table_data->id,
                      'count'              => $quantity,
                      'ratio_pack_type'    => $this->ratio_pack_type[$i]
                  ]);
            }
            $i++;
        }

        // fetch erp_order_details table details for each quantity_table ids.
        foreach($quantity_data as $quantity_table_id)
        {
            $quantity_id = $quantity_table_id->id;
            $erp_order_details[] = DB::table('erp_order_details')->where('order_qnty_table_name','ilike', $qunty_tbl)->join('erp_status', 'erp_status.id', 'erp_order_details.status')->where('order_qnty_table_id','=', $quantity_id)->select('erp_order_details.id','erp_status.status_name', 'erp_order_details.erp_order_id')->first();
        } 

        if(!is_null($erp_order_details[0]))
        {
            $erp_details_status =  $erp_order_details[0]->status_name;
            $erp_order_id =  $erp_order_details[0]->erp_order_id;

            foreach($erp_order_details as $erp_order_detail){
              // delete from history table and insert new rows.
              $history_table_id =  DB::table('history')->where('erp_order_details_id',$erp_order_detail->id)->value('id');
              DB::table('event_history')->where('history_id',$history_table_id)->delete();
              DB::table('history')->where('id',$history_table_id)->delete();
              // delete from erp_order_details table
              DB::table('erp_order_details')->where('id',$erp_order_detail->id)->delete();
          }
            
            /** @var array [Pass array of values to updateTescoErpOrderDetails function] */
            $data_array = [];   
            $tesco_buyer_id = DB::table('buyers')->select('id')->where('buyer_name','=','Tesco')->first();
            $data_array['tableName'] = $table;
            $data_array['buyer_id'] = $tesco_buyer_id->id;
            $data_array['id'] = $table_data->id;
            $data_array['erp_details_status'] = $erp_details_status;
            $data_array['erp_order_id'] = $erp_order_id;
            $data_array['created_by'] = $created_by;

            // pass $table_name, $buyer_id,  $table_data->id, $erp_details_status and $erp_order_id array
            $edit_erp_obj->updateTescoErpOrderDetails($data_array);
        }
      }
    } 
    catch (Exception $e)
    {
      return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
    }
  }
}
