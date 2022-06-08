<?php

namespace App\Http\Controllers\ErpUser\NonArtworkJobmanager;

use Auth;
use DataTables;
use App\ErpOrders as Erp;
use Illuminate\Http\Request;
use App\Classes\JobcardMetadata;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use App\ErpOrderDetails as ErpOrderDetails;
use Carbon\Carbon;
use App\Classes\JobcardIDGenerator;

class NonArtworkJobCardFiltersController extends Controller
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

    public function getPermittedTemplates()
    {
        $erp_user_data  = DB::table('erpusers_template_mapping')
        ->join('buyers', 'erpusers_template_mapping.buyer', 'buyers.id')
        ->where('erpusers_template_mapping.company',session()->get('company'))
        ->where(function ($query) {
            if (Auth::guard('erpuser')->id() != 1) {
                $query->where('erpusers_template_mapping.erp_user', Auth::guard('erpuser')->id());
            }
        })
        ->select('erpusers_template_mapping.buyer as buyer_id', 'erpusers_template_mapping.templates', 'buyers.template_table', 'buyers.item_master_table')
        ->distinct('erpusers_template_mapping.templates')
        ->get();

        $new_orders = array();
        $i = 1;
        foreach ($erp_user_data as $value) {
            $arr_template =  DB::table($value->template_table)
                ->where('hierarchy_id', $value->templates)
                ->distinct('hierarchy_id')
                ->pluck('item_id');
            if ($arr_template) {
                $new_orders[$value->buyer_id . "-" . $i] = $arr_template;
            }
            $i++;
        }

        return $new_orders;
    }

    /**
	 * Show the Non Artwork Jobcard  Filter View.
	 * @author [Praveen] <[praveen@wizroots.com]>
	 * @return \Illuminate\Http\Response
	 */
    public function index()
    {
        $companies   = 	DB::table('erpuser_company_mapping')
									->join('company','erpuser_company_mapping.company','company.id')
									->where('erpuser_company_mapping.erp_user',Auth::guard('erpuser')->id())
									->select('erpuser_company_mapping.*','company.name', 'company.logo')
									->get();
        if(!$companies){
            return view('erp.home');
        }
                      
        $session_company_id = session()->get('company');

        if($session_company_id == "" && $session_company_id == null){
            $company_data   = DB::table('company')
                            ->where('company.id', $companies[0]->company)
                            ->select('company.id', 'company.name')
                            ->first();

            session()->put('company', $company_data->id);
            session()->put('company_name', strtolower( str_replace(' ','',$company_data->name)) );
        }
        
        $new_orders = $this->getPermittedTemplates();
        $buyers_array = array();
        $erp_po_ids = array();

        foreach ($new_orders as $key => $values) {
            $split_key = explode('-', $key);
            $buyer_id = $split_key[0];
            $this->buyer_name = DB::table('buyers')->where('id', $buyer_id)->value('buyer_name');

            $erp_po_ids[] = Erp::join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
            ->where([
                'erp_order_details.deleted_at' => null,
                'erp_order_details.status' => $this->erp_jc_status_id,
                'erp_order_details.order_status_id' => $this->order_ap_status_id,
                'erp_order_details.order_qnty_table_name' => 'non_artwork_orders_quanty',
                'erp_orders.buyer_id' => $buyer_id
            ])
            ->whereIn('erp_order_details.template_id', $values)
            ->orderBy('erp_order_details.erp_order_id', 'asc')
            ->pluck('erp_orders.po_no', 'erp_orders.erp_id');

            $buyers_array[$buyer_id] = $this->buyer_name;
        }

        $erpid_collection = new Collection();

        foreach ($erp_po_ids as $items) {
            foreach ($items as $key => $item) {
                $erpid_collection->put($key, $item);
            }
        }

        return view(
            'erp.Non-Artwork-Jobmanager.non-artwork-jobcard-home',
            [
                'erp_po_ids' => $erpid_collection->all(),
                'buyers' => $buyers_array,
                'companies' => $companies
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
        $templates = $this->getPermittedTemplates();
        $buyer_name = DB::table('buyers')->where('id', $request['buyer_id'])->value('buyer_name');

        foreach ($templates as $key => $values) {
            $split_key = explode('-', $key);
            $buyer_id = $split_key[0];
            $this->buyer_name = DB::table('buyers')->where('id', $buyer_id)->value('buyer_name');

            $item_names[] = Erp::join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
            ->join(strtolower($buyer_name).'_item_master', 'erp_order_details.template_id', strtolower($buyer_name).'_item_master.id')
            ->join(strtolower($buyer_name).'_template', strtolower($buyer_name).'_template.item_id', strtolower($buyer_name).'_item_master.id')
            ->join('reports_template_level_name', strtolower($buyer_name).'_template.name_in_reports', 'reports_template_level_name.id')
            ->where(['erp_order_details.deleted_at' => null,
                    'erp_order_details.status' => $this->erp_jc_status_id,
                    'erp_order_details.order_status_id' => $this->order_ap_status_id,
                    'erp_order_details.order_qnty_table_name' => 'non_artwork_orders_quanty',
                    'erp_orders.buyer_id' => $request['buyer_id'],
                    'erp_orders.customer_id' => $request['customer_id']])
            ->whereIn('erp_order_details.template_id', $values)
            ->select('reports_template_level_name.name', 'reports_template_level_name.id')
            ->distinct('reports_template_level_name.id')
            ->get();
        }

        $item_names_collection = new Collection();

        foreach ($item_names as $items) {
            foreach ($items as $key => $item) {
                $item_names_collection->put($key, $item);
            }
        }

        return response()->json([
            'error'=> false,
            'message' =>'success', 
            'item_names' => $item_names_collection->all() 
        ],200);	
    }

    public function getReferencesByItemName(Request $request)
    {
        $buyer_name = DB::table('buyers')->where('id', $request['buyer_id'])->value('buyer_name');
        $templates = $this->getPermittedTemplates();

        foreach ($templates as $key => $values) {
            $split_key = explode('-', $key);
            $buyer_id = $split_key[0];
            $this->buyer_name = DB::table('buyers')->where('id', $buyer_id)->value('buyer_name');

            $references[] = Erp::join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
            ->join(strtolower($buyer_name).'_item_master', 'erp_order_details.template_id', strtolower($buyer_name).'_item_master.id')
            ->join(strtolower($buyer_name).'_template', strtolower($buyer_name).'_template.item_id', strtolower($buyer_name).'_item_master.id')
            ->join('reports_template_level_name', strtolower($buyer_name).'_template.name_in_reports', 'reports_template_level_name.id')
            ->where(['erp_order_details.deleted_at' => null,
                    'erp_order_details.status' => $this->erp_jc_status_id,
                    'erp_order_details.order_status_id' => $this->order_ap_status_id,
                    'erp_order_details.order_qnty_table_name' => 'non_artwork_orders_quanty',
                    'erp_orders.buyer_id' => $request['buyer_id'],
                    'erp_orders.customer_id' => $request['customer_id']])
            ->whereIn(strtolower($buyer_name).'_template.name_in_reports', $request['item_name_id'])
            ->whereIn('erp_order_details.template_id', $values)
            ->select('erp_order_details.reference_name', 'erp_order_details.template_id')
            ->distinct('erp_order_details.template_id')
            ->get();
        }

        $references_collection = new Collection();

        foreach ($references as $items) {
            foreach ($items as $key => $item) {
                $references_collection->put($key, $item);
            }
        }

        return response()->json([
            'error'=> false,
            'message' =>'success', 
            'references' => $references_collection->all()
        ],200);		
    }

    public function getStyleOcnBuyerPoByItemName(Request $request)
    {
        $buyer_name = DB::table('buyers')->where('id', $request['buyer_id'])->value('buyer_name');
        $templates = $this->getPermittedTemplates();

        foreach ($templates as $key => $values) {
            $split_key = explode('-', $key);
            $buyer_id = $split_key[0];
            $this->buyer_name = DB::table('buyers')->where('id', $buyer_id)->value('buyer_name');

            $query = Erp::join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
            ->join(strtolower($buyer_name).'_item_master', 'erp_order_details.template_id', strtolower($buyer_name).'_item_master.id')
            ->join(strtolower($buyer_name).'_template', strtolower($buyer_name).'_template.item_id', strtolower($buyer_name).'_item_master.id')
            ->join('reports_template_level_name', strtolower($buyer_name).'_template.name_in_reports', 'reports_template_level_name.id')
            ->where(['erp_order_details.deleted_at' => null,
                    'erp_order_details.status' => $this->erp_jc_status_id,
                    'erp_order_details.order_status_id' => $this->order_ap_status_id,
                    'erp_order_details.order_qnty_table_name' => 'non_artwork_orders_quanty',
                    'erp_orders.buyer_id' => $request['buyer_id'],
                    'erp_orders.customer_id' => $request['customer_id'],])
            ->whereIn(strtolower($buyer_name).'_template.name_in_reports', $request['item_name_id'])
            ->whereIn('erp_order_details.reference_name', $request['reference_name'])
            ->whereIn('erp_order_details.template_id', $values);

            $ocn_numbers[] = $query->select('erp_order_details.ocn_number')
            ->whereNotNull('erp_order_details.ocn_number')
            ->distinct('erp_order_details.ocn_number')
            ->get();

            $buyer_po_number[] = $query->select('erp_order_details.buyer_po_number')
            ->whereNotNull('erp_order_details.buyer_po_number')
            ->distinct('erp_order_details.buyer_po_number')
            ->get();

            $style_no[] = $query->select('erp_order_details.style_no')
            ->whereNotNull('erp_order_details.style_no')
            ->distinct('erp_order_details.style_no')
            ->get();
        }

        $ocn_collection = new Collection();
        $buyer_po_collection = new Collection();
        $style_no_collection = new Collection();

        foreach ($ocn_numbers as $items) {
            foreach ($items as $key => $item) {
                $ocn_collection->put($key, $item);
            }
        }


        foreach ($buyer_po_number as $items) {
            foreach ($items as $key => $item) {
                $buyer_po_collection->put($key, $item);
            }
        }


        foreach ($style_no as $items) {
            foreach ($items as $key => $item) {
                $style_no_collection->put($key, $item);
            }
        }

        return response()->json([
            'error'=> false,
            'message' =>'success', 
            'ocn_numbers' => $ocn_collection->all(), 
            'buyer_po_number' => $buyer_po_collection->all(), 
            'style_no' => $style_no_collection->all() 
        ],200);
    }

        /**
     * Partial CSV data ajax datatable.
     *
     * @return array csv data
     */
    public function getFilteredNatOrders(Request $request)
    {
        if(!$request['erp_id'] && !$request['customer_po']){
            $buyer_name = DB::table('buyers')
            ->where('id', $request['buyer_id'])
            ->value('buyer_name');

            $nat_jc_filtered_data = Erp::join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
            ->join(strtolower($buyer_name).'_item_master', 'erp_order_details.template_id', strtolower($buyer_name).'_item_master.id')
            ->join(strtolower($buyer_name).'_template', strtolower($buyer_name).'_template.item_id', strtolower($buyer_name).'_item_master.id')
            ->join('reports_template_level_name', strtolower($buyer_name).'_template.name_in_reports', 'reports_template_level_name.id')
            ->join('customers', 'customers.id', 'erp_orders.customer_id')
            ->join('non_artwork_orders_quanty', 'non_artwork_orders_quanty.id', 'erp_order_details.order_qnty_table_id')
            ->join('non_artwork_orders', 'non_artwork_orders.id', 'non_artwork_orders_quanty.non_artwork_order_id')
            ->where(['erp_order_details.deleted_at' => null,
                    'erp_order_details.status' => $this->erp_jc_status_id,
                    'erp_order_details.order_status_id' => $this->order_ap_status_id,
                    'erp_order_details.order_qnty_table_name' => 'non_artwork_orders_quanty',
                    'erp_orders.buyer_id' => $request['buyer_id'],
                    'erp_orders.customer_id' => $request['customer_id'],])
            ->whereIn(strtolower($buyer_name).'_template.name_in_reports', $request['item_name_id'])
            ->whereIn('erp_order_details.reference_name', $request['reference_name'])
            ->where(function($query) use ($request){
                if($request['style_number'])
                {
                        $query->orWhereIn('erp_order_details.style_no', $request['style_number']);
                }
                if($request['ocn_number'])
                {
                        $query->orWhereIn('erp_order_details.ocn_number', $request['ocn_number']);
                }
                if($request['buyer_po_number'])
                {
                        $query->orWhereIn('erp_order_details.buyer_po_number', $request['buyer_po_number']);
                }
            })
            ->groupBy('erp_orders.id', 'erp_orders.erp_id', 'customers.company_name', 'erp_orders.po_no', 'erp_order_details.buyer_po_number', 'erp_order_details.style_no', 'erp_order_details.reference_name', 'erp_order_details.ocn_number', 'erp_order_details.uuid', 'erp_order_details.template_id', 'non_artwork_orders.color', 'non_artwork_orders.composition', 'reports_template_level_name.name', 'erp_orders.buyer_id', 'erp_order_details.order_type')
            ->select('erp_orders.id', 'erp_orders.erp_id', 'customers.company_name as customer', DB::raw("'".$buyer_name."'".' as "buyer"'), 'erp_orders.po_no as customer_po', 'erp_order_details.buyer_po_number', 'erp_order_details.style_no', 'erp_order_details.reference_name', 'erp_order_details.ocn_number', 'erp_order_details.uuid', 'erp_order_details.template_id', 'non_artwork_orders.color', 'non_artwork_orders.composition', 'reports_template_level_name.name as item_name', DB::raw("SUM(erp_order_details.order_quantity) as quantity"), 'erp_order_details.order_type')
            ->get();
        } else {
            $buyer_name = DB::table('erp_orders')
            ->join('buyers', 'erp_orders.buyer_id', 'buyers.id')
            ->where('erp_orders.erp_id', $request['erp_id'][0])
            ->value('buyer_name');

            $nat_jc_filtered_data = DB::table('erp_orders')->join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
            ->join(strtolower($buyer_name).'_item_master', 'erp_order_details.template_id', strtolower($buyer_name).'_item_master.id')
            ->join(strtolower($buyer_name).'_template', strtolower($buyer_name).'_template.item_id', strtolower($buyer_name).'_item_master.id')
            ->join('reports_template_level_name', strtolower($buyer_name).'_template.name_in_reports', 'reports_template_level_name.id')
            ->join('customers', 'customers.id', 'erp_orders.customer_id')
            ->join('non_artwork_orders_quanty', 'non_artwork_orders_quanty.id', 'erp_order_details.order_qnty_table_id')
            ->join('non_artwork_orders', 'non_artwork_orders.id', 'non_artwork_orders_quanty.non_artwork_order_id')
            ->where(['erp_order_details.deleted_at' => null,
                    'erp_order_details.status' => $this->erp_jc_status_id,
                    'erp_order_details.order_status_id' => $this->order_ap_status_id,
                    'erp_order_details.order_qnty_table_name' => 'non_artwork_orders_quanty'])
            ->where(function($query) use ($request){
                if($request['erp_id'])
                {
                        $query->orWhereIn('erp_orders.erp_id', $request['erp_id']);
                }
                if($request['customer_po'])
                {
                        $query->orWhereIn('erp_orders.po_no', $request['customer_po']);
                }
            })
            ->groupBy('erp_orders.id', 'erp_orders.erp_id', 'customers.company_name', 'erp_orders.po_no', 'erp_order_details.buyer_po_number', 'erp_order_details.style_no', 'erp_order_details.reference_name', 'erp_order_details.ocn_number', 'erp_order_details.uuid', 'erp_order_details.template_id', 'non_artwork_orders.color', 'non_artwork_orders.composition', 'reports_template_level_name.name', 'erp_orders.buyer_id', 'erp_order_details.order_type')
            ->select('erp_orders.id', 'erp_orders.erp_id', 'customers.company_name as customer', DB::raw("'".$buyer_name."'".' as "buyer"'), 'erp_orders.po_no as customer_po', 'erp_order_details.buyer_po_number', 'erp_order_details.style_no', 'erp_order_details.reference_name', 'erp_order_details.ocn_number', 'erp_order_details.uuid', 'erp_order_details.template_id', 'non_artwork_orders.color', 'non_artwork_orders.composition', 'reports_template_level_name.name as item_name', DB::raw("SUM(erp_order_details.order_quantity) as quantity"), 'erp_order_details.order_type')
            ->get();
        }

        return Datatables::of($nat_jc_filtered_data)
        ->addColumn('Sl_No', function($nat_jc_filtered_data) {
            return '<div class="form-check">
                    <a href="javascript:void(0)">
                        <label>
                            <input class="row-checkbox" name="nat-row-checkbox" type="checkbox" data-erp-id="'.$nat_jc_filtered_data->id.'" data-customer-name="'.$nat_jc_filtered_data->customer.'" data-buyer-name="'.$nat_jc_filtered_data->buyer.'" data-buyer-po="'.$nat_jc_filtered_data->buyer_po_number.'" data-refname="'.$nat_jc_filtered_data->reference_name.'" data-uuid="'.$nat_jc_filtered_data->uuid.'" style="margin-right: 10px;" onchange="return selectNatOrders(this)"></label>
                    </a>
                    </div>';
          })
        ->rawColumns(['Sl_No'])
        ->make(true);
    }

    public function getNatJcOrderDetails(Request  $request){
        if(session()->get('company_name') === "jupitergarmentsaccessories"){
            $jc_metadata = New JobcardMetadata();
            $metadata = $jc_metadata->erpJobcardMetaData();

            $uuid_array         = explode(',', $request['uuid_array']);
            $erp_orders_ids     = explode(',', $request['erp_id_array']);
            $order_dt_array     = array();
            $delivery_dt_array  = array();
            $style_array        = array();
            $ordertype_array    = array();
            $temp_id_array      = array();
            $template_data      = array();
            $ratio_templates    = array();
            $single_templates   = array();
            $print_color_array  = array();
            $material_array     = array();
            $article_array      = array();
            $length_array       = array();
            $width_array        = array();
            $distinct_ref_array = array();
            $common_data_chunk  = array();
            $size_chunk         = array();
            $final_chunk        = array();
            $order_detail_ids   = array();
            $erp_id_array       = array();
            $customer_po_array  = array();
            $color_code         = array();
            $tint_color         = array();

            /** Get erp order details table ids from erp orders table id */
            foreach ($uuid_array as $row => $uuid) {

                $order_detail_id_array = ErpOrderDetails::where([
                    'erp_order_details.uuid' => $uuid,
                    'erp_order_details.status' => $this->erp_jc_status_id,
                    'erp_order_details.order_status_id' => $this->order_ap_status_id
                ])->pluck('erp_order_details.id')->all();

                $order_detail_ids = array_unique(array_merge($order_detail_ids, $order_detail_id_array));
            }
            sort($order_detail_ids);
            
            $order_details_collection = Erp::with(['customer', 'buyer'])
            ->join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
            ->join('non_artwork_orders_quanty', 'non_artwork_orders_quanty.id', 'erp_order_details.order_qnty_table_id')
            ->join('non_artwork_orders', 'non_artwork_orders.id', 'non_artwork_orders_quanty.non_artwork_order_id')
            ->join('company', 'erp_order_details.company_id', 'company.id')
            ->whereIn('erp_order_details.id', $order_detail_ids)
            ->select('company.name as company', 'company.id as company_id', 'company.address as company_address', 'company.logo as company_logo', 'non_artwork_orders.color', 'erp_order_details.*', 'erp_orders.buyer_id', 'erp_orders.customer_id', 'erp_orders.erp_id')
            ->get();

            foreach ($order_details_collection as $key => $order_details) {

                !in_array($order_details->created_at, $order_dt_array, true) ? array_push($order_dt_array, $order_details->created_at) : $order_dt_array;

                !in_array(Carbon::parse($order_details->delivery_date)->format('d-m-Y'), $delivery_dt_array, true) ? array_push($delivery_dt_array, Carbon::parse($order_details->delivery_date)->format('d-m-Y')) : $delivery_dt_array;

                !in_array($order_details->style_no, $style_array, true) ? array_push($style_array, $order_details->style_no) : $style_array;

                !in_array($order_details->order_type, $ordertype_array, true) ? array_push($ordertype_array, $order_details->order_type) : $ordertype_array;

                !in_array($order_details->template_id, $temp_id_array, true) ? array_push($temp_id_array, $order_details->template_id) : $temp_id_array;

                !in_array($order_details->reference_name, $distinct_ref_array, true) ? array_push($distinct_ref_array, $order_details->reference_name) : $distinct_ref_array;

                !in_array($order_details->po_no, $customer_po_array, true) ? array_push($customer_po_array, $order_details->po_no) : $customer_po_array;

                /** Create separate single templates array  */
                // to differenciate reorder and repeatorder having same values
                $unique_id = $order_details->uuid;

                $erp_id_array[$order_details->reference_name][] = $unique_id;

                $single_templates[$order_details->reference_name]['order_data'][$unique_id]['ocn_number'] = $order_details->ocn_number;

                $single_templates[$order_details->reference_name]['order_data'][$unique_id]['erp_id'] = $order_details->erp_id;

                $single_templates[$order_details->reference_name]['order_data'][$unique_id]['buyer_po_number'] = $order_details->buyer_po_number;

                $single_templates[$order_details->reference_name]['order_data'][$unique_id]['template_id'] = $order_details->template_id;

                $single_templates[$order_details->reference_name]['size'][$order_details->size][$unique_id] = $order_details->order_quantity;

                $single_templates[$order_details->reference_name]['order_data'][$unique_id]['style_no'] = $order_details->style_no;

                $single_templates[$order_details->reference_name]['order_data'][$unique_id]['color'] = $order_details->color;

            }

            /** Get templates metadata details */
            if (!empty($temp_id_array)) {
                $template_details = DB::table($order_details_collection[0]['buyer']->template_table)
                    ->select($order_details_collection[0]['buyer']->template_table . '.color', $order_details_collection[0]['buyer']->template_table . '.dimension_height as length', $order_details_collection[0]['buyer']->template_table . '.dimension_width as width', $order_details_collection[0]['buyer']->item_master_table . '.item_name as article', 'raw_material_master.material_name_employee as material',  $order_details_collection[0]['buyer']->template_table . '.material_id')
                    ->join($order_details_collection[0]['buyer']->item_master_table, $order_details_collection[0]['buyer']->item_master_table . '.id', $order_details_collection[0]['buyer']->template_table . '.hierarchy_id')
                    ->join('raw_material_master', 'raw_material_master.id', $order_details_collection[0]['buyer']->template_table . '.material_id')
                    ->whereIn($order_details_collection[0]['buyer']->template_table . '.item_id', $temp_id_array)
                    ->orderBy($order_details_collection[0]['buyer']->template_table . '.id')
                    ->get();

                foreach ($template_details as $template_detail) {
                    !in_array($template_detail->color, $print_color_array, true) ? array_push($print_color_array, $template_detail->color) : $print_color_array;

                    !in_array($template_detail->material, $material_array, true) ? array_push($material_array, $template_detail->material) : $material_array;

                    !in_array($template_detail->article, $article_array, true) ? array_push($article_array, $template_detail->article) : $article_array;

                    !in_array($template_detail->length, $length_array, true) ? array_push($length_array, $template_detail->length) : $length_array;

                    !in_array($template_detail->width, $width_array, true) ? array_push($width_array, $template_detail->width) : $width_array;
                }
            } else {
                return response()->json(['error' => true, 'message' => 'Template details are missing, contact your admin.'], 404);
            }
            
            /** Different size breakup value handling */
            foreach ($single_templates as $template => $template_item) {
                ksort($template_item['order_data']);
                $po_count = sizeof($template_item['order_data']);

                foreach ($template_item['size'] as $size => $qty) {
                    $size_count = sizeof($qty);

                    if ($size_count < $po_count) {
                        /** push 0 qty value for missing/additional size-breakup  */
                        for ($i = 0; $i < sizeof($erp_id_array[$template]); $i++) {
                            $qty = !array_key_exists($erp_id_array[$template][$i], $qty) ? array_merge($qty, [$erp_id_array[$template][$i] => 0]) : $qty;
                        }
                    }
                    ksort($qty);

                    $template_item['size'][$size] = $qty;
                }
                $single_templates[$template] = $template_item;

                /** Split template data array into small chunks (3 elements in each chunks) */
                /** common data chunk */
                $common_data_chunk = array_chunk($template_item['order_data'], 3);
                foreach ($common_data_chunk as $key => $data) {
                    $final_chunk[$template . '_' . $key]['order_data'] = $data;
                }

                foreach ($template_item['size'] as $size => $qty) {
                    /** size-quantity chunk */
                    $size_chunk =  array_chunk($qty, 3, true);
                    foreach ($size_chunk as $key2 => $value2) {
                        /** check if all the size values are 0 or not
                         * remove following if condition if client wants zero qty size in final table.
                         */
                        $is_size_qty_empty = array_filter($value2) != NULL ? 0 : 1;
                        if ($is_size_qty_empty != 1) {
                            $final_chunk[$template . '_' . $key2]['size'][$size . '_' . $key2] = $value2;
                        }
                    }
                }
            }

            // jobcard id format "Itemname(2)FinancialYearERPID(last 5)0001" where  and for old order "JGA-JC-1"
            $workorder_id = $this->jobcard_id_gen_obj->generateJobcardID($article_array, $erp_orders_ids);

            /** Push all array values into template_data array */
            $template_data['order_date']       = Carbon::parse(min($order_dt_array))->format('d-m-Y');
            $template_data['cus_del_date']     = $delivery_dt_array;
            $template_data['today_date']       = Carbon::parse($this->current_date)->format('d-m-Y');
            $template_data['workorder_id']     = $workorder_id;
            $template_data['company']          = $order_details_collection[0]->company;
            $template_data['customer']         = $order_details_collection[0]->customer;
            $template_data['buyer']            = $order_details_collection[0]->buyer;
            $template_data['ordertype_array']  = $ordertype_array;
            $template_data['style_array']      = $style_array;
            $template_data['refnum_array']     = $distinct_ref_array;
            $template_data['color']            = $print_color_array;
            $template_data['material']		   = $material_array;
            $template_data['article']          = $article_array;
            $template_data['length']   		   = $length_array;
            $template_data['width']            = $width_array;
            $template_data['ratio']            = $ratio_templates;
            $template_data['single']           = $final_chunk;
            $template_data['order_detail_ids'] = $order_detail_ids;
            $template_data['customer_po_array'] = $customer_po_array;
            $template_data['color_code']       = $color_code;
            $template_data['tint_color']       = $tint_color;
            $metadata                          = $jc_metadata->erpJobcardMetaData();
            return $template_data;
            return response()->json([
				'error'=> false,
				'message' =>'Success', 
				'template_data' => $template_data,
				'disp_modes' => $metadata['erp_dispatch_mode'],
				'finish_types' => $metadata['erp_finish_type'],
				'machines' => $metadata['erp_machines'],
				'teeth_nos' => $metadata['erp_teeth_no']

			],200);	
        }else {
            return response()->json(['error'=> true,'message' =>'Jobcard for Spectrra Printers currently not available !!'],406);
        }
    }
}