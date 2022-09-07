<?php

use Illuminate\Database\Seeder;

class ColorMatchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $color_match = [
            ['id' => 1, 'name' => 'Cust Sample'],
            ['id' => 2, 'name' => 'Shade Card'],
            ['id' => 3, 'name' => 'Delta E'],
            ['id' => 4, 'name' => 'Mellow'],
        ];

        DB::table('color_match')->insert($color_match);
    }
}
