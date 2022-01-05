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

    public function index()
    {
        try {
            // fetch tesco care metadata from class
            $symbols = $this->caremeta->getCareLabelSymbols();

            $coo = $this->caremeta->getCountryOrigin();

            $kids_warning = $this->caremeta->getKidsWarning();

            $components = $this->caremeta->getCareLabelComponents();

            $fabriclists = $this->caremeta->getCareLabelFabrics();

            $additional_instructions = $this->caremeta->getCareLabelInstructions();

            $part_of_two_piece_set = $this->caremeta->getPartOfTwoPieceSet();

            $tesco_suppliers = $this->customers->getCustomers(Auth::guard('web_customer')->id());

            return View::make('customer/tesco-customer-care-labels')
                ->with([
                    'symbols' => $symbols,
                    'tesco_suppliers' => $tesco_suppliers,
                    'coo' => $coo,
                    'kids_warning' => $kids_warning,
                    'components' => $components,
                    'fabriclists' => $fabriclists,
                    'additional_instructions' => $additional_instructions,
                    'part_of_two_piece_set' => $part_of_two_piece_set
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

    public function getSecondarySizeCategoriesByPrimaryID(Request $request)
    {
        $size_categories = DB::table('tesco_primary_size')
            ->select('tssc.id', 'tssc.category_name')
            ->join('tesco_secondary_size_chart', 'tesco_secondary_size_chart.tesco_primary_size_id', 'tesco_primary_size.id')
            ->join('tesco_secondary_size_category as tssc', 'tssc.id', 'tesco_secondary_size_chart.tesco_secondary_size_category_id')
            ->where('tesco_primary_size.tesco_size_department_id', $request['size'])
            ->groupBy('tssc.id', 'tssc.category_name')
            ->orderBy('tssc.id')
            ->get();

        return response()->json([
            'message' => 'success',
            'size_categories' => $size_categories
        ], 200);
    }

    public function getSecondarySizeListsBySizeCategoryID(Request $request)
    {
        $template_id = $request['template_id'];
        $tesco_sizelists = TescoPrimarySize::with(['secSizeChart' => function ($query) use ($request) {
            $query->whereIn('tesco_secondary_size_chart.tesco_secondary_size_category_id', $request['size_category']);
        }])
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

    public function getTescoTemplateMoq(Request $request)
    {
        $templates = explode(',', $request['templates']);

        $buyer_id  = $request['buyer_id'];

        /**  [Get parent customer id from tesco_suppliers metadata id] */
        $customer_id = DB::table("customer_metadata")
            ->where('customer_metadata_id', $request['supplier'])
            ->select('customer_id')
            ->first();

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
                    'customer_id' => $customer_id->customer_id,
                    'buyer_id' => $buyer_id
                ])
                ->select('price_taxation.moq')
                ->first();

            $moq_value[$temp_name->item_name] = !is_null($moq) ? $moq->moq : 0;
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
