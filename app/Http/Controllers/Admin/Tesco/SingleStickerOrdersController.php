<?php

namespace App\Http\Controllers\Admin\Tesco;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SingleStickerOrdersController extends Controller
{
    public function submitSingleStickerOrder(Request $request)
    {
        dd($request);
        // if ($request->ajax()) {

            // $request->validated();

        //     $response = $this->storeorder->storeTescoLabelOrder($request, 'admin');

        //     return $response;
        // }
    }
}
