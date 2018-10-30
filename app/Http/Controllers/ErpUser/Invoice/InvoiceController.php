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
        $customers = DB::table('customers')
                              ->where('is_active','=',1)
                              ->get();
        return view('erp.Invoice.create-invoice',['customers' => $customers]);
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
                            ->join('customers','erp_orders.customer_id','customers.id')
                            ->join('buyers','erp_orders.buyer_id','buyers.id')
                            ->select('erp_orders.id','buyers.buyer_name','buyers.id as buyer_id','customers.company_name','customers.id as customer_id','erp_orders.po_no','erp_orders.erp_id')
                            ->get();
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
            $erp_id = $request->erp_id;
            $orderlist   = ErpOrderDetails::where('erp_order_id',$erp_id)
                            ->join('erp_orders','erp_orders.id','erp_order_details.erp_order_id')
                            ->join('customers','erp_orders.customer_id','customers.id')
                            ->join('buyers','erp_orders.buyer_id','buyers.id')
                            ->select('erp_order_details.id','buyers.buyer_name','buyers.id as buyer_id','customers.company_name','customers.id as customer_id','erp_orders.po_no','erp_order_details.style_no','erp_order_details.order_quantity','erp_order_details.rate','erp_order_details.size','erp_order_details.reference_name','customers.place_of_supply','erp_order_details.ocn_number','erp_order_details.template_id','erp_orders.id as erp_id','buyers.template_table','buyers.item_master_table')
                            ->get();

            foreach ($orderlist as $key => $value){
               $details = DB::table($value->template_table)
                            ->where($value->template_table.'.item_id',$value->template_id)
                            ->select($value->template_table.'.hsn_code',$value->template_table.'.tax')->first();
                $value->hsn_code = $details->hsn_code;
                $value->tax = $details->tax;
            }
            $result      = response()->json(['error'=> false,'message' =>'success','orderlist'=>$orderlist ],200);
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

}