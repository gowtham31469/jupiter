<?php

use Illuminate\Database\Seeder;

class TescoTemplateTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tesco_template')->delete();
        
        \DB::table('tesco_template')->insert(array (
            0 => 
            array (
                'id' => 10,
                'item_id' => 14,
                'template_name' => 'TSL 1',
                'template_description' => 'TSL 1',
                'template_file' => '/storage/app/template/tesco/TSL1_UK.svg',
                'disney' => 0,
                'tax' => 0,
                'hierarchy_id' => 2,
                'flag' => 1,
            ),
            1 => 
            array (
                'id' => 11,
                'item_id' => 15,
                'template_name' => 'TCL 4',
                'template_description' => 'TCL 4',
                'template_file' => '/storage/app/template/tesco/TCL4_UK.svg',
                'disney' => 0,
                'tax' => 0,
                'hierarchy_id' => 2,
                'flag' => 1,
            ),
            2 => 
            array (
                'id' => 1,
                'item_id' => 5,
                'template_name' => 'TCL 1',
                'template_description' => 'TCL 1',
                'template_file' => '/storage/app/template/tesco/TCL1_CE.svg',
                'disney' => 0,
                'tax' => 0,
                'hierarchy_id' => 2,
                'flag' => 1,
            ),
            3 => 
            array (
                'id' => 2,
                'item_id' => 6,
                'template_name' => 'TCL 2',
                'template_description' => 'TCL 2',
                'template_file' => '/storage/app/template/tesco/TCL2_CE.svg',
                'disney' => 0,
                'tax' => 0,
                'hierarchy_id' => 2,
                'flag' => 1,
            ),
            4 => 
            array (
                'id' => 3,
                'item_id' => 7,
                'template_name' => 'TSL 1',
                'template_description' => 'TSL 1',
                'template_file' => '/storage/app/template/tesco/TSL1_CE.svg',
                'disney' => 0,
                'tax' => 0,
                'hierarchy_id' => 2,
                'flag' => 1,
            ),
            5 => 
            array (
                'id' => 4,
                'item_id' => 8,
                'template_name' => 'TCL 4',
                'template_description' => 'TCL 4',
                'template_file' => '/storage/app/template/tesco/TCL4_CE.svg',
                'disney' => 0,
                'tax' => 0,
                'hierarchy_id' => 2,
                'flag' => 1,
            ),
            6 => 
            array (
                'id' => 5,
                'item_id' => 9,
                'template_name' => 'TSL 3',
                'template_description' => 'TSL 3',
                'template_file' => '/storage/app/template/tesco/TSL3_CE.svg',
                'disney' => 0,
                'tax' => 0,
                'hierarchy_id' => 2,
                'flag' => 1,
            ),
            7 => 
            array (
                'id' => 7,
                'item_id' => 11,
                'template_name' => 'THL 3',
                'template_description' => 'THL 3',
                'template_file' => '/storage/app/template/tesco/THL3_CE.svg',
                'disney' => 0,
                'tax' => 0,
                'hierarchy_id' => 2,
                'flag' => 1,
            ),
            8 => 
            array (
                'id' => 8,
                'item_id' => 12,
                'template_name' => 'TCL 1',
                'template_description' => 'TCL 1',
                'template_file' => '/storage/app/template/tesco/TCL1_UK.svg',
                'disney' => 0,
                'tax' => 0,
                'hierarchy_id' => 2,
                'flag' => 1,
            ),
            9 => 
            array (
                'id' => 9,
                'item_id' => 13,
                'template_name' => 'TCL 2',
                'template_description' => 'TCL 2',
                'template_file' => '/storage/app/template/tesco/TCL2_UK.svg',
                'disney' => 0,
                'tax' => 0,
                'hierarchy_id' => 2,
                'flag' => 1,
            ),
            10 => 
            array (
                'id' => 12,
                'item_id' => 16,
                'template_name' => 'TSL 3',
                'template_description' => 'TSL 3',
                'template_file' => '/storage/app/template/tesco/TSL3_UK.svg',
                'disney' => 0,
                'tax' => 0,
                'hierarchy_id' => 2,
                'flag' => 1,
            ),
            11 => 
            array (
                'id' => 14,
                'item_id' => 18,
                'template_name' => 'THL 3',
                'template_description' => 'THL 3',
                'template_file' => '/storage/app/template/tesco/THL3_UK.svg',
                'disney' => 0,
                'tax' => 0,
                'hierarchy_id' => 2,
                'flag' => 1,
            ),
            12 => 
            array (
                'id' => 6,
                'item_id' => 10,
                'template_name' => 'THL 1',
                'template_description' => 'THL 1',
                'template_file' => '/storage/app/template/tesco/THL1_CE.svg',
                'disney' => 0,
                'tax' => 0,
                'hierarchy_id' => 2,
                'flag' => 1,
            ),
            13 => 
            array (
                'id' => 13,
                'item_id' => 17,
                'template_name' => 'THL 1',
                'template_description' => 'THL 1',
                'template_file' => '/storage/app/template/tesco/THL1_UK.svg',
                'disney' => 0,
                'tax' => 0,
                'hierarchy_id' => 2,
                'flag' => 1,
            ),
            15 => 
            array (
                'id' => 15,
                'item_id' => 19,
                'template_name' => 'TCL 1',
                'template_description' => 'TCL 1',
                'template_file' => '/storage/app/template/tesco/TCL1_CE.svg',
                'disney' => 0,
                'tax' => 0,
                'hierarchy_id' => 2,
                'flag' => 1,
            ),
            16 => 
            array (
                'id' => 16,
                'item_id' => 20,
                'template_name' => 'TCL 2',
                'template_description' => 'TCL 2',
                'template_file' => '/storage/app/template/tesco/TCL2_CE.svg',
                'disney' => 0,
                'tax' => 0,
                'hierarchy_id' => 2,
                'flag' => 1,
            ),
            17 => 
            array (
                'id' => 17,
                'item_id' => 21,
                'template_name' => 'TSL 1',
                'template_description' => 'TSL 1',
                'template_file' => '/storage/app/template/tesco/TSL1_CE.svg',
                'disney' => 0,
                'tax' => 0,
                'hierarchy_id' => 2,
                'flag' => 1,
            ),
            18 => 
            array (
                'id' => 18,
                'item_id' => 22,
                'template_name' => 'TCL 4',
                'template_description' => 'TCL 4',
                'template_file' => '/storage/app/template/tesco/TCL4_CE.svg',
                'disney' => 0,
                'tax' => 0,
                'hierarchy_id' => 2,
                'flag' => 1,
            ),
            19 => 
            array (
                'id' => 19,
                'item_id' => 23,
                'template_name' => 'TSL 3',
                'template_description' => 'TSL 3',
                'template_file' => '/storage/app/template/tesco/TSL3_CE.svg',
                'disney' => 0,
                'tax' => 0,
                'hierarchy_id' => 2,
                'flag' => 1,
            ),
            20 => 
            array (
                'id' => 20,
                'item_id' => 24,
                'template_name' => 'THL 3',
                'template_description' => 'THL 3',
                'template_file' => '/storage/app/template/tesco/THL3_CE.svg',
                'disney' => 0,
                'tax' => 0,
                'hierarchy_id' => 2,
                'flag' => 1,
            ),
            21 => 
            array (
                'id' => 21,
                'item_id' => 25,
                'template_name' => 'THL 1',
                'template_description' => 'THL 1',
                'template_file' => '/storage/app/template/tesco/THL1_CE.svg',
                'disney' => 0,
                'tax' => 0,
                'hierarchy_id' => 2,
                'flag' => 1,
            ),
        ));
        
        
    }
}