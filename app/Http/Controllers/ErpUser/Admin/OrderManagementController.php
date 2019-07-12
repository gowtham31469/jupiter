<?php

namespace App\Http\Controllers\ErpUser\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Session;
use DB;

class OrderManagementController extends Controller
{
	private $order_status_id;
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
        switch($request->val){
			case 1:
				
				$erp_order = \App\ErpOrderDetails::where('id', $request->id)->get();
				$erp_order_id = $erp_order{0}->erp_order_id;
				
				\App\ErpOrderDetails::where('id', $request->id)->delete();
				
				$erp_order_details = \App\ErpOrderDetails::where('erp_order_id', $erp_order_id)->get();
				
				if(count($erp_order_details) == 0)
				{
					\App\ErpOrders::where('id', $erp_order_id)->delete();
					$status="hold-change";
				}
				else
				{
					$status="no-change";
				}
				$message = response()->json(['message' => "Order Item Changed to Hold Status",'status' => $status], 200);
				break;
			case 2:
				\App\ErpOrderDetails::where('id', $request->id)->restore();
				
				$erp_order = \App\ErpOrderDetails::where('id', $request->id)->get();
				$order_qty_table = $erp_order{0}->order_qnty_table_name;
				$order_qnty_table_id = $erp_order{0}->order_qnty_table_id;
				$buyer_name = \App\ErpOrders::where('erp_orders.id', $erp_order{0}->erp_order_id)->select('buyer_name')->join('buyers', 'buyers.id', 'erp_orders.buyer_id')->first();

				// update individual row quanity to 0 in artworks orders table.
				if($buyer_name->buyer_name === 'Tesco')
				{
				    DB::table($order_qty_table)->where('id',$order_qnty_table_id)->update(['count' => 0]);
				} else  
				{
				    DB::table($order_qty_table)->where('id',$order_qnty_table_id)->update(['quantity' => 0]);
				}

				$erp_order_id = $erp_order{0}->erp_order_id;
				
				$erp_order_details = \App\ErpOrderDetails::where('erp_order_id', $erp_order_id)->get();
				
				if(count($erp_order_details) != 0)
				{
					\App\ErpOrders::where('id', $erp_order_id)->restore();
				}
				
				\App\ErpOrderDetails::where('id', $request->id)->update([
					'status'	=> 4,
					'order_status_id' => $this->order_status_id->id
				]);
				
				$erp_cancel_count = \App\ErpOrderDetails::where('erp_order_id', $erp_order_id)->where('status', 4)->get();
				
				$erp_count = \App\ErpOrderDetails::where('erp_order_id', $erp_order_id)->get();
				
				if(count($erp_cancel_count) == count($erp_count))
				{
					\App\ErpOrders::where('id', $erp_order_id)->update([
						'status'	=> 4
					]);
					$status="delete-change";
				}
				else
				{
					$status="no-change";
				}
				
				$message = response()->json(['message' => "Order Item Cancelled successfully",'status' => $status], 200);
				break;
			case 3:
				\App\ErpOrderDetails::where('id', $request->id)->restore();
				
				$erp_order = \App\ErpOrderDetails::where('id', $request->id)->get();
				$erp_order_id = $erp_order{0}->erp_order_id;
				$erp_order_details = \App\ErpOrderDetails::where('erp_order_id', $erp_order_id)->get();
				
				if(count($erp_order_details) != 0)
				{
					\App\ErpOrders::where('id', $erp_order_id)->restore();
					
					$status="restore-change";
				}
				else
				{
					$status="no-change";
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
		switch($request->status){
			case "Hold":
				\App\ErpOrders::where('id', $request->id)->delete();
				\App\ErpOrderDetails::where('erp_order_id', $request->id)->delete();
				$message = "Order Changed to Hold Status successfully";
				break;
			case "Cancel":
				\App\ErpOrders::where('id', $request->id)->restore();
				\App\ErpOrders::where('id', $request->id)->update([
					'status'	=> 4
				]);

				/**
				 * Update order status in orders table
				 */
				$erp_order_details = \App\ErpOrderDetails::where('erp_order_id', $request->id)->get();
				$buyer_name = \App\ErpOrders::where('erp_orders.id', $erp_order_details{0}->erp_order_id)->select('buyer_name')->join('buyers', 'buyers.id', 'erp_orders.buyer_id')->first();

				foreach($erp_order_details as $erp_data)
				{
					$order_qty_table = $erp_data->order_qnty_table_name;
					$order_qnty_table_id = $erp_data->order_qnty_table_id;

					// make orders cancelled in artwork table
					if($buyer_name->buyer_name === 'Tesco')
					{
						$qnty_id  =  str_replace("_orders_quanty","_order_id",str_replace("tesco_","",$order_qty_table));
						$main_table  = str_replace("_quanty","",$order_qty_table);
					} 
					else  
					{
						// get the first letter of each word for a given string
						$result_str  =  str_replace("_"," ",str_replace("_orders_qty","",$order_qty_table));
						$qnty_id = '';
						foreach (explode(' ', $result_str) as $word)
						{
							$qnty_id .= strtolower($word[0]);
						}
						$main_table  = str_replace("_qty","",$order_qty_table);
					}

					$main_table_id = DB::table($order_qty_table)->where('id',$order_qnty_table_id)->value($qnty_id);

					DB::table($main_table)->where('id',$main_table_id)->update(['order_status' => "Order Cancelled"]);
				}

				\App\ErpOrderDetails::where('erp_order_id', $request->id)->restore();
				\App\ErpOrderDetails::where('erp_order_id', $request->id)->update([
					'status'	=> 4,
					'order_status_id' => $this->order_status_id->id
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
