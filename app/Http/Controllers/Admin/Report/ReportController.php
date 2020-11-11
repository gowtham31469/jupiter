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
							  // '6'=>'On Hold by Supplier',
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
		
		$result = OrderManagementHistory::where('buyer_id',$request->buyer)
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

		$result = OrderManagementHistory::where('buyer_id',$request->buyer)
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

		$if_Placed = ["DT_Row_Index","Order_Dt","Material","Sub_Material","Style","P.O","OCN","Reference","ByrPo","Quantity","Aw_Status","PI_Status","PO_Status"];

		$if_Cancelled = ["DT_Row_Index","Order_Dt","Material","Sub_Material","Style","P.O","OCN","Reference","Quantity"];

		$if_Aw_Pending = ["DT_Row_Index","Order_Dt","Material","Sub_Material","Style","P.O","OCN","Reference","ByrPo","Quantity","Aw_Status","PI_Status","PO_Status"];

		$if_PO_Upload_Pending = ["DT_Row_Index","Order_Dt","Material","Sub_Material","Style","P.O","OCN","Reference","ByrPo","Quantity","Aw_Status"];

		$if_PI_Upload_Pending = ["DT_Row_Index","Order_Dt","Material","Sub_Material","Style","P.O","OCN","Reference","ByrPo","Quantity","Aw_Status"];

		$if_On_Hold = ["DT_Row_Index","Order_Dt","Material","Sub_Material","Style","P.O","OCN","Reference","Quantity","Aw_Status"];

		$if_Incomplete = ["DT_Row_Index","Order_Dt","Material","Sub_Material","Style","P.O","OCN","Reference","Quantity"];

		$if_Dispatched = ["DT_Row_Index","Order_Dt","Material","Sub_Material","Style","P.O","OCN","Reference","Quantity","DispMode","Awb"];

		$if_Pending = ["DT_Row_Index","Order_Dt","Material","Sub_Material","Style","P.O","OCN","Reference","Quantity"];

		$all_fields = ["DT_Row_Index","Order_Dt","Material","Sub_Material","Style","P.O","OCN","Reference","ByrPo","Quantity","Aw_Status","Inv_No","Inv_Dt","Aw_Status","PI_Status","PO_Status","Reason","Reapproved_Dt","DispMode","Awb"];

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
		// dd($request->all());
		$table_names = OrderManagementHistory::where('buyer_id',$request->buyer)
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

		->where(function($q) use ($request) {

			foreach ($request->status as $key => $value) 
			{
				switch ($value) 
				{
					case 1:
						$q->where('order_status',2);
					break;
					
					case 2:
						$q->where('artwork_status',1);
					break;

					case 3:
						$q->where('po_status',1);
					break;

					case 4:
						$q->where('pi_status',1);
					break;

					case 5:
						$q->where('order_status',3);
					break;

					case 6:
						$q->where('order_status','Hold');
					break;

					case 7:
						$q->where('order_status',1);
					break;

					default:
						# code...
						break;
				}
			}
			
		})
		->select('order_table_name','order_table_id')
		->get();

		$queryRes = collect();
		
		$ddmmyy="DD/MM/YYYY";

		switch ($request->buyer) {
			case 1:
				foreach ($table_names as $key => $value) 
				{
					$primary = $value['order_table_name'];
					$secondary = $value['order_table_name']."_qty";
					$primary_id = $value['order_table_name'].'.id';
					$secondary_id = $value['order_table_name'].'_qty'.'.oht_id';
					$primary_order_date = $value['order_table_name'].'.order_created_at';

					$res = collect();

					$res = DB::table($primary)
						->leftJoin($secondary,$secondary_id,$primary_id)
						->select([
							DB::raw('to_char('.$primary_order_date.','."'".$ddmmyy."'".') as "Order_Dt"'),
							'uvc as Style',
							'quantity as Quantity',
							'pi_status as PI_Status',
							'po_status as PO_Status',
							'status as Aw_Status',
							'po as ByrPo',
							'po_number as P.O',
						])->get();
					

					$template_id = DB::table($primary)->where('id',$value['order_table_id'])->first()->template_id;

					$template_data = DB::table('orchestra_item_master')
											->where('orchestra_item_master.id', $template_id)
											->select('orchestra_item_master.material_id', 'orchestra_item_master.item_name', 'orchestra_item_master.sub_material_id')
											->first();

					$material_name  = DB::table('orchestra_item_master')
												->where('id', '=', $template_data->material_id)
												->value('item_name');

					$material_sub  = DB::table('orchestra_item_master')
											->where('id', '=', $template_data->sub_material_id)
											->value('item_name');

					$material_ref   = $template_data->item_name;


					$res->map(function ($res,$key) use($material_name,$material_sub,$material_ref) {
		                    $res->Material = $material_name;
		                    $res->Sub_Material = $material_sub;
		                    $res->Reference = $material_ref;
		                    $res->Inv_No = "-";
		                    $res->Inv_Dt = "-";
		                    $res->Reapproved_Dt = "-";
		                    $res->DispMode = "-";		
		                    $res->Awb = "-";		
		                    $res->Reason = "-";		
		                    $res->OCN = "-";		
                   		 });

					$queryRes = $queryRes->merge($res);
				}
				break;
			

			case 2:
				foreach ($table_names as $key => $value) 
				{

					$primary = $value['order_table_name'];
					$base = explode("_", $primary)[1];
					$secondary = $value['order_table_name']."_quanty";
					$primary_id = $value['order_table_name'].'.id';
					$secondary_id = $value['order_table_name'].'_quanty.'.$base.'_order_id';
					$primary_order_date = $value['order_table_name'].'.order_created_at';

					$res = collect();

					$res = DB::table($primary)
						->leftJoin($secondary,$secondary_id,$primary_id)
						->select([
							DB::raw('to_char('.$primary_order_date.','."'".$ddmmyy."'".') as "Order_Dt"'),
							'zone','uk_style_ref','ce_style_ref',
							'count as Quantity',
							'pi_status as PI_Status',
							'po_status as PO_Status',
							'order_status as Aw_Status',
							'po_number as ByrPo',
							'ocn_number as OCN',
							'purchase_order_no as P.O',
						])
						->get();
					
					$template_id = DB::table($primary)->where('id',$value['order_table_id'])->first()->template_id;

					$template_level = DB::table('tesco_item_master as t1')
                            ->select('t3.item_name as material_sub', 't4.item_name as material', 't1.item_name as ref')
                            ->leftJoin('tesco_item_master as t2', 't2.id','=','t1.parent_id')
                            ->leftJoin('tesco_item_master as t3', 't3.id','=','t2.parent_id')
                            ->leftJoin('tesco_item_master as t4', 't4.id','=','t3.parent_id')
                            ->where('t1.id', $template_id)
                            ->first();

					$res->map(function ($res,$key) use($template_level) {
		                    $res->Material = $template_level->material_sub;
		                    $res->Sub_Material = $template_level->material;
		                    $res->Reference = $template_level->ref;
		                    $res->Inv_No = "-";
		                    $res->Inv_Dt = "-";
		                    $res->Reapproved_Dt = "-";
		                    $res->DispMode = "-";		
		                    $res->Awb = "-";		
		                    $res->Reason = "-";		
		                    $res->zone=="UK" ? $res->Style = $res->uk_style_ref : $res->Style = $res->ce_style_ref;	
                   		 });

					$queryRes = $queryRes->merge($res);
				}
				break;


			default:
				# code...
				break;
		}

		return Datatables::of($queryRes)->addIndexColumn()->make();
	}


	public function testQuery()
	{
		$res = DB::table('orchestra_hang_tag_orders')->leftJoin('orchestra_hang_tag_orders_qty','orchestra_hang_tag_orders_qty.oht_id','orchestra_hang_tag_orders.id')->select(['orchestra_hang_tag_orders.created_at as Created'])->get()->take(1);

		$res1 = DB::table('orchestra_heat_transfer_orders')->leftJoin('orchestra_heat_transfer_orders_qty','orchestra_heat_transfer_orders_qty.oht_id','orchestra_heat_transfer_orders.id')->select(['orchestra_heat_transfer_orders.created_at as Created'])->get()->take(1);
		$queryRes = $res->merge($res1);
		return $queryRes;
	}
}
