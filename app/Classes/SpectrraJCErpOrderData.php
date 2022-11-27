<?php

namespace App\Classes;
use DB;
use App\ErpOrderDetails;

/**
 * Class to get spectrra prints erp order data
 * @author [Praveen] <[praveen@wizroots.com]>
 */
class SpectrraJCErpOrderData
{
    public function getSpectrraERPOrderData($erp_id)
    {

        return ErpOrderDetails::with('orders:id,po_no,customer_id,buyer_id,erp_id', 'company:id,name,address,logo')
        ->where([
            'erp_order_id' => $erp_id,
        ])
        ->select('erp_order_details.style_no', 'erp_order_details.po_verified_date', 'erp_order_details.order_date', 'erp_order_details.delivery_date', 'erp_order_details.erp_order_id', 'erp_order_details.uuid', 'erp_order_details.company_id', 'erp_order_details.reference_name', DB::raw('SUM(order_quantity) as total_qty'))
        ->groupBy('erp_order_details.style_no', 'erp_order_details.po_verified_date', 'erp_order_details.order_date', 'erp_order_details.delivery_date', 'erp_order_details.erp_order_id', 'erp_order_details.uuid', 'erp_order_details.company_id', 'erp_order_details.reference_name')
        ->first();
        
    }
}