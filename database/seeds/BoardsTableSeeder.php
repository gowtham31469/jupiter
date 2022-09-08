<?php

use Illuminate\Database\Seeder;

class BoardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $boards = [
            ['id' => 1, 'name' => 'Smooth Texture'],
            ['id' => 2, 'name' => 'Cyber Exel'],
        ];

        DB::table('boards')->insert($boards);
    }
}
