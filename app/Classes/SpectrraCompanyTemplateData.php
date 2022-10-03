<?php

namespace App\Classes;

use DB;
use App\TemplateBoardColorMapping;

/**
 * Class to fetch spectrra template metadata
 * @author Praveen
 */
class SpectrraCompanyTemplateData
{
    public function getSpectrraCompanyTemplateData($request)
    {
        return TemplateBoardColorMapping::with(['colorMatches', 'frontColors'])
        ->where('buyer_template_table', $request['buyer'].'_item_master')
        ->where('template_id', $request['template_id'])
        ->first();
    }
}