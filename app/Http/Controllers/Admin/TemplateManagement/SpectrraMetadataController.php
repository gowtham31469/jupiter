<?php

namespace App\Http\Controllers\Admin\TemplateManagement;

use App\Classes\SpectrraTemplateMetadata;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TemplateBoardColorMapping;
use DB;

class SpectrraMetadataController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
    }

    public function getSpectrraCompanyTemplateData($request)
    {
        return TemplateBoardColorMapping::with('colorMatches', 'frontColors')
        ->where('buyer_template_table', $request['buyer'].'_template')
        ->where('template_id', $request['template_id'])
        ->get();
    }

    public function getSpectrraTemplateDetails(Request $request)
    {
        $spectrra_metadata = new SpectrraTemplateMetadata();

        return response()->json([
            'error' => false,
            'message' => 'Success',
            'board_category' => $spectrra_metadata->getBoardCategories(),
            'boards' => $spectrra_metadata->getBoards(),
            'color_match' => $spectrra_metadata->getColorMatches(),
            'spectrra_template_data' => $this->getSpectrraCompanyTemplateData($request),
        ], 200);

        // $radio = $request->get('radion_button', 0);
    }

    public function storeSpectrraTemplateDetails(Request $request)
    {
        dd($request->get('print_spec_bar', 0));
    }
}
