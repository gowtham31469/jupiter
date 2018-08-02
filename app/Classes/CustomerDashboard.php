<?php
namespace App\Classes;
use DB;
use Auth;

class CustomerDashboard
{
	private $tcl1;
    private $tcl2;
    private $tcl4;
    private $thl1;
    private $thl3;
    private $tsl1;
    private $tsl3;
    private $tcl1_table;
    private $tcl2_table;
    private $tcl4_table;
    private $thl1_table;
    private $thl3_table;
    private $tsl1_table;
    private $tsl3_table;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->tcl1 = "TCL 1";
        $this->tcl2 = "TCL 2";
        $this->tcl4 = "TCL 4";
        $this->thl1 = "THL 1";
        $this->thl3 = "THL 3";
        $this->tsl1 = "TSL 1";
        $this->tsl3 = "TSL 3";

        $this->tcl1_table = "tesco_tcl1_orders";
        $this->tcl2_table = "tesco_tcl2_orders";
        $this->tcl4_table = "tesco_tcl4_orders";
        $this->thl1_table = "tesco_thl1_orders";
        $this->thl3_table = "tesco_thl3_orders";
        $this->tsl1_table = "tesco_tsl1_orders";
        $this->tsl3_table = "tesco_tsl3_orders";
    }


	public function getAllApprovedOrders()
	{
		$customer_id = Auth::guard('web_customer')->id();
        $customer_role_id = DB::table('customer_profile')->select('*')
                              ->where('id',$customer_id)->first();
		$approved['tcl1'] = DB::table('tesco_tcl1_orders')
                              ->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)->where('order_status',"Order Approved")
                              ->where('app_ord_flag',0)
                              ->select('*',DB::raw("'".$this->tcl1."'".' as "TemplateName"'),DB::raw("'".$this->tcl1_table."'".' as "tablename"'))
                              ->get() ;
          $approved['tcl2'] = DB::table('tesco_tcl2_orders')
                                ->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Approved")
                                ->where('app_ord_flag',0)
                                ->select('*',DB::raw("'".$this->tcl2."'".' as "TemplateName"'),DB::raw("'".$this->tcl2_table."'".' as "tablename"'))
                                ->get() ;
          $approved['tcl4'] = DB::table('tesco_tcl4_orders')
                               ->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
                               ->where('order_status',"Order Approved")
                               ->where('app_ord_flag',0)
                               ->select('*',DB::raw("'".$this->tcl4."'".' as "TemplateName"'),DB::raw("'".$this->tcl4_table."'".' as "tablename"'))
                               ->get() ;
          $approved['thl1'] = DB::table('tesco_thl1_orders')
                                ->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Approved")
                                ->where('app_ord_flag',0)
                                ->select('*',DB::raw("'".$this->thl1."'".' as "TemplateName"'),DB::raw("'".$this->thl1_table."'".' as "tablename"'))
                                ->get() ;
          $approved['thl3'] = DB::table('tesco_thl3_orders')
                                ->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Approved")
                                ->where('app_ord_flag',0)
                                ->select('*',DB::raw("'".$this->thl3."'".' as "TemplateName"'),DB::raw("'".$this->thl3_table."'".' as "tablename"'))
                                ->get() ;
          $approved['tsl1'] = DB::table('tesco_tsl1_orders')
                              ->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Approved")
                              ->where('app_ord_flag',0)
                              ->select('*',DB::raw("'".$this->tsl1."'".' as "TemplateName"'),DB::raw("'".$this->tsl1_table."'".' as "tablename"'))
                              ->get() ;
          $approved['tsl3'] = DB::table('tesco_tsl3_orders')
                              ->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Approved")
                              ->where('app_ord_flag',0)
                              ->select('*',DB::raw("'".$this->tsl3."'".' as "TemplateName"'),DB::raw("'".$this->tsl3_table."'".' as "tablename"'))
                              ->get() ;
        return $approved ;
	}

	public function getApprovedOrders()
	{
		$customer_id = Auth::guard('web_customer')->id();
        $customer_role_id = DB::table('customer_profile')->select('*')
                              ->where('id',$customer_id)->first();
		$approved['tcl1'] = DB::table('tesco_tcl1_orders')
                              ->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)->where('order_status',"Order Approved")
                              ->where('app_ord_flag',0)
                              ->where('tesco_tcl1_orders.order_placed_by','=',$customer_id)
                              ->select('*',DB::raw("'".$this->tcl1."'".' as "TemplateName"'),DB::raw("'".$this->tcl1_table."'".' as "tablename"'))
                              ->get() ;
          $approved['tcl2'] = DB::table('tesco_tcl2_orders')
                                ->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Approved")
                                ->where('app_ord_flag',0)
                                ->where('tesco_tcl2_orders.order_placed_by','=',$customer_id)
                                ->select('*',DB::raw("'".$this->tcl2."'".' as "TemplateName"'),DB::raw("'".$this->tcl2_table."'".' as "tablename"'))
                                ->get() ;
          $approved['tcl4'] = DB::table('tesco_tcl4_orders')
                               ->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
                               ->where('order_status',"Order Approved")
                               ->where('app_ord_flag',0)
                               ->where('tesco_tcl4_orders.order_placed_by','=',$customer_id)
                               ->select('*',DB::raw("'".$this->tcl4."'".' as "TemplateName"'),DB::raw("'".$this->tcl4_table."'".' as "tablename"'))
                               ->get() ;
          $approved['thl1'] = DB::table('tesco_thl1_orders')
                                ->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Approved")
                                ->where('app_ord_flag',0)
                                ->where('tesco_thl1_orders.order_placed_by','=',$customer_id)
                                ->select('*',DB::raw("'".$this->thl1."'".' as "TemplateName"'),DB::raw("'".$this->thl1_table."'".' as "tablename"'))
                                ->get() ;
          $approved['thl3'] = DB::table('tesco_thl3_orders')
                                ->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Approved")
                                ->where('app_ord_flag',0)
                                ->where('tesco_thl3_orders.order_placed_by','=',$customer_id)
                                ->select('*',DB::raw("'".$this->thl3."'".' as "TemplateName"'),DB::raw("'".$this->thl3_table."'".' as "tablename"'))
                                ->get() ;
          $approved['tsl1'] = DB::table('tesco_tsl1_orders')
                              ->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Approved")
                              ->where('app_ord_flag',0)
                              ->where('tesco_tsl1_orders.order_placed_by','=',$customer_id)
                              ->select('*',DB::raw("'".$this->tsl1."'".' as "TemplateName"'),DB::raw("'".$this->tsl1_table."'".' as "tablename"'))
                              ->get() ;
          $approved['tsl3'] = DB::table('tesco_tsl3_orders')
                              ->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Approved")
                              ->where('app_ord_flag',0)
                              ->where('tesco_tsl3_orders.order_placed_by','=',$customer_id)
                              ->select('*',DB::raw("'".$this->tsl3."'".' as "TemplateName"'),DB::raw("'".$this->tsl3_table."'".' as "tablename"'))
                              ->get() ;
        return $approved ;
	}

	public function getAllPendingOrders()
	{
		$customer_id = Auth::guard('web_customer')->id();
        $customer_role_id = DB::table('customer_profile')->select('*')
                              ->where('id',$customer_id)->first();
		$pending['tcl1'] = DB::table('tesco_tcl1_orders')
                              ->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)->where('order_status',"Order Placed")
                              ->where('pen_ord_flag',0)
                              ->select('*',DB::raw("'".$this->tcl1."'".' as "TemplateName"'),DB::raw("'".$this->tcl1_table."'".' as "tablename"'))
                              ->get() ;
          $pending['tcl2'] = DB::table('tesco_tcl2_orders')
                                ->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Placed")
                                ->where('pen_ord_flag',0)
                                ->select('*',DB::raw("'".$this->tcl2."'".' as "TemplateName"'),DB::raw("'".$this->tcl2_table."'".' as "tablename"'))
                                ->get() ;
          $pending['tcl4'] = DB::table('tesco_tcl4_orders')
                               ->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
                               ->where('order_status',"Order Placed")
                               ->where('pen_ord_flag',0)
                               ->select('*',DB::raw("'".$this->tcl4."'".' as "TemplateName"'),DB::raw("'".$this->tcl4_table."'".' as "tablename"'))
                               ->get() ;
          $pending['thl1'] = DB::table('tesco_thl1_orders')
                                ->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Placed")
                                ->where('pen_ord_flag',0)
                                ->select('*',DB::raw("'".$this->thl1."'".' as "TemplateName"'),DB::raw("'".$this->thl1_table."'".' as "tablename"'))
                                ->get() ;
          $pending['thl3'] = DB::table('tesco_thl3_orders')
                                ->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Placed")
                                ->where('pen_ord_flag',0)
                                ->select('*',DB::raw("'".$this->thl3."'".' as "TemplateName"'),DB::raw("'".$this->thl3_table."'".' as "tablename"'))
                                ->get() ;
          $pending['tsl1'] = DB::table('tesco_tsl1_orders')
                              ->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Placed")
                              ->where('pen_ord_flag',0)
                              ->select('*',DB::raw("'".$this->tsl1."'".' as "TemplateName"'),DB::raw("'".$this->tsl1_table."'".' as "tablename"'))
                              ->get() ;
          $pending['tsl3'] = DB::table('tesco_tsl3_orders')
                              ->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Placed")
                              ->where('pen_ord_flag',0)
                              ->select('*',DB::raw("'".$this->tsl3."'".' as "TemplateName"'),DB::raw("'".$this->tsl3_table."'".' as "tablename"'))
                              ->get() ;
        return $pending;
	}

	public function getPendingOrders()
	{
		$customer_id = Auth::guard('web_customer')->id();
        $customer_role_id = DB::table('customer_profile')->select('*')
                              ->where('id',$customer_id)->first();
		$pending['tcl1'] = DB::table('tesco_tcl1_orders')
                              ->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)->where('order_status',"Order Placed")
                              ->where('pen_ord_flag',0)
                              ->where('tesco_tcl1_orders.order_placed_by','=',$customer_id)
                              ->select('*',DB::raw("'".$this->tcl1."'".' as "TemplateName"'),DB::raw("'".$this->tcl1_table."'".' as "tablename"'))
                              ->get() ;
          $pending['tcl2'] = DB::table('tesco_tcl2_orders')
                                ->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Placed")
                                ->where('pen_ord_flag',0)
                                ->where('tesco_tcl2_orders.order_placed_by','=',$customer_id)
                                ->select('*',DB::raw("'".$this->tcl2."'".' as "TemplateName"'),DB::raw("'".$this->tcl2_table."'".' as "tablename"'))
                                ->get() ;
          $pending['tcl4'] = DB::table('tesco_tcl4_orders')
                               ->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
                               ->where('order_status',"Order Placed")
                               ->where('pen_ord_flag',0)
                               ->where('tesco_tcl4_orders.order_placed_by','=',$customer_id)
                               ->select('*',DB::raw("'".$this->tcl4."'".' as "TemplateName"'),DB::raw("'".$this->tcl4_table."'".' as "tablename"'))
                               ->get() ;
          $pending['thl1'] = DB::table('tesco_thl1_orders')
                                ->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Placed")
                                ->where('pen_ord_flag',0)
                                ->where('tesco_thl1_orders.order_placed_by','=',$customer_id)
                                ->select('*',DB::raw("'".$this->thl1."'".' as "TemplateName"'),DB::raw("'".$this->thl1_table."'".' as "tablename"'))
                                ->get() ;
          $pending['thl3'] = DB::table('tesco_thl3_orders')
                                ->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Placed")
                                ->where('pen_ord_flag',0)
                                ->where('tesco_thl3_orders.order_placed_by','=',$customer_id)
                                ->select('*',DB::raw("'".$this->thl3."'".' as "TemplateName"'),DB::raw("'".$this->thl3_table."'".' as "tablename"'))
                                ->get() ;
          $pending['tsl1'] = DB::table('tesco_tsl1_orders')
                              ->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Placed")
                              ->where('pen_ord_flag',0)
                              ->where('tesco_tsl1_orders.order_placed_by','=',$customer_id)
                              ->select('*',DB::raw("'".$this->tsl1."'".' as "TemplateName"'),DB::raw("'".$this->tsl1_table."'".' as "tablename"'))
                              ->get() ;
          $pending['tsl3'] = DB::table('tesco_tsl3_orders')
                              ->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Placed")
                              ->where('pen_ord_flag',0)
                              ->where('tesco_tsl3_orders.order_placed_by','=',$customer_id)
                              ->select('*',DB::raw("'".$this->tsl3."'".' as "TemplateName"'),DB::raw("'".$this->tsl3_table."'".' as "tablename"'))
                              ->get() ;
        return $pending;
	}


	public function getAllCancelledOrders()
	{
		$customer_id = Auth::guard('web_customer')->id();
        $customer_role_id = DB::table('customer_profile')->select('*')
                              ->where('id',$customer_id)->first();
        $cancelled['tcl1'] = DB::table('tesco_tcl1_orders')
                              ->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)->where('order_status',"Order Cancelled")
                              ->where('cancel_ord_flag',0)
                              ->select('*',DB::raw("'".$this->tcl1."'".' as "TemplateName"'),DB::raw("'".$this->tcl1_table."'".' as "tablename"'))
                              ->get() ;
        $cancelled['tcl2'] = DB::table('tesco_tcl2_orders')
                                ->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Cancelled")
                                ->where('cancel_ord_flag',0)
                                ->select('*',DB::raw("'".$this->tcl2."'".' as "TemplateName"'),DB::raw("'".$this->tcl2_table."'".' as "tablename"'))
                                ->get() ;
        $cancelled['tcl4'] = DB::table('tesco_tcl4_orders')
                               ->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
                               ->where('order_status',"Order Cancelled")
                               ->where('cancel_ord_flag',0)
                               ->select('*',DB::raw("'".$this->tcl4."'".' as "TemplateName"'),DB::raw("'".$this->tcl4_table."'".' as "tablename"'))
                               ->get() ;
        $cancelled['thl1'] = DB::table('tesco_thl1_orders')
                                ->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Cancelled")
                                ->where('cancel_ord_flag',0)
                                ->select('*',DB::raw("'".$this->thl1."'".' as "TemplateName"'),DB::raw("'".$this->thl1_table."'".' as "tablename"'))
                                ->get() ;
        $cancelled['thl3'] = DB::table('tesco_thl3_orders')
                                ->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Cancelled")
                                ->where('cancel_ord_flag',0)
                                ->select('*',DB::raw("'".$this->thl3."'".' as "TemplateName"'),DB::raw("'".$this->thl3_table."'".' as "tablename"'))
                                ->get() ;
        $cancelled['tsl1'] = DB::table('tesco_tsl1_orders')
                              ->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Cancelled")
                              ->where('cancel_ord_flag',0)
                              ->select('*',DB::raw("'".$this->tsl1."'".' as "TemplateName"'),DB::raw("'".$this->tsl1_table."'".' as "tablename"'))
                              ->get() ;
        $cancelled['tsl3'] = DB::table('tesco_tsl3_orders')
                              ->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Cancelled")
                              ->where('cancel_ord_flag',0)
                              ->select('*',DB::raw("'".$this->tsl3."'".' as "TemplateName"'),DB::raw("'".$this->tsl3_table."'".' as "tablename"'))
                              ->get() ;                      
        return $cancelled;
	}

	public function getCancelledOrders()
	{
		$customer_id = Auth::guard('web_customer')->id();
        $customer_role_id = DB::table('customer_profile')->select('*')
                              ->where('id',$customer_id)->first();
        $cancelled['tcl1'] = DB::table('tesco_tcl1_orders')
                              ->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)->where('order_status',"Order Cancelled")
                              ->where('cancel_ord_flag',0)
                              ->where('tesco_tcl1_orders.order_placed_by','=',$customer_id)
                              ->select('*',DB::raw("'".$this->tcl1."'".' as "TemplateName"'),DB::raw("'".$this->tcl1_table."'".' as "tablename"'))
                              ->get() ;
        $cancelled['tcl2'] = DB::table('tesco_tcl2_orders')
                                ->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Cancelled")
                                ->where('cancel_ord_flag',0)
                                ->where('tesco_tcl2_orders.order_placed_by','=',$customer_id)
                                ->select('*',DB::raw("'".$this->tcl2."'".' as "TemplateName"'),DB::raw("'".$this->tcl2_table."'".' as "tablename"'))
                                ->get() ;
        $cancelled['tcl4'] = DB::table('tesco_tcl4_orders')
                               ->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
                               ->where('order_status',"Order Cancelled")
                               ->where('cancel_ord_flag',0)
                               ->where('tesco_tcl4_orders.order_placed_by','=',$customer_id)
                               ->select('*',DB::raw("'".$this->tcl4."'".' as "TemplateName"'),DB::raw("'".$this->tcl4_table."'".' as "tablename"'))
                               ->get() ;
        $cancelled['thl1'] = DB::table('tesco_thl1_orders')
                                ->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Cancelled")
                                ->where('cancel_ord_flag',0)
                                ->where('tesco_thl1_orders.order_placed_by','=',$customer_id)
                                ->select('*',DB::raw("'".$this->thl1."'".' as "TemplateName"'),DB::raw("'".$this->thl1_table."'".' as "tablename"'))
                                ->get() ;
        $cancelled['thl3'] = DB::table('tesco_thl3_orders')
                                ->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Cancelled")
                                ->where('cancel_ord_flag',0)
                                ->where('tesco_thl3_orders.order_placed_by','=',$customer_id)
                                ->select('*',DB::raw("'".$this->thl3."'".' as "TemplateName"'),DB::raw("'".$this->thl3_table."'".' as "tablename"'))
                                ->get() ;
        $cancelled['tsl1'] = DB::table('tesco_tsl1_orders')
                              ->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Cancelled")
                              ->where('cancel_ord_flag',0)
                              ->where('tesco_tsl1_orders.order_placed_by','=',$customer_id)
                              ->select('*',DB::raw("'".$this->tsl1."'".' as "TemplateName"'),DB::raw("'".$this->tsl1_table."'".' as "tablename"'))
                              ->get() ;
        $cancelled['tsl3'] = DB::table('tesco_tsl3_orders')
                              ->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Cancelled")
                              ->where('cancel_ord_flag',0)
                              ->where('tesco_tsl3_orders.order_placed_by','=',$customer_id)
                              ->select('*',DB::raw("'".$this->tsl3."'".' as "TemplateName"'),DB::raw("'".$this->tsl3_table."'".' as "tablename"'))
                              ->get() ;                      
        return $cancelled;
	}

  public function getApprovedOrdersCount($approved_orders)
  {
    return count($approved_orders['tcl1'])+count($approved_orders['tcl2'])+count($approved_orders['tcl4'])+count($approved_orders['thl1'])+count($approved_orders['thl3'])+count($approved_orders['tsl1']) + count($approved_orders['tsl3']);
  }
  
  public function getPendingOrdersCount($app_pending_orders)
  {
    return count($app_pending_orders['tcl1'])+count($app_pending_orders['tcl2'])+count($app_pending_orders['tcl4'])+count($app_pending_orders['thl1'])+count($app_pending_orders['thl3'])+count($app_pending_orders['tsl1']) + count($app_pending_orders['tsl3']);
  }

  public function getCancelledOrdersCount($cancelled_orders)
  {
    return count($cancelled_orders['tcl1'])+count($cancelled_orders['tcl2'])+count($cancelled_orders['tcl4'])+count($cancelled_orders['thl1'])+count($cancelled_orders['thl3'])+count($cancelled_orders['tsl1']) + count($cancelled_orders['tsl3']);
  }
}