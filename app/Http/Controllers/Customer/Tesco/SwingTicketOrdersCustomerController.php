<?php

namespace App\Http\Controllers\Customer\Tesco;

use DB;
use Auth;
use View;
use App\TescoPrimarySize;
use Illuminate\Http\Request;
use App\Classes\TescoCareMetadata;

use App\Http\Controllers\Controller;
use App\Classes\Customer\CustomersList;
use App\Classes\TescoSwingTicketMetadata;
use App\Classes\StoreTescoSwingTicketOrder;
use App\Http\Requests\TescoSwingTicketOrderRequest;
use App\Classes\EditTescoOrders;
use App\Classes\UpdateTescoSwingTicketOrder;
use App\Classes\TescoSwingTicketParser;

class SwingTicketOrdersCustomerController extends Controller
{
    private $caremeta;
    private $swingTicketMetadata;
    private $swingTicketOrder;
    private $parse_swing_tickets;

    public function __construct(TescoSwingTicketMetadata $swingTicketMetadata, TescoCareMetadata $caremeta, StoreTescoSwingTicketOrder $swingTicketOrder, CustomersList $customers, TescoSwingTicketParser $parse_swing_tickets)
    {
        $this->swingTicketMetadata = $swingTicketMetadata;

        $this->caremeta = $caremeta;

        $this->swingTicketOrder = $swingTicketOrder;

        $this->customers = $customers;

        $this->product_description = $this->swingTicketMetadata->getProductDescription();

        $this->wee_symbols = $this->swingTicketMetadata->getWeeSymbols();

        $this->woolmark_logos = $this->swingTicketMetadata->getwoolmarkLogos();

        $this->season_codes = $this->swingTicketMetadata->getSeasonCodes();

        $this->coo = $this->caremeta->getCountryOrigin();

        $this->parse_swing_tickets = $parse_swing_tickets;

    }


    public function index(Request $request)
    {
        try {
            $size_data = TescoPrimarySize::with('secSizeChart')
            ->where(
                [
                    'tesco_primary_size.tesco_size_department_id' => $request['size'],
                    'tesco_primary_size.is_flag' => true
                ]
            )
            ->orderBy('tesco_primary_size.id')
            ->get();

            $product_benefits = $this->swingTicketMetadata->getProductBenefits($request['template']);

            $mm_product_benefits = $this->swingTicketMetadata->getMMProductBenefits($request['template']);

            $tesco_suppliers = $this->customers->getCustomers(Auth::guard('web_customer')->id());

            $tesco_template_file = $this->swingTicketMetadata->getTemplateFileSwingTickets($request['template']);

            return View::make('customer/tesco-customer-swing-tickets')
                ->with([
                    'product_benefits' => $product_benefits,
                    'tesco_suppliers' => $tesco_suppliers,
                    'coo' => $this->coo,
                    'mm_product_benefits' => $mm_product_benefits,
                    'product_description' => $this->product_description,
                    'wee_symbols' => $this->wee_symbols,
                    'woolmark_logos' => $this->woolmark_logos,
                    'size_data' => $size_data,
                    'template' => $request['template'],
                    'tesco_template_file' => $tesco_template_file,
                    'season_codes' => $this->season_codes
                ]);
        } catch (\Exception $e) {
            return response()->json([
                'Message' => $e->getMessage(),
                'Line' => $e->getLine(),
                'File' => $e->getFile()
            ]);
        }
    }

    public function submitSwingTicketOrder(TescoSwingTicketOrderRequest $request)
    {
        if ($request->ajax()) {

            $request->validated();

            $response = $this->swingTicketOrder->storeTescoSwingTicketOrder($request, 'customer');

            return $response;
        }
    }

    public function getSwingTicketOrderDetail(Request $request)
    {
        if ($request->ajax()) {
            $get_order_details = new EditTescoOrders();
            $result = $get_order_details->getSwingTicketOrderDetails($request);

            $product_benefits = $this->swingTicketMetadata->getProductBenefits($result['order_details']->template_id);

            $mm_product_benefits = $this->swingTicketMetadata->getMMProductBenefits($result['order_details']->template_id);

            $tesco_suppliers = $this->customers->getCustomers(Auth::guard('web_customer')->id());

            if ($result) {
                return response()->json([
                    'error' => false,
                    'message' => 'success',
                    'order_details' => $result['order_details'],
                    'moq' => $result['moq'],
                    'erp_details' => $result['erp_details'],
                    'product_benefits' => $product_benefits,
                    'tesco_suppliers' => $tesco_suppliers,
                    'coo' => $this->coo,
                    'mm_product_benefits' => $mm_product_benefits,
                    'product_description' => $this->product_description,
                    'wee_symbols' => $this->wee_symbols,
                    'woolmark_logos' => $this->woolmark_logos,
                    'season_codes' => $this->season_codes
                ], 200);
            } else {
                return response()->json(['error' => true, 'message' => 'Error'], 400);
            }
        }
    }

    public function updateSwingTicketOrder(TescoSwingTicketOrderRequest $request)
    {

        if ($request->ajax()) {

            $request->validated();

            $updateoSwingTicket = new UpdateTescoSwingTicketOrder();
            
            $response = $updateoSwingTicket->updateTescoSwingTicket($request, 'customer');

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
    public function createSwingTicketOrders(Request $request) {
        try {
            if($request->zone === 'CE') {
                return $this->parse_swing_tickets->createOrder($request);
            } else {
                return response()->json(['error' => 'Unknown Template Zone'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }

    /**
     * Method to get size chart for swing tickets
     * @param  Request $request Formdata
     * @param  $role either admin or customer
     * @return [type] view
     */
    public function getSwingSizeChart(Request $request) {
        try {
            $size_data = TescoPrimarySize::with('secSizeChart')
            ->where(
                [
                    'tesco_primary_size.tesco_size_department_id' => $request['size'],
                    'tesco_primary_size.is_flag' => true
                ]
            )
            ->orderBy('tesco_primary_size.id')
            ->get();
            return response()->json([
                'message' => 'success',
                'data' => $size_data
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
}
