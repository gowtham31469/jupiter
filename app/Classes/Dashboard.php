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
											->whereIn('order_status',['Order Approved','Order Placed'])
											->where('pen_pro_flag',0)
											->select('*')
											->get();
			$pendingproduction['tcl2'] = DB::table('tesco_tcl2_orders')
											->whereIn('order_status',['Order Approved','Order Placed'])
											->where('pen_pro_flag',0)
											->select('*')
											->get();
			$pendingproduction['tcl4'] = DB::table('tesco_tcl4_orders')
											->whereIn('order_status',['Order Approved','Order Placed'])
											->where('pen_pro_flag',0)
											->select('*')
											->get();
			$pendingproduction['tsl1'] = DB::table('tesco_tsl1_orders')
											->whereIn('order_status',['Order Approved','Order Placed'])
											->where('pen_pro_flag',0)
											->select('*')
											->get();
			$pendingproduction['tsl3'] = DB::table('tesco_tsl3_orders')
											->whereIn('order_status',['Order Approved','Order Placed'])
											->where('pen_pro_flag',0)
											->select('*')
											->get();
			$pendingproduction['thl1'] = DB::table('tesco_thl1_orders')
											->whereIn('order_status',['Order Approved','Order Placed'])
											->where('pen_pro_flag',0)
											->select('*')
											->get();
			$pendingproduction['thl3'] = DB::table('tesco_thl3_orders')
											->whereIn('order_status',['Order Approved','Order Placed'])
											->where('pen_pro_flag',0)
											->select('*')
											->get();
      		return $pendingproduction;
   }
	
	public function newOrders()
	{
			$new_orders['tcl1'] = DB::table('tesco_tcl1_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->select('*')
									->get();
			$new_orders['tcl2'] = DB::table('tesco_tcl2_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->select('*')
									->get();
			$new_orders['tcl4'] = DB::table('tesco_tcl4_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->select('*')
									->get();
			$new_orders['thl1'] = DB::table('tesco_thl1_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->select('*')
									->get();
			$new_orders['thl3'] = DB::table('tesco_thl3_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->select('*')
									->get();
			$new_orders['tsl1'] = DB::table('tesco_tsl1_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->select('*')
									->get();
			$new_orders['tsl3'] = DB::table('tesco_tsl3_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->select('*')
									->get();
			return $new_orders;
	}
	
	public function approvedOrders()
	{
			$approved_orders['tcl1'] = DB::table('tesco_tcl1_orders')
									->where('order_status','Order Approved')
									->where('app_ord_flag',0)
									->select('*')
									->get();
			$approved_orders['tcl2'] = DB::table('tesco_tcl2_orders')
									->where('order_status','Order Approved')
									->where('app_ord_flag',0)
									->select('*')
									->get();
			$approved_orders['tcl4'] = DB::table('tesco_tcl4_orders')
									->where('order_status','Order Approved')
									->where('app_ord_flag',0)
									->select('*')
									->get();
			$approved_orders['thl1'] = DB::table('tesco_thl1_orders')
									->where('order_status','Order Approved')
									->where('app_ord_flag',0)
									->select('*')
									->get();
			$approved_orders['thl3'] = DB::table('tesco_thl3_orders')
									->where('order_status','Order Approved')
									->where('app_ord_flag',0)
									->select('*')
									->get();
			$approved_orders['tsl1'] = DB::table('tesco_tsl1_orders')
									->where('order_status','Order Approved')
									->where('app_ord_flag',0)
									->select('*')
									->get();
			$approved_orders['tsl3'] = DB::table('tesco_tsl3_orders')
									->where('order_status','Order Approved')
									->where('app_ord_flag',0)
									->select('*')
									->get();
        	return $approved_orders;
	}
	
	public function pendingOrders()
	{
			
			$pending_orders['tcl1'] = DB::table('tesco_tcl1_orders')
									->where('order_status','Order Placed')
									->where('pen_ord_flag',0)
									->select('*')
									->get();
			$pending_orders['tcl2'] = DB::table('tesco_tcl2_orders')
									->where('order_status','Order Placed')
									->where('pen_ord_flag',0)
									->select('*')
									->get();
			$pending_orders['tcl4'] = DB::table('tesco_tcl4_orders')
									->where('order_status','Order Placed')
									->where('pen_ord_flag',0)
									->select('*')
									->get();
			$pending_orders['thl1'] = DB::table('tesco_thl1_orders')
									->where('order_status','Order Placed')
									->where('pen_ord_flag',0)
									->select('*')
									->get();
			$pending_orders['thl3'] = DB::table('tesco_thl3_orders')
									->where('order_status','Order Placed')
									->where('pen_ord_flag',0)
									->select('*')
									->get();
			$pending_orders['tsl1'] = DB::table('tesco_tsl1_orders')
									->where('order_status','Order Placed')
									->where('pen_ord_flag',0)
									->select('*')
									->get();
			$pending_orders['tsl3'] = DB::table('tesco_tsl3_orders')
									->where('order_status','Order Placed')
									->where('pen_ord_flag',0)
									->select('*')
									->get();
			return $pending_orders;
	}
	
	public function aowp()
	{
			$app_pipo['tcl1_1'] = DB::table('tesco_tcl1_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->where('app_pipo_flag',0)
									->select('*')
									->get();
			
			$app_pipo['tcl1_2'] = DB::table('tesco_tcl1_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->where('app_pipo_flag',0)
									->select('*')
									->get();
			
			$app_pipo['tcl2_1'] = DB::table('tesco_tcl2_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->where('app_pipo_flag',0)
									->select('*')
									->get();
			
			$app_pipo['tcl2_2'] = DB::table('tesco_tcl2_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->where('app_pipo_flag',0)
									->select('*')
									->get();
			
			$app_pipo['tcl4_1'] = DB::table('tesco_tcl4_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->where('app_pipo_flag',0)
									->select('*')
									->get();
			
			$app_pipo['tcl4_2'] = DB::table('tesco_tcl4_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->where('app_pipo_flag',0)
									->select('*')
									->get();
			
			$app_pipo['thl1_1'] = DB::table('tesco_thl1_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->where('app_pipo_flag',0)
									->select('*')
									->get();
			
			$app_pipo['thl1_2'] = DB::table('tesco_thl1_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->where('app_pipo_flag',0)
									->select('*')
									->get();
			
			$app_pipo['thl3_1'] = DB::table('tesco_thl3_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->where('app_pipo_flag',0)
									->select('*')
									->get();
			
			$app_pipo['thl3_2'] = DB::table('tesco_thl3_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->where('app_pipo_flag',0)
									->select('*')
									->get();
			$app_pipo['tsl1_1'] = DB::table('tesco_tsl1_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->where('app_pipo_flag',0)
									->select('*')
									->get();
			
			$app_pipo['tsl1_2'] = DB::table('tesco_tsl1_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->where('app_pipo_flag',0)
									->select('*')
									->get();
			
			$app_pipo['tsl3_1'] = DB::table('tesco_tsl3_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->where('app_pipo_flag',0)
									->select('*')
									->get();
			
			$app_pipo['tsl3_2'] = DB::table('tesco_tsl3_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->where('app_pipo_flag',0)
									->select('*')
									->get();
        	return $app_pipo;
	}
}