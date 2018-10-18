<?php
namespace App\Http\Controllers\ErpUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Response;
use View;
use DB;
use App\Erpuser;
use App\ErpuserBuyerMapping;
use App\ErpuserCompanyMapping;
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
		$users 		= \App\Erpuser::users();
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
			return response()->json(['error'=> true,'message' =>'Email Already Exists'], 409);
		}
		else
		{
			$data = new Erpuser;
			
			$data->name = $request->name;
			$data->email = $request->email;
			$data->password = bcrypt(random_bytes(8));
			/*   Insert and get id of ErpUser   */
			if($data->save()) {
				/*   ErpUser - Buyer Mapping  */
				foreach($request->buyer as $buyer){
					ErpuserBuyerMapping::create([
						'erp_user'	=>	$data->id,
						'buyer'	=>	$buyer
					]);
				}
				/*   ErpUser - Company Mapping  */
				foreach($request->company as $company){
					ErpuserCompanyMapping::create([
						'erp_user'	=>	$data->id,
						'company'	=>	$company
					]);
				}
				return Response::json(array('error' => false, 'message' => 'User Details Added'), 200);
			}
			
			return Response::json(array('error'=> true, 'message' =>'Something Went wrong'), 500);
		}
	}
	/**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        \App\Erpuser::where('id', $request->id)->delete();
		return redirect('erp/users')->with('message', 'User Deleted successfully');
    }

}