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
        /*	Roles Array	*/
        $roles = array(
            '1' => 'PO MANAGER', 
            '2' => 'JOB CARD MANAGER', 
            '3' => 'INVOICE MANAGER',
            '4' => 'PRINT MANAGER', 
            '5' => 'NON-ARTWORK JOB CARD MANAGER',
            '6' => 'NON TEMPLATE ORDERS'
        );
        foreach($roles as $key => $role){
            \App\ErpRoles::updateOrCreate([
                'id'   => $key,
                'name' => $role
            ]);
        }
		
    }
}
