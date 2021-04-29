<?php

namespace App\Http\Controllers\Customer\Tesco;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use DB;
use Auth;
use App\Classes\TescoCareMetadata;
use App\Classes\StoreTescoLabelOrder;
use App\Classes\CancelTescoOrders;

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
use App\TescoWashcareOrdersComponentFibreMapping;
use App\TescoWashcareOrdersComponentMapping;
use App\OrderManagementHistory;
use App\FinancialYearResetDate;
use App\Http\Requests\SubmitTescoLabelOrder;

class CustomerCareLabelOrdersController extends Controller
{
    private $storeorder;
    private $caremeta;

    public function __construct(TescoCareMetadata $caremeta, StoreTescoLabelOrder $storeorder)
    {

        $this->caremeta = $caremeta;

        $this->storeorder = $storeorder;
    }

    public function index()
    {
        try {
            // fetch tesco care metadata from class
            $symbols = $this->caremeta->getCareMetadata()['symbols'];

            $coo = $this->caremeta->getCareMetadata()['coo'];

            $kids_warning = $this->caremeta->getCareMetadata()['kids_warning'];

            $components = $this->caremeta->getCareMetadata()['components'];

            $fabriclists = $this->caremeta->getCareMetadata()['fabriclists'];

            $additional_instructions = $this->caremeta->getCareMetadata()['additional_instructions'];

            $customer = DB::table('customer_profile')
                ->select('*')
                ->where('customer_profile.id', Auth::guard('web_customer')->id())
                ->first();

            $tesco_suppliers = DB::table('tesco_suppliers')
                ->join('customer_metadata', 'customer_metadata.customer_metadata_id', 'tesco_suppliers.id')
                ->where('customer_metadata.customer_id', $customer->company_id)
                ->where('is_enabled', 1)
                ->orderBy('tesco_suppliers.supplier_name')
                ->pluck('tesco_suppliers.supplier_name', 'tesco_suppliers.id')
                ->all();

            return View::make('customer/tesco-customer-care-labels')
                ->with([
                    'symbols' => $symbols,
                    'tesco_suppliers' => $tesco_suppliers,
                    'coo' => $coo,
                    'kids_warning' => $kids_warning,
                    'components' => $components,
                    'fabriclists' => $fabriclists,
                    'additional_instructions' => $additional_instructions
                ]);
        } catch (\Exception $e) {
            return response()->json([
                'Message' => $e->getMessage(),
                'Line' => $e->getLine(),
                'File' => $e->getFile()
            ]);
        }
    }

    public function getFiberComponentsMeta(Request $request)
    {
        $data['components'] = DB::table('tesco_components')
            ->where('is_enabled', 1)
            ->pluck('english', 'id')
            ->all();

        $data['fabriclists'] = DB::table('tesco_fabriclists')
            ->where('is_enabled', 1)
            ->pluck('english', 'id')
            ->all();

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
        $tesco_sizelists = TescoPrimarySize::with(['secSizeChart' => function ($query) use ($request) {
            $query->whereIn('tesco_secondary_size_chart.tesco_secondary_size_category_id', $request['size_category']);
        }])
            ->where('tesco_primary_size.tesco_size_department_id', $request['size_id'])
            ->orderBy('tesco_primary_size.id')
            ->get();

        return response()->json([
            'message' => 'success',
            'data' => $tesco_sizelists
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

    public function submitCareLabelOrder(SubmitTescoLabelOrder $request)
    {

        if ($request->ajax()) {

            $request->validated();

            $response = $this->storeorder->storeTescoLabelOrder($request, 'customer');

            return response()->json([
                'message' => 'success',
                'data' => $response
            ], 200);
        }
    }
}
