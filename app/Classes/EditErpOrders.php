<?php
namespace App\Classes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Customer;
use App\CustomerBillAddress;
use DB;
use Auth;
use App\RolePermissionMdl;
use Carbon\Carbon;
use App\ErpOrders as Erp;
use App\ErpOrderDetails;
use App\History;

class EditErpOrders
{
	private $updated_at;

	/**
	 * updateTescoErpOrderDetails Function
	 *
	 * @param [type] $data_array
	 * @return void
	 * @author Praveen <praveen@wizroots.com>
	 */
	public function updateTescoErpOrderDetails($data_array)
	{
		try
		{			
			$table_name     = $data_array['tableName']; 
			$buyer_id		= $data_array['buyer_id'];
            $row_id		    = $data_array['id'];
            $erp_details_status	= $data_array['erp_details_status'];
			$erp_order_id		 = $data_array['erp_order_id'];
            $qnty_table     = $table_name.'_quanty' ;
			$qnty_id        =  str_replace("_orders","_order_id",str_replace("tesco_","",$table_name));
			// Get Erp status table id for  status name.
            $erp_status_id = DB::table('erp_status')->where('status_name', 'ilike', $erp_details_status)->value('id');
            
			$this->updated_at = Carbon::now('Asia/Kolkata')->toDateTimeString();
            $orders     	= DB::table($table_name)->where($table_name.'.id',$row_id)
            					->join('tesco_item_master',$table_name.'.template_id','=','tesco_item_master.id')
                                ->select('order_id','po_number','uk_style_ref','ce_style_ref','season_code','short_description','eqos_site_code','manufacture_date','tesco_department_id','tesco_brand_id','tesco_supplier_id as customer_id','rfid_compliant','tag_at_source','zone','template_type','pi_status','po_status','po_file','purchase_order_no','order_status','template_id','delivery_date','order_placed_by','pi_rev_no','edit_permission','cancel_ord_flag','order_created_at','order_approved_at','order_cancelled_at','po_uploaded_at','tesco_item_master.company_id','tesco_item_master.item_name','ocn_number','order_flag','pi_id','pi_file')->first();
            $order_details  = DB::table($qnty_table)->where($qnty_id,$row_id)
            				->get();
			$order_status   = $orders->order_status;

			$customer_data  = DB::table('customers')->where('id',$orders->customer_id)->first();

			if ($customer_data->pi_required == 1 && $customer_data->po_required== 0) {
				$po_no     = $orders->pi_id;
				$po_file   = $orders->pi_file;
			}
			else if($customer_data->po_required== 1){
				$po_no     = $orders->purchase_order_no;
				$po_file   = $orders->po_file;
			}

			// get order_status table id for the status name.
			$order_status_id  = DB::table('orders_status')->select('id')->where('status_name', 'ilike', $order_status)->first();
			
			// update customer id in erp_orders table
            Erp::where('id',$erp_order_id)->update(['customer_id' => $orders->customer_id]);

            $this->updateTescoOrderDetails($qnty_table, $order_details, $orders, $erp_order_id, $buyer_id, $erp_status_id, $order_status_id, $po_no);

			$result = response()->json(['error'=> false,'message' =>'success'],200);
		}
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}

	/**
	 * updateTescoOrderDetails Function to update erp_order_details table while editing from order status page 
	 *
	 * @param [type] $qnty_table
	 * @param [type] $order_details
	 * @param [type] $orders
	 * @param [type] $erp_order_id
	 * @param [type] $buyer_id
	 * @param [type] $erp_status_id
	 * @return void
	 * @author Praveen <praveen@wizroots.com>
	 */
	function updateTescoOrderDetails($qnty_table, $order_details, $orders, $erp_order_id, $buyer_id, $erp_status_id, $order_status_id, $customer_po_no)
	{
		if ($order_details)
		{

			foreach ($order_details as $key => $vals) {

				$style_no = ($orders->zone == "CE") ? $orders->ce_style_ref : $orders->uk_style_ref ;
				$custom_ref_name = DB::table('price_taxation')->where('customer_id',$orders->customer_id)->where('buyer_id',$buyer_id)->where('template_id',$orders->template_id)->value('custom_name');
				$dataExists = DB::table('erp_order_details')->where('order_qnty_table_name',$qnty_table)->where('order_qnty_table_id',$vals->id)->where('erp_order_id',$erp_order_id)->count();
				
				try{

					if($dataExists == 0)
					{
						// unique id for array creation
						// combination of erpid-refname-styleno-buyerpo-ordertype
						$unique_id = 'ERP-'.str_replace(' ', '-', $orders->item_name).'-'.$erp_order_id.'-'.$style_no.'-'.$orders->po_number.'-'.$orders->order_flag;

						$erp_order_details_id = ErpOrderDetails::insertGetId([
							'erp_order_id'     		=> $erp_order_id,
							'style_no'         		=> $style_no,
							'order_quantity'   		=> $vals->count,
							'pending_orders'		=> $vals->count,
							'size'         	   		=> $vals->size,
							'delivery_date'	   		=> $orders->delivery_date,
							'order_qnty_table_name' => $qnty_table,
							'order_qnty_table_id' 	=> $vals->id,
							'template_id'			=> $orders->template_id,
							'company_id'			=> $orders->company_id,
							'reference_name'		=> $orders->item_name,
							'ocn_number'	   		=> $orders->ocn_number,
							'status' 				=> $erp_status_id,
							'template_type'         => $orders->template_type,
							'custom_ref_name' 		=> $custom_ref_name,
							'buyer_po_number'  		=> $orders->po_number,
							'zone'					=> $orders->zone,
							'order_type'            => $orders->order_flag,
							'created_at'            => $this->updated_at,
							'updated_at'            => $this->updated_at,
							'order_status_id'       => $order_status_id->id,
							'order_date'            => $orders->order_created_at,
							'aw_date'               => $orders->order_approved_at,
							'uuid'                  => $unique_id
						]);
	
						/** Get updated data and update in history table */
						$template          = 'Available';
						$action_id         = DB::table('actions')->where('action', 'ilike', 'PO Manager Verification Pending')->value('id');
						
						$template_level = DB::table('tesco_item_master as t1')
											->select('t3.item_name as material_sub', 't4.item_name as material', 't1.item_name as ref')
											->leftJoin('tesco_item_master as t2', 't2.id','=','t1.parent_id')
											->leftJoin('tesco_item_master as t3', 't3.id','=','t2.parent_id')
											->leftJoin('tesco_item_master as t4', 't4.id','=','t3.parent_id')
											->where('t1.id', $orders->template_id)
											->first();
						
						$material_name  = $template_level->material;
						$material_sub   = $template_level->material_sub;
						$material_ref   = $template_level->ref;
	
						$history_id = DB::table('history')
						->insertGetId([
							'buyer_id'             => $buyer_id,
							'erp_order_details_id' => $erp_order_details_id,
							'template'             => $template,
							'season'               => $orders->season_code,
							'country'              => $orders->zone,
							'customer_id'          => $orders->customer_id,
							'material'             => $material_name,
							'mat_sub'              => $material_sub,
							'reference'            => $material_ref,
							'style'                => $style_no,
							'po'                   => $customer_po_no,
							'buyer_po'             => $orders->po_number,
							'ocn'                  => $orders->ocn_number,
							'created_at'           => $this->updated_at,
							'updated_at'           => $this->updated_at,
						]);
	
						DB::table('event_history')
						->insert([
							'history_id'  => $history_id,
							'action_id'   => $action_id,
							'created_at'  => $this->updated_at,
							'updated_at'  => $this->updated_at,
						]);
					}
				} catch (\Exception $e) {
					$errorCode  = $e->errorInfo[1];
					if ($errorCode === 1062) { // Duplicate Entry error code
						return true;
					}
					// continue;
				}	

				// remove erp_orders table row , if no FK relation exists in erp_order_details table
				DB::table('erp_orders')->select('erp_orders.id')->whereNotIn('erp_orders.id', function($rightjoin) {                                             $rightjoin->select('erp_orders.id')
					->from('erp_orders')
					->rightjoin('erp_order_details', 'erp_orders.id', 'erp_order_details.erp_order_id')->groupBy('erp_orders.id');
				})->delete();
				
			}
		}
	}

	/**
	 * updateOrchestraErpOrderDetails Function
	 *
	 * @param [type] $data_array
	 * @return void
	 * @author Praveen <praveen@wizroots.com>
	 */
	public function updateOrchestraErpOrderDetails($data_array)
	{
		try
		{			
			$main_table_name    = $data_array['main_table']; 
			$main_table_id      = $data_array['main_table_id']; 
			$quantity_table     = $data_array['quantity_table']; 
			$buyer_id		    = $data_array['buyer_id'];
			$quantity_id_field  = $data_array['quantity_id_field'];
			$erp_details_status = $data_array['erp_details_status'];
			$erp_order_id		= $data_array['erp_order_id'];

			// Get Erp status table id for  status name.
            $erp_status_id = DB::table('erp_status')->where('status_name', 'ilike', $erp_details_status)->value('id');
			$this->created_at = Carbon::now('Asia/Kolkata')->toDateTimeString();

			// template front and back id storing in orchestra_hang_tag_orders table
			if($main_table_name === 'orchestra_hang_tag_orders')
			{
				$orders = DB::table($main_table_name)->where($main_table_name.'.id',$main_table_id)
							->join('orchestra_item_master',$main_table_name.'.template_front_id','=','orchestra_item_master.id')
							->select('orchestra_item_master.*', $main_table_name.'.*')->first();
			}
			else   
			{
				$orders = DB::table($main_table_name)->where($main_table_name.'.id',$main_table_id)
							->join('orchestra_item_master',$main_table_name.'.template_id','=','orchestra_item_master.id')
							->select('orchestra_item_master.*', $main_table_name.'.*')->first();
			}
            
            $order_details  = DB::table($quantity_table)->where($quantity_id_field,$main_table_id)
            				->get();
			$order_status   = $orders->status;

			$customer_data  = DB::table('customers')->where('id',$orders->customer_id)->first();

			if ($customer_data->pi_required === 1 && $customer_data->po_required === 0) {
				$po_no     = $orders->pi_id;
				$po_file   = $orders->pi_file;
			}
			else if($customer_data->po_required === 1){
				$po_no     = $orders->po_number;
				$po_file   = $orders->po_file;
			}

			// get order_status table id for the status name.
			$order_status_id  = DB::table('orders_status')->select('id')->where('status_name', 'ilike', $order_status)->first();
			
			// update customer id in erp_orders table
            Erp::where('id',$erp_order_id)->update(['customer_id' => $orders->customer_id]);

            $this->updateOrchestraOrderDetails($quantity_table, $order_details, $orders, $erp_order_id, $buyer_id, $erp_status_id, $order_status_id, $po_no, $main_table_name);

			$result = response()->json(['error'=> false,'message' =>'success'],200);
		}
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}

	/**
	 * updateOrchestraOrderDetails Function to update erp_order_details table while editing from order status page 
	 *
	 * @param [type] $qnty_table
	 * @param [type] $order_details
	 * @param [type] $orders
	 * @param [type] $erp_order_id
	 * @param [type] $buyer_id
	 * @param [type] $erp_status_id
	 * @return void
	 * @author Praveen <praveen@wizroots.com>
	 */
	function updateOrchestraOrderDetails($qnty_table, $order_details, $orders, $erp_order_id, $buyer_id, $erp_status_id, $order_status_id, $customer_po_no, $main_table_name)
	{
		if ($order_details)
		{

			foreach ($order_details as $key => $vals) {

				// template front and back id storing in orchestra_hang_tag_orders table
				if($main_table_name === 'orchestra_hang_tag_orders')
				{
					$custom_ref_name = DB::table('price_taxation')->where('customer_id',$orders->customer_id)->where('buyer_id',$buyer_id)->where('template_id',$orders->template_front_id)->value('custom_name');
					$template_id = $orders->template_front_id;
				}
				else   
				{
					$custom_ref_name = DB::table('price_taxation')->where('customer_id',$orders->customer_id)->where('buyer_id',$buyer_id)->where('template_id',$orders->template_id)->value('custom_name');
					$template_id = $orders->template_id;
				}
				$dataExists = DB::table('erp_order_details')->where('order_qnty_table_name',$qnty_table)->where('order_qnty_table_id',$vals->id)->where('erp_order_id',$erp_order_id)->count();
				
				try{

					if($dataExists == 0)
					{
						// unique id for array creation
						// combination of erpid-refname-styleno-buyerpo-ordertype
						$unique_id = 'ERP-'.str_replace(' ', '-', $orders->item_name).'-'.$erp_order_id.'-'.$orders->uvc.'-'.$orders->po.'-'.$orders->order_flag;

						$erp_order_details_id = ErpOrderDetails::insertGetId([
							'erp_order_id'     		=> $erp_order_id,
							'style_no'         		=> $orders->uvc,
							'order_quantity'   		=> $vals->quantity,
							'pending_orders'		=> $vals->quantity,
							'size'         	   		=> $vals->size,
							'delivery_date'	   		=> $orders->delivery_date,
							'order_qnty_table_name' => $qnty_table,
							'order_qnty_table_id' 	=> $vals->id,
							'template_id'			=> $template_id,
							'company_id'			=> $orders->company_id,
							'reference_name'		=> $orders->item_name,
							'status' 				=> $erp_status_id,
							'custom_ref_name' 		=> $custom_ref_name,
							'buyer_po_number'  		=> $orders->po,
							'order_type'            => $orders->order_flag,
							'created_at'            => $this->created_at,
							'order_status_id'       => $order_status_id->id,
							'order_date'            => $orders->order_created_at,
							'aw_date'               => $orders->order_approved_at,
							'uuid'                  => $unique_id,
							'template_type'         => $orders->template_type,
						]);

						// write data into history tables
						$action_id      = DB::table('actions')->where('action', 'ilike', 'PO Manager Verification Pending')->value('id');

						$template_data = DB::table('orchestra_item_master')
											->where('orchestra_item_master.id', $template_id)
											->select('orchestra_item_master.material_id', 'orchestra_item_master.item_name', 'orchestra_item_master.sub_material_id')->first();
						$material_name  = DB::table('orchestra_item_master')->where('id', '=', $template_data->material_id)->value('item_name');
						$material_sub  = DB::table('orchestra_item_master')->where('id', '=', $template_data->sub_material_id)->value('item_name');

						$material_ref   = $template_data->item_name;
						$buyer_po       = $orders->po;
						$style          = $orders->uvc;
						$ocn_number     = null;
						$season         = $orders->season_code;
						$country        = null;
						$template       = 'Available';

						/** Get updated data and update in history table */
						$history_id = DB::table('history')
						->insertGetId([
							'buyer_id'             => $buyer_id,
							'erp_order_details_id' => $erp_order_details_id,
							'template'             => $template,
							'season'               => $season,
							'country'              => $country,
							'customer_id'          => $orders->customer_id,
							'material'             => $material_name,
							'mat_sub'              => $material_sub,
							'reference'            => $material_ref,
							'style'                => $style,
							'po'                   => $customer_po_no,
							'buyer_po'             => $buyer_po,
							'ocn'                  => $ocn_number,
							'created_at'           => $this->created_at,
							'updated_at'           => $this->created_at,
						]);

						DB::table('event_history')
						->insert([
							'history_id'  => $history_id,
							'action_id'   => $action_id,
							'created_at'  => $this->created_at,
							'updated_at'  => $this->created_at,
						]);
					}	
				} catch (\Exception $e) {
					$errorCode  = $e->errorInfo[1];
					if ($errorCode === 1062) { // Duplicate Entry error code
						return true;
					}
					// continue;
				}

				// remove erp_orders table row , if no FK relation exists in erp_order_details table
				DB::table('erp_orders')->select('erp_orders.id')->whereNotIn('erp_orders.id', function($rightjoin) {                                                  $rightjoin->select('erp_orders.id')
					->from('erp_orders')
					->rightjoin('erp_order_details', 'erp_orders.id', 'erp_order_details.erp_order_id')->groupBy('erp_orders.id');
				})->delete();
			}
		}
	}
}