<?php

namespace App\Http\Controllers\Customer\Tesco;

use App\Classes\Customer\CustomersList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use DB;
use Auth;
use App\Classes\TescoCareMetadata;
use App\Classes\StoreTescoLabelOrder;
use App\Classes\UpdateTescoLabelOrder;
use App\Classes\TescoCareLabelParser;

use App\TescoPrimarySize;
use App\Http\Requests\TescoLabelOrderRequest;
use App\Classes\EditTescoOrders;

class CareLabelOrdersCustomerController extends Controller
{
    private $storeorder;
    private $caremeta;
    private $updateLabelOrder;
    private $parselabel;
    private $customers;

    public function __construct(TescoCareMetadata $caremeta, StoreTescoLabelOrder $storeorder, UpdateTescoLabelOrder $updateLabelOrder, TescoCareLabelParser $parselabel, CustomersList $customers)
    {

        $this->caremeta = $caremeta;

        $this->storeorder = $storeorder;

        $this->updateLabelOrder = $updateLabelOrder;

        $this->parselabel = $parselabel;

        $this->customers = $customers;
    }

    public function index(Request $request)
    {
        try {
            $tesco_suppliers = $this->customers->getCustomers(Auth::guard('web_customer')->user()->company_id);
            return View::make('customer/tesco-customer-care-labels')
                ->with([
                    'symbols' => $this->caremeta->getCareLabelSymbols(),
                    'tesco_suppliers' => $tesco_suppliers,
                    'coo' => $this->caremeta->getCountryOrigin(),
                    'kids_warning' => $this->caremeta->getKidsWarning(),
                    'components' => $this->caremeta->getCareLabelComponents(),
                    'fabriclists' => $this->caremeta->getCareLabelFabrics(),
                    'template' => $request['template'],
                    'additional_instructions' => $this->caremeta->getCareLabelInstructions(),
                    'part_of_two_piece_set' => $this->caremeta->getPartOfTwoPieceSet()
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
        
        $moq = DB::table('price_taxation')
        ->where([
            'template_id' => $template_id,
            'customer_id' => Auth::guard('web_customer')->user()->company_id,
            'buyer_id' => 2
        ])
        ->value('price_taxation.moq');

        $moq_value = !is_null($moq) ? $moq : 0;

        return response()->json([
            'message' => 'success',
            'data' => $tesco_sizelists,
            'tesco_template' => $tesco_template,
            'moq' => $moq_value
        ], 200);
    }

    public function getTescoTemplateMoq(Request $request)
    {
        $templates = explode(',', $request['templates']);
        $moq_array = array();
        $moq_value = array();

        foreach ($templates as $template_id) {

            $temp_name = DB::table('tesco_item_master')
                ->where('id', $template_id)
                ->select('tesco_item_master.item_name')
                ->first();

            $moq = DB::table('price_taxation')
                ->where([
                    'template_id' => $template_id,
                    'customer_id' => Auth::guard('web_customer')->user()->company_id,
                    'buyer_id' => $request['buyer_id']
                ])
                ->value('price_taxation.moq');

            $moq_value[$temp_name->item_name] = !is_null($moq) ? $moq : 0;
        }

        array_push($moq_array, $moq_value);

        return response()->json([
            'error' => false,
            'message' => 'success',
            'moq' => $moq_array
        ], 200);
    }

    public function submitCareLabelOrder(TescoLabelOrderRequest $request)
    {

        if ($request->ajax()) {

            $request->validated();

            $response = $this->storeorder->storeTescoLabelOrder($request, 'customer');

            return $response;
        }
    }

    public function getCareLabelOrderDetail(Request $request)
    {
        if ($request->ajax()) {
            $tesco_suppliers = $this->customers->getCustomers(Auth::guard('web_customer')->user()->company_id);

            $get_order_details = new EditTescoOrders();
            $result = $get_order_details->getCareLabelOrderDetails($request);

            if ($result) {
                return response()->json([
                    'error' => false,
                    'message' => 'success',
                    'order_details' => $result['order_details'],
                    'moq' => $result['moq'],
                    'erp_details' => $result['erp_details'],
                    'symbols' => $this->caremeta->getCareLabelSymbols(),
                    'tesco_suppliers' => $tesco_suppliers,
                    'coo' => $this->caremeta->getCountryOrigin(),
                    'kids_warning' => $this->caremeta->getKidsWarning(),
                    'components' => $this->caremeta->getCareLabelComponents(),
                    'fabriclists' => $this->caremeta->getCareLabelFabrics(),
                    'additional_instructions' => $this->caremeta->getCareLabelInstructions(),
                    'part_of_two_piece_set' => $this->caremeta->getPartOfTwoPieceSet()
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
