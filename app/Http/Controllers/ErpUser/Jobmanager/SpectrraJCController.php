<?php

namespace App\Http\Controllers\ErpUser\Jobmanager;

use Carbon\Carbon;
use App\ErpOrderDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Classes\JobcardIDGenerator;
use App\Http\Controllers\Controller;
use App\Classes\SpectrraCompanyTemplateData;

class SpectrraJCController extends Controller
{
    private $erp_jc_status_id;
    private $order_ap_status_id;
    private $jobcard_id_gen_obj;
    private $current_date;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('erpuser');

        $role = ["NON-ARTWORK JOB CARD MANAGER", "JOB CARD MANAGER"];
        // $this->middleware('role_permission:'.$role);
        $this->middleware('role_permission:', ['only' => $role]);

        // get the current time
        $this->current_date = Carbon::now('Asia/Kolkata')->format('Y-m-d');

        // Get Erp status table id for 'Moved To JC Queue' status.
        $this->erp_jc_status_id = DB::table('erp_status')
            ->where('status_name', 'Moved To JC Queue')
            ->value('id');

        // Get order status table id for 'Order Approved' status.
        $this->order_ap_status_id = DB::table('orders_status')
            ->whereIn('status_name', ['Order Approved', 'Order Placed'])
            ->pluck('id')
            ->all();
        $this->jobcard_id_gen_obj = new JobcardIDGenerator();
    }

    public function getSpectrraOrderDetails(Request $request)
    {
        $get_spectrra_metadata = new SpectrraCompanyTemplateData();

        $order_details = ErpOrderDetails::with('orders:id,po_no,erp_id,customer_id,buyer_id')->where([
            'erp_order_details.uuid' => $request['uuid_array'],
            'erp_order_details.status' => $this->erp_jc_status_id,
        ])
            ->whereIn('erp_order_details.order_status_id', $this->order_ap_status_id)
            ->select('erp_order_details.style_no', 'erp_order_details.po_verified_date', 'erp_order_details.order_date', 'erp_order_details.delivery_date', 'erp_order_details.erp_order_id', 'erp_order_details.template_id', DB::raw('SUM(order_quantity) as total_qty'))
            ->groupBy('erp_order_details.style_no', 'erp_order_details.po_verified_date', 'erp_order_details.order_date', 'erp_order_details.delivery_date', 'erp_order_details.erp_order_id', 'erp_order_details.template_id')
            ->first();

        $data['template_id'] = $order_details['template_id'];
        $data['buyer'] = strtolower($order_details['orders']['buyer']['buyer_name']);

        $spectrra_template_data = $get_spectrra_metadata->getSpectrraCompanyTemplateData($data);

        return response()->json([
            'error' => false,
            'message' => 'success',
            'spectrra_template_data' => $spectrra_template_data,
            'order_details' => $order_details,
        ], 200);
    }
}
