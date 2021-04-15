<?php
namespace App\Classes;
use DB;

use App\TescoWashcareOrders;
use App\TescoWashcareOrdersQuantity;
use App\TescoWashcareOrdersAdditionalCareInstructions;
use App\TescoWashcareOrdersComponentFibreMapping;
use App\TescoWashcareOrdersComponentMapping;
/**
 * Class to store tesco label orders
 * @author Praveen
 */
class StoreTescoLabelOrder 
{


     public function storeTescoLabelOrder($request)
     {
        if($request->tesco_care_supplier){

            $tesco_main_customer_id     = DB::table("customer_metadata")
            ->where('customer_metadata_id','=', $request->tesco_care_supplier)
            ->select('customer_id')
            ->first();
           
            $customer_profile_id = DB::table("customer_profile")
            ->where('company_id','=',$tesco_main_customer_id->customer_id)
            ->where('role_id','=',1)
            ->select('id')
            ->first();
        
            $created_by = $customer_profile_id != null?$customer_profile_id->id:0;

        } else {

            $created_by = 0;

        }

        $total_qty = array_sum($request->quantity);

        $is_order_count_empty = $total_qty>0?0:1;

        $order_status  = $is_order_count_empty === 1?'Order Pending':'Order Placed';

        $edit_permission  = $is_order_count_empty === 1?1:0;
        

        $country_translation = DB::table('tesco_countries')
        ->where('id', $request->tesco_care_coo)
        ->select('english', 'polish', 'czech', 'slovak', 'hungarian')
        ->get();

        $kids_warning_translation = DB::table('tesco_kids_warning_statements')
        ->where('id', $request->tesco_care_kids_warning)
        ->select('english', 'polish', 'czech', 'slovak', 'hungarian')
        ->get();
    
        $insert_data = array(
            'template_id'                    => $request->template_id,
            'po_number'                      => $request->tesco_care_po,
            'uk_style_ref'                   => $request->tesco_care_uk_style,
            'ce_style_ref'                   => $request->tesco_care_ce_style,
            'eqos_site_code'                 => $request->tesco_care_eqos,
            'tesco_supplier_id'              => $tesco_main_customer_id->customer_id,
            'tesco_metadata_supplier_id'     => $request->tesco_care_supplier,
            'fabric_batch_code'              => $request->tesco_care_fabric_code,
            'leather_suede_code'             => $request->tesco_care_leather_id,
            'tog'                            => $request->tesco_care_tog,
            'country_of_origin_id'           => $request->tesco_care_coo,
            'country_of_origin_text'         => $country_translation,
            'kids_warning_id'                => $request->tesco_care_kids_warning,
            'kids_warning_text'              => $kids_warning_translation,
            'primary_size_id'                => $request->primary_size_id,
            'wash_symbol_id'                 => $request->tesco_care_wash_instruction,
            'bleach_symbol_id'               => $request->tesco_care_bleach_instruction,
            'dry_symbol_id'                  => $request->tesco_care_dry_instruction,
            'ironing_symbol_id'              => $request->tesco_care_iron_instruction,
            'dryclean_symbol_id'             => $request->tesco_care_dry_clean_instruction,
            'include_btn_sew_space'          => $request->include_btn_sew_space,
            'exclusive_trims'                => $request->exclusive_trims,
            'contains_non_textile_part'      => $request->contains_non_textile_part,
            'part_of_set'                    => $request->part_of_set,
            'bs_en_declaration'              => $request->bs_en_declaration,
            'baby_sleeping_bag_symbol'       => $request->baby_sleeping_bag_symbol,
            'order_status'                   => $order_status,
            'order_flag'                     => 'newOrder',
            'po_status'                      => "",
            'pi_status'                      => "",
            'total_qty'                      => $total_qty,
            'edit_permission'                => $edit_permission,
            'order_placed_by'                => $created_by,
            // 'exclusive_trims_text'           => "",
            // 'contains_non_textile_part_text' => "",
            // 'part_of_set_text'               => "",
            'is_order_count_empty'           => $is_order_count_empty,
        );

        $washcare_order_id = TescoWashcareOrders::create($insert_data)->id;

        $uk_ean = $request->uk_ean;

        $ce_ean = $request->ce_ean;

        $quantity = $request->quantity;

        $secondary_size = $request->secondary_size_id;

        for($qty_index = 0; $qty_index < count($uk_ean); $qty_index++)
        {
            $qty_data[] = array(
                'washcare_order_id' => $washcare_order_id,
                'uk_ean'   => $uk_ean[$qty_index],
                'ce_ean'   => $ce_ean[$qty_index],
                'quantity' => $quantity[$qty_index],
                'size_id'  => $secondary_size[$qty_index]
            );
        }

        TescoWashcareOrdersQuantity::insert($qty_data);

        $additional_instruction = $request->tesco_care_additional_instruction;
       
        for($additional_index = 0; $additional_index < count($additional_instruction); $additional_index++)
        {
            $additional_text = DB::table('tesco_care_additional_instructions')
            ->where('id', $additional_instruction[$additional_index])
            ->select('english', 'polish', 'czech', 'slovak', 'hungarian')
            ->get();

            $additional_data[] = array(
                'washcare_order_id' => $washcare_order_id,
                'additional_care_id'   => $additional_instruction[$additional_index],
                'additional_care_text'   => $additional_text
            );
        }

        TescoWashcareOrdersAdditionalCareInstructions::insert($additional_data);
        // DynamicField::insert($insert_data);

     }

}