<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinancialYearResetDateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('financial_year_reset_date')->insert([
            'financial_year_date' => '01-04',
            'created_at' => date("Y-m-d H:i:s")
        ]);
    }
}
