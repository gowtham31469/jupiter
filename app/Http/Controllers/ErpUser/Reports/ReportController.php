<?php

namespace App\Http\Controllers\ErpUser\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Buyer;
use App\Customer;
use DB;

class ReportController extends Controller
{
    public function index()
    {
    	$buyers = (DB::table('buyers')->pluck('buyer_name','id'));
    	$template = (array("1"=>"All","2"=>"Avlb","3"=>"Not Avlb"));
    	$country = (array("1"=>"All","2"=>"UK","3"=>"CE"));
    	$season = (array("1"=>"All","2"=>"AW18","3"=>"SS18","4"=>"AW19","5"=>"SS19"));
        $customers = Customer::all()->pluck('company_name','id');
        // dd($customers);
    	$result = (array("buyer"=> $buyers,"template" =>$template,"season"=>$season,"country"=>$country));

    	return view('erp.Report.reports',compact('result','buyers','template','country','season'));
    }

    public function level1Filter(Request $request)
    {
    	dd($request->all());
    }

}
