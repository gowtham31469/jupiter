<?php

namespace App\Http\Controllers\Customer\CustomerProfile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Modules;
use Response;
use View;
use App\Customer;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;
use Hash;
use Auth;

/**
 * Customer Profile controller for admin profile related functionality.
 * @author <Praveen>
 */
class CustomerProfileController extends Controller
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
     * Update Customer password.
     * @param Customer id, new password, current password
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function updateCustomerPassword(Request $request)
    {
      try{

            if (!(Hash::check($request->get('cp'), Auth::guard('web_customer')->user()->password))) {
                // The passwords matches
                return response()->json(['error'=> false,'message' =>'Your current password does not matches with the password you provided. Please try again.'],400);
            }
     
            if(strcmp($request->get('cp'), $request->get('np')) == 0){
                //Current password and new password are same
                return response()->json(['error'=> false,'message' =>'New Password cannot be same as your current password. Please choose a different password.'],409);
            }
     
            //Change Password
            $user = Auth::guard("web_customer")->user();
            $user->password = bcrypt($request->get('np'));
            $user->save();
     
            return response()->json(['error'=> false,'message' =>'Success'],200);
          } catch (\Exception $e)
          {
              return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
 
    }
}
