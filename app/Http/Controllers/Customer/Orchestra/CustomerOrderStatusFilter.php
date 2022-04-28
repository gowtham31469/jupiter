<?php

namespace App\Http\Controllers\Customer\Orchestra;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Classes\OrchestraOrderFilter;

class CustomerOrderStatusFilter extends Controller
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
        $filter_obj = new OrchestraOrderFilter($request);

        $data = $filter_obj->getOrchOrderDataByFilter();

        echo  json_encode($data);
    }
}
