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
use App\OrderManagementHistory;
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

		$options_for_delivery = ['8'=>'Dispacted',
							     '9'=>'Pending'];

		$order_hisory = OrderManagementHistory::all();

		$seasons = $order_hisory->unique('season')->pluck('season');

		$buyer_id = $order_hisory->unique('buyer_id')->pluck('buyer_id');

		$buyers = Buyer::whereIn('id',$buyer_id)->orderBy('buyer_name')
												->pluck('buyer_name','id')
												->toArray();
		$templates = [];
		$countries = [];

		return view('Admin.Reports.reportgenerator',compact('buyers','allActions','countries','seasons','templates','options_for_orders','options_for_delivery'));
    }


    public function level1Filter(Request $request)
	{
		
		$result = OrderManagementHistory::whereIn('buyer_id',$request->buyer)
		->whereIn('season',$request->season)

		->whereBetween('created_at', [$request->date_start.' 00:00:00', $request->date_end.' 23:59:59'])
		->select('material','customer_id','style','sub_material')
		->get();

		$customer_id = $result->unique('customer_id')->pluck('customer_id');

		$materials = $result->unique('material')->pluck('material');
		$sub_materials = $result->unique('sub_material')->pluck('sub_material');
		$style = $result->unique('style')->pluck('style');

		$customers=Customer::whereIn('id',$customer_id)->orderBy('company_name')
		->pluck('company_name','id')->toArray();    

		return response()->json(['customers'=>$customers,'result'=>$result,'Materials'=>$materials,'SubMaterials'=>$sub_materials,'Style'=>$style]);
	}


	public function level2Filter(Request $request)
	{

		$result = OrderManagementHistory::whereIn('buyer_id',$request->buyer)
		->whereIn('season',$request->season)
		->whereIn('material',$request->materials)
		->whereIn('sub_material',$request->mat_subs)
		->whereIn('style',$request->styles)
		->whereIn('customer_id',$request->customers)

		->whereBetween('created_at', [$request->date_start.' 00:00:00', $request->date_end.' 23:59:59'])
		->select('reference','buyer_po','ocn_number','customer_po')
		->get();

		$reference = $result->unique('reference')->pluck('reference');
		$buyer_po = $result->unique('buyer_po')->pluck('buyer_po');
		$customer_po = $result->unique('customer_po')->pluck('customer_po');
		$ocn = $result->unique('ocn_number')->pluck('ocn_number');

		return response()->json(['Reference'=>$reference,'BuyerPo'=>$buyer_po,'CustomerPo'=>$customer_po,'Ocn'=>$ocn]);
	}


	public function admin_getTableFields(Request $request)
	{   

		$default_fields = [];
		$extra_fields = [];

		$if_Placed = ["Order_Dt","Material","Sub_Material","Style","P.O","OCN","Reference","ByrPo","Quantity","Aw_Status","PI_PO_status"];

		$if_Cancelled = ["Order_Dt","Material","Sub_Material","Style","P.O","OCN","Reference","Quantity","Reason"];

		$if_Aw_Pending = ["Order_Dt","Material","Sub_Material","Style","P.O","OCN","Reference","ByrPo","Quantity","Aw_Status","PI_PO_status","Reapproved_Dt"];

		$if_PO_Upload_Pending = ["Order_Dt","Material","Sub_Material","Style","P.O","OCN","Reference","ByrPo","Quantity","Aw_Status"];

		$if_PI_Upload_Pending = ["Order_Dt","Material","Sub_Material","Style","P.O","OCN","Reference","ByrPo","Quantity","Aw_Status"];

		$if_On_Hold = ["Order_Dt","Material","Sub_Material","Style","P.O","OCN","Reference","Quantity","Aw_Status","Reason"];

		$if_Incomplete = ["Order_Dt","Material","Sub_Material","Style","P.O","OCN","Reference","Quantity"];

		$if_Dispatched = ["Order_Dt","Material","Sub_Material","Style","P.O","OCN","Reference","Quantity","DispMode","Awb"];

		$if_Pending = ["Order_Dt","Material","Sub_Material","Style","P.O","OCN","Reference","Quantity"];

		$all_fields = ["Order_Dt","Material","Sub_Material","Style","P.O","OCN","Reference","ByrPo","Quantity","Aw_Status","Inv_No","Inv_Dt","Aw_Status","PI_PO_status","Reason","Reapproved_Dt","DispMode","Awb"];

		foreach ($request->status as $key => $value) {
			# code...
			switch ($value) 
					{
						case 1:
							foreach ($if_Placed as $key => $value)
							{
								array_push($default_fields,$value);
							}
						break;

						case 2:
							foreach ($if_Aw_Pending as $key => $value)
							{
								array_push($default_fields,$value);
							}
						break;

						case 3:
							foreach ($if_PO_Upload_Pending as $key => $value)
							{
								array_push($default_fields,$value);
							}
						break;

						case 4:
							foreach ($if_PI_Upload_Pending as $key => $value)
							{
								array_push($default_fields,$value);
							}
						break;

						case 5:
							foreach ($if_Cancelled as $key => $value)
							{
								array_push($default_fields,$value);
							}
						break;

						case 6:
							foreach ($if_On_Hold as $key => $value)
							{
								array_push($default_fields,$value);
							}
						break;

						case 7:
							foreach ($if_Incomplete as $key => $value)
							{
								array_push($default_fields,$value);
							}
						break;

						case 8:
							foreach ($if_Dispatched as $key => $value)
							{
								array_push($default_fields,$value);
							}
						break;

						case 9:
							foreach ($if_Pending as $key => $value)
							{
								array_push($default_fields,$value);
							}
						break;

						default:
						break;
					}

		}

		return response()->json(['Default'=>array_values(array_unique($default_fields)),'Extra'=>array_values(array_diff(array_values(array_unique($all_fields)),array_values(array_unique($default_fields))))]);

	}


	public function admin_setUpDatatable(Request $request)
	{   
		// return $request->all();


		$table_names = OrderManagementHistory::whereIn('buyer_id',$request->buyer)
		->whereIn('season',$request->season)
		->whereIn('material',$request->materials)
		->whereIn('sub_material',$request->mat_subs)
		->whereIn('style',$request->styles)
		->whereIn('customer_id',$request->customers)
		->whereIn('reference',$request->references)
		->whereIn('customer_po',$request->pos)
		->whereIn('buyer_po',$request->buyer_pos)
		->whereIn('ocn_number',$request->ocns)
		->whereBetween('created_at', [$request->date_start.' 00:00:00', $request->date_end.' 23:59:59'])
		->select('order_table_name','order_table_id')
		->get();

		// $queryRes =
		$ddmmyy="DD/MM/YYYY";
		$queryRes = collect(); 
		// return $table_names;
		foreach ($table_names as $key => $value) 
		{

			$primary = $value['order_table_name'];
			$secondary = $value['order_table_name']."_qty";
			$primary_id = $value['order_table_name'].'.id';
			$secondary_id = $value['order_table_name'].'_qty'.'.oht_id';
			$primary_order_date = $value['order_table_name'].'.order_created_at';
			// return $primary_order_date;

			$res = DB::table($primary)
					->leftJoin($secondary,$secondary_id,$primary_id)
					->select([
						// ''.$primary_order_date.' as Order_Dt'])->get();
	         
	                    DB::raw('to_char('.$primary_order_date.','."'".$ddmmyy."'".') as "Order_Dt"'),
	    //                 'company_name as Customer',
	                    // 'material as Material',
	                    'uvc as Style',
	    //                 'history.po as P.O',
	    //                 'ocn as OCN',
	    //                 'jc_number as JC.No',
	    //                 'invoice_number as Inv.No',
	    //                 'season as Season',
	    //                 'country as Country',
	    //                 'buyer_po as ByrPo',
	    //                 'order_quantity as Quantity',
	    //                 'actions.action as Status',
	    //                 'event_history.reason as Reason'
                	])
                	->get();
               	
            // dd($queryRes[0]->created_at);
			$queryRes = $queryRes->merge($res);

			
		}

		// dd($res);

		return Datatables::of($queryRes)->make();
	}


	public function testQuery()
	{
		$res = DB::table('orchestra_hang_tag_orders')->leftJoin('orchestra_hang_tag_orders_qty','orchestra_hang_tag_orders_qty.oht_id','orchestra_hang_tag_orders.id')->select(['orchestra_hang_tag_orders.created_at as Created'])->get()->take(1);

		$res1 = DB::table('orchestra_heat_transfer_orders')->leftJoin('orchestra_heat_transfer_orders_qty','orchestra_heat_transfer_orders_qty.oht_id','orchestra_heat_transfer_orders.id')->select(['orchestra_heat_transfer_orders.created_at as Created'])->get()->take(1);
		$queryRes = $res->merge($res1);
		return $queryRes;
	}
}
