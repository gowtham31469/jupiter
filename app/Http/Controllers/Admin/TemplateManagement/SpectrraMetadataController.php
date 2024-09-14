<?php

namespace App\Http\Controllers\Admin\TemplateManagement;

use App\Classes\SpectrraTemplateMetadata;
use App\Classes\StoreSpectrraTemplateMetadata;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\SpectrraCompanyTemplateData;
use App\Http\Requests\SpectrraTemplateMetadataRequest;
use DB;

class SpectrraMetadataController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
    }

    public function getSpectrraTemplateDetails(Request $request)
    {
        $spectrra_metadata = new SpectrraTemplateMetadata();
        $get_spectrra_metadata = new SpectrraCompanyTemplateData();

        return response()->json([
            'error' => false,
            'message' => 'Success',
            'board_category' => $spectrra_metadata->getBoardCategories(),
            'boards' => $spectrra_metadata->getBoards(),
            'color_match' => $spectrra_metadata->getColorMatches(),
            'spectrra_template_data' => $get_spectrra_metadata->getSpectrraCompanyTemplateData($request),
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
