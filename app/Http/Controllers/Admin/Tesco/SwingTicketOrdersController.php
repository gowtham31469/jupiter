<?php

namespace App\Http\Controllers\Admin\Tesco;

use DB;
use View;
use App\Classes\Modules;
use Illuminate\Http\Request;
use App\Classes\TescoCareMetadata;
use App\Http\Controllers\Controller;

use App\Classes\TescoSwingTicketMetadata;
use App\TescoPrimarySize;

class SwingTicketOrdersController extends Controller
{
    private $caremeta;
    private $swingTicketMetadata;
    private $module;

    public function __construct(Modules $module, TescoSwingTicketMetadata $swingTicketMetadata, TescoCareMetadata $caremeta)
    {
        $this->module = $module;

        $this->swingTicketMetadata = $swingTicketMetadata;

        $this->caremeta = $caremeta;
        
    }


    public function index($size)
    {
        try {
            $get_main_module = $this->module->getMainModules();
            $get_sub_module = $this->module->getSubModules(10);

            View::composer('layouts.Admin.navbar', function ($view) use ($get_main_module, $get_sub_module) {
                return $view->with(['modules' => $get_main_module, 'sub_modules' => $get_sub_module]);
            });

            $size_data = TescoPrimarySize::with('secSizeChart')
            ->where('tesco_primary_size.tesco_size_department_id', $size)
            ->where('tesco_primary_size.is_flag', true)
            ->orderBy('tesco_primary_size.id')
            ->get();
           
            // fetch tesco swing tickets metadata from class
            $product_benefits = $this->swingTicketMetadata->getProductBenefits();

            $mm_product_benefits = $this->swingTicketMetadata->getMMProductBenefits();

            $product_description = $this->swingTicketMetadata->getProductDescription();

            $wee_symbols = $this->swingTicketMetadata->getWeeSymbols();

            $woolmark_logos = $this->swingTicketMetadata->getwoolmarkLogos();

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
                    'size_data' => $size_data
                ]);
        } catch (\Exception $e) {
            return response()->json([
                'Message' => $e->getMessage(),
                'Line' => $e->getLine(),
                'File' => $e->getFile()
            ]);
        }
    }
}
