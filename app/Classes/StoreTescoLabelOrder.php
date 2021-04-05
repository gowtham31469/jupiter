<?php
namespace App\Classes;
use DB;
/**
 * Class to store tesco label orders
 * @author Praveen
 */
class StoreTescoLabelOrder 
{
    /**
     * Method to store label order
     * @author Praveen <praveen@wizroots.com>
     * @param mixed $request
     */

     public function storeTescoLabelOrder($request)
     {
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
            'order_flag'                   => 'newOrder',
            'po_status'                    => "",
            'pi_status'                    => "",
            'total_qty'                    => "",
            'edit_permission'              => "",
            'order_placed_by'              => $created_by,
            'exclusive_trims_text'         => "",
            'contains_non_textile_part_text'=> "",
            'part_of_set_text'             => "",
            'is_order_count_empty'         => "",
        );
        // DynamicField::insert($insert_data);

     }

}