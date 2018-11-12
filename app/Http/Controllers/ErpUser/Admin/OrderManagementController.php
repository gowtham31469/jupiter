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
				\App\ErpOrderDetails::where('id', $request->id)->delete();
				$message = response()->json(['message' => "Order Item Changed to Hold Status"], 200);
				break;
			case 2:
				\App\ErpOrderDetails::where('id', $request->id)->restore();
				\App\ErpOrderDetails::where('id', $request->id)->update([
					'status'	=> 4
				]);
				$message = response()->json(['message' => "Order Item Cancelled successfully"], 200);
				break;
			case 3:
				\App\ErpOrderDetails::where('id', $request->id)->restore();
				$message = response()->json(['message' => "Order Item Restored successfully"], 200);
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
				$message = "Order Changed to Hold Status successfully";
				break;
			case "Cancel":
				\App\ErpOrders::where('id', $request->id)->restore();
				\App\ErpOrders::where('id', $request->id)->update([
					'status'	=> 4
				]);
				$message = "Order Cancelled successfully";
				break;
			case "Restore":
				\App\ErpOrders::where('id', $request->id)->restore();
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
