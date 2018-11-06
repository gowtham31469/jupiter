<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
		$this->call([
			ErpUsersTableSeeder::class,
			ErpUsersCompanyMappingTableSeeder::class,
			ErpUsersBuyerMappingTableSeeder::class,
			ErpRolesTableSeeder::class,
			ErpStatusTableSeeder::class,
			AddCancelStatusToErpStatusTableSeeder::class
		]);
    }
}
