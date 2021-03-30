<?php

use Illuminate\Database\Seeder;

class TescoSizeDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [
            ['id' => 1, 'department_name' => 'Accessories Baby'],
            ['id' => 2, 'department_name' => 'Accessories Kids'],
            ['id' => 3, 'department_name' => 'Baby Age'],
            ['id' => 4, 'department_name' => 'Baby Sleeping Bags'],
            ['id' => 5, 'department_name' => 'Boys'],
            ['id' => 6, 'department_name' => 'Generic'],
            ['id' => 7, 'department_name' => 'Generous Fit Boys'],
            ['id' => 8, 'department_name' => 'Generous Fit Girls'],
            ['id' => 9, 'department_name' => 'Mens Tops - Exlclude Formal Shirts'],
            ['id' => 10, 'department_name' => 'Mens Trouser/Bottoms Generic'],
            ['id' => 11, 'department_name' => 'Mens Underwear'],
            ['id' => 12, 'department_name' => 'Unisex Kids'],
            ['id' => 13, 'department_name' => 'Womens Dual'],
            ['id' => 14, 'department_name' => 'Womens Generic'],
            ['id' => 15, 'department_name' => 'Womens Outerwear'],
            ['id' => 16, 'department_name' => 'Girls']
        ];

        DB::table('tesco_size_department')->insert($departments);
    }
}
