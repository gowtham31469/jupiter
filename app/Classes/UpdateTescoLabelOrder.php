<?php
namespace App\Classes;
use DB;
use Auth;

use App\TescoWashcareOrders;
use App\TescoWashcareOrdersQuantity;
use App\TescoWashcareOrdersComponentFibreMapping;
use App\TescoWashcareOrdersComponentMapping;
use Carbon\Carbon;
use App\Classes\StoreOrderManagementHistory;
use App\Classes\StoreTescoLabelOrder;
/**
 * Class to update tesco label orders
 * @author Praveen
 */
class UpdateTescoLabelOrder 
{

     public function updateTescoLabelOrder($request, $role)
     {
        $order_history_obj = new StoreOrderManagementHistory();

        $store_order_obj = new StoreTescoLabelOrder();

        $customer_data = $store_order_obj->getCustomerDetails($request->tesco_care_supplier, $role);
       
        $total_qty = array_sum($request->quantity);

        $is_order_count_empty = $total_qty > 0?0:1;

        $order_status  = $is_order_count_empty === 1?'Order Pending':'Order Placed';

        $edit_permission  = $is_order_count_empty === 1?1:0;

        $translation_texts = $store_order_obj->getTranslationTexts($request);
        
        $insert_data = array(
            'po_number'                      => $request->tesco_care_po,
            'uk_style_ref'                   => $request->tesco_care_uk_style,
            'ce_style_ref'                   => $request->tesco_care_ce_style,
            'eqos_site_code'                 => $request->tesco_care_eqos,
            'tesco_supplier_id'              => $customer_data['customer_id'],
            'tesco_metadata_supplier_id'     => $request->tesco_care_supplier,
            'fabric_batch_code'              => $request->tesco_care_fabric_code,
            'leather_suede_code'             => $request->tesco_care_leather_id,
            'tog'                            => $request->tesco_care_tog,
            'country_of_origin_id'           => $request->tesco_care_coo,
            'country_of_origin_text'         => $translation_texts['country_translation'],
            'kids_warning_id'                => $request->tesco_care_kids_warning,
            'kids_warning_text'              => $translation_texts['kids_warning_translation'],
            'wash_symbol_id'                 => $request->tesco_care_wash_instruction,
            'bleach_symbol_id'               => $request->tesco_care_bleach_instruction,
            'dry_symbol_id'                  => $request->tesco_care_dry_instruction,
            'ironing_symbol_id'              => $request->tesco_care_iron_instruction,
            'dryclean_symbol_id'             => $request->tesco_care_dry_clean_instruction,
            'include_btn_sew_space'          => !is_null($request->tesco_care_button_space)?$request->tesco_care_button_space:0,
            'exclusive_trims'                => !is_null($request->tesco_care_exclusive_trim)?$request->tesco_care_exclusive_trim:0,
            'contains_non_textile_part'      => !is_null($request->tesco_care_non_textile_part)?$request->tesco_care_non_textile_part:0,
            'part_of_set'                    => !is_null($request->tesco_care_part_of_set)?$request->tesco_care_part_of_set:0,
            'bs_en_declaration'              => !is_null($request->tesco_care_bs_en)?$request->tesco_care_bs_en:0,
            'baby_sleeping_bag_symbol'       => !is_null($request->tesco_care_sleep_bag_symbol)?$request->tesco_care_sleep_bag_symbol:0,
            'order_status'                   => $order_status,
            'total_qty'                      => $total_qty,
            'edit_permission'                => $edit_permission,
            'exclusive_trims_text'           => $translation_texts['exclusive_trim'],
            'contains_non_textile_part_text' => $translation_texts['non_textile_part'],
            'part_of_set_text'               => $translation_texts['part_of_set'],
            'is_order_count_empty'           => $is_order_count_empty,
            'additional_care_id_1'           => $request->tesco_care_additional_instruction_1,
            'additional_care_text_1'         => $translation_texts['additional_text_1'],
            'additional_care_id_2'           => $request->tesco_care_additional_instruction_2,
            'additional_care_text_2'         => $translation_texts['additional_text_2'],
            'additional_care_id_3'           => $request->tesco_care_additional_instruction_3,
            'additional_care_text_3'         => $translation_texts['additional_text_3'],
        );

        TescoWashcareOrders::where(['id' => $request->order_id])->update($insert_data);

        for($qty_index = 0; $qty_index < count($request->uk_ean); $qty_index++)
        {

            $qty_data = array(
                'uk_ean'                   => $request->uk_ean[$qty_index],
                'ce_ean'                   => $request->ce_ean[$qty_index],
                'quantity'                 => $request->quantity[$qty_index],
            );

            TescoWashcareOrdersQuantity::where([
                'id' => $request->secondary_size_id[$qty_index]
            ])
            ->update($qty_data);

        }

        TescoWashcareOrdersComponentMapping::where('washcare_order_id', $request->order_id)->delete();

        $store_order_obj->storeGarmentComponents($request);

        /** Insert order data into history table */
        $order_data = [];

        $order_data['season_code'] = null;
        $order_data['po_number'] = $request->tesco_care_po;
        $order_data['ocn_no'] = null;
        $order_data['style'] = $request->tesco_care_uk_style . '/' . $request->tesco_care_ce_style;
        $order_data['customer_id'] = $customer_data['customer_id'];
        $order_data['template_id'] = $request->template_id;
        $order_data['order_id'] = $request->order_id;
        $order_data['po_status'] = null;
        $order_data['pi_status'] = $customer_data['pi_required'];
        $order_data['order_status_id'] = $order_status === 'Order Pending'?6:2;
        $order_data['edit_permission'] = $edit_permission;
        $order_data['tesco_label'] = true;
        $order_data['order_table'] = "tesco_washcare_orders";

        $order_history_obj->storeOrdersHistory($order_data);

        return 'success';

     }

}