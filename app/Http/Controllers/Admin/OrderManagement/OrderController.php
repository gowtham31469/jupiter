<?php

namespace App\Http\Controllers\Admin\OrderManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Modules;
use Response;
use View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;
use PDF;
use App\Classes\Dashboard;
use Carbon\Carbon;
use App\FinancialYearResetDate;
use Session;
use App\Classes\TescoCareMetadata;

class OrderController extends Controller
{
	private $current_date;
	private $year;
	private $financial_year_date;
	private $financial_year_start;
	private $financial_year_end;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
	   $this->middleware('auth');
	   // get the current time
		$this->current_date = Carbon::now('Asia/Kolkata')->toDateTimeString();

		// get year as "2020"
		$this->year = Carbon::parse($this->current_date)->format('Y');

		$this->financial_year_date = FinancialYearResetDate::value('financial_year_date');

		// format financial year date as "yyyy-dd-mm" 
		$this->financial_year_date_format = $this->year.'-'.$this->financial_year_date;

		// get the current date in "dd-mm-yyyy" format
		$this->today_date = Carbon::now('Asia/Kolkata')->format('Y-m-d');
		
		if($this->today_date < $this->financial_year_date_format){
			$this->financial_year_start = Carbon::parse($this->financial_year_date_format)->subYear()->toDateString();
			$this->financial_year_end = Carbon::parse($this->financial_year_date_format)->subDay()->toDateString();
		} else {
			$this->financial_year_start = $this->financial_year_date_format;
			$this->financial_year_end = Carbon::parse($this->financial_year_date_format)->addYear()->subDay()->toDateString();
		}

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Admin/login');
    }

    public function dashboard()
    {
        $module_var = new Modules();
        $is_allowed = $module_var->permittedModules();
        if($is_allowed)
        {            
            $get_main_module = $module_var->getMainModules();
            $get_sub_module = $module_var->getSubModules(9);
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
            });
            
            $buyers = DB::table('buyers')->select('*')->where('is_active','=',1)->get();
			$buyer_count = count($buyers);
			
			if(Session::get('fiscal-year') != null) {
				$year = explode('-', Session::get('fiscal-year'));
				$from_date = $year[0].'-'.$this->financial_year_date;
				$to_date =Carbon::parse($from_date)->addYear()->subDay()->toDateString(); 
			} else {
				$from_date = $this->financial_year_start;
				$to_date =$this->financial_year_end; 
			}
			
			$new_count['tcl1'] = DB::table('tesco_tcl1_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->where('order_status','!=','Order Cancelled')
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tcl1_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			$new_count['tcl2'] = DB::table('tesco_tcl2_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->where('order_status','!=','Order Cancelled')
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tcl2_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			$new_count['tcl4'] = DB::table('tesco_tcl4_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tcl4_orders.created_at', [$from_date, $to_date])
									->where('order_status','!=','Order Cancelled')
									->select(DB::raw('count(*) as sum'))
									->get();
			$new_count['thl1'] = DB::table('tesco_thl1_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_thl1_orders.created_at', [$from_date, $to_date])
									->where('order_status','!=','Order Cancelled')
									->select(DB::raw('count(*) as sum'))
									->get();
			$new_count['thl3'] = DB::table('tesco_thl3_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_thl3_orders.created_at', [$from_date, $to_date])
									->where('order_status','!=','Order Cancelled')
									->select(DB::raw('count(*) as sum'))
									->get();
			$new_count['tsl1'] = DB::table('tesco_tsl1_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tsl1_orders.created_at', [$from_date, $to_date])
									->where('order_status','!=','Order Cancelled')
									->select(DB::raw('count(*) as sum'))
									->get();
			$new_count['tsl3'] = DB::table('tesco_tsl3_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tsl3_orders.created_at', [$from_date, $to_date])
									->where('order_status','!=','Order Cancelled')
									->select(DB::raw('count(*) as sum'))
									->get();

			$new_count['wcl'] = DB::table('tesco_washcare_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_washcare_orders.created_at', [$from_date, $to_date])
									->where('order_status','!=','Order Cancelled')
									->select(DB::raw('count(*) as sum'))
									->get();
			
			$tot_count = $new_count['tcl1']{0}->sum + $new_count['tcl2']{0}->sum + $new_count['tcl4']{0}->sum + $new_count['thl1']{0}->sum + $new_count['thl3']{0}->sum + $new_count['tsl1']{0}->sum + $new_count['tsl3']{0}->sum + $new_count['wcl']{0}->sum;
			
			
			$approved_orders['tcl1'] = DB::table('tesco_tcl1_orders')
									->where('order_status','Order Approved')
									->where('app_ord_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tcl1_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			$approved_orders['tcl2'] = DB::table('tesco_tcl2_orders')
									->where('order_status','Order Approved')
									->where('app_ord_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tcl2_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			$approved_orders['tcl4'] = DB::table('tesco_tcl4_orders')
									->where('order_status','Order Approved')
									->where('app_ord_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tcl4_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			$approved_orders['thl1'] = DB::table('tesco_thl1_orders')
									->where('order_status','Order Approved')
									->where('app_ord_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_thl1_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			$approved_orders['thl3'] = DB::table('tesco_thl3_orders')
									->where('order_status','Order Approved')
									->where('app_ord_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_thl3_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			$approved_orders['tsl1'] = DB::table('tesco_tsl1_orders')
									->where('order_status','Order Approved')
									->where('app_ord_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tsl1_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			$approved_orders['tsl3'] = DB::table('tesco_tsl3_orders')
									->where('order_status','Order Approved')
									->where('app_ord_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tsl3_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();

			$approved_orders['wcl'] = DB::table('tesco_washcare_orders')
									->where('order_status','Order Approved')
									->where('app_ord_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_washcare_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			
			$approve_count = $approved_orders['tcl1']{0}->sum + $approved_orders['tcl2']{0}->sum + $approved_orders['tcl4']{0}->sum + $approved_orders['thl1']{0}->sum + $approved_orders['thl3']{0}->sum + $approved_orders['tsl1']{0}->sum + $approved_orders['tsl3']{0}->sum + $approved_orders['wcl']{0}->sum;
			
			$pending_orders['tcl1'] = DB::table('tesco_tcl1_orders')
									->where('order_status','Order Placed')
									->where('pen_ord_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tcl1_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			$pending_orders['tcl2'] = DB::table('tesco_tcl2_orders')
									->where('order_status','Order Placed')
									->where('pen_ord_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tcl2_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			$pending_orders['tcl4'] = DB::table('tesco_tcl4_orders')
									->where('order_status','Order Placed')
									->where('pen_ord_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tcl4_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			$pending_orders['thl1'] = DB::table('tesco_thl1_orders')
									->where('order_status','Order Placed')
									->where('pen_ord_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_thl1_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			$pending_orders['thl3'] = DB::table('tesco_thl3_orders')
									->where('order_status','Order Placed')
									->where('pen_ord_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_thl3_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			$pending_orders['tsl1'] = DB::table('tesco_tsl1_orders')
									->where('order_status','Order Placed')
									->where('pen_ord_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tsl1_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			$pending_orders['tsl3'] = DB::table('tesco_tsl3_orders')
									->where('order_status','Order Placed')
									->where('pen_ord_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tsl3_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();

			$pending_orders['wcl'] = DB::table('tesco_washcare_orders')
									->where('order_status','Order Placed')
									->where('pen_ord_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_washcare_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			
			$pending_count = $pending_orders['tcl1']{0}->sum + $pending_orders['tcl2']{0}->sum + $pending_orders['tcl4']{0}->sum + $pending_orders['thl1']{0}->sum + $pending_orders['thl3']{0}->sum + $pending_orders['tsl1']{0}->sum + $pending_orders['tsl3']{0}->sum + $pending_orders['wcl']{0}->sum;
			
			
			$app_pipo1['tcl1'] = DB::table('tesco_tcl1_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tcl1_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			
			$app_pipo2['tcl1'] = DB::table('tesco_tcl1_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tcl1_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			
			$app_pipo1['tcl2'] = DB::table('tesco_tcl2_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tcl2_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			
			$app_pipo2['tcl2'] = DB::table('tesco_tcl2_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tcl2_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			
			$app_pipo1['tcl4'] = DB::table('tesco_tcl4_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tcl4_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			
			$app_pipo2['tcl4'] = DB::table('tesco_tcl4_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tcl4_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			
			$app_pipo1['thl1'] = DB::table('tesco_thl1_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_thl1_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			
			$app_pipo2['thl1'] = DB::table('tesco_thl1_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_thl1_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			
			$app_pipo1['thl3'] = DB::table('tesco_thl3_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_thl3_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			
			$app_pipo2['thl3'] = DB::table('tesco_thl3_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_thl3_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			$app_pipo1['tsl1'] = DB::table('tesco_tsl1_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tsl1_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			
			$app_pipo2['tsl1'] = DB::table('tesco_tsl1_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tsl1_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			
			$app_pipo1['tsl3'] = DB::table('tesco_tsl3_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tsl3_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			
			$app_pipo2['tsl3'] = DB::table('tesco_tsl3_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tsl3_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();

			$app_pipo1['wcl'] = DB::table('tesco_washcare_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_washcare_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			
			$app_pipo2['wcl'] = DB::table('tesco_washcare_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->where('app_pipo_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_washcare_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			
			
			$app_pipo = $app_pipo1['tcl1']{0}->sum + $app_pipo1['tcl2']{0}->sum + $app_pipo1['tcl4']{0}->sum + $app_pipo1['thl1']{0}->sum + $app_pipo1['thl3']{0}->sum + $app_pipo1['tsl1']{0}->sum + $app_pipo1['tsl3']{0}->sum + $app_pipo2['tcl1']{0}->sum + $app_pipo2['tcl2']{0}->sum + $app_pipo2['tcl4']{0}->sum + $app_pipo2['thl1']{0}->sum + $app_pipo2['thl3']{0}->sum + $app_pipo2['tsl1']{0}->sum + $app_pipo2['tsl3']{0}->sum + $app_pipo1['wcl']{0}->sum + $app_pipo2['wcl']{0}->sum;
			
			$pending_class    = new Dashboard();

            $pendingproduction      = $pending_class->pendingProduction($from_date, $to_date);
                
			
			
		
          return view('Admin/Order Management/dashboard',['buyers' => $buyers,'buyer_count' => $buyer_count,'new_orders'=>$tot_count,'approved_pipo'=>$app_pipo,'approved_orders'=>$approve_count,'pending_orders'=>$pending_count,'pendingproduction'=>$pendingproduction]);
        }
        else
        {
             return redirect('/admin/dashboard');
        }

    }
	
	
	
	 /**
	 * Show the Order dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	
    public function orders(TescoCareMetadata $caremeta)
    {
        $module_var = new Modules();
        $is_allowed = $module_var->permittedModules();
        if($is_allowed)
        {
            $get_main_module = $module_var->getMainModules();
            $get_sub_module = $module_var->getSubModules(9);
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
            });
            
            $buyers = DB::table('buyers')->select('*')->where('artwork_flag','=',1)->where('is_active','=',1)->get();
            $buyer_count = count($buyers);

            $symbols = $caremeta->getCareMetadata()['symbols'];

            $coo = $caremeta->getCareMetadata()['coo'];

            $kids_warning = $caremeta->getCareMetadata()['kids_warning'];

            $components = $caremeta->getCareMetadata()['components'];

            $fabriclists = $caremeta->getCareMetadata()['fabriclists'];

            $tesco_suppliers = $caremeta->getCareMetadata()['tesco_suppliers'];

            $additional_instructions = $caremeta->getCareMetadata()['additional_instructions'];

            return view('Admin/Order Management/orders',['buyers' => $buyers,
            	'buyer_count' => $buyer_count,'symbols' => $symbols,
            	'tesco_suppliers' => $tesco_suppliers,'coo' => $coo,
            	'kids_warning' => $kids_warning,'components' => $components,
            	'fabriclists' => $fabriclists,
                'additional_instructions' => $additional_instructions]);
        }
        else
        {
             return redirect('/admin/dashboard');
        }

    }    
	
	 /**
	 * Show the Order dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	
    public function nonArtworkOrders()
    {
        $module_var = new Modules();
        $is_allowed = $module_var->permittedModules();
        if($is_allowed)
        {
            $get_main_module = $module_var->getMainModules();
            $get_sub_module = $module_var->getSubModules(9);
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
            });
            
            $buyers = DB::table('buyers')->select('*')->where('artwork_flag','=',1)->where('is_active','=',1)->get();
			
			$orders = DB::table('non_artwork_orders')->select('*')->get();
			
            $buyer_count = count($buyers);
            return view('Admin/Order Management/non-art-orders',['buyers' => $buyers,'buyer_count' => $buyer_count,'orders' => $orders]);
        }
        else
        {
             return redirect('/admin/dashboard');
        }

    }   
	
	 /**
	 * Show the Order Status dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	
    public function orderStatus()
    {
        $module_var = new Modules();
        $is_allowed = $module_var->permittedModules();
        if($is_allowed)
        {
            $get_main_module = $module_var->getMainModules();
            $get_sub_module = $module_var->getSubModules(9);
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
            });
            $tesco_brand = DB::table('tesco_brand')->select('brand','id')->get();
            $tesco_department = DB::table('tesco_department')->select('department','id')->get();
            $tesco_suppliers = DB::table('tesco_suppliers')->select('tesco_suppliers.supplier_name','tesco_suppliers.id')->join('customer_metadata','customer_metadata.customer_metadata_id','=','tesco_suppliers.id')->where('is_enabled','=',1)->get();
            $buyers = DB::table('buyers')->select('*')->where('is_active','=',1)->where('artwork_flag','=',1)->get();
            $buyer_count = count($buyers);
            return view('Admin/Order Management/order-status',['buyers' => $buyers,'tesco_brand' => $tesco_brand,'tesco_department' => $tesco_department,'tesco_suppliers' => $tesco_suppliers,'buyer_count' => $buyer_count]);
        }
        else
        {
             return redirect('/admin/dashboard');
        }

    }    
	
	 /**
	 * Show the Order Status dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	
    public function reorder()
    {
        $module_var = new Modules();
        $is_allowed = $module_var->permittedModules();
        if($is_allowed)
        {
            $get_main_module = $module_var->getMainModules();
            $get_sub_module = $module_var->getSubModules(9);
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
            });
            
            $buyers = DB::table('buyers')->select('*')->where('is_active','=',1)->get();
            $buyer_count = count($buyers);
            return view('Admin/Order Management/reorder',['buyers' => $buyers,'buyer_count' => $buyer_count]);
        }
        else
        {
             return redirect('/admin/dashboard');
        }

    }  
	
	/**
	 * Print Preview For 150 * 100 Size Stickers
	 *
	 * @return pdf
	 */
	
    public function printPdf(Request $request)
    {
		 $name=$request->name.'-'.$request->buyer_no;
         $svg=urldecode($request->svg);
		
		 $id = $request->id;
		 $customer = DB::table("orchestra_standard_carton_sticker_orders")
						->join('customers', 'orchestra_standard_carton_sticker_orders.customer_id', 'customers.id')
						->select('customers.short_name','orchestra_standard_carton_sticker_orders.created_at')
						->where('orchestra_standard_carton_sticker_orders.id',$id)
						->get();
		 $customer_name = $customer{0}->short_name;
		 $this->customer_name = $customer_name;
		
		 $this->created_date = $customer{0}->created_at;
		
		 $this->template_name = "STD-CARTON";
		 $this->template_zone = "";
	     $this->template_desc = "";
		 $this->tesco_po_number = $request->buyer_no;
		 $this->style = $request->style_no;
	   
		 define ('PDF_FONT_NAME_MAIN', 'calibri');
		 define ('PDF_FONT_NAME_DATA', 'calibri');
			// Custom Header
		  PDF::setHeaderCallback(function($pdf) {
			$pdf->SetY(0);
		  $name=$this->template_name;
		  $zone=$this->template_zone;
			$tesco_po_number=$this->tesco_po_number;
			$style=$this->style;
			$tesco_description=$this->template_desc;
			$date=date_create($this->created_date);
			$created_date =  date_format($date,"d.m.Y");
			$image_file= K_PATH_IMAGES.'logo_example.jpg';
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="50.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal" style="&#10;    /* border-radius: 27px; */&#10;">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L666,65 L666,66 L499,66 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L500,65 L500,144 L499,144 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L821,65 L821,66 L665,66 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L969,65 L969,66 L820,66 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,65 L969,65 L969,105 L968,105 L968,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,104 L969,104 L969,144 L968,144 L968,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,182 L821,182 L821,183 L499,183 L499,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L500,143 L500,183 L499,183 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,182 L969,182 L969,183 L820,183 L820,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,143 L969,143 L969,183 L968,183 L968,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L666,143 L666,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,144 L665,144 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,105 L665,105 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L666,104 L666,143 L665,143 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,143 L820,143 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,144 L820,144 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L821,143 L821,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L1016,212 L1016,213 L8,213 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,213 L1016,213 L1016,214 L8,214 L8,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L8,214 L9,213 L9,212 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,213 L1015,214 L1016,214 L1016,212 L1015,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L1016,216 L1016,217 L8,217 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,217 L1016,217 L1016,218 L8,218 L8,217"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L8,218 L9,217 L9,216 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,217 L1015,218 L1016,218 L1016,216 L1015,217"/>
					</g>
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="56cm" height="37cm" version="1.1">
							<image xlink:href="'.public_path('/images/jupiter.jpg').'" x="174" y="66" height="65px" width="116px" style=""/>
					</svg>
					<g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Jupiter Garments Accessories</text>

					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">STYLE : '.$style.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REF : '.$name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DATE : '.$created_date.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Size: 150x100mm</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DESCRIPTION : '.$tesco_description.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REV.NO :</text>
					</g>
					</g>
					</svg>';
					$pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			});
	   
	   
			// Custom Footer
			PDF::setFooterCallback(function($pdf) {
			$tesco_po_number=$this->tesco_po_number;
			$customer_name=$this->customer_name;
			// Position at 15 mm from bottom
			$pdf->SetY(-50);
			$pdf->SetX(9.5);
			// Set font
			//$pdf->SetFont('frankgothcnbt');
			// Page number
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188mm" height="65.2667mm" viewBox="0 0 1360 168" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L1352,52 L1352,53 L8,53 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,53 L1352,53 L1352,54 L8,54 L8,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L8,54 L9,53 L9,52 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,53 L1351,54 L1352,54 L1352,52 L1351,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L1352,56 L1352,57 L8,57 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,57 L1352,57 L1352,58 L8,58 L8,57"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L8,58 L9,57 L9,56 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,57 L1351,58 L1352,58 L1352,56 L1351,57"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M385,79 L386,79 L386,157 L385,157 L385,79"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M764,79 L765,79 L765,157 L764,157 L764,79"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">CUSTOMER : '.$customer_name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">APPROVED BY : </text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">COMMENTS IF ANY :</text>
					</g>
					</g>
					</svg>';
					
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		});
		
//          print_r($svg);
        PDF::setImageScale(1);
        PDF::SetFont('calibrib');
        PDF::AddPage();
		$style6 = array('width' => 0.353, 'cap' => '', 'join' => '', 'dash' => '', 'color' => '');
        //PDF::RoundedRect(95, 255, 40, 30, 0.1, '1111', null, $style6);
        //PDF::RoundedRect(140, 255, 40, 30, 0.1, '1111', 'null', $style6);
        PDF::RoundedRect(10, 47.5, 150, 100, 0.000001, '1111', null, $style6);
		PDF::RoundedRect(12.5, 50, 145, 95, 0.000001, '1111', null, $style6);
		PDF::ImageSVG('@' . $svg, $x=12.5, $y=50, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
        PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
        print_r(url('/storage/app/template/'.$name.'.pdf'));
    } 
	
	/**
	 * Print Preview For 150 * 100 Size Stickers
	 *
	 * @return pdf
	 */
	
    public function proofPdf(Request $request)
    {
		 $name=$request->name;
         $svg=urldecode($request->svg);
         define ('PDF_FONT_NAME_MAIN', 'calibrib');
         define ('PDF_FONT_NAME_DATA', 'calibrib');
//          print_r($svg);
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
		PDF::SetAutoPageBreak(TRUE);
        PDF::SetFont('calibrib');
		PDF::SetMargins(0, 0, 0, false);
        PDF::AddPage('L',array(324,465));
		PDF::setImageScale(1.66);
		$style6 = array('width' => 0.353, 'cap' => '', 'join' => '', 'dash' => '', 'color' => '');
        //PDF::RoundedRect(95, 255, 40, 30, 0.1, '1111', null, $style6);
        //PDF::RoundedRect(140, 255, 40, 30, 0.1, '1111', 'null', $style6);
		
		//Top Left Corner
		PDF::Line(7, 6, 7, 12, $style6);
		PDF::Line(1, 12, 7, 12, $style6);
		
		//Bottom Left Corner
		PDF::Line(7, 313, 7, 319, $style6);
		PDF::Line(1, 313, 7, 313, $style6);
		
		//Top Right Corner
		PDF::Line(458, 6, 458, 12, $style6);
		PDF::Line(458, 12, 464, 12, $style6);
		
		//Bottom Right Corner
		PDF::Line(458, 313, 464, 313, $style6);
		PDF::Line(458, 313, 458, 319, $style6);
		
		PDF::RoundedRect(10, 15, 145, 95, 0.000001, '1111', null, $style6);
        PDF::ImageSVG('@' . $svg, $x=10, $y=15, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		PDF::Line(157.5, 6, 157.5, 12, $style6);
		PDF::Line(157.5, 313, 157.5, 319, $style6);
		
		PDF::RoundedRect(160, 15, 145, 95, 0.000001, '1111', null, $style6);
        PDF::ImageSVG('@' . $svg, $x=160, $y=15, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		PDF::Line(307.5, 6, 307.5, 12, $style6);
		PDF::Line(307.5, 313, 307.5, 319, $style6);
		
		PDF::RoundedRect(310, 15, 145, 95, 0.000001, '1111', null, $style6);
        PDF::ImageSVG('@' . $svg, $x=310, $y= 15, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		PDF::Line(1, 112.5, 7, 112.5, $style6);
		PDF::Line(458, 112.5, 464, 112.5, $style6);
		
		PDF::RoundedRect(10, 115, 145, 95, 0.000001, '1111', null, $style6);
        PDF::ImageSVG('@' . $svg, $x=10, $y=115, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		PDF::RoundedRect(160, 115, 145, 95, 0.000001, '1111', null, $style6);
        PDF::ImageSVG('@' . $svg, $x=160, $y=115, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		PDF::RoundedRect(310, 115, 145, 95, 0.000001, '1111', null, $style6);
        PDF::ImageSVG('@' . $svg, $x=310, $y=115, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		PDF::Line(1, 212.5, 7, 212.5, $style6);
		PDF::Line(458, 212.5, 464, 212.5, $style6);
		
		PDF::RoundedRect(10, 215, 145, 95, 0.000001, '1111', null, $style6);
		PDF::ImageSVG('@' . $svg, $x=10, $y=215, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		PDF::RoundedRect(160, 215, 145, 95, 0.000001, '1111', null, $style6);
		PDF::ImageSVG('@' . $svg, $x=160, $y=215, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		PDF::RoundedRect(310, 215, 145, 95, 0.000001, '1111', null, $style6);
		PDF::ImageSVG('@' . $svg, $x=310, $y=215, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		
        PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
        print_r(url('/storage/app/template/'.$name.'.pdf'));
    } 
	
	
	/**
	 * Print Preview For 150 * 100 Size Stickers
	 *
	 * @return pdf
	 */
	
    public function printPdfArr(Request $request)
    {
		 $name=$request->name.'-'.$request->buyer_no;
		 $svgarr = explode(',_',$request->svg);
         //$svg=urldecode($request->svg);
         $id = $request->id;
		 $customer = DB::table("orchestra_solid_carton_sticker_orders")
						->join('customers', 'orchestra_solid_carton_sticker_orders.customer_id', 'customers.id')
						->select('customers.short_name','orchestra_solid_carton_sticker_orders.created_at')
						->where('orchestra_solid_carton_sticker_orders.id',$id)
						->get();
		 $customer_name = $customer{0}->short_name;
		 $this->customer_name = $customer_name;
		
		 $this->created_date = $customer{0}->created_at;
		
		 $this->template_name = "SOL-CARTON";
		 $this->template_zone = "";
	     $this->template_desc = "";
		 $this->tesco_po_number = $request->buyer_no;
		 $this->style = $request->style_no;
	   
		 define ('PDF_FONT_NAME_MAIN', 'calibri');
		 define ('PDF_FONT_NAME_DATA', 'calibri');
			// Custom Header
		  PDF::setHeaderCallback(function($pdf) {
			$pdf->SetY(0);
		  $name=$this->template_name;
		  $zone=$this->template_zone;
			$tesco_po_number=$this->tesco_po_number;
			$style=$this->style;
			$tesco_description=$this->template_desc;
			$date=date_create($this->created_date);
			$created_date =  date_format($date,"d.m.Y");
			$image_file= K_PATH_IMAGES.'logo_example.jpg';
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="50.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal" style="&#10;    /* border-radius: 27px; */&#10;">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L666,65 L666,66 L499,66 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L500,65 L500,144 L499,144 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L821,65 L821,66 L665,66 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L969,65 L969,66 L820,66 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,65 L969,65 L969,105 L968,105 L968,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,104 L969,104 L969,144 L968,144 L968,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,182 L821,182 L821,183 L499,183 L499,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L500,143 L500,183 L499,183 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,182 L969,182 L969,183 L820,183 L820,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,143 L969,143 L969,183 L968,183 L968,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L666,143 L666,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,144 L665,144 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,105 L665,105 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L666,104 L666,143 L665,143 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,143 L820,143 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,144 L820,144 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L821,143 L821,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L1016,212 L1016,213 L8,213 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,213 L1016,213 L1016,214 L8,214 L8,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L8,214 L9,213 L9,212 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,213 L1015,214 L1016,214 L1016,212 L1015,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L1016,216 L1016,217 L8,217 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,217 L1016,217 L1016,218 L8,218 L8,217"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L8,218 L9,217 L9,216 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,217 L1015,218 L1016,218 L1016,216 L1015,217"/>
					</g>
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="56cm" height="37cm" version="1.1">
							<image xlink:href="'.public_path('/images/jupiter.jpg').'" x="174" y="66" height="65px" width="116px" style=""/>
					</svg>
					<g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Jupiter Garments Accessories</text>

					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">STYLE : '.$style.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REF : '.$name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DATE : '.$created_date.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Size: 150x100mm</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DESCRIPTION : '.$tesco_description.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REV.NO :</text>
					</g>
					</g>
					</svg>';
					$pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			});
	   
	   
			// Custom Footer
			PDF::setFooterCallback(function($pdf) {
			$tesco_po_number=$this->tesco_po_number;
			$customer_name=$this->customer_name;
			// Position at 15 mm from bottom
			$pdf->SetY(-50);
			$pdf->SetX(9.5);
			// Set font
			//$pdf->SetFont('frankgothcnbt');
			// Page number
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188mm" height="65.2667mm" viewBox="0 0 1360 168" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L1352,52 L1352,53 L8,53 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,53 L1352,53 L1352,54 L8,54 L8,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L8,54 L9,53 L9,52 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,53 L1351,54 L1352,54 L1352,52 L1351,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L1352,56 L1352,57 L8,57 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,57 L1352,57 L1352,58 L8,58 L8,57"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L8,58 L9,57 L9,56 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,57 L1351,58 L1352,58 L1352,56 L1351,57"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M385,79 L386,79 L386,157 L385,157 L385,79"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M764,79 L765,79 L765,157 L764,157 L764,79"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">CUSTOMER : '.$customer_name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">APPROVED BY : </text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">COMMENTS IF ANY :</text>
					</g>
					</g>
					</svg>';
					
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		});
//          print_r($svg);
        PDF::setImageScale(1.66);
//        PDF::setPrintHeader(false);
//        PDF::setPrintFooter(false);
        PDF::SetFont('calibrib');
		PDF::AddPage('P', 'A4');
		//PDF::RoundedRect(10, 10, 150, 100, 0, '0000');
		//PDF::RoundedRect(12.5, 12.5, 145, 95, 0, '0000');
		
		$style6 = array('width' => 0.353, 'cap' => '', 'join' => '', 'dash' => '', 'color' => '');
        PDF::RoundedRect(10, 47.5, 150, 100, 0.000001, '1111', null, $style6);
        PDF::RoundedRect(12.5, 50, 145, 95, 0.000001, '1111', null, $style6);
		PDF::SetAutoPageBreak(TRUE);
		$i=0;
		$j=0;
		$yy=50;
		foreach($svgarr as $value)
        {
			$svg=urldecode($value);
			
			
			if($i == 0)
			{
				//PDF::RoundedRect(10, 160, 150, 100, 0, '0000');
				$yy=50;
				//PDF::RoundedRect(12.5, 162.5, 145, 95, 0, '0000');
				
				PDF::RoundedRect(10, 47.5, 150, 100, 0.000001, '1111', null, $style6);
        		PDF::RoundedRect(12.5, 50, 145, 95, 0.000001, '1111', null, $style6);
				PDF::ImageSVG('@' . $svg, $x=12.5, $y=$yy, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
				
			}
			else if($i == 2)
			{
				PDF::AddPage('P', 'A4');
				//PDF::RoundedRect(10, 10, 150, 100, 0, '0000');
				$yy=50;
				//PDF::RoundedRect(12.5, 12.5, 145, 95, 0, '0000');
				
				PDF::RoundedRect(10, 47.5, 150, 100, 0.000001, '1111', null, $style6);
        		PDF::RoundedRect(12.5, 50, 145, 95, 0.000001, '1111', null, $style6);
				PDF::ImageSVG('@' . $svg, $x=12.5, $y=$yy, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
				$i = 0;
			}
			else
			{
				//PDF::RoundedRect(10, 160, 150, 100, 0, '0000');
				$yy += 112.5;
				//PDF::RoundedRect(12.5, 162.5, 145, 95, 0, '0000');
				
				PDF::RoundedRect(10, 160, 150, 100, 0.000001, '1111', null, $style6);
				PDF::RoundedRect(12.5, 162.5, 145, 95, 0.000001, '1111', null, $style6);
				PDF::ImageSVG('@' . $svg, $x=12.5, $y=$yy, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			}
			$i++;
			$j++;
		 }
		 
		 PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
		 print_r(url('/storage/app/template/'.$name.'.pdf'));
    } 
	
	
	
	
	/**
	 * Print Preview For 150 * 100 Size Stickers
	 *
	 * @return pdf
	 */
	
    public function proofPdfArr(Request $request)
    {
		 $name=$request->name;
		 $svgarr = explode(',_',$request->svg);
         //$svg=urldecode($request->svg);
         define ('PDF_FONT_NAME_MAIN', 'calibrib');
         define ('PDF_FONT_NAME_DATA', 'calibrib');
//          print_r($svg);
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
		PDF::SetAutoPageBreak(TRUE);
        PDF::SetFont('calibrib');
		//PDF::RoundedRect(10, 10, 150, 100, 0, '0000');
		//PDF::RoundedRect(12.5, 12.5, 145, 95, 0, '0000');
		
		$style6 = array('width' => 0.353, 'cap' => '', 'join' => '', 'dash' => '', 'color' => '');
		PDF::SetAutoPageBreak(TRUE);
		foreach($svgarr as $value)
        {
			$svg=urldecode($value);
			
			PDF::SetMargins(0, 0, 0, false);
			PDF::AddPage('L',array(324,465));
			PDF::setImageScale(1.66);
			$style6 = array('width' => 0.353, 'cap' => '', 'join' => '', 'dash' => '', 'color' => '');
			//PDF::RoundedRect(95, 255, 40, 30, 0.1, '1111', null, $style6);
			//PDF::RoundedRect(140, 255, 40, 30, 0.1, '1111', 'null', $style6);

			//Top Left Corner
			PDF::Line(7, 6, 7, 12, $style6);
			PDF::Line(1, 12, 7, 12, $style6);

			//Bottom Left Corner
			PDF::Line(7, 313, 7, 319, $style6);
			PDF::Line(1, 313, 7, 313, $style6);

			//Top Right Corner
			PDF::Line(458, 6, 458, 12, $style6);
			PDF::Line(458, 12, 464, 12, $style6);

			//Bottom Right Corner
			PDF::Line(458, 313, 464, 313, $style6);
			PDF::Line(458, 313, 458, 319, $style6);

			PDF::RoundedRect(10, 15, 145, 95, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=10, $y=15, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);

			PDF::Line(157.5, 6, 157.5, 12, $style6);
			PDF::Line(157.5, 313, 157.5, 319, $style6);

			PDF::RoundedRect(160, 15, 145, 95, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=160, $y=15, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);

			PDF::Line(307.5, 6, 307.5, 12, $style6);
			PDF::Line(307.5, 313, 307.5, 319, $style6);

			PDF::RoundedRect(310, 15, 145, 95, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=310, $y= 15, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);

			PDF::Line(1, 112.5, 7, 112.5, $style6);
			PDF::Line(458, 112.5, 464, 112.5, $style6);

			PDF::RoundedRect(10, 115, 145, 95, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=10, $y=115, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);

			PDF::RoundedRect(160, 115, 145, 95, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=160, $y=115, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);

			PDF::RoundedRect(310, 115, 145, 95, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=310, $y=115, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);

			PDF::Line(1, 212.5, 7, 212.5, $style6);
			PDF::Line(458, 212.5, 464, 212.5, $style6);

			PDF::RoundedRect(10, 215, 145, 95, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=10, $y=215, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);

			PDF::RoundedRect(160, 215, 145, 95, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=160, $y=215, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);

			PDF::RoundedRect(310, 215, 145, 95, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=310, $y=215, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		 }
		 
		 PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
		 print_r(url('/storage/app/template/'.$name.'.pdf'));
    } 
	
	/**
	 * Print Preview For Hang Tag
	 *
	 * @return pdf
	 */
	
	public function printHangPdf(Request $request)
    {
        $svgarrfront=explode(',_',$request->svg); 
		//$svg1=urldecode($request->svg1); 
		$svgarr=explode(',_',$request->svgRoot); 
        define ('PDF_FONT_NAME_MAIN', 'calibrib');
        define ('PDF_FONT_NAME_DATA', 'calibrib');
        PDF::setImageScale(1);
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
        PDF::SetFont('alternategothic2bt');
        PDF::AddPage();
		//PDF::RoundedRect(10, 10, 61, 131, 0, '0000');
		//PDF::SetFillColor(255, 255, 255);
        //PDF::SetTextColor(0, 0, 0);
        //PDF::ImageSVG('@' . $svg, $x=12.5, $y=12.5, '49', '117', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
        PDF::SetFont('arial');
		$count=0;
		$xx=12.5;
		$i=0;
		$yy=12.5;
		$arr_mer=array_merge($svgarrfront,$svgarr);
		$fir=0;
		$sec = count($arr_mer) / 2;
		foreach($arr_mer as $value)
		{
			$svg=urldecode($value);
			//PDF::RoundedRect(80, 10, 61, 131, 0, '0000');
			//PDF::ImageSVG('@' . $svg,  $x=$xx, $y=12.5, '49', '117', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			
			if($count%2==0)  
			{  
				PDF::ImageSVG('@' . urldecode($arr_mer[$fir]),  $x=$xx, $y=$yy, '49', '117', $link='', $align='', $palign='', $border=0, $fitonpage=false);
				$fir++;
			}  
			else  
			{  
				PDF::ImageSVG('@' . urldecode($arr_mer[$sec]),  $x=$xx, $y=$yy, '49', '117', $link='', $align='', $palign='', $border=0, $fitonpage=false);
				$sec++;
			}   
			
			$xx += 65;
			$i++;
			
			if($i == 3)
			{
				//PDF::AddPage();
				//$i=0;
				$xx=12.5;
				$yy=150;
			}
			
			if($i == 6)
			{
				PDF::AddPage();
				$i=0;
				$xx=12.5;
				$yy=12.5;
			}
			$count++;
		}
		
        PDF::Output( public_path('/storage/app/template/solid1.pdf'), 'F');
		
		exec("gs -dSAFER -dBATCH -dNOPAUSE -dNOCACHE -sDEVICE=pdfwrite -sColorConversionStrategy=CMYK -dProcessColorModel=/DeviceCMYK -sOutputFile=".public_path('/storage/app/template/solid1_cmyk.pdf')." ".public_path('/storage/app/template/solid1.pdf'));
        
//        print_r(url('/storage/app/template/'.$name.'_cmyk.pdf'));
		
        print_r(url('/storage/app/template/solid1_cmyk.pdf'));
    } 	
	
	/**
	 * Print Preview For Special Tag
	 *
	 * @return pdf
	 */
	
	public function printSpecialPdf(Request $request)
    {
        $svg=urldecode($request->svg); 
        define ('PDF_FONT_NAME_MAIN', 'calibrib');
        define ('PDF_FONT_NAME_DATA', 'calibrib');
        PDF::setImageScale(1);
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
        PDF::SetFont('calibrib');
        PDF::AddPage();
        PDF::RoundedRect(10, 10, 150, 100, 0, '0000');
		//PDF::SetFillColor(255, 255, 255);
        //PDF::SetTextColor(0, 0, 0);
        PDF::ImageSVG('@' . $svg, $x=12.5, $y=12.5, '145', '95', $link='', $align='', $palign='', $border=1, $fitonpage=false);
        PDF::Output( public_path('/storage/app/template/solid1.pdf'), 'F');
        print_r(url('/storage/app/template/solid1.pdf'));
    } 
	
	/**
	 * Print Preview For Wash Care Lables.
	 *
	 * @return pdf
	 */
	
	public function printWashPdf(Request $request)
    {
		$svgarr = explode(',_',$request->svg);
		$id = $request->id;
		$owcl = DB::table('orchestra_wash_care_label_orders')->select('*')->where('id',$id)->first();

		$customer_id = $owcl->customer_id;
		$customer = DB::table('customers')->select('*')->where('id',$customer_id)->first();

		$customer_name = $customer->short_name;
		$this->customer_name = $customer_name;
	   
		$this->created_date = $owcl->created_at;
	   
		$this->template_name = "";
		$this->template_zone = "";
		$this->template_desc = "Taffeta Wash Care Label";
		$this->tesco_po_number = $owcl->po;
		$this->style = $owcl->uvc;

        //$svg=urldecode($request->svg);
		
		$made_in_select=$request->made_in_select;
		$orchestra_countries = DB::table('orchestra_countries')->select('*')->where('id',$made_in_select)->get();
		
		
		$care_instruction_select = explode(',',$request->care_instruction_select_fin); 
		$casval = "";
		$cascount = 1 ;
		foreach($care_instruction_select as $cval)
		{
			$casval .= 'WHEN '.$cval.' THEN '.$cascount;
			$cascount++;
		}
		$orchestra_care_instruction = DB::table('orchestra_care_instruction')->select('*')->whereIn('id',$care_instruction_select)->orderByRaw("CASE id
		".$casval."
		ELSE ".$cascount." END")->get();
		
		//print_r($orchestra_countries);
		
		//print_r($orchestra_countries);
		
        $svg=urldecode($request->svg); 
        $svg_back=urldecode($request->svg_back); 
        $svg_middle=urldecode($request->svg_middle); 
        define ('PDF_FONT_NAME_MAIN', 'calibri');
		define ('PDF_FONT_NAME_DATA', 'calibri');
		
		PDF::setHeaderCallback(function($pdf) {
			$pdf->SetY(0);
		  $name=$this->template_name;
		  $zone=$this->template_zone;
			$tesco_po_number=$this->tesco_po_number;
			if($tesco_po_number == null || $tesco_po_number == "null")
			{
				$tesco_po_number = "";
			}
			else
			{
				$tesco_po_number = $tesco_po_number;
			}
			$style=$this->style;
			$tesco_description=$this->template_desc;
			$date=date_create($this->created_date);
			$created_date =  date_format($date,"d.m.Y");
			$image_file= K_PATH_IMAGES.'logo_example.jpg';
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="50.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal" style="&#10;    /* border-radius: 27px; */&#10;">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L666,65 L666,66 L499,66 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L500,65 L500,144 L499,144 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L821,65 L821,66 L665,66 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L969,65 L969,66 L820,66 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,65 L969,65 L969,105 L968,105 L968,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,104 L969,104 L969,144 L968,144 L968,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,182 L821,182 L821,183 L499,183 L499,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L500,143 L500,183 L499,183 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,182 L969,182 L969,183 L820,183 L820,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,143 L969,143 L969,183 L968,183 L968,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L666,143 L666,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,144 L665,144 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,105 L665,105 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L666,104 L666,143 L665,143 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,143 L820,143 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,144 L820,144 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L821,143 L821,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L1016,212 L1016,213 L8,213 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,213 L1016,213 L1016,214 L8,214 L8,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L8,214 L9,213 L9,212 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,213 L1015,214 L1016,214 L1016,212 L1015,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L1016,216 L1016,217 L8,217 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,217 L1016,217 L1016,218 L8,218 L8,217"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L8,218 L9,217 L9,216 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,217 L1015,218 L1016,218 L1016,216 L1015,217"/>
					</g>
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="56cm" height="37cm" version="1.1">
							<image xlink:href="'.public_path('/images/jupiter.jpg').'" x="174" y="66" height="65px" width="116px" style=""/>
					</svg>
					<g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Jupiter Garments Accessories</text>

					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">STYLE : '.$style.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REF : '.$name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DATE : '.$created_date.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Size: 127x80mm</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DESCRIPTION : '.$tesco_description.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REV.NO :</text>
					</g>
					</g>
					</svg>';
					$pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			});
	   
	   
			// Custom Footer
			PDF::setFooterCallback(function($pdf) {
			$tesco_po_number=$this->tesco_po_number;
			if($tesco_po_number == null || $tesco_po_number == "null")
			{
				$tesco_po_number = "";
			}
			else
			{
				$tesco_po_number = $tesco_po_number;
			}
			$customer_name=$this->customer_name;
			// Position at 15 mm from bottom
			$pdf->SetY(-50);
			$pdf->SetX(9.5);
			// Set font
			//$pdf->SetFont('frankgothcnbt');
			// Page number
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188mm" height="65.2667mm" viewBox="0 0 1360 168" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L1352,52 L1352,53 L8,53 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,53 L1352,53 L1352,54 L8,54 L8,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L8,54 L9,53 L9,52 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,53 L1351,54 L1352,54 L1352,52 L1351,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L1352,56 L1352,57 L8,57 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,57 L1352,57 L1352,58 L8,58 L8,57"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L8,58 L9,57 L9,56 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,57 L1351,58 L1352,58 L1352,56 L1351,57"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M385,79 L386,79 L386,157 L385,157 L385,79"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M764,79 L765,79 L765,157 L764,157 L764,79"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">CUSTOMER : '.$customer_name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">APPROVED BY : </text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">COMMENTS IF ANY :</text>
					</g>
					</g>
					</svg>';
					
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		});
		
        PDF::setImageScale(1);
        // PDF::setPrintHeader(false);
        // PDF::setPrintFooter(false);
        PDF::SetFont('tahoma', '',  1.5, '', true);
        PDF::AddPage();
        //PDF::RoundedRect(10, 10, 150, 100, 0, '0000');
		//PDF::SetFillColor(255, 255, 255);
        //PDF::SetTextColor(0, 0, 0);
		
	
		$tbl='<p  style="font-size:5px;color:cmyk(0%, 0%, 0%, 100%);font-family:Calibri;line-height:1.35">Fabriqué en - Made in - <br> Fabricado en - <br>Fabricado em - <br>Fabbricato in - <br>Vervaardigd in  - <br>Hergestellt in -<br><span style="font-family:Tahoma">  ﺻﻨﻊ ب </span>-<br> παράγεται στην</p>';
		
		$tbl1='<p  style="font-size:5px;color:cmyk(0%, 0%, 0%, 100%);font-family:Calibri">'.$orchestra_countries{0}->french.' - '.$orchestra_countries{0}->english.' - '.$orchestra_countries{0}->spanish.' - '.$orchestra_countries{0}->portuguese.' - <br>'.$orchestra_countries{0}->italian.' - '.$orchestra_countries{0}->dutch.' - '.$orchestra_countries{0}->german.' - <br><span style="font-family:Tahoma">'.$orchestra_countries{0}->arabic.'</span> - '.$orchestra_countries{0}->greek.'</p>';
		$tbl2="";
		foreach($orchestra_care_instruction as $value)
		{
			$tbl2.='<p style="font-size:5px;color:cmyk(0%, 0%, 0%, 100%);font-family:Calibri;text-align:left">'.$value->french.' - '.$value->english.' - '.$value->spanish.' - '.$value->portuguese.' - '.$value->italian.' - '.$value->dutch.' - '.$value->german.' - <span style="font-family:Tahoma">'.$value->arabic.'</span> - '.$value->greek.'</p><br><br>';
		}
		
		//print_r($tbl2);
		$tbl3="";
		$composition = json_decode($request->composition);
		//print_r($composition);
		foreach($composition as $key => $value)
		{
			
			$express_value = explode('_',$value[0]);
			if($express_value[0] != "Select Option")
			{
					$exp_value = explode('_',$value[0]);
					$orchestra_parts = DB::table('orchestra_parts')->select('*')->where('id',$exp_value[0])->get();
				
					$tbl3.='<p style="font-size:5px;color:cmyk(0%, 0%, 0%, 100%)">'.$orchestra_parts{0}->french.' - '.$orchestra_parts{0}->english.' - '.$orchestra_parts{0}->spanish.' - '.$orchestra_parts{0}->portuguese.' - '.$orchestra_parts{0}->italian.' - '.$orchestra_parts{0}->dutch.' - '.$orchestra_parts{0}->german.' - <span>'.$orchestra_parts{0}->arabic.'</span> - '.$orchestra_parts{0}->greek.'</p><br><br>';
			}
			foreach($value as $key1 => $value1)
            {
				$exp_val = explode('_',$value1);
				$orchestra_compositions = DB::table('orchestra_compositions')->select('*')->where('english',$exp_val[1])->get();
				
				$tbl3.='<p style="font-size:5px;color:cmyk(0%, 0%, 0%, 100%);font-family:Calibri">'.$exp_val[2].'% '.$orchestra_compositions{0}->french.' - '.$orchestra_compositions{0}->english.' - '.$orchestra_compositions{0}->spanish.' - '.$orchestra_compositions{0}->portuguese.' - '.$orchestra_compositions{0}->italian.' - '.$orchestra_compositions{0}->dutch.' - '.$orchestra_compositions{0}->german.' - <span style="font-family:Tahoma">'.$orchestra_compositions{0}->arabic.'</span> - '.$orchestra_compositions{0}->greek.'</p><br><br>';
			}
		}
		$xx=12.5;
		$i=0;
		foreach($svgarr as $value)
		{
			$svg=urldecode($value);
			PDF::writeHTMLCell('25.5', '40', $xx, $y=42.5, '<p style="font-size:12px">Back</p>', '', 0, 0, true, 'C', true);
        	PDF::ImageSVG('@' . $svg, $x=$xx, $y=50, '25.5', '165', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			PDF::SetFillColor(255, 255, 255);
			PDF::SetTextColor(0, 0, 0);
			PDF::writeHTMLCell('25.5', '40', $xx, '172.5', $tbl, '', 0, 0, true, 'C', true);
			PDF::writeHTMLCell('26', '40', $xx, '191.8', $tbl1, '', 0, 0, true, 'C', true);
			PDF::writeHTMLCell('25.5', '40', $xx, $y=217.5, '<p style="font-size:12px">Front</p>', '', 0, 0, true, 'C', true);
			$xx += 37.5;
			$i++;
			
			if($i == 5)
			{
				PDF::AddPage();
				$i=0;
				$xx=12.5;
			}
		}
		PDF::writeHTMLCell('25.5', '40', $xx, $y=42.5, '<p style="font-size:12px">Back</p>', '', 0, 0, true, 'C', true);
		PDF::ImageSVG('@' . $svg_back, $xx, $y=50, '25.5', '165', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		PDF::writeHTMLCell('22', '40', $xx + 2.25, '62.5', $tbl2, '', 0, 0, true, 'C', true);
		PDF::writeHTMLCell('25', '40', $xx + 1, '147.5', $tbl3, '', 0, 0, true, 'C', true);
		PDF::writeHTMLCell('25.5', '40', $xx, $y=217.5, '<p style="font-size:12px">Front</p>', '', 0, 0, true, 'C', true);
		PDF::AddPage();
		PDF::ImageSVG('@' . $svg_middle, $x=50, $y=50, '55.5', '82.5', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		//print_r($i);
//		if($i == 0)
//		{
//			PDF::ImageSVG('@' . $svg_back, $x=12.5, $y=12.5, '25', '165', $link='', $align='', $palign='', $border=0, $fitonpage=false);
//      		PDF::ImageSVG('@' . $svg_middle, $x=50, $y=12.5, '55', '82.5', $link='', $align='', $palign='', $border=0, $fitonpage=false);
//			PDF::writeHTMLCell('22', '40', '14', '25', $tbl2, '', 1, 1, true, 'C', true);
//			PDF::writeHTMLCell('25', '40', '13.5', '110', $tbl3, '', 1, 1, true, 'C', true);
//
//		}
//		else if($i == 1)
//		{
//			PDF::ImageSVG('@' . $svg_back, $x=$xx, $y=12.5, '25', '165', $link='', $align='', $palign='', $border=0, $fitonpage=false);
//      		PDF::ImageSVG('@' . $svg_middle, $x=$xx+37.5, $y=12.5, '55', '82.5', $link='', $align='', $palign='', $border=0, $fitonpage=false);
//			PDF::writeHTMLCell('22', '40', $xx+1.5, '25', $tbl2, '', 1, 1, true, 'C', true);
//			PDF::writeHTMLCell('25', '40', $xx+0.5, '110', $tbl3, '', 1, 1, true, 'C', true);
//		}
//		else if($i == 2)
//		{
//			PDF::ImageSVG('@' . $svg_back, $x=$xx, $y=12.5, '25', '165', $link='', $align='', $palign='', $border=0, $fitonpage=false);
//      		PDF::ImageSVG('@' . $svg_middle, $x=$xx+37.5, $y=12.5, '55', '82.5', $link='', $align='', $palign='', $border=0, $fitonpage=false);
//			PDF::writeHTMLCell('22', '40', $xx+1.5, '25', $tbl2, '', 1, 1, true, 'C', true);
//			PDF::writeHTMLCell('25', '40', $xx+0.5, '110', $tbl3, '', 1, 1, true, 'C', true);
//		}
		
      
		
//		PDF::writeHTMLCell('8', '10', '16.2', '61', $tbl, '', 1, 1, true, 'C', true);
		
	
        PDF::Output( public_path('/storage/app/template/solid1.pdf'), 'F');
        print_r(url('/storage/app/template/solid1.pdf'));
    } 
	
	/**
	 * Print Preview For Standard Poly Bag Stickers
	 * It Contains Dynamic Table
	 * This Function is used to create dynamic table
	 * @return pdf
	 */
	
    public function printPdfTable(Request $request)
    {
         $name=$request->name.'-'.$request->buyer_no;
		 $color=$request->color;
         $svg=urldecode($request->svg);
         $id = $request->id;
		 $customer = DB::table("orchestra_standard_poly_bag_sticker_orders")
						->join('customers', 'orchestra_standard_poly_bag_sticker_orders.customer_id', 'customers.id')
						->select('customers.short_name','orchestra_standard_poly_bag_sticker_orders.created_at')
						->where('orchestra_standard_poly_bag_sticker_orders.id',$id)
						->get();
		 $customer_name = $customer{0}->short_name;
		 $this->customer_name = $customer_name;
		
		 $this->created_date = $customer{0}->created_at;
		
		 $this->template_name = "STD-POLY";
		 $this->template_zone = "";
	     $this->template_desc = "";
		 $this->tesco_po_number = $request->buyer_no;
		 $this->style = $request->style_no;
	   
		 define ('PDF_FONT_NAME_MAIN', 'calibri');
		 define ('PDF_FONT_NAME_DATA', 'calibri');
			// Custom Header
		  PDF::setHeaderCallback(function($pdf) {
			$pdf->SetY(0);
		  $name=$this->template_name;
		  $zone=$this->template_zone;
			$tesco_po_number=$this->tesco_po_number;
			$style=$this->style;
			$tesco_description=$this->template_desc;
			$date=date_create($this->created_date);
			$created_date =  date_format($date,"d.m.Y");
			$image_file= K_PATH_IMAGES.'logo_example.jpg';
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="50.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal" style="&#10;    /* border-radius: 27px; */&#10;">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L666,65 L666,66 L499,66 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L500,65 L500,144 L499,144 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L821,65 L821,66 L665,66 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L969,65 L969,66 L820,66 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,65 L969,65 L969,105 L968,105 L968,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,104 L969,104 L969,144 L968,144 L968,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,182 L821,182 L821,183 L499,183 L499,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L500,143 L500,183 L499,183 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,182 L969,182 L969,183 L820,183 L820,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,143 L969,143 L969,183 L968,183 L968,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L666,143 L666,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,144 L665,144 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,105 L665,105 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L666,104 L666,143 L665,143 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,143 L820,143 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,144 L820,144 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L821,143 L821,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L1016,212 L1016,213 L8,213 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,213 L1016,213 L1016,214 L8,214 L8,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L8,214 L9,213 L9,212 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,213 L1015,214 L1016,214 L1016,212 L1015,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L1016,216 L1016,217 L8,217 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,217 L1016,217 L1016,218 L8,218 L8,217"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L8,218 L9,217 L9,216 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,217 L1015,218 L1016,218 L1016,216 L1015,217"/>
					</g>
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="56cm" height="37cm" version="1.1">
							<image xlink:href="'.public_path('/images/jupiter.jpg').'" x="174" y="66" height="65px" width="116px" style=""/>
					</svg>
					<g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Jupiter Garments Accessories</text>

					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">STYLE : '.$style.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REF : '.$name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DATE : '.$created_date.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Size: 150x100mm</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DESCRIPTION : '.$tesco_description.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REV.NO :</text>
					</g>
					</g>
					</svg>';
					$pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			});
	   
	   
			// Custom Footer
			PDF::setFooterCallback(function($pdf) {
			$tesco_po_number=$this->tesco_po_number;
			$customer_name=$this->customer_name;
			// Position at 15 mm from bottom
			$pdf->SetY(-50);
			$pdf->SetX(9.5);
			// Set font
			//$pdf->SetFont('frankgothcnbt');
			// Page number
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188mm" height="65.2667mm" viewBox="0 0 1360 168" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L1352,52 L1352,53 L8,53 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,53 L1352,53 L1352,54 L8,54 L8,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L8,54 L9,53 L9,52 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,53 L1351,54 L1352,54 L1352,52 L1351,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L1352,56 L1352,57 L8,57 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,57 L1352,57 L1352,58 L8,58 L8,57"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L8,58 L9,57 L9,56 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,57 L1351,58 L1352,58 L1352,56 L1351,57"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M385,79 L386,79 L386,157 L385,157 L385,79"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M764,79 L765,79 L765,157 L764,157 L764,79"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">CUSTOMER : '.$customer_name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">APPROVED BY : </text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">COMMENTS IF ANY :</text>
					</g>
					</g>
					</svg>';
					
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		});
//          print_r($svg);
		
		$orchestra_color = DB::table('orchestra_color_code')->select('*')->where('code',$color)->get();
		if(count($orchestra_color) > 0)
		{
			$color_code=$orchestra_color{0}->french;
		}
		else
		{
			$color_code="";
		}
		
        PDF::setImageScale(1);
//        PDF::setPrintHeader(false);
//        PDF::setPrintFooter(false);
        PDF::SetFont('calibrib');
        PDF::AddPage();
		
		$style6 = array('width' => 0.353, 'cap' => '', 'join' => '', 'dash' => '', 'color' => '');
        //PDF::RoundedRect(95, 255, 40, 30, 0.1, '1111', null, $style6);
        //PDF::RoundedRect(140, 255, 40, 30, 0.1, '1111', 'null', $style6);
        PDF::RoundedRect(10, 47.5, 150, 100, 0.000001, '1111', null, $style6);
		PDF::RoundedRect(12.5, 50, 145, 95, 0.000001, '1111', null, $style6);
		
		//PDF::RoundedRect(10, 10, 150, 100, 0, '0000');
		//PDF::RoundedRect(12.5, 12.5, 145, 95, 0, '0000');
        $td_size="";
        $td_col="";
        $total=0;
        $size=explode(',',$request->size);
        $qty=explode(',',$request->qty);
        $size_break=explode(',',$request->size_break);
        $i=0;
		$index_arr=array();
        foreach($size as $val)
        {
			  $size_var = explode(' ',$size[0]);
			  if (!in_array($val, $index_arr)) 
			  { 
				   if($val != "")
					{
						array_push($index_arr,$val);
						if($size_var[1] == "MOIS")
						{
							if(count($size) > 6)
							{
								$td_size.='<td style="font-size:10px;line-height:1.8"><br>'.$val.'</td>';
							}
							else
							{
								$td_size.="<td><br><br>".$val."</td>";
							}
						}
						else
						{
							if(count($size) > 7)
							{
								$td_size.='<td style="font-size:11px;line-height:1.8"><br>'.$val.'</td>';
							}
							else
							{
								$td_size.="<td><br><br>".$val."</td>";
							}
						}
						
						$td_col.="<td>".$size_break[$i]."</td>";
					} 
			  } 
           
            $total+=$size_break[$i];
            $i++;
		}
		if(count($size) > 6)
		{
			if(strlen($color_code) > 6)
			{
				if($color_code == "BLEU FONCE" || $color_code == "BLEU CLAIR" || $color_code == "GRIS CLAIR" || $color_code == "GRIS FONCE" || $color_code == "ROSE CLAIR" || $color_code == "ROSE FONCE" || $color_code == "VERT FONCE" || $color_code == "VERT CLAIR" || $color_code == "BEIGE CLAIR")
				{
					$line_height = "2.5";
				}
				else
				{
					$line_height = "1.2";	
				}
				$text_size = "7.5px";
				$line="1.5";
			}
			else
			{
				$text_size = "7.5px";
				$line_height = "2.5";
				$line="1.5";
			}
		}
		else
		{
			$text_size = "7.5px";
			$line_height = "2.5";
			$line="1.5";
		}
		
          $tbl = '<table  border="1" style="text-align:center;">
		  			<tr style="line-height:0.53">
                          <td style="font-size:14px;text-align:center;line-height:0.45"><br><br>Size:</td>
                          '.$td_size.'
                          <td><br><br>Total<br></td>
                          </tr>
                      <tr style="line-height:'.$line.'">
                          <td style="font-size:'.$text_size.';line-height:'.$line_height.'">'.$color_code.'</td>
                          '.$td_col.'
                          <td style="line-height:'.$line.'">'.$total.'</td>
                          </tr>
            </table>';
        PDF::SetFillColor(255, 255, 255);
        PDF::SetTextColor(0, 0, 0);
        
        PDF::ImageSVG('@' . $svg, $x=12.5, $y=50, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		PDF::writeHTMLCell(147, '', '11.5', '92.5', $tbl, '', 1, 1, true, '', true);
        
  
        
        $style = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'calibrib',
            'fontsize' => 12,
            'stretchtext' => 4
        );
         ///PDF::write1DBarcode('3531114195599', 'EAN13', '60', '72', '', 32, 0.45, $style, 'N');

       
        PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
        print_r(url('/storage/app/template/'.$name.'.pdf'));
        
    }
	
	/**
	 * Print Preview For Standard Poly Bag Stickers
	 * It Contains Dynamic Table
	 * This Function is used to create dynamic table
	 * @return pdf
	 */
	
    public function proofPdfTable(Request $request)
    {
         $name=$request->name;
		 $color=$request->color;
         $svg=urldecode($request->svg);
         define ('PDF_FONT_NAME_MAIN', 'calibrib');
         define ('PDF_FONT_NAME_DATA', 'calibrib');
//          print_r($svg);
		
		$orchestra_color = DB::table('orchestra_color_code')->select('*')->where('code',$color)->get();
		if(count($orchestra_color) > 0)
		{
			$color_code=$orchestra_color{0}->french;
		}
		else
		{
			$color_code="";
		}
		
		PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
		PDF::SetAutoPageBreak(TRUE);
        PDF::SetFont('calibrib');
		PDF::SetMargins(0, 0, 0, false);
        PDF::AddPage('L',array(324,465));
		PDF::setImageScale(1.66);
		
		$style6 = array('width' => 0.353, 'cap' => '', 'join' => '', 'dash' => '', 'color' => '');
        $td_size="";
        $td_col="";
        $total=0;
        $size=explode(',',$request->size);
        $size_break=explode(',',$request->size_break);
        $i=0;
		$index_arr=array();
        foreach($size as $val)
        {
			  $size_var = explode(' ',$size[0]);
			  if (!in_array($val, $index_arr)) 
			  { 
				   if($val != "")
					{
						array_push($index_arr,$val);
						if($size_var[1] == "MOIS")
						{
							if(count($size) > 6)
							{
								$td_size.='<td style="font-size:14px;line-height:2.3"><br>'.$val.'</td>';
							}
							else
							{
								$td_size.="<td><br><br>".$val."</td>";
							}
						}
						else
						{
							if(count($size) > 7)
							{
								$td_size.='<td style="font-size:14px;line-height:2.3"><br>'.$val.'</td>';
							}
							else
							{
								$td_size.="<td><br><br>".$val."</td>";
							}
						}
						
						$td_col.='<td style="line-height:1.5">'.$size_break[$i].'</td>';
					} 
			  } 
           
            $total+=$size_break[$i];
            $i++;
		}
		
		if(strlen($color_code) > 6)
		{
			$text_size = "10px";
			$line_height = "3";
		}
		else
		{
			$text_size = "14px";
			$line_height = "2";
		}

          $tbl = '<table  border="1" style="text-align:center;">
		  			<tr style="line-height:0.53">
                          <td style="font-size:14px;text-align:center;line-height:0.75"><br><br>Size:</td>
                          '.$td_size.'
                          <td><br><br>Total<br></td>
                          </tr>
                      <tr>
                          <td style="font-size:'.$text_size.';text-align:center;line-height:'.$line_height.'">'.$color_code.'</td>
                          '.$td_col.'
                          <td style="line-height:1.5">'.$total.'</td>
                          </tr>
            </table>';
        PDF::SetFillColor(255, 255, 255);
        PDF::SetTextColor(0, 0, 0);
        
		
		//Top Left Corner
		PDF::Line(7, 6, 7, 12, $style6);
		PDF::Line(1, 12, 7, 12, $style6);
		
		//Bottom Left Corner
		PDF::Line(7, 313, 7, 319, $style6);
		PDF::Line(1, 313, 7, 313, $style6);
		
		//Top Right Corner
		PDF::Line(458, 6, 458, 12, $style6);
		PDF::Line(458, 12, 464, 12, $style6);
		
		//Bottom Right Corner
		PDF::Line(458, 313, 464, 313, $style6);
		PDF::Line(458, 313, 458, 319, $style6);
		
		PDF::RoundedRect(10, 15, 145, 95, 0.000001, '1111', null, $style6);
        
        PDF::ImageSVG('@' . $svg, $x=10, $y=15, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
        PDF::writeHTMLCell(147, '', '9', '57.5', $tbl, '', 1, 1, true, '', true);
		
		PDF::Line(157.5, 6, 157.5, 12, $style6);
		PDF::Line(157.5, 313, 157.5, 319, $style6);
		
		PDF::RoundedRect(160, 15, 145, 95, 0.000001, '1111', null, $style6);
        PDF::ImageSVG('@' . $svg, $x=160, $y=15, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		PDF::writeHTMLCell(147, '', '159', '57.5', $tbl, '', 1, 1, true, '', true);
		
		PDF::Line(307.5, 6, 307.5, 12, $style6);
		PDF::Line(307.5, 313, 307.5, 319, $style6);
		
		PDF::RoundedRect(310, 15, 145, 95, 0.000001, '1111', null, $style6);
        PDF::ImageSVG('@' . $svg, $x=310, $y= 15, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		PDF::writeHTMLCell(147, '', '309', '57.5', $tbl, '', 1, 1, true, '', true);
		
		PDF::Line(1, 112.5, 7, 112.5, $style6);
		PDF::Line(458, 112.5, 464, 112.5, $style6);
		
		PDF::RoundedRect(10, 115, 145, 95, 0.000001, '1111', null, $style6);
        PDF::ImageSVG('@' . $svg, $x=10, $y=115, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		PDF::writeHTMLCell(147, '', '9', '157.5', $tbl, '', 1, 1, true, '', true);
		
		PDF::RoundedRect(160, 115, 145, 95, 0.000001, '1111', null, $style6);
        PDF::ImageSVG('@' . $svg, $x=160, $y=115, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		PDF::writeHTMLCell(147, '', '159', '157.5', $tbl, '', 1, 1, true, '', true);
		
		PDF::RoundedRect(310, 115, 145, 95, 0.000001, '1111', null, $style6);
        PDF::ImageSVG('@' . $svg, $x=310, $y=115, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		PDF::writeHTMLCell(147, '', '309', '157.5', $tbl, '', 1, 1, true, '', true);
		
		PDF::Line(1, 212.5, 7, 212.5, $style6);
		PDF::Line(458, 212.5, 464, 212.5, $style6);
		
		PDF::RoundedRect(10, 215, 145, 95, 0.000001, '1111', null, $style6);
		PDF::ImageSVG('@' . $svg, $x=10, $y=215, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		PDF::writeHTMLCell(147, '', '9', '257.5', $tbl, '', 1, 1, true, '', true);
		
		PDF::RoundedRect(160, 215, 145, 95, 0.000001, '1111', null, $style6);
		PDF::ImageSVG('@' . $svg, $x=160, $y=215, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		PDF::writeHTMLCell(147, '', '159', '257.5', $tbl, '', 1, 1, true, '', true);
		
		PDF::RoundedRect(310, 215, 145, 95, 0.000001, '1111', null, $style6);
		PDF::ImageSVG('@' . $svg, $x=310, $y=215, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		PDF::writeHTMLCell(147, '', '309', '257.5', $tbl, '', 1, 1, true, '', true);
		
        
  
        PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
        print_r(url('/storage/app/template/'.$name.'.pdf'));
        
    }
	
	/**
	 * Print Preview For 100 * 70 Size Stickers
	 *
	 * @return pdf
	 */
	
     public function printPdfSmall(Request $request)
    {
		 
		 $name=$request->name.'-'.$request->buyer_no;
		 $svgarr = explode(',_',$request->svg);
         //$svg=urldecode($request->svg);
         $id = $request->id;
		 $customer = DB::table("orchestra_solid_poly_bag_sticker_orders")
						->join('customers', 'orchestra_solid_poly_bag_sticker_orders.customer_id', 'customers.id')
						->select('customers.short_name','orchestra_solid_poly_bag_sticker_orders.created_at')
						->where('orchestra_solid_poly_bag_sticker_orders.id',$id)
						->get();
		 $customer_name = $customer{0}->short_name;
		 $this->customer_name = $customer_name;
		
		 $this->created_date = $customer{0}->created_at;
		
		 $this->template_name = "SOL-PS";
		 $this->template_zone = "";
	     $this->template_desc = "";
		 $this->tesco_po_number = $request->buyer_no;
		 $this->style = $request->style_no;
	   
		 define ('PDF_FONT_NAME_MAIN', 'calibri');
		 define ('PDF_FONT_NAME_DATA', 'calibri');
			// Custom Header
		  PDF::setHeaderCallback(function($pdf) {
			$pdf->SetY(0);
		  $name=$this->template_name;
		  $zone=$this->template_zone;
			$tesco_po_number=$this->tesco_po_number;
			$style=$this->style;
			$tesco_description=$this->template_desc;
			$date=date_create($this->created_date);
			$created_date =  date_format($date,"d.m.Y");
			$image_file= K_PATH_IMAGES.'logo_example.jpg';
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="50.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal" style="&#10;    /* border-radius: 27px; */&#10;">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L666,65 L666,66 L499,66 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L500,65 L500,144 L499,144 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L821,65 L821,66 L665,66 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L969,65 L969,66 L820,66 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,65 L969,65 L969,105 L968,105 L968,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,104 L969,104 L969,144 L968,144 L968,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,182 L821,182 L821,183 L499,183 L499,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L500,143 L500,183 L499,183 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,182 L969,182 L969,183 L820,183 L820,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,143 L969,143 L969,183 L968,183 L968,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L666,143 L666,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,144 L665,144 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,105 L665,105 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L666,104 L666,143 L665,143 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,143 L820,143 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,144 L820,144 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L821,143 L821,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L1016,212 L1016,213 L8,213 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,213 L1016,213 L1016,214 L8,214 L8,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L8,214 L9,213 L9,212 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,213 L1015,214 L1016,214 L1016,212 L1015,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L1016,216 L1016,217 L8,217 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,217 L1016,217 L1016,218 L8,218 L8,217"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L8,218 L9,217 L9,216 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,217 L1015,218 L1016,218 L1016,216 L1015,217"/>
					</g>
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="56cm" height="37cm" version="1.1">
							<image xlink:href="'.public_path('/images/jupiter.jpg').'" x="174" y="66" height="65px" width="116px" style=""/>
					</svg>
					<g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Jupiter Garments Accessories</text>

					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">STYLE : '.$style.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REF : '.$name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DATE : '.$created_date.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Size: 100x70mm</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DESCRIPTION : '.$tesco_description.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REV.NO :</text>
					</g>
					</g>
					</svg>';
					$pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			});
	   
	   
			// Custom Footer
			PDF::setFooterCallback(function($pdf) {
			$tesco_po_number=$this->tesco_po_number;
			$customer_name=$this->customer_name;
			// Position at 15 mm from bottom
			$pdf->SetY(-50);
			$pdf->SetX(9.5);
			// Set font
			//$pdf->SetFont('frankgothcnbt');
			// Page number
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188mm" height="65.2667mm" viewBox="0 0 1360 168" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L1352,52 L1352,53 L8,53 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,53 L1352,53 L1352,54 L8,54 L8,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L8,54 L9,53 L9,52 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,53 L1351,54 L1352,54 L1352,52 L1351,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L1352,56 L1352,57 L8,57 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,57 L1352,57 L1352,58 L8,58 L8,57"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L8,58 L9,57 L9,56 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,57 L1351,58 L1352,58 L1352,56 L1351,57"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M385,79 L386,79 L386,157 L385,157 L385,79"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M764,79 L765,79 L765,157 L764,157 L764,79"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">CUSTOMER : '.$customer_name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">APPROVED BY : </text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">COMMENTS IF ANY :</text>
					</g>
					</g>
					</svg>';
					
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		});
//          print_r($svg);
        PDF::setImageScale(1.66);
//        PDF::setPrintHeader(false);
//        PDF::setPrintFooter(false);
        PDF::SetFont('calibrib');
		PDF::AddPage('P', 'A4');
		//PDF::RoundedRect(10, 10, 100, 70, 0, '0000');
		//PDF::RoundedRect(12.5, 12.5, 95, 65, 0, '0000');
		 
		$style6 = array('width' => 0.353, 'cap' => '', 'join' => '', 'dash' => '', 'color' => '');
		 
//		PDF::SetAutoPageBreak(TRUE);
		$i=0;
		$j=0;
		$yy=50;
		foreach($svgarr as $value)
        {
			$svg=urldecode($value);
			
			if($i == 3)
			{
				PDF::AddPage('P', 'A4');
				//PDF::RoundedRect(10, 10, 100, 70, 0, '0000');
				//PDF::RoundedRect(12.5, 12.5, 95, 65, 0, '0000');
				
				PDF::RoundedRect(55, 47.5, 100, 70, 0.000001, '1111', null, $style6);
				PDF::RoundedRect(57.5, 50, 95, 65, 0.000001, '1111', null, $style6);
				$yy=50;
				PDF::ImageSVG('@' . $svg, $x=57.5, $y=$yy, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
				$i = 0;
			}
			else  if($i == 0)
			{
				//PDF::RoundedRect(10, 100, 100, 70, 0, '0000');
				//PDF::RoundedRect(12.5, 102.5, 95, 65, 0, '0000');
				
				PDF::RoundedRect(55, 47.5, 100, 70, 0.000001, '1111', null, $style6);
				PDF::RoundedRect(57.5, 50, 95, 65, 0.000001, '1111', null, $style6);
				$yy=50;
				PDF::ImageSVG('@' . $svg, $x=57.5, $y=$yy, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
				
			}
			else if($i == 1)
			{
				//PDF::RoundedRect(10, 100, 100, 70, 0, '0000');
				//PDF::RoundedRect(12.5, 102.5, 95, 65, 0, '0000');
				PDF::RoundedRect(55, 122.5, 100, 70, 0.000001, '1111', null, $style6);
				PDF::RoundedRect(57.5, 125, 95, 65, 0.000001, '1111', null, $style6);
				$yy += 75;
				PDF::ImageSVG('@' . $svg, $x=57.5, $y=$yy, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			}
			else
			{
				//PDF::RoundedRect(10, 190, 100, 70, 0, '0000');
				//PDF::RoundedRect(12.5, 192.5, 95, 65, 0, '0000');
				PDF::RoundedRect(55, 197.5, 100, 70, 0.000001, '1111', null, $style6);
				PDF::RoundedRect(57.5, 200, 95, 65, 0.000001, '1111', null, $style6);
				$yy += 75;
				PDF::ImageSVG('@' . $svg, $x=57.5, $y=$yy, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			}
			$i++;
			$j++;
		 }
        PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
        print_r(url('/storage/app/template/'.$name.'.pdf'));
    }
	
	/**
	 * Print Preview For 100 * 70 Size Stickers
	 *
	 * @return pdf
	 */
	
     public function proofPdfSmall(Request $request)
    {
		 
		 $name=$request->name;
		 $color=$request->color;
		 $svgarr = explode(',_',$request->svg);
         //$svg=urldecode($request->svg);
         define ('PDF_FONT_NAME_MAIN', 'calibrib');
         define ('PDF_FONT_NAME_DATA', 'calibrib');
//          print_r($svg);
		
		$orchestra_color = DB::table('orchestra_color_code')->select('*')->where('code',$color)->get();
		if(count($orchestra_color) > 0)
		{
			$color_code=$orchestra_color{0}->french;
		}
		else
		{
			$color_code="";
		}
		
		PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
		PDF::SetAutoPageBreak(TRUE);
        PDF::SetFont('calibrib');
		//PDF::RoundedRect(10, 10, 100, 70, 0, '0000');
		//PDF::RoundedRect(12.5, 12.5, 95, 65, 0, '0000');
		 
		$style6 = array('width' => 0.353, 'cap' => '', 'join' => '', 'dash' => '', 'color' => '');
		 
//		PDF::SetAutoPageBreak(TRUE);
		foreach($svgarr as $value)
        {
			$svg=urldecode($value);
			PDF::SetMargins(0, 0, 0, false);
			PDF::AddPage('P',array(324,442));
			PDF::setImageScale(1.66);
			
			//Top Left Corner
			PDF::Line(11.5, 3, 11.5, 10.5, $style6); //straight
			PDF::Line(3, 10.5, 11.5, 10.5, $style6); //wide

			//Bottom Left Corner
			PDF::Line(11.5, 431.5, 11.5, 438.5, $style6);
			PDF::Line(3, 431.5, 11.5, 431.5, $style6);

			//Top Right Corner
			PDF::Line(312.5, 3, 312.5, 10.5, $style6);
			PDF::Line(312.5, 10.5, 321, 10.5, $style6);

			//Bottom Right Corner
			PDF::Line(312.5, 431.5, 321, 431.5, $style6);
			PDF::Line(312.5, 431.5, 312.5, 438, $style6);
			
			
			PDF::Line(11.5, 3, 11.5, 10.5, $style6);
			PDF::Line(112, 3, 112, 10.5, $style6);
			PDF::Line(212, 3, 212, 10.5, $style6);
			
			PDF::Line(3, 81, 11.5, 81, $style6);
			PDF::Line(3, 151, 11.5, 151, $style6);
			PDF::Line(3, 221, 11.5, 221, $style6);
			PDF::Line(3, 291, 11.5, 291, $style6);
			PDF::Line(3, 361, 11.5, 361, $style6);
			
			PDF::Line(312.5, 81, 321, 81, $style6);
			PDF::Line(312.5, 151, 321, 151, $style6);
			PDF::Line(312.5, 221, 321, 221, $style6);
			PDF::Line(312.5, 291, 321, 291, $style6);
			PDF::Line(312.5, 361, 321, 361, $style6);
			
			
			PDF::Line(112, 431.5, 112, 438.5, $style6);
			PDF::Line(212, 431.5, 212, 438.5, $style6);
			
			PDF::RoundedRect(14.5, 13.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=14.5, $y=13.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(114.5, 13.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=114.5, $y=13.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(214.5, 13.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=214.5, $y=13.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(14.5, 83.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=14.5, $y=83.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(114.5, 83.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=114.5, $y=83.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(214.5, 83.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=214.5, $y=83.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(14.5, 153.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=14.5, $y=153.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(114.5, 153.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=114.5, $y=153.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(214.5, 153.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=214.5, $y=153.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(14.5, 223.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=14.5, $y=223.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(114.5, 223.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=114.5, $y=223.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(214.5, 223.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=214.5, $y=223.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(14.5, 293.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=14.5, $y=293.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(114.5, 293.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=114.5, $y=293.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(214.5, 293.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=214.5, $y=293.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(14.5, 363.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=14.5, $y=363.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(114.5, 363.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=114.5, $y=363.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			PDF::RoundedRect(214.5, 363.5, 95, 65, 0.000001, '1111', null, $style6);
			PDF::ImageSVG('@' . $svg, $x=214.5, $y=363.5, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		 }
        PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
        print_r(url('/storage/app/template/'.$name.'.pdf'));
    }
	/**
     * Function to get tesco tsl template UK , CE size chart based on size entered.
     * @author <Praveen>
     * @param [string] $[size] [<Size entered>], [string] $[zone] [<zone selected>]
     * @return equivalent uk,ce size for size entered
     */
    public function getTescoTSLSizechart(Request $request)
    {
       try {
             
		   $size = $request['size'];
		   $uvc_tco = $request['uvc_tco'];

		   $size = DB::table('orchestra_size_chart')->select('cm as size')->where('age', 'ilike', $size)->where('is_enabled','=',1)->get();
		   
		   $orchestra_parcel_quantity_details = DB::table('orchestra_parcel_quantity_details')->select('*')->where('UVC_TCO', $uvc_tco)->get();
           
           return response()->json(['size_chart' => $size,'opqt' => $orchestra_parcel_quantity_details]);
		   
       } catch (\Exception $e) {
           return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
       }
    }
   
}
