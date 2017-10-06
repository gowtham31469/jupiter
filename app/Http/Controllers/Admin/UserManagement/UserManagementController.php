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
    $module_var = new Modules();
    $is_allowed = $module_var->permittedModules();
    if($is_allowed)
    {
        $get_main_module = $module_var->getMainModules();
        View::composer('layouts.Admin.navbar', function($view) use ($get_main_module) {
            return $view->with(['modules' => $get_main_module]);
        });
        $get_sub_module = $module_var->getSubModules(2);
        View::composer('layouts.Admin.sidebar', function($view) use ($get_sub_module) {
            return $view->with(['modules' => $get_sub_module]);
        });
        return view('Admin/User Management/home',['sub_modules' => $get_sub_module]);
    }
    else
    {
         return redirect('/admin/dashboard');
    }
  }
  /**
   * Show the users list page.
   * @author <Praveen>
   * @return \Illuminate\Http\Response
   */
  public function users()
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
              $users = DB::table('users')->select('users.id','user_id','name','email','phone','user_role_name','address','user_role_id')
                          ->join('user_role','user_role.id','users.user_role_id')
                          ->get();
              $roles = DB::table('user_role')->pluck('user_role_name','id');
              View::composer('layouts.Admin.sidebar', function($view) use ($get_sub_module) {
                  return $view->with(['modules' => $get_sub_module]);
              });
              return view('Admin/User Management/users')->with(['users' => $users,'sub_modules' => $get_sub_module,'roles' => $roles]);
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
   * Show the roles list page.
   * @author <Praveen>
   * @return \Illuminate\Http\Response
   */
  public function roles()
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
                $roles = DB::table('user_role')->select('user_role.*')
                            ->get();
                View::composer('layouts.Admin.sidebar', function($view) use ($get_sub_module) {
                    return $view->with(['modules' => $get_sub_module]);
                });
                return view('Admin/User Management/roles')->with(['roles' => $roles,'sub_modules' => $get_sub_module]);
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
}
