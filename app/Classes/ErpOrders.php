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
			
			// if ($orders->purchase_order_no == "" && $orders->po_file == "" && $orders->po_file=="") {
				
			// }
			// else{
				$erp_orders = Erp::where('buyer_id',$buyer_id)->where('customer_id',$orders->customer_id)->where('po_no',$po_no)->first();
			// }			

			if ($erp_orders) {
			
				// $erp_order_table_status = DB::table('erp_order_details')
				// 	->where('erp_order_details.erp_order_id', $erp_orders->id)
				// 	->pluck('status')->all();
				
				// if(count(array_unique($erp_order_table_status)) === 1)
				// {
				// 	DB::table('erp_orders')
				// 		->where('erp_orders.id', $erp_orders->id)
				// 		->update([
				// 			'status'  => $erp_order_table_status[0]
				// 		]);
				// 	$status = $erp_order_table_status[0];
				// }
				// else 
				// {
					Erp::where('id',$erp_orders->id)->update(['status'=>1, 'po_file' => $po_file]);
					$status = 1;
				//}
			    $this->insertOrderDetails($qnty_table, $order_details, $orders, $erp_orders, $buyer_id, $status, $erp_orders->id);
			}
			else
			{
				$erp_orders = Erp::create([
								'buyer_id'         => $buyer_id,
								'customer_id'      => $orders->customer_id,
								'po_no'            => $po_no,
								'po_file'          => $po_file,
								'status'		   => 1,
								'order_status_id'  => $order_status_id->id,
								'created_at'       => $this->created_at
							 ]);
				$erp_id = "ERPID-0".$erp_orders->id;
				Erp::where('id',$erp_orders->id)->update(['erp_id'=>$erp_id]);
				$status = 1;

				$this->insertOrderDetails($qnty_table, $order_details, $orders, $erp_orders, $buyer_id, $status, $erp_orders->id);
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
	function insertOrderDetails($qnty_table, $order_details, $orders, $erp_orders, $buyer_id, $status, $erp_orders_id)
	{
		if ($order_details)
		{
			foreach ($order_details as $key => $vals) {

				$style_no = ($orders->zone == "CE") ? $orders->ce_style_ref : $orders->uk_style_ref ;
				$custom_ref_name = DB::table('price_taxation')->where('customer_id',$orders->customer_id)->where('buyer_id',$buyer_id)->where('template_id',$orders->template_id)->value('custom_name');

				$dataExists = DB::table('erp_order_details')->where('order_qnty_table_name',$qnty_table)->where('order_qnty_table_id',$vals->id)->where('erp_order_id',$erp_orders_id)->first();

				if(!empty($dataExists)){
					//$erp_status_id = $dataExists->status;
					//DB::table('erp_order_details')->where('id',$dataExists->id)->delete();
				}
				else 
				{
					DB::table('erp_order_details')->where('order_qnty_table_name',$qnty_table)->where('order_qnty_table_id',$vals->id)->delete();
					ErpOrderDetails::create([
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
						'created_at'            => $this->created_at
					]);
				}

				// remove erp_orders table row , if no FK relation exists in erp_order_details table
				DB::table('erp_orders')->select('erp_orders.id')->whereNotIn('erp_orders.id', function($rightjoin) {                                                                                         $rightjoin->select('erp_orders.id')                                                                   ->from('erp_orders')->rightjoin('erp_order_details', 'erp_orders.id', 'erp_order_details.erp_order_id')                                                                                ->groupBy('erp_orders.id');                                                         })->delete();
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
            $orders     	= DB::table($table_name)->where($table_name.'.id',$row_id)
            					->join('orchestra_item_master',$table_name.'.template_id','=','orchestra_item_master.id')
                                ->select('*')->first();
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
			
			// if ($orders->purchase_order_no == "" && $orders->po_file == "" && $orders->po_file=="") {
				
			// }
			// else{
				$erp_orders = Erp::where('buyer_id',$buyer_id)->where('customer_id',$orders->customer_id)->where('po_no',$po_no)->first();
			// }			

			if ($erp_orders) {
			
				// $erp_order_table_status = DB::table('erp_order_details')
				// 	->where('erp_order_details.erp_order_id', $erp_orders->id)
				// 	->pluck('status')->all();
				
				// if(count(array_unique($erp_order_table_status)) === 1)
				// {
				// 	DB::table('erp_orders')
				// 		->where('erp_orders.id', $erp_orders->id)
				// 		->update([
				// 			'status'  => $erp_order_table_status[0]
				// 		]);
				// 	$status = $erp_order_table_status[0];
				// }
				// else 
				// {
					Erp::where('id',$erp_orders->id)->update(['status'=>1, 'po_file' => $po_file]);
					$status = 1;
				//}
			    $this->insertOrderDetails($qnty_table, $order_details, $orders, $erp_orders, $buyer_id, $status, $erp_orders->id);
			}
			else
			{
				$erp_orders = Erp::create([
								'buyer_id'         => $buyer_id,
								'customer_id'      => $orders->customer_id,
								'po_no'            => $po_no,
								'po_file'          => $po_file,
								'status'		   => 1,
								'order_status_id'  => $order_status_id->id,
								'created_at'       => $this->created_at
							 ]);
				$erp_id = "ERPID-0".$erp_orders->id;
				Erp::where('id',$erp_orders->id)->update(['erp_id'=>$erp_id]);
				$status = 1;

				$this->insertOrderDetails($qnty_table, $order_details, $orders, $erp_orders, $buyer_id, $status, $erp_orders->id);
			}
			$result = response()->json(['error'=> false,'message' =>'success'],200);
		}
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}
}