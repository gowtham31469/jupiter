<?php

namespace App\Http\Controllers\Admin\Orchestra;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\OrchestraOrderFilter;


class OrderStatusFilters extends Controller
{
    protected function orchestraOrdersFilter(Request $request)
    {
        
        $filter_obj = new OrchestraOrderFilter($request);

        $data = $filter_obj->getOrdersSeasonCodes();

        $style_no = $filter_obj->getOrdersStyleNumbers();

        return response()->json(['error' => false, 'message' => 'success', 'data' => $data, 'style_no' => $style_no], 200);
    }

    protected function getOrchestraOrders(Request $request)
    {
        dd($request->orch_filter_orderstatus);
    }
}
