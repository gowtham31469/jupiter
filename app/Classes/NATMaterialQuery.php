<?php

namespace App\Classes;

use DB;

/**
 * Class to fetch non-artwork material and sub matrials from hierarchy
 * @author Praveen
 */
class NATMaterialQuery
{
        public function nonArtworkMaterialDetails($template_id, $buyer)
        {
                return DB::table($buyer.'_item_master as t1')
                        ->leftJoin($buyer.'_item_master as t2', 't2.id', 't1.parent_id')
                        ->leftJoin($buyer.'_item_master as t3', 't3.id', 't2.parent_id')
                        ->leftJoin($buyer.'_item_master as t4', 't4.id', 't3.parent_id')
                        ->join($buyer.'_template', 't1.id', $buyer.'_template.item_id')
                        ->join('reports_template_level_name', $buyer.'_template.name_in_reports', 'reports_template_level_name.id')
                        ->where('t1.id', $template_id)
                        ->select('t1.item_name as ref', 't2.item_name as material_sub', 't3.item_name as material', 'reports_template_level_name.name as name_in_reports')
                        ->first();
        }
}