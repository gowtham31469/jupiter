<?php

namespace App\Classes;
use App\TemplateBoardColorMapping;

/**
 * Class to Remove spectrra template metadata
 * @author [Praveen] <[praveen@wizroots.com]>
 */
class ClearSpectrraTemplateMetadata
{
    public function clearSpectrraData($buyer, $template_id, $company)
    {
        if($company != 2){
            TemplateBoardColorMapping::where('buyer_template_table', $buyer.'_template')
            ->where('template_id', $template_id)
            ->delete();
        }
    }
}
