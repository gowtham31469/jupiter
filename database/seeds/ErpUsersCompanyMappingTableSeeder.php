<?php

use Illuminate\Database\Seeder;
use App\ErpuserCompanyMapping;

class ErpUsersCompanyMappingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		if(!count (\App\ErpuserCompanyMapping::all())){
			ErpuserCompanyMapping::create([
				'company' => 1, 'erp_user' => 1
			]);
			ErpuserCompanyMapping::create([
				'company' => 2, 'erp_user' => 1
			]);
		}
    }
}
