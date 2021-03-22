<?php
namespace App\Classes;
use App\ErpOrders as Erp;

class ErpIDGenerator
{
    /**
     * Generate Erp ID 
     * @return $erp_id
    */
	public function generateErpID($from, $today_date, $financial_year, $month)
	{
		try
		{	
            // Logic for resetting ERP ID format for new financial year	
				// check if already order placed in new financial year or not.
				$already_order_placed = Erp::select('erp_id')->find(Erp::whereBetween('created_at', [$from, $today_date])->orWhereDate('created_at', '=',$today_date)->max('id'));
				
				// if old orders exists, then generate erpid based on old data
				// else start new erpid from initial value(00001)
				if($already_order_placed){

					$max_erp_id = $already_order_placed->erp_id;
					// To maintain old erp id with prefix "ERPID-0"
					// we are checking erp id prefix of existing orders
					$prefix_str = substr($max_erp_id, 0, 7);

					// maintain erp id prefix as "ERPID-0" for old orders
					// "yymmxxxxx" format for new financial year orders
					if($prefix_str === "ERPID-0"){
						$erp_id_prefix = "ERPID-0";
						$split_erp_id = explode("-",$max_erp_id);
						(int)$parsed_max_id  = ltrim(end($split_erp_id), '0');
						(int)$parsed_max_id += 1;
					} else {
						$erp_id_prefix = $financial_year.$month;
						(int)$parsed_max_id  = substr($max_erp_id, 4);
						(int)$parsed_max_id  = str_pad($parsed_max_id+1, 5, 0, STR_PAD_LEFT);
					}

				} else {
					(int)$parsed_max_id = str_pad(1, 5, 0, STR_PAD_LEFT);
					$erp_id_prefix = $financial_year.$month;
				}
				
				// erp id format : "yymmxxxxx". and for old orders "ERPID-01" 
                $erp_id = $erp_id_prefix.$parsed_max_id;
                
                return $erp_id;
		}
        catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
    
}