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
use App\InvoiceAddress;
use Elibyy\TCPDF\Facades\TCPDF;
use App\Classes\invoiceTCPDF;
use App\EventHistory;
use App\History;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    private $current_date;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		$this->middleware('erpuser');
		// get the current time
		$this->current_date = Carbon::now('Asia/Kolkata')->toDateTimeString();

	}

    /** 
     * @return invoice list view
     */
    public function index()
    {
        
        $objInv     = new Invoice();
        $companies  = $objInv->setCompany();
        $invoices   = Invoices::join('customers','invoice.customer_id','customers.id')
                            ->join('invoice_details','invoice_details.invoice_id','invoice.id')
                            ->join('buyers','invoice.buyer_id','buyers.id')
                            ->where('invoice_details.company_id',session()->get('company'))
                            ->whereNull('invoice.eway_no')
                            ->select('invoice.invoice_id','erp_id','buyers.buyer_name','buyers.id as buyer_id','customers.company_name','customers.id as customer_id','po_no','invoice_file','invoice.id')
                            ->distinct('invoice.id')
                            ->get();
        return view('erp.Invoice.invoice-list',['invoices'=>$invoices,'companies' => $companies]);
	}

    /**
     * @return create invoice view
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
                            ->whereIn('erp_order_details.status',[6,8])
                            // ->whereIn('erp_orders.status',[6,8])
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
     * @param Request
     * @return \Illuminate\Http\Response
     */
    public function erpOrderList(Request $request)
    {
        try {
            $customer_id = Auth::guard('erpuser')->id(); 
            $erp_id      = $request->erp_id;
            $orderlist   = ErpOrderDetails::whereIn('erp_order_id',$erp_id)
                            ->join('company','erp_order_details.company_id','company.id')
                            ->join('erp_orders','erp_orders.id','erp_order_details.erp_order_id')
                            ->join('customers','erp_orders.customer_id','customers.id')
                            ->join('buyers','erp_orders.buyer_id','buyers.id')
                            ->leftjoin('freight_terms','freight_terms.id','customers.freight_type')
                            ->where('erp_order_details.pending_orders','!=' , 0)
                            ->where('erp_order_details.company_id',session()->get('company'))
                            //->whereIn('erp_orders.status',[6,8])
                            ->whereIn('erp_order_details.status',[6,8])
                            ->select('erp_order_details.id','buyers.buyer_name','buyers.id as buyer_id','customers.company_name','customers.id as customer_id','erp_orders.po_no','erp_order_details.style_no','erp_order_details.order_quantity','erp_order_details.rate','erp_order_details.size','erp_order_details.custom_ref_name as reference_name','erp_order_details.reference_name as ref_no','customers.place_of_supply','erp_order_details.ocn_number','erp_order_details.template_id','erp_orders.erp_id as erp_id','buyers.template_table','buyers.item_master_table','erp_order_details.pending_orders as pending_qnty','erp_orders.po_file','erp_orders.customer_id','customers.gstin_number','erp_orders.id as erpid','credit_limit','customers.freight_type as freight_id','freight_terms.freight_terms as delvry_thru','customers.nominated_forwarder','erp_order_details.buyer_po_number',DB::raw("0".' as "delvry_qnty"'))
							->orderBy('erp_order_details.id', 'asc')
                            ->get();
            if ($orderlist[0]->freight_id == 3) {
                $orderlist[0]->delvry_thru  = DB::table('nominated_forwarders')->where('id',$orderlist[0]->nominated_forwarder)->value('forwarder');       
            }               
            $company_details =  DB::table('company')
                                ->where('id',session()->get('company'))
                                ->select('company.*')->first();
            $bill_to = DB::table('customer_bill_address')
                          ->where('customer_bill_address.customer_id',$orderlist[0]->customer_id)
                          ->where('customer_bill_address.is_billing_address',1)
                          ->select('*')
                          ->first();                 
            $ship_to = DB::table('customer_bill_address')
                          ->where('customer_bill_address.customer_id',$orderlist[0]->customer_id)
                          ->where('customer_bill_address.is_shipping_address',1)
                          ->select('*')
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
	
	public function setInvoiceCustRefName()
	{
		
		 $erp_order_details = DB::table('erp_order_details')
                          ->select('*')
                           ->get();    
		foreach($erp_order_details as $value)
		{
			 DB::table('erp_order_details')->where('id', $value->id)->update(['custom_ref_name'=> $value->reference_name ]);
		}
		
		
	}

    /** 
     * method to set the invoice details of selected orders of an ERP-ID 
     * @param Request
     * @return \Illuminate\Http\Response
     */
    public function setInvoice(Request $request)
    {

        ini_set('max_input_vars', 5000);
        try 
        {
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
            ini_set('max_execution_time', 1000);
            $bill_to = DB::table('customer_bill_address')
                          ->where('customer_bill_address.customer_id',$orders[0]['customer_id'])
                          ->where('customer_bill_address.is_billing_address',1)
                          ->select('*')
                           ->first();                 
            $ship_to = DB::table('customer_bill_address')
                          ->where('customer_bill_address.customer_id',$orders[0]['customer_id'])
                          ->where('customer_bill_address.is_shipping_address',1)
                          ->select('*')
                          ->first();
                      
            if($bill_to->id == $ship_to->id){
                $invAdd     =   InvoiceAddress::create([
                                    'customer_id'  => $bill_to->customer_id,
                                    'address'      => $bill_to->address,
                                    'country'      => $bill_to->country,
                                    'email'        => $bill_to->email,
                                    'telephone'    => $bill_to->telephone,
                                    'fax'          => $bill_to->fax,
                                    'pincode'      => $bill_to->pincode,
                                ]);
                $delivery_at =  $invAdd->id;
                $billTo_id      =  $invAdd->id;

            }
            else{
                $billAdd   =   InvoiceAddress::create([
                                    'customer_id'  => $bill_to->customer_id,
                                    'address'      => $bill_to->address,
                                    'country'      => $bill_to->country,
                                    'email'        => $bill_to->email,
                                    'telephone'    => $bill_to->telephone,
                                    'fax'          => $bill_to->fax,
                                    'pincode'      => $bill_to->pincode,
                                 ]);
                $deliveryAdd =   InvoiceAddress::create([
                                    'customer_id'  => $ship_to->customer_id,
                                    'address'      => $ship_to->address,
                                    'country'      => $ship_to->country,
                                    'email'        => $ship_to->email,
                                    'telephone'    => $ship_to->telephone,
                                    'fax'          => $ship_to->fax,
                                    'pincode'      => $ship_to->pincode,
                                 ]); 
                $delivery_at =  $deliveryAdd->id;
                $billTo_id    =  $billAdd->id;   
            }            

            $company_details =  DB::table('company')
                                ->where('id',session()->get('company'))
                                ->first();         

            if ($orders) {
                // dd($request->base_amt);
                $invoiceData = Invoices::create([
                                'customer_id'    => $orders[0]['customer_id'],
                                'buyer_id'       => $orders[0]['buyer_id'],
                                'po_no'          => $orders[0]['po_no'],
                                'po_file'        => $orders[0]['po_file'],
                                'erp_id'         => $orders[0]['erp_id'],
                                'credit_limit'   => $orders[0]['credit_limit'],
                                'dispatch_thru'  => $orders[0]['delvry_thru'],
                                'round_off_amnt' =>  $roundOff,
                                'grand_total'    =>  $grandTotal,
                                'place_of_supply'=>  $place_of_supply,
                                'delivery_at'    =>  $delivery_at,
                                'bill_to'        =>  $billTo_id,
                                'base_amnt'       =>  $request->base_amt
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
                $par_count = 0;
                $ful_count = 0;

                Invoices::where('id', $invoiceData->id)->update(['invoice_id'=> $invoice_id ]);
                foreach ($orders as $key => $value) {
                    $pendingQnty = $value['pending_qnty'] - $value['delvry_qnty'];
                    $size = $value['size'] == ""?"null":$value['size'];
                    $hsn_code = $value['hsn_code']==""?0: $value['hsn_code'];
                    $status = $pendingQnty==0?5:6;
                    if ($status==5) {
                        $par_count++;
                    }
                    else{
                        $ful_count++;
                    }                       
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
                        'size'          =>  $size,                        
                        'reference_no'  =>  $value['ref_no'],
                        'custom_ref_name'  =>  $value['reference_name'],
                        'ocn_no'        =>  $value['ocn_number'] ? $value['ocn_number'] : '-', 
                        'item_name'     =>  $value['item_name'], 
                        'item'          =>  $value['item_name'], 
                        'buyer_po_number' =>  $value['buyer_po_number'],          
                        'company_id'    =>  session()->get('company'),
                    ]);
                    // dd(/$a);
                    ErpOrderDetails::where('id',$value['id'])
                        ->update([
                            'delivered_quantity'   => ( $value['order_quantity'] - $pendingQnty ),
                            'pending_orders'       => $pendingQnty,
                            'status'               => $status,
                            'invoice_number'       => $invoice_id,
                            'invoice_created_by'   => Auth::guard('erpuser')->id(),
                            'invoice_created_date' => $this->current_date
                            ]);                  

                    ErpOrdersInvoice::create([
                        'invoice_id'=>$invoiceData->id,
                        'erp_order_id'=>$orders[0]['erpid']
                    ]);


                $historyId = History::where('erp_order_details_id',ErpOrderDetails::where('erp_order_id',$orders[0]['erpid'])->first()->id)->first()->id;

                $invAction = EventHistory::create([
                'action_id'    => $place_of_supply == 1 ? 14 : 15 ,
                'history_id'   => $historyId,
                'created_by'   => Auth::guard('erpuser')->id(),
                ]);


                }
                $erpStatus = ($par_count !=0 && $ful_count!=0) ? 6:$status;
                Erp::where('erp_id',$orders[0]['erp_id'])
                        ->update(['status'=>$erpStatus]);
                $result    = response()->json(['error'=> false,'message' =>'success'],200);
            }
            
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

    /**
     * method to get the details of a particular Invoice ID
     * @param  Request
     * @return \Illuminate\Http\Response
     */
    public function getInvoiceDetails(Request $request)
    {
        try
        {
            $inv_id   = $request->invoice_id;
            // $invoice_details  = DB::table('invoice')
            //                     ->join('cu')
            //                     ->where('invoice_id',$inv_id)->get();

            $invoice_details  =      Invoices::join('customers','invoice.customer_id','customers.id')
                                // ->join('invoice_details','invoice_details.invoice_id','invoice.id')
                                ->join('buyers','invoice.buyer_id','buyers.id')
                                // ->where('invoice_details.company_id',session()->get('company'))
                                ->where('invoice_id',$inv_id)
                                ->select('invoice.invoice_id','erp_id','buyers.buyer_name','buyers.id as buyer_id','customers.company_name','customers.id as customer_id','po_no','invoice_file','invoice.id')
                                ->distinct('invoice.id')
                                ->get();
            if ($invoice_details) {
                $result    = response()->json(['error'=> false,'message' =>'success','invoice_details'=>$invoice_details],200);
            }
            else{
                $result    = response()->json(['error'=> true,'message' =>'Error'],400);
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

            $extra_charges  = InvExtraCharges::where('invoice_id',$inv_id)->get();

            $company_details =  DB::table('company')
                                ->where('id',session()->get('company'))
                                ->select('company.*')->first();
            
            $result    = response()->json(['error'=> false,'message' =>'success','inv_details'=>$inv_details,'extra_charges'=>$extra_charges ,'company_details'=>$company_details, 'bill_to'=>$bill_to,'ship_to'=>$ship_to],200);
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }
    
    /**
     * method to update the e-way bill number for an invoice ID
     * @param  Request [eway bill number]
     * @return \Illuminate\Http\Response
     */
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
            // define ('PDF_FONT_NAME_MAIN', 'helvetica');
            // define ('PDF_FONT_NAME_DATA', 'helvetica');
            $pdf = new invoiceTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->setCompanyData($company_details);
            //margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
            // set header and footer fonts
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_TOP);

            // set image scale factor
            $pdf->setImageScale(0.8);

            // set font
            $pdf->SetFont('helvetica', '', 8);

            // add a page
            $pdf->AddPage('P', 'A4');
            $pdf->SetXY(5.3, 24);
            $pdf->writeHTML($dynView->render());
            // PDF::SetPrintHeader(false);
            // PDF::SetPrintFooter(false);
            // PDF::AddPage();
            // PDF::writeHTML($dynView->render());
            $pdfdata =$pdf->output( public_path('storage/app/public/invoices/invoices_'.$inv_id.'.pdf'), 'F');

            Invoices::where('id', $inv_id)->update(['eway_no'=> $eway_no ,'invoice_file'=>'/storage/app/public/invoices/invoices_'.$inv_id.'.pdf']);
            $attachment =  public_path('storage/app/public/invoices/invoices_'.$inv_id.'.pdf');
            //Mail notiication
            // $useremail = "aswathy@wizroots.com";
            $useremail = $inv_details[0]->email;
            Mail::send('mails.invoice', ['title' => 'Invoice','to_email' => $useremail], function ($message) use ($useremail,$attachment)
                    {
                        $message->from('admin@jga.com', 'JGA');
                        $message->subject('Invoice');
                        $message->attach($attachment);
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

    /**
     * method to generate PDF view of customer invoice
     * @param  Request [invoice details]
     * @return pdf file path
     */
    public function downloadInvoice(Request $request)
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
                $pdf = new invoiceTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                $pdf->setCompanyData($company_details);
                //margins
                $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                // set header and footer fonts
                $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
                // set auto page breaks
                $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_TOP);

                // set image scale factor
                $pdf->setImageScale(0.8);

                // set font
                $pdf->SetFont('helvetica', '', 8);

                // add a page
                // $pdf->AddPage('P', 'A4');
                // $pdf->SetXY(5.3, 24);
                // for ($i=0; $i <3 ; $i++) { 
                //     $pdf->AddPage('P', 'A4');
                //     $pdf->SetXY(5.3, 24);
                //      // $pdf->Cell(10, -20, 'Customer Copy ', 0, false, 'C', 0, '', 0, false, 'T', 'M');
                //     $pdf->writeHTML($dynView->render());
                    
                // }

                $invoice = array(1, 2, 3);
                foreach($invoice as $value){
                    switch($value){
                         case 1: 
                            $copy="text for invoice 1";
                            $pdf->AddPage('P', 'A4');
                            $pdf->SetXY(5.3, 24);
                            // $pdf->Cell(10, -20, $copy, 0, false, 'C', 0, '', 0, false, 'T', 'M');
                            $pdf->writeHTML($dynView->render()); 
                         break;
                         case 2: 
                            $copy="text for invoice 2";
                            $pdf->AddPage('P', 'A4');
                            $pdf->SetXY(5.3, 24);
                            // $pdf->Cell(10, -20, $copy, 0, false, 'C', 0, '', 0, false, 'T', 'M');
                            $pdf->writeHTML($dynView->render());  
                         break;
                         case 3: 
                            $copy="text for invoice 3";
                            $pdf->AddPage('P', 'A4');
                            $pdf->SetXY(5.3, 24);
                            // $pdf->Cell(10, -20,$copy, 0, false, 'C', 0, '', 0, false, 'T', 'M');
                            $pdf->writeHTML($dynView->render());         
                         break;      
                    }
                }
     
            $pdfdata =$pdf->output( public_path('storage/app/public/invoices/invoices_'.$inv_id.'.pdf'), 'F');

            $attachment =  public_path('storage/app/public/invoices/invoices_'.$inv_id.'.pdf');
            return "/storage/app/public/invoices/invoices_".$inv_id.".pdf";
        } 
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

}