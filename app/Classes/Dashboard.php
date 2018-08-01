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
}