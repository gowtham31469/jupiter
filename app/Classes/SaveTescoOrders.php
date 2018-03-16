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
use App\Classes\SaveTescoOrdersUpdateQuery;
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
           /** [ Creating new object for SaveTescoOrdersUpdateQuery() class ] */
           $tesco_order_obj = new SaveTescoOrdersUpdateQuery();  
           /** Insert order details first time. If data already exits, update old values**/
           if($request['is_insert'] == 'true')
           {
            /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
            $is_exists = TescoTCL1Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','=','Saved')->first();
            
              if($is_exists)
              {
                $order_id = $is_exists->id;
                /** [ Invoking updateTCL1Order function using class instance ] */
                $tesco_order  = $tesco_order_obj->updateTCL1Order($request,$order_id,$template_id);

                return $is_exists;
                
              }
              else
              {
                return TescoTCL1Orders::create([
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
           }
           else
           {
              /** [ Invoking updateTCL1Order function using class instance ] */
                $tesco_order  = $tesco_order_obj->updateTCL1Order($request,$request['tcl1_last_id'],$template_id);
                return $tesco_order;
           }
            
            
        } catch (\Exception $e)
        {
             return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
	}
  /**
   * method to save the tesco TCL2 orders
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function saveTCL2Order($request,$template_id)
  {
    try {
          /** [ Creating new object for SaveTescoOrdersUpdateQuery() class ] */
           $tesco_order_obj = new SaveTescoOrdersUpdateQuery(); 

           /** Insert order details first time. If data already exits, update old values**/
           if($request['is_insert'] == 'true')
           {
            /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
            $is_exists = TescoTCL2Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','=','Saved')->first();
            
              if($is_exists)
              {
                $order_id = $is_exists->id;
                /** [ Invoking updateTCL2Order function using class instance ] */
                $tesco_order  = $tesco_order_obj->updateTCL2Order($request,$order_id,$template_id);

                return $is_exists;
                
              }
              else
              {
                return TescoTCL2Orders::create([
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
           }
           else
           {
              /** [ Invoking updateTCL2Order function using class instance ] */
              $tesco_order  = $tesco_order_obj->updateTCL2Order($request,$request['tcl2_last_id'],$template_id);
                return $tesco_order;
           }
            
        } catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }
  /**
   * method to save the tesco TSL1 orders
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function saveTSL1Order($request,$template_id)
  {
    try {
           /** [ Creating new object for SaveTescoOrdersUpdateQuery() class ] */
           $tesco_order_obj = new SaveTescoOrdersUpdateQuery(); 

           /** Insert order details first time. If data already exits, update old values**/
           if($request['is_insert'] == 'true')
           {
            /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
            $is_exists = TescoTSL1Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','=','Saved')->first();
            
              if($is_exists)
              {
                $order_id = $is_exists->id;
                /** [ Invoking updateTCL1Order function using class instance ] */
                $tesco_order  = $tesco_order_obj->updateTSL1Order($request,$order_id,$template_id);

                return $is_exists;
                
              }
              else
              {
                return TescoTSL1Orders::create([
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
              
           }
           else
           {
              /** [ Invoking updateTSL1Order function using class instance ] */
                $tesco_order  = $tesco_order_obj->updateTSL1Order($request,$request['tsl1_last_id'],$template_id);
                return $tesco_order;
           }
            
        } catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }
  /**
   * method to save the tesco THL1 orders
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function saveTHL1Order($request,$template_id)
  {
    try {
           /** [ Creating new object for SaveTescoOrdersUpdateQuery() class ] */
           $tesco_order_obj = new SaveTescoOrdersUpdateQuery(); 

           /** Insert order details first time. If data already exits, update old values**/
           if($request['is_insert'] == 'true')
           {
            /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
            $is_exists = TescoTHL1Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','=','Saved')->first();
            
              if($is_exists)
              {
                $order_id = $is_exists->id;
                /** [ Invoking updateTHL1Order function using class instance ] */
                $tesco_order  = $tesco_order_obj->updateTHL1Order($request,$order_id,$template_id);

                return $is_exists;
                
              }
              else
              {
                return TescoTHL1Orders::create([
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
            }
            else
            {
              /** [ Invoking updateTHL1Order function using class instance ] */
              $tesco_order  = $tesco_order_obj->updateTHL1Order($request,$request['thl1_last_id'],$template_id);
                return $tesco_order;
            }
            
        } catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }
  /**
   * method to save the tesco TCL4 orders
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function saveTCL4Order($request,$template_id)
  {
    try {
           /** [ Creating new object for SaveTescoOrdersUpdateQuery() class ] */
           $tesco_order_obj = new SaveTescoOrdersUpdateQuery();  

           /** Insert order details first time. If data already exits, update old values**/
           if($request['is_insert'] == 'true')
           {
              /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
              $is_exists = TescoTCL4Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','=','Saved')->first();
              
                if($is_exists)
                {
                  $order_id = $is_exists->id;
                  /** [ Invoking updateTCL4Order function using class instance ] */
                  $tesco_order  = $tesco_order_obj->updateTCL4Order($request,$order_id,$template_id);

                  return $is_exists;
                  
                }
                else
                {
                  return TescoTCL4Orders::create([
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
           }
           else
           {
              /** [ Invoking updateTCL4Order function using class instance ] */
              $tesco_order  = $tesco_order_obj->updateTCL4Order($request,$request['tcl4_last_id'],$template_id);
                return $tesco_order;
           }
            
        } catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }
  /**
   * method to save the tesco TSL3 orders
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function saveTSL3Order($request,$template_id)
  {
    try {
            /** [ Creating new object for SaveTescoOrdersUpdateQuery() class ] */
            $tesco_order_obj = new SaveTescoOrdersUpdateQuery();

            /** Insert order details first time. If data already exits, update old values**/
            if($request['is_insert'] == 'true')
            {
              /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
              $is_exists = TescoTSL3Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','=','Saved')->first();
              
                if($is_exists)
                {
                  $order_id = $is_exists->id;
                  /** [ Invoking updateTSL3Order function using class instance ] */
                  $tesco_order  = $tesco_order_obj->updateTSL3Order($request,$order_id,$template_id);

                  return $is_exists;
                  
                }
                else
                {
                  return TescoTSL3Orders::create([
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
            }
            else
            {
              /** [ Invoking updateTSL3Order function using class instance ] */
              $tesco_order  = $tesco_order_obj->updateTSL3Order($request,$request['tsl3_last_id'],$template_id);
                return $tesco_order;
            }
            
        } catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }
  /**
   * method to save the tesco THL3 orders
   * @param  [type] $request, $template_id
   * @return tesco order table id (inserted_id)
   */
  public function saveTHL3Order($request,$template_id)
  {
    try {
            /** [ Creating new object for SaveTescoOrdersUpdateQuery() class ] */
            $tesco_order_obj = new SaveTescoOrdersUpdateQuery();

            /** Insert order details first time. If data already exits, update old values**/
            if($request['is_insert'] == 'true')
            {
              /** [ Check if order already placed for this PO number+CE style+UK style combination ] */
              $is_exists = TescoTHL3Orders::select('id')->where('po_number','=',$request['po_number'])->where('uk_style_ref','=',$request['uk_style'])->where('ce_style_ref','=',$request['ce_style'])->where('order_status','=','Saved')->first();
              
                if($is_exists)
                {
                  $order_id = $is_exists->id;
                  /** [ Invoking updateTHL3Order function using class instance ] */
                  $tesco_order  = $tesco_order_obj->updateTHL3Order($request,$order_id,$template_id);

                  return $is_exists;
                  
                }
                else
                {
                  return TescoTHL3Orders::create([
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
              
            }
            else
            {
              /** [ Invoking updateTHL3Order function using class instance ] */
              $tesco_order  = $tesco_order_obj->updateTHL3Order($request,$request['thl3_last_id'],$template_id);
              return $tesco_order;
            }
            
            
        } catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }
}
