<?php

namespace App\Http\Controllers\ErpUser\NonArtworkJobmanager;

use Auth;
use DataTables;
use App\ErpOrders as Erp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;

class NonArtworkJobCardFiltersController extends Controller
{
	private $erp_jc_status_id;
	private $order_ap_status_id;

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

		// Get Erp status table id for 'Moved To JC Queue' status.
		$this->erp_jc_status_id = DB::table('erp_status')->where('status_name', 'Moved To JC Queue')->value('id');
		 
		// Get order status table id for 'Order Approved' status.
		$this->order_ap_status_id = DB::table('orders_status')->where('status_name', 'Order Approved')->value('id');
	}

    public function getPermittedTemplates()
    {
        $erp_user_data  = DB::table('erpusers_template_mapping')
        ->join('buyers', 'erpusers_template_mapping.buyer', 'buyers.id')
        ->where('erpusers_template_mapping.company', 1)
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
        $new_orders = $this->getPermittedTemplates();
        $buyers_array = array();

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
            $buyer_name = DB::table('buyers')->where('id', $request['buyer_id'])->value('buyer_name');

            $nat_jc_filtered_data = Erp::join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
            ->join(strtolower($buyer_name).'_item_master', 'erp_order_details.template_id', strtolower($buyer_name).'_item_master.id')
            ->join(strtolower($buyer_name).'_template', strtolower($buyer_name).'_template.item_id', strtolower($buyer_name).'_item_master.id')
            ->join('customers', 'customers.id', 'erp_orders.customer_id')
            ->join('reports_template_level_name', strtolower($buyer_name).'_template.name_in_reports', 'reports_template_level_name.id')
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
            ->select('erp_orders.erp_id', 'customers.company_name as customer', DB::raw("'".$buyer_name."'".' as "buyer"'), 'erp_orders.po_no as customer_po', 'erp_order_details.buyer_po_number', 'erp_order_details.style_no', 'erp_order_details.reference_name', 'erp_order_details.ocn_number', 'reports_template_level_name.name as item_name', 'erp_order_details.order_quantity as quantity', 'erp_order_details.order_qnty_table_id')
            ->whereNotNull('erp_order_details.ocn_number')
            ->distinct('erp_order_details.ocn_number')
            ->get();
        } else {

            $nat_jc_filtered_data = Erp::join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
            ->join('customers', 'customers.id', 'erp_orders.customer_id')
            ->join('buyers', 'buyers.id', 'erp_orders.buyer_id')
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
            ->select('erp_orders.erp_id', 'customers.company_name as customer', 'buyers.buyer_name as buyer', 'erp_orders.po_no as customer_po', 'erp_order_details.buyer_po_number', 'erp_order_details.style_no', 'erp_order_details.reference_name', 'erp_order_details.ocn_number', 'erp_order_details.order_quantity as quantity', 'erp_order_details.order_qnty_table_id')
            ->whereNotNull('erp_order_details.ocn_number')
            ->distinct('erp_order_details.ocn_number')
            ->get();
        }

        foreach ($nat_jc_filtered_data as $data) {
            $nat_order_data = DB::table('non_artwork_orders_quanty')
            ->join('non_artwork_orders', 'non_artwork_orders_quanty.non_artwork_order_id', 'non_artwork_orders.id')
            ->where('non_artwork_orders_quanty.id', $data->order_qnty_table_id)
            ->select('color', 'composition')
            ->first();

            $data['color'] = $nat_order_data->color;
            $data['composition'] = $nat_order_data->composition;

            if($request['erp_id'] || $request['customer_po']){
               
                $data['item_name'] = DB::table('non_artwork_orders_quanty')
                ->join('non_artwork_orders', 'non_artwork_orders_quanty.non_artwork_order_id', 'non_artwork_orders.id')
                ->join(strtolower($data->buyer).'_item_master', 'non_artwork_orders.template_id', strtolower($data->buyer).'_item_master.id')
                ->join(strtolower($data->buyer).'_template', strtolower($data->buyer).'_template.item_id', strtolower($data->buyer).'_item_master.id')
                ->join('reports_template_level_name', strtolower($data->buyer).'_template.name_in_reports', 'reports_template_level_name.id')
                ->where('non_artwork_orders_quanty.id', $data->order_qnty_table_id)
                ->value('reports_template_level_name.name as item_name');
            }
        }

        return Datatables::of($nat_jc_filtered_data)
        ->addIndexColumn()
        ->make(true);
    }
}
