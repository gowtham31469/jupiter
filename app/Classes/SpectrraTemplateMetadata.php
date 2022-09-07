<?php

namespace App\Classes;
use DB;

/**
 * Class to get spectrra prints templates metadata dropdown values
 * @author [Praveen] <[praveen@wizroots.com]>
 */
class SpectrraTemplateMetadata
{
    public function getBoardCategories()
    {
        return DB::table('board_category')
            ->pluck('name', 'id')
            ->all();
    }

    public function getBoards()
    {
        return DB::table('boards')
            ->pluck('name', 'id')
            ->all();
    }

    public function getColorMatches()
    {
        return DB::table('color_match')
            ->pluck('name', 'id')
            ->all();
    }
}
