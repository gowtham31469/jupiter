<?php

namespace App\Classes;

use DB;
use Auth;
use App\History;
use Carbon\Carbon;
use App\ErpOrderDetails;
use App\JobcardMainTable;
use App\Classes\NATMaterialQuery;

/**
 * Class to update / create jobcard main table
 * @author Praveen
 */
class UpdateCreateJobcardMainTable
{

    public function updateOrCreateJobcardData($request, $workorder_id, $current_timestamp, $crud_type, $is_non_artwork_template)
    {

        $created_date    = $request['created_date'] == '' ? NULL : Carbon::parse($request['created_date'], 'Asia/Kolkata')->toDateString();
        $order_date      = $request['order_date'] == '' ? NULL : Carbon::parse($request['order_date'], 'Asia/Kolkata')->toDateString();
        $cus_del_date    = $request['cus_del_date'] == '' ? NULL : Carbon::parse($request['cus_del_date'], 'Asia/Kolkata')->toDateString();
        $invoice_date    = $request['invoice_date'] == '' ? NULL : Carbon::parse($request['invoice_date'], 'Asia/Kolkata')->toDateString();
        $approval_date   = $request['approval_date'] == '' ? NULL : Carbon::parse($request['approval_date'], 'Asia/Kolkata')->toDateString();
        $act_del_date    = $request['act_del_date'] == '' ? NULL : Carbon::parse($request['act_del_date'], 'Asia/Kolkata')->toDateString();
        $aw_app_date     = $request['aw_app_date'] == '' ? NULL : Carbon::parse($request['aw_app_date'], 'Asia/Kolkata')->toDateString();

        return JobcardMainTable::updateOrCreate(
            [   'work_order_id'          => $workorder_id,],
            [
                'company_id'             => $request['company_id'],
                'customer_id'            => $request['customer_id'],
                'buyer_id'               => $request['buyer_id'],
                'created_date'           => $created_date,
                'material'               => $request['material'],
                'article'                => $request['article'],
                'reference'              => $request['reference'],
                'order_type'             => $request['order_type'],
                'dispatch_mode'          => $request['disp_mode'],
                'order_date'             => $order_date,
                'length'                 => $request['length'],
                'repeat_gap'             => $request['repeat_gap'],
                'machine'                => $request['machine'],
                'customer_del_date'      => $cus_del_date,
                'width'                  => $request['width'],
                'roll_width'             => $request['roll_width'],
                'film_no'                => $request['film_no'],
                'invoice_date'           => $invoice_date,
                'length_side'            => $request['length_side'],
                'excess_percentage'      => $request['excess'],
                'teeth_no'               => $request['teeth_no'],
                'invoice_num'            => $request['inv_no'],
                'finish_type'            => $request['finish_type'],
                'print_color'            => $request['print_color'],
                'compiled_by'            => $request['compiled_by'],
                'barcode_read'           => $request['barcode_read'],
                'operator'               => $request['operator'],
                'checker'                => $request['checker'],
                'authorized_by'          => $request['authorized_by'],
                'axicon_verifier'        => $request['axicon_verifier'],
                'approved_by'            => $request['approved_by'],
                'packer'                 => $request['packer'],
                'approval_date'          => $approval_date,
                'roll_issued_by'         => $request['roll_issued_by'],
                'cutter'                 => $request['cutter'],
                'cartons'                => $request['cartons'],
                'template_quantity_json' => $request['final_json_obj'],
                'aw_approve_date'        => $aw_app_date,
                'act_del_date'           => $act_del_date,
                'no_of_repeats'          => $request['no_repeats'],
                'is_non_artwork_template' => $is_non_artwork_template,
                $crud_type               => $current_timestamp,
                'style_number'           => $request['style_no'],
                'no_of_stickers'         => $request['no_sticker'],
                'tint_color'             => $request['tint_color'],
                'customer_po_num'        => $request['customer_po']
            ]
        );
    }


    public function updateJCDataHistory($erp_orders_ids, $uuid, $workorder_id, $current_timestamp,$buyer, $request)
    {
        // Get Erp status table id for 'Jobcard Created' status.
        $erp_status_id = DB::table('erp_status')->where('status_name', 'Jobcard Created')->value('id');
        $action_id  = DB::table('actions')->where('action', 'JC Created')->value('id');

        foreach($erp_orders_ids as $row => $erp_orders_id)
        {
            /** Get updated data and update in history table */
            ErpOrderDetails::where('erp_order_details.erp_order_id', $erp_orders_id)
            ->where('erp_order_details.uuid', $uuid[$row])
            ->update([
                'status'  => $erp_status_id,
                'jc_number' => $workorder_id,
                'jc_created_by' => Auth::guard('erpuser')->id(),
                'jc_created_date' => $current_timestamp
            ]);

            $jobcard_data = ErpOrderDetails::where('erp_order_details.erp_order_id', $erp_orders_id)
                    ->where('erp_order_details.uuid', $uuid[$row])
                    ->select('id', 'order_qnty_table_name', 'order_qnty_table_id', 'status')
                    ->get();
            
            foreach($jobcard_data as $key => $data)
            {
                if($buyer === 'orchestra'){
                    $qnty_table     = $data->order_qnty_table_name;
                    $qty_row_id     = $data->order_qnty_table_id;
                    $main_table     = str_replace("_qty","",$qnty_table);
                    $result_str     = str_replace("_"," ",str_replace("_orders","",$main_table));
                    $qnty_id        = '';
                    foreach (explode(' ', $result_str) as $word)
                    {
                        $qnty_id .= strtolower($word[0]);
                    }
                    
                    $order_details  = DB::table($qnty_table)->select($main_table.'.*')->where($qnty_table.'.id', $qty_row_id)->join($main_table, $main_table.'.id', $qnty_table.'.'.$qnty_id.'_id')
                                    ->first();
                    
                    // template front and back id storing in orchestra_hang_tag_orders table
                    $template_id = $main_table === 'orchestra_hang_tag_orders'?$order_details->template_front_id:$order_details->template_id;

                    $template_data = DB::table('orchestra_item_master')
                                    ->where('orchestra_item_master.id', $template_id)
                                    ->select('orchestra_item_master.material_id', 'orchestra_item_master.item_name', 'orchestra_item_master.sub_material_id')->first();
                    $material_name  = DB::table('orchestra_item_master')->where('id', $template_data->material_id)->value('item_name');
                    $material_sub  = DB::table('orchestra_item_master')->where('id', $template_data->sub_material_id)->value('item_name');
                    
                    $material_ref   = $template_data->item_name;
                    
                    $customer_po    = $order_details->po_number;
                    $buyer_po       = $order_details->po;
                    $style          = $order_details->uvc;
                    $ocn_number     = null;
                    $season         = $order_details->season_code;
                }  else {
                    $main_table     = str_replace("_quanty","",$data->order_qnty_table_name);
                    $qnty_id        = str_replace("_orders_quanty","_order_id",str_replace($buyer."_","",$data->order_qnty_table_name));
                    
                    $order_details  = DB::table($data->order_qnty_table_name)
                                        ->select($main_table.'.*')
                                        ->where($data->order_qnty_table_name.'.id', $data->order_qnty_table_id)
                                        ->join($main_table, $main_table.'.id', $data->order_qnty_table_name.'.'.$qnty_id)
                                        ->first();
                    
                    $nat_material_obj = new NATMaterialQuery();
                    $template_level = $nat_material_obj->nonArtworkMaterialDetails($order_details->template_id, $buyer);
                    
                    $material_name  = $template_level->material;
                    $material_sub   = $template_level->material_sub;
                    $material_ref   = $template_level->ref;

                    $season         = $order_details->season_code;
                    $customer_po    = $order_details->purchase_order_no;
                    $buyer_po       = $order_details->po_number;
                    $ocn_number     = $order_details->ocn_number;

                    if($buyer === 'tesco'){
                        $country        = $order_details->zone;
						$style_zone     = strtolower($country).'_style_ref';
                        $style          = $order_details->$style_zone;
                    } else {
                        $style          = $order_details->style_ref;
                    }
                }
                
                // it will insert if erp_order_details_id i snot exists, otherwise it will update 
                $history_id = History::updateOrCreate(['erp_order_details_id' => $data->id], [
                    'buyer_id'             => $request['buyer_id'],
                    'template'             => 'Not Available',
                    'season'               => $season,
                    'customer_id'          => $request['customer_id'],
                    'material'             => $material_name,
                    'mat_sub'              => $material_sub,
                    'reference'            => $material_ref,
                    'style'                => $style,
                    'po'                   => $customer_po,
                    'buyer_po'             => $buyer_po,
                    'ocn'                  => $ocn_number,
                    $request['crud_operation_type']  => $current_timestamp,
                    'disp_mode_id'         => $request['disp_mode']
                ]);

                DB::table('event_history')
                ->insert([
                    'history_id'  => $history_id->id,
                    'action_id'   => $action_id,
                    'created_by'  => Auth::guard('erpuser')->id(),
                    $request['crud_operation_type']  => $current_timestamp,
                ]);
                
            }

            /** Update jobcard created status erp orders table(iff, all the orders in the order details table have same status. ie, after creating jobcard for all orders) */
            if(count(array_unique($jobcard_data->pluck('status')->all())) === 1)
            {
                DB::table('erp_orders')
                    ->where('erp_orders.id', $erp_orders_id)
                    ->update([
                        'status'  => $jobcard_data->pluck('status')->all()[0]
                    ]);
            }
            
        }
    }
}
