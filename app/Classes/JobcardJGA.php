<?php
namespace App\Classes;
use DB;
use Carbon\Carbon;
use App\ErpOrders as Erp;
use App\Classes\JobcardMetadata;
use App\Classes\JobcardCommonClass;
use App\ErpOrderDetails as ErpOrderDetails;

class JobcardJGA
{
	public function getOrderDataForJGAJC($request, $erp_jc_status_id, $order_ap_status_id)
	{
        $jc_class_obj = new JobcardCommonClass();
		$jc_metadata = New JobcardMetadata();
        $jobcard_id_gen_obj = new JobcardIDGenerator();
        $erp_orders_ids     = explode(',', $request['orders']);
        $selected_ref_arrays= explode(',', $request['ref_array']);
        $buyer_pos          = explode(',', $request['buyer_po_array']);
        $order_type         = explode(',', $request['order_type_array']);
        $uuid               = explode(',', $request['uuid_array']);
        $order_dt_array     = array();
        $delivery_dt_array  = array();
        $style_array        = array();
        $ordertype_array    = array();
        $temp_id_array      = array();
        $template_data      = array();
        $ratio_templates    = array();
        $single_templates   = array();
        $distinct_ref_array = array();
        $single_temp_data   = array();
        $order_detail_ids   = array();
        $erp_id_array       = array();
        $customer_po_array  = array();
        $color_code         = array();
        $tint_color         = array();

        /** Get erp order details table ids from erp orders table id */
        foreach ($erp_orders_ids as $row => $erp_orders_id) {
            // buyer po number is not mandatory for orchestra.
            // So splitting query using conditional statements.
            $order_detail_id_array = ErpOrderDetails::where([
                'erp_order_details.erp_order_id' => $erp_orders_id,
                'erp_order_details.order_type' => $order_type[$row],
                'erp_order_details.uuid' => $uuid[$row],
                'erp_order_details.status' => $erp_jc_status_id,
                'erp_order_details.order_status_id' => $order_ap_status_id,
                'erp_order_details.reference_name' => $selected_ref_arrays[$row]
            ]);
            if ($buyer_pos[$row] != "") {
                $order_detail_id_array = $order_detail_id_array->where('erp_order_details.buyer_po_number', $buyer_pos[$row])
                    ->pluck('erp_order_details.id')->all();
            } else {
                $order_detail_id_array = $order_detail_id_array->pluck('erp_order_details.id')->all();
            }

            $order_detail_ids = array_unique(array_merge($order_detail_ids, $order_detail_id_array));
        }
        sort($order_detail_ids);
        
        $order_details_collection = Erp::with(['customer:id,short_name', 'buyer:id,buyer_name,template_table,item_master_table'])
        ->join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
        ->join('company', 'erp_order_details.company_id', 'company.id')
        ->whereIn('erp_order_details.id', $order_detail_ids)
        ->select('company.name as company', 'company.id as company_id', 'company.address as company_address', 'company.logo as company_logo', 'erp_order_details.*', 'erp_orders.buyer_id', 'erp_orders.customer_id', 'erp_orders.erp_id', 'erp_orders.po_no')
        ->get();

        foreach ($order_details_collection as $key => $order_details) {

            !in_array($order_details->created_at, $order_dt_array, true) ? array_push($order_dt_array, $order_details->created_at) : $order_dt_array;

            !in_array(Carbon::parse($order_details->delivery_date)->format('d-m-Y'), $delivery_dt_array, true) ? array_push($delivery_dt_array, Carbon::parse($order_details->delivery_date)->format('d-m-Y')) : $delivery_dt_array;

            !in_array($order_details->style_no, $style_array, true) ? array_push($style_array, $order_details->style_no) : $style_array;

            !in_array($order_details->order_type, $ordertype_array, true) ? array_push($ordertype_array, $order_details->order_type) : $ordertype_array;

            !in_array($order_details->template_id, $temp_id_array, true) ? array_push($temp_id_array, $order_details->template_id) : $temp_id_array;

            !in_array($order_details->reference_name, $distinct_ref_array, true) ? array_push($distinct_ref_array, $order_details->reference_name) : $distinct_ref_array;

            !in_array($order_details->po_no, $customer_po_array, true) ? array_push($customer_po_array, $order_details->po_no) : $customer_po_array;

            /** Create separate ratio/single templates array  */
            if ($order_details->template_type == "ratio") {
                if ($order_details_collection[0]['buyer']->buyer_name === "Tesco") {
                    $order_main_table_data = DB::table($order_details->order_qnty_table_name)
                    ->select($order_details->order_qnty_table_name . '.id as qty_table_id', $order_details->order_qnty_table_name . '.ratio_pack_type')
                    ->first();

                    $order_details->ratio_pack_type = $order_main_table_data->ratio_pack_type;
                } else {
                    $order_main_table_data = DB::table($order_details->order_qnty_table_name)
                    ->select($order_details->order_qnty_table_name . '.id as qty_table_id')
                    ->first();
                }

                $order_details->qty_table_id = $order_main_table_data->qty_table_id;

                $ratio_templates[$order_details->reference_name][] = $order_details;
            } else {
                // to differenciate reorder and repeatorder having same values
                $unique_id = $order_details->uuid;

                $erp_id_array[$order_details->reference_name][] = $unique_id;

                $single_templates[$order_details->reference_name]['order_data'][$unique_id]['ocn_number'] = $order_details->ocn_number;
                $single_templates[$order_details->reference_name]['order_data'][$unique_id]['erp_id'] = $order_details->erp_id;
                $single_templates[$order_details->reference_name]['order_data'][$unique_id]['buyer_po_number'] = $order_details->buyer_po_number;
                $single_templates[$order_details->reference_name]['order_data'][$unique_id]['template_id'] = $order_details->template_id;
                $single_templates[$order_details->reference_name]['size'][$order_details->size][$unique_id] = $order_details->order_quantity;
            }

            // Add color code and age group pantone color as tint color for orchestra
            if ($order_details_collection[0]['buyer']->buyer_name === "Orchestra") {
                // parse orchestra orders table and table id from quantity table name
                $main_table     = str_replace("_qty", "", $order_details->order_qnty_table_name);
                $result_str     = str_replace("_", " ", str_replace("_orders", "", $main_table));
                $qnty_id        = '';
                foreach (explode(' ', $result_str) as $word) {
                    $qnty_id .= strtolower($word[0]);
                }

                if(in_array($main_table, ['orchestra_solid_carton_sticker_orders', 'orchestra_solid_poly_bag_sticker_orders', 'orchestra_standard_carton_sticker_orders', 'orchestra_standard_poly_bag_sticker_orders'])){
                    $orchestra_color_details = DB::table($order_details->order_qnty_table_name)
                    ->select($main_table . '.color_code', 'orchestra_age_group.pantone_color')
                    ->join($main_table, $main_table . '.id', $order_details->order_qnty_table_name . '.' . $qnty_id . '_id')
                    ->join('orchestra_age_group',  'orchestra_age_group.id', $main_table . '.group_id')
                    ->where($order_details->order_qnty_table_name . '.id', $order_details->order_qnty_table_id)
                    ->first();

                    !in_array($orchestra_color_details->color_code, $color_code, true) ? array_push($color_code, $orchestra_color_details->color_code) : $color_code;
                    !in_array($orchestra_color_details->pantone_color, $tint_color, true) ? array_push($tint_color, $orchestra_color_details->pantone_color) : $tint_color;
                }
            }
        }

        if(empty($temp_id_array)){
            return response()->json(['error' => true, 'message' => 'Template details are missing, contact your admin.'], 404);
        }

        /** Get templates metadata details */
        list($print_color_array, $material_array, $article_array, $length_array, $width_array, $type_array, $name_in_reports_array) = $jc_class_obj->getTemplateData($order_details_collection[0]['buyer'], $temp_id_array);

        /** Different size breakup value handling */
        $final_chunk = $jc_class_obj->manipulateSizeData($single_templates, $erp_id_array);

        $company_array = $jc_class_obj->companyData($order_details_collection[0]);

        // jobcard id format "Itemname(2)FinancialYearERPID(last 5)0001" where  and for old order "JGA-JC-1"
        $workorder_id = $jobcard_id_gen_obj->generateJobcardID($article_array, $erp_orders_ids);

        /** Push all array values into template_data array */
        $template_data['order_date']       = Carbon::parse(min($order_dt_array))->format('d-m-Y');
        $template_data['cus_del_date']     = $delivery_dt_array;
        $template_data['today_date']       = Carbon::parse(Carbon::now('Asia/Kolkata')->format('Y-m-d'))->format('d-m-Y');
        $template_data['workorder_id']     = $workorder_id;
        $template_data['company']          = $company_array;
        $template_data['customer']         = $order_details_collection[0]->customer;
        $template_data['buyer']            = $order_details_collection[0]->buyer;
        $template_data['ordertype_array']  = $ordertype_array;
        $template_data['style_array']      = $style_array;
        $template_data['refnum_array']     = $distinct_ref_array;
        $template_data['color']            = $print_color_array;
        $template_data['material']		   = $material_array;
        $template_data['article']          = $article_array;
        $template_data['length']   		   = $length_array;
        $template_data['width']            = $width_array;
        $template_data['ratio']            = $ratio_templates;
        $template_data['single']           = $final_chunk;
        $template_data['order_detail_ids'] = $order_detail_ids;
        $template_data['customer_po_array'] = $customer_po_array;
        $template_data['color_code']       = $color_code;
        $template_data['tint_color']       = $tint_color;
        $metadata                          = $jc_metadata->erpJobcardMetaData();

        return array($template_data, $metadata);
    }
    
}