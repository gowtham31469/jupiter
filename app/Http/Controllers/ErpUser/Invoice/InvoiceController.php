<?php
namespace App\Http\Controllers\ErpUser\Invoice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Response;
use View;
use DB;
use Auth;
use Mail;
use App\ErpOrders as Erp;
use App\ErpOrderDetails;
use App\ErpOrdersInvoice; 
use App\Invoices;
use App\InvoiceDetails;
use App\InvExtraCharges;
use PDF;
use App\Classes\Invoice;
use Elibyy\TCPDF\Facades\TCPDF;

class InvoiceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('erpuser');
   
    }

    /**
     * Show the application dashboard.
     * @author [Praveen] <[praveen@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $objInv     = new Invoice();
        $companies  = $objInv->setCompany();
        $invoices   = Invoices::join('customers','invoice.customer_id','customers.id')
                            ->join('invoice_details','invoice_details.invoice_id','invoice.id')
                            ->join('buyers','invoice.buyer_id','buyers.id')
                            ->where('invoice_details.company_id',session()->get('company'))
                            ->select('invoice.invoice_id','erp_id','buyers.buyer_name','buyers.id as buyer_id','customers.company_name','customers.id as customer_id','po_no','invoice_file','invoice.id')
                            ->distinct('invoice.id')
                            ->get();
        return view('erp.Invoice.invoice-list',['invoices'=>$invoices,'companies' => $companies]);
	}
     /**
     * Show the application dashboard.
     * @author [Praveen] <[praveen@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $objInv     = new Invoice();
        $companies  = $objInv->setCompany();
        $customers  = DB::table('customers')
                              ->where('is_active','=',1)
                              ->get();
        $invID =   "INV-00".(Invoices::max('id')+1); 

        return view('erp.Invoice.create-invoice',['customers' => $customers,'invID'=>$invID,'companies' => $companies]);
    }

    /**
     * method to get the PO list for a particular customer
     * @param Request $request [description]
     */
    public function erpPOList(Request $request)
    {
        try {
            $customer_id = $request->customer_id;
            $po_list   = Erp::where('customer_id',$customer_id)
                            ->join('erp_order_details','erp_order_details.erp_order_id','erp_orders.id')
                            ->whereIn('erp_orders.status',[3,6])
                            // ->where('erp_orders.status','!=',5)
                            ->where('erp_order_details.company_id',session()->get('company'))
                            ->join('customers','erp_orders.customer_id','customers.id')
                            ->join('buyers','erp_orders.buyer_id','buyers.id')
                            ->select('erp_orders.id','buyers.buyer_name','buyers.id as buyer_id','customers.company_name','customers.id as customer_id','erp_orders.po_no','erp_orders.erp_id')
                            ->distinct('erp_orders.id')->get();
            $address_list = DB::table('customer_bill_address')->where('customer_id',$customer_id)->where('is_shipping_address',1)->get();
                            
            $result      = response()->json(['error'=> false,'message' =>'success','po_list'=>$po_list ,'address_list'=> $address_list],200);
        }
        catch (\Exception $e){
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

    /**
     * method to get the orders list for an ERP ID
     * @param Request $request [description]
     */
    public function erpOrderList(Request $request)
    {
        try {
            $customer_id = Auth::guard('erpuser')->id(); 
            $erp_id      = $request->erp_id;
            $orderlist   = ErpOrderDetails::where('erp_order_id',$erp_id)
                            ->join('company','erp_order_details.company_id','company.id')
                            ->join('erp_orders','erp_orders.id','erp_order_details.erp_order_id')
                            ->join('customers','erp_orders.customer_id','customers.id')
                            ->join('buyers','erp_orders.buyer_id','buyers.id')
                            ->leftjoin('freight_terms','freight_terms.id','customers.freight_type')
                            ->where('erp_order_details.pending_orders','!=' , 0)
                            ->where('erp_order_details.company_id',session()->get('company'))
                            ->whereIn('erp_orders.status',[3,6])
                            ->whereIn('erp_order_details.status',[3,6])
                            // ->where('erp_orders.status','!=',5)
                            ->select('erp_order_details.id','buyers.buyer_name','buyers.id as buyer_id','customers.company_name','customers.id as customer_id','erp_orders.po_no','erp_order_details.style_no','erp_order_details.order_quantity','erp_order_details.rate','erp_order_details.size','erp_order_details.custom_ref_name as reference_name','erp_order_details.reference_name as ref_no','customers.place_of_supply','erp_order_details.ocn_number','erp_order_details.template_id','erp_orders.erp_id as erp_id','buyers.template_table','buyers.item_master_table','erp_order_details.pending_orders as pending_qnty','erp_orders.po_file','erp_orders.customer_id','customers.gstin_number','erp_orders.id as erpid','credit_limit','customers.freight_type as freight_id','freight_terms.freight_terms as delvry_thru','customers.nominated_forwarder',DB::raw("0".' as "delvry_qnty"'))
                            ->get();
            if ($orderlist[0]->freight_id == 3) {
                $orderlist[0]->delvry_thru  = DB::table('nominated_forwarder')->where('id',$orderlist[0]->nominated_forwarder)->value('forwarder');       
            }               
            $company_details =  DB::table('company')
                                ->where('id',session()->get('company'))
                                ->select('company.*')->first();
            $bill_to = DB::table('customer_bill_address')
                          ->where('customer_bill_address.customer_id',$orderlist[0]->customer_id)
                          ->where('customer_bill_address.is_billing_address',1)
                          ->select('address','country')
                          ->first();                 
            $ship_to = DB::table('customer_bill_address')
                          ->where('customer_bill_address.customer_id',$orderlist[0]->customer_id)
                          ->where('customer_bill_address.is_shipping_address',1)
                          ->select('address','country')
                          ->first();

            foreach ($orderlist as $key => $value){
               $details = DB::table($value->template_table)
                            ->where($value->template_table.'.item_id',$value->template_id)
                            ->select($value->template_table.'.hsn_code',$value->template_table.'.tax','hierarchy_id')->first();                     
                $value->hsn_code = $details->hsn_code;
                $value->tax = $details->tax;
                $value->item_name =  DB::table($value->item_master_table)->where('id',$details->hierarchy_id)->value('item_name');
            }
            $result      = response()->json(['error'=> false,'message' =>'success','orderlist'=>$orderlist,'bill_to'=>$bill_to,'ship_to'=>$ship_to,'company_details'=>$company_details],200);
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

    public function setInvoice(Request $request){
        try {
            $orders        =  $request['finalData'];
            $htmlData      =  $request->htmlData;
            $tax_amnt      =  $request->tax_amnt;
            $ewayNo        =  $request->ewayNo; 
            $creditTerms   =  $request->creditTerms;
            $taxTotal      = $request->taxTotal;
            $place_of_supply = $request->place_of_supply;
            $roundOff      = $request->roundOff;
            $amntInwords   = $request->amntInwords;
            $grandTotal    = $request->grandTotal;
            $invoiceNo     = $request->invoiceNo;
            $invoiceDate   = $request->invoiceDate;
            $delivery_at   = $request->delivery_at;
            $eChargesArr   = $request['eCharges'];
            
            $bill_to = DB::table('customer_bill_address')
                          ->where('customer_bill_address.customer_id',$orders[0]['customer_id'])
                          ->where('customer_bill_address.is_billing_address',1)
                          ->select('address','country')
                           ->first();                 
            $ship_to = DB::table('customer_bill_address')
                          ->where('customer_bill_address.customer_id',$orders[0]['customer_id'])
                          ->where('customer_bill_address.is_shipping_address',1)
                          ->select('address','country')
                          ->first();

            $company_details =  DB::table('company')
                                ->where('id',session()->get('company'))
                                ->first();
                        
            // $dynView = View::make('template')
            //     ->with(['company_details' => $company_details, 'arr' => $orders, 'ship_to'=>$ship_to,'bill_to'=>$bill_to ,'tax_amnt'=>$tax_amnt ,'place_of_supply'=>$place_of_supply ,'roundOff'=>$roundOff,'taxTotal'=>$taxTotal, 'creditTerms'=>$creditTerms,'amntInwords'=>$amntInwords,'ewayNo'=>$ewayNo,'grandTotal'=>$grandTotal,'invoiceNo'=>$invoiceNo,'invoiceDate'=>$invoiceDate ,'eChargesArr'=>$eChargesArr]);            

            if ($orders) {
                $invoiceData = Invoices::create([
                                'customer_id'   => $orders[0]['customer_id'],
                                'buyer_id'      => $orders[0]['buyer_id'],
                                'po_no'         => $orders[0]['po_no'],
                                'po_file'       => $orders[0]['po_file'],
                                'erp_id'        => $orders[0]['erp_id'],
                                'credit_limit'  => $orders[0]['credit_limit'],
                                'dispatch_thru' => $orders[0]['delvry_thru'],
                                'round_off_amnt'=>  $roundOff,
                                'grand_total'   =>  $grandTotal,
                                'place_of_supply' =>  $place_of_supply,
                                'delivery_at'    =>  $delivery_at,
                            ]);
                
                $invoice_id =  "INV-00".$invoiceData->id;
                if ($eChargesArr) {
                    foreach ($eChargesArr as $objts) {
                        foreach ($objts as $key => $vals) {
                            InvExtraCharges::create([
                                'invoice_id' => $invoiceData->id,
                                'ext_chrg_name' => $key,
                                'amount' => $vals
                            ]);
                        }
                    }
                }
                // ob_start();
                // PDF::SetPrintHeader(false);
                // PDF::SetPrintFooter(false);
                // PDF::AddPage();
                // PDF::writeHTML($dynView->render());
                // $pdfdata = PDF::output( public_path('storage/app/public/invoices/invoices_'.$invoice_id.'.pdf'), 'F');
                // Invoices::where('id', $invoiceData->id)->update(['invoice_id'=> $invoice_id ,'invoice_file'=>'/storage/app/public/invoices/invoices_'.$invoice_id.'.pdf']);

                Invoices::where('id', $invoiceData->id)->update(['invoice_id'=> $invoice_id ]);
                foreach ($orders as $key => $value) {
                    $pendingQnty = $value['pending_qnty'] - $value['delvry_qnty'];
                    $size = $value['size'] == ""?"null":$value['size'];
                    $hsn_code = $value['hsn_code']==""?0: $value['hsn_code'];
                    $status = $pendingQnty==0?5:6;
                    InvoiceDetails::create([
                        'invoice_id'    =>  $invoiceData->id,
                        'order_no'      =>  $value['id'],
                        'style_no'      =>  $value['style_no'],
                        'ordered_qnty'  =>  $value['order_quantity'],
                        'delivered_qnty'=>  $value['delvry_qnty'],
                        'pending_qnty'  =>  $pendingQnty,
                        'rate'          =>  $value['rate'],
                        'tax'           =>  $value['tax'],
                        'hsn_code'      =>  $hsn_code,
                        // 'hsn_code'      =>  2,
                        'size'          =>  $size,                        
                        'reference_no'  =>  $value['ref_no'],
                        'custom_ref_name'  =>  $value['reference_name'],
                        'ocn_no'        =>  $value['ocn_number'], 
                        'item_name'     =>  $value['item_name'], 
                        'item'     =>  $value['item_name'],               
                        'company_id'    =>  1,
                    ]);
                    
                    ErpOrderDetails::where('id',$value['id'])
                        ->update([
                            'delivered_quantity' => ( $value['order_quantity'] - $pendingQnty ),
                            'pending_orders'     => $pendingQnty,
                            'status'             => $status
                            ]);
                    Erp::where('erp_id',$orders[0]['erp_id'])
                        ->update(['status'=>$status]);

                  ErpOrdersInvoice::create([
                    'invoice_id'=>$invoiceData->id,
                    'erp_order_id'=>$orders[0]['erpid']
                    ]);
                }
                $attatchment =  public_path('storage/app/public/invoices/invoices_'.$invoice_id.'.pdf');
                //Mail notiication
                $useremail = "aswathy@wizroots.com";
                // $useremail = Auth::guard('erpuser')->email();
                // Mail::send('mails.invoice', ['title' => 'Invoice','to_email' => $useremail], function ($message) use ($useremail,$attatchment)
                //         {
                //             $message->from('admin@jga.com', 'JGA');
                //             $message->subject('Invoice');
                //             $message->attach($attatchment);
                //             $message->to($useremail);
                //         });
                $result    = response()->json(['error'=> false,'message' =>'success'],200);
            }
            
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }


    public function getInvoice(Request $request)
    {
        try {
            $objInv     = new Invoice();
            $inv_id      = $request->id;
            $ddmmyy="DD/MM/YYYY";
            $inv_details = $objInv->getInvoiceDetails($inv_id);
            $ship_to = $objInv->getShippTo($inv_details);
            $bill_to = $objInv->getBillTo($inv_details);

            // $inv_details = Invoices::where('invoice.id',$inv_id)
            //                 ->join('invoice_details','invoice_details.invoice_id','invoice.id')
            //                 ->join('customers','invoice.customer_id','customers.id')
            //                 ->leftjoin('nominated_forwarders','nominated_forwarders.id','customers.nominated_forwarder')
            //                 ->join('buyers','invoice.buyer_id','buyers.id')
            //                 ->where('invoice_details.company_id',session()->get('company'))
            //                 ->select('invoice.invoice_id','erp_id','buyers.buyer_name','buyers.id as buyer_id','customers.company_name','customers.id as customer_id','po_no','invoice_file','invoice.id as inv_id','invoice_details.*','invoice.invoice_id','customers.place_of_supply','invoice.round_off_amnt','invoice.grand_total',DB::raw('to_char(invoice.created_at,'."'".$ddmmyy."'".') as "inv_date"'),'gstin_number','invoice.credit_limit','invoice.dispatch_thru as delvry_thru')->get();
            $extra_charges  = InvExtraCharges::where('invoice_id',$inv_id)->get();

            $company_details =  DB::table('company')
                                ->where('id',session()->get('company'))
                                ->select('company.*')->first();
            // $bill_to = DB::table('customer_bill_address')
            //               ->where('customer_bill_address.customer_id',$inv_details[0]->customer_id)
            //               ->where('customer_bill_address.is_billing_address',1)
            //               ->select('address','country')
            //               ->first();                 
            // $ship_to = DB::table('customer_bill_address')
            //               ->where('customer_bill_address.customer_id',$inv_details[0]->customer_id)
            //               ->where('customer_bill_address.is_shipping_address',1)
            //               ->select('address','country')
            //               ->first();
            
            $result    = response()->json(['error'=> false,'message' =>'success','inv_details'=>$inv_details,'extra_charges'=>$extra_charges ,'company_details'=>$company_details, 'bill_to'=>$bill_to,'ship_to'=>$ship_to],200);
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

    public function updateInvoice(Request $request)
    {
        try{
            $objInv      = new Invoice();
            $inv_id      = $request->inv_id;
            $amntInwords = $request->amntInwords;
            $eway_no     = $request->eway_no;
            $inv_details = $objInv->getInvoiceDetails($inv_id);
            $ship_to     = $objInv->getShippTo($inv_details);
            $bill_to     = $objInv->getBillTo($inv_details);
            $company_details =  DB::table('company')
                                ->where('id',session()->get('company'))
                                ->first();
            $extra_charges  = InvExtraCharges::where('invoice_id',$inv_id)->get();
            $uniqueTaxes    = $objInv->getTaxDetails($inv_details);
            
            
            $dynView = View::make('template')
                ->with(['company_details' => $company_details, 'inv_details' => $inv_details, 'ship_to'=>$ship_to,'bill_to'=>$bill_to,'inv_details'=>$inv_details,'eway_no'=> $eway_no,'amntInwords'=>$amntInwords , 'eChargesArr'=>$extra_charges,'uniqueTaxes'=>$uniqueTaxes]);
            ob_start();
            define ('PDF_FONT_NAME_MAIN', 'helvetica');
            define ('PDF_FONT_NAME_DATA', 'helvetica');
            PDF::SetPrintHeader(false);
            PDF::SetPrintFooter(false);
            PDF::AddPage();
            PDF::writeHTML($dynView->render());
            $pdfdata = PDF::output( public_path('storage/app/public/invoices/invoices_'.$inv_id.'.pdf'), 'F');
            Invoices::where('id', $inv_id)->update(['eway_no'=> $eway_no ,'invoice_file'=>'/storage/app/public/invoices/invoices_'.$inv_id.'.pdf']);
            $attatchment =  public_path('storage/app/public/invoices/invoices_'.$inv_id.'.pdf');
            //Mail notiication
            $useremail = "aswathy@wizroots.com";
            // $useremail = Auth::guard('erpuser')->user()->email;
            Mail::send('mails.invoice', ['title' => 'Invoice','to_email' => $useremail], function ($message) use ($useremail,$attatchment)
                    {
                        $message->from('admin@jga.com', 'JGA');
                        $message->subject('Invoice');
                        $message->attach($attatchment);
                        $message->to($useremail);
                    });

            $result    = response()->json(['error'=> false,'message' =>'success'],200);
        } 
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

}