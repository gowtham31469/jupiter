<?php

namespace App\Http\Controllers\Merchandiser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Modules;
use Response;
use View;
use App\Merchandiser;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;
use Hash;
use Auth;

/**
 * Merchandiser Profile controller for admin profile related functionality.
 * @author <Praveen>
 */
class MerchandiserProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       $this->middleware('merchandiser_auth');
    }
    /**
     * Get Merchandiser details by ID for editing.
     * @param Merchandiser id
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function getMerchandiserDetailsById(Request $request)
    {
      try {
            $id = $request['id'];
            $merchandiser = DB::table('merchandisers')->select('id','merchandiser_name as name','merchandiser_address as address','email','merchandiser_phone as phone','buyer_id')->where('merchandisers.id',$id)->get();
            return response()->json(['error'=>'false','message' =>'success', 'merchandiser' => $merchandiser]);
          } catch (\Exception $e)
          {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }
    /**
       * Update merchandiser by id.
       * @param Merchandiser id
       * @author <Praveen>
       * @return \Illuminate\Http\Response
       */
      public function updateMerchandiserById(Request $request)
      {
        try {
              $id                 = $request['id'];
              $input['id']        = Input::get('id');
              $input['email']     = Input::get('email');
              $input['name']      = Input::get('name');
              $input['address']   = Input::get('address');
              $input['phone']     = Input::get('phone');
              $input['buyer']     = Input::get('buyer');
              $email_rules = array('email' => 'unique:merchandisers,email,'.$id);
              $emailValidator = Validator::make($input ,$email_rules);

              if($emailValidator->fails())
              {
                  return response()->json(['error'=> true,'message' =>'Email Already Exists'],409);
              }
              else
              {
                  Merchandiser::where('id',$id)
                   ->update([
                     'merchandiser_name'      => $input['name'],
                     'email'                  => $input['email'],
                     'merchandiser_address'   => preg_replace('/\s*,\s*/', ', ', $input['address']),
                     'merchandiser_phone'     => $input['phone'],
                     'buyer_id'               => $input['buyer'],
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
     * Update Merchandiser password.
     * @param Merchandiser id, new password, current password
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function updateMerchandiserPassword(Request $request)
    {
      try{

            if (!(Hash::check($request->get('cp'), Auth::guard("web_merchandiser")->user()->password))) {
                // The passwords matches
                return response()->json(['error'=> false,'message' =>'Your current password does not matches with the password you provided. Please try again.'],400);
            }
     
            if(strcmp($request->get('cp'), $request->get('np')) == 0){
                //Current password and new password are same
                return response()->json(['error'=> false,'message' =>'New Password cannot be same as your current password. Please choose a different password.'],409);
            }
     
            //Change Password
            $user = Auth::guard("web_merchandiser")->user();
            $user->password = bcrypt($request->get('np'));
            $user->save();
     
            return response()->json(['error'=> false,'message' =>'Success'],200);
          } catch (\Exception $e)
          {
              return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
 
    }
}
