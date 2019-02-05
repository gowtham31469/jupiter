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
									->join('company','erpuser_company_mapping.company','company.id', 'company.logo')
									->where('erpuser_company_mapping.erp_user',$customer_id)
									->select('erpuser_company_mapping.*','company.name')
									->get();
			$buyers      = 	DB::table('erpuser_buyer')
									->where('erpuser_buyer.erp_user',$customer_id)
									->pluck('erpuser_buyer.buyer')
									->all();

			$company_id   = DB::table('company')
									->select('company.*')
									->first();
			// Get Erp status table id for 'Moved To JC Queue' status.
			$erp_status_id = DB::table('erp_status')->where('status_name', 'ilike', 'Moved To JC Queue')->value('id');
			
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
					$erp_order_details = DB::table('erp_orders')->join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
											->join('customers', 'erp_orders.customer_id', 'customers.id')
											->join('buyers', 'erp_orders.buyer_id', 'buyers.id')
											->join('erp_status', 'erp_orders.status', 'erp_status.id')
						                    ->join('tesco_template', 'tesco_template.item_id', 'erp_order_details.template_id')
											->where('erp_order_details.template_id', '=', $valuess)
											->where('erp_orders.status', '=', $erp_status_id)
											->where('erp_order_details.status', '=', $erp_status_id)
											->where('erp_orders.order_status_id', '=', 4)
											->whereIn('erp_orders.buyer_id', $buyers)
											->orderBy('erp_order_details.erp_order_id', 'asc')
											->groupBy('erp_order_details.template_id', 'erp_orders.id', 'customers.company_name', 'erp_status.status_name', 'buyers.id', 'erp_order_details.reference_name', 'erp_order_details.style_no', 'erp_order_details.buyer_po_number', 'erp_order_details.delivery_date', 'erp_order_details.erp_order_id','tesco_template.template_file')
											->select('erp_orders.*','erp_order_details.reference_name', 'erp_order_details.style_no', 'erp_order_details.buyer_po_number', 'erp_order_details.delivery_date', 'customers.company_name', 'erp_status.status_name', 'buyers.buyer_name', 'buyers.id as buyer_id','tesco_template.template_file')
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
			asort($orders_erp);
			return view('erp.Jobmanager.jobmanager', ['orders' => $orders_erp, 'companies' => $companies]);
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
		$order_details  = 	DB::table('erp_order_details')
									->where('erp_order_details.style_no',$style_no)
									->where('erp_order_details.erp_order_id',$id)
									->where('erp_order_details.reference_name',$ref_name)
									->where('erp_order_details.buyer_po_number',$buyer_no)
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
			$buyers_array       = array();
			$customers_array    = array();
			$company_array      = array();
			$order_dt_array     = array();
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
									->pluck('erp_order_details.id')->all();

				$order_detail_ids = array_unique(array_merge($order_detail_ids,$order_detail_id_array));
			}
 			sort($order_detail_ids);

			$workorder_id = substr(number_format(Carbon::now()->timestamp * rand(),0,'',''),0,8);

			foreach($order_detail_ids as $key => $order_detail_id)
			{
				$order_details = DB::table('erp_orders')
									->select('erp_orders.*', 'erp_order_details.*')
									->join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
									->where('erp_order_details.id', '=', $order_detail_id)
									->first();

				!in_array($order_details->buyer_id, $buyers_array)?array_push($buyers_array,$order_details->buyer_id):$buyers_array;

				!in_array($order_details->customer_id, $customers_array)?array_push($customers_array,$order_details->customer_id):$customers_array;

				!in_array($order_details->company_id, $company_array)?array_push($company_array,$order_details->company_id):$company_array;

				!in_array($order_details->created_at, $order_dt_array)?array_push($order_dt_array,$order_details->created_at):$order_dt_array;

				!in_array($order_details->style_no, $style_array)?array_push($style_array,$order_details->style_no):$style_array;

				!in_array($order_details->order_type, $ordertype_array)?array_push($ordertype_array,$order_details->order_type):$ordertype_array;

				!in_array($order_details->template_id, $temp_id_array)?array_push($temp_id_array,$order_details->template_id):$temp_id_array;

				!in_array($order_details->reference_name, $distinct_ref_array)?array_push($distinct_ref_array,$order_details->reference_name):$distinct_ref_array
				;

				/** Create separate ratio/single templates array  */
				if($order_details->template_type == "ratio")
				{
					$ratio_templates[$order_details->reference_name][] = DB::table('erp_order_details')->select('erp_order_details.order_quantity', $order_details->order_qnty_table_name.'.ratio_pack_type', $order_details->order_qnty_table_name.'.id as qty_table_id',  'erp_order_details.reference_name', 'erp_order_details.ocn_number', 'erp_orders.erp_id', 'erp_order_details.id as erp_order_details_id', 'erp_order_details.template_id', 'erp_order_details.buyer_po_number')
						->join($order_details->order_qnty_table_name, 'erp_order_details.order_qnty_table_id', $order_details->order_qnty_table_name.'.id')
						->join('erp_orders', 'erp_order_details.erp_order_id', 'erp_orders.id')
						->where('erp_order_details.id', '=', $order_detail_id)
						->first();
				}
				else
				{
					$single_temp_data = DB::table('erp_orders')->select('erp_order_details.ocn_number', 'erp_orders.erp_id', 'erp_order_details.id as erp_order_details_id', 'erp_orders.id as erp_table_id', 'erp_order_details.size', 'erp_order_details.order_quantity', 'erp_order_details.reference_name', 'erp_order_details.template_id', 'erp_order_details.buyer_po_number')
								->join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
								->where('erp_order_details.id', '=', $order_detail_id)
								->first();

					$erp_id_array[$single_temp_data->reference_name][] = $single_temp_data->erp_id.'-'.$single_temp_data->buyer_po_number;

					$single_templates[$single_temp_data->reference_name]['order_data'][$single_temp_data->erp_id.'-'.$single_temp_data->buyer_po_number]['ocn_number'] = $single_temp_data->ocn_number;
					$single_templates[$single_temp_data->reference_name]['order_data'][$single_temp_data->erp_id.'-'.$single_temp_data->buyer_po_number]['erp_id'] = $single_temp_data->erp_id;
					$single_templates[$single_temp_data->reference_name]['order_data'][$single_temp_data->erp_id.'-'.$single_temp_data->buyer_po_number]['buyer_po_number'] = $single_temp_data->buyer_po_number;
					$single_templates[$single_temp_data->reference_name]['order_data'][$single_temp_data->erp_id.'-'.$single_temp_data->buyer_po_number]['template_id'] = $single_temp_data->template_id;
					$single_templates[$single_temp_data->reference_name]['size'][$single_temp_data->size][$single_temp_data->erp_id.'-'.$single_temp_data->buyer_po_number]= $single_temp_data->order_quantity;
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

					!in_array($material_array[$template_details->material_id]=$template_details->material, $material_array)?$material_array[$template_details->material_id]=$template_details->material:$material_array;

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
			$template_data['today_date']       = $this->current_date;
			$template_data['workorder_id']     = $workorder_id;
			$template_data['company']          = $company;
			$template_data['customer']         = $customer;
			$template_data['buyer']            = $buyer;
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

			return response()->json(['error'=> false,'message' =>'Success', 'template_data' => $template_data],200);
  
          } catch (\Exception $e)
          {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
          }
	}
	
	/**
     * To store Jobcard data.
     * @author [Praveen] <[praveen@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
	public function storeJobcardData(Request $request)
	{
		try {
				$company_id      = $request['company_id'];
				$workorder_id    = $request['workorder_id'];
				$created_date    = Carbon::parse($request['created_date'],'Asia/Kolkata')->toDateString();
				$article         = $request['article'];
				$material        = $request['material'];
				$customer_id     = $request['customer_id'];
				$style_no        = $request['style_no'];
				$order_type      = $request['order_type'];
				$buyer_id        = $request['buyer_id'];
				$reference       = $request['reference'];
				$disp_mode       = $request['disp_mode'];
				$order_date      = Carbon::parse($request['order_date'],'Asia/Kolkata')->toDateString();
				$length          = $request['length'];
				$repeat_gap      = $request['repeat_gap'];
				$machine         = $request['machine'];
				$cus_del_date    = Carbon::parse($request['cus_del_date'],'Asia/Kolkata')->toDateString();
				$roll_width      = $request['roll_width'];
				$width           = $request['width'];
				$film_no         = $request['film_no'];
				$act_del_date    = Carbon::parse($request['act_del_date'],'Asia/Kolkata')->toDateString();
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
				$approval_date   = Carbon::parse($request['approval_date'],'Asia/Kolkata')->toDateString();
				$roll_issued_by  = $request['roll_issued_by'];
				$cutter          = $request['cutter'];
				$cartons         = $request['cartons'];
				$final_json_obj  = $request['final_json_obj'];
				$erp_orders_ids  = explode(',', $request['erp_orders_ids']);
			    $selected_ref    = explode(',', $request['selected_ref_array']);
				$buyer_pos       = explode(',', $request['selected_buyer_po_array']);

				if(isset($company_id) && isset($workorder_id) && isset($created_date) && isset($article) && isset($material) && isset($customer_id) && isset($buyer_id) && isset($disp_mode) && isset($order_date) && isset($repeat_gap) && isset($machine) && isset($cus_del_date) && isset($roll_width)  && isset($film_no) && isset($act_del_date) && isset($length_side) && isset($excess) && isset($teeth_no)  && isset($inv_no) && isset($no_sticker) && isset($finish_type)  && isset($print_color) && isset($compiled_by) && isset($barcode_read) && isset($operator) && isset($checker) && isset($authorized_by) && isset($axicon_verifier) && isset($approved_by) && isset($packer) && isset($approval_date) && isset($roll_issued_by)  && isset($cutter) && isset($cartons) && isset($final_json_obj) )
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
						'act_del_date'           => $act_del_date,
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
						'created_at'             => Carbon::now('Asia/Kolkata')->toDateTimeString(),
						'updated_at'             => Carbon::now('Asia/Kolkata')->toDateTimeString()
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
									'created_at'              => Carbon::now('Asia/Kolkata')->toDateTimeString(),
									'updated_at'              => Carbon::now('Asia/Kolkata')->toDateTimeString()
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

						$buyer = DB::table('buyers')->select('buyer_name')->where('id', '=', $buyer_id)->first();
						$customer = DB::table('customers')->select('short_name as customer_name')->where('id', '=', $customer_id)->first();
						$company = DB::table('company')->select('name as company', 'address as company_address', 'logo as company_logo')->where('id', '=', $company_id)->first();

						/** [ Creating new object for JobcardPDFHeaderFooter() class ] */
						$pdf = new JobcardPDFHeaderFooter(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
						$pdf->setData($company, $workorder_id, $created_date, $article, $material, $compiled_by, $barcode_read, $operator, $checker, $authorized_by, $axicon_verifier, $approved_by, $packer, $approval_date, $roll_issued_by, $cutter, $cartons);	

						$pdf->SetPrintHeader(true);
						$pdf->SetPrintFooter(true);  

						//margins
						$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
						$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
						$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
						
						// set header and footer fonts
						$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
						$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
						
						// set auto page breaks
						$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

						// set image scale factor
						$pdf->setImageScale(1.8);

						// set font
						$pdf->SetFont('helvetica', '', 10);

						// add a page
						$pdf->AddPage('P', 'A4');

						// set some text to print
						$view = View::make('erp/Jobmanager/jobcard-pdf-template')
								->with([
										'buyer' 		 => $buyer,
										'customer' 		 => $customer,
										'company'		 => $company,
										'workorder_id'   => $workorder_id,
										'created_date'   => Carbon::parse($created_date)->format('d-m-Y'),
										'article'	     => $article,
										'material'		 => $material,
										'style_no'		 => $style_no,
										'order_type'     => $order_type,
										'reference'		 => $reference,
										'disp_mode'	     => $disp_mode,
										'order_date'	 => Carbon::parse($order_date)->format('d-m-Y'),
										'length'		 => $length,
										'repeat_gap'	 => $repeat_gap,
										'machine'		 => $machine,
										'cus_del_date' 	 => Carbon::parse($cus_del_date)->format('d-m-Y'),
										'roll_width'	 => $roll_width,
										'width'	  		 => $width,
										'film_no'		 => $film_no, 
										'act_del_date'   => Carbon::parse($act_del_date)->format('d-m-Y'),
										'length_side'	 => $length_side,
										'excess'	     => $excess,
										'teeth_no'		 => $teeth_no,
										'inv_no'	     => $inv_no,
										'no_sticker'	 => $no_sticker,
										'finish_type'	 => $finish_type,
										'print_color'    => $print_color,
										'compiled_by'    => $compiled_by,
										'barcode_read'   => $barcode_read,
										'operator'       => $operator,
										'checker'        => $checker,
										'authorized_by'  => $authorized_by,
										'axicon_verifier'=> $axicon_verifier,
										'approved_by'	 => $approved_by,
										'packer'		 => $packer,
										'approval_date'  => Carbon::parse($approval_date)->format('d-m-Y'),
										'roll_issued_by' => $roll_issued_by,
										'cutter'		 => $cutter,
										'cartons' 		 => $cartons,
										'final_json_obj' => json_decode($final_json_obj, true),
										'single_ref'	 => $single_ref,
										'act_excess'	 => $act_excess,
										'sticker_mtr'	 => $sticker_mtr,
										'settings'		 => $settings,
										'total_mtrs' 	 => $total_mtrs
								]);

						//append content to header
						$pdf->SetXY(15,49);
						$pdf->writeHTML($view->render());

						//Close and output PDF document
						ob_end_clean();
						$file_path =  "/storage/app/public/jobcards/".$workorder_id."."."pdf";	
						$pdf->IncludeJS("print();");
						$pdf->Output( public_path($file_path), 'F');

						DB::table('jobcard_main_table')->where('id', $store_jobcard_id)
						->update([
							'file_path'   => $file_path,
							'updated_at'  => Carbon::now('Asia/Kolkata')->toDateTimeString()
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
	//	return $result;
	}
	/**
   * Print Preview For Stickers
   *
   * @return pdf
   */
  
    public function printDivTesco(Request $request)
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
        PDF::AddPage('P', 'A4');
		// set auto page breaks
		PDF::SetAutoPageBreak(TRUE);
		$i=0;
		$j=0;
		$yy=12.5;
		foreach($svgarr as $value)
		{
			$svg=urldecode($value);
			file_put_contents(public_path('/storage/app/template/demo_'.$j.'.svg'), $svg);
			// PDF::ImageSVG('@' . $svg, $x=12.5, $y=$yy, $w=127, $h=80, $link='', $align='', $palign='', $border=1, $fitonpage=false);
			$html = '<img style="width:480px;height:302.36220472px;border:1px solid #333" src="'.public_path('/storage/app/template/demo_'.$j.'.svg').'">';
			PDF::writeHTMLCell(129, 82.5, 12.5, $yy, $html, '', 0, 0, false, 'L', false);
			if($i == 3)
			{
				PDF::AddPage('P', 'A4');
				$yy=92.5;
				$i = 0;
			}
			else
			{
				$yy += 85;
			}
			 $i++;
			 $j++;
		}
        PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
        print_r(url('/storage/app/template/'.$name.'.pdf'));
    } 
	 /**
   * Print Preview For Stickers
   *
   * @return pdf
   */
  
    public function printDivTescoSmall(Request $request)
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
        PDF::AddPage('P', 'A4');
		// set auto page breaks
		PDF::SetAutoPageBreak(TRUE);
        $i=0;
		$yy=0;
		$j=0;
		PDF::SetXY(10, 50);
		foreach($svgarr as $value)
		{
			$svg=urldecode($value);
			PDF::SetFillColor(255,255,255);
			if($i == 3 || $i == 6 || $i == 9 || $i == 12 || $i == 18)
			{
				$xx=15;
				$yy+=50;
			}
			else if($i == 0)
			{
				$yy=10;
				$xx=15;
			}
			else if($i == 15)
			{
				PDF::AddPage();
				$xx=15;
				$yy=10;
			}
			else 
			{
				$xx+=70;
			}
			
			// PDF::ImageSVG('@' . $svg, $x=$xx, $y=$yy, $w=40, $h=30, $link='', $align='', $palign='', $border=1, $fitonpage=false);
			
				file_put_contents(public_path('/storage/app/template/demo_'.$j.'.svg'), $svg);
			// PDF::ImageSVG('@' . $svg, $x=12.5, $y=$yy, $w=127, $h=80, $link='', $align='', $palign='', $border=1, $fitonpage=false);
			$html = '<img style="width:151.18110236px;height:113.38582677px;border:1px solid #333" src="'.public_path('/storage/app/template/demo_'.$j.'.svg').'">';
			PDF::writeHTMLCell(42, 32, $xx, $yy, $html, '', 0, 0, false, 'L', false);
			
			 $i++;
			 $j++;
		}
        PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
        print_r(url('/storage/app/template/'.$name.'.pdf'));
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
       } catch (\Exctesteption $e) {
           return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
       }
    }
	
	
	public function scriptUpdateBuyerNo()
	{
		$order_details = DB::table('erp_order_details')->get();
		
		foreach($order_details as $value)
		{
			
			
			$order_details_omp  = 	DB::table($value->order_qnty_table_name)
									->where('id',$value->order_qnty_table_id)
									->select('*')
									->get();
			
			$order_table = str_replace("_quanty","",$value->order_qnty_table_name);
			$order_id  = strtolower(str_replace(" ","",$value->reference_name).'_order_id');
			
			$order_omp  = 	DB::table($order_table)
									->where($order_table.'.id',$order_details_omp{0}->$order_id)
									->select($order_table.'.*')
									->get();
			
			 $data['custom_name']=DB::table('price_taxation')->where('template_id',$order_omp{0}->template_id)->where('customer_id',$order_omp{0}->tesco_supplier_id)->where('buyer_id',2)->select('*')->get();
			
			DB::table('erp_order_details')
							->where('erp_order_details.id', $value->id)
							->update([
								'buyer_po_number'  => $order_omp{0}->po_number,
								'zone'  => $order_omp{0}->zone,
								'custom_ref_name'  => $data['custom_name']{0}->custom_name,
							]);
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
										->select('erpuser_company_mapping.*','company.name')
										->get();
				$company_id   = DB::table('company')
				->select('company.*')
				->first();
				
				if(session()->get('company') == "" && session()->get('company') == null)
				{
					session()->put('company', $company_id->id);
					session()->put('company_name', strtolower( str_replace(' ','',$company_id->name)) );
				}
				$jobcards  = 	DB::table('jobcard_main_table')
								->select('jobcard_main_table.*','customers.company_name',
								'buyers.buyer_name')
								->join('customers', 'jobcard_main_table.customer_id', 'customers.id')
								->join('buyers', 'jobcard_main_table.buyer_id', 'buyers.id')
								->where('jobcard_main_table.company_id', '=', session()->get('company'))
								->get();

				return view('erp.Jobmanager.jobcards-list', ['jobcards' => $jobcards, 'companies' => $companies]);	
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

				if(session()->get('company') == "" && session()->get('company') == null && $customer_id == null)
				{
					return response()->json(['error'=> true,'message' =>'Not Authorized User'], 401);
				}
				else
				{
					$jobcard_data  = DB::table('jobcard_main_table')
										->select('jobcard_main_table.*','customers.company_name',
										'buyers.buyer_name')
										->join('customers', 'jobcard_main_table.customer_id', 'customers.id')
										->join('company', 'company.id', 'jobcard_main_table.company_id')
										->join('buyers', 'jobcard_main_table.buyer_id', 'buyers.id')
										->where('jobcard_main_table.id', '=', $id)
										->first();
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


}