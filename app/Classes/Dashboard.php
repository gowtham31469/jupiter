<?php
namespace App\Classes;
use DB;
/*
 * Create an EAN13 Barcode image.
 */

class Dashboard
{
   public function pendingProduction()
   {
	   		$pendingproduction['tcl1'] = DB::table('tesco_tcl1_orders')
				->join('customers','customers.id','=','tesco_tcl1_orders.tesco_supplier_id')
											->whereIn('order_status',['Order Approved','Order Placed'])
											->where('pen_pro_flag',0)
											->where('is_order_count_empty',0)
											->select('tesco_tcl1_orders.*','customers.company_name')
											->get();
			$pendingproduction['tcl2'] = DB::table('tesco_tcl2_orders')
				->join('customers','customers.id','=','tesco_tcl2_orders.tesco_supplier_id')
											->whereIn('order_status',['Order Approved','Order Placed'])
											->where('pen_pro_flag',0)
											->where('is_order_count_empty',0)
											->select('tesco_tcl2_orders.*','customers.company_name')
											->get();
			$pendingproduction['tcl4'] = DB::table('tesco_tcl4_orders')
				->join('customers','customers.id','=','tesco_tcl4_orders.tesco_supplier_id')
											->whereIn('order_status',['Order Approved','Order Placed'])
											->where('pen_pro_flag',0)
											->where('is_order_count_empty',0)
											->select('tesco_tcl4_orders.*','customers.company_name')
											->get();
			$pendingproduction['tsl1'] = DB::table('tesco_tsl1_orders')
				->join('customers','customers.id','=','tesco_tsl1_orders.tesco_supplier_id')
											->whereIn('order_status',['Order Approved','Order Placed'])
											->where('pen_pro_flag',0)
											->where('is_order_count_empty',0)
											->select('tesco_tsl1_orders.*','customers.company_name')
											->get();
			$pendingproduction['tsl3'] = DB::table('tesco_tsl3_orders')
				->join('customers','customers.id','=','tesco_tsl3_orders.tesco_supplier_id')
											->whereIn('order_status',['Order Approved','Order Placed'])
											->where('pen_pro_flag',0)
											->where('is_order_count_empty',0)
											->select('tesco_tsl3_orders.*','customers.company_name')
											->get();
			$pendingproduction['thl1'] = DB::table('tesco_thl1_orders')
				->join('customers','customers.id','=','tesco_thl1_orders.tesco_supplier_id')
											->whereIn('order_status',['Order Approved','Order Placed'])
											->where('pen_pro_flag',0)
											->where('is_order_count_empty',0)
											->select('tesco_thl1_orders.*','customers.company_name')
											->get();
			$pendingproduction['thl3'] = DB::table('tesco_thl3_orders')
				->join('customers','customers.id','=','tesco_thl3_orders.tesco_supplier_id')
											->whereIn('order_status',['Order Approved','Order Placed'])
											->where('pen_pro_flag',0)
											->where('is_order_count_empty',0)
											->select('tesco_thl3_orders.*','customers.company_name')
											->get();
      		return $pendingproduction;
   }
	
	public function newOrders()
	{
			$new_orders['tcl1'] = DB::table('tesco_tcl1_orders')
				->join('customers','customers.id','=','tesco_tcl1_orders.tesco_supplier_id')
				->join('tesco_tcl1_orders_quanty','tesco_tcl1_orders_quanty.tcl1_order_id','=','tesco_tcl1_orders.id')
									->whereRaw('tesco_tcl1_orders.created_at > current_date - 1')
									->where('new_ord_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_tcl1_orders.*','customers.company_name',DB::raw('sum(count) as sum'))
									->groupBy('tesco_tcl1_orders.id','customers.company_name')
									->get();
			$new_orders['tcl2'] = DB::table('tesco_tcl2_orders')
				->join('customers','customers.id','=','tesco_tcl2_orders.tesco_supplier_id')
				->join('tesco_tcl2_orders_quanty','tesco_tcl2_orders_quanty.tcl2_order_id','=','tesco_tcl2_orders.id')
									->whereRaw('tesco_tcl2_orders.created_at > current_date - 1')
									->where('new_ord_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_tcl2_orders.*','customers.company_name',DB::raw('sum(count) as sum'))
									->groupBy('tesco_tcl2_orders.id','customers.company_name')
									->get();
			$new_orders['tcl4'] = DB::table('tesco_tcl4_orders')
				->join('customers','customers.id','=','tesco_tcl4_orders.tesco_supplier_id')
				->join('tesco_tcl4_orders_quanty','tesco_tcl4_orders_quanty.tcl4_order_id','=','tesco_tcl4_orders.id')
									->whereRaw('tesco_tcl4_orders.created_at > current_date - 1')
									->where('new_ord_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_tcl4_orders.*','customers.company_name',DB::raw('sum(count) as sum'))
									->groupBy('tesco_tcl4_orders.id','customers.company_name')
									->get();
			$new_orders['thl1'] = DB::table('tesco_thl1_orders')
				->join('customers','customers.id','=','tesco_thl1_orders.tesco_supplier_id')
				->join('tesco_thl1_orders_quanty','tesco_thl1_orders_quanty.thl1_order_id','=','tesco_thl1_orders.id')
									->whereRaw('tesco_thl1_orders.created_at > current_date - 1')
									->where('tesco_thl1_orders.new_ord_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_thl1_orders.*','customers.company_name',DB::raw('sum(count) as sum'))
									->groupBy('tesco_thl1_orders.id','customers.company_name')
									->get();
			$new_orders['thl3'] = DB::table('tesco_thl3_orders')
				->join('customers','customers.id','=','tesco_thl3_orders.tesco_supplier_id')
				->join('tesco_thl3_orders_quanty','tesco_thl3_orders_quanty.thl3_order_id','=','tesco_thl3_orders.id')
									->whereRaw('tesco_thl3_orders.created_at > current_date - 1')
									->where('new_ord_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_thl3_orders.*','customers.company_name',DB::raw('sum(count) as sum'))
									->groupBy('tesco_thl3_orders.id','customers.company_name')
									->get();
			$new_orders['tsl1'] = DB::table('tesco_tsl1_orders')
				->join('customers','customers.id','=','tesco_tsl1_orders.tesco_supplier_id')
				->join('tesco_tsl1_orders_quanty','tesco_tsl1_orders_quanty.tsl1_order_id','=','tesco_tsl1_orders.id')
									->whereRaw('tesco_tsl1_orders.created_at > current_date - 1')
									->where('new_ord_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_tsl1_orders.*','customers.company_name',DB::raw('sum(count) as sum'))
									->groupBy('tesco_tsl1_orders.id','customers.company_name')
									->get();
			$new_orders['tsl3'] = DB::table('tesco_tsl3_orders')
				->join('customers','customers.id','=','tesco_tsl3_orders.tesco_supplier_id')
				->join('tesco_tsl3_orders_quanty','tesco_tsl3_orders_quanty.tsl3_order_id','=','tesco_tsl3_orders.id')
									->whereRaw('tesco_tsl3_orders.created_at > current_date - 1')
									->where('new_ord_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_tsl3_orders.*','customers.company_name',DB::raw('sum(count) as sum'))
									->groupBy('tesco_tsl3_orders.id','customers.company_name')
									->get();
			return $new_orders;
	}
	
	public function approvedOrders()
	{
			$approved_orders['tcl1'] = DB::table('tesco_tcl1_orders')
				->join('customers','customers.id','=','tesco_tcl1_orders.tesco_supplier_id')
									->where('order_status','Order Approved')
									->where('app_ord_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_tcl1_orders.*','customers.company_name')
									->get();
			$approved_orders['tcl2'] = DB::table('tesco_tcl2_orders')
				->join('customers','customers.id','=','tesco_tcl2_orders.tesco_supplier_id')
									->where('order_status','Order Approved')
									->where('app_ord_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_tcl2_orders.*','customers.company_name')
									->get();
			$approved_orders['tcl4'] = DB::table('tesco_tcl4_orders')
				->join('customers','customers.id','=','tesco_tcl4_orders.tesco_supplier_id')
									->where('order_status','Order Approved')
									->where('app_ord_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_tcl4_orders.*','customers.company_name')
									->get();
			$approved_orders['thl1'] = DB::table('tesco_thl1_orders')
				->join('customers','customers.id','=','tesco_thl1_orders.tesco_supplier_id')
									->where('order_status','Order Approved')
									->where('app_ord_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_thl1_orders.*','customers.company_name')
									->get();
			$approved_orders['thl3'] = DB::table('tesco_thl3_orders')
				->join('customers','customers.id','=','tesco_thl3_orders.tesco_supplier_id')
									->where('order_status','Order Approved')
									->where('app_ord_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_thl3_orders.*','customers.company_name')
									->get();
			$approved_orders['tsl1'] = DB::table('tesco_tsl1_orders')
				->join('customers','customers.id','=','tesco_tsl1_orders.tesco_supplier_id')
									->where('order_status','Order Approved')
									->where('app_ord_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_tsl1_orders.*','customers.company_name')
									->get();
			$approved_orders['tsl3'] = DB::table('tesco_tsl3_orders')
				->join('customers','customers.id','=','tesco_tsl3_orders.tesco_supplier_id')
									->where('order_status','Order Approved')
									->where('app_ord_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_tsl3_orders.*','customers.company_name')
									->get();
        	return $approved_orders;
	}
	
	public function pendingOrders()
	{
			
			$pending_orders['tcl1'] = DB::table('tesco_tcl1_orders')
				->join('customers','customers.id','=','tesco_tcl1_orders.tesco_supplier_id')
									->where('order_status','Order Placed')
									->where('pen_ord_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_tcl1_orders.*','customers.company_name')
									->get();
			$pending_orders['tcl2'] = DB::table('tesco_tcl2_orders')
				->join('customers','customers.id','=','tesco_tcl2_orders.tesco_supplier_id')
									->where('order_status','Order Placed')
									->where('pen_ord_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_tcl2_orders.*','customers.company_name')
									->get();
			$pending_orders['tcl4'] = DB::table('tesco_tcl4_orders')
				->join('customers','customers.id','=','tesco_tcl4_orders.tesco_supplier_id')
									->where('order_status','Order Placed')
									->where('pen_ord_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_tcl4_orders.*','customers.company_name')
									->get();
			$pending_orders['thl1'] = DB::table('tesco_thl1_orders')
				->join('customers','customers.id','=','tesco_thl1_orders.tesco_supplier_id')
									->where('order_status','Order Placed')
									->where('pen_ord_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_thl1_orders.*','customers.company_name')
									->get();
			$pending_orders['thl3'] = DB::table('tesco_thl3_orders')
				->join('customers','customers.id','=','tesco_thl3_orders.tesco_supplier_id')
									->where('order_status','Order Placed')
									->where('pen_ord_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_thl3_orders.*','customers.company_name')
									->get();
			$pending_orders['tsl1'] = DB::table('tesco_tsl1_orders')
				->join('customers','customers.id','=','tesco_tsl1_orders.tesco_supplier_id')
									->where('order_status','Order Placed')
									->where('pen_ord_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_tsl1_orders.*','customers.company_name')
									->get();
			$pending_orders['tsl3'] = DB::table('tesco_tsl3_orders')
				->join('customers','customers.id','=','tesco_tsl3_orders.tesco_supplier_id')
									->where('order_status','Order Placed')
									->where('pen_ord_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_tsl3_orders.*','customers.company_name')
									->get();
			return $pending_orders;
	}
	
	public function aowp()
	{
			$app_pipo['tcl1_1'] = DB::table('tesco_tcl1_orders')
				->join('customers','customers.id','=','tesco_tcl1_orders.tesco_supplier_id')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_tcl1_orders.*','customers.company_name')
									->get();
			
			$app_pipo['tcl1_2'] = DB::table('tesco_tcl1_orders')
				->join('customers','customers.id','=','tesco_tcl1_orders.tesco_supplier_id')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_tcl1_orders.*','customers.company_name')
									->get();
			
			$app_pipo['tcl2_1'] = DB::table('tesco_tcl2_orders')
				->join('customers','customers.id','=','tesco_tcl2_orders.tesco_supplier_id')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_tcl2_orders.*','customers.company_name')
									->get();
			
			$app_pipo['tcl2_2'] = DB::table('tesco_tcl2_orders')
				->join('customers','customers.id','=','tesco_tcl2_orders.tesco_supplier_id')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_tcl2_orders.*','customers.company_name')
									->get();
			
			$app_pipo['tcl4_1'] = DB::table('tesco_tcl4_orders')
				->join('customers','customers.id','=','tesco_tcl4_orders.tesco_supplier_id')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_tcl4_orders.*','customers.company_name')
									->get();
			
			$app_pipo['tcl4_2'] = DB::table('tesco_tcl4_orders')
				->join('customers','customers.id','=','tesco_tcl4_orders.tesco_supplier_id')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_tcl4_orders.*','customers.company_name')
									->get();
			
			$app_pipo['thl1_1'] = DB::table('tesco_thl1_orders')
				->join('customers','customers.id','=','tesco_thl1_orders.tesco_supplier_id')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_thl1_orders.*','customers.company_name')
									->get();
			
			$app_pipo['thl1_2'] = DB::table('tesco_thl1_orders')
				->join('customers','customers.id','=','tesco_thl1_orders.tesco_supplier_id')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_thl1_orders.*','customers.company_name')
									->get();
			
			$app_pipo['thl3_1'] = DB::table('tesco_thl3_orders')
				->join('customers','customers.id','=','tesco_thl3_orders.tesco_supplier_id')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_thl3_orders.*','customers.company_name')
									->get();
			
			$app_pipo['thl3_2'] = DB::table('tesco_thl3_orders')
				->join('customers','customers.id','=','tesco_thl3_orders.tesco_supplier_id')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_thl3_orders.*','customers.company_name')
									->get();
			$app_pipo['tsl1_1'] = DB::table('tesco_tsl1_orders')
				->join('customers','customers.id','=','tesco_tsl1_orders.tesco_supplier_id')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_tsl1_orders.*','customers.company_name')
									->get();
			
			$app_pipo['tsl1_2'] = DB::table('tesco_tsl1_orders')
				->join('customers','customers.id','=','tesco_tsl1_orders.tesco_supplier_id')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_tsl1_orders.*','customers.company_name')
									->get();
			
			$app_pipo['tsl3_1'] = DB::table('tesco_tsl3_orders')
				->join('customers','customers.id','=','tesco_tsl3_orders.tesco_supplier_id')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_tsl3_orders.*','customers.company_name')
									->get();
			
			$app_pipo['tsl3_2'] = DB::table('tesco_tsl3_orders')
				->join('customers','customers.id','=','tesco_tsl3_orders.tesco_supplier_id')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->select('tesco_tsl3_orders.*','customers.company_name')
									->get();
        	return $app_pipo;
	}
}