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
use App\TescoWashcareOrders;

class CareLabelOrdersCustomerController extends Controller
{
    private $storeorder;
    private $caremeta;
    private $updateLabelOrder;
    private $parselabel;
    private $customers;
    private $symbols;
    private $coo;
    private $kids_warning;
    private $components;
    private $fabriclists;
    private $tesco_suppliers;
    private $additional_instructions;
    private $part_of_two_piece_set;

    public function __construct(TescoCareMetadata $caremeta, StoreTescoLabelOrder $storeorder, UpdateTescoLabelOrder $updateLabelOrder, TescoCareLabelParser $parselabel, CustomersList $customers)
    {

        $this->caremeta = $caremeta;

        $this->storeorder = $storeorder;

        $this->updateLabelOrder = $updateLabelOrder;

        $this->parselabel = $parselabel;

        $this->customers = $customers;

        // fetch tesco care metadata from class
        $this->symbols = $this->caremeta->getCareLabelSymbols();

        $this->coo = $this->caremeta->getCountryOrigin();

        $this->kids_warning = $this->caremeta->getKidsWarning();

        $this->components = $this->caremeta->getCareLabelComponents();

        $this->fabriclists = $this->caremeta->getCareLabelFabrics();

        $this->additional_instructions = $this->caremeta->getCareLabelInstructions();

        $this->part_of_two_piece_set = $this->caremeta->getPartOfTwoPieceSet();
    }

    public function index()
    {
        try {
            $tesco_suppliers = $this->customers->getCustomers(Auth::guard('web_customer')->id());
            return View::make('customer/tesco-customer-care-labels')
                ->with([
                    'symbols' => $this->symbols,
                    'tesco_suppliers' => $tesco_suppliers,
                    'coo' => $this->coo,
                    'kids_warning' => $this->kids_warning,
                    'components' => $this->components,
                    'fabriclists' => $this->fabriclists,
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

    public function getCareLabelOrderDetail(Request $request)
    {
        if ($request->ajax()) {
            $erp_order_details = array();
            $tesco_suppliers = $this->customers->getCustomers(Auth::guard('web_customer')->id());

            $order_details  = TescoWashcareOrders::with([
                'quantities',
                'garment_components',
                'item_master'
            ])
            ->where([
                'id' => $request->id
            ])
            ->first();

            $order_quantity_ids = $order_details->quantities->pluck('id');

            $moq     =    DB::table('price_taxation')
            ->where([
                'template_id' => $order_details->template_id,
                'customer_id' => $order_details->tesco_supplier_id,
                'buyer_id' => 2
            ])
            ->value('moq');

            // fetch erp_order_details table status
            $erp_order_details[] = DB::table('erp_order_details')
            ->join('erp_status', 'erp_status.id', 'erp_order_details.status')
            ->where('order_qnty_table_name', 'tesco_washcare_orders_quanty')
            ->whereIn('order_qnty_table_id', $order_quantity_ids)
            ->select('erp_order_details.id', 'erp_status.status_name')
            ->first();

            if ($order_details) {
                $result = response()->json([
                    'error' => false,
                    'message' => 'success',
                    'order_details' => $order_details,
                    'moq' => $moq,
                    'erp_details' => $erp_order_details,
                    'symbols' => $this->symbols,
                    'tesco_suppliers' => $tesco_suppliers,
                    'coo' => $this->coo,
                    'kids_warning' => $this->kids_warning,
                    'components' => $this->components,
                    'fabriclists' => $this->fabriclists,
                    'additional_instructions' => $this->additional_instructions,
                    'part_of_two_piece_set' => $this->part_of_two_piece_set
                ], 200);
            } else {
                $result = response()->json(['error' => true, 'message' => 'Error'], 400);
            }
            return $result;
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
