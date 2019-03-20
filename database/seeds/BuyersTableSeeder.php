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
				'buyer_name' => 'C&A'
			]);
		\App\Buyer::create([
				'id' =>4,
				'buyer_name' => 'WW'
			]);
		\App\Buyer::create([
				'id' =>5,
				'buyer_name' => 'GERBER'
			]);
		\App\Buyer::create([
				'id' =>6,
				'buyer_name' => 'PRIMARK'
			]);
		\App\Buyer::create([
				'id' =>7,
				'buyer_name' => 'LIDL'
			]);
		\App\Buyer::create([
				'id' =>8,
				'buyer_name' => 'ERVE'
			]);
		\App\Buyer::create([
				'id' =>9,
				'buyer_name' => 'OSTIN'
			]);
    }
}
