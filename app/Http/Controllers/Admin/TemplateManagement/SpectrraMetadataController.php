<?php

namespace App\Http\Controllers\Admin\TemplateManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class SpectrraMetadataController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
    }

    public function getSpectrraMetadata(Request $request)
    {

        // $radio = $request->get('radion_button', 0);
        return DB::table($request['buyer'].'_template')->where('id', $request['template_id'])->get();
    }
}
