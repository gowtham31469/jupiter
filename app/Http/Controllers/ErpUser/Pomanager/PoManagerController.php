<?php
namespace App\Http\Controllers\ErpUser\Pomanager;

use DB;
use Auth;
use View;
use Session;
use Response;
use App\History;
use Carbon\Carbon;
use App\ErpOrders as Erp;
use Illuminate\Http\Request;
use App\Classes\ErpCompanySession;
use App\Classes\TescoMaterialQuery;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Input;
use App\Classes\ErpUserMappedTemplates;
use Illuminate\Support\Facades\Validator;
use App\ErpOrderDetails as ErpOrderDetails;

class PoManagerController extends Controller
{
	private $current_date;
	private $tesco_material_obj;
	
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		$this->middleware('erpuser');
		$role = "PO MANAGER";
        $this->middleware('role_permission:'.$role);
		// get the current time
		$this->current_date = Carbon::now('Asia/Kolkata')->toDateTimeString();

		$this->tesco_material_obj = new TescoMaterialQuery();
   
    }

    /**
     * Show the Po Manager List View.
     * @author [Gowtham] <[gowtham@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    /**
     * Build the full set of PO-verify candidate rows (status Active/Hold, permitted
     * templates for the current company/buyer). Shared by index() (for the new/hold
     * counts) and poVerifyData() (for the server-side-paginated table) so both stay
     * in sync with the exact same business rules - this loop is unchanged from the
     * original index() implementation, only extracted so it isn't duplicated.
     * @return array
     */
    private function getPoVerifyOrders()
    {
		$erp_user_mapped_template_obj = new ErpUserMappedTemplates();
        $new_orders = $erp_user_mapped_template_obj->getPermittedTemplates();

		 $orders_erp = array();

		 foreach($new_orders as $key => $values)
		 {
			$split_key = explode('-', $key);
			$buyer_id = $split_key[0];
			foreach($values as $valuess)
			{
				$erp_order_details = Erp::join('erp_order_details','erp_order_details.erp_order_id','erp_orders.id')
										 ->join('customers','erp_orders.customer_id','customers.id')
										 ->join('erp_status','erp_orders.status','erp_status.id')
										 ->where('erp_order_details.company_id',session()->get('company'))
										 ->where('erp_order_details.template_id',$valuess)
										 ->where('erp_orders.buyer_id', $buyer_id)
										 ->where(function($q) {
											$q->where('erp_orders.status',1)
											->orWhere('erp_orders.status',2);
										 })
										 ->groupBy('erp_orders.id','customers.company_name','erp_status.status_name','erp_order_details.created_at')
										 ->select('erp_orders.*','customers.company_name','erp_status.status_name','erp_order_details.created_at as po_created_date')
										 ->get();

				if(count($erp_order_details) > 0)
				{
					foreach($erp_order_details as $erp_order_det)
					{
						// format po created date to dd-mm-yyyy
						if($erp_order_det["po_created_date"] != null)
						{
							 $erp_order_det["po_created_date"] = Carbon::parse($erp_order_det["po_created_date"])->format('d-m-Y');
						}

						if (array_key_exists($erp_order_det->id,$orders_erp))
						{
							if($orders_erp[$erp_order_det->id]['po_created_date'] < $erp_order_det['po_created_date'])
							{
								$orders_erp[$erp_order_det->id]['po_created_date']=$erp_order_det['po_created_date'];
							}
						}
						else
						{
							$orders_erp[$erp_order_det->id] = $erp_order_det;
						}
					}
				}
			}

		 }

		usort($orders_erp, function($a, $b) {
			return $b['created_at'] <=> $a['created_at'];
		});

		return array_values($orders_erp);
    }

    public function index()
    {
		$new_orders_count=0;
		$hold_orders_count=0;

		$set_company_session = new ErpCompanySession();

        $companies = $set_company_session->setCompanySession();

		$orders_erp = $this->getPoVerifyOrders();

		foreach($orders_erp as $orders_erp_count)
		{

				if($orders_erp_count->status == 1)
				{
					$new_orders_count++;
				}
				else if($orders_erp_count->status == 2)
				{
					$hold_orders_count++;
				}
		}

		return view('erp.Pomanager.poverify',['companies' => $companies,'new_orders' => $new_orders_count,'hold_orders' => $hold_orders_count]);
	}

	/**
	 * Server-side DataTables endpoint for the PO Verify list - was previously rendered
	 * entirely into the initial page load (all matching rows at once). The underlying
	 * data comes from several merged queries (see getPoVerifyOrders()), not a single
	 * paginatable query, so search/sort/pagination are applied in PHP over that result
	 * rather than pushed down to SQL.
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function poVerifyData(Request $request)
	{
		$orders_erp = $this->getPoVerifyOrders();
		$records_total = count($orders_erp);

		$search = trim((string) $request->input('search.value', ''));
		if ($search !== '') {
			$search_lower = strtolower($search);
			$orders_erp = array_values(array_filter($orders_erp, function($vals) use ($search_lower) {
				$partial_text = $vals->partial_status == 0 ? 'no' : 'yes';
				$haystacks = [
					(string) $vals->erp_id,
					(string) $vals->company_name,
					(string) $vals->po_no,
					$partial_text,
					(string) $vals->status_name,
					(string) $vals->po_created_date,
				];
				foreach ($haystacks as $haystack) {
					if (strpos(strtolower($haystack), $search_lower) !== false) {
						return true;
					}
				}
				return false;
			}));
		}

		$records_filtered = count($orders_erp);

		// Sortable columns map to the DataTables column index (see poverify.blade.php).
		// Column 0 (Sl.No), 5 (Status badge) and 7 (Actions) aren't real sortable fields.
		$sortable_columns = [1 => 'erp_id', 2 => 'company_name', 3 => 'po_no', 4 => 'partial_status', 6 => 'po_created_date'];
		$order = $request->input('order');
		if (!empty($order) && isset($sortable_columns[$order[0]['column']])) {
			$sort_field = $sortable_columns[$order[0]['column']];
			$sort_dir   = ($order[0]['dir'] ?? 'asc') === 'desc' ? -1 : 1;
			usort($orders_erp, function($a, $b) use ($sort_field, $sort_dir) {
				return $sort_dir * strcasecmp((string) $a->$sort_field, (string) $b->$sort_field);
			});
		}
		// else: keep getPoVerifyOrders()'s default order (by erp_orders.created_at desc),
		// matching the previous non-interactive behaviour on first load.

		$start  = max(0, (int) $request->input('start', 0));
		$length = (int) $request->input('length', 10);
		$page_items = $length > 0 ? array_slice($orders_erp, $start, $length) : $orders_erp;

		$data = [];
		foreach ($page_items as $index => $vals) {
			$status_class = $vals->status == 2 ? 'label label-danger' : 'label label-info';
			$status_html  = '<span class="'.$status_class.'">'.e($vals->status_name).'</span>';

			$onclick_args = implode(',', array_map(function($v) {
				return "'".addslashes(e((string) $v))."'";
			}, [$vals->id, $vals->po_no, $vals->buyer_id, $vals->customer_id, $vals->po_file]));
			$actions_html = '<a class="pi_generation" href="javascript:void(0)" onclick="view_order_details('.$onclick_args.')"><i class="fa fa-eye action-button-view" title="" data-original-title="view Details"></i></a>';

			$data[] = [
				'sl_no'           => $start + $index + 1,
				'erp_id'          => $vals->erp_id,
				'company_name'    => $vals->company_name,
				'po_no'           => $vals->po_no,
				'partial_status'  => $vals->partial_status == 0 ? 'NO' : 'YES',
				'status_html'     => $status_html,
				'po_created_date' => explode(' ', $vals->po_created_date)[0] ?? '',
				'actions'         => $actions_html,
				'DT_RowId'        => $vals->id,
			];
		}

		return response()->json([
			'draw'            => (int) $request->input('draw', 1),
			'recordsTotal'    => $records_total,
			'recordsFiltered' => $records_filtered,
			'data'            => $data,
		]);
	}

	public function poOrdersList()
	{
		
		$customer_id = Auth::guard('erpuser')->id();
		
		$companies   = 	DB::table('erpuser_company_mapping')
								->join('company','erpuser_company_mapping.company','company.id')
								->where('erpuser_company_mapping.erp_user',$customer_id)
								->select('erpuser_company_mapping.*','company.name', 'company.logo')
								->get();
		
		return view('erp.Pomanager.po-orders-list',['companies' => $companies]);
	}
	
	public function getOrderDetails(Request $request)
	{
		  try {
            $erp_id = $request->erp_id; 
			  
			$data   = 	Erp::where('erp_orders.erp_id',$erp_id)
								->select('erp_orders.*')
								->get();
			  
			if(count($data) > 0)
			{
					$buyer_id = $data{0}->buyer_id;
					$customer_id = $data{0}->customer_id;
					$id = $data{0}->id;


				   $arr_template =  DB::table('buyers')
									 ->where('id',$buyer_id)
									 ->first();

					$orderlist   = ErpOrderDetails::join($arr_template->template_table,'erp_order_details.template_id',$arr_template->template_table.'.item_id')
									->join('erp_status','erp_order_details.status','erp_status.id')
									->where('erp_order_details.order_status_id','!=',3)
									->where('erp_order_details.erp_order_id',$id)
									->select('erp_order_details.*',$arr_template->template_table.'.template_name','erp_status.status_name')
									->get();

						$orders   = Erp::where('erp_orders.id',$id)
									->join('customers','erp_orders.customer_id','customers.id')
									->select('erp_orders.*','customers.company_name')
									->get();

				   foreach($orderlist as $value)
				   {
					  $price =  DB::table('price_taxation')->select('price_taxation.*')->where('customer_id',$customer_id)->where('buyer_id',$buyer_id)->where('template_id',$value->template_id)->first();

					   if (empty($price)) {
						  $value->price = 0;
					   }
					   else
					   {
						  $price_range =  DB::table('price_range')->select('price_range.*')->where('price_taxation_id',$price->id)->where('volume_from','<=',$value->order_quantity)->where('volume_to','>=',$value->order_quantity)->first();

						   if(empty($price_range))
						   {
							   $value->price = 0;	 
						   }
						   else
						   {
								$value->price = $price_range->price;
						   }
					   }


				   }
				
				   $result      = response()->json(['error'=> false,'message' =>'success','orderlist'=>$orderlist,'orders'=>$orders],200);
			}
		    else
		    {
					$result      = response()->json(['error'=> false,'message' =>'empty'],200);
		    }
			  
		
			   
           
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}
	
	/**
     * Update the status of PO.
     * @author [Gowtham] <[gowtham@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function changePoUpdatedStatus(Request $request)
    {
		
		$erp_order_id = $request->id;
		try { 
          $data 		=   DB::table('erp_orders')->where('id',$erp_order_id)
						  		->update([
								  'po_updated_status' => 0
								]);
		 $result        = response()->json(['error'=> false,'message' =>'success'],200);
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
		
	}
	
	 /**
     * Show the Erp order details.
     * @author [Gowtham] <[gowtham@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
	public function orderDetails(Request $request)
    {
		   try {
            $id = $request->id; 
			$buyer_id = $request->buyer_id;
			$customer_id = $request->customer_id;
			   
			$arr_template =  DB::table('buyers')
								->where('id',$buyer_id)
								->first();

			$orders   = Erp::where('erp_orders.id',$id)
							->join('customers','erp_orders.customer_id','customers.id')
							->select('erp_orders.*','customers.company_name')
							->get();   

			$company_id = DB::table('company')
							->select('company.*')
							->first();

			if(session()->get('company') == "" && session()->get('company') == null)
			{
				session()->put('company', $company_id->id);
				session()->put('company_name', strtolower( str_replace(' ','',$company_id->name)) );
			}

			$orderlist   = ErpOrderDetails::select('erp_order_details.*',$arr_template->template_table.'.template_name','erp_status.status_name')
							->join($arr_template->template_table,'erp_order_details.template_id',$arr_template->template_table.'.item_id')
							->where('erp_order_details.company_id',session()->get('company'))
							->where('erp_order_details.order_quantity', '!=' ,0)
							->join('erp_status','erp_order_details.status','erp_status.id')
							->where('erp_order_details.erp_order_id',$id)
							->where('erp_order_details.status','!=',4)
							->where($arr_template->template_table.'.flag', '=', 0)
							->orderBy('erp_order_details.buyer_po_number','asc')
							->orderBy('erp_order_details.reference_name','asc')
                            ->get();
			
		    foreach($orderlist as $value)
		    {
			  $price =  DB::table('price_taxation')->select('price_taxation.*')->where('customer_id',$customer_id)->where('buyer_id',$buyer_id)->where('template_id',$value->template_id)->first();
			   	
			   if (empty($price)) {
				  $value->price = 0;
				 
			   }
			   else
			   {
				  $price_range =  DB::table('price_range')->select('price_range.*')->where('price_taxation_id',$price->id)->where('volume_from','<=',$value->order_quantity)->where('volume_to','>=',$value->order_quantity)->first();

				   if(empty($price_range))
				   {
					   $value->price = 0;	 
				   }
				   else
				   {
					   $value->price = $price_range->price;
				   }
			   }
		    }
			   
            $result      = response()->json(['error'=> false,'message' =>'success','orderlist'=>$orderlist,'orders'=>$orders],200);
        }
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}
	/**
     * Submit Po.
     * @author [Gowtham] <[gowtham@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
	public function submitPoVerify(Request $request)
	{
		try {
			$po = $request->po;
			$style_no = $request->style_no;
			$reference = $request->reference;
			$qty = $request->qty;
			$price = $request->price;
			$po_complete = $request->po_complete;
			$comment = $request->comment;
			$commenttext = $request->commenttext;
			$count = $request->count_i;
			$count_j = $request->count_j;
			$erp_order_id = $request->erp_order_id;
			$erp_od_ids = $request->ids;
			$purchase_order_number = $request->purchase_order_number;
			$customer_name = $request->customer_name;
			$erp_user     = Auth::guard('erpuser')->id();

			// Get Erp status table id for 'Jobcard Created' and 'Cancelled' status.
			$erp_status_ids = DB::table('erp_status')->whereIn('status_name', ['Jobcard Created', 'Cancelled', 'Moved To JC Queue', 'Order Dispatched', 'Partially Dispatched'])->pluck('id')->all();

			$orderlist   = ErpOrderDetails::join('erp_orders', 'erp_order_details.erp_order_id', 'erp_orders.id')
					->where('erp_order_details.order_quantity', '!=' ,0)
					->where('erp_order_details.erp_order_id', $erp_order_id)
					->whereNotIn('erp_order_details.status', $erp_status_ids)
					->whereIn('erp_order_details.id', $erp_od_ids)
					->select('erp_order_details.*', 'erp_orders.customer_id', 'erp_orders.po_no', 'erp_orders.buyer_id')
					->get();

			$customer_list =  DB::table('customers')->select('*')->where('customers.id', $orderlist{0}->customer_id)->get();

			// Approve orders
			if ($count == 0) {

				foreach ($orderlist as $value) {
					$po_rejected_date = $value->po_rejected_date;
					$price =  DB::table('price_taxation')->select('price_taxation.id')->where('customer_id', $value->customer_id)->where('buyer_id', $value->buyer_id)->where('template_id', $value->template_id)->get();

					if(sizeof($price) > 0) {
						$price_range =  DB::table('price_range')->select('price_range.price')->where('price_taxation_id', $price{0}->id)->where('volume_from', '<=', $value->order_quantity)->where('volume_to', '>=', $value->order_quantity)->get();
					} else {
                        return response()->json(['error' => true, 'message' => 'Price details should be within a range'], 422);
					}

					if(sizeof($price_range) > 0) {
						// For first time approving, update po approved date .
						// if approving a rejected order , then update reapproved date
						$query = ErpOrderDetails::where('id', $value->id)
						->whereNotIn('status', $erp_status_ids);

						if($po_rejected_date === null || $po_rejected_date === '') {
							$action_id  = DB::table('actions')->where('action', 'ilike', 'PO Manager Verified')->value('id');
							$query = $query->update([
										'rate' => $price_range{0}->price,
										'status' => 3,
										'reason' => $commenttext,
										'po_manager_approved_by' => $erp_user,
										'po_verified_date' => $this->current_date
									]);
						} else {
							$action_id  = DB::table('actions')->where('action', 'ilike', 'PO Manager Reapproved')->value('id');
							$query->update([
								'rate' => $price_range{0}->price,
								'status' => 3,
								'reason' => $commenttext,
								'po_manager_approved_by' => $erp_user,
								'po_reapproved_date' => $this->current_date
							]);
						}

						// insert  into history table with action id
						$res = $this->insertHistoryData($value, $action_id, $erp_user, $commenttext);
					} else {
                        return response()->json(['error' => true, 'message' => 'Price details should be within a range'], 422);
					}
				}
				
				$data 		=   DB::table('erp_orders')->where('id', $erp_order_id)
					->update([
						'status' => 3,
						'partial_status' => 0
					]);
				$result      = response()->json(['error' => false, 'message' => 'success', 'status' => true], 200);

			} else if ($count_j == $count) {

				foreach ($orderlist as $value) {
					$po_rejected_date = $value->po_rejected_date;
					$price =  DB::table('price_taxation')->select('price_taxation.id')->where('customer_id', $value->customer_id)->where('buyer_id', $value->buyer_id)->where('template_id', $value->template_id)->get();

					if(sizeof($price) > 0) {
						$price_range =  DB::table('price_range')->select('price_range.price')->where('price_taxation_id', $price{0}->id)->where('volume_from', '<=', $value->order_quantity)->where('volume_to', '>=', $value->order_quantity)->get();
					} else {
                        return response()->json(['error' => true, 'message' => 'Price details should be within a range'], 422);
					}

					if(sizeof($price_range) > 0) {
						// For first time approving, update po approved date .
						// if approving a rejected order , then update reapproved date
						$query = ErpOrderDetails::where('id', $value->id)
						->whereNotIn('status', $erp_status_ids);

						if($po_rejected_date === null || $po_rejected_date === '') {
							$action_id  = DB::table('actions')->where('action', 'ilike', 'PO Manager Verified')->value('id');
							$query = $query->update([
										'rate' => $price_range{0}->price,
										'status' => 3,
										'reason' => $commenttext,
										'po_manager_approved_by' => $erp_user,
										'po_verified_date' => $this->current_date
									]);
						} else {
							$action_id  = DB::table('actions')->where('action', 'ilike', 'PO Manager Reapproved')->value('id');
							$query->update([
								'rate' => $price_range{0}->price,
								'status' => 3,
								'reason' => $commenttext,
								'po_manager_approved_by' => $erp_user,
								'po_reapproved_date' => $this->current_date
							]);
						}

						// insert  into history table with action id
						$res = $this->insertHistoryData($value, $action_id, $erp_user, $commenttext);
					} else {
                        return response()->json(['error' => true, 'message' => 'Price details should be within a range'], 422);
					}
				}

				// approve partial orders
				$data 	=   DB::table('erp_orders')->where('id', $erp_order_id)
								->update([
									'status' => 3,
									'partial_status' => 1
								]);

				$to_email =  DB::table('tesco_error_report_emails')->select('*')->where('id', 1)->first();

				Mail::send('mails.error-report-po', ['po' => $po, 'style_no' => $style_no, 'reference' => $reference, 'qty' => $qty, 'price' => $price, 'po_complete' => $po_complete, 'comment' => $comment, 'commenttext' => $commenttext, 'purchase_order_number' => $orderlist{0}->po_no, 'customer_name' => $customer_list{0}->company_name, 'erp_order_id' => $erp_order_id], function ($message) use ($to_email,$customer_list) {
					$message->from('admin@jga.com');
					$message->subject('Jupiter Garments Accessories - Error Report');
					$message->to($to_email->email_address);
					$message->cc($customer_list{0}->email);
				});
				$result      = response()->json(['error' => false, 'message' => 'success', 'status' => true], 200);

			} else {
				$action_id  = DB::table('actions')->where('action', 'ilike', 'PO Manager Rejected')->value('id');
				$data 		=   DB::table('erp_orders')->where('id', $erp_order_id)
					->update([
						'status' => 2,
						'partial_status' => $count_j
					]);

				$data_erp		=   ErpOrderDetails::where('erp_order_id', $erp_order_id)
					->whereNotIn('status', $erp_status_ids)
					->update([
						'status' => 2,
						'reason' => $commenttext,
						'po_rejected_date' => $this->current_date,
						'po_rejected_by' => $erp_user,
					]);
				// insert  into history table with action id
				foreach ($orderlist as $value) {

					$this->insertHistoryData($value, $action_id, $erp_user, $commenttext);	
				}	
				$to_email =  DB::table('tesco_error_report_emails')->select('*')->where('id', 1)->first();

				Mail::send('mails.error-report-po', ['po' => $po, 'style_no' => $style_no, 'reference' => $reference, 'qty' => $qty, 'price' => $price, 'po_complete' => $po_complete, 'comment' => $comment, 'commenttext' => $commenttext, 'purchase_order_number' => $orderlist{0}->po_no, 'customer_name' => $customer_list{0}->company_name, 'erp_order_id' => $erp_order_id], function ($message) use ($to_email,$customer_list) {
					$message->from('admin@jga.com');
					$message->subject('Jupiter Garments Accessories - Error Report');
					$message->to($to_email->email_address);
					$message->cc($customer_list{0}->email);
				});
				$result      = response()->json(['error' => false, 'message' => 'success', 'status' => false], 200);
			}

		} catch (\Exception $e) {
			$result = response()->json(['Message' => $e->getMessage(), 'Line' => $e->getLine(), 'File' => $e->getFile()]);
		}
		return $result;
	}

	/** Function to insert OR update history table
	 * @author praveen <praveen@wizroots.com>
	 * @param mixed $data, $action_id, $erp_user
	 */
	public function insertHistoryData($data, $action_id, $erp_user, $commenttext)
	{
		try {
				$history_id = History::where('erp_order_details_id', $data->id)->value('id');

				DB::table('event_history')
				->insert([
					'history_id'  => $history_id,
					'action_id'   => $action_id,
					'created_by'  => $erp_user,
					'reason'      => $commenttext,
					'created_at'  => $this->current_date,
					'updated_at'  => $this->current_date,
				]);

			return "success";
		} catch (\Exception $e) {
			return response()->json(['Message' => $e->getMessage(), 'Line' => $e->getLine(), 'File' => $e->getFile()]);
		}
	}
}
