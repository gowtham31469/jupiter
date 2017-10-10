<?php

namespace App\Http\Controllers\Admin\UserManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Modules;
use Response;
use View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;
use App\User;
use Hash;

class UserManagementController extends Controller
{
  /**
   * Create a new controller instance.
   * @author <Praveen>
   * @return void
   */
  public function __construct()
  {
     $this->middleware('auth');
  }
  /**
   * Show the user-management home page.
   * @author <Praveen>
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    try {
          $module_var = new Modules();
          $is_allowed = $module_var->permittedModules();
          if($is_allowed)
          {
              $get_main_module = $module_var->getMainModules();
              View::composer('layouts.Admin.navbar', function($view) use ($get_main_module) {
                  return $view->with(['modules' => $get_main_module]);
              });
              $roles = DB::table('user_role')->pluck('user_role_name','id');
              $user_id = DB::table('users')->max('id');
              $user_id = $user_id + 1;
              $user_id = "JGAU00".$user_id;
              return view('Admin/User Management/home',['roles' => $roles,'user_id' => $user_id]);
          }
          else
          {
               return redirect('/admin/dashboard');
          }
        } catch (\Exception $e)
        {
               return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }
 /**
  * Show the users list view on ajax.
  * @author <Praveen>
  * @return \Illuminate\Http\Response
  */
  public function users()
  {
    try{
          $users = DB::table('users')->select('users.id','user_id','name','email','phone','user_role_name','address','user_role_id')->join('user_role','user_role.id','users.user_role_id')->get();
          return response()->json(['error'=>'false','message' =>'success', 'users' => $users]);
        } catch (\Exception $e)
        {
               return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }
  /**
   * Show the roles list view on ajax.
   * @author <Praveen>
   * @return \Illuminate\Http\Response
   */
  public function roles()
  {
    try {
          $roles = DB::table('user_role')->select('id','role_id','user_role_name','user_role_description')->get();
          return response()->json(['error'=>'false','message' =>'success', 'roles' => $roles]);
        } catch (\Exception $e)
        {
               return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }
  /**
   * function to add jga users.
   * @author <Praveen>
   * @return \Illuminate\Http\Response
   */
  public function addJgaUser(Request $request)
  {
    try {
            $user_id   = $request['user_id'];
            $name      = $request['name'];
            $email     = $request['email'];
            $phone     = $request['phone'];
            $user_role = $request['user_role'];
            $address   = $request['address'];
            $password  = Hash::make($request['p']); //Password
            $email_exists = User::where('email',$email)->select('id')->get();
            if(count($email_exists) > 0)
            {
              $result = response()->json(['error'=> true,'message' =>'Email Already Exists'],409);
            }
            else
            {
              $inserted = User::create([
                          'user_id'    => $user_id,
                          'name'       => $name,
                          'email'      => $email,
                          'phone'      => $phone,
                          'user_role_id'  => $user_role,
                          'address'    => $address,
                          'password'   => $password
                        ]);
              if($inserted != '')
              {
                $result = response()->json(['error'=> false,'message' =>'success'],200);
              }
              else
              {
                $result = response()->json(['error'=> true,'message' =>'Error'],400);
              }
            }
        } catch (\Exception $e)
        {
               $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
  /**
   * Get user details by ID for editing.
   * @author <Praveen>
   * @return \Illuminate\Http\Response
   */
  public function getUserById(Request $request)
  {
    try {
          $id = $request['id'];
          $user = DB::table('users')->select('user_id','user_role_id','name','email','phone','address')->where('users.id',$id)->get();
          return response()->json(['error'=>'false','message' =>'success', 'user' => $user]);
        } catch (\Exception $e)
        {
               return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }
  /**
   * function to update jga users.
   * @author <Praveen>
   * @return \Illuminate\Http\Response
   */
  public function updateUserById(Request $request)
  {
    try {
            $id        = $request['id'];
            // $name      = $request['name'];
            // $email     = $request['email'];
            // $phone     = $request['phone'];
            // $user_role = $request['user_role'];
            // $address   = $request['address'];
            $input['id']        = Input::get('id');
            $input['email']     = Input::get('email');
            $input['name']      = Input::get('name');
            $input['user_role'] = Input::get('user_role');
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
              $updated = User::where('users.id',$id)
                          ->update([
                            'name'       => $input['name'],
                            'email'      => $input['email'],
                            'phone'      => $input['phone'],
                            'user_role_id'  => $input['user_role'],
                            'address'    => $input['address']
                          ]);
              if($updated != '')
              {
                $result = response()->json(['error'=> false,'message' =>'success'],200);
              }
              else
              {
                $result = response()->json(['error'=> true,'message' =>'Error'],400);
              }
            }
        } catch (\Exception $e)
        {
               $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }
  /**
   * function to update jga users.
   * @author <Praveen>
   * @return \Illuminate\Http\Response
   */
  public function deleteUserById(Request $request)
  {
    try {
              $id        = $request['id'];
              $deleted = user::where('users.id', '=', $id)
                          ->delete();
              if($deleted != '')
              {
                $result = response()->json(['error'=> false,'message' =>'success'],200);
              }
              else
              {
                $result = response()->json(['error'=> true,'message' =>'Error'],400);
              }
        } catch (\Exception $e)
        {
               $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
  }

}
