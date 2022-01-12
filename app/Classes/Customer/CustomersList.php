<?php

namespace App\Classes\Customer;

use DB;

/**
 * Class fetch customers list based on logged in customer
 * @author Praveen
 */
class CustomersList
{
        public function getCustomers($customer_id)
        {
                $customer = DB::table('customer_profile')
                        ->select('*')
                        ->where('customer_profile.id', $customer_id)
                        ->first();

                return DB::table('tesco_suppliers')
                        ->join('customer_metadata', 'customer_metadata.customer_metadata_id', 'tesco_suppliers.id')
                        ->where('customer_metadata.customer_id', $customer->company_id)
                        ->where('is_enabled', 1)
                        ->orderBy('tesco_suppliers.supplier_name')
                        ->pluck('tesco_suppliers.supplier_name', 'tesco_suppliers.id')
                        ->all();
        }
}
