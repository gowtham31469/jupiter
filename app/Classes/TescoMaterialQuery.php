<?php
namespace App\Classes;
use DB;
/**
 * Class to fetch tesco material and sub matrials from hierarchy
 * @author Praveen
 */
class TescoMaterialQuery
{
	public function tescoMaterialDetails($template_id)
	{
		$template_level = DB::table('tesco_item_master as t1')
                            ->select('t3.item_name as material_sub', 't4.item_name as material', 't1.item_name as ref', 'reports_template_level_name.name as name_in_reports')
                            ->leftJoin('tesco_item_master as t2', 't2.id','=','t1.parent_id')
                            ->leftJoin('tesco_item_master as t3', 't3.id','=','t2.parent_id')
                            ->leftJoin('tesco_item_master as t4', 't4.id','=','t3.parent_id')
                            ->join('tesco_template', 't1.id', 'tesco_template.item_id')
                            ->join('reports_template_level_name', 'tesco_template.name_in_reports', 'reports_template_level_name.id')
                            ->where('t1.id', $template_id)
                            ->first();
    
        return $template_level;
	}

}