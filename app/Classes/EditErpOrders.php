<?php
namespace App\Classes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Customer;
use App\CustomerBillAddress;
use DB;
use Auth;
use App\RolePermissionMdl;
use Carbon\Carbon;
use App\ErpOrders as Erp;
use App\ErpOrderDetails;

class EditErpOrders
{
	private $updated_at;
	public function updateTescoErpOrderDetails($data_array)
	{
		try
		{			
			$table_name     = $data_array['tableName']; 
			$buyer_id		= $data_array['buyer_id'];
            $row_id		    = $data_array['id'];
            $erp_details_status	= $data_array['erp_details_status'];
            $erp_order_id		 = $data_array['erp_order_id'];
            $qnty_table     = $table_name.'_quanty' ;
			$qnty_id        =  str_replace("_orders","_order_id",str_replace("tesco_","",$table_name));
			// Get Erp status table id for 'Moved To JC Queue' status.
            $erp_status_id = DB::table('erp_status')->where('status_name', 'ilike', $erp_details_status)->value('id');
            
			$this->updated_at = Carbon::now('Asia/Kolkata')->toDateTimeString();
            $orders     	= DB::table($table_name)->where($table_name.'.id',$row_id)
            					->join('tesco_item_master',$table_name.'.template_id','=','tesco_item_master.id')
                                ->select('order_id','po_number','uk_style_ref','ce_style_ref','season_code','short_description','eqos_site_code','manufacture_date','tesco_department_id','tesco_brand_id','tesco_supplier_id as customer_id','rfid_compliant','tag_at_source','zone','template_type','pi_status','po_status','po_file','purchase_order_no','order_status','template_id','delivery_date','order_placed_by','pi_rev_no','edit_permission','cancel_ord_flag','order_created_at','order_approved_at','order_cancelled_at','ocn_number','po_uploaded_at','tesco_item_master.company_id','tesco_item_master.item_name','ocn_number','order_flag','pi_id','pi_file')->first();
            $order_details  = DB::table($qnty_table)->where($qnty_id,$row_id)
            				->get();
			$order_status   = $orders->order_status;

			$customer_data  = DB::table('customers')->where('id',$orders->customer_id)->first();

			if ($customer_data->pi_required == 1 && $customer_data->po_required== 0) {
				$po_no     = $orders->pi_id;
				$po_file   = $orders->pi_file;
			}
			else if($customer_data->po_required== 1){
				$po_no     = $orders->purchase_order_no;
				$po_file   = $orders->po_file;
			}

			$order_status_id  = DB::table('orders_status')->select('id')->where('status_name', 'ilike', $order_status)->first();

            Erp::where('id',$erp_order_id)->update(['customer_id' => $orders->customer_id]);

            $this->updateOrderDetails($qnty_table, $order_details, $orders, $erp_order_id, $buyer_id, $erp_status_id);

			$result = response()->json(['error'=> false,'message' =>'success'],200);
		}
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}

	function updateOrderDetails($qnty_table, $order_details, $orders, $erp_order_id, $buyer_id, $erp_status_id)
	{
		if ($order_details)
		{
			foreach ($order_details as $key => $vals) {

				$style_no = ($orders->zone == "CE") ? $orders->ce_style_ref : $orders->uk_style_ref ;
				$custom_ref_name = DB::table('price_taxation')->where('customer_id',$orders->customer_id)->where('buyer_id',$buyer_id)->where('template_id',$orders->template_id)->value('custom_name');
				$dataExists = DB::table('erp_order_details')->where('order_qnty_table_name',$qnty_table)->where('order_qnty_table_id',$vals->id)->first();
				
				if(!empty($dataExists)){
					
				}
				else{
					ErpOrderDetails::create([
						'erp_order_id'     		=> $erp_order_id,
		            	'style_no'         		=> $style_no,
		           		'order_quantity'   		=> $vals->count,
		           		'pending_orders'		=> $vals->count,
		            	'size'         	   		=> $vals->size,
		            	'delivery_date'	   		=> $orders->delivery_date,
		            	'order_qnty_table_name' => $qnty_table,
		            	'order_qnty_table_id' 	=> $vals->id,
		            	'template_id'			=> $orders->template_id,
		            	'company_id'			=> $orders->company_id,
		            	'reference_name'		=> $orders->item_name,
		            	'ocn_number'	   		=> $orders->ocn_number,
						'status' 				=> $erp_status_id,
						'template_type'         => $orders->template_type,
						'custom_ref_name' 		=> $custom_ref_name,
						'buyer_po_number'  		=> $orders->po_number,
						'zone'					=> $orders->zone,
						'order_type'            => $orders->order_flag,
						'updated_at'            => $this->updated_at
					]);
				}				
			}
		}
	}
}