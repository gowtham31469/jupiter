<?php

namespace App\Http\Controllers\Admin\Tesco;

use App\TescoWashcareOrders;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use DB;
use App\Classes\Modules;

class CareLabelOrdersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Modules $module)
    {
       $this->middleware('auth');
       $this->module = $module;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $is_allowed = $this->module->permittedModules();
            if($is_allowed)
            {
                $get_main_module = $this->module->getMainModules();
                $get_sub_module = $this->module->getSubModules(10);
                View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                    return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
                });
                return view('Admin/Order Management/tesco-care-labels');
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TescoWashcareOrders  $tescoWashcareOrders
     * @return \Illuminate\Http\Response
     */
    public function show(TescoWashcareOrders $tescoWashcareOrders)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TescoWashcareOrders  $tescoWashcareOrders
     * @return \Illuminate\Http\Response
     */
    public function edit(TescoWashcareOrders $tescoWashcareOrders)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TescoWashcareOrders  $tescoWashcareOrders
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TescoWashcareOrders $tescoWashcareOrders)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TescoWashcareOrders  $tescoWashcareOrders
     * @return \Illuminate\Http\Response
     */
    public function destroy(TescoWashcareOrders $tescoWashcareOrders)
    {
        //
    }
}
