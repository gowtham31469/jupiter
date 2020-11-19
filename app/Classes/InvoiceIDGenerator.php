<?php
namespace App\Classes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Response;
use View;
use DB;
use Auth;
use Mail;
use App\ErpOrders as Erp;
use App\ErpOrderDetails;
use App\ErpOrdersInvoice; 
use App\Invoices;
use App\InvoiceDetails;
use App\InvExtraCharges;
use PDF;
use App\Classes\Invoice;
use App\InvoiceAddress;
use Elibyy\TCPDF\Facades\TCPDF;
use App\Classes\invoiceTCPDF;
use App\EventHistory;
use App\History;
use Carbon\Carbon;
use App\FinancialYearResetDate;

class InvoiceIDGenerator
{
    private $current_date;
    private $year;
	private $today_date;
    private $from;
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
        $this->current_date = Carbon::now('Asia/Kolkata')->toDateTimeString();
        
        // get year as "2020"
		$this->year = Carbon::parse($this->current_date)->format('Y');

		$this->financial_year_date = FinancialYearResetDate::value('financial_year_date');

		// format financial year date as "yyyy-dd-mm" 
		$this->financial_year_date_format = $this->year.'-'.$this->financial_year_date;

		// get the current date in "dd-mm-yyyy" format
		$this->today_date = Carbon::now('Asia/Kolkata')->format('Y-m-d');

		$this->from  = Carbon::parse($this->financial_year_date_format)
						->startOfDay()        // 2018-09-29 00:00:00.000000
                        ->toDateTimeString(); // 2018-09-29 00:00:00
        
        // financial year identification
		if($this->today_date < $this->financial_year_date_format){
			$this->financial_year = Carbon::parse($this->financial_year_date_format)->subYear()->format('y');
		} else {
			$this->financial_year = Carbon::parse($this->financial_year_date_format)->format('y');
		}

	}
    
     /**
     * Generate Invoice ID 
     * @return $invoice_id
    */
	public function generateInvoiceID()
	{
		try
		{	
            // Logic for resetting invoice ID format for new financial year			 
            $max_invoice_id = Invoices::select('invoice_id')->find(Invoices::whereBetween('created_at', [$this->from, $this->current_date])->orWhereDate('created_at', '=',$this->today_date)->max('id'));
            $invoice_id_prefix = $this->financial_year;

            if($max_invoice_id){
                $explode_invoice_number = explode("-",$max_invoice_id->invoice_id);
                $trim_invoice  = ltrim(end($explode_invoice_number), '0');
                (int)$parsed_max_id = substr($trim_invoice, 2);
                (int)$parsed_max_id = str_pad($parsed_max_id+1, 5, 0, STR_PAD_LEFT);

            } else {
                (int)$parsed_max_id = str_pad(1, 5, 0, STR_PAD_LEFT);
            }
            
            // invoice id format "yyXXXXX"
            $invoice_id = $invoice_id_prefix.$parsed_max_id;

            return $invoice_id;
		}
        catch (\Exception $e)
        {
            return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
    }
    
}