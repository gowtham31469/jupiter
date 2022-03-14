<?php

namespace App\Classes;

use DB;

/**
 * Class to fetch tesco material and sub matrials from hierarchy
 * @author Praveen
 */
class TescoMaterialQuery
{
        public function tescoMaterialDetails($template_id, $order_table)
        {
                $query = DB::table('tesco_item_master as t1')
                        ->leftJoin('tesco_item_master as t2', 't2.id', 't1.parent_id')
                        ->leftJoin('tesco_item_master as t3', 't3.id', 't2.parent_id')
                        ->leftJoin('tesco_item_master as t4', 't4.id', 't3.parent_id')
                        ->join('tesco_template', 't1.id', 'tesco_template.item_id')
                        ->join('reports_template_level_name', 'tesco_template.name_in_reports', 'reports_template_level_name.id')
                        ->where('t1.id', $template_id);

                switch ($order_table) {
                        case 'tesco_washcare_orders':
                                $query->select('t1.item_name as ref', 't2.item_name as material_sub', 't3.item_name as material', 'reports_template_level_name.name as name_in_reports');
                                break;
                        case 'tesco_swingticket_orders':
                                $query->select('t1.item_name as ref', 't2.item_name as material_sub', 't3.item_name as material', 'reports_template_level_name.name as name_in_reports');
                                break;
                        default:
                                $query->select('t1.item_name as ref', 't3.item_name as material_sub', 't4.item_name as material', 'reports_template_level_name.name as name_in_reports');
                                break;
                }

                $template_level = $query->first();;

                return $template_level;
        }
}
