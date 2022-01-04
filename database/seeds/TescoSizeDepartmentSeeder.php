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
            ['id' => 1, 'department_name' => 'Accessories - Baby'],
            ['id' => 2, 'department_name' => 'Accessories - Kids (Part of Set)'],
            ['id' => 3, 'department_name' => 'Accessories - Kids'],
            ['id' => 4, 'department_name' => 'Baby - Tops / dress / sleepsuit / bodysuit'],
            ['id' => 5, 'department_name' => 'Baby - Booties'],
            ['id' => 6, 'department_name' => 'Baby - Bottoms'],
            ['id' => 7, 'department_name' => 'Baby -  All in Ones'],
            ['id' => 8, 'department_name' => 'Dress-Up - Baby'],
            ['id' => 9, 'department_name' => 'Baby Sleeping Bags'],
            ['id' => 10, 'department_name' => 'Boys - Tops'],
            ['id' => 11, 'department_name' => 'Boys - Back to School Shirts'],
            ['id' => 12, 'department_name' => 'Boys - Bottoms'],
            ['id' => 13, 'department_name' => 'Boys - Top & Bottoms'],
            ['id' => 14, 'department_name' => 'Boys - All in Ones'],
            ['id' => 15, 'department_name' => 'Boys Generic'],
            ['id' => 16, 'department_name' => 'Dress-Up - Boys Tops'],
            ['id' => 17, 'department_name' => 'Dress-Up - Boys Top & Bottoms'],
            ['id' => 18, 'department_name' => 'Dress-Up - Boys Bottoms'],
            ['id' => 19, 'department_name' => 'Dress-Up - Boys All in Ones'],
            ['id' => 20, 'department_name' => 'Dog (Halloween Costume)'],
            ['id' => 21, 'department_name' => 'Footwear - Baby'],
            ['id' => 22, 'department_name' => 'Footwear - Baby/Kids'],
            ['id' => 23, 'department_name' => 'Footwear - Kids (larger sizes)'],
            ['id' => 24, 'department_name' => 'Footwear - Ladies'],
            ['id' => 25, 'department_name' => 'Footwear - Mens'],
            ['id' => 26, 'department_name' => 'Generic (no secondary sizing)'],
            ['id' => 27, 'department_name' => 'Plus Fit - Boys Tops'],
            ['id' => 28, 'department_name' => 'Plus Fit - Boys Bottoms'],
            ['id' => 29, 'department_name' => 'Plus Fit - Girls Tops'],
            ['id' => 30, 'department_name' => 'Plus Fit - Girls Bottoms'],
            ['id' => 31, 'department_name' => 'Plus Fit - Girls All in Ones/Playsuits/Dresses'],
            ['id' => 32, 'department_name' => 'Unisex Kids - Tops'],
            ['id' => 33, 'department_name' => 'Unisex Kids - Top and Bottoms'],
            ['id' => 34, 'department_name' => 'Unisex Kids - Bottoms'],
            ['id' => 35, 'department_name' => 'Unisex Kids - All in Ones/Playsuits'],
            ['id' => 36, 'department_name' => 'Girls - Tops'],
            ['id' => 37, 'department_name' => 'Girls - Top & Bottoms'],
            ['id' => 38, 'department_name' => 'Girls - Bottoms'],
            ['id' => 39, 'department_name' => 'Girls - All in Ones/Playsuits/Dresses'],
            ['id' => 40, 'department_name' => 'Girls Generic'],
            ['id' => 41, 'department_name' => 'Dress-up - Girls Tops'],
            ['id' => 42, 'department_name' => 'Dress-Up - Girls Top and Bottoms'],
            ['id' => 43, 'department_name' => 'Dress-up - Girls Bottoms'],
            ['id' => 44, 'department_name' => 'Dress-up - Girls All in Ones/Playsuits/Dresses'],
            ['id' => 45, 'department_name' => 'Ladies Hosiery'],
            ['id' => 46, 'department_name' => 'Mens Formal Shirts'],
            ['id' => 47, 'department_name' => 'Womens Lingerie & Swimwear Top - Bra with Cup'],
            ['id' => 48, 'department_name' => 'Mens Formal Jackets'],
            ['id' => 49, 'department_name' => 'Mens Generic Tops (exc formal shirts)'],
            ['id' => 50, 'department_name' => 'Mens Trouser (waist/leg)'],
            ['id' => 51, 'department_name' => 'Mens Generic Trouser/Bottoms (waist only)'],
            ['id' => 52, 'department_name' => 'Mens Generic Trouser/Bottoms'],
            ['id' => 53, 'department_name' => 'Mens Underwear'],
            ['id' => 54, 'department_name' => 'Mens waist only measurement'],
            ['id' => 55, 'department_name' => 'Socks - Adult'],
            ['id' => 56, 'department_name' => 'Socks - Adult (Ladies)'],
            ['id' => 57, 'department_name' => 'Socks - Adult (Mens)'],
            ['id' => 58, 'department_name' => 'Socks - Baby'],
            ['id' => 59, 'department_name' => 'Socks - Kids'],
            ['id' => 60, 'department_name' => 'Womens Dual Tops'],
            ['id' => 61, 'department_name' => 'Womens Dual Tops & Bottoms'],
            ['id' => 62, 'department_name' => 'Womens Dual Bottoms'],
            ['id' => 63, 'department_name' => 'Womens Generic Tops'],
            ['id' => 64, 'department_name' => 'Womens Generic Top & Bottom'],
            ['id' => 65, 'department_name' => 'Womens Generic Bottoms'],
            ['id' => 66, 'department_name' => 'Womens Generic Outerwear/Dress/Tunic/Playsuit'],
            ['id' => 67, 'department_name' => 'Womens Outerwear/Dress/Tunic/Playsuit (Sizes 4-26)'],
            ['id' => 68, 'department_name' => 'Womens Tops (Sizes 4-26)'],
            ['id' => 69, 'department_name' => 'Womens Lingerie & Swimwear Bottoms (Sizes 4-26)'],
            ['id' => 70, 'department_name' => 'Womens Lingerie - Bodies (Sizes 4-26)'],
            ['id' => 71, 'department_name' => 'Womens Swimwear - Swimsuit (Sizes 4-26)'],
            ['id' => 72, 'department_name' => 'Womens Bottoms  (Sizes 4-26)'],
            ['id' => 73, 'department_name' => 'Womens Denim  (Sizes 4-26)'],
            ['id' => 74, 'department_name' => 'Womens Denim - Short / Regular / Long  (Sizes 4-26)'],
            ['id' => 75, 'department_name' => 'Womens Lingerie & Swimwear - Tops (Sizes 4-26)'],
            ['id' => 76, 'department_name' => 'Womens Swimwear - Bra Sized Swimsuit'],
            ['id' => 77, 'department_name' => 'Accessories - Adults'],
            ['id' => 78, 'department_name' => 'Mens swim'],
            ['id' => 79, 'department_name' => 'Kids Swimwear - Unisex Towelling Ponchos']
        ];

        DB::table('tesco_size_department')->insert($departments);
    }
}
