<?php

namespace App\Http\Controllers\ErpUser\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Session;
use DB;
use Auth;
use Carbon\Carbon;
use App\OrderManagementHistory;

class OrderManagementController extends Controller
{
	private $order_status_id;
	private $current_date;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		$this->middleware('erpuser');
		// get order cancelled status id
		$this->order_status_id  = DB::table('orders_status')->select('id')->where('status_name', 'ilike', 'Order Cancelled')->first();
		// get the current time
		$this->current_date = Carbon::now('Asia/Kolkata')->toDateTimeString();
	}


    /**
     * Show the form for editing the specified resource.
     *
     * @param  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {

		$details 	= \App\ErpOrders::withTrashed()->find($request->id)->orderDetails;
		foreach($details as $detail){
			$oDetails = \App\ErpOrderDetails::withTrashed()->find($detail->id);
			$detail->company = $oDetails->company->name;
			$detail->status	 = $oDetails->getStatus->status_name;
		}
		/*	Order Details	*/
		$order 				= \App\ErpOrders::withTrashed()->find($request->id);
		$order->customer	= $order->customer->company_name;
		$order->buyer	 	= $order->buyer->buyer_name;
		$order->status	 	= $order->getStatus->status_name;
		
		return View::make('erp.Partials.Admin Orders.details')
				->with(['details' => $details, 'order' => $order])
				->renderSections()['order-detail-modal'];      
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
		$erp_user = Auth::guard('erpuser')->id();
		
		$status="no-change";

        switch($request->val){
			case 1:
				$erp_order_details = \App\ErpOrderDetails::where('erp_order_details.id', $request->id)
				->join('erp_orders', 'erp_order_details.erp_order_id', 'erp_orders.id')
				->select('erp_order_details.*', 'erp_orders.customer_id', 'erp_orders.buyer_id')
				->first();

				$erp_order_id = $erp_order_details->erp_order_id;

				\App\ErpOrderDetails::where('erp_order_details.id', $request->id)->update([
					'order_hold_by'   => $erp_user,
					'order_hold_date' => $this->current_date
				]);
				\App\ErpOrderDetails::where('id', $request->id)->delete();
				
				$erp_orders_data = \App\ErpOrderDetails::where('erp_order_id', $erp_order_id)->count();
				
				if($erp_orders_data == 0) {
					\App\ErpOrders::where('id', $erp_order_id)->delete();
					$status="hold-change";
				}

				// Writing history data for hold order reports
				$action_id  = DB::table('actions')->where('action', 'ilike', 'Hold')->value('id');
				$history_id = \App\History::where('erp_order_details_id', $erp_order_details->id)->value('id');
				
				DB::table('event_history')
				->insert([
					'history_id'  => $history_id,
					'action_id'   => $action_id,
					'created_by'  => $erp_user,
					'created_at'  => $this->current_date,
					'updated_at'  => $this->current_date,
				]);

				$message = response()->json(['message' => "Order Item Changed to Hold Status",'status' => $status], 200);
				break;
			case 2:
				\App\ErpOrderDetails::where('id', $request->id)->restore();
				
				$erp_order_details = \App\ErpOrderDetails::where('erp_order_details.id', $request->id)
				->join('erp_orders', 'erp_order_details.erp_order_id', 'erp_orders.id')
				->select('erp_order_details.*', 'erp_orders.customer_id', 'erp_orders.buyer_id')
				->first();

				$erp_order_id = $erp_order_details->erp_order_id;
				
				// Writing history data for cancelled order reports
				$action_id  = DB::table('actions')->where('action', 'ilike', 'Cancelled')->value('id');
				/**
				 * Update order status in orders table
				 */
				$order_qty_table     = $erp_order_details->order_qnty_table_name;
				$order_qnty_table_id = $erp_order_details->order_qnty_table_id;
				$buyer_id            = $erp_order_details->buyer_id;
				$buyer               = DB::table('buyers')->select('buyer_name')->where('id', $buyer_id)->first();

				// make orders cancelled in artwork table
				if($buyer->buyer_name === 'Tesco')
				{
					$main_table  = str_replace("_quanty","",$order_qty_table);

					// update individual row quanity to 0 in artworks orders table.
					if(in_array($main_table, ['tesco_washcare_orders', 'tesco_swingticket_orders'])){
						DB::table($order_qty_table)->where('id',$order_qnty_table_id)->update(['quantity' => 0]);
					} else {
						DB::table($order_qty_table)->where('id',$order_qnty_table_id)->update(['count' => 0]);
					}

				} else {
					// update individual row quanity to 0 in artworks orders table.
					DB::table($order_qty_table)->where('id',$order_qnty_table_id)->update(['quantity' => 0]);

				}

				$history_id = \App\History::where('erp_order_details_id', $erp_order_details->id)->value('id');

				DB::table('event_history')
				->insert([
					'history_id'  => $history_id,
					'action_id'   => $action_id,
					'created_by'  => $erp_user,
					'created_at'  => $this->current_date,
					'updated_at'  => $this->current_date,
				]);

				$erp_orders_data = \App\ErpOrderDetails::where('erp_order_id', $erp_order_id)->count();
				
				if($erp_orders_data != 0)
				{
					\App\ErpOrders::where('id', $erp_order_id)->restore();
				}
				
				\App\ErpOrderDetails::where('id', $request->id)->update([
					'status'	=> 4,
					'order_status_id' => $this->order_status_id->id,
					'order_cancelled_by' => $erp_user
				]);
				
				$erp_cancel_count = \App\ErpOrderDetails::where('erp_order_id', $erp_order_id)->where('status', 4)->count();
				
				if($erp_cancel_count == $erp_orders_data)
				{
					\App\ErpOrders::where('id', $erp_order_id)->update([
						'status'	=> 4
					]);
					$status="delete-change";
				}
				
				$message = response()->json(['message' => "Order Item Cancelled successfully",'status' => $status], 200);
				break;
			case 3:
				\App\ErpOrderDetails::where('id', $request->id)->restore();
				
				$erp_order_details = \App\ErpOrderDetails::where('erp_order_details.id', $request->id)->join('erp_orders', 'erp_order_details.erp_order_id', 'erp_orders.id')->select('erp_order_details.*', 'erp_orders.customer_id', 'erp_orders.buyer_id')->first();
				$erp_order_id = $erp_order_details->erp_order_id;
				
				$erp_orders_data = \App\ErpOrderDetails::where('erp_order_id', $erp_order_id)->count();
				
				if($erp_orders_data != 0)
				{
					\App\ErpOrders::where('id', $erp_order_id)->restore();
					
					$status="restore-change";
				}
				
				$message = response()->json(['message' => "Order Item Restored successfully",'status' => $status], 200);
				break;
			default:
				$message = response()->json(['message' => "Something Went Wrong"], 500);
				break;
		}
		return $message;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
		$erp_user = Auth::guard('erpuser')->id();
		// Get Erp status table id for 'Cancelled' status.
		$erp_status_id = DB::table('erp_status')->where('status_name', 'ilike', 'Cancelled')->value('id');

		switch($request->status){
			case "Hold":
				
			    // Fetch all the active orders (ie, orders which are not cancelled and not in hold state)
				$erp_order_details = \App\ErpOrderDetails::where('erp_order_id', $request->id)
									 ->where('erp_order_details.deleted_at',  null)
									 ->where('erp_order_details.status', '<>', $erp_status_id)
									 ->join('erp_orders', 'erp_order_details.erp_order_id', 'erp_orders.id')
									 ->select('erp_order_details.*', 'erp_orders.customer_id', 'erp_orders.buyer_id')
									 ->get();
				
				\App\ErpOrders::where('id', $request->id)->delete();
				\App\ErpOrderDetails::where('erp_order_id', $request->id)->update([
					'order_hold_by'   => $erp_user,
					'order_hold_date' => $this->current_date
				]);
				\App\ErpOrderDetails::where('erp_order_id', $request->id)->where('erp_order_details.status', '<>', $erp_status_id)->delete();
				
				
				// Writing history data for hold order reports

				$action_id  = DB::table('actions')->where('action', 'ilike', 'Hold')->value('id');
				foreach($erp_order_details as $erp_data)
				{
					$history_id = \App\History::where('erp_order_details_id', $erp_data->id)->value('id');

					DB::table('event_history')
					->insert([
						'history_id'  => $history_id,
						'action_id'   => $action_id,
						'created_by'  => $erp_user,
						'created_at'  => $this->current_date,
						'updated_at'  => $this->current_date,
					]);
				}
				$message = "Order Changed to Hold Status successfully";
				break;
			case "Cancel":
				
				\App\ErpOrders::where('id', $request->id)->restore();
				\App\ErpOrderDetails::where('erp_order_id', $request->id)->restore();

				// Fetch all the active orders (ie, orders which are not cancelled)
				$erp_order_details = \App\ErpOrderDetails::where('erp_order_id', $request->id)
									 ->where('erp_order_details.status', '<>', $erp_status_id)
									 ->join('erp_orders', 'erp_order_details.erp_order_id', 'erp_orders.id')
									 ->select('erp_order_details.*', 'erp_orders.customer_id', 'erp_orders.buyer_id')
									 ->get();
									 
				\App\ErpOrders::where('id', $request->id)->update([
					'status'	=> 4
				]);
				
				// Writing history data for cancelled order reports
				
				$action_id  = DB::table('actions')->where('action', 'ilike', 'Cancelled')->value('id');
				/**
				 * Update order status in orders table
				 */
				foreach($erp_order_details as $erp_data)
				{
					$order_qty_table     = $erp_data->order_qnty_table_name;
					$order_qnty_table_id = $erp_data->order_qnty_table_id;
					$buyer               = DB::table('buyers')->select('buyer_name')->where('id', $erp_data->buyer_id)->value('buyer_name');

					// make orders cancelled in artwork table
					if($buyer === 'Orchestra') {

						// get the first letter of each word for a given string
						$result_str  =  str_replace("_"," ",str_replace("_orders_qty","",$order_qty_table));
						$qnty_id = '';
						foreach (explode(' ', $result_str) as $word)
						{
							$qnty_id .= strtolower($word[0]);
						}
						$main_table  = str_replace("_qty","",$order_qty_table);
						$order_qty_field = $qnty_id.'_id';
						$order_status_field = 'status';
					} else {
						$order_qty_field  =  str_replace("_orders_quanty","_order_id",str_replace(strtolower($buyer)."_","",$order_qty_table));
						$main_table  = str_replace("_quanty","",$order_qty_table);
						$order_status_field = 'order_status';
					}

					$history_id = \App\History::where('erp_order_details_id', $erp_data->id)->value('id');
	
					DB::table('event_history')
					->insert([
						'history_id'  => $history_id,
						'action_id'   => $action_id,
						'created_by'  => $erp_user,
						'created_at'  => $this->current_date,
						'updated_at'  => $this->current_date,
					]);

					$main_table_id = DB::table($order_qty_table)->where('id',$order_qnty_table_id)->value($order_qty_field);

					DB::table($main_table)->where('id',$main_table_id)->update([$order_status_field => "Order Cancelled"]);
					
					 // update order status as "Order Cancelled" in order management history table
					OrderManagementHistory::where([['order_table_name', $main_table], ['order_table_id', $main_table_id]])->update([
						'order_status' => 3,
					]);
				}

				\App\ErpOrderDetails::where('erp_order_id', $request->id)->where('erp_order_details.status', '<>', $erp_status_id)->update([
					'status'	=> 4,
					'order_status_id' => $this->order_status_id->id,
					'order_cancelled_by' => $erp_user
				]);
				$message = "Order Cancelled successfully";
				break;
			case "Restore":
				\App\ErpOrders::where('id', $request->id)->restore();
				\App\ErpOrderDetails::where('erp_order_id', $request->id)->restore();
				$message = "Order Restored successfully";
				break;
			default:
				$message = "Something Went Wrong";
				break;
		}
		Session::flash('message', $message); 
		return "true";
    }
}
