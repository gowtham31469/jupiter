<?php

use Illuminate\Database\Seeder;

class AddHoldOrderStatusToErpStatusTabbleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		if(! \App\ErpStatus::where('id', 7)->first() ){
			 \App\ErpStatus::create([
				'id' => 7,
				'status_name' => 'Hold',
          		'status_desc' => 'To Hold Whole Order'
			]);
		}
    }
}
