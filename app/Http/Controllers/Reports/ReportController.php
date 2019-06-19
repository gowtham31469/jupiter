<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Buyer;
use DB;

class ReportController extends Controller
{
    public function index()
    {
    	$buyers = (DB::table('buyers')->pluck('buyer_name','id'));
    	$template = (array("1"=>"All","2"=>"Avlb","3"=>"Not Avlb"));
    	$country = (array("1"=>"All","2"=>"UK","3"=>"CE"));
    	$season = (array("1"=>"All","2"=>"AW18","3"=>"SS18","4"=>"AW19","5"=>"SS19"));

    	$result = (array("buyer"=> $buyers,"template" =>$template,"season"=>$season,"country"=>$country));

    	return view('report',compact('result','buyers','template','country','season'));
    }

    public function filter_1(Request $request)
    {
    	dd($request->all());
    }

}
