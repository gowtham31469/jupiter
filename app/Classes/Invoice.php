<?php
namespace App\Classes;
use DB;
use Auth;
use App\Invoices;

class Invoice
{
    public function getInvoiceDetails($inv_id){
        $ddmmyy="DD/MM/YYYY";
        return Invoices::where('invoice.id',$inv_id)
                        // ->join('customer_bill_address','customer_bill_address.id','invoice.delivery_at')
                        ->join('invoice_details','invoice_details.invoice_id','invoice.id')
                        ->join('customers','invoice.customer_id','customers.id')
                        ->leftjoin('nominated_forwarders','nominated_forwarders.id','customers.nominated_forwarder')
                        ->join('buyers','invoice.buyer_id','buyers.id')
                        ->where('invoice_details.company_id',session()->get('company'))
                        ->select('invoice.invoice_id','erp_id','buyers.buyer_name','buyers.id as buyer_id','customers.company_name','customers.id as customer_id','customers.email as customer_email','po_no','invoice_file','invoice.id as inv_id','invoice_details.*','invoice.invoice_id','invoice.round_off_amnt','invoice.grand_total',DB::raw('to_char(invoice.created_at,'."'".$ddmmyy."'".') as "inv_date"'),'gstin_number','invoice.credit_limit','invoice.dispatch_thru as delvry_thru','invoice_details.ocn_no as ocn_number','invoice_details.custom_ref_name as reference_name','invoice_details.delivered_qnty as delvry_qnty','invoice.place_of_supply','invoice_details.item_name','invoice_details.hsn_code','invoice_details.buyer_po_number','invoice.delivery_at','invoice.bill_to')->get();

        // $invoice_details =
        // Invoices::with(
        //     [
        //         'invoiceDetails',
        //         'customer:id,email,company_name',
        //         'buyer:id,buyer_name'
        //     ]
        // )
        // ->where('invoice.id',$inv_id)
        // ->whereHas('invoiceDetails', function ($query)
        // {
        //     $query->where('invoice_details.company_id', session()->get('company'));
        // })
        // ->whereHas('customer', function ($query)
        // {
        //     $query->leftjoin('nominated_forwarders','nominated_forwarders.id','customers.nominated_forwarder');
        // })
        // ->get();   
                        
        // return $invoice_details;
    }

    public function getShippTo($inv_details){
        $ship_to = DB::table('invoice_address')
                          ->where('invoice_address.customer_id',$inv_details[0]->customer_id)
                          ->where('invoice_address.id',$inv_details[0]->delivery_at)
                          ->select('*')
                          ->first();
        return $ship_to;
    }

    public function getBillTo($inv_details){
        $bill_to = DB::table('invoice_address')
                          ->where('invoice_address.customer_id',$inv_details[0]->customer_id)
                          ->where('invoice_address.id',$inv_details[0]->bill_to)
                          ->select('*')
                          ->first();
        return $bill_to;
    }

    public function getTaxDetails($inv_details){
        $uniqueTaxes = array();
        foreach($inv_details as $array)
        {
            if(!in_array($array['tax'], $uniqueTaxes))
                $uniqueTaxes[] = $array['tax'];
        }
        
        $txArr = array();
        foreach ($uniqueTaxes as $value) {
            $taxSum  = 0;
           foreach ($inv_details as $vals) {
                if ($vals->tax==$value) {
                    $taxSum +=($vals->rate*$vals->delvry_qnty);
                }
            } 
            $txArr[$value] = $taxSum ;
        }
        return $txArr;
    }
}