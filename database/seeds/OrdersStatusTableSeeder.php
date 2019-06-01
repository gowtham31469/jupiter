<?php

use Illuminate\Database\Seeder;

class OrdersStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

		if(!count(\App\OrdersStatus::all()) ){
			\App\OrdersStatus::query()->delete();
			\App\OrdersStatus::create([
				'id' =>1,
				'status_name' => 'Saved',
            	'status_desc' => 'Order is in drafted state.'
			]);	
			\App\OrdersStatus::create([
				 'id' =>2,
				 'status_name' => 'Order Placed',
           		 'status_desc' => 'Order is in submitted state.'
			]);
			\App\OrdersStatus::create([
				'id' =>3,
				'status_name' => 'Order Cancelled',
          		'status_desc' => 'Order is cancelled.'
            ]);
            \App\OrdersStatus::create([
				'id' =>4,
				'status_name' => 'Order Approved',
          		'status_desc' => 'Order is Approved.'
			]);
            \App\OrdersStatus::create([
				'id' =>5,
				'status_name' => 'Buyer Approval Pending',
          		'status_desc' => 'Order Approval Pending.'
			]);
		}
    }
}
