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
   * Show the users list page.
   * @author <Praveen>
   * @return \Illuminate\Http\Response
   */
  public function users()
  {
    $module_var = new Modules();
    $is_allowed = $module_var->permittedModules();
    if($is_allowed)
    {
        $top_mod = "User Management";
        $get_main_module = $module_var->getMainModules();
        View::composer('layouts.Admin.navbar', function($view) use ($get_main_module) {
            return $view->with(['modules' => $get_main_module]);
        });
        $get_sub_module = $module_var->getSubModules(2);
        $users = DB::table('users')->select('users.id','user_id','name','email','phone','user_role_name','address','user_role_id')
                    ->join('user_role','user_role.id','users.user_role_id')
                    ->get();
        View::composer('layouts.Admin.sidebar', function($view) use ($get_sub_module,$top_mod) {
            return $view->with(['modules' => $get_sub_module,'top_mod' => $top_mod]);
        });
        return view('Admin/User Management/users')->with(['users' => $users,'sub_modules' => $get_sub_module]);
    }
    else
    {
         return redirect('/admin/dashboard');
    }
  }
  /**
   * Show the roles list page.
   * @author <Praveen>
   * @return \Illuminate\Http\Response
   */
  public function roles()
  {
    $module_var = new Modules();
    $is_allowed = $module_var->permittedModules();
    if($is_allowed)
    {
        $top_mod = "User Management";
        $get_main_module = $module_var->getMainModules();
        View::composer('layouts.Admin.navbar', function($view) use ($get_main_module) {
            return $view->with(['modules' => $get_main_module]);
        });
        $get_sub_module = $module_var->getSubModules(2);
        $roles = DB::table('user_role')->select('user_role.*')
                    ->get();
        View::composer('layouts.Admin.sidebar', function($view) use ($get_sub_module,$top_mod) {
            return $view->with(['modules' => $get_sub_module,'top_mod' => $top_mod]);
        });
        return view('Admin/User Management/roles')->with(['roles' => $roles,'sub_modules' => $get_sub_module]);
    }
    else
    {
         return redirect('/admin/dashboard');
    }
  }
}
