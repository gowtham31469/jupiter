<?php

namespace App\Classes;

use DB;

use App\OrderManagementHistory;
use App\Classes\TescoMaterialQuery;

/**
 * Class to store order management history
 * @author Praveen
 */
class StoreOrderManagementHistory
{

    public function storeOrdersHistory($order_data)
    {

        $tesco_material_obj = new TescoMaterialQuery();

        $tesco_buyer_id = DB::table('buyers')
            ->select('id')
            ->where('buyer_name', 'Tesco')
            ->first();

        $template_level = $tesco_material_obj->tescoMaterialDetails($order_data['template_id'], $order_data['tesco_label']);

        switch ($order_data['po_status']) {
            case 'PO Pending':
                $po_upload_status = 0;
                break;
            case 'PO Uploaded':
                $po_upload_status = 1;
                break;
            case null:
                $po_upload_status = null;
                break;
            case 'null':
                $po_upload_status = null;
                break;
            default:
                $po_upload_status = null;
        }

        switch ($order_data['pi_status']) {
            case 'PI Approval Pending':
                $pi_approve_status = 0;
                break;
            case 'PI Rejected':
                $pi_approve_status = 0;
                break;
            case 'PI Approved':
                $pi_approve_status = 1;
                break;
            case null:
                $pi_approve_status = null;
                break;
            case 'null':
                $po_upload_status = null;
                break;
            default:
                $pi_approve_status = null;
        }

        OrderManagementHistory::updateOrCreate(
            [
                'order_table_name'  => $order_data['order_table'],
                'order_table_id'    => $order_data['order_id']
            ],
            [
                'buyer_id'                  => $tesco_buyer_id->id,
                'season'                    => $order_data['season_code'],
                'customer_id'               => $order_data['customer_id'],
                'material'                  => $template_level->material,
                'sub_material'              => $template_level->material_sub,
                'style'                     => $order_data['style'],
                'reference'                 => $template_level->ref,
                'buyer_po'                  => $order_data['po_number'],
                'ocn_number'                => $order_data['ocn_no'],
                'order_status'              => $order_data['order_status_id'],
                'pi_status'                 => $pi_approve_status,
                'po_status'                 => $po_upload_status,
                'name_in_reports'           => $template_level->name_in_reports,
                'order_edit_enabled'        => $order_data['edit_permission']
            ]
        );
    }
}
