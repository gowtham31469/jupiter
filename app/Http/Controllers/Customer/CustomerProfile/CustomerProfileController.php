<?php

namespace App\Http\Controllers\Customer\CustomerProfile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Modules;
use Response;
use View;
use App\Customer;
use App\CustomerProfile;
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
    /**
     * Get customer details by ID for editing.
     * @param customer id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function getCustomerDetailsById(Request $request)
    {
      try {
            $id = $request['id'];
            $customer = DB::table('customer_profile')->select('id','name','email','title')->where('customer_profile.id',$id)->get();
            return response()->json(['error'=>'false', 'message' =>'success', 'customer' => $customer]);
          } catch (\Exception $e)
          {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }
    /**
     * Update customer by id.
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function updateCustomerDetailsById(Request $request)
    {
      try {
            $id               = $request['id'];
            $buyer            = Input::get('buyer');
            $input['name']    = Input::get('name');
            $input['email']   = Input::get('email');
            $input['title']   = Input::get('title');
                
            $email_rules = array('email' => 'unique:customer_profile,email,'.$id);
            $emailValidator  = Validator::make($input,$email_rules);

            if($emailValidator->fails())
            {
                return response()->json(['error'=> true,'message' =>'Email Already Exists'],409);
            }
            else
            {
                CustomerProfile::where('id',$id)
                 ->update([
                   'name'      => $input['name'],
                   'email'     => $input['email'],
                   'title'     => $input['title']
                ]);

            $result = response()->json(['error'=> false,'message' =>'Success'],200);
          }
          } catch (\Exception $e)
          {
                 $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
          return $result;
      }
}
