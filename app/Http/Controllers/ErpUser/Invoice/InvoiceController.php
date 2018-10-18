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

    public function erpOrderList(Request $request)
    {
        try {
            $customer_id = $request->customer_id;
            $orderlist   = Erp::where('customer_id',$customer_id)
                            ->join('customers','erp_orders.customer_id','customers.id')
                            ->join('buyers','erp_orders.buyer_id','buyers.id')
                            ->select('erp_orders.id','buyers.buyer_name','buyers.id as buyer_id','customers.company_name','customers.id as customer_id','erp_orders.po_no')
                            ->get();
            $result      = response()->json(['error'=> false,'message' =>'success','orderlist'=>$orderlist ],200);
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
    }

}