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

class ErpOrders
{
	private $created_at;
	public function updateTescoErpOrders($request)
	{
		try
		{			
			$table_name     = $request['tableName']; 
			$buyer_id		= $request['buyer_id'];
			$row_id		    = $request['id'];
            $qnty_table     = $table_name.'_quanty' ;
			$qnty_id        =  str_replace("_orders","_order_id",str_replace("tesco_","",$table_name));
			
			$this->created_at = Carbon::now('Asia/Kolkata')->toDateTimeString();
            $orders     	= DB::table($table_name)->where($table_name.'.id',$row_id)
            					->join('tesco_item_master',$table_name.'.template_id','=','tesco_item_master.id')
                                ->select('order_id','po_number','uk_style_ref','ce_style_ref','season_code','short_description','eqos_site_code','manufacture_date','tesco_department_id','tesco_brand_id','tesco_supplier_id as customer_id','rfid_compliant','tag_at_source','zone','template_type','pi_status','po_status','po_file','purchase_order_no','order_status','template_id','delivery_date','order_placed_by','pi_rev_no','edit_permission','cancel_ord_flag','order_created_at','order_approved_at','order_cancelled_at','ocn_number','po_uploaded_at','tesco_item_master.company_id','tesco_item_master.item_name','ocn_number','order_flag')->first();
            $order_details  = DB::table($qnty_table)->where($qnty_id,$row_id)
            				->get();
			$order_status = $orders->order_status;

			$order_status_id  = DB::table('orders_status')->select('id')->where('status_name', 'ilike', $order_status)->first();
			
			if ($orders->purchase_order_no == "" && $orders->po_file == "" && $orders->po_file=="") {
				
			}
			else{
				$erp_orders = Erp::where('buyer_id',$buyer_id)->where('customer_id',$orders->customer_id)->where('po_no',$orders->purchase_order_no)->first();
			}			

			if ($erp_orders) {
				Erp::where('id',$erp_orders->id)->update(['status'=>1]);
				$this->insertOrderDetails($qnty_table,$order_details,$orders,$erp_orders,$buyer_id);
			}
			else{
				$erp_orders =   Erp::create([
									'buyer_id'         => $buyer_id,
				                    'customer_id'      => $orders->customer_id,
				                    'po_no'            => $orders->purchase_order_no,
				                    'po_file'          => $orders->po_file,
									'status'		   => 1,
									'order_status_id'  => $order_status_id->id,
									'created_at'       => $this->created_at
								]);
				$erp_id = "ERPID-0".$erp_orders->id;
				Erp::where('id',$erp_orders->id)->update(['erp_id'=>$erp_id]);

				$this->insertOrderDetails($qnty_table,$order_details,$orders,$erp_orders,$buyer_id);
			}
			$result = response()->json(['error'=> false,'message' =>'success'],200);
		}
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}

	function insertOrderDetails($qnty_table,$order_details,$orders,$erp_orders,$buyer_id)
	{
		if ($order_details)
		{
			foreach ($order_details as $key => $vals) {

				$style_no = ($orders->zone == "CE") ? $orders->ce_style_ref : $orders->uk_style_ref ;
				$custom_ref_name = DB::table('price_taxation')->where('customer_id',$orders->customer_id)->where('buyer_id',$buyer_id)->where('template_id',$orders->template_id)->value('custom_name');

				ErpOrderDetails::create([
					'erp_order_id'     		=> $erp_orders->id,
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
					'status' 				=> 1,
					'template_type'         => $orders->template_type,
					'custom_ref_name' 		=> $custom_ref_name,
					'buyer_po_number'  		=> $orders->po_number,
					'zone'					=> $orders->zone,
					'order_type'            => $orders->order_flag,
					'created_at'            => $this->created_at
				]);
			}
		}
	}
}