<?php
namespace App\Classes;
use DB;
use Auth;

class CustomerDashboard
{
	  private $tcl1;
    private $tcl2;
    private $tcl4;
    private $tcl5;
    private $thl1;
    private $thl3;
    private $tsl1;
    private $tsl2;
    private $tsl3;
    private $tcl1_table;
    private $tcl2_table;
    private $tcl4_table;
    private $tcl5_table;
    private $thl1_table;
    private $thl3_table;
    private $tsl1_table;
    private $tsl2_table;
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
        $this->tcl5 = "TCL 5";
        $this->thl1 = "THL 1";
        $this->thl3 = "THL 3";
        $this->tsl1 = "TSL 1";
        $this->tsl2 = "TSL 2";
        $this->tsl3 = "TSL 3";

        $this->tcl1_table = "tesco_tcl1_orders";
        $this->tcl2_table = "tesco_tcl2_orders";
        $this->tcl4_table = "tesco_tcl4_orders";
        $this->tcl5_table = "tesco_tcl5_orders";
        $this->thl1_table = "tesco_thl1_orders";
        $this->thl3_table = "tesco_thl3_orders";
        $this->tsl1_table = "tesco_tsl1_orders";
        $this->tsl2_table = "tesco_tsl2_orders";
        $this->tsl3_table = "tesco_tsl3_orders";
        $this->wash_table = "tesco_washcare_orders";
    }


	public function getAllApprovedOrders($from_date, $to_date)
	{
		$customer_id = Auth::guard('web_customer')->id();
        $customer_role_id = DB::table('customer_profile')->select('*')
                              ->where('id',$customer_id)->first();
		$approved['tcl1'] = DB::table('tesco_tcl1_orders')
                              ->join('customers','tesco_tcl1_orders.tesco_supplier_id','=','customers.id')
                              ->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)->where('order_status',"Order Approved")
                              ->where('app_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->whereBetween('tesco_tcl1_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_tcl1_orders.*',DB::raw("'".$this->tcl1."'".' as "TemplateName"'),DB::raw("'".$this->tcl1_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;
          $approved['tcl2'] = DB::table('tesco_tcl2_orders')
                                ->join('customers','tesco_tcl2_orders.tesco_supplier_id','=','customers.id')
                                ->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Approved")
                                ->where('app_ord_flag',0)
                                ->where('is_order_count_empty',0)
                                ->whereBetween('tesco_tcl2_orders.created_at', [$from_date, $to_date])
                                ->select('tesco_tcl2_orders.*',DB::raw("'".$this->tcl2."'".' as "TemplateName"'),DB::raw("'".$this->tcl2_table."'".' as "tablename"'),'customers.company_name')
                                ->get() ;
          $approved['tcl4'] = DB::table('tesco_tcl4_orders')
                               ->join('customers','tesco_tcl4_orders.tesco_supplier_id','=','customers.id')
                               ->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
                               ->where('order_status',"Order Approved")
                               ->where('app_ord_flag',0)
                               ->where('is_order_count_empty',0)
                               ->whereBetween('tesco_tcl4_orders.created_at', [$from_date, $to_date])
                               ->select('tesco_tcl4_orders.*',DB::raw("'".$this->tcl4."'".' as "TemplateName"'),DB::raw("'".$this->tcl4_table."'".' as "tablename"'),'customers.company_name')
                               ->get() ;


          $approved['tcl5'] = DB::table('tesco_tcl5_orders')
                              ->join('customers','tesco_tcl5_orders.tesco_supplier_id','=','customers.id')
                              ->where('tesco_tcl5_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Approved")
                              ->where('app_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->whereBetween('tesco_tcl5_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_tcl5_orders.*',DB::raw("'".$this->tcl5."'".' as "TemplateName"'),DB::raw("'".$this->tcl5_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;

          $approved['thl1'] = DB::table('tesco_thl1_orders')
                                ->join('customers','tesco_thl1_orders.tesco_supplier_id','=','customers.id')
                                ->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Approved")
                                ->where('app_ord_flag',0)
                                ->where('is_order_count_empty',0)
                                ->whereBetween('tesco_thl1_orders.created_at', [$from_date, $to_date])
                                ->select('tesco_thl1_orders.*',DB::raw("'".$this->thl1."'".' as "TemplateName"'),DB::raw("'".$this->thl1_table."'".' as "tablename"'),'customers.company_name')
                                ->get() ;
          $approved['thl3'] = DB::table('tesco_thl3_orders')
                                ->join('customers','tesco_thl3_orders.tesco_supplier_id','=','customers.id')
                                ->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Approved")
                                ->where('app_ord_flag',0)
                                ->where('is_order_count_empty',0)
                                ->whereBetween('tesco_thl3_orders.created_at', [$from_date, $to_date])
                                ->select('tesco_thl3_orders.*',DB::raw("'".$this->thl3."'".' as "TemplateName"'),DB::raw("'".$this->thl3_table."'".' as "tablename"'),'customers.company_name')
                                ->get() ;
          $approved['tsl1'] = DB::table('tesco_tsl1_orders')
                              ->join('customers','tesco_tsl1_orders.tesco_supplier_id','=','customers.id')
                              ->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Approved")
                              ->where('app_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->whereBetween('tesco_tsl1_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_tsl1_orders.*',DB::raw("'".$this->tsl1."'".' as "TemplateName"'),DB::raw("'".$this->tsl1_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;

          $approved['tsl2'] = DB::table('tesco_tsl2_orders')
                              ->join('customers','tesco_tsl2_orders.tesco_supplier_id','=','customers.id')
                              ->where('tesco_tsl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Approved")
                              ->where('app_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->whereBetween('tesco_tsl2_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_tsl2_orders.*',DB::raw("'".$this->tsl2."'".' as "TemplateName"'),DB::raw("'".$this->tsl2_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;

          $approved['tsl3'] = DB::table('tesco_tsl3_orders')
                              ->join('customers','tesco_tsl3_orders.tesco_supplier_id','=','customers.id')
                              ->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Approved")
                              ->where('app_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->whereBetween('tesco_tsl3_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_tsl3_orders.*',DB::raw("'".$this->tsl3."'".' as "TemplateName"'),DB::raw("'".$this->tsl3_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;

          $approved['wcl'] = DB::table('tesco_washcare_orders')
                              ->join('customers','tesco_washcare_orders.tesco_supplier_id','=','customers.id')
                              ->join('tesco_template','tesco_washcare_orders.template_id','=','tesco_template.item_id')
                              ->where('tesco_washcare_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Approved")
                              ->where('app_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->whereBetween('tesco_washcare_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_washcare_orders.*',DB::raw('tesco_template.template_name as "TemplateName"'),DB::raw("'".$this->wash_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;
        return $approved ;
	}

	public function getApprovedOrders($from_date, $to_date)
	{
		$customer_id = Auth::guard('web_customer')->id();
        $customer_role_id = DB::table('customer_profile')->select('*')
                              ->where('id',$customer_id)->first();
		$approved['tcl1'] = DB::table('tesco_tcl1_orders')
                              ->join('customers','tesco_tcl1_orders.tesco_supplier_id','=','customers.id')
                              ->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)->where('order_status',"Order Approved")
                              ->where('app_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->where('tesco_tcl1_orders.order_placed_by','=',$customer_id)
                              ->whereBetween('tesco_tcl1_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_tcl1_orders.*',DB::raw("'".$this->tcl1."'".' as "TemplateName"'),DB::raw("'".$this->tcl1_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;
          $approved['tcl2'] = DB::table('tesco_tcl2_orders')
                                ->join('customers','tesco_tcl2_orders.tesco_supplier_id','=','customers.id')
                                ->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Approved")
                                ->where('app_ord_flag',0)
                                ->where('is_order_count_empty',0)
                                ->where('tesco_tcl2_orders.order_placed_by','=',$customer_id)
                                ->whereBetween('tesco_tcl2_orders.created_at', [$from_date, $to_date])
                                ->select('tesco_tcl2_orders.*',DB::raw("'".$this->tcl2."'".' as "TemplateName"'),DB::raw("'".$this->tcl2_table."'".' as "tablename"'),'customers.company_name')
                                ->get() ;
          $approved['tcl4'] = DB::table('tesco_tcl4_orders')
                              ->join('customers','tesco_tcl4_orders.tesco_supplier_id','=','customers.id')
                               ->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
                               ->where('order_status',"Order Approved")
                               ->where('app_ord_flag',0)
                               ->where('is_order_count_empty',0)
                               ->where('tesco_tcl4_orders.order_placed_by','=',$customer_id)
                               ->whereBetween('tesco_tcl4_orders.created_at', [$from_date, $to_date])
                               ->select('tesco_tcl4_orders.*',DB::raw("'".$this->tcl4."'".' as "TemplateName"'),DB::raw("'".$this->tcl4_table."'".' as "tablename"'),'customers.company_name')
                               ->get() ;

          $approved['tcl5'] = DB::table('tesco_tcl5_orders')
                              ->join('customers','tesco_tcl5_orders.tesco_supplier_id','=','customers.id')
                               ->where('tesco_tcl5_orders.tesco_supplier_id', $customer_role_id->company_id)
                               ->where('order_status',"Order Approved")
                               ->where('app_ord_flag',0)
                               ->where('is_order_count_empty',0)
                               ->where('tesco_tcl5_orders.order_placed_by','=',$customer_id)
                               ->whereBetween('tesco_tcl5_orders.created_at', [$from_date, $to_date])
                               ->select('tesco_tcl5_orders.*',DB::raw("'".$this->tcl5."'".' as "TemplateName"'),DB::raw("'".$this->tcl5_table."'".' as "tablename"'),'customers.company_name')
                               ->get() ;


          $approved['thl1'] = DB::table('tesco_thl1_orders')
                                ->join('customers','tesco_thl1_orders.tesco_supplier_id','=','customers.id')
                                ->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Approved")
                                ->where('app_ord_flag',0)
                                ->where('is_order_count_empty',0)
                                ->where('tesco_thl1_orders.order_placed_by','=',$customer_id)
                                ->whereBetween('tesco_thl1_orders.created_at', [$from_date, $to_date])
                                ->select('tesco_thl1_orders.*',DB::raw("'".$this->thl1."'".' as "TemplateName"'),DB::raw("'".$this->thl1_table."'".' as "tablename"'),'customers.company_name')
                                ->get() ;
          $approved['thl3'] = DB::table('tesco_thl3_orders')
                                ->join('customers','tesco_thl3_orders.tesco_supplier_id','=','customers.id')
                                ->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Approved")
                                ->where('app_ord_flag',0)
                                ->where('is_order_count_empty',0)
                                ->where('tesco_thl3_orders.order_placed_by','=',$customer_id)
                                ->whereBetween('tesco_thl3_orders.created_at', [$from_date, $to_date])
                                ->select('tesco_thl3_orders.*',DB::raw("'".$this->thl3."'".' as "TemplateName"'),DB::raw("'".$this->thl3_table."'".' as "tablename"'),'customers.company_name')
                                ->get() ;
          $approved['tsl1'] = DB::table('tesco_tsl1_orders')
                              ->join('customers','tesco_tsl1_orders.tesco_supplier_id','=','customers.id')
                              ->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Approved")
                              ->where('app_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->where('tesco_tsl1_orders.order_placed_by','=',$customer_id)
                              ->whereBetween('tesco_tsl1_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_tsl1_orders.*',DB::raw("'".$this->tsl1."'".' as "TemplateName"'),DB::raw("'".$this->tsl1_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;


          $approved['tsl2'] = DB::table('tesco_tsl2_orders')
                              ->join('customers','tesco_tsl2_orders.tesco_supplier_id','=','customers.id')
                              ->where('tesco_tsl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Approved")
                              ->where('app_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->where('tesco_tsl2_orders.order_placed_by','=',$customer_id)
                              ->whereBetween('tesco_tsl2_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_tsl2_orders.*',DB::raw("'".$this->tsl2."'".' as "TemplateName"'),DB::raw("'".$this->tsl2_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;

          $approved['tsl3'] = DB::table('tesco_tsl3_orders')
                              ->join('customers','tesco_tsl3_orders.tesco_supplier_id','=','customers.id')
                              ->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Approved")
                              ->where('app_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->where('tesco_tsl3_orders.order_placed_by','=',$customer_id)
                              ->whereBetween('tesco_tsl3_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_tsl3_orders.*',DB::raw("'".$this->tsl3."'".' as "TemplateName"'),DB::raw("'".$this->tsl3_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;


          $approved['wcl'] = DB::table('tesco_washcare_orders')
                              ->join('customers','tesco_washcare_orders.tesco_supplier_id','=','customers.id')
                              ->join('tesco_template','tesco_washcare_orders.template_id','=','tesco_template.item_id')
                              ->where('tesco_washcare_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Approved")
                              ->where('app_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->where('tesco_washcare_orders.order_placed_by','=',$customer_id)
                              ->whereBetween('tesco_washcare_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_washcare_orders.*',DB::raw('tesco_template.template_name as "TemplateName"'),DB::raw("'".$this->wash_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;
        return $approved ;
	}

	public function getAllPendingOrders($from_date, $to_date)
	{
		$customer_id = Auth::guard('web_customer')->id();
        $customer_role_id = DB::table('customer_profile')->select('*')
                              ->where('id',$customer_id)->first();
		$pending['tcl1'] = DB::table('tesco_tcl1_orders')
                              ->join('customers','tesco_tcl1_orders.tesco_supplier_id','=','customers.id')
                              ->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)->where('order_status',"Order Placed")
                              ->where('pen_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->whereBetween('tesco_tcl1_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_tcl1_orders.*',DB::raw("'".$this->tcl1."'".' as "TemplateName"'),DB::raw("'".$this->tcl1_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;
          $pending['tcl2'] = DB::table('tesco_tcl2_orders')
                                ->join('customers','tesco_tcl2_orders.tesco_supplier_id','=','customers.id')
                                ->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Placed")
                                ->where('pen_ord_flag',0)
                                ->where('is_order_count_empty',0)
                                ->whereBetween('tesco_tcl2_orders.created_at', [$from_date, $to_date])
                                ->select('tesco_tcl2_orders.*',DB::raw("'".$this->tcl2."'".' as "TemplateName"'),DB::raw("'".$this->tcl2_table."'".' as "tablename"'),'customers.company_name')
                                ->get() ;
          $pending['tcl4'] = DB::table('tesco_tcl4_orders')
                               ->join('customers','tesco_tcl4_orders.tesco_supplier_id','=','customers.id')
                               ->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
                               ->where('order_status',"Order Placed")
                               ->where('pen_ord_flag',0)
                               ->where('is_order_count_empty',0)
                               ->whereBetween('tesco_tcl4_orders.created_at', [$from_date, $to_date])
                               ->select('tesco_tcl4_orders.*',DB::raw("'".$this->tcl4."'".' as "TemplateName"'),DB::raw("'".$this->tcl4_table."'".' as "tablename"'),'customers.company_name')
                               ->get() ;


          $pending['tcl5'] = DB::table('tesco_tcl5_orders')
                               ->join('customers','tesco_tcl5_orders.tesco_supplier_id','=','customers.id')
                               ->where('tesco_tcl5_orders.tesco_supplier_id', $customer_role_id->company_id)
                               ->where('order_status',"Order Placed")
                               ->where('pen_ord_flag',0)
                               ->where('is_order_count_empty',0)
                               ->whereBetween('tesco_tcl5_orders.created_at', [$from_date, $to_date])
                               ->select('tesco_tcl5_orders.*',DB::raw("'".$this->tcl5."'".' as "TemplateName"'),DB::raw("'".$this->tcl5_table."'".' as "tablename"'),'customers.company_name')
                               ->get() ;

          $pending['thl1'] = DB::table('tesco_thl1_orders')
                                ->join('customers','tesco_thl1_orders.tesco_supplier_id','=','customers.id')
                                ->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Placed")
                                ->where('pen_ord_flag',0)
                                ->where('is_order_count_empty',0)
                                ->whereBetween('tesco_thl1_orders.created_at', [$from_date, $to_date])
                                ->select('tesco_thl1_orders.*',DB::raw("'".$this->thl1."'".' as "TemplateName"'),DB::raw("'".$this->thl1_table."'".' as "tablename"'),'customers.company_name')
                                ->get() ;
          $pending['thl3'] = DB::table('tesco_thl3_orders')
                                ->join('customers','tesco_thl3_orders.tesco_supplier_id','=','customers.id')
                                ->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Placed")
                                ->where('pen_ord_flag',0)
                                ->where('is_order_count_empty',0)
                                ->whereBetween('tesco_thl3_orders.created_at', [$from_date, $to_date])
                                ->select('tesco_thl3_orders.*',DB::raw("'".$this->thl3."'".' as "TemplateName"'),DB::raw("'".$this->thl3_table."'".' as "tablename"'),'customers.company_name')
                                ->get() ;
          $pending['tsl1'] = DB::table('tesco_tsl1_orders')
                              ->join('customers','tesco_tsl1_orders.tesco_supplier_id','=','customers.id')
                              ->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Placed")
                              ->where('pen_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->whereBetween('tesco_tsl1_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_tsl1_orders.*',DB::raw("'".$this->tsl1."'".' as "TemplateName"'),DB::raw("'".$this->tsl1_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;

          $pending['tsl2'] = DB::table('tesco_tsl2_orders')
                              ->join('customers','tesco_tsl2_orders.tesco_supplier_id','=','customers.id')
                              ->where('tesco_tsl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Placed")
                              ->where('pen_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->whereBetween('tesco_tsl2_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_tsl2_orders.*',DB::raw("'".$this->tsl2."'".' as "TemplateName"'),DB::raw("'".$this->tsl2_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;

          $pending['tsl3'] = DB::table('tesco_tsl3_orders')
                              ->join('customers','tesco_tsl3_orders.tesco_supplier_id','=','customers.id')
                              ->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Placed")
                              ->where('pen_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->whereBetween('tesco_tsl3_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_tsl3_orders.*',DB::raw("'".$this->tsl3."'".' as "TemplateName"'),DB::raw("'".$this->tsl3_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;

          $pending['wcl'] = DB::table('tesco_washcare_orders')
                              ->join('customers','tesco_washcare_orders.tesco_supplier_id','=','customers.id')
                              ->join('tesco_template','tesco_washcare_orders.template_id','=','tesco_template.item_id')
                              ->where('tesco_washcare_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Placed")
                              ->where('pen_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->whereBetween('tesco_washcare_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_washcare_orders.*',DB::raw('tesco_template.template_name as "TemplateName"'),DB::raw("'".$this->wash_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;
        return $pending;
	}

	public function getPendingOrders($from_date, $to_date)
	{
		$customer_id = Auth::guard('web_customer')->id();
        $customer_role_id = DB::table('customer_profile')->select('*')
                              ->where('id',$customer_id)->first();

		      $pending['tcl1'] = DB::table('tesco_tcl1_orders')
                              ->join('customers','tesco_tcl1_orders.tesco_supplier_id','=','customers.id')
                              ->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)->where('order_status',"Order Placed")
                              ->where('pen_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->where('tesco_tcl1_orders.order_placed_by','=',$customer_id)
                              ->whereBetween('tesco_tcl1_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_tcl1_orders.*',DB::raw("'".$this->tcl1."'".' as "TemplateName"'),DB::raw("'".$this->tcl1_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;
          $pending['tcl2'] = DB::table('tesco_tcl2_orders')
                                ->join('customers','tesco_tcl2_orders.tesco_supplier_id','=','customers.id')
                                ->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Placed")
                                ->where('pen_ord_flag',0)
                                ->where('is_order_count_empty',0)
                                ->where('tesco_tcl2_orders.order_placed_by','=',$customer_id)
                                ->whereBetween('tesco_tcl2_orders.created_at', [$from_date, $to_date])
                                ->select('tesco_tcl2_orders.*',DB::raw("'".$this->tcl2."'".' as "TemplateName"'),DB::raw("'".$this->tcl2_table."'".' as "tablename"'),'customers.company_name')
                                ->get() ;
          $pending['tcl4'] = DB::table('tesco_tcl4_orders')
                              ->join('customers','tesco_tcl4_orders.tesco_supplier_id','=','customers.id')
                               ->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
                               ->where('order_status',"Order Placed")
                               ->where('pen_ord_flag',0)
                               ->where('is_order_count_empty',0)
                               ->where('tesco_tcl4_orders.order_placed_by','=',$customer_id)
                               ->whereBetween('tesco_tcl4_orders.created_at', [$from_date, $to_date])
                               ->select('tesco_tcl4_orders.*',DB::raw("'".$this->tcl4."'".' as "TemplateName"'),DB::raw("'".$this->tcl4_table."'".' as "tablename"'),'customers.company_name')
                               ->get() ;

          $pending['tcl5'] = DB::table('tesco_tcl5_orders')
                              ->join('customers','tesco_tcl5_orders.tesco_supplier_id','=','customers.id')
                               ->where('tesco_tcl5_orders.tesco_supplier_id', $customer_role_id->company_id)
                               ->where('order_status',"Order Placed")
                               ->where('pen_ord_flag',0)
                               ->where('is_order_count_empty',0)
                               ->where('tesco_tcl5_orders.order_placed_by','=',$customer_id)
                               ->whereBetween('tesco_tcl5_orders.created_at', [$from_date, $to_date])
                               ->select('tesco_tcl5_orders.*',DB::raw("'".$this->tcl5."'".' as "TemplateName"'),DB::raw("'".$this->tcl5_table."'".' as "tablename"'),'customers.company_name')
                               ->get() ;

          $pending['thl1'] = DB::table('tesco_thl1_orders')
                                ->join('customers','tesco_thl1_orders.tesco_supplier_id','=','customers.id')
                                ->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Placed")
                                ->where('pen_ord_flag',0)
                                ->where('is_order_count_empty',0)
                                ->where('tesco_thl1_orders.order_placed_by','=',$customer_id)
                                ->whereBetween('tesco_thl1_orders.created_at', [$from_date, $to_date])
                                ->select('tesco_thl1_orders.*',DB::raw("'".$this->thl1."'".' as "TemplateName"'),DB::raw("'".$this->thl1_table."'".' as "tablename"'),'customers.company_name')
                                ->get() ;
          $pending['thl3'] = DB::table('tesco_thl3_orders')
                                ->join('customers','tesco_thl3_orders.tesco_supplier_id','=','customers.id')
                                ->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Placed")
                                ->where('pen_ord_flag',0)
                                ->where('is_order_count_empty',0)
                                ->where('tesco_thl3_orders.order_placed_by','=',$customer_id)
                                ->whereBetween('tesco_thl3_orders.created_at', [$from_date, $to_date])
                                ->select('tesco_thl3_orders.*',DB::raw("'".$this->thl3."'".' as "TemplateName"'),DB::raw("'".$this->thl3_table."'".' as "tablename"'),'customers.company_name')
                                ->get() ;
          $pending['tsl1'] = DB::table('tesco_tsl1_orders')
                              ->join('customers','tesco_tsl1_orders.tesco_supplier_id','=','customers.id')
                              ->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Placed")
                              ->where('pen_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->where('tesco_tsl1_orders.order_placed_by','=',$customer_id)
                              ->whereBetween('tesco_tsl1_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_tsl1_orders.*',DB::raw("'".$this->tsl1."'".' as "TemplateName"'),DB::raw("'".$this->tsl1_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;

          $pending['tsl2'] = DB::table('tesco_tsl2_orders')
                              ->join('customers','tesco_tsl2_orders.tesco_supplier_id','=','customers.id')
                              ->where('tesco_tsl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Placed")
                              ->where('pen_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->where('tesco_tsl2_orders.order_placed_by','=',$customer_id)
                              ->whereBetween('tesco_tsl2_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_tsl2_orders.*',DB::raw("'".$this->tsl2."'".' as "TemplateName"'),DB::raw("'".$this->tsl2_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;

          $pending['tsl3'] = DB::table('tesco_tsl3_orders')
                              ->join('customers','tesco_tsl3_orders.tesco_supplier_id','=','customers.id')
                              ->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Placed")
                              ->where('pen_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->where('tesco_tsl3_orders.order_placed_by','=',$customer_id)
                              ->whereBetween('tesco_tsl3_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_tsl3_orders.*',DB::raw("'".$this->tsl3."'".' as "TemplateName"'),DB::raw("'".$this->tsl3_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;

          $pending['wcl'] = DB::table('tesco_washcare_orders')
                              ->join('customers','tesco_washcare_orders.tesco_supplier_id','=','customers.id')
                              ->join('tesco_template','tesco_washcare_orders.template_id','=','tesco_template.item_id')
                              ->where('tesco_washcare_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Placed")
                              ->where('pen_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->where('tesco_washcare_orders.order_placed_by','=',$customer_id)
                              ->whereBetween('tesco_washcare_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_washcare_orders.*',DB::raw('tesco_template.template_name as "TemplateName"'),DB::raw("'".$this->wash_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;
        return $pending;
	}


	public function getAllCancelledOrders($from_date, $to_date)
	{
		$customer_id = Auth::guard('web_customer')->id();
        $customer_role_id = DB::table('customer_profile')->select('*')
                              ->where('id',$customer_id)->first();
        $cancelled['tcl1'] = DB::table('tesco_tcl1_orders')
                              ->join('customers','tesco_tcl1_orders.tesco_supplier_id','=','customers.id')
                              ->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)->where('order_status',"Order Cancelled")
                              ->where('cancel_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->whereBetween('tesco_tcl1_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_tcl1_orders.*',DB::raw("'".$this->tcl1."'".' as "TemplateName"'),DB::raw("'".$this->tcl1_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;
        $cancelled['tcl2'] = DB::table('tesco_tcl2_orders')
           ->join('customers','tesco_tcl2_orders.tesco_supplier_id','=','customers.id')
                                ->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Cancelled")
                                ->where('cancel_ord_flag',0)
                                ->where('is_order_count_empty',0)
                                ->whereBetween('tesco_tcl2_orders.created_at', [$from_date, $to_date])
                                ->select('tesco_tcl2_orders.*',DB::raw("'".$this->tcl2."'".' as "TemplateName"'),DB::raw("'".$this->tcl2_table."'".' as "tablename"'),'customers.company_name')
                                ->get() ;
        $cancelled['tcl4'] = DB::table('tesco_tcl4_orders')
                               ->join('customers','tesco_tcl4_orders.tesco_supplier_id','=','customers.id')
                               ->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
                               ->where('order_status',"Order Cancelled")
                               ->where('cancel_ord_flag',0)
                               ->where('is_order_count_empty',0)
                               ->whereBetween('tesco_tcl4_orders.created_at', [$from_date, $to_date])
                               ->select('tesco_tcl4_orders.*',DB::raw("'".$this->tcl4."'".' as "TemplateName"'),DB::raw("'".$this->tcl4_table."'".' as "tablename"'),'customers.company_name')
                               ->get() ;

        $cancelled['tcl5'] = DB::table('tesco_tcl5_orders')
                               ->join('customers','tesco_tcl5_orders.tesco_supplier_id','=','customers.id')
                               ->where('tesco_tcl5_orders.tesco_supplier_id', $customer_role_id->company_id)
                               ->where('order_status',"Order Cancelled")
                               ->where('cancel_ord_flag',0)
                               ->where('is_order_count_empty',0)
                               ->whereBetween('tesco_tcl5_orders.created_at', [$from_date, $to_date])
                               ->select('tesco_tcl5_orders.*',DB::raw("'".$this->tcl5."'".' as "TemplateName"'),DB::raw("'".$this->tcl5_table."'".' as "tablename"'),'customers.company_name')
                               ->get() ;

        $cancelled['thl1'] = DB::table('tesco_thl1_orders')
                                ->join('customers','tesco_thl1_orders.tesco_supplier_id','=','customers.id')
                                ->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Cancelled")
                                ->where('cancel_ord_flag',0)
                                ->where('is_order_count_empty',0)
                                ->whereBetween('tesco_thl1_orders.created_at', [$from_date, $to_date])
                                ->select('tesco_thl1_orders.*',DB::raw("'".$this->thl1."'".' as "TemplateName"'),DB::raw("'".$this->thl1_table."'".' as "tablename"'),'customers.company_name')
                                ->get() ;
        $cancelled['thl3'] = DB::table('tesco_thl3_orders')
                                ->join('customers','tesco_thl3_orders.tesco_supplier_id','=','customers.id')
                                ->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Cancelled")
                                ->where('cancel_ord_flag',0)
                                ->where('is_order_count_empty',0)
                                ->whereBetween('tesco_thl3_orders.created_at', [$from_date, $to_date])
                                ->select('tesco_thl3_orders.*',DB::raw("'".$this->thl3."'".' as "TemplateName"'),DB::raw("'".$this->thl3_table."'".' as "tablename"'),'customers.company_name')
                                ->get() ;
        $cancelled['tsl1'] = DB::table('tesco_tsl1_orders')
                               ->join('customers','tesco_tsl1_orders.tesco_supplier_id','=','customers.id')
                              ->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Cancelled")
                              ->where('cancel_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->whereBetween('tesco_tsl1_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_tsl1_orders.*',DB::raw("'".$this->tsl1."'".' as "TemplateName"'),DB::raw("'".$this->tsl1_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;

        $cancelled['tsl2'] = DB::table('tesco_tsl2_orders')
                               ->join('customers','tesco_tsl2_orders.tesco_supplier_id','=','customers.id')
                              ->where('tesco_tsl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Cancelled")
                              ->where('cancel_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->whereBetween('tesco_tsl2_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_tsl2_orders.*',DB::raw("'".$this->tsl2."'".' as "TemplateName"'),DB::raw("'".$this->tsl2_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;

        $cancelled['tsl3'] = DB::table('tesco_tsl3_orders')
                              ->join('customers','tesco_tsl3_orders.tesco_supplier_id','=','customers.id')
                              ->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Cancelled")
                              ->where('cancel_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->whereBetween('tesco_tsl3_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_tsl3_orders.*',DB::raw("'".$this->tsl3."'".' as "TemplateName"'),DB::raw("'".$this->tsl3_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;  
                              
                              
        $cancelled['wcl'] = DB::table('tesco_washcare_orders')
                              ->join('customers','tesco_washcare_orders.tesco_supplier_id','=','customers.id')
                              ->join('tesco_template','tesco_washcare_orders.template_id','=','tesco_template.item_id')
                              ->where('tesco_washcare_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Cancelled")
                              ->where('cancel_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->whereBetween('tesco_washcare_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_washcare_orders.*',DB::raw('tesco_template.template_name as "TemplateName"'),DB::raw("'".$this->wash_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ; 
        return $cancelled;
	}

	public function getCancelledOrders($from_date, $to_date)
	{
		$customer_id = Auth::guard('web_customer')->id();
        $customer_role_id = DB::table('customer_profile')->select('*')
                              ->where('id',$customer_id)->first();
        $cancelled['tcl1'] = DB::table('tesco_tcl1_orders')
                              ->join('customers','tesco_tcl1_orders.tesco_supplier_id','=','customers.id')
                              ->where('tesco_tcl1_orders.tesco_supplier_id', $customer_role_id->company_id)->where('order_status',"Order Cancelled")
                              ->where('cancel_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->where('tesco_tcl1_orders.order_placed_by','=',$customer_id)
                              ->whereBetween('tesco_tcl1_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_tcl1_orders.*',DB::raw("'".$this->tcl1."'".' as "TemplateName"'),DB::raw("'".$this->tcl1_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;
        $cancelled['tcl2'] = DB::table('tesco_tcl2_orders')
                                ->join('customers','tesco_tcl2_orders.tesco_supplier_id','=','customers.id')
                                ->where('tesco_tcl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Cancelled")
                                ->where('cancel_ord_flag',0)
                                ->where('is_order_count_empty',0)
                                ->where('tesco_tcl2_orders.order_placed_by','=',$customer_id)
                                ->whereBetween('tesco_tcl2_orders.created_at', [$from_date, $to_date])
                                ->select('tesco_tcl2_orders.*',DB::raw("'".$this->tcl2."'".' as "TemplateName"'),DB::raw("'".$this->tcl2_table."'".' as "tablename"'),'customers.company_name')
                                ->get() ;
        $cancelled['tcl4'] = DB::table('tesco_tcl4_orders')
                                ->join('customers','tesco_tcl4_orders.tesco_supplier_id','=','customers.id')
                               ->where('tesco_tcl4_orders.tesco_supplier_id', $customer_role_id->company_id)
                               ->where('order_status',"Order Cancelled")
                               ->where('cancel_ord_flag',0)
                               ->where('is_order_count_empty',0)
                               ->where('tesco_tcl4_orders.order_placed_by','=',$customer_id)
                               ->whereBetween('tesco_tcl4_orders.created_at', [$from_date, $to_date])
                               ->select('tesco_tcl4_orders.*',DB::raw("'".$this->tcl4."'".' as "TemplateName"'),DB::raw("'".$this->tcl4_table."'".' as "tablename"'),'customers.company_name')
                               ->get() ;

        $cancelled['tcl5'] = DB::table('tesco_tcl5_orders')
                                ->join('customers','tesco_tcl5_orders.tesco_supplier_id','=','customers.id')
                               ->where('tesco_tcl5_orders.tesco_supplier_id', $customer_role_id->company_id)
                               ->where('order_status',"Order Cancelled")
                               ->where('cancel_ord_flag',0)
                               ->where('is_order_count_empty',0)
                               ->where('tesco_tcl5_orders.order_placed_by','=',$customer_id)
                               ->whereBetween('tesco_tcl5_orders.created_at', [$from_date, $to_date])
                               ->select('tesco_tcl5_orders.*',DB::raw("'".$this->tcl5."'".' as "TemplateName"'),DB::raw("'".$this->tcl5_table."'".' as "tablename"'),'customers.company_name')
                               ->get() ;

        $cancelled['thl1'] = DB::table('tesco_thl1_orders')
                                 ->join('customers','tesco_thl1_orders.tesco_supplier_id','=','customers.id')
                                ->where('tesco_thl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Cancelled")
                                ->where('cancel_ord_flag',0)
                                ->where('is_order_count_empty',0)
                                ->where('tesco_thl1_orders.order_placed_by','=',$customer_id)
                                ->whereBetween('tesco_thl1_orders.created_at', [$from_date, $to_date])
                                ->select('tesco_thl1_orders.*',DB::raw("'".$this->thl1."'".' as "TemplateName"'),DB::raw("'".$this->thl1_table."'".' as "tablename"'),'customers.company_name')
                                ->get() ;
        $cancelled['thl3'] = DB::table('tesco_thl3_orders')
                                 ->join('customers','tesco_thl3_orders.tesco_supplier_id','=','customers.id')
                                ->where('tesco_thl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                                ->where('order_status',"Order Cancelled")
                                ->where('cancel_ord_flag',0)
                                ->where('is_order_count_empty',0)
                                ->where('tesco_thl3_orders.order_placed_by','=',$customer_id)
                                ->whereBetween('tesco_thl3_orders.created_at', [$from_date, $to_date])
                                ->select('tesco_thl3_orders.*',DB::raw("'".$this->thl3."'".' as "TemplateName"'),DB::raw("'".$this->thl3_table."'".' as "tablename"'),'customers.company_name')
                                ->get() ;
        $cancelled['tsl1'] = DB::table('tesco_tsl1_orders')
                               ->join('customers','tesco_tsl1_orders.tesco_supplier_id','=','customers.id')
                              ->where('tesco_tsl1_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Cancelled")
                              ->where('cancel_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->where('tesco_tsl1_orders.order_placed_by','=',$customer_id)
                              ->whereBetween('tesco_tsl1_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_tsl1_orders.*',DB::raw("'".$this->tsl1."'".' as "TemplateName"'),DB::raw("'".$this->tsl1_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;


        $cancelled['tsl2'] = DB::table('tesco_tsl2_orders')
                               ->join('customers','tesco_tsl2_orders.tesco_supplier_id','=','customers.id')
                              ->where('tesco_tsl2_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Cancelled")
                              ->where('cancel_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->where('tesco_tsl2_orders.order_placed_by','=',$customer_id)
                              ->whereBetween('tesco_tsl2_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_tsl2_orders.*',DB::raw("'".$this->tsl2."'".' as "TemplateName"'),DB::raw("'".$this->tsl2_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;

        $cancelled['tsl3'] = DB::table('tesco_tsl3_orders')
                               ->join('customers','tesco_tsl3_orders.tesco_supplier_id','=','customers.id')
                              ->where('tesco_tsl3_orders.tesco_supplier_id', $customer_role_id->company_id)
                              ->where('order_status',"Order Cancelled")
                              ->where('cancel_ord_flag',0)
                              ->where('is_order_count_empty',0)
                              ->where('tesco_tsl3_orders.order_placed_by','=',$customer_id)
                              ->whereBetween('tesco_tsl3_orders.created_at', [$from_date, $to_date])
                              ->select('tesco_tsl3_orders.*',DB::raw("'".$this->tsl3."'".' as "TemplateName"'),DB::raw("'".$this->tsl3_table."'".' as "tablename"'),'customers.company_name')
                              ->get() ;    


        $cancelled['wcl'] = DB::table('tesco_washcare_orders')
                              ->join('customers','tesco_washcare_orders.tesco_supplier_id','=','customers.id')
                              ->join('tesco_template','tesco_washcare_orders.template_id','=','tesco_template.item_id')
                             ->where('tesco_washcare_orders.tesco_supplier_id', $customer_role_id->company_id)
                             ->where('order_status',"Order Cancelled")
                             ->where('cancel_ord_flag',0)
                             ->where('is_order_count_empty',0)
                             ->where('tesco_washcare_orders.order_placed_by','=',$customer_id)
                             ->whereBetween('tesco_washcare_orders.created_at', [$from_date, $to_date])
                             ->select('tesco_washcare_orders.*',DB::raw('tesco_template.template_name as "TemplateName"'),DB::raw("'".$this->wash_table."'".' as "tablename"'),'customers.company_name')
                             ->get() ;                   
        return $cancelled;
	}

  public function getApprovedOrdersCount($approved_orders)
  {
    return count($approved_orders['tcl1'])+count($approved_orders['tcl2'])+count($approved_orders['tcl4'])+count($approved_orders['tcl5'])+count($approved_orders['thl1'])+count($approved_orders['thl3'])+count($approved_orders['tsl1'])+count($approved_orders['tsl2']) + count($approved_orders['tsl3']) + count($approved_orders['wcl']);
  }
  
  public function getPendingOrdersCount($app_pending_orders)
  {
    return count($app_pending_orders['tcl1'])+count($app_pending_orders['tcl2'])+count($app_pending_orders['tcl4'])+count($app_pending_orders['tcl5'])+count($app_pending_orders['thl1'])+count($app_pending_orders['thl3'])+count($app_pending_orders['tsl1'])+count($app_pending_orders['tsl2']) + count($app_pending_orders['tsl3']) + count($app_pending_orders['wcl']);
  }

  public function getCancelledOrdersCount($cancelled_orders)
  {
    return count($cancelled_orders['tcl1'])+count($cancelled_orders['tcl2'])+count($cancelled_orders['tcl4'])+count($cancelled_orders['tcl5'])+count($cancelled_orders['thl1'])+count($cancelled_orders['thl3'])+count($cancelled_orders['tsl1'])+count($cancelled_orders['tsl2']) + count($cancelled_orders['tsl3']) + count($cancelled_orders['wcl']);
  }
}