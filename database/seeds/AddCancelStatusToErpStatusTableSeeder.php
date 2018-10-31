<?php

use Illuminate\Database\Seeder;

class AddCancelStatusToErpStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\ErpStatus::create([
				'id' =>4,
				'status_name' => 'Cancelled',
          		'status_desc' => 'Order or Order Item cancelled'
			]);
    }
}
