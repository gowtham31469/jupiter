<?php

namespace App\Classes;

use DB;
use Carbon\Carbon;
use App\Classes\ErpOrders;
use App\OrderManagementHistory;


/**
 * Class to update tesco customer PO files
 * @author Praveen
 */
class UploadCombinedPO
{

    public function uploadCustomerPO($request)
    {
        $erp_obj        = new ErpOrders();
        $response_status = [];
        $template_ids  = explode(',', $request['template_id']);
        $template      = explode(',', $request['templates']);

        $po_file = $request['po_file'];
        $destination_path = '/storage/app/public/po-files/';
        $path = 'storage/app/public/po-files/';
        $Orname = $po_file->getClientOriginalName();
        $filename = 'TESCO_PO_' . time() . '_' . preg_replace('/#[^\s]+/', '', preg_replace('/\s+/', '-', $Orname));
        $po_file->move($path, $filename);
        $destination_path_insert = $destination_path . $filename;
        foreach ($template_ids as $key => $template_id) {
            if ($template_id > 0) {
                //Dynamic function name and database model name for each templates
                $model_name = 'App\\' . "Tesco" . $template[$key] . "Orders";
                $order_table_name = "tesco_" . strtolower($template[$key]) . "_orders";
                $model_name::where('id', $template_id)
                ->update([
                    'po_status'         => "PO Uploaded",
                    'purchase_order_no' => $request['po_number'],
                    'po_file'           => $destination_path_insert,
                    'delivery_date'     => $request['delivery_date'],
                    'po_uploaded_at'    => Carbon::now('Asia/Kolkata')->toDateTimeString()
                ]);
                /** @var array [Pass array of values to updateTescoErpOrders function] */
                $data_array = [];
                $tesco_buyer_id = DB::table('buyers')->select('id')->where('buyer_name', '=', 'Tesco')->first();
                $data_array['tableName'] = $order_table_name;
                $data_array['buyer_id'] = $tesco_buyer_id->id;
                $data_array['id'] = $template_id;

                $response_status = $erp_obj->updateTescoErpOrders($data_array);

                // update po upload status as "PO Uploaded" in order history table
                OrderManagementHistory::where([['order_table_name', $order_table_name], ['order_table_id', $template_id]])->update([
                    'po_status'   => 1,
                    'customer_po' => $request['po_number'],
                ]);
            }
        }

        if ($response_status === 'success') {
            return response()->json(['error' => false, 'message' => 'success', 'po_number' => $request['po_number']], 200);
        } else {
            return response()->json(['error' => true, 'message' => 'Failed'], 400);
        }
    }
}
