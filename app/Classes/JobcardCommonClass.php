<?php
namespace App\Classes;
use DB;
use Carbon\Carbon;
use App\ErpOrderDetails;
use App\ErpOrders as Erp;

class JobcardCommonClass
{
	public function getNatErpOrderDetails($uuid_array, $order_ap_status_id, $erp_jc_status_id){
		$order_detail_ids   = array();
		foreach ($uuid_array as $row => $uuid) {

			$order_detail_id_array = ErpOrderDetails::where([
				'erp_order_details.uuid' => $uuid,
				'erp_order_details.status' => $erp_jc_status_id,
				'erp_order_details.order_status_id' => $order_ap_status_id
			])->pluck('erp_order_details.id')->all();

			$order_detail_ids = array_unique(array_merge($order_detail_ids, $order_detail_id_array));
		}
		sort($order_detail_ids);
		
		$order_details_collection = Erp::with(['customer:id,short_name', 'buyer:id,buyer_name,template_table,item_master_table'])
		->join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
		->join('non_artwork_orders_quanty', 'non_artwork_orders_quanty.id', 'erp_order_details.order_qnty_table_id')
		->join('non_artwork_orders', 'non_artwork_orders.id', 'non_artwork_orders_quanty.non_artwork_order_id')
		->join('company', 'erp_order_details.company_id', 'company.id')
		->whereIn('erp_order_details.id', $order_detail_ids)
		->select('company.name as company', 'company.id as company_id', 'company.address as company_address', 'company.logo as company_logo', 'non_artwork_orders.color', 'erp_order_details.*', 'erp_orders.buyer_id', 'erp_orders.customer_id', 'erp_orders.erp_id', 'erp_orders.po_no', 'non_artwork_orders.composition')
		->get();

		return array($order_details_collection, $order_detail_ids);
	}

	public function createOrderDataArray($order_details_collection){
		$order_dt_array     = array();
		$delivery_dt_array  = array();
		$style_array        = array();
		$ordertype_array    = array();
		$temp_id_array      = array();
		$distinct_ref_array = array();
		$customer_po_array  = array();
		$single_templates   = array();
		$erp_id_array       = array();

		foreach ($order_details_collection as $key => $order_details) {

			// to handle non-artwork and artwork orders.
			$reference_name = $order_details->order_qnty_table_name === 'non_artwork_orders_quanty'?'non-artwork':$order_details->reference_name;

			!in_array($order_details->created_at, $order_dt_array, true) ? array_push($order_dt_array, $order_details->created_at) : $order_dt_array;

			!in_array(Carbon::parse($order_details->delivery_date)->format('d-m-Y'), $delivery_dt_array, true) ? array_push($delivery_dt_array, Carbon::parse($order_details->delivery_date)->format('d-m-Y')) : $delivery_dt_array;

			!in_array($order_details->style_no, $style_array, true) ? array_push($style_array, $order_details->style_no) : $style_array;

			!in_array($order_details->order_type, $ordertype_array, true) ? array_push($ordertype_array, $order_details->order_type) : $ordertype_array;

			!in_array($order_details->template_id, $temp_id_array, true) ? array_push($temp_id_array, $order_details->template_id) : $temp_id_array;

			!in_array($order_details->reference_name, $distinct_ref_array, true) ? array_push($distinct_ref_array, $order_details->reference_name) : $distinct_ref_array;

			!in_array($order_details->po_no, $customer_po_array, true) ? array_push($customer_po_array, $order_details->po_no) : $customer_po_array;

			/** Create separate single templates array  */
			// to differenciate reorder and repeatorder having same values
			$unique_id = $order_details->uuid;

			$erp_id_array[$reference_name][] = $unique_id;

			$single_templates[$reference_name]['order_data'][$unique_id]['ocn_number'] = $order_details->ocn_number;

			$single_templates[$reference_name]['order_data'][$unique_id]['erp_id'] = $order_details->erp_id;

			$single_templates[$reference_name]['order_data'][$unique_id]['buyer_po_number'] = $order_details->buyer_po_number;

			$single_templates[$reference_name]['order_data'][$unique_id]['template_id'] = $order_details->template_id;

			$single_templates[$reference_name]['order_data'][$unique_id]['style_no'] = $order_details->style_no;

			$single_templates[$reference_name]['order_data'][$unique_id]['color'] = $order_details->color;

			$single_templates[$reference_name]['order_data'][$unique_id]['composition'] = $order_details->composition;

			$single_templates[$reference_name]['size'][$order_details->size][$unique_id] = $order_details->order_quantity;

		}

		return array($order_dt_array, $delivery_dt_array, $style_array, $ordertype_array, $temp_id_array, $distinct_ref_array, $customer_po_array, $single_templates, $erp_id_array);
	}

	public function getTemplateData($buyer, $temp_id_array) {
		$print_color_array  = array();
		$material_array     = array();
		$article_array      = array();
		$length_array       = array();
		$width_array        = array();
		$type_array         = array();
		$name_in_reports_array = array();

		$template_details = DB::table($buyer->template_table)
                ->select($buyer->template_table . '.color', $buyer->template_table . '.dimension_height as length', $buyer->template_table . '.dimension_width as width', $buyer->item_master_table . '.item_name as article', 'raw_material_master.material_name_employee as material',  $buyer->template_table . '.material_id', $buyer->template_table.'.type as template_type', 'reports_template_level_name.name as name_in_reports')
                ->join($buyer->item_master_table, $buyer->item_master_table . '.id', $buyer->template_table . '.hierarchy_id')
                ->join('reports_template_level_name', 'reports_template_level_name' . '.id', $buyer->template_table . '.name_in_reports')
                ->join('raw_material_master', 'raw_material_master.id', $buyer->template_table . '.material_id')
                ->whereIn($buyer->template_table . '.item_id', $temp_id_array)
                ->orderBy($buyer->template_table . '.id')
                ->get();

		foreach ($template_details as $template_detail) {
			!in_array($template_detail->color, $print_color_array, true) ? array_push($print_color_array, $template_detail->color) : $print_color_array;

			!in_array($template_detail->material, $material_array, true) ? array_push($material_array, $template_detail->material) : $material_array;

			!in_array($template_detail->article, $article_array, true) ? array_push($article_array, $template_detail->article) : $article_array;

			!in_array($template_detail->length, $length_array, true) ? array_push($length_array, $template_detail->length) : $length_array;

			!in_array($template_detail->width, $width_array, true) ? array_push($width_array, $template_detail->width) : $width_array;

			!in_array($template_detail->template_type, $type_array, true) ? array_push($type_array, $template_detail->template_type) : $type_array;

			!in_array($template_detail->name_in_reports, $name_in_reports_array, true) ? array_push($name_in_reports_array, $template_detail->name_in_reports) : $name_in_reports_array;

		}

		return array($print_color_array, $material_array, $article_array, $length_array, $width_array, $type_array, $name_in_reports_array);
	}

	public function manipulateSizeData($single_templates, $erp_id_array)
	{
		$final_chunk        = array();
		$size_chunk         = array();
		$common_data_chunk  = array();

		foreach ($single_templates as $template => $template_item) {
			ksort($template_item['order_data']);
			$po_count = sizeof($template_item['order_data']);

			foreach ($template_item['size'] as $size => $qty) {
				$size_count = sizeof($qty);

				if ($size_count < $po_count) {
					/** push 0 qty value for missing/additional size-breakup  */
					for ($i = 0; $i < sizeof($erp_id_array[$template]); $i++) {
						$qty = !array_key_exists($erp_id_array[$template][$i], $qty) ? array_merge($qty, [$erp_id_array[$template][$i] => 0]) : $qty;
					}
				}
				ksort($qty);

				$template_item['size'][$size] = $qty;
			}
			$single_templates[$template] = $template_item;

			/** Split template data array into small chunks (3 elements in each chunks) */
			/** common data chunk */
			$common_data_chunk = array_chunk($template_item['order_data'], 3);
			foreach ($common_data_chunk as $key => $data) {
				$final_chunk[$template . '_' . $key]['order_data'] = $data;
			}

			foreach ($template_item['size'] as $size => $qty) {
				/** size-quantity chunk */
				$size_chunk =  array_chunk($qty, 3, true);
				foreach ($size_chunk as $key2 => $value2) {
					/** check if all the size values are 0 or not
					 * remove following if condition if client wants zero qty size in final table.
					 */
					$is_size_qty_empty = array_filter($value2) != NULL ? 0 : 1;
					if ($is_size_qty_empty != 1) {
						$final_chunk[$template . '_' . $key2]['size'][$size . '_' . $key2] = $value2;
					}
				}
			}
		}

		return $final_chunk;
    }

	public function companyData($company_data){
		$company_array['company'] = $company_data->company;
		$company_array['company_id'] = $company_data->company_id;
		$company_array['company_address'] = $company_data->company_address;
		$company_array['company_logo'] = $company_data->company_logo;

		return $company_array;
	}

    
}