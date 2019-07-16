<?php
namespace App\Classes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use Illuminate\Support\Facades\Input;
use DB;
use Carbon\Carbon;
use App\ErpOrders as Erp;
use App\ErpOrderDetails;
use Auth;

class ErpOrders
{
	private $created_at;

	/**
	 * Update tesco orders details in erp_orders table
	 *
	 * @param [type] $request
	 * @return void
	 */
	public function updateTescoErpOrders($request)
	{
		try
		{			
			$table_name     = $request['tableName']; 
			$buyer_id		= $request['buyer_id'];
			$row_id		    = $request['id'];
            $qnty_table     = $table_name.'_quanty' ;
			$qnty_id        =  str_replace("_orders","_order_id",str_replace("tesco_","",$table_name));
			
			$this->created_at = Carbon::now('Asia/Kolkata')->toDateTimeString();
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

			$order_status_id  = DB::table('orders_status')->select('id')->where('status_name', 'ilike', $order_status)->first();
			
			$erp_orders = Erp::where('buyer_id',$buyer_id)->where('customer_id',$orders->customer_id)->where('po_no',$po_no)->first();

			if ($erp_orders) {
			
				Erp::where('id',$erp_orders->id)->update(['status'=>1, 'po_file' => $po_file]);
				$status = 1;
				$r = $this->insertOrderDetails($qnty_table, $order_details, $orders, $erp_orders, $buyer_id, $status, $erp_orders->id, $order_status_id, $po_no);
			}
			else
			{
				$erp_orders = Erp::create([
								'buyer_id'         => $buyer_id,
								'customer_id'      => $orders->customer_id,
								'po_no'            => $po_no,
								'po_file'          => $po_file,
								'status'		   => 1,
								'created_at'       => $this->created_at
							 ]);
				$erp_id = "ERPID-0".$erp_orders->id;
				Erp::where('id',$erp_orders->id)->update(['erp_id'=>$erp_id]);
				$status = 1;

				$this->insertOrderDetails($qnty_table, $order_details, $orders, $erp_orders, $buyer_id, $status, $erp_orders->id, $order_status_id, $po_no);
			}
			$result = response()->json(['error'=> false,'message' =>'success'],200);
		}
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}
	
	/**
	 * Insert Tesco Order details in erp_order_details table
	 *
	 * @param [type] $qnty_table
	 * @param [type] $order_details
	 * @param [type] $orders
	 * @param [type] $erp_orders
	 * @param [type] $buyer_id
	 * @param [type] $status
	 * @param [type] $erp_orders_id
	 * @return void
	 */
	function insertOrderDetails($qnty_table, $order_details, $orders, $erp_orders, $buyer_id, $status, $erp_orders_id, $order_status_id, $customer_po_no)
	{
		if ($order_details)
		{
			foreach ($order_details as $key => $vals) {

				$style_no       = ($orders->zone == "CE") ? $orders->ce_style_ref : $orders->uk_style_ref ;

				$custom_ref_name = DB::table('price_taxation')->where('customer_id',$orders->customer_id)->where('buyer_id',$buyer_id)->where('template_id',$orders->template_id)->value('custom_name');

				$dataExists = DB::table('erp_order_details')->where('order_qnty_table_name',$qnty_table)->where('order_qnty_table_id',$vals->id)->where('erp_order_id',$erp_orders_id)->first();

				if(!empty($dataExists)){
					//$erp_status_id = $dataExists->status;
					//DB::table('erp_order_details')->where('id',$dataExists->id)->delete();
				}
				else 
				{
					DB::table('erp_order_details')->where('order_qnty_table_name',$qnty_table)->where('order_qnty_table_id',$vals->id)->delete();
					$erp_order_details_id = ErpOrderDetails::insertGetId([
						'erp_order_id'     		=> $erp_orders->id,
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
						'status' 				=> $status,
						'template_type'         => $orders->template_type,
						'custom_ref_name' 		=> $custom_ref_name,
						'buyer_po_number'  		=> $orders->po_number,
						'zone'					=> $orders->zone,
						'order_type'            => $orders->order_flag,
						'created_at'            => $this->created_at,
						'order_status_id'       => $order_status_id->id,
					]);
				}

				/** Get updated data and update in history table */
				$template          = 'Avlb';
				$current_timestamp = Carbon::now('Asia/Kolkata')->toDateTimeString();
				$action_id         = DB::table('actions')->where('action', 'ilike', 'PO Mngr Verified Pending')->value('id');
				
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
					'created_at'           => $current_timestamp,
					'updated_at'           => $current_timestamp,
				]);

				DB::table('event_history')
				->insert([
					'history_id'  => $history_id,
					'action_id'   => $action_id,
					'created_by'  => Auth::guard('web')->id(),
					'created_at'  => $current_timestamp,
					'updated_at'  => $current_timestamp,
				]);

				// remove erp_orders table row , if no FK relation exists in erp_order_details table
				DB::table('erp_orders')->select('erp_orders.id')->whereNotIn('erp_orders.id', function($rightjoin) {                                                  $rightjoin->select('erp_orders.id')
					     ->from('erp_orders')
						 ->rightjoin('erp_order_details', 'erp_orders.id', 'erp_order_details.erp_order_id')                                                 ->groupBy('erp_orders.id');
				})->delete();
			}
		}
	}

	/**
	 * Update orchestra orders details in erp_orders table
	 *
	 * @param [type] $request
	 * @return void
	 */
	public function updateOrchestraErpOrders($request)
	{
		try
		{			
		    $table_name     = $request['tableName']; 
			$buyer_id		= $request['buyer_id'];
			$row_id		    = $request['id'];
			$qnty_table     = $table_name.'_qty' ;
			
			// get the first letter of each word for a given string
            $result_str  =  str_replace("_"," ",str_replace("_orders","",$table_name));
            $qnty_id = '';
            foreach (explode(' ', $result_str) as $word)
            {
                $qnty_id .= strtolower($word[0]);
            }
			
			$this->created_at = Carbon::now('Asia/Kolkata')->toDateTimeString();

			// template front and back id storing in orchestra_hang_tag_orders table
			if($table_name === 'orchestra_hang_tag_orders')
			{
				$orders = DB::table($table_name)->where($table_name.'.id',$row_id)
							->join('orchestra_item_master',$table_name.'.template_front_id','=','orchestra_item_master.id')
							->select('orchestra_item_master.*', $table_name.'.*')->first();
			}
			else   
			{
				$orders = DB::table($table_name)->where($table_name.'.id',$row_id)
							->join('orchestra_item_master',$table_name.'.template_id','=','orchestra_item_master.id')
							->select('orchestra_item_master.*', $table_name.'.*')->first();
			}
            
            $order_details  = DB::table($qnty_table)->where($qnty_id.'_id',$row_id)
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

			$order_status_id  = DB::table('orders_status')->select('id')->where('status_name', 'ilike', $order_status)->first();
			
			$erp_orders = Erp::where('buyer_id',$buyer_id)->where('customer_id',$orders->customer_id)->where('po_no',$po_no)->first();
						

			if ($erp_orders) {
			
				Erp::where('id',$erp_orders->id)->update(['status'=>1, 'po_file' => $po_file]);
					$status = 1;
			    $this->insertOrchestraOrderDetails($qnty_table, $order_details, $orders, $erp_orders, $buyer_id, $status, $erp_orders->id, $table_name, $order_status_id, $po_no);
			}
			else
			{
				$erp_orders = Erp::create([
								'buyer_id'         => $buyer_id,
								'customer_id'      => $orders->customer_id,
								'po_no'            => $po_no,
								'po_file'          => $po_file,
								'status'		   => 1,
								'created_at'       => $this->created_at
							 ]);
				$erp_id = "ERPID-0".$erp_orders->id;
				Erp::where('id',$erp_orders->id)->update(['erp_id'=>$erp_id]);
				$status = 1;

				$this->insertOrchestraOrderDetails($qnty_table, $order_details, $orders, $erp_orders, $buyer_id, $status, $erp_orders->id, $table_name, $order_status_id, $po_no);
			}
			$result = response()->json(['error'=> false,'message' =>'success'],200);
		}
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}

   /**
	 * Insert Orchestra Order details in erp_order_details table
	 *
	 * @param [type] $qnty_table
	 * @param [type] $order_details
	 * @param [type] $orders
	 * @param [type] $erp_orders
	 * @param [type] $buyer_id
	 * @param [type] $status
	 * @param [type] $erp_orders_id
	 * @return void
	 */
	function insertOrchestraOrderDetails($qnty_table, $order_details, $orders, $erp_orders, $buyer_id, $status, $erp_orders_id, $table_name, $order_status_id, $customer_po_no)
	{
		if ($order_details)
		{
			foreach ($order_details as $key => $vals) {

				// template front and back id storing in orchestra_hang_tag_orders table
				if($table_name === 'orchestra_hang_tag_orders')
				{
					$custom_ref_name = DB::table('price_taxation')->where('customer_id',$orders->customer_id)->where('buyer_id',$buyer_id)->where('template_id',$orders->template_front_id)->value('custom_name');
					$tempalte_id = $orders->template_front_id;
				}
				else   
				{
					$custom_ref_name = DB::table('price_taxation')->where('customer_id',$orders->customer_id)->where('buyer_id',$buyer_id)->where('template_id',$orders->template_id)->value('custom_name');
					$tempalte_id = $orders->template_id;
				}

				$dataExists = DB::table('erp_order_details')->where('order_qnty_table_name',$qnty_table)->where('order_qnty_table_id',$vals->id)->where('erp_order_id',$erp_orders_id)->first();

				if(!empty($dataExists)){
					//$erp_status_id = $dataExists->status;
					//DB::table('erp_order_details')->where('id',$dataExists->id)->delete();
				}
				else 
				{
					DB::table('erp_order_details')->where('order_qnty_table_name',$qnty_table)->where('order_qnty_table_id',$vals->id)->delete();
					$erp_order_details_id = ErpOrderDetails::insertGetId([
						'erp_order_id'     		=> $erp_orders->id,
						'order_quantity'   		=> $vals->quantity,
						'pending_orders'		=> $vals->quantity,
						'size'         	   		=> $vals->size,
						'delivery_date'	   		=> $orders->delivery_date,
						'order_qnty_table_name' => $qnty_table,
						'order_qnty_table_id' 	=> $vals->id,
						'template_id'			=> $tempalte_id,
						'company_id'			=> $orders->company_id,
						'reference_name'		=> $orders->item_name,
						'status' 				=> $status,
						'custom_ref_name' 		=> $custom_ref_name,
						'buyer_po_number'  		=> $orders->po,
						'order_type'            => $orders->order_flag,
						'created_at'            => $this->created_at,
						'order_status_id'       => $order_status_id->id,
					]);
				}

				/** Get updated data and update in history table */
				// $template       = 'Avlb';
				// $country        = "";
				// $current_timestamp = Carbon::now('Asia/Kolkata')->toDateTimeString();
				// $action_id      = DB::table('actions')->where('action', 'ilike', 'PO Mngr Verified Pending')->value('id');
				
				
				// $template_level = DB::table('tesco_item_master as t1')
				// 					->select('t3.item_name as material_sub', 't4.item_name as material', 't1.item_name as ref')
				// 					->leftJoin('tesco_item_master as t2', 't2.id','=','t1.parent_id')
				// 					->leftJoin('tesco_item_master as t3', 't3.id','=','t2.parent_id')
				// 					->leftJoin('tesco_item_master as t4', 't4.id','=','t3.parent_id')
				// 					->where('t1.id', $orders->template_id)
				// 					->first();
				
				// $material_name  = $template_level->material;
				// $material_sub   = $template_level->material_sub;
				// $material_ref   = $template_level->ref;

				// $history_id = DB::table('history')
				// ->insertGetId([
				// 	'buyer_id'             => $buyer_id,
				// 	'erp_order_details_id' => $erp_order_details_id,
				// 	'template'             => $template,
				// 	'season'               => $orders->season_code,
				// 	'country'              => $country,
				// 	'customer_id'          => $orders->customer_id,
				// 	'material'             => $material_name,
				// 	'mat_sub'              => $material_sub,
				// 	'reference'            => $material_ref,
				// 	'style'                => $style_no,
				// 	'po'                   => $customer_po_no,
				// 	'buyer_po'             => $orders->po_number,
				// 	'ocn'                  => $orders->ocn_number,
				// 	'created_at'           => $current_timestamp,
				// 	'updated_at'           => $current_timestamp,
				// ]);

				// DB::table('event_history')
				// ->insert([
				// 	'history_id'  => $history_id,
				// 	'action_id'   => $action_id,
				// 	'created_by'  => Auth::guard('web')->id(),
				// 	'created_at'  => $current_timestamp,
				// 	'updated_at'  => $current_timestamp,
				// ]);

				// remove erp_orders table row , if no FK relation exists in erp_order_details table
				DB::table('erp_orders')->select('erp_orders.id')->whereNotIn('erp_orders.id', function($rightjoin) {                                                  $rightjoin->select('erp_orders.id')
					     ->from('erp_orders')
						 ->rightjoin('erp_order_details', 'erp_orders.id', 'erp_order_details.erp_order_id')                                                 ->groupBy('erp_orders.id');
				})->delete();
			}
		}
	}
}