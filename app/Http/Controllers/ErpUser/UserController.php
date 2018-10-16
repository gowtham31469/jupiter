<?php
namespace App\Http\Controllers\ErpUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Response;
use View;
use DB;

class UserController extends Controller
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
     * @author [Gba] <[guberakannan@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		return view('erp.home');
	}
	
	/**
     * Show the application dashboard.
     * @author [Gba] <[guberakannan@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
		return view('erp.users.user-list');
	}

}