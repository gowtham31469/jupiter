<?php

namespace App\Http\Controllers\Admin\TemplateManagement;

use App\Classes\SpectrraTemplateMetadata;
use App\Classes\StoreSpectrraTemplateMetadata;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TemplateBoardColorMapping;
use App\Http\Requests\SpectrraTemplateMetadataRequest;
use DB;

class SpectrraMetadataController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
    }

    public function getSpectrraCompanyTemplateData($request)
    {
        return TemplateBoardColorMapping::with(['colorMatches', 'frontColors'])
        ->where('buyer_template_table', $request['buyer'].'_template')
        ->where('template_id', $request['template_id'])
        ->first();
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
    }

    public function storeSpectrraTemplateDetails(SpectrraTemplateMetadataRequest $request)
    {
        if ($request->ajax()) {

            $request->validated();

            $store_spectrra_metadata = new StoreSpectrraTemplateMetadata();

            $store_spectrra_metadata->storeTemplateData($request);

            return response()->json([
                'error' => false,
                'message' => 'success'
            ], 200);
        }
    }
}
