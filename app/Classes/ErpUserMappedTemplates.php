<?php
namespace App\Classes;

use DB,Auth;

class ErpUserMappedTemplates
{
    public function erpUserTemplateMap()
    {
        return DB::table('erpusers_template_mapping')
        ->join('buyers', 'erpusers_template_mapping.buyer', 'buyers.id')
        // ->where('erpusers_template_mapping.company',session()->get('company'))    // commented company mapping check : bcoz of parent-child company id mismatch. 
        ->where(function ($query) {
            if (Auth::guard('erpuser')->id() != 1) {
                $query->where('erpusers_template_mapping.erp_user', Auth::guard('erpuser')->id());
            }
        })
        ->select('erpusers_template_mapping.buyer as buyer_id', 'erpusers_template_mapping.templates', 'buyers.template_table', 'buyers.item_master_table')
        ->distinct('erpusers_template_mapping.templates')
        ->get();
    }
    
    public function getPermittedTemplates()
    {
        $erp_user_data  = $this->erpUserTemplateMap();

        $new_orders = array();
        $i = 1;
        foreach ($erp_user_data as $value) {
            $arr_template =  DB::table($value->template_table)
                ->where('hierarchy_id', $value->templates)
                ->distinct('hierarchy_id')
                ->pluck('item_id');
            if ($arr_template->isNotEmpty()) {
                $new_orders[$value->buyer_id . "-" . $i] = $arr_template;
            }
            $i++;
        }

        return $new_orders;
    }
    
}