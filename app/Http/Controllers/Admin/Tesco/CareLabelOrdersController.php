<?php

namespace App\Http\Controllers\Admin\Tesco;

use DB;
use View;
use App\Classes\Modules;
use App\TescoPrimarySize;
use Illuminate\Http\Request;
use App\Classes\EditTescoOrders;
use App\Classes\TescoCareMetadata;
use App\Http\Controllers\Controller;

use App\Classes\StoreTescoLabelOrder;
use App\Classes\TescoCareLabelParser;
use App\Classes\UpdateTescoLabelOrder;
use App\Http\Requests\TescoLabelOrderRequest;

class CareLabelOrdersController extends Controller
{
    private $storeorder;
    private $caremeta;
    private $module;
    private $parselabel;
    private $updateLabelOrder;
    private $symbols;
    private $coo;
    private $kids_warning;
    private $components;
    private $fabriclists;
    private $tesco_suppliers;
    private $additional_instructions;
    private $part_of_two_piece_set;

    public function __construct(Modules $module, TescoCareMetadata $caremeta, StoreTescoLabelOrder $storeorder,  TescoCareLabelParser $parselabel,UpdateTescoLabelOrder $updateLabelOrder)
    {
        $this->module = $module;

        $this->caremeta = $caremeta;

        $this->storeorder = $storeorder;

        $this->parselabel = $parselabel;
        
        $this->updateLabelOrder = $updateLabelOrder;

        // fetch tesco care metadata from class
        $this->symbols = $this->caremeta->getCareLabelSymbols();

        $this->coo = $this->caremeta->getCountryOrigin();

        $this->kids_warning = $this->caremeta->getKidsWarning();

        $this->components = $this->caremeta->getCareLabelComponents();

        $this->fabriclists = $this->caremeta->getCareLabelFabrics();

        $this->tesco_suppliers = $this->caremeta->getTescoSuppliers();

        $this->additional_instructions = $this->caremeta->getCareLabelInstructions();

        $this->part_of_two_piece_set = $this->caremeta->getPartOfTwoPieceSet();
    }


    public function index(Request $request)
    {
        try {
            $get_main_module = $this->module->getMainModules();
            $get_sub_module = $this->module->getSubModules(10);

            View::composer('layouts.Admin.navbar', function ($view) use ($get_main_module, $get_sub_module) {
                return $view->with(['modules' => $get_main_module, 'sub_modules' => $get_sub_module]);
            });

            return View::make('Admin/Order Management/tesco-care-labels')
                ->with([
                    'symbols' => $this->symbols,
                    'tesco_suppliers' => $this->tesco_suppliers,
                    'coo' => $this->coo,
                    'kids_warning' => $this->kids_warning,
                    'components' => $this->components,
                    'fabriclists' => $this->fabriclists,
                    'template' => $request['template'],
                    'additional_instructions' => $this->additional_instructions,
                    'part_of_two_piece_set' => $this->part_of_two_piece_set
                ]);
        } catch (\Exception $e) {
            return response()->json([
                'Message' => $e->getMessage(),
                'Line' => $e->getLine(),
                'File' => $e->getFile()
            ]);
        }
    }


    public function getFiberComponentsMeta()
    {
        $data['components'] = $this->caremeta->getCareLabelComponents();

        $data['fabriclists'] = $this->caremeta->getCareLabelFabrics();

        return response()->json([
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function getSecondarySizeListsBySizeCategoryID(Request $request)
    {
        $template_id = $request['template_id'];
        $tesco_sizelists = TescoPrimarySize::with(['secSizeChart'])
            ->where('tesco_primary_size.tesco_size_department_id', $request['size_id'])
            ->where('tesco_primary_size.is_flag', true)
            ->orderBy('tesco_primary_size.id')
            ->get();

        $tesco_template = DB::table('tesco_template')
            ->where('item_id', $template_id)
            ->get();

        return response()->json([
            'message' => 'success',
            'data' => $tesco_sizelists,
            'tesco_template' => $tesco_template 
        ], 200);
    }


    public function submitCareLabelOrder(TescoLabelOrderRequest $request)
    {
        if ($request->ajax()) {

            $request->validated();

            $response = $this->storeorder->storeTescoLabelOrder($request, 'admin');

            return $response;
        }
    }

    public function getCareLabelOrderDetail(Request $request)
    {
        if ($request->ajax()) {
            $get_order_details = new EditTescoOrders();
            $result = $get_order_details->getCareLabelOrderDetails($request);

            if ($result) {
                return response()->json([
                    'error' => false,
                    'message' => 'success',
                    'order_details' => $result['order_details'],
                    'moq' => $result['moq'],
                    'erp_details' => $result['erp_details'],
                    'symbols' => $this->symbols,
                    'tesco_suppliers' => $this->tesco_suppliers,
                    'coo' => $this->coo,
                    'kids_warning' => $this->kids_warning,
                    'components' => $this->components,
                    'fabriclists' => $this->fabriclists,
                    'additional_instructions' => $this->additional_instructions,
                    'part_of_two_piece_set' => $this->part_of_two_piece_set
                ], 200);
            } else {
                return response()->json(['error' => true, 'message' => 'Error'], 400);
            }
        }
    }

    public function updateCareLabelOrder(TescoLabelOrderRequest $request)
    {

        if ($request->ajax()) {

            $request->validated();
            
            $response = $this->updateLabelOrder->updateTescoLabelOrder($request, 'admin');

            return response()->json([
                'message' => 'success',
                'data' => $response
            ], 200);
        }
    }


    /**
     * Method to parse Tesco Label Orders using PO upload.
     * @param  Request $request Formdata
     * @param  $role either admin or customer
     * @return [type] view
     */
    public function createLabelOrders(Request $request) {
        try {
            if($request->zone === 'UK') {
                //handle the order using createOrderForUK method
                return $this->parselabel->createOrderForUK($request);
            } else if($request->zone === 'CE') {
                //handle the order using createOrderForCE method
                return  $this->parselabel->createOrderForCE($request);
            } else {
                return response()->json(['error' => 'Unknown Template Zone'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
}
