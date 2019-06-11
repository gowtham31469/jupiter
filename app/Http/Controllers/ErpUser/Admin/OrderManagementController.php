<?php

namespace App\Http\Controllers\ErpUser\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Session;

class OrderManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        
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
				$erp_order_id = $erp_order{0}->erp_order_id;
				
				$erp_order_details = \App\ErpOrderDetails::where('erp_order_id', $erp_order_id)->get();
				
				if(count($erp_order_details) != 0)
				{
					\App\ErpOrders::where('id', $erp_order_id)->restore();
				}
				
				\App\ErpOrderDetails::where('id', $request->id)->update([
					'status'	=> 4
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
				\App\ErpOrderDetails::where('erp_order_id', $request->id)->restore();
				\App\ErpOrderDetails::where('erp_order_id', $request->id)->update([
					'status'	=> 4
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
