<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;   
use App\Classes\Modules;
use Response;
use View;

class HomeController extends Controller
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
            //get permitted menus
        
            $top_mod = "Dashboard";
            $get_main_module = $module_var->getMainModules();
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module) {
                return $view->with(['modules' => $get_main_module]);
            });
           /* $get_sub_module = $module_var->getSubModules(1);
            View::composer('layouts.Admin.sidebar', function($view) use ($get_sub_module,$top_mod) {
                return $view->with(['modules' => $get_sub_module,'top_mod' => $top_mod]);
            });*/
            $get_sub_module=array();
            return view('home',['sub_modules' => $get_sub_module]);
        
    }
}
