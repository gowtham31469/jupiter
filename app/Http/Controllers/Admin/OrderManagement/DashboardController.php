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
use App\FinancialYearResetDate;
use Carbon\Carbon;
use App\Classes\Dashboard;
use Session;

class DashboardController extends Controller
{
	private $financial_year_date;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
	   $this->middleware('auth');
	   $this->financial_year_date = FinancialYearResetDate::value('financial_year_date');
    }
	
	public function newOrders(Request $request)
	{
		try
		{
			$fiscal_year   = $request->fiscal_year;
			$new_class    = new Dashboard();
            $new_orders      = $new_class->newOrders($fiscal_year);			
            $result = response()->json(['error'=> false,'message' =>'success','new_orders'=>$new_orders],200);
       
		}
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}
	
	public function approvedOrders(Request $request)
	{
		try
		{
			$fiscal_year   = $request->fiscal_year;
			
			$approved_class    = new Dashboard();

            $approved_orders   = $approved_class->approvedOrders($fiscal_year);
			
            $result = response()->json(['error'=> false,'message' =>'success','approved_orders'=>$approved_orders],200);
       
		}
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}
	
	public function pendingOrders(Request $request)
	{
		try
		{
			$fiscal_year   = $request->fiscal_year;

			$pending_class    = new Dashboard();

            $pending_orders   = $pending_class->pendingOrders($fiscal_year);
			
            $result = response()->json(['error'=> false,'message' =>'success','pending_orders'=>$pending_orders],200);
       
		}
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}
	
	public function aowp(Request $request)
	{
		try
		{
			
			$fiscal_year   = $request->fiscal_year;

			$aowp_class    = new Dashboard();

            $app_pipo  	   = $aowp_class->aowp($fiscal_year);
			
            $result = response()->json(['error'=> false,'message' =>'success','app_pipo'=>$app_pipo],200);
       
		}
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}
	
	public function viewedPenProduction(Request $request)
	{
		try
		{
			$table_name   = $request->table_name;
			$row_id = $request->id;
			
			 $data   = DB::table($table_name)->where('id',$row_id)
                        ->update([
                          'pen_pro_flag' => 1,
                        ]);
			
			$pending_class    = new Dashboard();

			if(Session::get('fiscal-year') != null) {
				$year = explode('-', Session::get('fiscal-year'));
				$from_date = $year[0].'-'.$this->financial_year_date;
				$to_date =Carbon::parse($from_date)->addYear()->subDay()->toDateString(); 
			} else {
				$from_date = $this->financial_year_start;
				$to_date =$this->financial_year_end; 
			}

            $pendingproduction      = $pending_class->pendingProduction($from_date, $to_date);
			 $result = response()->json(['error'=> false,'message' =>'success','pendingproduction'=>$pendingproduction],200);
		}
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}
	
	public function viewedPenProductionAll(Request $request)
	{
		try
		{
			$table_name   = $request->table_name;
			$row_id = $request->id;
			
			
			foreach($row_id as $value)
			{
				$tab = explode("-",$value);
				$table_name=$tab[0];
				$id=$tab[1];
				$data   = DB::table($table_name)->where('id',$id)
                        ->update([
                          'pen_pro_flag' => 1,
                ]);
			}
			
			$pending_class    = new Dashboard();

			if(Session::get('fiscal-year') != null) {
				$year = explode('-', Session::get('fiscal-year'));
				$from_date = $year[0].'-'.$this->financial_year_date;
				$to_date =Carbon::parse($from_date)->addYear()->subDay()->toDateString(); 
			} else {
				$from_date = $this->financial_year_start;
				$to_date =$this->financial_year_end; 
			}

            $pendingproduction      = $pending_class->pendingProduction($from_date, $to_date);
			 $result = response()->json(['error'=> false,'message' =>'success','pendingproduction'=>$pendingproduction],200);
		}
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}
	
	public function viewedNewOrders(Request $request)
	{
		try
		{
			$table_name   = $request->table_name;
			$row_id = $request->id;

			$fiscal_year   = $request->fiscal_year;
			
			 $data   = DB::table($table_name)->where('id',$row_id)
                        ->update([
                          'new_ord_flag' => 1,
                        ]);
			
			$new_class    = new Dashboard();

            $new_orders      = $new_class->newOrders($fiscal_year);
			
            $result = response()->json(['error'=> false,'message' =>'success','new_orders'=>$new_orders],200);
		}
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}
	
	public function viewedNewOrdersAll(Request $request)
	{
		try
		{
			$table_name   = $request->table_name;
			$row_id = $request->id;
			$fiscal_year   = $request->fiscal_year;

			$year = explode('-', $fiscal_year);
			$from_date = $year[0].'-'.$this->financial_year_date;
			$to_date =Carbon::parse($from_date)->addYear()->subDay()->toDateString();
			
			foreach($row_id as $value)
			{
				$tab = explode("-",$value);
				$table_name=$tab[0];
				$id=$tab[1];
				$data   = DB::table($table_name)->where('id',$id)
                        ->update([
                          'new_ord_flag' => 1,
                        ]);
			}
			
			$new_class    = new Dashboard();

            $new_orders      = $new_class->newOrders($fiscal_year);
			
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
									->where('order_status','!=','Order Cancelled')
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tcl4_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();

			$new_count['tcl5'] = DB::table('tesco_tcl5_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->where('order_status','!=','Order Cancelled')
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tcl5_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();

			$new_count['thl1'] = DB::table('tesco_thl1_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->where('order_status','!=','Order Cancelled')
									->where('is_order_count_empty',0)
									->whereBetween('tesco_thl1_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			$new_count['thl3'] = DB::table('tesco_thl3_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->where('order_status','!=','Order Cancelled')
									->where('is_order_count_empty',0)
									->whereBetween('tesco_thl3_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			$new_count['tsl1'] = DB::table('tesco_tsl1_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->where('order_status','!=','Order Cancelled')
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tsl1_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();

			$new_count['tsl2'] = DB::table('tesco_tsl2_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->where('order_status','!=','Order Cancelled')
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tsl2_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();

			$new_count['tsl3'] = DB::table('tesco_tsl3_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->where('order_status','!=','Order Cancelled')
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tsl3_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();

			$new_count['wcl'] = DB::table('tesco_washcare_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->where('order_status','!=','Order Cancelled')
									->where('is_order_count_empty',0)
									->whereBetween('tesco_washcare_orders.created_at', [$from_date, $to_date])
									->select(DB::raw('count(*) as sum'))
									->get();
			
			$tot_count = $new_count['tcl1']{0}->sum + $new_count['tcl2']{0}->sum + $new_count['tcl4']{0}->sum + $new_count['tcl5']{0}->sum + $new_count['thl1']{0}->sum + $new_count['thl3']{0}->sum + $new_count['tsl1']{0}->sum + $new_count['tsl2']{0}->sum + $new_count['tsl3']{0}->sum + $new_count['wcl']{0}->sum;
			
            $result = response()->json(['error'=> false,'message' =>'success','new_orders'=>$new_orders,'tot_count'=>$tot_count],200);
		}
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}
	
	public function viewedPenOrders(Request $request)
	{
		try
		{
			$table_name   = $request->table_name;
			$row_id = $request->id;
			$fiscal_year   = $request->fiscal_year;
			
			 $data   = DB::table($table_name)->where('id',$row_id)
                        ->update([
                          'pen_ord_flag' => 1,
                        ]);
			
			$pending_class    = new Dashboard();

            $pending_orders   = $pending_class->pendingOrders($fiscal_year);
			
            $result = response()->json(['error'=> false,'message' =>'success','pending_orders'=>$pending_orders],200);
		}
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}
	
	public function viewedPenOrdersAll(Request $request)
	{
		try
		{
			$table_name   = $request->table_name;
			$row_id = $request->id;
			$fiscal_year   = $request->fiscal_year;

			$year = explode('-', $fiscal_year);
			$from_date = $year[0].'-'.$this->financial_year_date;
			$to_date =Carbon::parse($from_date)->addYear()->subDay()->toDateString();

			foreach($row_id as $value)
			{
				$tab = explode("-",$value);
				$table_name=$tab[0];
				$id=$tab[1];
				
			 	$data   = DB::table($table_name)->where('id',$id)
                        ->update([
                          'pen_ord_flag' => 1,
                        ]);
			}
			
			$pending_class    = new Dashboard();

            $pending_orders_div   = $pending_class->pendingOrders($fiscal_year);
			
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

			$pending_orders['tcl5'] = DB::table('tesco_tcl5_orders')
									->where('order_status','Order Placed')
									->where('pen_ord_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tcl5_orders.created_at', [$from_date, $to_date])
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

			$pending_orders['tsl2'] = DB::table('tesco_tsl2_orders')
									->where('order_status','Order Placed')
									->where('pen_ord_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tsl2_orders.created_at', [$from_date, $to_date])
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
			
			$pending_count = $pending_orders['tcl1']{0}->sum + $pending_orders['tcl2']{0}->sum + $pending_orders['tcl4']{0}->sum + $pending_orders['tcl5']{0}->sum + $pending_orders['thl1']{0}->sum + $pending_orders['thl3']{0}->sum + $pending_orders['tsl1']{0}->sum + $pending_orders['tsl2']{0}->sum + $pending_orders['tsl3']{0}->sum + $pending_orders['wcl']{0}->sum;
			
            $result = response()->json(['error'=> false,'message' =>'success','pending_orders'=>$pending_orders_div,'pending_count'=>$pending_count],200);
		}
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}
	
	public function viewedAppOrders(Request $request)
	{
		try
		{
			$table_name   = $request->table_name;
			$row_id = $request->id;
			$fiscal_year   = $request->fiscal_year;
			
			 $data   = DB::table($table_name)->where('id',$row_id)
                        ->update([
                          'app_ord_flag' => 1,
                        ]);
			
			$approved_class    = new Dashboard();

            $approved_orders   = $approved_class->approvedOrders($fiscal_year);
			
            $result = response()->json(['error'=> false,'message' =>'success','approved_orders'=>$approved_orders],200);
		}
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}
	
	public function viewedAppOrdersAll(Request $request)
	{
		try
		{
			$table_name   = $request->table_name;
			$row_id = $request->id;
			$fiscal_year   = $request->fiscal_year;

			$year = explode('-', $fiscal_year);
			$from_date = $year[0].'-'.$this->financial_year_date;
			$to_date =Carbon::parse($from_date)->addYear()->subDay()->toDateString(); 
			
			foreach($row_id as $value)
			{
				$tab = explode("-",$value);
				$table_name=$tab[0];
				$id=$tab[1];
				
					 $data   = DB::table($table_name)->where('id',$id)
                        ->update([
                          'app_ord_flag' => 1,
                        ]);
			}
			
			$approved_class    = new Dashboard();

            $approved_orders_div   = $approved_class->approvedOrders($fiscal_year);
			
			
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

			$approved_orders['tcl5'] = DB::table('tesco_tcl5_orders')
									->where('order_status','Order Approved')
									->where('app_ord_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tcl5_orders.created_at', [$from_date, $to_date])
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

			$approved_orders['tsl2'] = DB::table('tesco_tsl2_orders')
									->where('order_status','Order Approved')
									->where('app_ord_flag',0)
									->where('is_order_count_empty',0)
									->whereBetween('tesco_tsl2_orders.created_at', [$from_date, $to_date])
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
			
			$approve_count = $approved_orders['tcl1']{0}->sum + $approved_orders['tcl2']{0}->sum + $approved_orders['tcl4']{0}->sum + $approved_orders['tcl5']{0}->sum + $approved_orders['thl1']{0}->sum + $approved_orders['thl3']{0}->sum + $approved_orders['tsl1']{0}->sum + $approved_orders['tsl2']{0}->sum + $approved_orders['tsl3']{0}->sum + $approved_orders['wcl']{0}->sum;
			
            $result = response()->json(['error'=> false,'message' =>'success','approved_orders'=>$approved_orders_div,'tot_count'=>$approve_count],200);
		}
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}
	
	public function viewedAOWP(Request $request)
	{
		try
		{
			$table_name   = $request->table_name;
			$row_id = $request->id;
			$fiscal_year   = $request->fiscal_year;
			
			 $data   = DB::table($table_name)->where('id',$row_id)
                        ->update([
                          'app_pipo_flag' => 1,
                        ]);
			
			$aowp_class    = new Dashboard();

            $app_pipo  	   = $aowp_class->aowp($fiscal_year);
			
            $result = response()->json(['error'=> false,'message' =>'success','app_pipo'=>$app_pipo],200);
		}
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}
	public function viewedAOWPAll(Request $request)
	{
		try
		{
			$table_name   = $request->table_name;
			$row_id = $request->id;
			$fiscal_year   = $request->fiscal_year;
			
			foreach($row_id as $value)
			{
				$tab = explode("-",$value);
				$table_name=$tab[0];
				$id=$tab[1];
				
				$data   = DB::table($table_name)->where('id',$id)
                        ->update([
                          'app_pipo_flag' => 1,
                        ]);
			}
			
			
			$aowp_class    = new Dashboard();

            $app_pipo  	   = $aowp_class->aowp($fiscal_year);
			
			
            $result = response()->json(['error'=> false,'message' =>'success','app_pipo'=>$app_pipo],200);
		}
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}

   
}
