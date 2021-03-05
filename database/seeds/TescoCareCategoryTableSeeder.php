<?php

use Illuminate\Database\Seeder;

class TescoCareCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['id' => 1, 'category_name' => 'Washing'],
            ['id' => 2, 'category_name' => 'Whitening'],
            ['id' => 3, 'category_name' => 'Dryer'],
            ['id' => 4, 'category_name' => 'Ironing'],
            ['id' => 5, 'category_name' => 'Prof Cleaning']
        ];

        DB::table('tesco_care_category')->insert($categories);
    }
}
