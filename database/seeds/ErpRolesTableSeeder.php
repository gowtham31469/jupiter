<?php

use Illuminate\Database\Seeder;

class ErpRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!count(\App\ErpRoles::all()) ){
			
			/*	Roles Array	*/
			$roles = array('PO MANAGER', 'JOB CARD MANAGER', 'INVOICE MANAGER');
			foreach($roles as $role){
				\App\ErpRoles::create([
					'name' => $role
				]);
			}
		}
    }
}
