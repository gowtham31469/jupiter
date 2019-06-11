<?php

use Illuminate\Database\Seeder;

class ErpUsersBuyerMappingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!count(\App\ErpuserBuyerMapping::all()) ){
			
			foreach(\App\Buyer::all() as $key => $val){
				\App\ErpuserBuyerMapping::create([
					'buyer' => $val->id, 'erp_user' => 1
				]);
			}
		}
    }
}
