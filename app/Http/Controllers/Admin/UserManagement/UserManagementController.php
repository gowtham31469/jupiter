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
              $get_sub_module = $module_var->getSubModules(2);
              View::composer('layouts.Admin.navbar', function($view) use ($get_main_module) {
                  return $view->with(['modules' => $get_main_module]);
              });
              $roles = DB::table('user_role')->pluck('user_role_name','id');
              $user_id = DB::table('users')->max('id');
              $user_id = $user_id + 1;
              $user_id = "JGAU00".$user_id;
              return view('Admin/User Management/home',['sub_modules' => $get_sub_module,'roles' => $roles,'user_id' => $user_id]);
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
          if(Request::ajax())
          {
            $user_id   = $request['user_id'];
            $name      = $request['name'];
            $email     = $request['email'];
            $password  = $request['password'];
            $phone     = $request['phone'];
            $user_role = $request['user_role'];
            $address   = $request['address'];
            return response()->json(['error'=>'false','message' =>'success']);
          }

        } catch (\Exception $e)
        {
               return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
  }
}
