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
		}
    }
}
