<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

use Response;
use View;
use DB;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('customer_auth');
    }

    /**
     * Show the application dashboard.
     * @author [Praveen] <[praveen@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $customer_id = Auth::guard('web_customer')->user()->id;
            $buyers = DB::table('buyers')
                          ->select('buyers.*')
                          ->join('customer_buyers','customer_buyers.buyer_id','buyers.id')
                          ->where('customer_buyers.customer_id',$customer_id)
                          ->where('buyers.is_active',1)
                          ->get();
            $buyer_count = count($buyers);
            return view('customer/home')->with(['buyers' => $buyers,'buyer_count' => $buyer_count]);
        } catch (\Exception $e)
          {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
        
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
                          ->join('customer_buyers','customer_buyers.buyer_id','buyers.id')
                          ->where('customer_buyers.customer_id',$customer_id)
                          ->get();
            $buyer_count = count($buyers);
            $tesco_brand = DB::table('tesco_brand')->select('brand','id')->get();
            $tesco_department = DB::table('tesco_department')->select('department','id')->get();
            $tesco_suppliers = DB::table('tesco_suppliers')->select('supplier_name','id')->where('is_enabled','=',1)->get();
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
                          ->join('customer_buyers','customer_buyers.buyer_id','buyers.id')
                          ->where('customer_buyers.customer_id',$customer_id)
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
                          ->join('customer_buyers','customer_buyers.buyer_id','buyers.id')
                          ->where('customer_buyers.customer_id',$customer_id)
                          ->get();
            $buyer_count = count($buyers);
            return view('customer/reorder')->with(['buyers' => $buyers,'buyer_count' => $buyer_count]);
        } catch (\Exception $e) {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        
        }
        
    }

}