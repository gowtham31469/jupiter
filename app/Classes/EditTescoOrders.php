<?php

namespace App\Classes;

use App\TescoSwingTicketOrders;
use DB;
use App\TescoWashcareOrders;


class EditTescoOrders
{
  public function getStickerOrderDetails($request)
  {
        $table_name =   $request->table_name;
        $erp_order_details = array();
        $order_table_array = explode("_", $table_name);
        $template_name = strtoupper($order_table_array[1]);

        $model_name  = 'App\\' . "Tesco" . $template_name . "Orders";

        $order_details  = $model_name::with([
            'quantities'
        ])
        ->where([
            'id' => $request->id
        ])
        ->first();

        $order_quantity_ids = $order_details->quantities->pluck('id');

        $tesco_item_master = DB::table('tesco_template')
        ->join('tesco_item_master', 'tesco_item_master.id', 'tesco_template.item_id')
        ->where('tesco_template.item_id', $order_details->template_id)
            ->select('tesco_template.template_file', 'tesco_item_master.is_qrcode_required', 'tesco_item_master.item_name')
            ->get();

        $moq     =    DB::table('price_taxation')
        ->where([
            'template_id' => $order_details->template_id,
            'customer_id' => $order_details->tesco_supplier_id,
            'buyer_id' => 2
        ])
            ->value('moq');

        // fetch erp_order_details table status
        $erp_order_details[] = DB::table('erp_order_details')
        ->join('erp_status', 'erp_status.id', 'erp_order_details.status')
        ->where('order_qnty_table_name', $table_name . '_quanty')
        ->whereIn('order_qnty_table_id', $order_quantity_ids)
            ->select('erp_order_details.id', 'erp_status.status_name')
            ->first();

        if ($order_details) {
            $result = response()->json(['error' => false, 'message' => 'success', 'order_details' => $order_details,  'tesco_item_master' => $tesco_item_master, 'moq' => $moq, 'erp_details' => $erp_order_details], 200);
        } else {
            $result = response()->json(['error' => true, 'message' => 'Error'], 400);
        }
        return $result;
  }

  public function getCareLabelOrderDetails($request){
        $erp_order_details = array();
        $data = [];

        $order_details  = TescoWashcareOrders::with([
            'quantities',
            'garment_components',
            'item_master'
        ])
        ->where([
            'id' => $request->id
        ])
        ->first();

        $order_quantity_ids = $order_details->quantities->pluck('id');

        $moq     =    DB::table('price_taxation')
        ->where([
            'template_id' => $order_details->template_id,
            'customer_id' => $order_details->tesco_supplier_id,
            'buyer_id' => 2
        ])
        ->value('moq');

        // fetch erp_order_details table status
        $erp_order_details[] = DB::table('erp_order_details')
        ->join('erp_status', 'erp_status.id', 'erp_order_details.status')
        ->where('order_qnty_table_name', 'tesco_washcare_orders_quanty')
        ->whereIn('order_qnty_table_id', $order_quantity_ids)
        ->select('erp_order_details.id', 'erp_status.status_name')
        ->first();


        $data['order_details'] = $order_details;
        $data['moq'] = $moq;
        $data['erp_details'] = $erp_order_details;

        return $data;
  }

  public function getSwingTicketOrderDetails($request){
    $erp_order_details = array();
    $data = [];

    $order_details  = TescoSwingTicketOrders::with([
        'quantities',
        'item_master'
    ])
    ->where([
        'id' => $request->id
    ])
    ->first();

    $order_quantity_ids = $order_details->quantities->pluck('id');

    $moq     =    DB::table('price_taxation')
    ->where([
        'template_id' => $order_details->template_id,
        'customer_id' => $order_details->tesco_supplier_id,
        'buyer_id' => 2
    ])
    ->value('moq');

    // fetch erp_order_details table status
    $erp_order_details[] = DB::table('erp_order_details')
    ->join('erp_status', 'erp_status.id', 'erp_order_details.status')
    ->where('order_qnty_table_name', 'tesco_swingticket_orders_quanty')
    ->whereIn('order_qnty_table_id', $order_quantity_ids)
    ->select('erp_order_details.id', 'erp_status.status_name')
    ->first();


    $data['order_details'] = $order_details;
    $data['moq'] = $moq;
    $data['erp_details'] = $erp_order_details;

    return $data;
}
}
