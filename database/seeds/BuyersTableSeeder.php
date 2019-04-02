<?php

use Illuminate\Database\Seeder;

class BuyersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Buyer::create([
				'id' =>3,
				'buyer_name' => 'C&A',
				'logo' => 'C&A',
				'buyer_address' => 'C&A',
				'buyer_email' => 'CNA@gmail.com',
				'buyer_phone' => '1234567890',
				'is_active' => '1',
			
			]);
		\App\Buyer::create([
				'id' =>4,
				'buyer_name' => 'WW',
				'logo' => 'WW',
				'buyer_address' => 'WW',
				'buyer_email' => 'WW@gmail.com',
				'buyer_phone' => '1234567890',
				'is_active' => '1',
			]);
		\App\Buyer::create([
				'id' =>5,
				'buyer_name' => 'GERBER',
				'logo' => 'GERBER',
				'buyer_address' => 'GERBER',
				'buyer_email' => 'GERBER@gmail.com',
				'buyer_phone' => '1234567890',
				'is_active' => '1',
			]);
		\App\Buyer::create([
				'id' =>6,
				'buyer_name' => 'PRIMARK',
				'logo' => 'PRIMARK',
				'buyer_address' => 'PRIMARK',
				'buyer_email' => 'PRIMARK@gmail.com',
				'buyer_phone' => '1234567890',
				'is_active' => '1',
			]);
		\App\Buyer::create([
				'id' =>7,
				'buyer_name' => 'LIDL',
				'logo' => 'LIDL',
				'buyer_address' => 'LIDL',
				'buyer_email' => 'LIDL@gmail.com',
				'buyer_phone' => '1234567890',
				'is_active' => '1',
			]);
		\App\Buyer::create([
				'id' =>8,
				'buyer_name' => 'ERVE',
				'logo' => 'ERVE',
				'buyer_address' => 'ERVE',
				'buyer_email' => 'ERVE@gmail.com',
				'buyer_phone' => '1234567890',
				'is_active' => '1',
			]);
		\App\Buyer::create([
				'id' =>9,
				'buyer_name' => 'OSTIN',
				'logo' => 'OSTIN',
				'buyer_address' => 'OSTIN',
				'buyer_email' => 'OSTIN@gmail.com',
				'buyer_phone' => '1234567890',
				'is_active' => '1',
			]);
    }
}
