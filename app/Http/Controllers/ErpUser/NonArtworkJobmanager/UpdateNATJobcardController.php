<?php

namespace App\Http\Controllers\ErpUser\NonArtworkJobmanager;

use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Classes\JobcardMetadata;
use App\Classes\ErpCompanySession;
use App\Http\Controllers\Controller;
use App\Classes\ErpUserMappedTemplates;
use App\Classes\JobcardIDByCustomerBuyerId;
use Illuminate\Database\Eloquent\Collection;

class UpdateNATJobcardController extends Controller
{
    private $jobcard_ids_obj;

    public function __construct()
    {
        $this->middleware('erpuser');
        $this->jobcard_ids_obj = new JobcardIDByCustomerBuyerId();
    }

    public function getNonArtworkJobcardList()
    {

        $erp_user_mapped_template_obj = new ErpUserMappedTemplates();
        $set_company_session = new ErpCompanySession();

        $companies = $set_company_session->setCompanySession();

        $new_orders = $erp_user_mapped_template_obj->getPermittedTemplates();
        $buyers_array = array();

        foreach ($new_orders as $key => $values) {
            $split_key = explode('-', $key);
            $buyer_id = $split_key[0];
            $this->buyer_name = DB::table('buyers')->where('id', $buyer_id)->value('buyer_name');
            $buyers_array[$buyer_id] = $this->buyer_name;
        }

        return view(
            'erp.Non-Artwork-Jobmanager.non-artwork-jobcards-list',
            [
                'buyers' => $buyers_array,
                'companies' => $companies
            ]
        );
    }

    /**
     * Function to get jobcard id list by  buyer id and customer id.
     * @author <Praveen>
     * @param  $buyer_id, $customer_id
     * @return equivalent jobcard ids list
     */
    public function getNatJobcardIdListByBuyerCustomerId(Request $request)
    {

        if ($request['selected_buyer_id'] == null || $request['selected_customer_id'] == null) {
            return response()->json(['error' => true, 'message' => 'Parameter Missing'], 400);
        }

        $jobcard_id_list  = $this->jobcard_ids_obj->getJobcardIds($request, 1);

        return response()->json(['error' => false, 'message' => 'Success', 'jobcard_id_list' => $jobcard_id_list], 200);
    }

    /**
     * Function to get jobcards list by  buyer id, customer id and jobcard id.
     * @author <Praveen>
     * @param  $buyer_id, $customer_id, $jobcard_id
     * @return equivalent jobcard list
     */
    public function getNatJobcardsListByBuyerCustomerJobcardId(Request $request)
    {
        try {

            if ($request['selected_buyer_id'] == null || $request['selected_customer_id'] == null || $request['selected_jobcard_id'] == null) {
                return response()->json(['error' => true, 'message' => 'Parameter Missing'], 400);
            }

            $jobcards_list  = $this->jobcard_ids_obj->getJobcardList($request, 1);

            return response()->json(['error' => false, 'message' => 'Success', 'jobcards_list' => $jobcards_list], 200);
        } catch (\Exception $e) {
            return response()->json(['Message' => $e->getMessage(), 'Line' => $e->getLine(), 'File' => $e->getFile()]);
        }
    }

    /**
     * Function to get jobcard details by id.
     * @author <Praveen>
     * @param  $id
     * @return equivalent jobcard details
     */
    public function getNatJobcardDetailsById(Request $request)
    {
        try {
            $id = $request['id'];
            $jc_metadata = new JobcardMetadata();
            $metadata = $jc_metadata->erpJobcardMetaData();

            $jobcard_data  = DB::table('jobcard_main_table')
                ->select('jobcard_main_table.*', 'customers.id as customer_id', 'customers.short_name as customer', 'buyers.buyer_name', 'buyers.id as buyer_id', 'company.name as company', 'company.id as company_id', 'company.address as company_address', 'company.logo as company_logo', 'jobcard_excess_details.ref_name', 'jobcard_excess_details.mtrs_for_stickers', 'jobcard_excess_details.actual_excess', 'jobcard_excess_details.settings', 'jobcard_excess_details.total_mtrs')
                ->join('customers', 'jobcard_main_table.customer_id', 'customers.id')
                ->join('jobcard_excess_details', 'jobcard_main_table.id', 'jobcard_excess_details.jobcard_main_table_id')
                ->join('company', 'company.id', 'jobcard_main_table.company_id')
                ->join('buyers', 'jobcard_main_table.buyer_id', 'buyers.id')
                ->where('jobcard_main_table.id', $id)
                ->first();
            $jobcard_data->created_date      =    $jobcard_data->created_date == '' ? NULL : Carbon::parse($jobcard_data->created_date)->format('d-m-Y');
            $jobcard_data->order_date        =    $jobcard_data->order_date == '' ? NULL : Carbon::parse($jobcard_data->order_date)->format('d-m-Y');
            $jobcard_data->customer_del_date =    $jobcard_data->customer_del_date == '' ? NULL : Carbon::parse($jobcard_data->customer_del_date)->format('d-m-Y');
            $jobcard_data->invoice_date      =    $jobcard_data->invoice_date == '' ? NULL : Carbon::parse($jobcard_data->invoice_date)->format('d-m-Y');
            $jobcard_data->approval_date     =    $jobcard_data->approval_date == '' ? NULL : Carbon::parse($jobcard_data->approval_date)->format('d-m-Y');
            $jobcard_data->act_del_date     =    $jobcard_data->act_del_date == '' ? NULL : Carbon::parse($jobcard_data->act_del_date)->format('d-m-Y');
            $jobcard_data->aw_approve_date     =    $jobcard_data->aw_approve_date == '' ? NULL : Carbon::parse($jobcard_data->aw_approve_date)->format('d-m-Y');

            return response()->json([
                'error' => false,
                'message' => 'Success',
                'jobcard_data' => $jobcard_data,
                'disp_modes' => $metadata['erp_dispatch_mode'],
                'finish_types' => $metadata['erp_finish_type'],
                'machines' => $metadata['erp_machines'],
                'teeth_nos' => $metadata['erp_teeth_no'],
                'modal_title' => 'Update Jobcard'

            ], 200);
        } catch (\Exception $e) {
            return response()->json(['Message' => $e->getMessage(), 'Line' => $e->getLine(), 'File' => $e->getFile()]);
        }
    }
}
