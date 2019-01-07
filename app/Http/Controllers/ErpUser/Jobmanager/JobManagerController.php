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
     * Show the Po Manager List View.
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
									->select('erpuser_company_mapping.*','company.name')
									->get();
			$buyers      = 	DB::table('erpuser_buyer')
									->where('erpuser_buyer.erp_user',$customer_id)
									->pluck('erpuser_buyer.buyer')
									->all();

			$company_id   = DB::table('company')
									->select('company.*')
									->first();
			
			
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
											->where('erp_order_details.template_id', '=', $valuess)
											->where('erp_orders.status', '=', 3)
											->where('erp_orders.order_status_id', '=', 4)
											->whereIn('erp_orders.buyer_id', $buyers)
											->orderBy('erp_order_details.erp_order_id', 'asc')
											->groupBy('erp_order_details.template_id', 'erp_orders.id', 'customers.company_name', 'erp_status.status_name', 'buyers.id', 'erp_order_details.reference_name', 'erp_order_details.style_no', 'erp_order_details.delivery_date', 'erp_order_details.erp_order_id')
											->select('erp_orders.*','erp_order_details.reference_name', 'erp_order_details.style_no', 'erp_order_details.delivery_date', 'customers.company_name', 'erp_status.status_name', 'buyers.buyer_name', 'buyers.id as buyer_id')
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
									->pluck('erp_order_details.id')->all();

				$order_detail_ids = array_merge($order_detail_ids,$order_detail_id_array);
			}

 			sort($order_detail_ids);

			$workorder_id = substr(number_format(Carbon::now()->timestamp * rand(),0,'',''),0,8);

			foreach($order_detail_ids as $order_detail_id)
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
					$ratio_templates[$order_details->reference_name][] = DB::table('erp_order_details')->select('erp_order_details.order_quantity', $order_details->order_qnty_table_name.'.ratio_pack_type', $order_details->order_qnty_table_name.'.id as qty_table_id',  'erp_order_details.reference_name', 'erp_order_details.ocn_number', 'erp_orders.erp_id', 'erp_orders.buyer_po_number', 'erp_order_details.id as erp_order_details_id', 'erp_order_details.template_id')
						->join($order_details->order_qnty_table_name, 'erp_order_details.order_qnty_table_id', $order_details->order_qnty_table_name.'.id')
						->join('erp_orders', 'erp_order_details.erp_order_id', 'erp_orders.id')
						->where('erp_order_details.id', '=', $order_detail_id)
						->first();
				}
				else
				{
					$single_temp_data = DB::table('erp_orders')->select('erp_order_details.ocn_number', 'erp_orders.erp_id', 'erp_orders.buyer_po_number', 'erp_order_details.id as erp_order_details_id', 'erp_orders.id as erp_table_id', 'erp_order_details.size', 'erp_order_details.order_quantity', 'erp_order_details.reference_name', 'erp_order_details.template_id')
								->join('erp_order_details', 'erp_order_details.erp_order_id', 'erp_orders.id')
								->where('erp_order_details.id', '=', $order_detail_id)
								->first();
								
					$erp_id_array[$single_temp_data->reference_name][] = $single_temp_data->erp_id;

					$single_templates[$single_temp_data->reference_name]['order_data'][$single_temp_data->erp_id]['ocn_number'] = $single_temp_data->ocn_number;
					$single_templates[$single_temp_data->reference_name]['order_data'][$single_temp_data->erp_id]['erp_id'] = $single_temp_data->erp_id;
					$single_templates[$single_temp_data->reference_name]['order_data'][$single_temp_data->erp_id]['buyer_po_number'] = $single_temp_data->buyer_po_number;
					$single_templates[$single_temp_data->reference_name]['order_data'][$single_temp_data->erp_id]['template_id'] = $single_temp_data->template_id;
					$single_templates[$single_temp_data->reference_name]['size'][$single_temp_data->size][$single_temp_data->erp_id]= $single_temp_data->order_quantity;
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
}