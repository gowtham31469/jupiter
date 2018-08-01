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
			
			$new_orders['tcl1'] = DB::table('tesco_tcl1_orders')
									->whereRaw('created_at > current_date - 1')
									->select('*')
									->get();
			$new_orders['tcl2'] = DB::table('tesco_tcl2_orders')
									->whereRaw('created_at > current_date - 1')
									->select('*')
									->get();
			$new_orders['tcl4'] = DB::table('tesco_tcl4_orders')
									->whereRaw('created_at > current_date - 1')
									->select('*')
									->get();
			$new_orders['thl1'] = DB::table('tesco_thl1_orders')
									->whereRaw('created_at > current_date - 1')
									->select('*')
									->get();
			$new_orders['thl3'] = DB::table('tesco_thl3_orders')
									->whereRaw('created_at > current_date - 1')
									->select('*')
									->get();
			$new_orders['tsl1'] = DB::table('tesco_tsl1_orders')
									->whereRaw('created_at > current_date - 1')
									->select('*')
									->get();
			$new_orders['tsl3'] = DB::table('tesco_tsl3_orders')
									->whereRaw('created_at > current_date - 1')
									->select('*')
									->get();
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
			
			$approved_orders['tcl1'] = DB::table('tesco_tcl1_orders')
									->where('order_status','Order Approved')
									->select('*')
									->get();
			$approved_orders['tcl2'] = DB::table('tesco_tcl2_orders')
									->where('order_status','Order Approved')
									->select('*')
									->get();
			$approved_orders['tcl4'] = DB::table('tesco_tcl4_orders')
									->where('order_status','Order Approved')
									->select('*')
									->get();
			$approved_orders['thl1'] = DB::table('tesco_thl1_orders')
									->where('order_status','Order Approved')
									->select('*')
									->get();
			$approved_orders['thl3'] = DB::table('tesco_thl3_orders')
									->where('order_status','Order Approved')
									->select('*')
									->get();
			$approved_orders['tsl1'] = DB::table('tesco_tsl1_orders')
									->where('order_status','Order Approved')
									->select('*')
									->get();
			$approved_orders['tsl3'] = DB::table('tesco_tsl3_orders')
									->where('order_status','Order Approved')
									->select('*')
									->get();
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
			
			$pending_orders['tcl1'] = DB::table('tesco_tcl1_orders')
									->where('order_status','Order Placed')
									->select('*')
									->get();
			$pending_orders['tcl2'] = DB::table('tesco_tcl2_orders')
									->where('order_status','Order Placed')
									->select('*')
									->get();
			$pending_orders['tcl4'] = DB::table('tesco_tcl4_orders')
									->where('order_status','Order Placed')
									->select('*')
									->get();
			$pending_orders['thl1'] = DB::table('tesco_thl1_orders')
									->where('order_status','Order Placed')
									->select('*')
									->get();
			$pending_orders['thl3'] = DB::table('tesco_thl3_orders')
									->where('order_status','Order Placed')
									->select('*')
									->get();
			$pending_orders['tsl1'] = DB::table('tesco_tsl1_orders')
									->where('order_status','Order Placed')
									->select('*')
									->get();
			$pending_orders['tsl3'] = DB::table('tesco_tsl3_orders')
									->where('order_status','Order Placed')
									->select('*')
									->get();
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
			
			$app_pipo['tcl1_1'] = DB::table('tesco_tcl1_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->select('*')
									->get();
			
			$app_pipo['tcl1_2'] = DB::table('tesco_tcl1_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->select('*')
									->get();
			
			$app_pipo['tcl2_1'] = DB::table('tesco_tcl2_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->select('*')
									->get();
			
			$app_pipo['tcl2_2'] = DB::table('tesco_tcl2_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->select('*')
									->get();
			
			$app_pipo['tcl4_1'] = DB::table('tesco_tcl4_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->select('*')
									->get();
			
			$app_pipo['tcl4_2'] = DB::table('tesco_tcl4_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->select('*')
									->get();
			
			$app_pipo['thl1_1'] = DB::table('tesco_thl1_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->select('*')
									->get();
			
			$app_pipo['thl1_2'] = DB::table('tesco_thl1_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->select('*')
									->get();
			
			$app_pipo['thl3_1'] = DB::table('tesco_thl3_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->select('*')
									->get();
			
			$app_pipo['thl3_2'] = DB::table('tesco_thl3_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->select('*')
									->get();
			$app_pipo['tsl1_1'] = DB::table('tesco_tsl1_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->select('*')
									->get();
			
			$app_pipo['tsl1_2'] = DB::table('tesco_tsl1_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->select('*')
									->get();
			
			$app_pipo['tsl3_1'] = DB::table('tesco_tsl3_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Pending')
									->whereIn('pi_status',['PI Approval Pending',""])
									->select('*')
									->get();
			
			$app_pipo['tsl3_2'] = DB::table('tesco_tsl3_orders')
									->where('order_status','Order Approved')
									->where('po_status','PO Uploaded')
									->where('pi_status','PI Approval Pending')
									->select('*')
									->get();
			
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
			
			$pending_class    	= new Dashboard();
            $pendingproduction  = $pending_class->pendingProduction();
			
			 $result = response()->json(['error'=> false,'message' =>'success','pendingproduction'=>$pendingproduction],200);
		}
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}

   
}
