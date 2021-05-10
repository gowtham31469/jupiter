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
/**
 * Class to store tesco label orders
 * @author Praveen
 */
class StoreTescoLabelOrder 
{

     public function storeTescoLabelOrder($request, $role)
     {
         
        $order_history_obj = new StoreOrderManagementHistory();

        $template_data = DB::table('tesco_item_master')
        ->join('tesco_template', 'tesco_item_master.id', 'tesco_template.item_id')
        ->join('reports_template_level_name', 'reports_template_level_name.id', 'tesco_template.name_in_reports')
        ->where('tesco_item_master.id', $request->template_id)
        ->select('tesco_item_master.item_name', 'reports_template_level_name.name as name_in_reports', 'tesco_item_master.company_id')
        ->first();

        $max_id = TescoWashcareOrders::max('id');

        $order_id = $template_data->company_id == 1?"JGA_".str_replace(" ","",$template_data->item_name)."00".($max_id+1):"SP_".str_replace(" ","",$template_data->item_name)."00".($max_id+1);
        
        $customer_data = $this->getCustomerDetails($request->tesco_care_supplier, $role);
       
        $total_qty = array_sum($request->quantity);

        $is_order_count_empty = $total_qty > 0?0:1;

        $order_status  = $is_order_count_empty === 1?'Order Pending':'Order Placed';

        $edit_permission  = $is_order_count_empty === 1?1:0;

        $pi_status = $customer_data['pi_required'] == 1?"PI Approval Pending":null;

        $po_status = $customer_data['po_required'] == 1?"PO Pending":null;

        $delivery_date_start     = Carbon::now('Asia/Kolkata')->addDay($customer_data['day_count']);

        $country_translation = DB::table('tesco_countries')
        ->where('id', $request->tesco_care_coo)
        ->select('english', 'polish', 'czech', 'slovak', 'hungarian')
        ->get();

        $kids_warning_translation = DB::table('tesco_kids_warning_statements')
        ->where('id', $request->tesco_care_kids_warning)
        ->select('english', 'polish', 'czech', 'slovak', 'hungarian')
        ->get();

        $additional_text_1 = !is_null($request->tesco_care_additional_instruction_1)?DB::table('tesco_care_additional_instructions')
        ->where('id', $request->tesco_care_additional_instruction_1)
        ->select('english', 'polish', 'czech', 'slovak', 'hungarian')
        ->get():null;
        
        $additional_text_2 = !is_null($request->tesco_care_additional_instruction_2)?DB::table('tesco_care_additional_instructions')
        ->where('id', $request->tesco_care_additional_instruction_2)
        ->select('english', 'polish', 'czech', 'slovak', 'hungarian')
        ->get():null;

        $additional_text_3 = !is_null($request->tesco_care_additional_instruction_3)?DB::table('tesco_care_additional_instructions')
        ->where('id', $request->tesco_care_additional_instruction_3)
        ->select('english', 'polish', 'czech', 'slovak', 'hungarian')
        ->get():null;

        $exclusive_trim = !is_null($request->tesco_care_exclusive_trim)?DB::table('tesco_care_additional_instructions')
        ->where('english', 'Exclusive of trims')
        ->select('english', 'polish', 'czech', 'slovak', 'hungarian')
        ->get():null;

        $non_textile_part = !is_null($request->tesco_care_non_textile_part)?DB::table('tesco_fabriclists')
        ->where('english', 'Contains non-textile parts of animal origin')
        ->select('english', 'polish', 'czech', 'slovak', 'hungarian')
        ->get():null;

        $part_of_set = !is_null($request->tesco_care_part_of_set)?DB::table('tesco_care_additional_instructions')
        ->where('english', 'part of set')
        ->select('english', 'polish', 'czech', 'slovak', 'hungarian')
        ->get():null;
        
        $insert_data = array(
            'order_id'                       => $order_id,
            'zone'                           => 'joint',
            'template_type'                  => 'joint',
            'template_id'                    => $request->template_id,
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
            'country_of_origin_text'         => $country_translation,
            'kids_warning_id'                => $request->tesco_care_kids_warning,
            'kids_warning_text'              => $kids_warning_translation,
            'primary_size_id'                => $request->primary_size_id,
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
            'order_flag'                     => 'newOrder',
            'po_status'                      => $po_status,
            'pi_status'                      => $pi_status,
            'total_qty'                      => $total_qty,
            'edit_permission'                => $edit_permission,
            'order_placed_by'                => $customer_data['created_by'],
            'exclusive_trims_text'           => $exclusive_trim,
            'contains_non_textile_part_text' => $non_textile_part,
            'part_of_set_text'               => $part_of_set,
            'is_order_count_empty'           => $is_order_count_empty,
            'order_created_at'               => Carbon::now('Asia/Kolkata')->toDateTimeString(),
            'additional_care_id_1'           => $request->tesco_care_additional_instruction_1,
            'additional_care_text_1'         => $additional_text_1,
            'additional_care_id_2'           => $request->tesco_care_additional_instruction_2,
            'additional_care_text_2'         => $additional_text_2,
            'additional_care_id_3'           => $request->tesco_care_additional_instruction_3,
            'additional_care_text_3'         => $additional_text_3,
        );

        $washcare_order_id = TescoWashcareOrders::create($insert_data)->id;

        for($qty_index = 0; $qty_index < count($request->uk_ean); $qty_index++)
        {

            $qty_data[] = array(
                'washcare_order_id'        => $washcare_order_id,
                'uk_ean'                   => $request->uk_ean[$qty_index],
                'ce_ean'                   => $request->ce_ean[$qty_index],
                'quantity'                 => $request->quantity[$qty_index],
                'size_id'                  => $request->secondary_size_id[$qty_index],
                'selected_secondary_sizes' => json_decode( json_encode($request->secondary_size_data[$qty_index]), true)
            );

        }

        TescoWashcareOrdersQuantity::insert($qty_data);

        $tesco_care_garment_component = $request->tesco_care_garment_component;

        $fiber_total_percentage = $request->fiber_total_percentage;
       
        for($component_index = 0; $component_index < count($tesco_care_garment_component); $component_index++)
        {

            if(is_null($tesco_care_garment_component[$component_index]) && ( is_null($fiber_total_percentage[$component_index]) || $fiber_total_percentage[$component_index] === 0  ) ) {

                continue;

            }
            
            $component_text = DB::table('tesco_components')
            ->where('id', $tesco_care_garment_component[$component_index])
            ->select('english', 'polish', 'czech', 'slovak', 'hungarian')
            ->get();
            
            $garment_comp_id = TescoWashcareOrdersComponentMapping::create([
                'washcare_order_id' => $washcare_order_id,
                'garment_component_id'   => $tesco_care_garment_component[$component_index],
                'garment_component_text'   => $component_text,
                'total_composition_percentage' => $fiber_total_percentage[$component_index]
            ])->id;
            
            $fiber_array_name = 'tesco_care_fiber_'.($component_index+1);
            $tesco_care_fiber = $request->$fiber_array_name;
            
            $individual_per_array_name = 'fiber_individual_percentage_'.($component_index+1);
            $fiber_individual_percentage = $request->$individual_per_array_name;

            $fiber_data = [];
            for ($fiber_index=0; $fiber_index < count($tesco_care_fiber); $fiber_index++) { 

                if(is_null($tesco_care_fiber[$fiber_index]) && ( is_null($fiber_individual_percentage[$fiber_index]) || $fiber_individual_percentage[$fiber_index] === 0  ) ) {

                    continue;

                }
                
                $fiber_text = DB::table('tesco_fabriclists')
                ->where('id', $tesco_care_fiber[$fiber_index])
                ->select('english', 'polish', 'czech', 'slovak', 'hungarian')
                ->get();
                
                $fiber_data[] = array(
                    'washcare_component_id' => $garment_comp_id,
                    'fibre_composition_id'   => $tesco_care_fiber[$fiber_index],
                    'fibre_composition_text'   => $fiber_text,
                    'fibre_composition_percentage' => $fiber_individual_percentage[$fiber_index]
                );

            }

            TescoWashcareOrdersComponentFibreMapping::insert($fiber_data);
            
        }

        /** Insert order data into history table */
        $order_data = [];

        $order_data['season_code'] = null;
        $order_data['po_number'] = $request->tesco_care_po;
        $order_data['ocn_no'] = null;
        $order_data['style'] = $request->tesco_care_uk_style . '/' . $request->tesco_care_ce_style;
        $order_data['customer_id'] = $customer_data['customer_id'];
        $order_data['template_id'] = $request->template_id;
        $order_data['order_id'] = $washcare_order_id;
        $order_data['po_status'] = $po_status;
        $order_data['pi_required'] = $customer_data['pi_required'];
        $order_data['order_status_id'] = $order_status === 'Order Pending'?6:2;
        $order_data['edit_permission'] = $edit_permission;
        $order_data['tesco_label'] = true;
        $order_data['order_table'] = "tesco_washcare_orders";

        $order_history_obj->storeOrdersHistory($order_data);

        $response = [];

        $response['last_insertion']         = $washcare_order_id;
        $response['delivery_date_start']    = $delivery_date_start;
        $response['pi_required']            = $customer_data['pi_required'];
        $response['po_required']            = $customer_data['po_required'];
        $response['delivery_date_required'] = $customer_data['delivery_date_req'];
        $response['is_order_count_empty']   = $is_order_count_empty;
        $response['style_number']           = $request->tesco_care_uk_style . '/' . $request->tesco_care_ce_style;
        $response['po_number']              = $request->tesco_care_po;

        return $response;

     }


     public function getCustomerDetails($tesco_care_supplier, $user_type) 
     {
        $customer_data = [];

        $customer_data['created_by'] = 0;
        $customer_data['delivery_date_req']= 0; 
        $customer_data['pi_required']      = 0; 
        $customer_data['po_required']      = 0; 
        $customer_data['day_count']        = 0; 
        $customer_data['customer_id']      = null;

        $tesco_main_customer_id     = DB::table("customer_metadata")
        ->where('customer_metadata_id', $tesco_care_supplier)
        ->select('customer_id')
        ->first();
       
        if($tesco_main_customer_id){ 

            if($user_type === 'admin') {

                $customer_profile_id = DB::table("customer_profile")
                ->join('customers', 'customers.id', 'customer_profile.company_id')
                ->where([
                    'company_id' => $tesco_main_customer_id->customer_id, 
                    'role_id' => 1
                ])
                ->select('customer_profile.id', 'pi_required', 'delivery_date_required', 'delivery_day_count', 'po_required')
                ->first();

            } else if($user_type === 'customer') {

                $customer_profile_id = DB::table("customer_profile")
                ->join('customers', 'customers.id', 'customer_profile.company_id')
                ->where('customer_profile.id', Auth::guard('web_customer')->id())
                ->select('customer_profile.id', 'pi_required', 'delivery_date_required', 'delivery_day_count', 'po_required')
                ->first();

            }

            $customer_data['created_by']       = $customer_profile_id != null?$customer_profile_id->id:0;
            $customer_data['delivery_date_req'] = $customer_profile_id->delivery_date_required; 
            $customer_data['pi_required']      = $customer_profile_id->pi_required; 
            $customer_data['po_required']      = $customer_profile_id->po_required;
            $customer_data['day_count']        = $customer_profile_id->delivery_day_count; 
            $customer_data['customer_id']      = $tesco_main_customer_id->customer_id;

        }

        return $customer_data;
     }

}