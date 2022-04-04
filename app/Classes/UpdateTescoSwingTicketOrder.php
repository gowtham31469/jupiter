<?php

namespace App\Classes;

use DB;

use App\ErpOrderDetails;
use App\Classes\EditErpOrders;
use App\TescoSwingTicketOrders;
use App\Classes\StoreTescoLabelOrder;
use App\TescoSwingTicketOrdersQuantity;
use App\Classes\StoreOrderManagementHistory;

/**
 * Class to update tesco swing ticket
 * @author Praveen
 */
class UpdateTescoSwingTicketOrder
{

    public function updateTescoSwingTicket($request, $role)
    {

         /** [ Check if order already placed for this style ] */
         $is_exists = TescoSwingTicketOrders::select('id')
         ->Where('ce_style_ref', $request->swing_option_id)
         ->where('po_number', $request->swing_po_number)
         ->where('id', '<>', $request->order_id)
         ->whereNotIn('order_status', ['Order Cancelled'])
         ->exists();
 
         if($is_exists)
         {
            return response()->json(['error'=> true,'message' =>'Order already exists'],409);
         }
         
        $order_history_obj = new StoreOrderManagementHistory();
        $swingticket_order_obj = new StoreTescoSwingTicketOrder();
        $store_order_obj = new StoreTescoLabelOrder();
        $edit_erp_obj    = new EditErpOrders();

        $customer_data = $store_order_obj->getCustomerDetails($request->swing_tesco_supplier, $role);

        $translation_texts = $swingticket_order_obj->getTranslationTexts($request);

        $total_qty = array_sum($request->swing_quantity);

        $is_order_count_empty = $total_qty > 0 ? 0 : 1;

        $order_status  = $is_order_count_empty === 1 ? 'Order Pending' : 'Order Placed';

        $edit_permission  = $is_order_count_empty === 1 ? 1 : 0;

        $insert_data = array(
            'po_number'                 => $request->swing_po_number,
            'uk_style_ref'              => $request->swing_style_number,
            'ce_style_ref'              => $request->swing_option_id,
            'season_code_id'            => $request->swing_season_code,
            'eqos_site_code'            => $request->swing_eqos,
            'tesco_supplier_id'         => $customer_data['customer_id'],
            'tesco_metadata_supplier_id'=> $request->swing_tesco_supplier,
            'country_of_origin_id'      => $request->swing_coo,
            'country_of_origin_text'    => $translation_texts['country_translation'],
            'order_status'              => $order_status,
            'is_order_count_empty'      => $is_order_count_empty,
            'total_qty'                 => $total_qty,
            'edit_permission'           => $edit_permission,
            '24_char_desc'              => strtoupper($request->swing_24_char_desc),
            'cz_selling_price'          => $request->swing_cz_price,
            'sk_selling_price'          => sprintf('%0.2f', $request->swing_sk_price),
            'hu_selling_price'          => $request->swing_hu_price,
            'wee_symbol_id'             => $request->swing_wee_symbol,
            'woolmark_logo_id'          => $request->swing_woolmark_logo,
            'product_desc_id'           => $request->swing_product_desc,
            'product_desc_text'         => $translation_texts['product_description_translation'],
            'product_benefit_id'        => !is_null($request->swing_product_benefit) ? $request->swing_product_benefit : null,
            'product_benefit_text'      => $translation_texts['product_benefits_translation'],
            'mm_product_benefit_id'     => !is_null($request->swing_mm_product_benefit) ? $request->swing_mm_product_benefit : null,
            'mm_product_benefit_text'   => $translation_texts['mm_product_benefits_translation'],
            'is_ce_mark_required'       => !is_null($request->swing_ce_mark) ? $request->swing_ce_mark : 0,
            'is_machine_washable'       => !is_null($request->swing_machine_washable) ? $request->swing_machine_washable : 0,
        );

        TescoSwingTicketOrders::where(['id' => $request->order_id])->update($insert_data);

        for ($qty_index = 0; $qty_index < count($request->swing_ean); $qty_index++) {

            $qty_data = array(
                'ean_barcode'     => $request->swing_ean[$qty_index],
                'quantity'        => !is_null($request->swing_quantity[$qty_index]) ? $request->swing_quantity[$qty_index] : 0,
            );

            TescoSwingTicketOrdersQuantity::where([
                'id' => $request->secondary_size_id[$qty_index],
            ])
            ->update($qty_data);
        }

        $current_pi_po_status = DB::table('tesco_swingticket_orders')->where(['id' => $request->order_id])->select('po_status', 'pi_status')->first();
        $season_code = DB::table('tesco_season_codes')->where('id', $request->swing_season_code)->value('name');

        /** Insert order data into history table */
        $order_data = [];

        $order_data['season_code'] = $season_code;
        $order_data['po_number'] = $request->swing_po_number;
        $order_data['ocn_no'] = null;
        $order_data['style'] = $request->swing_option_id;
        $order_data['customer_id'] = $customer_data['customer_id'];
        $order_data['template_id'] = $request->swing_template_id;
        $order_data['order_id'] = $request->order_id;
        $order_data['po_status'] = $current_pi_po_status->po_status;
        $order_data['pi_status'] = $current_pi_po_status->pi_status;
        $order_data['order_status_id'] = $order_status === 'Order Pending' ? 6 : 2;
        $order_data['edit_permission'] = $edit_permission;
        $order_data['tesco_label'] = false;
        $order_data['order_table'] = "tesco_swingticket_orders";

        $order_history_obj->storeOrdersHistory($order_data);

        // fetch erp_order_details table details for each quantity_table ids.
        $erp_order_details = ErpOrderDetails::join('erp_status', 'erp_status.id', 'erp_order_details.status')
        ->where('order_qnty_table_name', 'tesco_swingticket_orders_quanty')
        ->whereIn('order_qnty_table_id', $request->secondary_size_id)
        ->select('erp_order_details.id', 'erp_status.status_name', 'erp_order_details.erp_order_id')
        ->get();

        if($erp_order_details->isNotEmpty())
        {
            $erp_details_status =  $erp_order_details[0]->status_name;
            $erp_order_id =  $erp_order_details[0]->erp_order_id;

            $erp_order_details_id_array = $erp_order_details->pluck('id')->toArray();
        
            // delete from erp_order_details table
            DB::table('erp_order_details')->whereIn('id', $erp_order_details_id_array)->delete();
            
            $data_array = [];   
            $data_array['tableName'] = 'tesco_swingticket_orders';
            $data_array['buyer_id'] = DB::table('buyers')->where('buyer_name', 'Tesco')->value('id');
            $data_array['id'] = $request->order_id;
            $data_array['erp_details_status'] = $erp_details_status;
            $data_array['erp_order_id'] = $erp_order_id;

            $edit_erp_obj->updateTescoErpOrderDetails($data_array);
        }

        return 'success';
    }
}
