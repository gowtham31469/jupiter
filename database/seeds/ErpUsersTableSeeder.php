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
        DB::table('erpusers')->insert([
			'id'   => 1,
            'name' => "ERP Admin",
            'email' => 'erpadmin@gmail.com',
            'password' => bcrypt('123456789'),
            'created_at'=> Carbon::now('Asia/Kolkata')->toDateTimeString(),
            'updated_at'=> Carbon::now('Asia/Kolkata')->toDateTimeString()
        ]);	
    }
}
