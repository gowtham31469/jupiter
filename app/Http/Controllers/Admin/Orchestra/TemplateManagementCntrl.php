<?php

namespace App\Http\Controllers\Admin\Orchestra;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;   
use App\Classes\Modules;
use Response;
use View;
use DB;

class TemplateManagementCntrl extends Controller
{
    private $modules_permission;
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
     */
    public function index()
    {
        $module_var = new Modules();
        $is_allowed = $module_var->permittedModules();
        if($is_allowed)
        {
            $top_mod = "Template Management";
            $get_main_module = $module_var->getMainModules();
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module) {
                return $view->with(['modules' => $get_main_module]);
            });
            $get_sub_module = $module_var->getSubModules(21);
            return view('Admin/Orchestra/template-management');
        }
        else
        {
             return redirect('/admin/dashboard');
        }
        
    }    
    public function templateList()
    {
        $first_level= DB::table("orchestra_item_master")->select('id','item_name')->where("parent_id", 0)->get();
          if($first_level)
          {
            foreach ($first_level as $level1)
            {
              $level1_parent = $level1->id;
              $second_level = DB::table("orchestra_item_master")->where("parent_id",$level1_parent)->select('id','item_name')->get();
              $level1->second = $second_level;
              foreach ($level1->second as $level2)
              {
                  $level2_parent = $level2->id;
                  $third_level = DB::table("orchestra_template")->where("item_id",$level2_parent)->select('id','template_name')->get();
                  $level2->third = $third_level;
              }
            }

          }
          echo json_encode($first_level);
    }   
}

