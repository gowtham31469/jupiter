<?php

namespace App\Http\Controllers\Admin\Tesco;

use DB;
use View;
use App\Classes\Modules;
use App\TescoPrimarySize;
use Illuminate\Http\Request;
use App\Classes\TescoCareMetadata;

use App\Http\Controllers\Controller;
use App\Classes\TescoSwingTicketMetadata;
use App\Classes\StoreTescoSwingTicketOrder;
use App\Http\Requests\TescoSwingTicketOrderRequest;

class SwingTicketOrdersController extends Controller
{
    private $caremeta;
    private $swingTicketMetadata;
    private $module;
    private $swingTicketOrder;

    public function __construct(Modules $module, TescoSwingTicketMetadata $swingTicketMetadata, TescoCareMetadata $caremeta, StoreTescoSwingTicketOrder $swingTicketOrder)
    {
        $this->module = $module;

        $this->swingTicketMetadata = $swingTicketMetadata;

        $this->caremeta = $caremeta;

        $this->swingTicketOrder = $swingTicketOrder;
    }


    public function index(Request $request)
    {
        try {
            $get_main_module = $this->module->getMainModules();
            $get_sub_module = $this->module->getSubModules(10);

            View::composer('layouts.Admin.navbar', function ($view) use ($get_main_module, $get_sub_module) {
                return $view->with(['modules' => $get_main_module, 'sub_modules' => $get_sub_module]);
            });

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

            $tesco_suppliers = $this->caremeta->getTescoSuppliers();

            return View::make('Admin/Order Management/tesco-swing-tickets')
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
