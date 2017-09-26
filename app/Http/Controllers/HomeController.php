<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\Modules;
use View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Modules $modules_res)
    {
        $this->module = $modules_res;
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
            $get_main_module = $this->module->getMainModules();
            View::composer('layouts.Admin.sidebar', function($view) use ($get_main_module) {
                return $view->with(['modules' => $get_main_module]);
            });
             
            return view('home');
        
    }
}
