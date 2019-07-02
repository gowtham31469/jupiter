<?php

namespace App\Http\Controllers\Admin\AdminProfile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Modules;
use Response;
use View;
use App\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;
use Hash;
use Auth;

/**
 * Admin Profile controller for admin profile related functionality.
 * @author <Praveen>
 */
class AdminProfileController extends Controller
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
    /**
     * Get admin details by ID for editing.
     * @param admin id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function getAdminDetailsById(Request $request)
    {
      try {
            $id = $request['id'];
            $admin = DB::table('users')->select('id','name','address','email','phone')->where('users.id',$id)->get();
            return response()->json(['error'=>'false','message' =>'success', 'admin' => $admin]);
          } catch (\Exception $e)
          {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }
    /**
     * Update admin by id.
     * @param admin id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function updateAdminById(Request $request)
    {
      try {
            $id      = $request['id'];
            $input['id']        = Input::get('id');
            $input['email']     = Input::get('email');
            $input['name']      = Input::get('name');
            $input['address']   = Input::get('address');
            $input['phone']     = Input::get('phone');
            $email_rules = array('email' => 'unique:users,email,'.$id);
            $emailValidator = Validator::make($input ,$email_rules);

            if($emailValidator->fails())
            {
                return response()->json(['error'=> true,'message' =>'Email Already Exists'],409);
            }
            else
            {
                User::where('id',$id)
                 ->update([
                   'name'      => $input['name'],
                   'email'     => $input['email'],
                   'address'   => preg_replace('/\s*,\s*/', ', ', $input['address']),
                   'phone'     => $input['phone'],
                ]);

            $result = response()->json(['error'=> false,'message' =>'Success'],200);
          }
          } catch (\Exception $e)
          {
                 $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
          return $result;
      }

    /**
     * Update admin password.
     * @param admin id, new password, current password
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function updateAdminPassword(Request $request)
    {
      try{

            if (!(Hash::check($request->get('cp'), Auth::user()->password))) {
                // The passwords matches
                return response()->json(['error'=> false,'message' =>'Your current password does not matches with the password you provided. Please try again.'],400);
            }
     
            if(strcmp($request->get('cp'), $request->get('np')) == 0){
                //Current password and new password are same
                return response()->json(['error'=> false,'message' =>'New Password cannot be same as your current password. Please choose a different password.'],409);
            }
     
            //Change Password
            $user = Auth::user();
            $user->password = bcrypt($request->get('np'));
            $user->save();
     
            return response()->json(['error'=> false,'message' =>'Success'],200);
          } catch (\Exception $e)
          {
              return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
 
    }
}
