<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ErpUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		if(!count(\App\Erpuser::all()) ){
			\App\Erpuser::truncate();
			\App\Erpuser::create([
				'id'   => 1,
				'name' => "ERP Admin",
				'email' => 'erpadmin@gmail.com',
				'password' => bcrypt('123456789'),
				'is_admin'	=> true
			]);	
		}
    }
}
