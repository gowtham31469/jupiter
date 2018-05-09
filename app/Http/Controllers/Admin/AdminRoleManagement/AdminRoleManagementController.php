<?php

namespace App\Http\Controllers\Admin\AdminRoleManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Modules;
use Response;
use View;
use App\User;
use App\UserRole;
use App\RolePermissionMdl;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;
use Hash;

/**
 * Admin Role management controller(Add/edit new admin role)
 * @author <Praveen>
 */
class AdminRoleManagementController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     * @author <Praveen>
     */
    public function index()
    {
        $module_var = new Modules();
        $is_allowed = $module_var->permittedModules();
        if($is_allowed)
        {
            $get_main_module = $module_var->getMainModules();
            $get_sub_module = $module_var->getSubModules(41);
            $users = DB::table('users')->select('id','name','address','email','phone','is_active')->where('user_role_id','=',3)->orderBy('is_active','desc')->get();
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
            });
            return view('Admin/Admin Role Management/home')->with(['admin' => $users]);
        }
        else
        {
             return redirect('/admin/dashboard');
        }
    }
    /**
     * Admin list page show using ajax.
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function adminList()
    {
      try {
            $users = DB::table('users')->select('id','name','address','email','phone','is_active')->where('user_role_id','=',3)->orderBy('is_active','desc')->get();
            return response()->json(['error'=>'false','message' =>'success', 'admin' => $users]);
          } catch (\Exception $e)
          {
                 return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
    }
    /**
     * Add Admin to jga. 
     * @param \Illuminate\Http\Request
     * @author <Praveen>
     * @return \Illuminate\Http\Response
     */
    public function addNewAdmin(Request $request)
      {
        try {
                $name                  = $request['name'];
                $email                 = $request['email'];
                $phone                 = $request['phone'];
                $address               = $request['address'];
                $password              = Hash::make($request['p']);
                $email_exists = User::where('email',$email)->select('id')->get();
                if(count($email_exists) > 0)
                {
                  $result = response()->json(['error'=> true,'message' =>'Email Already Exists'],409);
                }
                else
                {
                  /** [ Get users table maximum id value for creating user id] */
                  $max_id = User::max('id');
                  $user_id = "JGAU00".($max_id+1);
                  $inserted = User::create([
                                'name'    	             => $name,
                                'email'                  => $email,
                                'password'               => $password,
                                'address'  				 => $address,
                                'phone'    				 => $phone,
                                'is_active'              => 0,
                                'user_role_id'           => 3,
                                'user_id'                => $user_id
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
       * Make admin active
       * @param admin id
       * @author <Praveen>
       * @return boolean true or false
       */
      public function makeAdminActive(Request $request)
      {
        try {
              $id      = $request['id'];
              
              User::where('id',$id)
               ->update([
                 'is_active'  => 1
              ]);

              $result = response()->json(['error'=> false,'message' =>'Success'],200);
            } 
            catch (\Exception $e)
            {
                   $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
            return $result;
      }
      /**
       * Make admin in-active
       * @author <Praveen>
       * @param admin id
       * @return boolean true or false
       */
      public function makeAdminInactive(Request $request)
      {
        try {
              $id      = $request['id'];
              
              User::where('id',$id)
               ->update([
                 'is_active'  => 0
              ]);

              $result = response()->json(['error'=> false,'message' =>'Success'],200);
            } 
            catch (\Exception $e)
            {
                   $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
            }
            return $result;
      }
}
