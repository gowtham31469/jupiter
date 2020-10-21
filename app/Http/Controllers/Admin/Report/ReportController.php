<?php

namespace App\Http\Controllers\Admin\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Buyer;
use App\Customer;
use App\History;
use App\Action;
use App\EventHistory;
use App\ErpOrderDetails;
use App\ErpOrdersInvoice; 
use App\Invoices;
use App\InvoiceDetails;
use App\InvExtraCharges;
use App\Classes\Invoice;
use App\InvoiceAddress;
use App\TescoTaxCode;
use Carbon\Carbon;
use DB;
use Auth;

class ReportController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
    }

    public function index()
    {
		$tax=array_unique(TescoTaxCode::all()->pluck('tax')->toArray());
		array_multisort($tax);
		array_walk($tax, function(&$value, $key) { $value .= '% tax'; } );

		$allActions = ['1'=>'Orders','2'=>'Delivery'];
		$action_as_id = array();

		$options_for_orders = ['1'=>'Orders Placed(submitted orders)',
							  '2'=>'AW Approvl Pending',
							  '3'=>'PO Upload Pending',
							  '4'=>'PI Approval Pending',
							  '5'=>'Cancelled',
							  '6'=>'On Hold by Supplier',
							  '7'=>'Incomplete(saved,not submitted)'];

		$options_for_delivery = ['1'=>'Dispacted',
							     '2'=>'Pending'];

		$buyerId = History::all()->pluck('buyer_id');
		$buyers=Buyer::whereIn('id',$buyerId)->orderBy('buyer_name')->pluck('buyer_name','id')->toArray();
		$actions= Action::select('id','action','type')->get()->groupBy('type')->toArray();
		$po = Action::where('type','PO Manager')->pluck('action','id')->toArray();
		$jc = Action::where('type','JC Manager')->pluck('action','id')->toArray();
		$order = Action::where('type','Order')->pluck('action','id')->toArray();
		$invoice = Action::where('type','Invoice')->pluck('action','id')->toArray();
		$invoice=$invoice+$tax;
		$templates = [];
		$countries = [];
		$seasons =array_filter(array_unique(History::all()->sortBy('season')->pluck('season')->toArray()));
		
		return view('Admin.Reports.reportgenerator',compact('buyers','actions','po','jc','jc','order','invoice','allActions','action_as_id','countries','seasons','templates','options_for_orders','options_for_delivery'));
    }


    public function level1Filter(Request $request)
	{

		$result = History::whereIn('buyer_id',$request->buyer)
		->leftJoin("event_history",'history.id', '=', 'event_history.history_id')
		->whereIn('season',$request->season)
		->whereIn('template',$request->template)
		->whereIn('country',$request->country)
		->whereBetween('event_history.created_at', [$request->date_start.' 00:00:00', $request->date_end.' 23:59:59'])
		->select('material','customer_id','style','mat_sub')
		->get();

		$customer_id = $result->map(function ($result) {
		return $result['customer_id'];
		});

		$customers=Customer::whereIn('id',$customer_id)->orderBy('company_name')
		->pluck('company_name','id')->toArray();    

		return response()->json(['customers'=>$customers,'result'=>$result]);
	}

}
