<?php
namespace App\Classes;
use DB;

class ERPOrderStatusCheck
{
	public function ERPStatus($data)
	{
		try
		{			
			$row_id     = $data['id'];
            $table_name = $data['table_name'];
            $erp_order_details = array();

            $order_details  =   DB::table($table_name)
            ->where('id',$row_id)
            ->select('*')
            ->first();

            $qnty_id         =  str_replace("_orders","_order_id",str_replace("tesco_","",$table_name));

            $quantity_details = DB::table($table_name.'_quanty')
                                ->where($qnty_id,$order_details->id)
                                ->orderBy('id')
                                ->get();

            // fetch erp_order_details table status
            foreach($quantity_details as $quantity)
            {

               $quantity_id = $quantity->id;

               $erp_order_details[] = DB::table('erp_order_details')
               ->where('order_qnty_table_name','ilike', $table_name.'_quanty')
               ->join('erp_status', 'erp_status.id', 'erp_order_details.status')
               ->where('order_qnty_table_id','=', $quantity_id)
               ->select('erp_order_details.id','erp_status.status_name')
               ->first();
               
            }

            return $erp_order_details;
		}
        catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
	}
    
    
    /**
     * Check orchestra orders erp status
     *
     * @param [type] $data
     * @return void
     */
	public function OrchestraERPStatus($data)
	{
		try
		{			
			$row_id     = $data['id'];
            $table_name = $data['table_name'];
            $erp_order_details = array();
            $order_details  =   DB::table($table_name)->where('id',$row_id)
                            ->select('*')->first();

            // get the first letter of each word for a given string
            $result_str  =  str_replace("_"," ",str_replace("_orders","",$table_name));
            $qnty_id = '';
            foreach (explode(' ', $result_str) as $word)
            {
                $qnty_id .= strtolower($word[0]);
            }
            
            $quantity_details = DB::table($table_name.'_qty')
                                ->where($qnty_id.'_id',$order_details->id)->orderBy('id')->get();
                                
            // fetch erp_order_details table status
            foreach($quantity_details as $quantity)
            {
               $quantity_id = $quantity->id;
               $erp_order_details[] = DB::table('erp_order_details')->where('order_qnty_table_name','ilike', $table_name.'_qty')->join('erp_status', 'erp_status.id', 'erp_order_details.status')->where('order_qnty_table_id','=', $quantity_id)->select('erp_order_details.id','erp_status.status_name')->first();
            }

            return $erp_order_details;
		}
        catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
	}
	
}