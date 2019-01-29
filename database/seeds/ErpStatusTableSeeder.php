<?php

use Illuminate\Database\Seeder;

class ErpStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

		if(!count(\App\ErpStatus::all()) ){
			
			\App\ErpStatus::create([
				'id' =>1,
				'status_name' => 'Moved To ERP',
            			'status_desc' => 'Moved To ERP'
			]);	
			\App\ErpStatus::create([
				 'id' =>2,
				 'status_name' => 'Hold For JC Queue',
           			 'status_desc' => 'Hold For JC Queue'
			]);
			\App\ErpStatus::create([
				'id' =>3,
				'status_name' => 'Moved To JC Queue',
          			'status_desc' => 'Moved To JC Queue'
			]);
			\App\ErpStatus::create([
				'id' =>4,
				'status_name' => 'Cancelled',
          			'status_desc' => 'Order or Order Item cancelled'
			]);
			\App\ErpStatus::create([
				'id' =>5,
				'status_name' => 'Invoice Generated',
          			'status_desc' => 'Invoice Generated and successfully send to the customer'
			]);
			\App\ErpStatus::create([
				'id' =>6,
				'status_name' => 'Partial Invoice',
          			'status_desc' => 'Invoice generated Partially'
			]);
			\App\ErpStatus::create([
				'id' =>7,
				'status_name' => 'Hold',
          			'status_desc' => 'To Hold Whole Order'
			]);
			\App\ErpStatus::create([
				'id' =>8,
				'status_name' => 'Jobcard Created',
          			'status_desc' => 'Jobcard Has Been Created Successfully'
			]);
		}
    }
}
