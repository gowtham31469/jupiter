<?php

namespace App\Http\Controllers\ErpUser\Jobmanager;

use View;
use Carbon\Carbon;
use App\ErpOrderDetails;
use Illuminate\Http\Request;
use App\SpectrraJobcardMainTable;
use Illuminate\Support\Facades\DB;
use App\Classes\JobcardIDGenerator;
use App\Http\Controllers\Controller;
use App\Classes\StoreSpectrraJobcard;
use App\Http\Requests\SpectrraJCRequest;
use App\Classes\SpectrraTemplateMetadata;
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
        $jobcard_id_gen_obj = new JobcardIDGenerator();
        $spectrra_metadata = new SpectrraTemplateMetadata();

        $order_details = ErpOrderDetails::with('orders:id,po_no,erp_id,customer_id,buyer_id', 'company:id,name,address,logo')
        ->where([
            'erp_order_details.uuid' => $request['uuid_array'],
            'erp_order_details.status' => $this->erp_jc_status_id,
        ])
        ->whereIn('erp_order_details.order_status_id', $this->order_ap_status_id)
        ->select('erp_order_details.style_no', 'erp_order_details.po_verified_date', 'erp_order_details.order_date', 'erp_order_details.delivery_date', 'erp_order_details.erp_order_id', 'erp_order_details.template_id', 'erp_order_details.company_id', 'erp_order_details.reference_name', DB::raw('SUM(order_quantity) as total_qty'))
        ->groupBy('erp_order_details.style_no', 'erp_order_details.po_verified_date', 'erp_order_details.order_date', 'erp_order_details.delivery_date', 'erp_order_details.erp_order_id', 'erp_order_details.template_id', 'erp_order_details.company_id', 'erp_order_details.reference_name')
        ->first();

        $data['template_id'] = $order_details['template_id'];
        $data['buyer'] = strtolower($order_details['orders']['buyer']['buyer_name']);
        $temp_id_array = explode('|', $order_details['template_id']);
        $erp_array = explode('|', $order_details['orders']['id']);
        $buyer_item_table = $order_details['orders']['buyer']['item_master_table'];
        $buyer_template_table = $order_details['orders']['buyer']['template_table'];
        $order_details->po_verified_date = is_null($order_details['po_verified_date']) === true?$order_details['po_verified_date']:Carbon::parse($order_details['po_verified_date'])->format('d-m-Y');
        $order_details->order_date = is_null($order_details['order_date']) === true?$order_details['order_date']:Carbon::parse($order_details['order_date'])->format('d-m-Y');;
        $order_details->delivery_date = is_null($order_details['delivery_date']) === true?$order_details['delivery_date']:Carbon::parse($order_details['delivery_date'])->format('d-m-Y');;

        $spectrra_template_data = $get_spectrra_metadata->getSpectrraCompanyTemplateData($data);
        
        /** Get templates metadata details */
        $template_details = DB::table($buyer_template_table)
                ->select($buyer_item_table . '.item_name as article', $buyer_template_table.'.type as template_type', 'reports_template_level_name.name as name_in_reports')
                ->join($buyer_item_table, $buyer_item_table . '.id', $buyer_template_table . '.hierarchy_id')
                ->join('reports_template_level_name', 'reports_template_level_name' . '.id', $buyer_template_table . '.name_in_reports')
                ->whereIn($buyer_template_table . '.item_id', $temp_id_array)
                ->orderBy($buyer_template_table . '.id')
                ->first();

        // jobcard id format "Itemname(2)FinancialYearERPID(last 5)0001" where  and for old order "JGA-JC-1"
        $article_array = explode('|', $template_details->article);
        $workorder_id = $jobcard_id_gen_obj->generateJobcardID($article_array, $erp_array);

        return response()->json([
            'error' => false,
            'message' => 'success',
            'spectrra_template_data' => $spectrra_template_data,
            'order_details' => $order_details,
            'modal_title' => 'Create Jobcard',
            'workorder_id' => $workorder_id,
            'template_details' => $template_details,
            'today_date'       => Carbon::parse($this->current_date)->format('d-m-Y'),
            'board_category' => $spectrra_metadata->getBoardCategories(),
            'boards' => $spectrra_metadata->getBoards(),
            'color_match' => $spectrra_metadata->getColorMatches(),
        ], 200);
    }

    public function storeSpectrraJobcard(SpectrraJCRequest $request)
    {
        $request->validated();
        
        $store_jc = new StoreSpectrraJobcard();
        $file_path = $store_jc->storeSpectrraJC($request);

        return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path],200);
    }

    public function getSpectrraJCDetails(Request $request)
    {
        $spectrra_metadata = new SpectrraTemplateMetadata();

        $jobcard_data  = SpectrraJobcardMainTable::with('company:id,name,address,logo', 'colorMatches', 'erpOrder')
        ->where('spectrra_jobcard_main_tables.id', '=', $request['jc_id'])
        ->first();

        $jobcard_data->created_date      =	$jobcard_data->created_date == ''?NULL:Carbon::parse($jobcard_data->created_date)->format('d-m-Y');
        $jobcard_data->order_date        =	$jobcard_data->order_date == ''?NULL:Carbon::parse($jobcard_data->order_date)->format('d-m-Y');
        $jobcard_data->po_verified_date =	$jobcard_data->po_verified_date == ''?NULL:Carbon::parse($jobcard_data->po_verified_date)->format('d-m-Y');
        $jobcard_data->invoice_date      =	$jobcard_data->invoice_date == ''?NULL:Carbon::parse($jobcard_data->invoice_date)->format('d-m-Y');
        $jobcard_data->customer_del_date      =	$jobcard_data->customer_del_date == ''?NULL:Carbon::parse($jobcard_data->customer_del_date)->format('d-m-Y');

        return response()->json([
            'error' => false,
            'message' => 'success',
            'modal_title' => 'Update Jobcard',
            'order_details'  => $jobcard_data,
            'board_category' => $spectrra_metadata->getBoardCategories(),
            'boards' => $spectrra_metadata->getBoards(),
            'color_match' => $spectrra_metadata->getColorMatches(),
        ], 200);
    }

}
