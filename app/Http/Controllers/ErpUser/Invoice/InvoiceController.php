<?php
namespace App\Http\Controllers\ErpUser\Invoice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Response;
use View;
use DB;
use App\ErpOrders as Erp;
use App\ErpOrderDetails;
use App\Invoices;
use App\InvoiceDetails;
use PDF;
use App\Classes\Invoice;

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
                            ->select('invoice.invoice_id','erp_id','buyers.buyer_name','buyers.id as buyer_id','customers.company_name','customers.id as customer_id','po_no')
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
                            ->where('erp_orders.status','!=',5)
                            ->where('erp_order_details.company_id',session()->get('company'))
                            ->join('customers','erp_orders.customer_id','customers.id')
                            ->join('buyers','erp_orders.buyer_id','buyers.id')
                            ->select('erp_orders.id','buyers.buyer_name','buyers.id as buyer_id','customers.company_name','customers.id as customer_id','erp_orders.po_no','erp_orders.erp_id')
                            ->distinct('erp_orders.id')->get();
                            
            $result      = response()->json(['error'=> false,'message' =>'success','po_list'=>$po_list ],200);
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
            $erp_id     = $request->erp_id;
            $orderlist  = ErpOrderDetails::where('erp_order_id',$erp_id)
                            ->join('company','erp_order_details.company_id','company.id')
                            ->join('erp_orders','erp_orders.id','erp_order_details.erp_order_id')
                            ->join('customers','erp_orders.customer_id','customers.id')
                            ->join('buyers','erp_orders.buyer_id','buyers.id')
                            ->where('erp_order_details.pending_orders','!=' , 0)
                            ->where('erp_order_details.company_id',session()->get('company'))
                            ->select('erp_order_details.id','buyers.buyer_name','buyers.id as buyer_id','customers.company_name','customers.id as customer_id','erp_orders.po_no','erp_order_details.style_no','erp_order_details.order_quantity','erp_order_details.rate','erp_order_details.size','erp_order_details.reference_name','customers.place_of_supply','erp_order_details.ocn_number','erp_order_details.template_id','erp_orders.erp_id as erp_id','buyers.template_table','buyers.item_master_table','erp_order_details.pending_orders as pending_qnty','erp_orders.po_file','erp_orders.customer_id','customers.gstin_number')
                            ->get();
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
                            ->select($value->template_table.'.hsn_code',$value->template_table.'.tax')->first();
                $value->hsn_code = $details->hsn_code;
                $value->tax = $details->tax;
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
            $orders   =  $request['finalData'];
            $htmlData =  $request->htmlData;
            // $pdf = html2pdf($htmlData);
            // echo file_put_contents("/storage/app/public/invoices/invoice",$htmlData);

            // $html2pdf = new \HTML2PDF('P', 'Legal', 'en', true, 'UTF-8', array(25.4, 20.4, 25.4, 20.4));
            // $html2pdf->pdf->SetTitle('HTML2PDF Sample');
            // $html2pdf->WriteHTML($content);
            // $html2pdf->Output('example.pdf');
            
            $tbl=utf8_encode($htmlData);
            PDF::writeHTML($tbl, true, false, false, false, '');
            PDF::Output( public_path('/storage/app/public/invoices/invoice.pdf'), 'F');
            // if ($orders) {
            //     $invoiceData = Invoices::create([
            //                     'customer_id' => $orders[0]['customer_id'],
            //                     'buyer_id'    => $orders[0]['buyer_id'],
            //                     'po_no'       => $orders[0]['po_no'],
            //                     'po_file'     => $orders[0]['po_file'],
            //                     'erp_id'      => $orders[0]['erp_id'],
            //                 ]);
            //     $invoice_id =  "INV-00".$invoiceData->id;
            //     Invoices::where('id', $invoiceData->id)->update(['invoice_id'=> $invoice_id ]);

            //     foreach ($orders as $key => $value) {

            //         $pendingQnty = $value['pending_qnty'] - $value['delvry_qnty'];
            //         $size = $value['size'] == ""?"null":$value['size'];
            //         $status = $pendingQnty==0?5:6;
            //         InvoiceDetails::create([
            //             'invoice_id'    =>  $invoiceData->id,
            //             'order_no'      =>  $value['id'],
            //             'style_no'      =>  $value['style_no'],
            //             'ordered_qnty'  =>  $value['order_quantity'],
            //             'delivered_qnty'=>  $value['delvry_qnty'],
            //             'pending_qnty'  =>  $pendingQnty,
            //             'rate'          =>  $value['rate'],
            //             'tax'           =>  $value['tax'],
            //             // 'hsn_code'      =>  $value['hsn_code'],
            //             'hsn_code'      =>  2,
            //             'size'          =>  $size,
            //             'reference_no'  =>  $value['reference_name'],
            //             'item'          =>  $value['reference_name'],
            //             'ocn_no'        =>  $value['ocn_number'],
            //             'company_id'    =>  1,
            //         ]);
                    
            //         ErpOrderDetails::where('id',$value['id'])
            //             ->update([
            //                 'delivered_quantity'=> ( $value['order_quantity'] - $pendingQnty ),
            //                 'pending_orders'    => $pendingQnty,
            //                 'status'            => $status
            //                 ]);
            //         Erp::where('erp_id',$orders[0]['erp_id'])
            //             ->update(['status'=>$status]);

            //     }
            //      $result    = response()->json(['error'=> false,'message' =>'success'],200);
            // }
            
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

}