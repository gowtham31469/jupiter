<?php

namespace App\Classes;

use DB;
use Auth;

use App\TescoSwingTicketOrders;
use App\TescoSwingTicketOrdersQuantity;
use Carbon\Carbon;
use App\Classes\StoreOrderManagementHistory;

/**
 * Class to store tesco Swing Ticket orders
 * @author Praveen
 */
class StoreTescoSwingTicketOrder
{
    

    public function storeTescoSwingTicketOrder($request, $role)
    {

        $order_history_obj = new StoreOrderManagementHistory();
        $carelabel_obj = new StoreTescoLabelOrder();

        $template_data = DB::table('tesco_item_master')
            ->join('tesco_template', 'tesco_item_master.id', 'tesco_template.item_id')
            ->join('reports_template_level_name', 'reports_template_level_name.id', 'tesco_template.name_in_reports')
            ->where('tesco_item_master.id', $request->swing_template_id)
            ->select('tesco_item_master.item_name', 'reports_template_level_name.name as name_in_reports', 'tesco_item_master.company_id')
            ->first();

        if(count($template_data) === 0){
            $errors = ["tesco_template" => "Template details are missing"];
            return response()->json(['errors' => $errors, 'message' => 'Template details are missing'], 422);
        }
        
        $max_id = TescoSwingTicketOrders::max('id');

        $order_id = $template_data->company_id == 1 ? "JGA_" . str_replace(" ", "", str_replace("/", "_", $template_data->item_name)) . "00" . ($max_id + 1) : "SP_" . str_replace(" ", "", str_replace("/", "_", $template_data->item_name)) . "00" . ($max_id + 1);

        $customer_data = $carelabel_obj->getCustomerDetails($request->swing_tesco_supplier, $role);

        $total_qty = array_sum($request->swing_quantity);

        $is_order_count_empty = $total_qty > 0 ? 0 : 1;

        $order_status  = $is_order_count_empty === 1 ? 'Order Pending' : 'Order Placed';

        $edit_permission  = $is_order_count_empty === 1 ? 1 : 0;

        $pi_status = $customer_data['pi_required'] == 1 ? "PI Approval Pending" : null;

        $po_status = $customer_data['po_required'] == 1 ? "PO Pending" : null;

        $delivery_date_start     = Carbon::now('Asia/Kolkata')->addDay($customer_data['day_count']);

        $translation_texts = $this->getTranslationTexts($request);

        $insert_data = array(
            'template_id'               => $request->swing_template_id,
            'order_id'                  => $order_id,
            'zone'                      => 'CE',
            'template_type'             => 'single',   
            'po_number'                 => $request->swing_po_number,
            'uk_style_ref'              => $request->swing_style_number,
            'ce_style_ref'              => $request->swing_option_id,
            'season_code_id'            => $request->swing_season_code,
            'eqos_site_code'            => $request->swing_eqos,
            'tesco_supplier_id'         => $customer_data['customer_id'],
            'tesco_metadata_supplier_id'=> $request->swing_tesco_supplier,
            'country_of_origin_id'      => $request->swing_coo,
            'country_of_origin_text'    => $translation_texts['country_translation'],
            'primary_size_id'           => $request->swing_primary_size_id,
            'order_status'              => $order_status,
            'order_flag'                => $request->order_type,
            'is_order_count_empty'      => $is_order_count_empty,
            'po_status'                 => $po_status,
            'pi_status'                 => $pi_status,
            'total_qty'                 => $total_qty,
            'edit_permission'           => $edit_permission,
            'order_placed_by'           => $customer_data['created_by'],
            'order_created_at'          => Carbon::now('Asia/Kolkata')->toDateTimeString(),
            '24_char_desc'              => $request->swing_24_char_desc,
            'cz_selling_price'          => $request->swing_cz_price,
            'sk_selling_price'          => $request->swing_sk_price,
            'hu_selling_price'          => $request->swing_hu_price,
            'wee_symbol_id'             => $request->swing_wee_symbol,
            'woolmark_logo_id'          => $request->swing_woolmark_logo,
            'product_desc_id'           => $request->swing_product_desc,
            'product_desc_text'         => $translation_texts['product_description_translation'],
            'product_benefit_id'        => $request->swing_product_benefit,
            'product_benefit_text'      => $translation_texts['product_benefits_translation'],
            'mm_product_benefit_id'     => $request->swing_mm_product_benefit,
            'mm_product_benefit_text'   => $translation_texts['mm_product_benefits_translation'],
            'is_ce_mark_required'       => !is_null($request->swing_ce_mark) ? $request->swing_ce_mark : 0,
            'is_machine_washable'       => !is_null($request->swing_machine_washable) ? $request->swing_machine_washable : 0,
        );

        $swing_ticket_order_id = TescoSwingTicketOrders::create($insert_data)->id;

        for ($qty_index = 0; $qty_index < count($request->swing_ean); $qty_index++) {

            $qty_data[] = array(
                'swingticket_order_id'     => $swing_ticket_order_id,
                'ean_barcode'              => $request->swing_ean[$qty_index],
                'quantity'                 => $request->swing_quantity[$qty_index],
                'size_id'                  => $request->secondary_size_id[$qty_index],
            );
        }

        TescoSwingTicketOrdersQuantity::insert($qty_data);

        /** Insert order data into history table */
        $order_data = [];

        $order_data['season_code'] = null;
        $order_data['po_number'] = $request->swing_po_number;
        $order_data['ocn_no'] = null;
        $order_data['style'] = $request->swing_option_id;
        $order_data['customer_id'] = $customer_data['customer_id'];
        $order_data['template_id'] = $request->swing_template_id;
        $order_data['order_id'] = $swing_ticket_order_id;
        $order_data['po_status'] = $po_status;
        $order_data['pi_status'] = $pi_status;
        $order_data['order_status_id'] = $order_status === 'Order Pending' ? 6 : 2;
        $order_data['edit_permission'] = $edit_permission;
        $order_data['tesco_label'] = false;
        $order_data['order_table'] = "tesco_swingticket_orders";

        $order_history_obj->storeOrdersHistory($order_data);

        $response = [];

        $response['last_insertion']         = $swing_ticket_order_id;
        $response['delivery_date_start']    = $delivery_date_start;
        $response['pi_required']            = $customer_data['pi_required'];
        $response['po_required']            = $customer_data['po_required'];
        $response['delivery_date_required'] = $customer_data['delivery_date_req'];
        $response['is_order_count_empty']   = $is_order_count_empty;
        $response['style_number']           = $request->swing_option_id;
        $response['po_number']              = $request->swing_po_number;

        return response()->json([
            'message' => 'success',
            'data' => $response
        ], 200);
    }

    public function getTranslationTexts($request)
    {
        $translations = [];

        $translations['country_translation'] = DB::table('tesco_countries')
            ->where('id', $request->swing_coo)
            ->select('english', 'polish', 'czech', 'slovak', 'hungarian')
            ->get();

        $translations['product_description_translation'] = DB::table('tesco_product_descriptions')
            ->where('id', $request->swing_product_desc)
            ->select('english', 'polish', 'czech', 'slovak', 'hungarian')
            ->get();

        $translations['mm_product_benefits_translation'] = !is_null($request->swing_product_benefit) ? DB::table('tesco_mm_product_benefits')
            ->where('id', $request->swing_product_benefit)
            ->select('english', 'polish', 'czech', 'slovak', 'hungarian')
            ->get() : null;

        $translations['product_benefits_translation'] = !is_null($request->swing_mm_product_benefit) ? DB::table('tesco_product_benefits')
            ->where('id', $request->swing_mm_product_benefit)
            ->select('english', 'polish', 'czech', 'slovak', 'hungarian')
            ->get() : null;

        return $translations;
    }


}
