<?php
namespace App\Classes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;
use Carbon\Carbon;
use App\ErpOrders as Erp;
use App\ErpOrderDetails;

class ERPOrderStatusCheck
{
	public function ERPStatus($request)
	{
		try
		{			
			$row_id     =   $request->id;
            $table_name =   $request->table_name;
            $order_details  =   DB::table($table_name)->where('id',$row_id)
                            ->select('*')->first();
            $qnty_id         =  str_replace("_orders","_order_id",str_replace("tesco_","",$table_name));
            $quantity_details = DB::table($table_name.'_quanty')
                                ->where($qnty_id,$order_details->id)->orderBy('id')->get();

            // fetch erp_order_details table status
            foreach($quantity_details as $quantity)
            {
               $quantity_id = $quantity->id;
               $erp_order_details[] = DB::table('erp_order_details')->where('order_qnty_table_name','ilike', $table_name.'_quanty')->join('erp_status', 'erp_status.id', 'erp_order_details.status')->where('order_qnty_table_id','=', $quantity_id)->select('erp_order_details.id','erp_status.status_name')->first();
            }

            $result = response()->json(['error'=> false,'message' =>'success','erp_details' => $erp_order_details],200);
		}
        catch (\Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
        return $result;
	}
	
}