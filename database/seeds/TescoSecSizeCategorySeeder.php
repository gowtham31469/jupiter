<?php

use Illuminate\Database\Seeder;

class TescoSecSizeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sec_categories = [
            ['id' => 1, 'category_name' => 'Height'],
            ['id' => 2, 'category_name' => 'Weight'],
            ['id' => 3, 'category_name' => 'Chest'],
            ['id' => 4, 'category_name' => 'Bust'],
            ['id' => 5, 'category_name' => 'Waist']
        ];

        DB::table('tesco_secondary_size_category')->insert($sec_categories);
    }
}
