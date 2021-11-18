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
use App\OrderManagementHistory;
use App\Classes\StoreOrderManagementHistory;
use App\ErpOrderDetails;

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
	 * method to store the tesco orders
	 * @param  [type] $request, $template_id
	 * @return tesco order table id (inserted_id)
	 */
	public function createTescoOrder($request,$template_id,$pi_required,$is_order_count_empty,$customer_id,$created_by,$po_required, $order_status, $edit_permission)
	{
		try {
          $order_history_obj = new StoreOrderManagementHistory();

          /** [ pi_required for this customer or not. If required then pi_staus will be "PI Approval Pending"  ] */
          $pi_status = $pi_required == 1?"PI Approval Pending":null;

          /** [ po_required for this customer or not. If required then po_staus will be "PO Pending"  ] */
          $po_status = $po_required == 1?"PO Pending":null;

          /** [ Get company id for template ] */
          $template_data = DB::table('tesco_item_master')->select('tesco_item_master.item_name', 'reports_template_level_name.name as name_in_reports', 'tesco_item_master.company_id')->join('tesco_template', 'tesco_item_master.id', 'tesco_template.item_id')->join('reports_template_level_name', 'reports_template_level_name.id', 'tesco_template.name_in_reports')->where('tesco_item_master.id','=',$template_id)->first();

          //Dynamic function name and database model name for each templates
          $order_model = 'App\\'."Tesco".str_replace(" ","",$template_data->item_name)."Orders";

          // total order quantity calculation
          $total_qty_array = $request['sticker_count'][$template_data->item_name];
          $total_qty = array_sum($total_qty_array);

          /** [ Get orders table maximum id value for creating order id] */
          $max_id = $order_model::max('id');

          $order_id = $template_data->company_id == 1?"JGA_".str_replace(" ","",$template_data->item_name)."00".($max_id+1):"SP_".str_replace(" ","",$template_data->item_name)."00".($max_id+1);

          /** Insert order details first time. If data already exits, update old values**/
          
          $order_submitted = $order_model::updateOrCreate([strtolower($request['zone']).'_style_ref' => $request[strtolower($request['zone']).'_style'], 'order_status' => 'Saved', 'po_number' => $request['po_number']], [
                    'order_id'             => $order_id,
                    'zone'                 => $request['zone'],
                    'template_type'        => $request['type'],
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
                    'order_status'         => $order_status,
                    'template_id'          => $template_id,
                    'pi_status'            => $pi_status,
                    'po_status'            => $po_status,
                    'is_order_count_empty' => $is_order_count_empty,
                    'order_placed_by'      => $created_by,
                    'order_created_at'     => $this->current_date,
                    'ocn_number'           => $request['ocn_no'],
                    'full_description'     => $request['full_description'],
                    'edit_permission'      => $edit_permission,
                    'total_qty'            => $total_qty
                  ]);

          /** Insert order data into history table */
          $order_data = [];

          $order_data['season_code'] = $request['season_code'];

          $order_data['po_number'] = $request['po_number'];

          $order_data['ocn_no'] = $request['ocn_no'];

          $order_data['style'] = $request[strtolower($request['zone']).'_style'];

          $order_data['customer_id'] = $customer_id;

          $order_data['template_id'] = $template_id;

          $order_data['order_id'] = $order_submitted->id;

          $order_data['po_status'] = $po_status;

          $order_data['pi_status'] = $pi_status;

          $order_data['order_status_id'] = $order_status === 'Order Pending'?6:2;

          $order_data['edit_permission'] = $edit_permission;
          
          $order_data['tesco_label'] = false;

          $order_history_obj->storeOrdersHistory($order_data);

          return $order_submitted;
            
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
  public function updateTescoOrders($table_name,$temp_data,$request,$customer_id, $created_by, $barcode_type)
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
      $tpnd_type = $zone === 'CE'?'orms_tpn':'tpnd';
      $carton_unit_type = $zone === 'CE'?'cases_per_carton':'units_per_carton';
      $qr_code         = explode('|', $request['qr_code']);
     
      if ($table_name == "tesco_tcl1_orders" || $table_name == "tesco_tcl2_orders" || $table_name == "tesco_tsl1_orders") {
          $table_array = array('tesco_tcl1_orders','tesco_tcl2_orders','tesco_tsl1_orders');
          $qnty_id_array = array('tesco_tcl1_orders_quanty','tesco_tcl2_orders_quanty','tesco_tsl1_orders_quanty');
          $template_array = array_combine($table_array, $qnty_id_array);
          $single_size    = $request['single_size'];
      }
      else if ($table_name == "tesco_tcl4_orders" || $table_name == "tesco_tsl3_orders" || $table_name == "tesco_tsl2_orders") {
          $table_array = array('tesco_tcl4_orders','tesco_tsl3_orders', 'tesco_tsl2_orders');
          $qnty_id_array = array('tesco_tcl4_orders_quanty','tesco_tsl3_orders_quanty','tesco_tsl2_orders_quanty');
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
                       ->select('count','id', 'ean_barcode', 'itf_barcode')->get(); 
        
       DB::table($qunty_tbl)->where($qnty_id,$table_data->id)->delete();
        
        // update quantity details(dynamic rows) for tempalate
        $i=0;              
        foreach($tpnd as $value)
        {
           $quantity = isset($quantity_data[$i])?$quantity_data[$i]->count:0;
           $second_barcode_type = $barcode_type === 'itf_barcode'?'ean_barcode':'itf_barcode';
           $old_barcode = isset($quantity_data[$i])?$quantity_data[$i]->$second_barcode_type:0;

            if(($template_name == "TCL1") ||($template_name == "TCL2") || ($template_name == "TSL1"))
            {
                  DB::table($qunty_tbl)->insert([
                    $tpnd_type           => $value,
                    $barcode_type        => $barcode[$i],
                    $qnty_id             => $table_data->id,
                    $carton_unit_type   => $case_size[$i],
                    'size'               => null,
                    'count'              => $quantity,
                    'ratio_pack_type'    => $this->ratio_pack_type[$i],
                    'qr_code'            => $qr_code[$i]
                  ]);
            }
            else if(($template_name == "TCL4") || ($template_name == "TSL3") || ($template_name == "TSL2"))
            {
                    DB::table($qunty_tbl)->insert([
                      $tpnd_type        => $value,
                      $barcode_type     => $barcode[$i],
                      $second_barcode_type => $old_barcode,
                      'case_size'       => $case_size[$i],
                      $qnty_id          => $table_data->id,
                      'size'            => $single_size[$i],
                      'count'           => $quantity,
                      'qr_code'         => $qr_code[$i]
                    ]);
            }
            $i++;
        }

        // fetch erp_order_details table details for each quantity_table ids.
        $erp_order_details = ErpOrderDetails::join('erp_status', 'erp_status.id', 'erp_order_details.status')
        ->where('order_qnty_table_name', $qunty_tbl)
        ->whereIn('order_qnty_table_id', $quantity_data->pluck('id')->toArray())
        ->select('erp_order_details.id', 'erp_status.status_name', 'erp_order_details.erp_order_id')
        ->get();

        if($erp_order_details->isNotEmpty())
        {
            $erp_details_status =  $erp_order_details[0]->status_name;
            $erp_order_id =  $erp_order_details[0]->erp_order_id;

            $erp_order_details_id_array = $erp_order_details->pluck('id')->toArray();
        
            // delete from erp_order_details table
            DB::table('erp_order_details')->whereIn('id', $erp_order_details_id_array)->delete();
            
            $data_array = [];   
            $data_array['tableName'] = $table;
            $data_array['buyer_id'] = DB::table('buyers')->where('buyer_name', 'Tesco')->value('id');
            $data_array['id'] = $table_data->id;
            $data_array['erp_details_status'] = $erp_details_status;
            $data_array['erp_order_id'] = $erp_order_id;
            $data_array['created_by'] = $created_by;

            $edit_erp_obj->updateTescoErpOrderDetails($data_array);
        }

        // update order data in order management history table 
        OrderManagementHistory::where([['order_table_name', $table], ['order_table_id', $table_data->id]])->update([
          'season'                    => $request['season_code'],
          'customer_id'               => $customer_id,
          'style'                     => $request[strtolower($request['zone']).'_style'],
          'buyer_po'                  => $request['po_number'],
          'ocn_number'                => $request['tesco_ocn'],
        ]);
      }
    } 
    catch (Exception $e)
    {
      return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
    }
  }
}
