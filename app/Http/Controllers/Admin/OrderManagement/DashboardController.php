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

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       $this->middleware('auth');
    }
	
	public function newOrders(Request $request)
	{
		try
		{
			$new_class    = new Dashboard();

            $new_orders      = $new_class->newOrders();
			
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
			
			$approved_class    = new Dashboard();

            $approved_orders   = $approved_class->approvedOrders();
			
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
			
			$pending_class    = new Dashboard();

            $pending_orders   = $pending_class->pendingOrders();
			
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
			
			$aowp_class    = new Dashboard();

            $app_pipo  	   = $aowp_class->aowp();
			
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

            $pendingproduction      = $pending_class->pendingProduction();
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

            $pendingproduction      = $pending_class->pendingProduction();
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
			
			 $data   = DB::table($table_name)->where('id',$row_id)
                        ->update([
                          'new_ord_flag' => 1,
                        ]);
			
			$new_class    = new Dashboard();

            $new_orders      = $new_class->newOrders();
			
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

            $new_orders      = $new_class->newOrders();
			
			$new_count['tcl1'] = DB::table('tesco_tcl1_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->select(DB::raw('count(*) as sum'))
									->get();
			$new_count['tcl2'] = DB::table('tesco_tcl2_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->select(DB::raw('count(*) as sum'))
									->get();
			$new_count['tcl4'] = DB::table('tesco_tcl4_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->select(DB::raw('count(*) as sum'))
									->get();
			$new_count['thl1'] = DB::table('tesco_thl1_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->select(DB::raw('count(*) as sum'))
									->get();
			$new_count['thl3'] = DB::table('tesco_thl3_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->select(DB::raw('count(*) as sum'))
									->get();
			$new_count['tsl1'] = DB::table('tesco_tsl1_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->select(DB::raw('count(*) as sum'))
									->get();
			$new_count['tsl3'] = DB::table('tesco_tsl3_orders')
									->whereRaw('created_at > current_date - 1')
									->where('new_ord_flag',0)
									->select(DB::raw('count(*) as sum'))
									->get();
			
			$tot_count = $new_count['tcl1']{0}->sum + $new_count['tcl2']{0}->sum + $new_count['tcl4']{0}->sum + $new_count['thl1']{0}->sum + $new_count['thl3']{0}->sum + $new_count['tsl1']{0}->sum + $new_count['tsl3']{0}->sum;
			
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
			
			 $data   = DB::table($table_name)->where('id',$row_id)
                        ->update([
                          'pen_ord_flag' => 1,
                        ]);
			
			$pending_class    = new Dashboard();

            $pending_orders   = $pending_class->pendingOrders();
			
            $result = response()->json(['error'=> false,'message' =>'success','pending_orders'=>$pending_orders],200);
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
			
			 $data   = DB::table($table_name)->where('id',$row_id)
                        ->update([
                          'app_ord_flag' => 1,
                        ]);
			
			$approved_class    = new Dashboard();

            $approved_orders   = $approved_class->approvedOrders();
			
            $result = response()->json(['error'=> false,'message' =>'success','approved_orders'=>$approved_orders],200);
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
			
			 $data   = DB::table($table_name)->where('id',$row_id)
                        ->update([
                          'app_pipo_flag' => 1,
                        ]);
			
			$aowp_class    = new Dashboard();

            $app_pipo  	   = $aowp_class->aowp();
			
            $result = response()->json(['error'=> false,'message' =>'success','app_pipo'=>$app_pipo],200);
		}
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}

   
}
