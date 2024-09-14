<?php

use Illuminate\Database\Seeder;

class BoardCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $boards_category = [
            ['id' => 1, 'name' => 'Tesco'],
            ['id' => 2, 'name' => 'ITC'],
            ['id' => 3, 'name' => 'TNPL'],
            ['id' => 4, 'name' => 'JK'],
        ];

        DB::table('board_category')->insert($boards_category);
    }
}
