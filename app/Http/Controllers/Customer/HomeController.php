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
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $customer_id = Auth::guard('web_customer')->user()->id;
            $buyers = DB::table('buyers')
                          ->select('*')
                          ->join('customer_buyers','customer_buyers.buyer_id','buyers.id')
                          ->where('customer_buyers.customer_id',$customer_id)
                          ->get();
            return view('customer/home')->with(['buyers' => $buyers]);
        } catch (\Exception $e)
          {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
        
    }
}