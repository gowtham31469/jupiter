<?php

namespace App\Http\Controllers\ErpUser\NonArtworkJobmanager;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Classes\JobcardIDGenerator;
use App\Http\Controllers\Controller;
use App\ErpOrders as Erp;
use App\ErpOrderDetails as ErpOrderDetails;

class NonArtworkJobManagerController extends Controller
{
    private $current_date;
	private $erp_jc_status_id;
	private $order_ap_status_id;
	private $jobcard_id_gen_obj;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('erpuser');

		$role = "NON-ARTWORK JOB CARD MANAGER";
        $this->middleware('role_permission:'.$role);
		// get the current time
		$this->current_date = Carbon::now('Asia/Kolkata')->format('Y-m-d');

		// Get Erp status table id for 'Moved To JC Queue' status.
		$this->erp_jc_status_id = DB::table('erp_status')->where('status_name', 'Moved To JC Queue')->value('id');
		 
		// Get order status table id for 'Order Approved' status.
		$this->order_ap_status_id = DB::table('orders_status')->where('status_name', 'Order Approved')->value('id');
		$this->jobcard_id_gen_obj = new JobcardIDGenerator();
	}

    /**
	 * Show the Non Artwork Jobcard  Filter View.
	 * @author [Praveen] <[praveen@wizroots.com]>
	 * @return \Illuminate\Http\Response
	 */
    public function index()
    {
        $erp_po_ids = Erp::join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
        ->where(['erp_order_details.deleted_at' => null,
                'erp_order_details.status' => $this->erp_jc_status_id,
                'erp_order_details.order_status_id' => $this->order_ap_status_id,
                'erp_order_details.order_qnty_table_name' => 'non_artwork_orders_quanty'])
        ->pluck('erp_orders.po_no', 'erp_orders.erp_id');

        $buyers = Erp::join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
        ->join('buyers', 'erp_orders.buyer_id', 'buyers.id')
        ->where(['erp_order_details.deleted_at' => null,
                'erp_order_details.status' => $this->erp_jc_status_id,
                'erp_order_details.order_status_id' => $this->order_ap_status_id,
                'erp_order_details.order_qnty_table_name' => 'non_artwork_orders_quanty'])
        ->pluck('buyers.buyer_name', 'buyers.id');

        return view(
            'erp.Non-Artwork-Jobmanager.non-artwork-jobcard-home',
            [
                'erp_po_ids' => $erp_po_ids,
                'buyers' => $buyers,
            ]
        );
    }

    public function getCustomersByBuyerId(Request $request)
    {
        $customers = Erp::join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
        ->join('customers', 'erp_orders.customer_id', 'customers.id')
        ->where(['erp_order_details.deleted_at' => null,
                'erp_order_details.status' => $this->erp_jc_status_id,
                'erp_order_details.order_status_id' => $this->order_ap_status_id,
                'erp_order_details.order_qnty_table_name' => 'non_artwork_orders_quanty',
                'erp_orders.buyer_id' => $request['buyer_id']])
        ->select('customers.company_name', 'customers.id')
        ->distinct('customers.id')
        ->get();

        return response()->json([
            'error'=> false,
            'message' =>'success', 
            'customers' => $customers 
        ],200);	
    }

    public function getItemNamesByCustomerId(Request $request)
    {
        $buyer_name = DB::table('buyers')->where('id', $request['buyer_id'])->value('buyer_name');

        $item_names = Erp::join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
        ->join(strtolower($buyer_name).'_item_master', 'erp_order_details.template_id', strtolower($buyer_name).'_item_master.id')
        ->join(strtolower($buyer_name).'_template', strtolower($buyer_name).'_template.item_id', strtolower($buyer_name).'_item_master.id')
        ->join('reports_template_level_name', strtolower($buyer_name).'_template.name_in_reports', 'reports_template_level_name.id')
        ->where(['erp_order_details.deleted_at' => null,
                'erp_order_details.status' => $this->erp_jc_status_id,
                'erp_order_details.order_status_id' => $this->order_ap_status_id,
                'erp_order_details.order_qnty_table_name' => 'non_artwork_orders_quanty',
                'erp_orders.customer_id' => $request['customer_id']])
        ->select('reports_template_level_name.name', 'reports_template_level_name.id')
        ->distinct('reports_template_level_name.id')
        ->get();

        return response()->json([
            'error'=> false,
            'message' =>'success', 
            'item_names' => $item_names 
        ],200);	
    }

    public function getReferencesByItemName(Request $request)
    {
        $buyer_name = DB::table('buyers')->where('id', $request['buyer_id'])->value('buyer_name');

        $references = Erp::join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
        ->join(strtolower($buyer_name).'_item_master', 'erp_order_details.template_id', strtolower($buyer_name).'_item_master.id')
        ->join(strtolower($buyer_name).'_template', strtolower($buyer_name).'_template.item_id', strtolower($buyer_name).'_item_master.id')
        ->join('reports_template_level_name', strtolower($buyer_name).'_template.name_in_reports', 'reports_template_level_name.id')
        ->where(['erp_order_details.deleted_at' => null,
                'erp_order_details.status' => $this->erp_jc_status_id,
                'erp_order_details.order_status_id' => $this->order_ap_status_id,
                'erp_order_details.order_qnty_table_name' => 'non_artwork_orders_quanty',
                'erp_orders.customer_id' => $request['customer_id'],])
        ->whereIn(strtolower($buyer_name).'_template.name_in_reports', $request['item_name_id'])
        ->select('erp_order_details.reference_name', 'erp_order_details.template_id')
        ->distinct('erp_order_details.template_id')
        ->get();

        return response()->json([
            'error'=> false,
            'message' =>'success', 
            'references' => $references
        ],200);		
    }

    public function getStyleOcnBuyerPoByItemName(Request $request)
    {
        $buyer_name = DB::table('buyers')->where('id', $request['buyer_id'])->value('buyer_name');

        $query = Erp::join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
        ->join(strtolower($buyer_name).'_item_master', 'erp_order_details.template_id', strtolower($buyer_name).'_item_master.id')
        ->join(strtolower($buyer_name).'_template', strtolower($buyer_name).'_template.item_id', strtolower($buyer_name).'_item_master.id')
        ->join('reports_template_level_name', strtolower($buyer_name).'_template.name_in_reports', 'reports_template_level_name.id')
        ->where(['erp_order_details.deleted_at' => null,
                'erp_order_details.status' => $this->erp_jc_status_id,
                'erp_order_details.order_status_id' => $this->order_ap_status_id,
                'erp_order_details.order_qnty_table_name' => 'non_artwork_orders_quanty',
                'erp_orders.customer_id' => $request['customer_id'],])
        ->whereIn(strtolower($buyer_name).'_template.name_in_reports', $request['item_name_id'])
        ->whereIn('erp_order_details.reference_name', $request['reference_name']);


        $ocn_numbers = $query->select('erp_order_details.ocn_number')
        ->distinct('erp_order_details.ocn_number')
        ->get();

        $buyer_po_number = $query->select('erp_order_details.buyer_po_number')
        ->distinct('erp_order_details.buyer_po_number')
        ->get();

        $style_no = $query->select('erp_order_details.style_no')
        ->distinct('erp_order_details.style_no')
        ->get();

        return response()->json([
            'error'=> false,
            'message' =>'success', 
            'ocn_numbers' => $ocn_numbers, 
            'buyer_po_number' => $buyer_po_number, 
            'style_no' => $style_no 
        ],200);		
    }
}
