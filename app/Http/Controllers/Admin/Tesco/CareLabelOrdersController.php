<?php

namespace App\Http\Controllers\Admin\Tesco;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use DB;
use App\Classes\Modules;
use App\Classes\TescoCareMetadata;
use App\Classes\StoreTescoLabelOrder;

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
use App\Http\Requests\SubmitTescoLabelOrder;

class CareLabelOrdersController extends Controller
{
    private $storeOrder;
    private $caremeta;
    private $module;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Modules $module, TescoCareMetadata $caremeta, StoreTescoLabelOrder $storeorder)
    {
       $this->module = $module;
       $this->caremeta = $caremeta;
       $this->storeorder = $storeorder;
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
            $additional_instructions = $this->caremeta->getCareMetadata()['additional_instructions'];
            
            return View::make('Admin/Order Management/tesco-care-labels')->with(['symbols' => $symbols, 'tesco_suppliers' => $tesco_suppliers, 'coo' => $coo, 'kids_warning' => $kids_warning, 'components' => $components, 'fabriclists' => $fabriclists, 'additional_instructions' => $additional_instructions]);
            
        } catch (\Exception $e)
        {
                return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }

    /**
     * Method to get fiber and germent components metadata.
     *
     * @param Request $request
     * @return collection data
     */
    public function getFiberComponentsMeta(Request $request)
    {
        $data['components'] = DB::table('tesco_components')->where('is_enabled','=',1)->pluck('english','id')->all();
		$data['fabriclists'] = DB::table('tesco_fabriclists')->where('is_enabled','=',1)->pluck('english','id')->all();
		
		return response()->json(['message' => 'success', 'data' => $data], 200);
    }

    /**
     * Method to get secondary size categories based on primary size id
     *
     * @param Request $request
     * @return collection size_categories
     */
    public function getSecondarySizeCategoriesByPrimaryID(Request $request)
    {
        $size_categories = DB::table('tesco_primary_size')->select('tssc.id','tssc.category_name')
		->join('tesco_secondary_size_chart', 'tesco_secondary_size_chart.tesco_primary_size_id', '=', 'tesco_primary_size.id')
		->join('tesco_secondary_size_category as tssc', 'tssc.id', '=', 'tesco_secondary_size_chart.tesco_secondary_size_category_id')
		->where('tesco_primary_size.tesco_size_department_id', $request['size'])
        ->groupBy('tssc.id','tssc.category_name')
        ->orderBy('tssc.id')
        ->get();
        
        return response()->json(['message' => 'success', 'size_categories' => $size_categories], 200);
    }

    /**
     * Method to get secondary size list with categories
     *
     * @param Request $request
     * @return collection size_lists
     */
    public function getSecondarySizeListsBySizeCategoryID(Request $request)
    {
        $tesco_sizelists = TescoPrimarySize::with(['secSizeChart' => function ($query) use ($request) {
            $query->whereIn('tesco_secondary_size_chart.tesco_secondary_size_category_id',$request['size_category']);
		}])
        ->where('tesco_primary_size.tesco_size_department_id', $request['size_id'])
        ->orderBy('tesco_primary_size.id')
        ->get();

		return response()->json(['message' => 'success', 'data' => $tesco_sizelists], 200);
    }

    public function submitCareLabelOrder(SubmitTescoLabelOrder $request)
    {

        if($request->ajax()){

            $request->validated();
            
            $this->storeorder->storeTescoLabelOrder($request);
            // $components = $request->tesco_care_garment_component;
            
            // for($comp_count = 0; $comp_count < count($components); $comp_count++)
            // {
            //     $data = array(
            //         'components' => $components[$comp_count]
            //     );

            //     $insert_data[] = $data; 
            // }

            return response()->json([
            'success'  => 'Data Added successfully.'
            ]);
        }

    }

}
