<?php
namespace App\Http\Controllers\ErpUser\Invoice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Response;
use View;
use DB;

class InvoiceController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('erpuser');
   
    }

    /**
     * Show the application dashboard.
     * @author [Praveen] <[praveen@wizroots.com]>
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tesco_suppliers = DB::table('tesco_suppliers')
                              ->select('tesco_suppliers.supplier_name','tesco_suppliers.id')
                              ->join('customer_metadata','customer_metadata.customer_metadata_id','=','tesco_suppliers.id')
                              ->join('customer_profile','customer_profile.company_id','=','customer_metadata.customer_id')
                              ->where('is_enabled','=',1)
                              ->groupBy('tesco_suppliers.id')
                              ->get();
        return view('erp.Invoice.create-invoice',['tesco_suppliers' => $tesco_suppliers]);
	}

}