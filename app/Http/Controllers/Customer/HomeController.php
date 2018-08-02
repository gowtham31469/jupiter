<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Classes\CustomerDashboard;
use Response;
use View;
use DB;
use Auth;

class HomeController extends Controller
{
    private $tcl1;
    private $tcl2;
    private $tcl4;
    private $thl1;
    private $thl3;
    private $tsl1;
    private $tsl3;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('customer_auth');
        $this->tcl1 = "TCL 1";
        $this->tcl2 = "TCL 2";
        $this->tcl4 = "TCL 4";
        $this->thl1 = "THL 1";
        $this->thl3 = "THL 3";
        $this->tsl1 = "TSL 1";
        $this->tsl3 = "TSL 3";
    }

    /**
     * Show the application dashboard.
     * @author [Praveen] <[praveen@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $customObj   = new CustomerDashboard();
            $customer_id = Auth::guard('web_customer')->id();
            $customer_role_id = DB::table('customer_profile')->select('*')
                              ->where('id',$customer_id)->first();
            $approved_orders = 0;
            $app_pending_orders = 0;
            $cancelled_orders = 0;

            $buyers = DB::table('buyers')
                          ->select('buyers.*')
                          ->join('customer_profile_buyer','customer_profile_buyer.buyer_id','buyers.id')
                          ->where('customer_profile_buyer.customer_profile_id',$customer_id)
                          ->where('buyers.is_active',1)
                          ->get();

            if($customer_role_id->role_id == 1)
            {
                $approved_orders = $customObj->getAllApprovedOrders();
                $approved_count  = count($approved_orders['tcl1'])+count($approved_orders['tcl2'])+count($approved_orders['tcl4'])+count($approved_orders['thl1'])+count($approved_orders['thl3'])+count($approved_orders['tsl1']) + count($approved_orders['tsl3']);

                $app_pending_orders = $customObj->getAllPendingOrders();
                $pending_count  = count($app_pending_orders['tcl1'])+count($app_pending_orders['tcl2'])+count($app_pending_orders['tcl4'])+count($app_pending_orders['thl1'])+count($app_pending_orders['thl3'])+count($app_pending_orders['tsl1']) + count($app_pending_orders['tsl3']);

                $cancelled_orders = $customObj->getAllCancelledOrders();
                $cancelled_count  = count($cancelled_orders['tcl1'])+count($cancelled_orders['tcl2'])+count($cancelled_orders['tcl4'])+count($cancelled_orders['thl1'])+count($cancelled_orders['thl3'])+count($cancelled_orders['tsl1']) + count($cancelled_orders['tsl3']);
            }
            else{
                $approved_orders = $customObj->getApprovedOrders();
                $approved_count  = count($approved_orders['tcl1'])+count($approved_orders['tcl2'])+count($approved_orders['tcl4'])+count($approved_orders['thl1'])+count($approved_orders['thl3'])+count($approved_orders['tsl1']) + count($approved_orders['tsl3']);
                $app_pending_orders = $customObj->getPendingOrders();
                $pending_count  = count($app_pending_orders['tcl1'])+count($app_pending_orders['tcl2'])+count($app_pending_orders['tcl4'])+count($app_pending_orders['thl1'])+count($app_pending_orders['thl3'])+count($app_pending_orders['tsl1']) + count($app_pending_orders['tsl3']);
                $cancelled_orders = $customObj->getCancelledOrders();
                $cancelled_count  = count($cancelled_orders['tcl1'])+count($cancelled_orders['tcl2'])+count($cancelled_orders['tcl4'])+count($cancelled_orders['thl1'])+count($cancelled_orders['thl3'])+count($cancelled_orders['tsl1']) + count($cancelled_orders['tsl3']);

            }
            
            
            $buyer_count = count($buyers);
            return view('customer/home')->with(['buyers' => $buyers,'buyer_count' => $buyer_count,'approved_orders'=>$approved_count , 'app_pending_orders'=>$pending_count, 'cancelled_orders' =>$cancelled_count]);
        } catch (\Exception $e)
          {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
        
    }


    public function getApprovedOrdersList()
    {
      try {
          $customObj   = new CustomerDashboard();
          $customer_id = Auth::guard('web_customer')->id();
          $customer_role_id = DB::table('customer_profile')->select('*')
                              ->where('id',$customer_id)->first();
          if($customer_role_id->role_id == 1)
            {
                $approved_orders = $customObj->getAllApprovedOrders();
            }
            else{
                $approved_orders = $customObj->getApprovedOrders();
            }

          $result = response()->json(['error'=> false,'message' =>'success','data'=>$approved_orders],200);
      } 
      catch (\Exception $e)
      {
             $result =  response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
      return $result;
    }

    public function getPendingOrdersList()
    {
      try {
          $customObj   = new CustomerDashboard();

          $customer_id = Auth::guard('web_customer')->id();
          $customer_role_id = DB::table('customer_profile')->select('*')
                              ->where('id',$customer_id)->first();
          if($customer_role_id->role_id == 1)
            {
                $pending_orders = $customObj->getAllPendingOrders();
            }
            else{
                $pending_orders = $customObj->getPendingOrders();
            }

          $result = response()->json(['error'=> false,'message' =>'success','data'=>$pending_orders],200);
      } 
      catch (\Exception $e)
      {
             $result =  response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
      return $result;
    }


     public function getCancelledOrdersList()
    {
      try {
          $customObj   = new CustomerDashboard();
          $customer_id = Auth::guard('web_customer')->id();
          $customer_role_id = DB::table('customer_profile')->select('*')
                              ->where('id',$customer_id)->first();
          if($customer_role_id->role_id == 1)
            {
                $cancelled_orders = $customObj->getAllCancelledOrders();
            }
            else{
                $cancelled_orders = $customObj->getCancelledOrders();
            }

          $result = response()->json(['error'=> false,'message' =>'success','data'=>$cancelled_orders],200);
      } 
      catch (\Exception $e)
      {
             $result =  response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
      }
      return $result;
    }


    public function viewedApprovedOrder(Request $request)
    {
      try
      {
        $table_name   = $request->table_name;
        $row_id = $request->id;
        
         $data   = DB::table($table_name)->where('id',$row_id)
                          ->update([
                            'app_ord_flag' => 1,
                          ]);
        
          $result = response()->json(['error'=> false,'message' =>'success'],200);
      }
          catch (\Exception $e)
          {
              $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
          return $result;
    }

    public function viewedPendingOrder(Request $request)
    {
      try
      {
        $table_name   = $request->table_name;
        $row_id = $request->id;
        
         $data   = DB::table($table_name)->where('id',$row_id)
                          ->update([
                            'pen_ord_flag' => 1,
                          ]);
        
          $result = response()->json(['error'=> false,'message' =>'success','approved_orders'=>$approved_orders],200);
      }
          catch (\Exception $e)
          {
              $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
          return $result;
    }

    public function viewedCancelledOrder(Request $request)
    {
      try
      {
        $table_name   = $request->table_name;
        $row_id       = $request->id;
        $data   = DB::table($table_name)->where('id',$row_id)
                          ->update([
                            'cancel_ord_flag' => 1
                          ]);
        
          $result = response()->json(['error'=> false,'message' =>'success'],200);
      }
          catch (\Exception $e)
          {
              $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
          return $result;
    }
    /**
     * Show the customer order status.
     * @return \Illuminate\Http\Response
     */
    public function customerOrderStatus()
    {
        try{
            $customer_id = Auth::guard('web_customer')->user()->id;
            $buyers = DB::table('buyers')
                          ->select('buyers.*')
                          ->join('customer_profile_buyer','customer_profile_buyer.buyer_id','buyers.id')
                          ->where('customer_profile_buyer.customer_profile_id',$customer_id)
                          ->get();
            $id = Auth::guard('web_customer')->id();
            $customer = DB::table('customer_profile')
                          ->select('*')
                          ->where('customer_profile.id','=',$id)
                          ->first();
            $buyer_count = count($buyers);
            $tesco_brand = DB::table('tesco_brand')->select('brand','id')->get();
            $tesco_department = DB::table('tesco_department')->select('department','id')->get();
            $tesco_suppliers = DB::table('tesco_suppliers')->select('tesco_suppliers.supplier_name','tesco_suppliers.id')->where('is_enabled','=',1)->join('customer_metadata','customer_metadata.customer_metadata_id','tesco_suppliers.id')->where('customer_metadata.customer_id','=',$customer->company_id)->get();
            return view('customer/customer-order-status')->with(['buyers' => $buyers,'tesco_brand' => $tesco_brand,'tesco_department' => $tesco_department,'tesco_suppliers' => $tesco_suppliers,'buyer_count' => $buyer_count]);
        } catch (\Exception $e)
          {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
        
    }

    /**
     * Show the customer orders.
     * @return \Illuminate\Http\Response
     */
    public function customerOrders()
    {
        try{
            $customer_id = Auth::guard('web_customer')->user()->id;
            $buyers = DB::table('buyers')
                          ->select('buyers.*')
                          ->join('customer_profile_buyer','customer_profile_buyer.buyer_id','buyers.id')
                          ->where('customer_profile_buyer.customer_profile_id',$customer_id)
                          ->get();
            $buyer_count = count($buyers);
            return view('customer/customer-orders')->with(['buyers' => $buyers,'buyer_count' => $buyer_count]);
        } catch (\Exception $e) {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        
        }
        
    }
	
	/**
		 * Show the Reorder page.
		 * @return \Illuminate\Http\Response
    */
    public function reorder()
    {
        try{
            $customer_id = Auth::guard('web_customer')->user()->id;
            $buyers = DB::table('buyers')
                          ->select('buyers.*')
                          ->join('customer_profile_buyer','customer_profile_buyer.buyer_id','buyers.id')
                          ->where('customer_profile_buyer.customer_profile_id',$customer_id)
                          ->get();
            $buyer_count = count($buyers);
            return view('customer/reorder')->with(['buyers' => $buyers,'buyer_count' => $buyer_count]);
        } catch (\Exception $e) {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        
        }
        
    }

}