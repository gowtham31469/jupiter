<?php

use Illuminate\Database\Seeder;

class ReportsTemplateLevelName extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('reports_template_level_name')->delete();
        
        \DB::table('reports_template_level_name')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Ratio Carton Sticker',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Ratio Polybag Sticker',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Single Carton Sticker',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Single Polybag Sticker',
            )
        ));
    }
}
