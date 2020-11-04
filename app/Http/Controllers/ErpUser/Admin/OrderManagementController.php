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
		

        switch($request->val){
			case 1:
				$erp_order_details = \App\ErpOrderDetails::where('erp_order_details.id', $request->id)->join('erp_orders', 'erp_order_details.erp_order_id', 'erp_orders.id')->select('erp_order_details.*', 'erp_orders.customer_id', 'erp_orders.buyer_id')->first();
				$erp_order_id = $erp_order_details->erp_order_id;

				\App\ErpOrderDetails::where('erp_order_details.id', $request->id)->update([
					'order_hold_by'   => $erp_user,
					'order_hold_date' => $this->current_date
				]);
				\App\ErpOrderDetails::where('id', $request->id)->delete();
				
				$erp_orders_data = \App\ErpOrderDetails::where('erp_order_id', $erp_order_id)->get();
				
				if(count($erp_orders_data) == 0)
				{
					\App\ErpOrders::where('id', $erp_order_id)->delete();
					$status="hold-change";
				}
				else
				{
					$status="no-change";
				}

				// Writing history data for hold order reports
				$action_id  = DB::table('actions')->where('action', 'ilike', 'Hold')->value('id');
				
				$buyer_id       = $erp_order_details->buyer_id;
				$buyer          = DB::table('buyers')->select('buyer_name')->where('id', '=', $buyer_id)->first();
				$template       = 'Available';
				$qty_row_id     = $erp_order_details->order_qnty_table_id;
				$qnty_table     = $erp_order_details->order_qnty_table_name;
				$customer_id    = $erp_order_details->customer_id;

				$customer       = DB::table('customers')->select('pi_required', 'po_required')->where('id', '=', $customer_id)->first();

				if($buyer->buyer_name === 'Tesco'){

					$main_table     = str_replace("_quanty","",$qnty_table);
					$qnty_id        = str_replace("_orders_quanty","_order_id",str_replace("tesco_","",$qnty_table));
					$order_details  = DB::table($qnty_table)
										->select($main_table.'.*')
										->where($qnty_table.'.id', $qty_row_id)
										->join($main_table, $main_table.'.id', $qnty_table.'.'.$qnty_id)
										->first();
					$season         = $order_details->season_code;
					$country        = $order_details->zone;
					$style_zone     = strtolower($country).'_style_ref';
					$template_id    = $order_details->template_id;
					$template_level = DB::table('tesco_item_master as t1')
										->select('t3.item_name as material_sub', 't4.item_name as material', 't1.item_name as ref')
										->leftJoin('tesco_item_master as t2', 't2.id','=','t1.parent_id')
										->leftJoin('tesco_item_master as t3', 't3.id','=','t2.parent_id')
										->leftJoin('tesco_item_master as t4', 't4.id','=','t3.parent_id')
										->where('t1.id', $template_id)
										->first();
					
					$material_name  = $template_level->material;
					$material_sub   = $template_level->material_sub;
					$material_ref   = $template_level->ref;

					if ($customer->pi_required == 1 && $customer->po_required== 0) {
						$customer_po  = $order_details->pi_id;
					}
					else if($customer->po_required== 1){
						$customer_po  = $order_details->purchase_order_no;
					}

					$buyer_po       = $order_details->po_number;
					$style          = $order_details->$style_zone;
					$ocn_number     = $order_details->ocn_number;
					

				} else if($buyer->buyer_name === 'Orchestra'){

					$main_table     = str_replace("_qty","",$qnty_table);
					$result_str     = str_replace("_"," ",str_replace("_orders","",$main_table));
					$qnty_id        = '';
					foreach (explode(' ', $result_str) as $word)
					{
					   $qnty_id .= strtolower($word[0]);
					}
					
					$order_details  = DB::table($qnty_table)->select($main_table.'.*')->where($qnty_table.'.id', $qty_row_id)->join($main_table, $main_table.'.id', $qnty_table.'.'.$qnty_id.'_id')
									->first();
					
					// template front and back id storing in orchestra_hang_tag_orders table
					$template_id = $main_table === 'orchestra_hang_tag_orders'?$order_details->template_front_id:$order_details->template_id;

					$template_data = DB::table('orchestra_item_master')
									->where('orchestra_item_master.id', $template_id)
									->select('orchestra_item_master.material_id', 'orchestra_item_master.item_name', 'orchestra_item_master.sub_material_id')->first();
					$material_name  = DB::table('orchestra_item_master')->where('id', '=', $template_data->material_id)->value('item_name');
					$material_sub  = DB::table('orchestra_item_master')->where('id', '=', $template_data->sub_material_id)->value('item_name');

					$material_ref   = $template_data->item_name;

					if ($customer->pi_required == 1 && $customer->po_required== 0) {
						$customer_po  = $order_details->pi_id;
					}
					else if($customer->po_required== 1){
						$customer_po  = $order_details->po_number;
					}

					$buyer_po       = $order_details->po;
					$style          = $order_details->uvc;
					$ocn_number     = null;
					$season         = $order_details->season_code;
					$country        = null;

				} else {

					$template       = 'Not Available';

				}


				$history_id = \App\History::updateOrCreate(['erp_order_details_id' => $erp_order_details->id], [
					'buyer_id'             => $buyer_id,
					'template'             => $template,
					'season'               => $season,
					'country'              => $country,
					'customer_id'          => $customer_id,
					'material'             => $material_name,
					'mat_sub'              => $material_sub,
					'reference'            => $material_ref,
					'style'                => $style,
					'po'                   => $customer_po,
					'buyer_po'             => $buyer_po,
					'ocn'                  => $ocn_number,
					'created_at'           => $this->current_date,
					'updated_at'           => $this->current_date,
				]);

				DB::table('event_history')
				->insert([
					'history_id'  => $history_id->id,
					'action_id'   => $action_id,
					'created_by'  => $erp_user,
					'created_at'  => $this->current_date,
					'updated_at'  => $this->current_date,
				]);

				$message = response()->json(['message' => "Order Item Changed to Hold Status",'status' => $status], 200);
				break;
			case 2:
				\App\ErpOrderDetails::where('id', $request->id)->restore();
				
				$erp_order_details = \App\ErpOrderDetails::where('erp_order_details.id', $request->id)->join('erp_orders', 'erp_order_details.erp_order_id', 'erp_orders.id')->select('erp_order_details.*', 'erp_orders.customer_id', 'erp_orders.buyer_id')->first();
				$erp_order_id = $erp_order_details->erp_order_id;
				
				// Writing history data for cancelled order reports
				$action_id  = DB::table('actions')->where('action', 'ilike', 'Cancelled')->value('id');
				/**
				 * Update order status in orders table
				 */
				$order_qty_table     = $erp_order_details->order_qnty_table_name;
				$order_qnty_table_id = $erp_order_details->order_qnty_table_id;
				$buyer_id            = $erp_order_details->buyer_id;
				$buyer               = DB::table('buyers')->select('buyer_name')->where('id', '=', $buyer_id)->first();
				$template            = 'Available';
				$customer_id         = $erp_order_details->customer_id;
				$customer            = DB::table('customers')->select('pi_required', 'po_required')->where('id', '=', $customer_id)->first();

				// make orders cancelled in artwork table
				if($buyer->buyer_name === 'Tesco')
				{
					$qnty_id  =  str_replace("_orders_quanty","_order_id",str_replace("tesco_","",$order_qty_table));
					$main_table  = str_replace("_quanty","",$order_qty_table);
					$order_details  = DB::table($order_qty_table)
										->select($main_table.'.*')
										->where($order_qty_table.'.id', $order_qnty_table_id)
										->join($main_table, $main_table.'.id', $order_qty_table.'.'.$qnty_id)
										->first();
					$season         = $order_details->season_code;
					$country        = $order_details->zone;
					$style_zone     = strtolower($country).'_style_ref';
					$template_id    = $order_details->template_id;
					$template_level = DB::table('tesco_item_master as t1')
										->select('t3.item_name as material_sub', 't4.item_name as material', 't1.item_name as ref')
										->leftJoin('tesco_item_master as t2', 't2.id','=','t1.parent_id')
										->leftJoin('tesco_item_master as t3', 't3.id','=','t2.parent_id')
										->leftJoin('tesco_item_master as t4', 't4.id','=','t3.parent_id')
										->where('t1.id', $template_id)
										->first();
					
					$material_name  = $template_level->material;
					$material_sub   = $template_level->material_sub;
					$material_ref   = $template_level->ref;

					if ($customer->pi_required == 1 && $customer->po_required== 0) {
						$customer_po  = $order_details->pi_id;
					}
					else if($customer->po_required== 1){
						$customer_po  = $order_details->purchase_order_no;
					}

					$buyer_po       = $order_details->po_number;
					$style          = $order_details->$style_zone;
					$ocn_number     = $order_details->ocn_number;

					// update individual row quanity to 0 in artworks orders table.
					DB::table($order_qty_table)->where('id',$order_qnty_table_id)->update(['count' => 0]);

				} else if($buyer->buyer_name === 'Orchestra') {

					// get the first letter of each word for a given string
					$result_str  =  str_replace("_"," ",str_replace("_orders_qty","",$order_qty_table));
					$qnty_id = '';
					foreach (explode(' ', $result_str) as $word)
					{
						$qnty_id .= strtolower($word[0]);
					}
					$main_table  = str_replace("_qty","",$order_qty_table);
					
					$order_details  = DB::table($order_qty_table)->select($main_table.'.*')->where($order_qty_table.'.id', $order_qnty_table_id)->join($main_table, $main_table.'.id', $order_qty_table.'.'.$qnty_id.'_id')
									->first();
					
					// template front and back id storing in orchestra_hang_tag_orders table
					$template_id = $main_table === 'orchestra_hang_tag_orders'?$order_details->template_front_id:$order_details->template_id;

					$template_data = DB::table('orchestra_item_master')
									->where('orchestra_item_master.id', $template_id)
									->select('orchestra_item_master.material_id', 'orchestra_item_master.item_name', 'orchestra_item_master.sub_material_id')->first();
					$material_name  = DB::table('orchestra_item_master')->where('id', '=', $template_data->material_id)->value('item_name');
					$material_sub  = DB::table('orchestra_item_master')->where('id', '=', $template_data->sub_material_id)->value('item_name');

					$material_ref   = $template_data->item_name;
					
					if ($customer->pi_required == 1 && $customer->po_required== 0) {
						$customer_po  = $order_details->pi_id;
					}
					else if($customer->po_required== 1){
						$customer_po  = $order_details->po_number;
					}

					$buyer_po       = $order_details->po;
					$style          = $order_details->uvc;
					$ocn_number     = null;
					$season         = $order_details->season_code;
					$country        = null;

					// update individual row quanity to 0 in artworks orders table.
					DB::table($order_qty_table)->where('id',$order_qnty_table_id)->update(['quantity' => 0]);

				} else {

					$template       = 'Not Available';

				}

				$history_id = \App\History::updateOrCreate(['erp_order_details_id' => $erp_order_details->id], [
					'buyer_id'             => $buyer_id,
					'template'             => $template,
					'season'               => $season,
					'country'              => $country,
					'customer_id'          => $customer_id,
					'material'             => $material_name,
					'mat_sub'              => $material_sub,
					'reference'            => $material_ref,
					'style'                => $style,
					'po'                   => $customer_po,
					'buyer_po'             => $buyer_po,
					'ocn'                  => $ocn_number,
					'created_at'           => $this->current_date,
					'updated_at'           => $this->current_date,
				]);

				DB::table('event_history')
				->insert([
					'history_id'  => $history_id->id,
					'action_id'   => $action_id,
					'created_by'  => $erp_user,
					'created_at'  => $this->current_date,
					'updated_at'  => $this->current_date,
				]);

				$erp_orders_data = \App\ErpOrderDetails::where('erp_order_id', $erp_order_id)->get();
				
				if(count($erp_orders_data) != 0)
				{
					\App\ErpOrders::where('id', $erp_order_id)->restore();
				}
				
				\App\ErpOrderDetails::where('id', $request->id)->update([
					'status'	=> 4,
					'order_status_id' => $this->order_status_id->id,
					'order_cancelled_by' => $erp_user
				]);
				
				$erp_cancel_count = \App\ErpOrderDetails::where('erp_order_id', $erp_order_id)->where('status', 4)->get();
				
				if(count($erp_cancel_count) == count($erp_orders_data))
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
				
				$erp_order_details = \App\ErpOrderDetails::where('erp_order_details.id', $request->id)->join('erp_orders', 'erp_order_details.erp_order_id', 'erp_orders.id')->select('erp_order_details.*', 'erp_orders.customer_id', 'erp_orders.buyer_id')->first();
				$erp_order_id = $erp_order_details->erp_order_id;
				
				$erp_orders_data = \App\ErpOrderDetails::where('erp_order_id', $erp_order_id)->get();
				
				if(count($erp_orders_data) != 0)
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
					$buyer_id       = $erp_data->buyer_id;
					$buyer          = DB::table('buyers')->select('buyer_name')->where('id', '=', $buyer_id)->first();
					$template       = 'Available';
					$qty_row_id     = $erp_data->order_qnty_table_id;
					$qnty_table     = $erp_data->order_qnty_table_name;
					$customer_id    = $erp_data->customer_id;

					$customer       = DB::table('customers')->select('pi_required', 'po_required')->where('id', '=', $customer_id)->first();

					if($buyer->buyer_name === 'Tesco'){

						$main_table     = str_replace("_quanty","",$qnty_table);
						$qnty_id        = str_replace("_orders_quanty","_order_id",str_replace("tesco_","",$qnty_table));
						$order_details  = DB::table($qnty_table)
											->select($main_table.'.*')
											->where($qnty_table.'.id', $qty_row_id)
											->join($main_table, $main_table.'.id', $qnty_table.'.'.$qnty_id)
											->first();
						$season         = $order_details->season_code;
						$country        = $order_details->zone;
						$style_zone     = strtolower($country).'_style_ref';
						$template_id    = $order_details->template_id;
						$template_level = DB::table('tesco_item_master as t1')
											->select('t3.item_name as material_sub', 't4.item_name as material', 't1.item_name as ref')
											->leftJoin('tesco_item_master as t2', 't2.id','=','t1.parent_id')
											->leftJoin('tesco_item_master as t3', 't3.id','=','t2.parent_id')
											->leftJoin('tesco_item_master as t4', 't4.id','=','t3.parent_id')
											->where('t1.id', $template_id)
											->first();
						
						$material_name  = $template_level->material;
						$material_sub   = $template_level->material_sub;
						$material_ref   = $template_level->ref;
						
						if ($customer->pi_required == 1 && $customer->po_required== 0) {
							$customer_po  = $order_details->pi_id;
						}
						else if($customer->po_required== 1){
							$customer_po  = $order_details->purchase_order_no;
						}

						$buyer_po       = $order_details->po_number;
						$style          = $order_details->$style_zone;
						$ocn_number     = $order_details->ocn_number;
						

					} else if($buyer->buyer_name === 'Orchestra'){

						$main_table     = str_replace("_qty","",$qnty_table);
						$result_str     = str_replace("_"," ",str_replace("_orders","",$main_table));
						$qnty_id        = '';
						foreach (explode(' ', $result_str) as $word)
						{
						$qnty_id .= strtolower($word[0]);
						}
						
						$order_details  = DB::table($qnty_table)->select($main_table.'.*')->where($qnty_table.'.id', $qty_row_id)->join($main_table, $main_table.'.id', $qnty_table.'.'.$qnty_id.'_id')
										->first();
						
						// template front and back id storing in orchestra_hang_tag_orders table
						$template_id = $main_table === 'orchestra_hang_tag_orders'?$order_details->template_front_id:$order_details->template_id;

						$template_data = DB::table('orchestra_item_master')
									->where('orchestra_item_master.id', $template_id)
									->select('orchestra_item_master.material_id', 'orchestra_item_master.item_name', 'orchestra_item_master.sub_material_id')->first();
						$material_name  = DB::table('orchestra_item_master')->where('id', '=', $template_data->material_id)->value('item_name');
						$material_sub  = DB::table('orchestra_item_master')->where('id', '=', $template_data->sub_material_id)->value('item_name');

						$material_ref   = $template_data->item_name;
						
						if ($customer->pi_required == 1 && $customer->po_required== 0) {
							$customer_po  = $order_details->pi_id;
						}
						else if($customer->po_required== 1){
							$customer_po  = $order_details->po_number;
						}

						$buyer_po       = $order_details->po;
						$style          = $order_details->uvc;
						$ocn_number     = null;
						$season         = $order_details->season_code;
						$country        = null;

					} else {

						$template       = 'Not Available';

					}


					$history_id = \App\History::updateOrCreate(['erp_order_details_id' => $erp_data->id], [
						'buyer_id'             => $buyer_id,
						'template'             => $template,
						'season'               => $season,
						'country'              => $country,
						'customer_id'          => $customer_id,
						'material'             => $material_name,
						'mat_sub'              => $material_sub,
						'reference'            => $material_ref,
						'style'                => $style,
						'po'                   => $customer_po,
						'buyer_po'             => $buyer_po,
						'ocn'                  => $ocn_number,
						'created_at'           => $this->current_date,
						'updated_at'           => $this->current_date,
					]);

					DB::table('event_history')
					->insert([
						'history_id'  => $history_id->id,
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
					$buyer_id            = $erp_data->buyer_id;
					$buyer               = DB::table('buyers')->select('buyer_name')->where('id', '=', $buyer_id)->first();
					$template            = 'Available';
					$customer_id         = $erp_data->customer_id;

					$customer 			 = DB::table('customers')->select('pi_required', 'po_required')->where('id', '=', $customer_id)->first();

					// make orders cancelled in artwork table
					if($buyer->buyer_name === 'Tesco')
					{
						$qnty_id  =  str_replace("_orders_quanty","_order_id",str_replace("tesco_","",$order_qty_table));
						$main_table  = str_replace("_quanty","",$order_qty_table);
						$order_details  = DB::table($order_qty_table)
											->select($main_table.'.*')
											->where($order_qty_table.'.id', $order_qnty_table_id)
											->join($main_table, $main_table.'.id', $order_qty_table.'.'.$qnty_id)
											->first();
						$order_qty_field = $qnty_id;
						$order_status_field = 'order_status';
						$season         = $order_details->season_code;
						$country        = $order_details->zone;
						$style_zone     = strtolower($country).'_style_ref';
						$template_id    = $order_details->template_id;
						$template_level = DB::table('tesco_item_master as t1')
											->select('t3.item_name as material_sub', 't4.item_name as material', 't1.item_name as ref')
											->leftJoin('tesco_item_master as t2', 't2.id','=','t1.parent_id')
											->leftJoin('tesco_item_master as t3', 't3.id','=','t2.parent_id')
											->leftJoin('tesco_item_master as t4', 't4.id','=','t3.parent_id')
											->where('t1.id', $template_id)
											->first();
						
						$material_name  = $template_level->material;
						$material_sub   = $template_level->material_sub;
						$material_ref   = $template_level->ref;
						
						if ($customer->pi_required == 1 && $customer->po_required== 0) {
							$customer_po  = $order_details->pi_id;
						}
						else if($customer->po_required== 1){
							$customer_po  = $order_details->purchase_order_no;
						}

						$buyer_po       = $order_details->po_number;
						$style          = $order_details->$style_zone;
						$ocn_number     = $order_details->ocn_number;

				    } else if($buyer->buyer_name === 'Orchestra') {

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
						
						$order_details  = DB::table($order_qty_table)->select($main_table.'.*')->where($order_qty_table.'.id', $order_qnty_table_id)->join($main_table, $main_table.'.id', $order_qty_table.'.'.$qnty_id.'_id')
										->first();
						
						// template front and back id storing in orchestra_hang_tag_orders table
						$template_id = $main_table === 'orchestra_hang_tag_orders'?$order_details->template_front_id:$order_details->template_id;

						$template_data = DB::table('orchestra_item_master')
									->where('orchestra_item_master.id', $template_id)
									->select('orchestra_item_master.material_id', 'orchestra_item_master.item_name', 'orchestra_item_master.sub_material_id')->first();
						$material_name  = DB::table('orchestra_item_master')->where('id', '=', $template_data->material_id)->value('item_name');
						$material_sub  = DB::table('orchestra_item_master')->where('id', '=', $template_data->sub_material_id)->value('item_name');
						
						$material_ref   = $template_data->item_name;
						
						if ($customer->pi_required == 1 && $customer->po_required== 0) {
							$customer_po  = $order_details->pi_id;
						}
						else if($customer->po_required== 1){
							$customer_po  = $order_details->po_number;
						}

						$buyer_po       = $order_details->po;
						$style          = $order_details->uvc;
						$ocn_number     = null;
						$season         = $order_details->season_code;
						$country        = null;

					} else {

						$template       = 'Not Available';

					}

					$history_id = \App\History::updateOrCreate(['erp_order_details_id' => $erp_data->id], [
						'buyer_id'             => $buyer_id,
						'template'             => $template,
						'season'               => $season,
						'country'              => $country,
						'customer_id'          => $customer_id,
						'material'             => $material_name,
						'mat_sub'              => $material_sub,
						'reference'            => $material_ref,
						'style'                => $style,
						'po'                   => $customer_po,
						'buyer_po'             => $buyer_po,
						'ocn'                  => $ocn_number,
						'created_at'           => $this->current_date,
						'updated_at'           => $this->current_date,
					]);
	
					DB::table('event_history')
					->insert([
						'history_id'  => $history_id->id,
						'action_id'   => $action_id,
						'created_by'  => $erp_user,
						'created_at'  => $this->current_date,
						'updated_at'  => $this->current_date,
					]);

					$main_table_id = DB::table($order_qty_table)->where('id',$order_qnty_table_id)->value($order_qty_field);

					DB::table($main_table)->where('id',$main_table_id)->update([$order_status_field => "Order Cancelled"]);
					
					 // update order status as "Order Cancelled" in order management history table
					OrderManagementHistory::where([['order_table_name', $main_table], ['order_table_id', $main_table_id]])->update([
						'order_status' => "Order Cancelled",
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
