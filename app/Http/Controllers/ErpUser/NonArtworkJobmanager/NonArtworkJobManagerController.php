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

        $customers = Erp::join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
        ->join('customers', 'erp_orders.customer_id', 'customers.id')
        ->where(['erp_order_details.deleted_at' => null,
                'erp_order_details.status' => $this->erp_jc_status_id,
                'erp_order_details.order_status_id' => $this->order_ap_status_id,
                'erp_order_details.order_qnty_table_name' => 'non_artwork_orders_quanty'])
        ->pluck('customers.company_name', 'customers.id');

        $item_names = DB::table('reports_template_level_name')->pluck('name', 'id');

        return view(
            'erp.Non-Artwork-Jobmanager.non-artwork-jobcard-home',
            [
                'erp_po_ids' => $erp_po_ids,
                'buyers' => $buyers,
                'customers' => $customers,
                'item_names' => $item_names,
            ]
        );
    }
}
