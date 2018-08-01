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
