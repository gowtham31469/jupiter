<?php

namespace App\Http\Controllers\Customer\Tesco;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerSingleStickerOrdersController extends Controller
{
    public function submitSingleStickerOrder(Request $request)
    {
        dd($request);
        // if ($request->ajax()) {

            // $request->validated();

            // $response = $this->storeorder->storeTescoLabelOrder($request, 'admin');

        //     return $response;
        // }
    }
}
