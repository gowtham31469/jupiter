<?php
namespace App\Http\Controllers\ErpUser\Jobmanager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Response;
use View;
use Auth;
use DB;
use Session;
use App\ErpOrders as Erp;
use App\ErpOrderDetails as ErpOrderDetails;
use Carbon\Carbon;
use PDF;
use App\Classes\JobcardPDFHeaderFooter;
use App\Classes\FPDF_Merge;
use File;
use Config;

class JobManagerController extends Controller
{
	private $current_date;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		$this->middleware('erpuser');
		// get the current time
		$this->current_date = Carbon::now('Asia/Kolkata')->format('d-m-Y');
   
		}
		
    /**
     * Show the Jobcard  Manager List View.
     * @author [Gowtham] <[gowtham@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
	public function index()
	{
		try {
			$customer_id = Auth::guard('erpuser')->id();
			
			$companies   = 	DB::table('erpuser_company_mapping')
									->join('company','erpuser_company_mapping.company','company.id')
									->where('erpuser_company_mapping.erp_user',$customer_id)
									->select('erpuser_company_mapping.*','company.name', 'company.logo')
									->get();
			$buyers      = 	DB::table('erpuser_buyer')
									->where('erpuser_buyer.erp_user',$customer_id)
									->pluck('erpuser_buyer.buyer')
									->all();

			if($companies)
			{
				$company_id   = DB::table('company')
								->select('company.*')
								->where('company.id', '=', $companies{0}->company)
								->first();
			}
			else
			{
				return view('erp.home');
			}

			// Get Erp status table id for 'Moved To JC Queue' status.
			$erp_status_id = DB::table('erp_status')->where('status_name', 'ilike', 'Moved To JC Queue')->value('id');
		 
			// Get order status table id for 'Order Approved' status.
			$order_status_id = DB::table('orders_status')->where('status_name', 'ilike', 'Order Approved')->value('id');
			
			if(session()->get('company') == "" && session()->get('company') == null)
			{
				session()->put('company', $company_id->id);
				session()->put('company_name', strtolower( str_replace(' ','',$company_id->name)) );
			}
			
			$templates   = 		DB::table('erpusers_template_mapping')
									->join('buyers','erpusers_template_mapping.buyer','buyers.id')
									->where('erpusers_template_mapping.company',session()->get('company'))
									->where('erpusers_template_mapping.erp_user',$customer_id)
									->select('erpusers_template_mapping.*','buyers.template_table','buyers.item_master_table','buyers.id as buyer_id')
									->get();
			
			$new_orders = array();
			$i=1;
			foreach($templates as $value)
			{
				$arr_template =  DB::table($value->template_table)
								->where('hierarchy_id',$value->templates)
								->pluck('item_id');
				if($arr_template)
				{
					$new_orders[$value->buyer_id."-".$i] = $arr_template;
				}
				$i++;
			}
			
			$orders_erp = array();
			
			foreach($new_orders as $key => $values)
			{
				foreach($values as $valuess)
				{
					$erp_order_details = Erp::join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
											->join('customers', 'erp_orders.customer_id', 'customers.id')
											->join('buyers', 'erp_orders.buyer_id', 'buyers.id')
											->join('erp_status', 'erp_orders.status', 'erp_status.id')
						                    ->join('tesco_template', 'tesco_template.item_id', 'erp_order_details.template_id')
											->where('erp_order_details.template_id', '=', $valuess)
											// ->where('erp_orders.status', '=', $erp_status_id)
											->where('erp_order_details.status', '=', $erp_status_id)
											->where('erp_order_details.order_status_id', '=', $order_status_id)
											->whereIn('erp_orders.buyer_id', $buyers)
											->orderBy('erp_order_details.erp_order_id', 'asc')
											->groupBy('erp_order_details.template_id', 'erp_orders.id', 'customers.company_name', 'erp_status.status_name', 'buyers.id', 'erp_order_details.reference_name', 'erp_order_details.style_no', 'erp_order_details.buyer_po_number', 'erp_order_details.delivery_date', 'erp_order_details.erp_order_id','tesco_template.template_file', 'erp_order_details.order_type', 'erp_order_details.created_at')
											->select('erp_orders.*','erp_order_details.reference_name', 'erp_order_details.style_no', 'erp_order_details.buyer_po_number', 'erp_order_details.delivery_date', 'customers.company_name', 'erp_status.status_name', 'buyers.buyer_name', 'buyers.id as buyer_id','tesco_template.template_file', 'erp_order_details.order_type', 'erp_order_details.created_at')
											->get();
					
					if(count($erp_order_details) > 0)
					{
						foreach($erp_order_details as $erp_order_det)
						{
							$orders_erp[] = $erp_order_det;
						}
						
					}
				}
				
			}
			/**
			 * Dropdown values
			 */
			$erp_dispatch_mode = DB::table('erp_dispatch_mode')->where('is_enabled','=',true)->pluck('disp_mode','id')->all();
			$erp_finish_type = DB::table('erp_finish_type')->where('is_enabled','=',true)->pluck('finish_type','id')->all();
			$erp_machines = DB::table('erp_machines')->where('is_enabled','=',true)->pluck('machine','id')->all();
			$erp_teeth_no = DB::table('erp_teeth_no')->where('is_enabled','=',true)->pluck('teeth_no','id')->all();

			asort($orders_erp);
			return view('erp.Jobmanager.jobmanager', ['orders' => $orders_erp, 'companies' => $companies, 'disp_modes' => $erp_dispatch_mode, 'finish_types' => $erp_finish_type, 'machines' => $erp_machines, 'teeth_nos' => $erp_teeth_no]);
		}
		catch (\Exception $e)
		{
		return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
		}
	}
	/**
     * Get order deatils.
     * @param order details id
     * @author Gowtham <gowtham@wizroots.com>
     * @return \Illuminate\Http\Response
     */
	public function getOrderDetails(Request $request)
	{
		$ref_name = $request['ref_name'];
		$style_no = $request['style_no'];
		$buyer_no = $request['buyer_no'];
		$id = $request['id'];
		$order_type = $request['order_type'];
		$created_at = $request['created_at'];
		$order_details  = 	DB::table('erp_order_details')
									->where('erp_order_details.style_no',$style_no)
									->where('erp_order_details.erp_order_id',$id)
									->where('erp_order_details.reference_name',$ref_name)
									->where('erp_order_details.buyer_po_number',$buyer_no)
									->where('erp_order_details.order_type',$order_type)
									->where('erp_order_details.status','!=',4)
									->orderBy('erp_order_details.id', 'desc')
									->select('*')
									->get();
		
		foreach($order_details as $value)
		{
			$order_details_omp  = 	DB::table($value->order_qnty_table_name)
									->where('id',$value->order_qnty_table_id)
									->select('*')
									->get();
			
			
			$order_table = str_replace("_quanty","",$value->order_qnty_table_name);
			$order_id  = strtolower(str_replace(" ","",$ref_name).'_order_id');
			
			$order_omp  = 	DB::table($order_table)
				->join('tesco_department',$order_table.'.tesco_department_id', 'tesco_department.id')
				->join('tesco_brand',$order_table.'.tesco_brand_id', 'tesco_brand.id')
				->join('tesco_suppliers',$order_table.'.tesco_metadata_supplier_id', 'tesco_suppliers.id')
									->where($order_table.'.id',$order_details_omp{0}->$order_id)
									->select($order_table.'.*','tesco_department.department','tesco_brand.brand','tesco_suppliers.supplier_name')
									->get();
			
			$value->tpnd=$order_details_omp{0}->tpnd;
			$value->itf_barcode=$order_details_omp{0}->itf_barcode;
			$value->units_per_carton=$order_details_omp{0}->units_per_carton;
			$value->cases_per_carton=$order_details_omp{0}->cases_per_carton;
			$value->ean_barcode=$order_details_omp{0}->ean_barcode;
			$value->size=$order_details_omp{0}->size;
			$value->case_size=$order_details_omp{0}->case_size;
			$value->orms_tpn=$order_details_omp{0}->orms_tpn;
			
			$value->zone=$order_omp{0}->zone;
			$value->po_number=$order_omp{0}->po_number;
			$value->uk_style_ref=$order_omp{0}->uk_style_ref;
			$value->ce_style_ref=$order_omp{0}->ce_style_ref;
			$value->season_code=$order_omp{0}->season_code;
			$value->short_description=$order_omp{0}->short_description;
			$value->eqos_site_code=$order_omp{0}->eqos_site_code;
			$value->manufacture_date=$order_omp{0}->manufacture_date;
			$value->department=$order_omp{0}->department;
			$value->brand=$order_omp{0}->brand;
			$value->supplier_name=$order_omp{0}->supplier_name;
			$value->rfid_compliant=$order_omp{0}->rfid_compliant;
			$value->tag_at_source=$order_omp{0}->tag_at_source;
			
		}
		
		return response()->json(['error'=> true,'order_details' =>$order_details],200);
	}
	/**
     * Get order deatils for job card template creation by selected ids.
     * @param order details id
     * @author Praveen <praveen@wizroots.com>
     * @return \Illuminate\Http\Response
     */
    public function getOrderDetailsByIds(Request $request)
    {
      try {
			$erp_orders_ids     = explode(',', $request['orders']);
			$selected_ref_arrays= explode(',', $request['ref_array']);
			$buyer_pos          = explode(',', $request['buyer_po_array']);
			$order_type         = explode(',', $request['order_type_array']);
			$created_at         = explode(',', $request['created_at_array']);
			$buyers_array       = array();
			$customers_array    = array();
			$company_array      = array();
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
			$single_temp_data   = array();
			$common_data_chunk  = array();
			$size_chunk         = array();
			$final_chunk        = array();
			$order_detail_ids   = array();
			$erp_id_array       = array();

			/** Get erp order details table ids from erp orders table id */
			foreach($erp_orders_ids as $row => $erp_orders_id)
			{
				$order_detail_id_array = DB::table('erp_order_details')
									->where('erp_order_details.erp_order_id', $erp_orders_id)
									->where('erp_order_details.reference_name', $selected_ref_arrays[$row])
									->where('erp_order_details.buyer_po_number', $buyer_pos[$row])
									->where('erp_order_details.order_type', $order_type[$row])
									->where('erp_order_details.created_at', $created_at[$row])
									->pluck('erp_order_details.id')->all();

				$order_detail_ids = array_unique(array_merge($order_detail_ids,$order_detail_id_array));
			}
 			sort($order_detail_ids);

			$workorder_id = DB::table('jobcard_main_table')->max('id')+1;

			foreach($order_detail_ids as $key => $order_detail_id)
			{
				$order_details = Erp::select('erp_orders.*', 'erp_order_details.*')
									->join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
									->where('erp_order_details.id', '=', $order_detail_id)
									->first();
				
				!in_array($order_details->buyer_id, $buyers_array)?array_push($buyers_array,$order_details->buyer_id):$buyers_array;

				!in_array($order_details->customer_id, $customers_array)?array_push($customers_array,$order_details->customer_id):$customers_array;

				!in_array($order_details->company_id, $company_array)?array_push($company_array,$order_details->company_id):$company_array;

				!in_array($order_details->created_at, $order_dt_array)?array_push($order_dt_array,$order_details->created_at):$order_dt_array;
				
				!in_array(Carbon::parse($order_details->delivery_date)->format('d-m-Y'), $delivery_dt_array)?array_push($delivery_dt_array,Carbon::parse($order_details->delivery_date)->format('d-m-Y')):$delivery_dt_array;

				!in_array($order_details->style_no, $style_array)?array_push($style_array,$order_details->style_no):$style_array;

				!in_array($order_details->order_type, $ordertype_array)?array_push($ordertype_array,$order_details->order_type):$ordertype_array;

				!in_array($order_details->template_id, $temp_id_array)?array_push($temp_id_array,$order_details->template_id):$temp_id_array;

				!in_array($order_details->reference_name, $distinct_ref_array)?array_push($distinct_ref_array,$order_details->reference_name):$distinct_ref_array
				;

				/** Create separate ratio/single templates array  */
				if($order_details->template_type == "ratio")
				{
					$ratio_templates[$order_details->reference_name][] = DB::table('erp_order_details')->select('erp_order_details.order_quantity', $order_details->order_qnty_table_name.'.ratio_pack_type', $order_details->order_qnty_table_name.'.id as qty_table_id',  'erp_order_details.reference_name', 'erp_order_details.ocn_number', 'erp_orders.erp_id', 'erp_order_details.id as erp_order_details_id', 'erp_order_details.template_id', 'erp_order_details.buyer_po_number', 'erp_order_details.order_type', 'erp_order_details.created_at')
						->join($order_details->order_qnty_table_name, 'erp_order_details.order_qnty_table_id', $order_details->order_qnty_table_name.'.id')
						->join('erp_orders', 'erp_order_details.erp_order_id', 'erp_orders.id')
						->where('erp_order_details.id', '=', $order_detail_id)
						->first();
				}
				else
				{
					$single_temp_data = Erp::select('erp_order_details.ocn_number', 'erp_orders.erp_id', 'erp_order_details.id as erp_order_details_id', 'erp_orders.id as erp_table_id', 'erp_order_details.size', 'erp_order_details.order_quantity', 'erp_order_details.reference_name', 'erp_order_details.template_id', 'erp_order_details.buyer_po_number', 'erp_order_details.order_type', 'erp_order_details.created_at')
								->join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
								->where('erp_order_details.id', '=', $order_detail_id)
								->first();
					
					// to differnciate reorder and repeatorder having same values
					$created_time = str_replace(':','', Carbon::parse($single_temp_data->created_at, 'Asia/Kolkata')->toTimeString());
					$created_dt   = str_replace('-','', Carbon::parse($single_temp_data->created_at, 'Asia/Kolkata')->toDateString());
					$created_at_key = $created_dt.$created_time;

					$erp_id_array[$single_temp_data->reference_name][] = $single_temp_data->erp_id.'-'.$single_temp_data->buyer_po_number.'-'.$single_temp_data->order_type.'-'.$created_at_key;

					$single_templates[$single_temp_data->reference_name]['order_data'][$single_temp_data->erp_id.'-'.$single_temp_data->buyer_po_number.'-'.$single_temp_data->order_type.'-'.$created_at_key]['ocn_number'] = $single_temp_data->ocn_number;
					$single_templates[$single_temp_data->reference_name]['order_data'][$single_temp_data->erp_id.'-'.$single_temp_data->buyer_po_number.'-'.$single_temp_data->order_type.'-'.$created_at_key]['erp_id'] = $single_temp_data->erp_id;
					$single_templates[$single_temp_data->reference_name]['order_data'][$single_temp_data->erp_id.'-'.$single_temp_data->buyer_po_number.'-'.$single_temp_data->order_type.'-'.$created_at_key]['buyer_po_number'] = $single_temp_data->buyer_po_number;
					$single_templates[$single_temp_data->reference_name]['order_data'][$single_temp_data->erp_id.'-'.$single_temp_data->buyer_po_number.'-'.$single_temp_data->order_type.'-'.$created_at_key]['template_id'] = $single_temp_data->template_id;
					$single_templates[$single_temp_data->reference_name]['size'][$single_temp_data->size][$single_temp_data->erp_id.'-'.$single_temp_data->buyer_po_number.'-'.$single_temp_data->order_type.'-'.$created_at_key]= $single_temp_data->order_quantity;
				}
			}

			/** Same buyer or not validation */
			$buyer_first_val = current($buyers_array);
			foreach ($buyers_array as $val) 
			{
				if ($buyer_first_val !== $val) {
					return response()->json(['error'=> true,'message' =>'Different Buyers Not Acceptable'],406);
				} else {
					$buyer = DB::table('buyers')->select('buyer_name', 'id as buyer_id', 'template_table', 'item_master_table')->where('id', '=', $buyer_first_val)->first();
					/** Dynamic artwork template table and item master table (Based on buyer) */
					$template_table    = $buyer->template_table;
					$item_master_table = $buyer->item_master_table;
				}
			}

			/** Unset unwanted values from buyers array */
			unset($buyer->template_table);
			unset($buyer->item_master_table);

			/** Same customer or not validation */
			$customer_first_val = current($customers_array);
			foreach ($customers_array as $val) 
			{
				if ($customer_first_val !== $val) {
					return response()->json(['error'=> true,'message' =>'Different Customers Not Acceptable'],406);
				} else {
				$customer = DB::table('customers')->select('short_name as customer_name', 'id as customer_id')->where('id', '=', $customer_first_val)->first();
				}
			}

			/** Same company or not validation */
			$company_first_val = current($company_array);
			foreach ($company_array as $val) 
			{
				if ($company_first_val !== $val) {
					return response()->json(['error'=> true,'message' =>'Different Companies Not Acceptable'],406);
				} else {
				$company = DB::table('company')->select('name as company', 'id as company_id', 'address as company_address', 'logo as company_logo')->where('id', '=', $company_first_val)->first();
				}
			}
			
			/** Get templates metadata details */
			foreach($temp_id_array as $template_id)
			{
				$template_details = DB::table($template_table)
											->select($template_table.'.color', $template_table.'.dimension_height as length', $template_table.'.dimension_width as width', $item_master_table.'.item_name as article', 'raw_material_master.material_name_employee as material',  $template_table.'.material_id')
											->join($item_master_table, $item_master_table.'.id', $template_table.'.hierarchy_id')
											->join('raw_material_master', 'raw_material_master.id', $template_table.'.material_id')
											->where($template_table.'.item_id', '=', $template_id)
											->first();

				if($template_details)
				{
					!in_array($template_details->color, $print_color_array)?array_push($print_color_array,$template_details->color):$print_color_array;

					!in_array($template_details->material, $material_array)?array_push($material_array,$template_details->material):$material_array;

					!in_array($template_details->article, $article_array)?array_push($article_array,$template_details->article):$article_array;

					!in_array($template_details->length, $length_array)?array_push($length_array,$template_details->length):$length_array;

					!in_array($template_details->width, $width_array)?array_push($width_array,$template_details->width):$width_array;
				}
				else 
				{
					return response()->json(['error'=> true,'message' =>'Template details are missing, contact your admin.'],404);
				}
			}
			
			/** Different size breakup value handling */
			foreach($single_templates as $template => $template_item)
			{
				ksort($template_item['order_data']);
				$po_count = sizeof($template_item['order_data']);

				foreach($template_item['size'] as $size => $qty)
				{
					$size_count = sizeof($qty);

					if($size_count < $po_count)
					{
						/** push 0 qty value for missing/additional size-breakup  */
						for($i = 0; $i < sizeof($erp_id_array[$template]); $i++)
						{
							$qty = !array_key_exists($erp_id_array[$template][$i], $qty)?array_merge($qty , [$erp_id_array[$template][$i] => 0]):$qty;
						}
					}
					ksort($qty);

					$template_item['size'][$size] = $qty;
				}
				$single_templates[$template] = $template_item;

				/** Split template data array into small chunks (3 elements in each chunks) */
				/** common data chunk */
				$common_data_chunk = array_chunk($template_item['order_data'], 3);
				foreach($common_data_chunk as $key => $data)
				{
					$final_chunk[$template.'_'.$key]['order_data'] = $data;
				}

				foreach($template_item['size'] as $size => $qty)
				{
					/** size-quantity chunk */
					$size_chunk =  array_chunk($qty, 3, true);
					foreach($size_chunk as $key2 => $value2)
					{
						/** check if all the size values are 0 or not
						 * remove following if condition if client wants zero qty size in final table.
						*/
						$is_size_qty_empty = array_filter($value2) != NULL?0:1;
						if($is_size_qty_empty != 1)
						{
							$final_chunk[$template.'_'.$key2]['size'][$size.'_'.$key2] = $value2;
						}
					}
				}
			}

			/** Push all array values into template_data array */
			$template_data['order_date']       = Carbon::parse(min($order_dt_array))->format('d-m-Y');
			$template_data['cus_del_date']     = $delivery_dt_array;
			$template_data['today_date']       = $this->current_date;
			$template_data['workorder_id']     = $workorder_id;
			$template_data['company']          = $company;
			$template_data['customer']         = $customer;
			$template_data['buyer']            = $buyer;
			$template_data['ordertype_array']  = $ordertype_array;
			$template_data['style_array']      = $style_array;
			$template_data['refnum_array']     = $distinct_ref_array;
			$template_data['color']            = $print_color_array;
			$template_data['material']		     = $material_array;
			$template_data['article']          = $article_array;
			$template_data['length']   		     = $length_array;
			$template_data['width']            = $width_array;
			$template_data['ratio']            = $ratio_templates;
			$template_data['single']           = $final_chunk;
			$template_data['order_detail_ids'] = $order_detail_ids;

			return response()->json(['error'=> false,'message' =>'Success', 'template_data' => $template_data],200);
  
          } catch (\Exception $e)
          {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
	}
	
	/**
     * To store Jobcard data.
     * @author [Praveen] <[praveen@wizroots.com]>
     * @return \Illuminate\Http\Response , pdf file path
     */
	public function storeJobcardData(Request $request)
	{
		try {
				$company_id      = $request['company_id'];
				$workorder_id    = $request['workorder_id'];
				$created_date    = $request['created_date'] == ''?NULL:Carbon::parse($request['created_date'],'Asia/Kolkata')->toDateString();
				$article         = $request['article'];
				$material        = $request['material'];
				$customer_id     = $request['customer_id'];
				$style_no        = $request['style_no'];
				$order_type      = $request['order_type'];
				$buyer_id        = $request['buyer_id'];
				$reference       = $request['reference'];
				$disp_mode       = $request['disp_mode'];
				$order_date      = $request['order_date'] == ''?NULL:Carbon::parse($request['order_date'],'Asia/Kolkata')->toDateString();
				$length          = $request['length'];
				$repeat_gap      = $request['repeat_gap'];
				$machine         = $request['machine'];
				$cus_del_date    = $request['cus_del_date'] == ''?NULL:Carbon::parse($request['cus_del_date'],'Asia/Kolkata')->toDateString();
				$roll_width      = $request['roll_width'];
				$width           = $request['width'];
				$film_no         = $request['film_no'];
				$invoice_date    = $request['invoice_date'] == ''?NULL:Carbon::parse($request['invoice_date'],'Asia/Kolkata')->toDateString();
				$length_side     = $request['length_side'];
				$excess          = $request['excess'];
				$teeth_no        = $request['teeth_no'];
				$inv_no          = $request['inv_no'];
				$no_sticker      = $request['no_sticker'];
				$finish_type     = $request['finish_type'];
				$print_color     = $request['print_color'];
				$compiled_by     = $request['compiled_by'];
				$barcode_read    = $request['barcode_read'];
				$operator        = $request['operator'];
				$checker         = $request['checker'];
				$authorized_by   = $request['authorized_by'];
				$axicon_verifier = $request['axicon_verifier'];
				$approved_by     = $request['approved_by'];
				$packer          = $request['packer'];
				$approval_date   = $request['approval_date'] == ''?NULL:Carbon::parse($request['approval_date'],'Asia/Kolkata')->toDateString();
				$roll_issued_by  = $request['roll_issued_by'];
				$cutter          = $request['cutter'];
				$cartons         = $request['cartons'];
				$final_json_obj  = $request['final_json_obj'];
				$erp_orders_ids  = explode(',', $request['erp_orders_ids']);
			  $selected_ref    = explode(',', $request['selected_ref_array']);
				$buyer_pos       = explode(',', $request['selected_buyer_po_array']);
				$order_types     = explode(',', $request['order_type_array']);
				$created_at      = explode(',', $request['created_at_array']);

				$current_timestamp = Carbon::now('Asia/Kolkata')->toDateTimeString();

				if(isset($company_id) && isset($workorder_id) && isset($created_date) && isset($article) && isset($material) && isset($customer_id) && isset($buyer_id) && isset($disp_mode) && isset($order_date) && isset($machine) && isset($cus_del_date)  && isset($finish_type)  && isset($compiled_by) && isset($final_json_obj) && isset($material))
				{
					// Get Erp status table id for 'Jobcard Created' status.
					$erp_status_id = DB::table('erp_status')->where('status_name', 'ilike', 'Jobcard Created')->value('id');
					$store_jobcard_id = DB::table('jobcard_main_table')->insertGetId([
						'work_order_id'          => $workorder_id,
						'company_id'             => $company_id,
						'customer_id'            => $customer_id,
						'buyer_id'               => $buyer_id,
						'created_date'           => $created_date,
						'material'               => $material,
						'article'                => $article,
						'style_number'           => $style_no,
						'reference'              => $reference,
						'order_type'             => $order_type,
						'dispatch_mode'          => $disp_mode,
						'order_date'             => $order_date,
						'length'                 => $length,
						'repeat_gap'             => $repeat_gap,
						'machine'                => $machine,
						'customer_del_date'      => $cus_del_date,
						'width'                  => $width,
						'roll_width'             => $roll_width,
						'film_no'                => $film_no,
						'invoice_date'           => $invoice_date,
						'length_side'            => $length_side,
						'excess_percentage'      => $excess,
						'teeth_no'               => $teeth_no,
						'invoice_num'            => $inv_no,
						'no_of_stickers'         => $no_sticker,
						'finish_type'            => $finish_type,
						'print_color'            => $print_color,
						'compiled_by'            => $compiled_by,
						'barcode_read'           => $barcode_read,
						'operator'               => $operator,
						'checker'                => $checker,
						'authorized_by'          => $authorized_by,
						'axicon_verifier'        => $axicon_verifier,
						'approved_by'            => $approved_by,
						'packer'                 => $packer,
						'approval_date'          => $approval_date,
						'roll_issued_by'         => $roll_issued_by,
						'cutter'                 => $cutter,
						'cartons'                => $cartons,
						'template_quantity_json' => $final_json_obj,
						'created_at'             => $current_timestamp,
						'updated_at'             => $current_timestamp
					  ]);
					
					/** Store jobcard settings and excess details**/
					if($store_jobcard_id != '')
					{
						if (!empty($request['single_ref'])) 
						{
							
							$single_ref      = explode(',', $request['single_ref']);
							$act_excess 	 = explode(',', $request['act_excess']);
							$sticker_mtr 	 = explode(',', $request['sticker_mtr']);
							$settings        = explode(',', $request['settings']);
							$total_mtrs 	 = explode(',', $request['total_mtrs']);

							$i = 0;
							foreach($single_ref as $value)
							{
								$store_jobcard_details = DB::table('jobcard_excess_details')->insert([
									'jobcard_main_table_id'   => $store_jobcard_id,
									'ref_name'                => $single_ref[$i],
									'mtrs_for_stickers'       => $sticker_mtr[$i],
									'actual_excess'           => $act_excess[$i],
									'settings'                => $settings[$i],
									'total_mtrs'              => $total_mtrs[$i],
									'created_at'              => $current_timestamp,
									'updated_at'              => $current_timestamp
								]);
								$i++;
							}
						}
						else
						{
							$single_ref      = explode(',', $request['single_ref']);
							$act_excess 	 = explode(',', $request['act_excess']);
							$sticker_mtr 	 = explode(',', $request['sticker_mtr']);
							$settings        = explode(',', $request['settings']);
							$total_mtrs 	 = explode(',', $request['total_mtrs']);
						}

						/** Update jobcard created status erp order details table */
						foreach($erp_orders_ids as $row => $erp_orders_id)
						{
							DB::table('erp_order_details')
							->where('erp_order_details.erp_order_id', $erp_orders_id)
							->where('erp_order_details.reference_name', 'ilike', $selected_ref[$row])
							->where('erp_order_details.buyer_po_number', 'ilike', $buyer_pos[$row])
							->where('erp_order_details.order_type', 'ilike', $order_types[$row])
							->where('erp_order_details.created_at', '=', $created_at[$row])
							->update([
								'status'  => $erp_status_id
							]);
						}

						/** Update jobcard created status erp orders table(iff, all the orders in the order details table have same status. ie, after creating jobcard for all orders) */
						foreach($erp_orders_ids as $row => $erp_orders_id)
						{
							$erp_order_table_status = DB::table('erp_order_details')
							    ->where('erp_order_details.erp_order_id', $erp_orders_id)
							    ->pluck('status')->all();
							
							if(count(array_unique($erp_order_table_status)) === 1)
							{
								DB::table('erp_orders')
									->where('erp_orders.id', $erp_orders_id)
									->update([
										'status'  => $erp_order_table_status[0]
									]);
							}
						}

						$buyer    = DB::table('buyers')->select('buyer_name')->where('id', '=', $buyer_id)->first();
						$customer = DB::table('customers')->select('short_name as customer_name')->where('id', '=', $customer_id)->first();
						$company  = DB::table('company')->select('name as company', 'address as company_address', 'logo as company_logo')->where('id', '=', $company_id)->first();

						$erp_dispatch_mode = DB::table('erp_dispatch_mode')->where('id','=', $disp_mode)->value('disp_mode');
						$erp_finish_type   = DB::table('erp_finish_type')->where('id','=', $finish_type)->value('finish_type');
						$erp_machine       = DB::table('erp_machines')->where('id','=', $machine)->value('machine');
						$erp_teeth_no 	   = DB::table('erp_teeth_no')->where('id','=', $teeth_no)->value('teeth_no');

						/** [ Creating new object for JobcardPDFHeaderFooter() class ] */
						$format_approval_date  = $approval_date == ''?NULL:Carbon::parse($approval_date)->format('d-m-Y');
						$formated_inv_date     = $invoice_date == ''?NULL:Carbon::parse($invoice_date)->format('d-m-Y');
						$formated_created_date = $created_date == ''?NULL:Carbon::parse($created_date)->format('d-m-Y');
						$formated_order_date   = $order_date == ''?NULL:Carbon::parse($order_date)->format('d-m-Y');
						$formated_cus_del_date = $cus_del_date == ''?NULL:Carbon::parse($cus_del_date)->format('d-m-Y');
						
						$pdf = new JobcardPDFHeaderFooter(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
						$pdf->setData($company, $workorder_id, $formated_created_date, $article, $material, $compiled_by, $barcode_read, $operator, $checker, $authorized_by, $axicon_verifier, $approved_by, $packer, $format_approval_date, $roll_issued_by, $cutter, $cartons, $single_ref, $act_excess, $sticker_mtr, $settings, $total_mtrs);		

						$pdf->SetPrintHeader(true);
						$pdf->SetPrintFooter(true);  

						//margins
						$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
						$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
						$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
						
						// set header and footer fonts
						$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
						$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
						
						// /** Handle Page break */
						// if ($pdf->last_page_flag)
						// {
								// set auto page breaks
						    $pdf->SetAutoPageBreak(TRUE, 40);
		
						// }
						// else 
						// {
						// 	  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
						// }
						

						// set image scale factor
						$pdf->setImageScale(1.8);

						// set font
						$pdf->SetFont('helvetica', '', 8);

						// add a page
						$pdf->AddPage('P', 'A4');

						// set some text to print
						$view = View::make('erp/Jobmanager/jobcard-pdf-template')
								->with([
										'buyer' 		     => $buyer,
										'customer' 		   => $customer,
										'company'		     => $company,
										'workorder_id'   => $workorder_id,
										'created_date'   => $formated_created_date,
										'article'	       => $article,
										'material'		   => $material,
										'style_no'		   => $style_no,
										'order_type'     => $order_type,
										'reference'		   => $reference,
										'disp_mode'	     => $erp_dispatch_mode,
										'order_date'	   => $formated_order_date,
										'length'		     => $length,
										'repeat_gap'	   => $repeat_gap,
										'machine'		     => $erp_machine,
										'cus_del_date' 	 => $formated_cus_del_date,
										'roll_width'	   => $roll_width,
										'width'	  		   => $width,
										'film_no'		     => $film_no, 
										'invoice_date'   => $formated_inv_date,
										'length_side'	   => $length_side,
										'excess'	       => $excess,
										'teeth_no'		   => $erp_teeth_no,
										'inv_no'	       => $inv_no,
										'no_sticker'	   => $no_sticker,
										'finish_type'	   => $erp_finish_type,
										'print_color'    => $print_color,
										'compiled_by'    => $compiled_by,
										'barcode_read'   => $barcode_read,
										'operator'       => $operator,
										'checker'        => $checker,
										'authorized_by'  => $authorized_by,
										'axicon_verifier'=> $axicon_verifier,
										'approved_by'	   => $approved_by,
										'packer'		     => $packer,
										'approval_date'  => $format_approval_date,
										'roll_issued_by' => $roll_issued_by,
										'cutter'		     => $cutter,
										'cartons' 		   => $cartons,
										'final_json_obj' => json_decode($final_json_obj, true),
										'single_ref'	   => $single_ref,
										'act_excess'	   => $act_excess,
										'sticker_mtr'	   => $sticker_mtr,
										'settings'		   => $settings,
										'total_mtrs' 	   => $total_mtrs
								]);

						//append content to header
						$pdf->SetXY(13.4,26.3);
						$pdf->writeHTML($view->render());

						//Close and output PDF document
						ob_end_clean();
						$file_path =  "/storage/app/public/jobcards/".$workorder_id."."."pdf";	
						$pdf->IncludeJS("print();");
						$pdf->Output( public_path($file_path), 'F');

						DB::table('jobcard_main_table')->where('id', $store_jobcard_id)
						->update([
							'file_path'   => $file_path,
							'updated_at'  => $current_timestamp
						]);
						return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path],200);	
					}
					else
					{
						return response()->json(['error'=> true,'message' =>'Error'],400);
					}
					
				}
				else
				{
					return response()->json(['error'=> true,'message' =>'Parameter is Missing'],400);
				}
		}
		catch (\Exception $e)
		{
			return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
		}
	}
	/**
   * Print Preview For Stickers
   *
   * @return pdf
   */
  
    public function printDivTesco(Request $request)
    {
		 $company_id = $request->id;
		 $customer = DB::table("erp_orders")
			 			->join('customers', 'erp_orders.customer_id', 'customers.id')
						->select('customers.short_name')
						->where('erp_orders.id',$company_id)
						->get();
		 $customer_name = $customer{0}->short_name;
		 $this->customer_name = $customer_name;
		
		 $this->created_date = $request->created_at;
		
		 $this->template_name = $request->name;
		 $this->template_zone = $request->zone;
		  if($request->name == "TCL1-sticker")
		  {
			  $this->template_desc = "RATIO STICKER";
		  }
		  else if($request->name == "TCL2-sticker")
	      {
			   $this->template_desc = "RATIO BD STICKER"; 
		  }
		  else if($request->name == "TCL4-sticker")
	      {
			   $this->template_desc =  "SINGLES BD STICKER";
		  }
		  else if($request->name == "THL1-sticker")
	      {
			    $this->template_desc = "RATIO STICKER";
		  }
		  else if($request->name == "THL3-sticker")
	      {
			   $this->template_desc = "SINGLES RATIO BD STICKER";
		  }
		  $this->tesco_po_number = $request->tesco_po_number;
		  $this->style = $request->style;
		  
			// Custom Header
		  PDF::setHeaderCallback(function($pdf) {
			$pdf->SetY(0);
		  $name=str_replace("-sticker", "", $this->template_name);
		  $zone=$this->template_zone;
			$tesco_po_number=$this->tesco_po_number;
			$style=$this->style;
			$tesco_description=$this->template_desc;
			$date=date_create($this->created_date);
			$created_date =  date_format($date,"d.m.Y");
			$image_file= K_PATH_IMAGES.'logo_example.jpg';
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="50.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal" style="&#10;    /* border-radius: 27px; */&#10;">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L666,65 L666,66 L499,66 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L500,65 L500,144 L499,144 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L821,65 L821,66 L665,66 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L969,65 L969,66 L820,66 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,65 L969,65 L969,105 L968,105 L968,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,104 L969,104 L969,144 L968,144 L968,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,182 L821,182 L821,183 L499,183 L499,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L500,143 L500,183 L499,183 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,182 L969,182 L969,183 L820,183 L820,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,143 L969,143 L969,183 L968,183 L968,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L666,143 L666,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,144 L665,144 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,105 L665,105 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L666,104 L666,143 L665,143 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,143 L820,143 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,144 L820,144 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L821,143 L821,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L1016,212 L1016,213 L8,213 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,213 L1016,213 L1016,214 L8,214 L8,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L8,214 L9,213 L9,212 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,213 L1015,214 L1016,214 L1016,212 L1015,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L1016,216 L1016,217 L8,217 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,217 L1016,217 L1016,218 L8,218 L8,217"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L8,218 L9,217 L9,216 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,217 L1015,218 L1016,218 L1016,216 L1015,217"/>
					</g>
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="56cm" height="37cm" version="1.1">
							<image xlink:href="'.public_path('/images/jupiter.jpg').'" x="174" y="66" height="65px" width="116px" style=""/>
					</svg>
					<g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Jupiter Garments Accessories</text>

					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">STYLE : '.$style.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REF : '.$name.' - '.$zone.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DATE : '.$created_date.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Size: 127x80mm</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DESCRIPTION : '.$tesco_description.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REV.NO :</text>
					</g>
					</g>
					</svg>';
					$pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			});
							
					// Custom Footer
			PDF::setFooterCallback(function($pdf) {
					$tesco_po_number=$this->tesco_po_number;
					$customer_name=$this->customer_name;
					// Position at 15 mm from bottom
					$pdf->SetY(-50);
					$pdf->SetX(9.5);
					// Set font
					//$pdf->SetFont('frankgothcnbt');
					// Page number
					$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188mm" height="65.2667mm" viewBox="0 0 1360 168" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L1352,52 L1352,53 L8,53 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,53 L1352,53 L1352,54 L8,54 L8,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L8,54 L9,53 L9,52 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,53 L1351,54 L1352,54 L1352,52 L1351,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L1352,56 L1352,57 L8,57 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,57 L1352,57 L1352,58 L8,58 L8,57"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L8,58 L9,57 L9,56 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,57 L1351,58 L1352,58 L1352,56 L1351,57"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M385,79 L386,79 L386,157 L385,157 L385,79"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M764,79 L765,79 L765,157 L764,157 L764,79"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">CUSTOMER : '.$customer_name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO No : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">APPROVED BY : </text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">COMMENTS IF ANY :</text>
					</g>
					</g>
					</svg>';
					
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
			});
		
		
				$svgarr = explode(',_',$request->svg);
				$name=$request->name;
		       // $svg=urldecode($request->svg);
				PDF::setImageScale(1);
				PDF::SetFont('frankgothcnbt');
				PDF::AddPage();
				$i=0;
				$yy=60;
				$j=1;
				PDF::SetXY(10, 50);
				foreach($svgarr as $value)
				{
					//$svg=urldecode($value);
					PDF::SetFillColor(255,255,255);
					if($i == 2)
					{
						$yy=60;
						PDF::AddPage();
						$i = 0;
					}
					else if($i == 0)
					{
						$yy=60;
					}
					else
					{
						$yy += 100;
					}
					$j++;
			
					
					//$svg=urldecode($value);
					file_put_contents(public_path('/storage/app/template/demo_'.$j.'.svg'), $value);
					$html = '<img style="width:480px;height:302.36220472px;border:1px solid #333" src="'.public_path('/storage/app/template/demo_'.$j.'.svg').'">';
					PDF::writeHTMLCell(127, 80, 15, $yy+1, $html, '', 0, 0, false, 'L', false);
				
					$i++;
					
				}
				$random = number_format(Carbon::now('Asia/Kolkata')->timestamp * rand(),0,'','');
				PDF::Output( public_path('/storage/app/template/'.$random.'-'.$name.'.pdf'), 'F');
				$file_path =  "/storage/app/template/".$random.'-'.$name."."."pdf";	
				
				return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path,'url'=>url('/storage/app/template/'.$random.'-'.$name.'.pdf')],200);	
    } 
	 /**
   * Print Preview For Stickers
   *
   * @return pdf
   */
  
    public function printDivTescoSmall(Request $request)
    {
		 $company_id = $request->id;
		 $customer = DB::table("erp_orders")
			 			->join('customers', 'erp_orders.customer_id', 'customers.id')
						->select('customers.short_name')
						->where('erp_orders.id',$company_id)
						->get();
		 $customer_name = $customer{0}->short_name;
		 $this->customer_name = $customer_name;
		
		 $this->created_date = $request->created_at;
		
		 $this->template_name = $request->name;
		 $this->template_zone = $request->zone;
		
		  if($request->name == "TSL1-sticker")
		  {
			  $this->template_desc = "RATIO POLYBAG STICKER";
		  }
		  else if($request->name == "TSL3-sticker")
	      {
			   $this->template_desc = "SINGLE POLYBAG STICKER "; 
		  }
		  $this->tesco_po_number = $request->tesco_po_number;
		  $this->style = $request->style;
		  $this->tesco_description = $request->tesco_description;
		  
			// Custom Header
		  PDF::setHeaderCallback(function($pdf) {
		  $name=str_replace("-sticker", "", $this->template_name);
		  $zone=$this->template_zone;
			$tesco_po_number=$this->tesco_po_number;
			$style=$this->style;
			$tesco_description=$this->template_desc;
			$date=date_create($this->created_date);
			$created_date =  date_format($date,"d.m.Y");
			$image_file= K_PATH_IMAGES.'logo_example.jpg';
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="50.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal" style="&#10;    /* border-radius: 27px; */&#10;">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L666,65 L666,66 L499,66 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L500,65 L500,144 L499,144 L499,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L821,65 L821,66 L665,66 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L969,65 L969,66 L820,66 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,65 L969,65 L969,105 L968,105 L968,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,104 L969,104 L969,144 L968,144 L968,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,182 L821,182 L821,183 L499,183 L499,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L500,143 L500,183 L499,183 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,182 L969,182 L969,183 L820,183 L820,182"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,143 L969,143 L969,183 L968,183 L968,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L666,143 L666,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,144 L665,144 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,105 L665,105 L665,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L666,104 L666,143 L665,143 L665,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,143 L820,143 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,144 L820,144 L820,104"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L821,143 L821,144 L499,144 L499,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L1016,212 L1016,213 L8,213 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,213 L1016,213 L1016,214 L8,214 L8,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L8,214 L9,213 L9,212 L8,212"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,213 L1015,214 L1016,214 L1016,212 L1015,213"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L1016,216 L1016,217 L8,217 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,217 L1016,217 L1016,218 L8,218 L8,217"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L8,218 L9,217 L9,216 L8,216"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,217 L1015,218 L1016,218 L1016,216 L1015,217"/>
					</g>
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="56cm" height="37cm" version="1.1">
							<image xlink:href="'.public_path('/images/jupiter.jpg').'" x="174" y="66" height="65px" width="116px" style=""/>
					</svg>
					<g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Jupiter Garments Accessories</text>

					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">STYLE : '.$style.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REF : '.$name.' - '.$zone.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DATE : '.$created_date.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">SIZE: 40x30mm</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DESCRIPTION : '.$tesco_description.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REV.NO:</text>
					</g>
					</g>
					</svg>';
					$pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			});
							
			// Custom Footer
			PDF::setFooterCallback(function($pdf) {
			$tesco_po_number=$this->tesco_po_number;
			$customer_name=$this->customer_name;
			// Position at 15 mm from bottom
			$pdf->SetY(-50);
			$pdf->SetX(9.5);
			// Set font
			//$pdf->SetFont('frankgothcnbt');
			// Page number
			$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188mm" height="65.2667mm" viewBox="0 0 1360 168" version="1.2" baseProfile="tiny">
					<title>Qt Svg Document</title>
					<desc>Generated with Qt</desc>
					<defs>
					</defs>
					<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
					<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L1352,52 L1352,53 L8,53 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,53 L1352,53 L1352,54 L8,54 L8,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L8,54 L9,53 L9,52 L8,52"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,53 L1351,54 L1352,54 L1352,52 L1351,53"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L1352,56 L1352,57 L8,57 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,57 L1352,57 L1352,58 L8,58 L8,57"/>
					</g>
					<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L8,58 L9,57 L9,56 L8,56"/>
					</g>
					<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,57 L1351,58 L1352,58 L1352,56 L1351,57"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M385,79 L386,79 L386,157 L385,157 L385,79"/>
					<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M764,79 L765,79 L765,157 L764,157 L764,79"/>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">CUSTOMER : '.$customer_name.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">APPROVED BY : </text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
					</g>
					<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">COMMENTS IF ANY :</text>
					</g>
					</g>
					</svg>';
					
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		});
		$svgarr = explode(',_',$request->svg);
		$name=$request->name;
    //$svg=urldecode($request->svg);
    PDF::setImageScale(1);
    PDF::SetFont('frankgothcnbt');	
		PDF::AddPage();
		$i=0;
		$yy=0;
		$j=1;
		PDF::SetXY(10, 50);
		foreach($svgarr as $value)
		{
			//$svg=urldecode($value);
			PDF::SetFillColor(255,255,255);
			if($i == 3 || $i == 6 || $i == 9 || $i == 15 || $i == 18)
			{
				$xx=15;
				$yy+=50;
			}
			else if($i == 0)
			{
				$yy=50;
				$xx=15;
			}
			else if($i == 12)
			{
				PDF::AddPage();
				$xx=15;
				$yy=50;
			}
			else 
			{
				$xx+=70;
			}
			$j++;
			
			
			file_put_contents(public_path('/storage/app/template/demo_'.$j.'.svg'), $value);
			$html = '<img style="width:151.18110236px;height:113.38582677px;border:1px solid #333" src="'.public_path('/storage/app/template/demo_'.$j.'.svg').'">';
			PDF::writeHTMLCell(42, 32, $xx, $yy, $html, '', 0, 0, false, 'L', false);
			$i++;

		}
		$random = number_format(Carbon::now('Asia/Kolkata')->timestamp * rand(),0,'','');
		PDF::Output( public_path('/storage/app/template/'.$random.'-'.$name.'.pdf'), 'F');
		$file_path =  "/storage/app/template/".$random.'-'.$name."."."pdf";	
		
		return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path,'url'=>url('/storage/app/template/'.$random.'-'.$name.'.pdf')],200);	
				
		} 
		/**
     * Function to Merge Jobcard pdf and artwork
     * @author <Praveen>
     * @param array PDF files
     * @return filepath
     */
		public function pdfMerge(Request $request)
		{
			try {
						$path = explode(',',$request['path']);
						$merge = new FPDF_Merge();
						foreach($path as $value)
						{
							if($value != null && $value != "")
							{
								$merge->add(public_path($value));
							}
							
						}
						$file_path =  $path[0];	

						$merge->output(public_path($file_path) , 'F');
						
						return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path],200);	
				} catch (\Exception $e) {
					return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
			}
				
		}
	/**
     * Function to get tesco tsl template UK , CE size chart based on size entered.
     * @author <Praveen>
     * @param [string] $[size] [<Size entered>], [string] $[zone] [<zone selected>]
     * @return equivalent uk,ce size for size entered
     */
    public function getTescoTSLSizechart(Request $request)
    {
       try {
             $zone = $request['zone'];
             $size = $request['size'];

           if($zone == 'UK')
           {
               $size = DB::table('tesco_size_chart')->select('uk as size')->where('po_size', 'ilike', $size)->where('is_enabled','=',1)->get();
           }
           else
           {
               $size = DB::table('tesco_size_chart')->select('ce as size')->where('po_size', 'ilike', $size)->where('is_enabled','=',1)->get();
           }
           
           return response()->json(['size_chart' => $size]);
       } catch (\Exception $e) {
           return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
       }
    }

	/**
     * Function to get all jobcards list.
     * @author <Praveen>
     * @return equivalent list of jobcards
     */
    public function getJobcards()
    {
        try {
				$customer_id = Auth::guard('erpuser')->id();
				
				$companies   = 	DB::table('erpuser_company_mapping')
										->join('company','erpuser_company_mapping.company','company.id')
										->where('erpuser_company_mapping.erp_user',$customer_id)
										->select('erpuser_company_mapping.*','company.name', 'company.logo','company.id')
										->get();
				if($companies)
				{
					$company_id   = DB::table('company')
									->select('company.*')
									->where('company.id', '=', $companies{0}->company)
									->first();
				}
				else
				{
					return view('erp.home');
				}
				
				
				if(session()->get('company') == "" && session()->get('company') == null)
				{
					session()->put('company', $company_id->id);
					session()->put('company_name', strtolower( str_replace(' ','',$company_id->name)) );
				}
				/**
				 * Dropdown values
				 */
				$buyers  = 	DB::table('buyers')
								->join('jobcard_main_table', 'jobcard_main_table.buyer_id', 'buyers.id')
								->where('jobcard_main_table.company_id', '=', session()->get('company'))
								->pluck('buyers.buyer_name','buyers.id')
								->all();
				
				$erp_dispatch_mode = DB::table('erp_dispatch_mode')->where('is_enabled','=',true)->pluck('disp_mode','id')->all();
				$erp_finish_type   = DB::table('erp_finish_type')->where('is_enabled','=',true)->pluck('finish_type','id')->all();
				$erp_machines      = DB::table('erp_machines')->where('is_enabled','=',true)->pluck('machine','id')->all();
				$erp_teeth_no      = DB::table('erp_teeth_no')->where('is_enabled','=',true)->pluck('teeth_no','id')->all();
				return view('erp.Jobmanager.jobcards-list', ['buyers' => $buyers, 'companies' => $companies, 'disp_modes' => $erp_dispatch_mode, 'finish_types' => $erp_finish_type, 'machines' => $erp_machines, 'teeth_nos' => $erp_teeth_no]);	
			} catch (\Exctesteption $e) {
				return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
			}
	}
	
	/**
     * Function to get jobcard details by id.
     * @author <Praveen>
	 * @param  $id
     * @return equivalent jobcard details
     */  
    public function getJobcardDetailsById(Request $request)
    {
        try {
				$id = $request['id'];

				$customer_id = Auth::guard('erpuser')->id();

				if(session()->get('company') == "" || session()->get('company') == null || $customer_id == null)
				{
					return view('erp.home');
				}
				else
				{
					$jobcard_data  = DB::table('jobcard_main_table')
										->select('jobcard_main_table.*', 'customers.id as customer_id','customers.short_name as customer', 'buyers.buyer_name', 'buyers.id as buyer_id', 'company.name as company', 'company.id as company_id', 'company.address as company_address','company.logo as company_logo')
										->join('customers', 'jobcard_main_table.customer_id', 'customers.id')
										->join('company', 'company.id', 'jobcard_main_table.company_id')
										->join('buyers', 'jobcard_main_table.buyer_id', 'buyers.id')
										->where('jobcard_main_table.id', '=', $id)
										->first();
					$jobcard_data->created_date      =	$jobcard_data->created_date == ''?NULL:Carbon::parse($jobcard_data->created_date)->format('d-m-Y');
					$jobcard_data->order_date        =	$jobcard_data->order_date == ''?NULL:Carbon::parse($jobcard_data->order_date)->format('d-m-Y');
					$jobcard_data->customer_del_date =	$jobcard_data->customer_del_date == ''?NULL:Carbon::parse($jobcard_data->customer_del_date)->format('d-m-Y');
					$jobcard_data->invoice_date      =	$jobcard_data->invoice_date == ''?NULL:Carbon::parse($jobcard_data->invoice_date)->format('d-m-Y');
					$jobcard_data->approval_date     =	$jobcard_data->approval_date == ''?NULL:Carbon::parse($jobcard_data->approval_date)->format('d-m-Y');

				    $jobcard_details  = DB::table('jobcard_excess_details')
										->select('jobcard_excess_details.ref_name', 'jobcard_excess_details.mtrs_for_stickers', 'jobcard_excess_details.actual_excess', 'jobcard_excess_details.settings', 'jobcard_excess_details.total_mtrs')
										->where('jobcard_excess_details.jobcard_main_table_id', '=', $jobcard_data->id)
										->get();
					$jobcard_data->additional_details = $jobcard_details;					
					return response()->json(['error'=> false,'message' =>'Success', 'jobcard_data' => $jobcard_data], 200);
				}
	
			} catch (\Exctesteption $e) {
				return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
			}
	}
	/**
     * Function to update jobcard details by id.
     * @author <Praveen>
	 * @param  $json_data
     * @return equivalent pdf file path
     */
    public function updateJobcardById(Request $request)
    {
		try {
				$company_id      = $request['company_id'];
				$workorder_id    = $request['workorder_id'];
				$created_date    = $request['created_date'] == ''?NULL:Carbon::parse($request['created_date'],'Asia/Kolkata')->toDateString();
				$article         = $request['article'];
				$material        = $request['material'];
				$customer_id     = $request['customer_id'];
				$style_no        = $request['style_no'];
				$order_type      = $request['order_type'];
				$buyer_id        = $request['buyer_id'];
				$reference       = $request['reference'];
				$disp_mode       = $request['disp_mode'];
				$order_date      = $request['order_date'] == ''?NULL:Carbon::parse($request['order_date'],'Asia/Kolkata')->toDateString();
				$length          = $request['length'];
				$repeat_gap      = $request['repeat_gap'];
				$machine         = $request['machine'];
				$cus_del_date    = $request['cus_del_date'] == ''?NULL:Carbon::parse($request['cus_del_date'],'Asia/Kolkata')->toDateString();
				$roll_width      = $request['roll_width'];
				$width           = $request['width'];
				$film_no         = $request['film_no'];
				$invoice_date    = $request['invoice_date'] == ''?NULL:Carbon::parse($request['invoice_date'],'Asia/Kolkata')->toDateString();
				$length_side     = $request['length_side'];
				$excess          = $request['excess'];
				$teeth_no        = $request['teeth_no'];
				$inv_no          = $request['inv_no'];
				$no_sticker      = $request['no_sticker'];
				$finish_type     = $request['finish_type'];
				$print_color     = $request['print_color'];
				$compiled_by     = $request['compiled_by'];
				$barcode_read    = $request['barcode_read'];
				$operator        = $request['operator'];
				$checker         = $request['checker'];
				$authorized_by   = $request['authorized_by'];
				$axicon_verifier = $request['axicon_verifier'];
				$approved_by     = $request['approved_by'];
				$packer          = $request['packer'];
				$approval_date   = $request['approval_date'] == ''?NULL:Carbon::parse($request['approval_date'],'Asia/Kolkata')->toDateString();
				$roll_issued_by  = $request['roll_issued_by'];
				$cutter          = $request['cutter'];
				$cartons         = $request['cartons'];
				$final_json_obj  = $request['final_json_obj'];
				$jobcard_id 	 = $request['jobcard_id'];
				
				$current_timestamp = Carbon::now('Asia/Kolkata')->toDateTimeString();

				if(isset($company_id) && isset($workorder_id) && isset($created_date) && isset($article) && isset($material) && isset($customer_id) && isset($buyer_id) && isset($disp_mode) && isset($order_date) && isset($machine) && isset($cus_del_date)  && isset($finish_type)  && isset($compiled_by) && isset($final_json_obj) && isset($jobcard_id) && isset($material))
				{
					DB::table('jobcard_main_table')->where('id', $jobcard_id)
					->update([
						'work_order_id'          => $workorder_id,
						'company_id'             => $company_id,
						'customer_id'            => $customer_id,
						'buyer_id'               => $buyer_id,
						'created_date'           => $created_date,
						'material'               => $material,
						'article'                => $article,
						'style_number'           => $style_no,
						'reference'              => $reference,
						'order_type'             => $order_type,
						'dispatch_mode'          => $disp_mode,
						'order_date'             => $order_date,
						'length'                 => $length,
						'repeat_gap'             => $repeat_gap,
						'machine'                => $machine,
						'customer_del_date'      => $cus_del_date,
						'width'                  => $width,
						'roll_width'             => $roll_width,
						'film_no'                => $film_no,
						'invoice_date'           => $invoice_date,
						'length_side'            => $length_side,
						'excess_percentage'      => $excess,
						'teeth_no'               => $teeth_no,
						'invoice_num'            => $inv_no,
						'no_of_stickers'         => $no_sticker,
						'finish_type'            => $finish_type,
						'print_color'            => $print_color,
						'compiled_by'            => $compiled_by,
						'barcode_read'           => $barcode_read,
						'operator'               => $operator,
						'checker'                => $checker,
						'authorized_by'          => $authorized_by,
						'axicon_verifier'        => $axicon_verifier,
						'approved_by'            => $approved_by,
						'packer'                 => $packer,
						'approval_date'          => $approval_date,
						'roll_issued_by'         => $roll_issued_by,
						'cutter'                 => $cutter,
						'cartons'                => $cartons,
						'template_quantity_json' => $final_json_obj,
						'updated_at'             => $current_timestamp
					]);
					
					if (!empty($request['single_ref'])) 
					{
						
						$single_ref      = explode(',', $request['single_ref']);
						$act_excess 	 = explode(',', $request['act_excess']);
						$sticker_mtr 	 = explode(',', $request['sticker_mtr']);
						$settings        = explode(',', $request['settings']);
						$total_mtrs 	 = explode(',', $request['total_mtrs']);

						// delete data before insertion.
						DB::table('jobcard_excess_details')->where('jobcard_main_table_id', '=', $jobcard_id)
							->delete();
							
						$i = 0;
						foreach($single_ref as $value)
						{
							$store_jobcard_details = DB::table('jobcard_excess_details')->insert([
								'jobcard_main_table_id'   => $jobcard_id,
								'ref_name'                => $single_ref[$i],
								'mtrs_for_stickers'       => $sticker_mtr[$i],
								'actual_excess'           => $act_excess[$i],
								'settings'                => $settings[$i],
								'total_mtrs'              => $total_mtrs[$i],
								'created_at'              => $current_timestamp,
								'updated_at'              => $current_timestamp
							]);
							$i++;
						}
					}
					else
					{
						$single_ref      = explode(',', $request['single_ref']);
						$act_excess 	 = explode(',', $request['act_excess']);
						$sticker_mtr 	 = explode(',', $request['sticker_mtr']);
						$settings        = explode(',', $request['settings']);
						$total_mtrs 	 = explode(',', $request['total_mtrs']);
					}

					$buyer    = DB::table('buyers')->select('buyer_name')->where('id', '=', $buyer_id)->first();
					$customer = DB::table('customers')->select('short_name as customer_name')->where('id', '=', $customer_id)->first();
					$company  = DB::table('company')->select('name as company', 'address as company_address', 'logo as company_logo')->where('id', '=', $company_id)->first();

					$erp_dispatch_mode = DB::table('erp_dispatch_mode')->where('id','=', $disp_mode)->value('disp_mode');
					$erp_finish_type   = DB::table('erp_finish_type')->where('id','=', $finish_type)->value('finish_type');
					$erp_machine       = DB::table('erp_machines')->where('id','=', $machine)->value('machine');
					$erp_teeth_no      = DB::table('erp_teeth_no')->where('id','=', $teeth_no)->value('teeth_no');

					/** [ Creating new object for JobcardPDFHeaderFooter() class ] */
					$format_approval_date  = $approval_date == ''?NULL:Carbon::parse($approval_date)->format('d-m-Y');
					$formated_inv_date     = $invoice_date == ''?NULL:Carbon::parse($invoice_date)->format('d-m-Y');
					$formated_created_date = $created_date == ''?NULL:Carbon::parse($created_date)->format('d-m-Y');
					$formated_order_date   = $order_date == ''?NULL:Carbon::parse($order_date)->format('d-m-Y');
					$formated_cus_del_date = $cus_del_date == ''?NULL:Carbon::parse($cus_del_date)->format('d-m-Y');
					
					$pdf = new JobcardPDFHeaderFooter(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
					$pdf->setData($company, $workorder_id, $formated_created_date, $article, $material, $compiled_by, $barcode_read, $operator, $checker, $authorized_by, $axicon_verifier, $approved_by, $packer, $format_approval_date, $roll_issued_by, $cutter, $cartons, $single_ref, $act_excess, $sticker_mtr, $settings, $total_mtrs);	

					$pdf->SetPrintHeader(true);
					$pdf->SetPrintFooter(true);  

					//margins
					$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
					$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
					$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
					
					// set header and footer fonts
					$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
					$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
					
					// /** Handle Page break */
					// if ($pdf->last_page_flag)
					// {
							// set auto page breaks
							$pdf->SetAutoPageBreak(TRUE, 40);
	
					// }
					// else 
					// {
					// 		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
					// }

					// set image scale factor
					$pdf->setImageScale(1.8);

					// set font
					$pdf->SetFont('helvetica', '', 8);

					// add a page
					$pdf->AddPage('P', 'A4');

					// set some text to print
					$view = View::make('erp/Jobmanager/jobcard-pdf-template')
								->with([
										'buyer' 		     => $buyer,
										'customer' 		   => $customer,
										'company'		     => $company,
										'workorder_id'   => $workorder_id,
										'created_date'   => $formated_created_date,
										'article'	       => $article,
										'material'		   => $material,
										'style_no'		   => $style_no,
										'order_type'     => $order_type,
										'reference'		   => $reference,
										'disp_mode'	     => $erp_dispatch_mode,
										'order_date'	   => $formated_order_date,
										'length'		     => $length,
										'repeat_gap'	   => $repeat_gap,
										'machine'		     => $erp_machine,
										'cus_del_date' 	 => $formated_cus_del_date,
										'roll_width'	   => $roll_width,
										'width'	  		   => $width,
										'film_no'		     => $film_no, 
										'invoice_date'   => $formated_inv_date,
										'length_side'	   => $length_side,
										'excess'	       => $excess,
										'teeth_no'		   => $erp_teeth_no,
										'inv_no'	       => $inv_no,
										'no_sticker'	   => $no_sticker,
										'finish_type'	   => $erp_finish_type,
										'print_color'    => $print_color,
										'compiled_by'    => $compiled_by,
										'barcode_read'   => $barcode_read,
										'operator'       => $operator,
										'checker'        => $checker,
										'authorized_by'  => $authorized_by,
										'axicon_verifier'=> $axicon_verifier,
										'approved_by'	   => $approved_by,
										'packer'		     => $packer,
										'approval_date'  => $format_approval_date,
										'roll_issued_by' => $roll_issued_by,
										'cutter'		     => $cutter,
										'cartons' 		   => $cartons,
										'final_json_obj' => json_decode($final_json_obj, true),
										'single_ref'	   => $single_ref,
										'act_excess'	   => $act_excess,
										'sticker_mtr'	   => $sticker_mtr,
										'settings'		   => $settings,
										'total_mtrs' 	   => $total_mtrs
								]);

					//append content to header
					$pdf->SetXY(13.4,25.5);
					$pdf->writeHTML($view->render());

					//Close and output PDF document
					ob_end_clean();
					$file_path =  "/storage/app/public/jobcards/".$workorder_id."."."pdf";

					/** delete already existing file from folder */	
					File::delete($file_path);
					
					$pdf->IncludeJS("print();");
					$pdf->Output( public_path($file_path), 'F');

					DB::table('jobcard_main_table')->where('id', $jobcard_id)
					->update([
						'file_path'   => $file_path,
						'updated_at'  => $current_timestamp
					]);
					return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path],200);	
					
				}
				else
				{
					return response()->json(['error'=> true,'message' =>'Parameter is Missing'],400);
				}
		}
		catch (\Exception $e)
		{
			return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
		}
	}


	/**
     * Function to get customers list by  buyer id.
     * @author <Praveen>
	 * @param  $buyer_id
     * @return equivalent customer list
     */  
    public function getCustomersListByBuyerId(Request $request)
    {
        try {
				$buyer_id = $request['selected_buyer_id'];

				if(session()->get('company') == "" || session()->get('company') == null)
				{
					return view('erp.home');
				}

				if($buyer_id == null)
				{
					return response()->json(['error'=> true,'message' =>'Parameter Missing'], 400);
				}
				else
				{
					$customers_list  = 	DB::table('customers')
										->join('jobcard_main_table', 'jobcard_main_table.customer_id', 'customers.id')
										->where('jobcard_main_table.company_id', '=', session()->get('company'))
										->where('jobcard_main_table.buyer_id', '=', $buyer_id)
										->pluck('customers.company_name','customers.id')
										->all();
					return response()->json(['error'=> false,'message' =>'Success', 'customers_list' => $customers_list], 200);
				}
				    
	
			} catch (\Exctesteption $e) {
				return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
			}
	}

	/**
     * Function to get jobcard id list by  buyer id and customer id.
     * @author <Praveen>
	 * @param  $buyer_id, $customer_id
     * @return equivalent jobcard ids list
     */  
    public function getJobcardIdListByBuyerCustomerId(Request $request)
    {
        try {
				$buyer_id    = $request['selected_buyer_id'];
				$customer_id = $request['selected_customer_id'];

				if(session()->get('company') == "" || session()->get('company') == null)
				{
					return view('erp.home');
				}

				if($buyer_id == null || $customer_id == null)
				{
					return response()->json(['error'=> true,'message' =>'Parameter Missing'], 400);
				}
				else
				{
					$jobcard_id_list  = DB::table('jobcard_main_table')
											->where('jobcard_main_table.company_id', '=', session()->get('company'))
											->where('jobcard_main_table.buyer_id', '=', $buyer_id)
											->where('jobcard_main_table.customer_id', '=', $customer_id)
											->pluck('jobcard_main_table.work_order_id','jobcard_main_table.id')
											->all();
					return response()->json(['error'=> false,'message' =>'Success', 'jobcard_id_list' => $jobcard_id_list], 200);
				}
				    
	
			} catch (\Exctesteption $e) {
				return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
			}
	}

	/**
     * Function to get jobcards list by  buyer id, customer id and jobcard id.
     * @author <Praveen>
	 * @param  $buyer_id, $customer_id, $jobcard_id
     * @return equivalent jobcard list
     */  
    public function getJobcardsListByBuyerCustomerJobcardId(Request $request)
    {
        try {
				$buyer_id    = $request['selected_buyer_id'];
				$customer_id = $request['selected_customer_id'];
				$jobcard_id  = $request['selected_jobcard_id'];

				if(session()->get('company') == "" || session()->get('company') == null)
				{
					return view('erp.home');
				}

				if($buyer_id == null || $customer_id == null || $jobcard_id == null)
				{
					return response()->json(['error'=> true,'message' =>'Parameter Missing'], 400);
				}
				else
				{
					$jobcards_list  = DB::table('jobcard_main_table')
											->join('customers', 'jobcard_main_table.customer_id', 'customers.id')
											->join('buyers', 'jobcard_main_table.buyer_id', 'buyers.id')
											->where('jobcard_main_table.company_id', '=', session()->get('company'))
											->where('jobcard_main_table.buyer_id', '=', $buyer_id)
											->where('jobcard_main_table.customer_id', '=', $customer_id)
											->where('jobcard_main_table.id', '=', $jobcard_id)
											->select('jobcard_main_table.id as jobcard_id', 'jobcard_main_table.work_order_id', 'customers.company_name', 'buyers.buyer_name')
											->first();
					return response()->json(['error'=> false,'message' =>'Success', 'jobcards_list' => $jobcards_list], 200);
				}
				    
	
			} catch (\Exctesteption $e) {
				return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
			}
	}
	 public function generateDivTesco(Request $request)
    {
		    $svgarr = explode(',_',$request->svg);
        $name=$request->name;
        $svg=urldecode($request->svg);
        define ('PDF_FONT_NAME_MAIN', 'frankgothcnbt');
        define ('PDF_FONT_NAME_DATA', 'frankgothcnbt');
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
        PDF::SetFont('frankgothcnbt');
		PDF::SetAutoPageBreak(TRUE);
		$i=0;
		foreach($svgarr as $value)
		{
			 PDF::SetMargins(0, 0, 0, false);
			 PDF::AddPage('L', array(80,127));
			 $svg=urldecode($value);
			 PDF::setImageScale(1.66);
			 PDF::ImageSVG('@' . $svg, $x=0, $y=0, $w=127, $h=80, $link='', $align='', $palign='', $border=0, $fitonpage=true);
			if($name != "TCL2-sticker" && $name != "TCL4-sticker")
			{
				 if($name != "THL3-sticker")
				 {
					 PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), 12, 52, 50, 16, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
				 }
				else
				{
					 PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), -2, 52, 75, 16, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
				}
			}
			
			 $i++;
		}
		if($name != "TCL4-sticker" && $name != "TCL2-sticker")
		{
			$j=0;
			if(count($svgarr) == $i)
			{
				foreach($svgarr as $value)
				{
					unlink(public_path('/storage/app/template/barcode_'.$j.'.png'));
					$j++;
				}
			}
		}
		
		PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
		print_r(url('/storage/app/template/'.$name.'.pdf'));
		
		
		
//		$svgarr = explode(',_',$request->svg);
//		$name=$request->name;
//        $svg=urldecode($request->svg);
//        define ('PDF_FONT_NAME_MAIN', 'frankgothcnbt');
//        define ('PDF_FONT_NAME_DATA', 'frankgothcnbt');
//        PDF::setPrintHeader(false);
//        PDF::setPrintFooter(false);
//        PDF::SetFont('frankgothcnbt');
//		PDF::SetAutoPageBreak(TRUE);
//		$j=0;
//		foreach($svgarr as $value)
//		{
//			PDF::SetMargins(0, 0, 0, false);
//			PDF::setImageScale(1);
//			PDF::AddPage('L', array(80,127));
//			$svg=urldecode($value);
//			file_put_contents(public_path('/storage/app/template/demo_'.$j.'.svg'), $svg);
//			$html = '<img style="width:480px;height:302.36220472px;border:1px solid #333" src="'.public_path('/storage/app/template/demo_'.$j.'.svg').'">';
//			PDF::writeHTMLCell(129, 82.5, -1,0, $html, '', 0, 0, false, 'L', false);
//			$j++;
//		}
//		PDF::deletePage(1);
//        PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
//        print_r(url('/storage/app/template/'.$name.'.pdf'));
    } 
	
	
		 /**
   * Print Preview For Stickers
   *
   * @return pdf
   */
  
    public function generateDivTescoSmall(Request $request)
    {
		$svgarr = explode(',_',$request->svg);
    	$name=$request->name;
        $svg=urldecode($request->svg);
        define ('PDF_FONT_NAME_MAIN', 'frankgothcnbt');
        define ('PDF_FONT_NAME_DATA', 'frankgothcnbt');
        PDF::setImageScale(1.66);
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
        PDF::SetFont('frankgothcnbt');
		PDF::SetFont('myriad');
		PDF::SetAutoPageBreak(TRUE);
		$i=0;
		foreach($svgarr as $value)
		{
			 PDF::AddPage('L', array(30,80));
			 PDF::SetMargins(0, 0, 0, false);
			
			 $svg=urldecode($value);
			
			
			
			 PDF::ImageSVG('@' . $svg, $x=0, $y=0, $w=40, $h=30, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			 PDF::ImageSVG('@' . $svg, $x=40, $y=0, $w=40, $h=30, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			 if($name == "TSL3-sticker")
			 {
				 PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), 0.9, 2, 36.5, 13, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
			
				PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), 40.9, 2, 36.5, 13, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
			 }
			 else
			 {
//				 $img = Image::make(public_path('/storage/app/template/barcode_'.$i.'.png'));
//
//				// now you are able to resize the instance
//				$img->resize(150, 50);
//
//				// finally we save the image as a new file
//				$img->save(public_path('/storage/app/template/barcode_RESIZE.png'));
				 
				 PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), -1.1, 2,41.5, 13, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
			
				PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), 38.7, 2, 41.5, 13, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
			 }
			
			
			
			
//			PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), -1.7, 2, 42, 13, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
//			
//			PDF::Image(public_path('/storage/app/template/barcode_'.$i.'.png'), 39.7, 2, 42, 13, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
//			
			$i++;
		}
		$j=0;
		if(count($svgarr) == $i)
		{
			foreach($svgarr as $value)
			{
				unlink(public_path('/storage/app/template/barcode_'.$j.'.png'));
				$j++;
			}
		}
        PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
        print_r(url('/storage/app/template/'.$name.'.pdf'));
    } 
	
	 /**
     * This Function is used to generate barcode using java.
     *
     * @return path of barcode
     */
  	public function generateBarcodeTsl1(Request $request)
    {
		$name=$request['name'];
		$type=$request['type'];
		$barcode=$request['barcode'];
		
		$tabula       = Config::get('tabula.bar_jar');
		
		$info    = shell_exec('java -jar '.$tabula.' '.$barcode.' '.public_path('/storage/app/template/'.$name.'.png').' '.$type.' 33 13');
        $info    = json_encode($info, true);
		return $info;
    }
	/**
     * This Function is used to generate barcode using java.
     *
     * @return path of barcode
     */
  	public function generateBarcodeTsl3(Request $request)
    {
		$name=$request['name'];
		$type=$request['type'];
		$barcode=$request['barcode'];
		
		$tabula       = Config::get('tabula.bar_jar');
		
		$info    = shell_exec('java -jar '.$tabula.' '.$barcode.' '.public_path('/storage/app/template/'.$name.'.png').' '.$type.' 33 13');
        $info    = json_encode($info, true);
		return $info;
    }
	/**
     * This Function is used to generate barcode using java.
     *
     * @return path of barcode
     */
  	public function generateBarcodeEanBig(Request $request)
    {
		$name=$request['name'];
		$type=$request['type'];
		$barcode=$request['barcode'];
		
		$tabula       = Config::get('tabula.bar_jar_big');
		
		$info    = shell_exec('java -jar '.$tabula.' '.$barcode.' '.public_path('/storage/app/template/'.$name.'.png').' '.$type.' 50 16');
        $info    = json_encode($info, true);
		return $info;
    }
	/**
     * This Function is used to generate barcode using java.
     *
     * @return path of barcode
     */
  	public function generateBarcodeItfBig(Request $request)
    {
		$name=$request['name'];
		$type=$request['type'];
		$barcode=$request['barcode'];
		
		$tabula       = Config::get('tabula.bar_jar_big');
		
		$info    = shell_exec('java -jar '.$tabula.' '.$barcode.' '.public_path('/storage/app/template/'.$name.'.png').' '.$type.' 50 16');
        $info    = json_encode($info, true);
		return $info;
    }


    public function scriptCheckOrder(){
    	$erpOrderDetails = DB::table('erp_order_details')->select('order_qnty_table_name','order_qnty_table_id')->get();
    	$count = 0 ; 
    	foreach ($erpOrderDetails as $value) {
    		$orderData = DB::table($value->order_qnty_table_name)->where('id',$value->order_qnty_table_id)->first();
    		
    		if(!empty($orderData)){
    			echo "\n";
    			echo "Exists";
    			echo $orderData->id;
    			echo $value->order_qnty_table_name;
    		}
    		else{
    			echo "Not exists\n";
    			echo $value->order_qnty_table_id;
    			echo $value->order_qnty_table_name;
    		}
    	}
		}
		
		/**
		 * Function to update order status id value for existing data in erp_order_details table.
		 * @author <Praveen>
		 */
		public function scriptUpdateOrderStatusId()
		{
			$order_details = DB::table('erp_order_details')->get();
			
			foreach($order_details as $value)
			{
				$order_details_data  = 	DB::table($value->order_qnty_table_name)
										->where('id',$value->order_qnty_table_id)
										->select('*')
										->get();
				
				if(sizeof($order_details_data) > 0)
				{
					$order_table = str_replace("_quanty","",$value->order_qnty_table_name);
					$order_id    = strtolower(str_replace(" ","",$value->reference_name).'_order_id');
					$order_data  = 	DB::table($order_table)
											->where($order_table.'.id',$order_details_data{0}->$order_id)
											->select($order_table.'.*')
											->get();
					$order_status = $order_data{0}->order_status;

					$order_status_id  = DB::table('orders_status')->select('id')->where('status_name', 'ilike', $order_status)->first();
					
					$data = ErpOrderDetails::where('id',$value->id)
					->update([
										'order_status_id'  => $order_status_id->id
									]);
									echo  "array size= ".sizeof($order_details_data)." |  order status : ". $order_status. "| code_status : ".$data;
				}
				echo  "array size= ".sizeof($order_details_data);
			}
		}
	

 public function printPdfTable(Request $request)
    {
         $name=$request->name;
         $svg=urldecode($request->svg);
         define ('PDF_FONT_NAME_MAIN', 'calibrib');
         define ('PDF_FONT_NAME_DATA', 'calibrib');
//          print_r($svg);
        PDF::setImageScale(1);
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
        PDF::SetFont('calibrib');
        PDF::AddPage();
		
		$style6 = array('width' => 0.353, 'cap' => '', 'join' => '', 'dash' => '', 'color' => '');
        //PDF::RoundedRect(95, 255, 40, 30, 0.1, '1111', null, $style6);
        //PDF::RoundedRect(140, 255, 40, 30, 0.1, '1111', 'null', $style6);
        PDF::RoundedRect(10, 10, 150, 100, 0.000001, '1111', null, $style6);
        PDF::RoundedRect(12.5, 12.5, 145, 95, 0.000001, '1111', null, $style6);
		
		//PDF::RoundedRect(10, 10, 150, 100, 0, '0000');
		//PDF::RoundedRect(12.5, 12.5, 145, 95, 0, '0000');
        $td_size="";
        $td_col="";
        $total=0;
        $size=explode(',',$request->size);
        $qty=explode(',',$request->qty);
        $i=0;
		$index_arr=array();
        foreach($size as $val)
        {
			  if (!in_array($val, $index_arr)) 
			  { 
				   if($val != "")
					{
					    array_push($index_arr,$val);
						$td_size.="<td><br><br>".$val."</td>";
						$td_col.="<td>".$qty[$i]."</td>";
					} 
			  } 
           
            $total+=$qty[$i];
            $i++;
        }
          $tbl = '<table  border="1" style="text-align:center;">
		  			<tr style="line-height:0.53">
                          <td style="font-size:14px;text-align:center;line-height:0.45"><br><br>Size:</td>
                          '.$td_size.'
                          <td><br><br>Total<br></td>
                          </tr>
                      <tr>
                          <td style="font-size:7.5px;line-height:2">ERCU</td>
                          '.$td_col.'
                          <td>'.$total.'</td>
                          </tr>
            </table>';
        PDF::SetFillColor(255, 255, 255);
        PDF::SetTextColor(0, 0, 0);
        
        PDF::ImageSVG('@' . $svg, $x=12.5, $y=12.5, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
        PDF::writeHTMLCell(147, '', '11.5', '55', $tbl, '', 1, 1, true, '', true);
        
  
        
        $style = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'calibrib',
            'fontsize' => 12,
            'stretchtext' => 4
        );
         ///PDF::write1DBarcode('3531114195599', 'EAN13', '60', '72', '', 32, 0.45, $style, 'N');

       
        PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
        print_r(url('/storage/app/template/'.$name.'.pdf'));
        
    }

	public function printPdf(Request $request)
    {
		 $name=$request->name;
         $svg=urldecode($request->svg);
         define ('PDF_FONT_NAME_MAIN', 'calibrib');
         define ('PDF_FONT_NAME_DATA', 'calibrib');
//          print_r($svg);
        PDF::setImageScale(1);
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
        PDF::SetFont('calibrib');
        PDF::AddPage();
		$style6 = array('width' => 0.353, 'cap' => '', 'join' => '', 'dash' => '', 'color' => '');
        //PDF::RoundedRect(95, 255, 40, 30, 0.1, '1111', null, $style6);
        //PDF::RoundedRect(140, 255, 40, 30, 0.1, '1111', 'null', $style6);
        PDF::RoundedRect(10, 10, 150, 100, 0.000001, '1111', null, $style6);
        PDF::RoundedRect(12.5, 12.5, 145, 95, 0.000001, '1111', null, $style6);
        PDF::ImageSVG('@' . $svg, $x=12.5, $y=12.5, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
        PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
        print_r(url('/storage/app/template/'.$name.'.pdf'));
    } 




	public function printPdfSmall(Request $request)
    {
		 
		  $name=$request->name;
		 $svgarr = explode(',_',$request->svg);
         //$svg=urldecode($request->svg);
         define ('PDF_FONT_NAME_MAIN', 'calibrib');
         define ('PDF_FONT_NAME_DATA', 'calibrib');
//          print_r($svg);
        PDF::setImageScale(1.66);
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
        PDF::SetFont('calibrib');
		PDF::AddPage('P', 'A4');
		//PDF::RoundedRect(10, 10, 100, 70, 0, '0000');
		//PDF::RoundedRect(12.5, 12.5, 95, 65, 0, '0000');
		 
		$style6 = array('width' => 0.353, 'cap' => '', 'join' => '', 'dash' => '', 'color' => '');
        PDF::RoundedRect(10, 10, 100, 70, 0.000001, '1111', null, $style6);
        PDF::RoundedRect(12.5, 12.5, 95, 65, 0.000001, '1111', null, $style6);
		 
//		PDF::SetAutoPageBreak(TRUE);
		$i=0;
		$j=0;
		$yy=12.5;
		foreach($svgarr as $value)
        {
			$svg=urldecode($value);
			
			if($i == 3)
			{
				PDF::AddPage('P', 'A4');
				//PDF::RoundedRect(10, 10, 100, 70, 0, '0000');
				//PDF::RoundedRect(12.5, 12.5, 95, 65, 0, '0000');
				
				PDF::RoundedRect(10, 10, 100, 70, 0.000001, '1111', null, $style6);
        		PDF::RoundedRect(12.5, 12.5, 95, 65, 0.000001, '1111', null, $style6);
				$yy=12.5;
				PDF::ImageSVG('@' . $svg, $x=12.5, $y=$yy, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
				$i = 0;
			}
			else  if($i == 0)
			{
				//PDF::RoundedRect(10, 100, 100, 70, 0, '0000');
				//PDF::RoundedRect(12.5, 102.5, 95, 65, 0, '0000');
				
				PDF::RoundedRect(10, 100, 100, 70, 0.000001, '1111', null, $style6);
        		PDF::RoundedRect(12.5, 102.5, 95, 65, 0.000001, '1111', null, $style6);
				$yy=12.5;
				PDF::ImageSVG('@' . $svg, $x=12.5, $y=$yy, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
				
			}
			else if($i == 1)
			{
				//PDF::RoundedRect(10, 100, 100, 70, 0, '0000');
				//PDF::RoundedRect(12.5, 102.5, 95, 65, 0, '0000');
				PDF::RoundedRect(10, 100, 100, 70, 0.000001, '1111', null, $style6);
        		PDF::RoundedRect(12.5, 102.5, 95, 65, 0.000001, '1111', null, $style6);
				$yy += 90;
				PDF::ImageSVG('@' . $svg, $x=12.5, $y=$yy, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			}
			else
			{
				//PDF::RoundedRect(10, 190, 100, 70, 0, '0000');
				//PDF::RoundedRect(12.5, 192.5, 95, 65, 0, '0000');
				PDF::RoundedRect(10, 190, 100, 70, 0.000001, '1111', null, $style6);
        		PDF::RoundedRect(12.5, 192.5, 95, 65, 0.000001, '1111', null, $style6);
				$yy += 90;
				PDF::ImageSVG('@' . $svg, $x=12.5, $y=$yy, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			}
			$i++;
			$j++;
		 }
        PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
        print_r(url('/storage/app/template/'.$name.'.pdf'));
    }

		/**
		 * Function to update uuid value for existing data in erp_order_details table.
		 * @author <Praveen>
		 */
		public function scriptUpdateUUID()
		{
			$order_details = DB::table('erp_order_details')->select('erp_order_id', 'style_no', 'order_qnty_table_name', 'created_at', 'buyer_po_number', 'order_type')->groupBy('erp_order_id', 'style_no', 'order_qnty_table_name', 'created_at', 'buyer_po_number', 'order_type')->get();
			
			foreach($order_details as $value)
			{
				// unique id for array creation
				$unique_id = 'ERP'.$value->erp_order_id.str_random(25);
			
				ErpOrderDetails::where(['erp_order_id' => $value->erp_order_id, 'style_no' => $value->style_no, 'order_qnty_table_name'=> $value->order_qnty_table_name, 'created_at'=> $value->created_at, 'buyer_po_number'=> $value->buyer_po_number, 'order_type'=> $value->order_type])
					->update([
								'uuid'  => $unique_id
							]);
				
			}
			
			return "uuid updated successfully !!!!";
		}

}