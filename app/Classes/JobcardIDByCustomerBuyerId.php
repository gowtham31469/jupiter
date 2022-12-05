<?php

namespace App\Classes;

use DB;

class JobcardIDByCustomerBuyerId
{
    public function getJobcardIds($request, $is_nat)
    {
        $jc_main_table = session()->get('company_name') === 'spectrraprints' ? 'spectrra_jobcard_main_tables' : 'jobcard_main_table';

        return DB::table($jc_main_table)
            ->where([
                $jc_main_table . '.company_id' => session()->get('company'),
                $jc_main_table . '.buyer_id' => $request['selected_buyer_id'],
                $jc_main_table . '.customer_id' => $request['selected_customer_id'],
                $jc_main_table . '.is_non_artwork_template' => $is_nat
            ])
            ->pluck($jc_main_table . '.work_order_id', $jc_main_table . '.id')
            ->all();
    }

    public function getJobcardList($request, $is_nat)
    {
        $jc_main_table = session()->get('company_name') === 'spectrraprints' ? 'spectrra_jobcard_main_tables' : 'jobcard_main_table';

        return DB::table($jc_main_table)
            ->join('customers', $jc_main_table . '.customer_id', 'customers.id')
            ->join('buyers', $jc_main_table . '.buyer_id', 'buyers.id')
            ->where([
                $jc_main_table . '.company_id' => session()->get('company'),
                $jc_main_table . '.buyer_id' => $request['selected_buyer_id'],
                $jc_main_table . '.is_non_artwork_template' => $is_nat,
                $jc_main_table . '.customer_id' => $request['selected_customer_id'],
                $jc_main_table . '.id' => $request['selected_jobcard_id']
            ])
            ->select($jc_main_table . '.id as jobcard_id', $jc_main_table . '.work_order_id', 'customers.company_name', 'buyers.buyer_name')
            ->first();
    }
}
