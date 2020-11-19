<?php
namespace App\Classes;
use DB;
use App\ErpOrders as Erp;
use Carbon\Carbon;
use App\FinancialYearResetDate;

class JobcardIDGenerator
{
    private $current_date;
	private $year;
    private $financial_year_date;
    private $financial_year_date_format;
    private $financial_year;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		// get the current time
		$this->current_date = Carbon::now('Asia/Kolkata')->format('Y-m-d');

		// get year as "2020"
		$this->year = Carbon::parse($this->current_date)->format('Y');

		$this->financial_year_date = FinancialYearResetDate::value('financial_year_date');

		// format financial year date as "yyyy-dd-mm" 
		$this->financial_year_date_format = $this->year.'-'.$this->financial_year_date;

		// financial year identification
		if($this->current_date < $this->financial_year_date_format){
			$this->financial_year = Carbon::parse($this->financial_year_date_format)->subYear()->format('y');
		} else {
			$this->financial_year = Carbon::parse($this->financial_year_date_format)->format('y');
		}

    }
    
     /**
     * Generate Jobcard ID 
     * @return $workorder_id
    */
	public function generateJobcardID($article_array, $erp_orders_ids)
	{
		try
		{	
            $item_array = ['Printed Labels' => 'PL', 'Woven Labels' => 'WL', 'Heat Tranfers' => 'HT', 'Carton & Polybag Stickers' => 'CS', 'Printed Boards' => 'PB', 'Swing Tags' => 'ST', 'Size Stickers' => 'ST', 'Garment Friendly' => 'GF', 'Price Stickers' => 'PS', 'Header Cards' => 'HC', 'Insert Cards' => 'IC', 'Cascades' => 'CD'];
            
            
			// Jobcard ID format snippets
            $item_name = sizeof($article_array) === 1?$item_array[$article_array[0]]:'XX';  // Item name(2) prefix for jobcard
            $order_id = 'XXXXX';  // xxxxx for multiple erpid selection.
            
            //  order id prefix logic
            if(sizeof(array_unique($erp_orders_ids)) === 1){
                $erp_id_str = Erp::where('erp_orders.id', '=', $erp_orders_ids[0])->value('erp_id');
                $split_erp_id = explode("-",$erp_id_str);

                // last 5 digits of erpid logic for old and new erpid's.
                // order id of old erpid 'ERPID-01234' is '01234'.
                // order id of new erpid 'yymmxxxxx' is 'xxxxx'.
                if($split_erp_id[0] === 'ERPID'){
                    $order_id = $split_erp_id[1];
                } else {
                    $order_id = substr(Erp::where('erp_orders.id', '=', $erp_orders_ids[0])->value('erp_id'),-5);  // last 5 characters/digits of erpid for jobcard id.
                }
            }
            
            // the default jobcard id prefix format
            (int)$parsed_max_id = str_pad(1, 4, 0, STR_PAD_LEFT); // to generate 4 digit continous numbers
            $jobcard_id_prefix = $item_name.$this->financial_year.$order_id;

            // Logic for resetting Jobcard ID format for new financial year			 
            $max_workorder_id = DB::table('jobcard_main_table')
                                ->select('work_order_id')
                                ->find(DB::table('jobcard_main_table')
                                ->where(function($q) use ($item_name) {
                                    $q->where('work_order_id', 'like', $item_name . '%')
                                      ->orWhere('work_order_id', 'like', 'JGA-JC-%');
                                })
                                ->whereBetween('created_date', [$this->financial_year_date_format, $this->current_date])
                                ->max('id'));

            if($max_workorder_id){
                $latest_workorder_id = $max_workorder_id->work_order_id;

                $prefix_str = substr($latest_workorder_id, 0, 7);
                
                // maintain jobcard id prefix as "JGA-JC-" for old orders
                if($prefix_str === "JGA-JC-"){
                    $jobcard_id_prefix = "JGA-JC-";
                    $split_workorder_id = explode("-",$latest_workorder_id);
                    (int)$parsed_max_id  = $split_workorder_id[2];
                    (int)$parsed_max_id += 1;
                } else {
                    $jobcard_id_prefix = $item_name.$this->financial_year.$order_id;
                    (int)$parsed_max_id  = substr($latest_workorder_id, 9);
                    (int)$parsed_max_id  = str_pad($parsed_max_id+1, 4, 0, STR_PAD_LEFT);
                }
            }
            
            // jobcard id format "Itemname(2)FinancialYearERPID(last 5)0001" where  and for old order "JGA-JC-1"
            $workorder_id = $jobcard_id_prefix.$parsed_max_id;

            return $workorder_id;
		}
        catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
    
}