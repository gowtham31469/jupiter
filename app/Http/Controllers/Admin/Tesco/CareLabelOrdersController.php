<?php

namespace App\Http\Controllers\Admin\Tesco;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use DB;
use Validator;
use App\Classes\Modules;
use App\Classes\TescoCareMetadata;

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
use App\Http\Requests\SaveTescoLabelOrder;

class CareLabelOrdersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Modules $module, TescoCareMetadata $caremeta)
    {
       $this->middleware('auth');
       $this->module = $module;
       $this->caremeta = $caremeta;
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

    public function saveCareLabelOrder(SaveTescoLabelOrder $request){

        if($request->ajax()){

            $request->validated();
            
            // $components = $request->tesco_care_garment_component;
            
            // for($comp_count = 0; $comp_count < count($components); $comp_count++)
            // {
            //     $data = array(
            //         'components' => $components[$comp_count]
            //     );

            //     $insert_data[] = $data; 
            // }

            /** Check if supplier value empty or not */
            if($request->tesco_care_supplier){
              $tesco_main_customer_id     = DB::table("customer_metadata")->where('customer_metadata_id','=', $request->tesco_care_supplier)->select('customer_id')->first();
              /** [Get comapanies super admin id as created by customer id] */
              $customer_profile_id = DB::table("customer_profile")->where('company_id','=',$tesco_main_customer_id->customer_id)->where('role_id','=',1)->select('id')->first();
             
              $created_by = $customer_profile_id != null?$customer_profile_id->id:0;
            } else {
              $created_by = 0;
            }

            $country_translation = DB::table('tesco_countries')->where('id', $request->tesco_care_coo)->select('english', 'polish', 'czech', 'slovak', 'hungarian')->first();
            $kids_warning_translation = DB::table('tesco_kids_warning_statements')->where('id', $request->tesco_care_kids_warning)->select('english', 'polish', 'czech', 'slovak', 'hungarian')->first();


            $data = array(
                'template_id'                  => $request->template_id,
                'po_number'                    => $request->tesco_care_po,
                'uk_style_ref'                 => $request->tesco_care_uk_style,
                'ce_style_ref'                 => $request->tesco_care_ce_style,
                'eqos_site_code'               => $request->tesco_care_eqos,
                'tesco_supplier_id'            => $tesco_main_customer_id->customer_id,
                'tesco_metadata_supplier_id'   => $request->tesco_care_supplier,
                'fabric_batch_code'            => $request->tesco_care_fabric_code,
                'leather_sue_code'             => $request->tesco_care_leather_id,
                'tog'                          => $request->tesco_care_tog,
                'country_of_origin_id'         => $request->tesco_care_coo,
                'country_of_origin_text'       => $country_translation,
                'kids_waring_id'               => $request->tesco_care_kids_warning,
                'kids_waring_text'             => $kids_warning_translation,
                'primary_size_id'              => $request->primary_size_id,
                'wash_symbol_id'               => $request->tesco_care_wash_instruction,
                'bleach_symbol_id'             => $request->tesco_care_bleach_instruction,
                'dry_symbol_id'                => $request->tesco_care_dry_instruction,
                'ironing_symbol_id'            => $request->tesco_care_iron_instruction,
                'dryclean_symbol_id'           => $request->tesco_care_dry_clean_instruction,
                'include_btn_sew_space'        => $request->include_btn_sew_space,
                'exclusive_trims'              => $request->exclusive_trims,
                'contains_non_textile_part'    => $request->contains_non_textile_part,
                'part_of_set'                  => $request->part_of_set,
                'bs_en_declaration'            => $request->bs_en_declaration,
                'baby_sleeping_bag_symbol'     => $request->baby_sleeping_bag_symbol,
                'order_status'                 => 'Saved',
                'pi_status'                    => "",
                'po_status'                    => "",
                'order_placed_by'              => $created_by,
            );

            $insert_data[] = $data;    
            // DynamicField::insert($insert_data);
            return response()->json([
            'success'  => 'Data Added successfully.'
            ]);
        }

    }

}
