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

class SwingTicketOrdersCustomerController extends Controller
{
    private $caremeta;
    private $swingTicketMetadata;
    private $swingTicketOrder;

    public function __construct(TescoSwingTicketMetadata $swingTicketMetadata, TescoCareMetadata $caremeta, StoreTescoSwingTicketOrder $swingTicketOrder, CustomersList $customers)
    {
        $this->swingTicketMetadata = $swingTicketMetadata;

        $this->caremeta = $caremeta;

        $this->swingTicketOrder = $swingTicketOrder;

        $this->customers = $customers;
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

            $product_description = $this->swingTicketMetadata->getProductDescription();

            $wee_symbols = $this->swingTicketMetadata->getWeeSymbols();

            $woolmark_logos = $this->swingTicketMetadata->getwoolmarkLogos();

            $season_codes = $this->swingTicketMetadata->getSeasonCodes();

            $coo = $this->caremeta->getCountryOrigin();

            $tesco_suppliers = $this->customers->getCustomers(Auth::guard('web_customer')->id());

            return View::make('customer/tesco-customer-swing-tickets')
                ->with([
                    'product_benefits' => $product_benefits,
                    'tesco_suppliers' => $tesco_suppliers,
                    'coo' => $coo,
                    'mm_product_benefits' => $mm_product_benefits,
                    'product_description' => $product_description,
                    'wee_symbols' => $wee_symbols,
                    'woolmark_logos' => $woolmark_logos,
                    'size_data' => $size_data,
                    'template' => $request['template'],
                    'season_codes' => $season_codes
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

            $response = $this->swingTicketOrder->storeTescoSwingTicketOrder($request, 'admin');

            return $response;
        }
    }
}
