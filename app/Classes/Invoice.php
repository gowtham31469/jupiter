<?php
namespace App\Classes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Customer;
use App\CustomerBillAddress;
use DB;
use Auth;
use App\Invoices;
use App\RolePermissionMdl;
use Carbon\Carbon;
use App\ErpOrders as Erp;
use App\ErpOrderDetails;

class Invoice
{
	public function setCompany()
	{
		$customer_id = Auth::guard('erpuser')->id();
            
        $companies   =  DB::table('erpuser_company_mapping')
                                ->join('company','erpuser_company_mapping.company','company.id')
                                ->where('erpuser_company_mapping.erp_user',$customer_id)
                                ->select('erpuser_company_mapping.*','company.name', 'company.logo')
                                ->get();
        
        $company_id = DB::table('company')
                                ->select('company.*')
                                ->first();
        
        
        if(session()->get('company') == "" && session()->get('company') == null)
        {
            session()->put('company', $company_id->id);
            session()->put('company_name', strtolower( str_replace(' ','',$company_id->name)) );
        }
    
        return $companies;
	}

    public function getInvoiceDetails($inv_id){
        $ddmmyy="DD/MM/YYYY";
        $inv_details = Invoices::where('invoice.id',$inv_id)
                        ->join('customer_bill_address','customer_bill_address.id','invoice.delivery_at')
                        ->join('invoice_details','invoice_details.invoice_id','invoice.id')
                        ->join('customers','invoice.customer_id','customers.id')
                        ->leftjoin('nominated_forwarders','nominated_forwarders.id','customers.nominated_forwarder')
                        ->join('buyers','invoice.buyer_id','buyers.id')
                        ->where('invoice_details.company_id',session()->get('company'))
                        ->select('invoice.invoice_id','erp_id','buyers.buyer_name','buyers.id as buyer_id','customers.company_name','customers.id as customer_id','po_no','invoice_file','invoice.id as inv_id','invoice_details.*','invoice.invoice_id','customers.place_of_supply','invoice.round_off_amnt','invoice.grand_total',DB::raw('to_char(invoice.created_at,'."'".$ddmmyy."'".') as "inv_date"'),'gstin_number','invoice.credit_limit','invoice.dispatch_thru as delvry_thru','invoice_details.ocn_no as ocn_number','invoice_details.custom_ref_name as reference_name','invoice_details.delivered_qnty as delvry_qnty','invoice.place_of_supply','customer_bill_address.address','customer_bill_address.pincode','customer_bill_address.email','customer_bill_address.telephone','invoice_details.item_name','invoice_details.hsn_code','invoice_details.buyer_po_number')->get();
        return $inv_details;
    }

    public function getShippTo($inv_details){
        $ship_to = DB::table('customer_bill_address')
                          ->where('customer_bill_address.customer_id',$inv_details[0]->customer_id)
                          ->where('customer_bill_address.is_shipping_address',1)
                          ->select('address','country')
                          ->first();
        return $ship_to;
    }

    public function getBillTo($inv_details){
        $bill_to = DB::table('customer_bill_address')
                          ->where('customer_bill_address.customer_id',$inv_details[0]->customer_id)
                          ->where('customer_bill_address.is_billing_address',1)
                          ->select('address','country')
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