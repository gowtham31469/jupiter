<?php

namespace App\Http\Controllers\Admin\Tesco;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use DB;
use App\Classes\Modules;
use App\Classes\TescoCareMetadata;

use App\TescoCareSymbols;
use App\TescoComponents;
use App\TescoCountries;
use App\TescoFabricLists;
use App\TescoKidsWarningStatements;
use App\TescoPrimarySize;
use App\TescoSecondarySizeCategory;
use App\TescoSecondarySizeChart;
use App\TescoWashcareOrders;
use App\TescoWashcareOrdersQuantity;
use App\TescoWashcareOrdersAdditionalCareInstructions;
use App\TescoWashcareOrdersComponentFibreMapping;
use App\TescoWashcareOrdersComponentMapping;
use App\OrderManagementHistory;
use App\FinancialYearResetDate;

class CareLabelOrdersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Modules $module, TescoCareMetadata $caremeta)
    {
       $this->middleware('auth');
       $this->module = $module;
       $this->caremeta = $caremeta;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $get_main_module = $this->module->getMainModules();
            $get_sub_module = $this->module->getSubModules(10);
            
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
            });
            
            // fetch tesco care metadata from class
            $symbols = $this->caremeta->getCareMetadata()['symbols'];
            $coo = $this->caremeta->getCareMetadata()['coo'];
            $kids_warning = $this->caremeta->getCareMetadata()['kids_warning'];
            $components = $this->caremeta->getCareMetadata()['components'];
            $fabriclists = $this->caremeta->getCareMetadata()['fabriclists'];
            $tesco_suppliers = $this->caremeta->getCareMetadata()['tesco_suppliers'];
            
            return View::make('Admin/Order Management/tesco-care-labels')->with(['symbols' => $symbols, 'tesco_suppliers' => $tesco_suppliers, 'coo' => $coo, 'kids_warning' => $kids_warning, 'components' => $components, 'fabriclists' => $fabriclists]);
            
        } catch (\Exception $e)
        {
                return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }

    /**
     * Get the tesco care 
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

}
