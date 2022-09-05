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
        $board_category = DB::table('board_category')
        ->pluck('name', 'id')
        ->all();

        $boards = DB::table('boards')
        ->pluck('name', 'id')
        ->all();

        $color_match = DB::table('color_match')
        ->pluck('name', 'id')
        ->all();

        return response()->json([
            'error' => false,
            'message' => 'Success',
            'board_category' => $board_category,
            'boards' => $boards,
            'color_match' => $color_match,
        ], 200);

        // $radio = $request->get('radion_button', 0);
        // return DB::table($request['buyer'].'_template')->where('id', $request['template_id'])->get();
    }
}
