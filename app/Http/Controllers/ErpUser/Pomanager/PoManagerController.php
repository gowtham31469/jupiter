<?php
namespace App\Http\Controllers\ErpUser\Pomanager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Response;
use View;
use DB;

class PoManagerController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('erpuser');
   
    }

    /**
     * Show the application dashboard.
     * @author [Praveen] <[praveen@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		// $orders = DB::table('buyers')->where('is_active','=',1)->pluck('buyer_name','id')->all();
		
		 return view('erp.Pomanager.poverify',['orders' => $orders]);
	}

}