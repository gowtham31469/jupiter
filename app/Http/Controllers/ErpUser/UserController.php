<?php
namespace App\Http\Controllers\ErpUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Response;
use View;
use DB;
use App\Buyer;

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
     * Show the users list.
     * @author [Gba] <[guberakannan@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
		$users 		= App\Erpuser::users();
		$company 	= DB::table('company')->pluck('name', 'id')->all();
		$buyers 	= \App\Buyer::pluck('buyer_name as name', 'id')->all();
		return view('erp.users.user-list', ['users' => $users, 'company' => $company, 'buyers' => $buyers]);
	}
	/**
     * Store the users.
     * @author [Gba] <[guberakannan@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$input['email']	= $request->email;
		$email_rules    = array('email' => 'unique:erpusers,email');
		$emailValidator = Validator::make($input,$email_rules);

		if($emailValidator->fails())
		{
			return response()->json(['error'=> true,'message' =>'Email Already Exists'],409);
		}
		else
		{
			App\Erpuser::create([
				'name'		=>	$request->name,
				'email'		=>	$request->email,
				'password'	=>	random_bytes(8)
			]);
			return response()->json(['error' => false], 200);
		}
	}

}