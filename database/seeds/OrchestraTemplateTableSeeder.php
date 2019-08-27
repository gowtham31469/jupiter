<?php

use Illuminate\Database\Seeder;

class OrchestraTemplateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\OrchestraTemplate::create([
            'item_id' => 4,
            'template_name' => "Orchestra Label",
            'template_description' => "Orchestra Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/Wash_Care_Label/WITHOUT_DISNEY_Front.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 2,
        ]);

        \App\OrchestraTemplate::create([
            'item_id' => 4,
            'template_name' => "Orchestra Label",
            'template_description' => "Orchestra Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/Wash_Care_Label/WITHOUT_DISNEY_unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 2,
        ]);

        \App\OrchestraTemplate::create([
            'item_id' => 4,
            'template_name' => "Orchestra Label",
            'template_description' => "Orchestra Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/Wash_Care_Label/WITHOUT_DISNEY_unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 2,
            'hierarchy_id' => 2,
        ]);

        \App\OrchestraTemplate::create([
            'item_id' => 5,
            'template_name' => "DISNEY",
            'template_description' => "DISNEY",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/Wash_Care_Label/DISNEY_Front.svg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 2,
        ]);

        \App\OrchestraTemplate::create([
            'item_id' => 5,
            'template_name' => "DISNEY",
            'template_description' => "DISNEY",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/Wash_Care_Label/DISNEY_unknown",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 2,
        ]);

        \App\OrchestraTemplate::create([
            'item_id' => 5,
            'template_name' => "DISNEY",
            'template_description' => "DISNEY",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/Wash_Care_Label/DISNEY_unknown",
            'disney' => 1,
            'tax' => 0,
            'flag' => 2,
            'hierarchy_id' => 2,
        ]);

        \App\OrchestraTemplate::create([
            'item_id' => 6,
            'template_name' => "DISNEY BABY",
            'template_description' => "DISNEY BABY",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/Wash_Care_Label/DISNEY_BABY_Front.svg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 2,
        ]);

        \App\OrchestraTemplate::create([
            'item_id' => 6,
            'template_name' => "DISNEY BABY",
            'template_description' => "DISNEY BABY",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/Wash_Care_Label/DISNEY_BABY_unknown",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 2,
        ]);

        \App\OrchestraTemplate::create([
            'item_id' => 6,
            'template_name' => "DISNEY BABY",
            'template_description' => "DISNEY BABY",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/Wash_Care_Label/DISNEY_BABY_unknown",
            'disney' => 1,
            'tax' => 0,
            'flag' => 2,
            'hierarchy_id' => 2,
        ]);
       
       \App\OrchestraTemplate::create([
            'item_id' => 8,
            'template_name' => "MARVEL",
            'template_description' => "MARVEL",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/Wash_Care_Label/MARVEL_Front.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 2,
        ]);

        \App\OrchestraTemplate::create([
            'item_id' => 8,
            'template_name' => "MARVEL",
            'template_description' => "MARVEL",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/Wash_Care_Label/MARVEL_unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 2,
        ]);

        \App\OrchestraTemplate::create([
            'item_id' => 8,
            'template_name' => "MARVEL",
            'template_description' => "MARVEL",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/Wash_Care_Label/MARVEL_unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 2,
            'hierarchy_id' => 2,
        ]);

        \App\OrchestraTemplate::create([
            'item_id' => 9,
            'template_name' => "STARWARS",
            'template_description' => "STARWARS",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/Wash_Care_Label/STARWARS_Front.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 2,
        ]);

        \App\OrchestraTemplate::create([
            'item_id' => 9,
            'template_name' => "STARWARS",
            'template_description' => "STARWARS",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/Wash_Care_Label/STARWARS_unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 2,
        ]);

        \App\OrchestraTemplate::create([
            'item_id' => 9,
            'template_name' => "STARWARS",
            'template_description' => "STARWARS",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/Wash_Care_Label/STARWARS_unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 2,
            'hierarchy_id' => 2,
        ]);


        // Printed Boards


          \App\OrchestraTemplate::create([
            'item_id' => 108,
            'template_name' => "T01A",
            'template_description' => "T01A",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T01A-01.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 103,
            ]);
            
            \App\OrchestraTemplate::create([
            'item_id' => 108,
            'template_name' => "T01A",
            'template_description' => "T01A",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T01A-02.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 103,
            ]);

             \App\OrchestraTemplate::create([
            'item_id' => 109,
            'template_name' => "T01B",
            'template_description' => "T01B",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T01B-01.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 103,
            ]);
            
            \App\OrchestraTemplate::create([
            'item_id' => 109,
            'template_name' => "T01B",
            'template_description' => "T01B",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T01B-02.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 103,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 110,
            'template_name' => "T01C",
            'template_description' => "T01C",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T01C-01.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 103,
            ]);
            
            \App\OrchestraTemplate::create([
            'item_id' => 110,
            'template_name' => "T01C",
            'template_description' => "T01C",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T01C-02.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 103,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 111,
            'template_name' => "T01D",
            'template_description' => "T01D",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T01D-01.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 103,
            ]);
            
            \App\OrchestraTemplate::create([
            'item_id' => 111,
            'template_name' => "T01D",
            'template_description' => "T01D",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T01D-02.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 103,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 112,
            'template_name' => "T01E",
            'template_description' => "T01E",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T01D-02.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 103,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 112,
            'template_name' => "T01E",
            'template_description' => "T01E",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T01D-02.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 103,
            ]);

             \App\OrchestraTemplate::create([
            'item_id' => 114,
            'template_name' => "T02A",
            'template_description' => "T02A",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T02A-01.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 103,
            ]);
            
            \App\OrchestraTemplate::create([
            'item_id' => 114,
            'template_name' => "T02A",
            'template_description' => "T02A",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T02A-02.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 103,
            ]);

             \App\OrchestraTemplate::create([
            'item_id' => 115,
            'template_name' => "T02B",
            'template_description' => "T02B",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T02A-01.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 103,
            ]);
            
            \App\OrchestraTemplate::create([
            'item_id' => 115,
            'template_name' => "T02B",
            'template_description' => "T02B",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T02A-02.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 103,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 116,
            'template_name' => "T02C",
            'template_description' => "T02C",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T02C-01.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 103,
            ]);
            
            \App\OrchestraTemplate::create([
            'item_id' => 116,
            'template_name' => "T02C",
            'template_description' => "T02C",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T02C-02.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 103,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 117,
            'template_name' => "T02D",
            'template_description' => "T02D",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T02C-01.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 103,
            ]);
            
            \App\OrchestraTemplate::create([
            'item_id' => 117,
            'template_name' => "T02D",
            'template_description' => "T02D",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T02C-02.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 103,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 118,
            'template_name' => "T02E",
            'template_description' => "T02E",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T02C-01.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 103,
            ]);
            
            \App\OrchestraTemplate::create([
            'item_id' => 118,
            'template_name' => "T02E",
            'template_description' => "T02E",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T02C-02.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 103,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 120,
            'template_name' => "T05A",
            'template_description' => "T05A",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T05A-01.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 103,
            ]);
            
            \App\OrchestraTemplate::create([
            'item_id' => 120,
            'template_name' => "T05A",
            'template_description' => "T05A",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T05A-02.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 103,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 121,
            'template_name' => "T05B",
            'template_description' => "T05B",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T05A-01.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 103,
            ]);
            
            \App\OrchestraTemplate::create([
            'item_id' => 121,
            'template_name' => "T05B",
            'template_description' => "T05B",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T05A-02.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 103,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 122,
            'template_name' => "T05C",
            'template_description' => "T05C",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T05D-01.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 103,
            ]);
            
            \App\OrchestraTemplate::create([
            'item_id' => 122,
            'template_name' => "T05C",
            'template_description' => "T05C",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T05D-02.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 103,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 123,
            'template_name' => "T05D",
            'template_description' => "T05D",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T05C-01.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 103,
            ]);
            
            \App\OrchestraTemplate::create([
            'item_id' => 123,
            'template_name' => "T05D",
            'template_description' => "T05D",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T05C-02.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 103,
            ]);


            \App\OrchestraTemplate::create([
            'item_id' => 157,
            'template_name' => "DBB1",
            'template_description' => "DBB1",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/DBB1/front.svg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 155,
            ]);
            
            \App\OrchestraTemplate::create([
            'item_id' => 157,
            'template_name' => "DBB1",
            'template_description' => "DBB1",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/DBB1/unknown",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 155,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 158,
            'template_name' => "DBB2",
            'template_description' => "DBB2",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/DBB1/front.svg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 155,
            ]);
            
            \App\OrchestraTemplate::create([
            'item_id' => 158,
            'template_name' => "DBB2",
            'template_description' => "DBB2",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/DBB1/unknown",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 155,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 159,
            'template_name' => "DBB3",
            'template_description' => "DBB3",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/DBB1/front.svg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 155,
            ]);
            
            \App\OrchestraTemplate::create([
            'item_id' => 159,
            'template_name' => "DBB3",
            'template_description' => "DBB3",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/DBB1/unknown",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 155,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 161,
            'template_name' => "COD1",
            'template_description' => "COD1",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD1/front.svg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 155,
            ]);
            
            \App\OrchestraTemplate::create([
            'item_id' => 161,
            'template_name' => "COD1",
            'template_description' => "COD1",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD1/unknown",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 155,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 162,
            'template_name' => "COD2",
            'template_description' => "COD2",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/front.svg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 155,
            ]);
            
            \App\OrchestraTemplate::create([
            'item_id' => 162,
            'template_name' => "COD2",
            'template_description' => "COD2",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 155,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 163,
            'template_name' => "COD2x",
            'template_description' => "COD2x",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/front.svg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 155,
            ]);
            
            \App\OrchestraTemplate::create([
            'item_id' => 163,
            'template_name' => "COD2x",
            'template_description' => "COD2x",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 155,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 164,
            'template_name' => "COD3",
            'template_description' => "COD3",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/front.svg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 155,
            ]);
            
            \App\OrchestraTemplate::create([
            'item_id' => 164,
            'template_name' => "COD3",
            'template_description' => "COD3",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 155,
            ]);



            \App\OrchestraTemplate::create([
            'item_id' => 166,
            'template_name' => "COD1",
            'template_description' => "COD1",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD1/front.svg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 155,
            ]);
            
            \App\OrchestraTemplate::create([
            'item_id' => 166,
            'template_name' => "COD1",
            'template_description' => "COD1",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD1/unknown",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 155,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 167,
            'template_name' => "COD2",
            'template_description' => "COD2",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/front.svg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 155,
            ]);
            
            \App\OrchestraTemplate::create([
            'item_id' => 167,
            'template_name' => "COD2",
            'template_description' => "COD2",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 155,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 168,
            'template_name' => "COD2x",
            'template_description' => "COD2x",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/front.svg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 155,
            ]);
            
            \App\OrchestraTemplate::create([
            'item_id' => 168,
            'template_name' => "COD2x",
            'template_description' => "COD2x",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 155,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 169,
            'template_name' => "COD3",
            'template_description' => "COD3",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/front.svg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 155,
            ]);
            
            \App\OrchestraTemplate::create([
            'item_id' => 169,
            'template_name' => "COD3",
            'template_description' => "COD3",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 155,
            ]);


            \App\OrchestraTemplate::create([
            'item_id' => 169,
            'template_name' => "COD3",
            'template_description' => "COD3",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 155,
            ]);


            \App\OrchestraTemplate::create([
            'item_id' => 172,
            'template_name' => "Boy-DM1",
            'template_description' => "Boy-DM1",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 171,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 172,
            'template_name' => "Boy-DM1",
            'template_description' => "Boy-DM1",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 171,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 173,
            'template_name' => "Girl-DM2",
            'template_description' => "Boy-DM1",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 171,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 173,
            'template_name' => "Girl-DM2",
            'template_description' => "Boy-DM1",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 171,
            ]);


            \App\OrchestraTemplate::create([
            'item_id' => 176,
            'template_name' => "Boy-DSW",
            'template_description' => "Boy-DSW",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 175,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 176,
            'template_name' => "Boy-DSW",
            'template_description' => "Boy-DSW",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 175,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 177,
            'template_name' => "Girl-DSW",
            'template_description' => "Boy-DSW",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 175,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 177,
            'template_name' => "Girl-DSW",
            'template_description' => "Boy-DSW",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 175,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 180,
            'template_name' => "SM1BB1",
            'template_description' => "SM1BB1",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 179,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 180,
            'template_name' => "SM1BB1",
            'template_description' => "SM1BB1",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 179,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 181,
            'template_name' => "SMI1",
            'template_description' => "SMI1",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 179,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 181,
            'template_name' => "SMI1",
            'template_description' => "SMI1",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 179,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 181,
            'template_name' => "Batman-HTBT",
            'template_description' => "Batman-HTBT",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 183,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 181,
            'template_name' => "Batman-HTBT",
            'template_description' => "Batman-HTBT",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 183,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 185,
            'template_name' => "LooneyToonesClassic-HTLT",
            'template_description' => "LooneyToonesClassic-HTLT",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 183,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 185,
            'template_name' => "LooneyToonesClassic-HTLT",
            'template_description' => "LooneyToonesClassic-HTLT",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 183,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 186,
            'template_name' => "Superman-HTSM",
            'template_description' => "Superman-HTSM",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 183,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 186,
            'template_name' => "Superman-HTSM",
            'template_description' => "Superman-HTSM",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 183,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 186,
            'template_name' => "WonderWoman-WWHT",
            'template_description' => "WonderWoman-WWHT",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 183,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 186,
            'template_name' => "WonderWoman-WWHT",
            'template_description' => "WonderWoman-WWHT",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 183,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 190,
            'template_name' => "Baby-T12",
            'template_description' => "Baby-T12",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 189,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 190,
            'template_name' => "Baby-T12",
            'template_description' => "Baby-T12",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 189,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 193,
            'template_name' => "HM1BA",
            'template_description' => "HM1BA",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 192,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 193,
            'template_name' => "HM1BA",
            'template_description' => "HM1BA",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 192,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 194,
            'template_name' => "HM1BRA",
            'template_description' => "HM1BRA",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 192,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 194,
            'template_name' => "HM1BRA",
            'template_description' => "HM1BRA",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 192,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 195,
            'template_name' => "HM1D",
            'template_description' => "HM1D",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 192,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 195,
            'template_name' => "HM1D",
            'template_description' => "HM1D",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 192,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 196,
            'template_name' => "HM1",
            'template_description' => "HM1",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 192,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 196,
            'template_name' => "HM1",
            'template_description' => "HM1",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 192,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 197,
            'template_name' => "HM2BRA",
            'template_description' => "HM2BRA",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 192,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 197,
            'template_name' => "HM2BRA",
            'template_description' => "HM2BRA",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 192,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 198,
            'template_name' => "MPT",
            'template_description' => "MPT",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 192,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 198,
            'template_name' => "MPT",
            'template_description' => "MPT",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 192,
            ]);

            // Heat Transfer

            \App\OrchestraTemplate::create([
            'item_id' => 64,
            'template_name' => "L09",
            'template_description' => "L09",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 67,
            'template_name' => "PBB1",
            'template_description' => "PBB1",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT_TRANSFER/DISNEY_H_TRANSFER/BABY/PBB1H.TRANSFER DISNEYBABY.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 67,
            'template_name' => "PBB1",
            'template_description' => "PBB1",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2//storage/app/template/Orchestra/Labels/HEAT_TRANSFER/DISNEY_H_TRANSFER/BABY/PBB2-H.TRANSFER DISNEY BABYDISNEY PIXAR.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 68,
            'template_name' => "PBB2",
            'template_description' => "PBB2",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT_TRANSFER/DISNEY_H_TRANSFER/BABY/PBB3-H.TRANSFER DISNEY BABY DISNEY WINNIE.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 69,
            'template_name' => "PBB3",
            'template_description' => "PBB3",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT_TRANSFER/DISNEY_H_TRANSFER/BABY/PBB3-H.TRANSFER DISNEY BABY DISNEY WINNIE.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 71,
            'template_name' => "PD1",
            'template_description' => "PD1",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT_TRANSFER/DISNEY_H_TRANSFER/GIRL_BOY/PD1-H.TRANSFER DISNEYDISNEY.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 72,
            'template_name' => "PD2",
            'template_description' => "PD2",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT_TRANSFER/DISNEY_H_TRANSFER/GIRL_BOY/PD2-H.TRANSFER DISNEYDISNEY PIXAR.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 73,
            'template_name' => "PD3",
            'template_description' => "PD3",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT_TRANSFER/DISNEY_H_TRANSFER/GIRL_BOY/PD3-H.TRANSFER DISNEYDISNEY WINNIE.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 75,
            'template_name' => "PD1",
            'template_description' => "PD1",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT_TRANSFER/DISNEY_H_TRANSFER/GIRL_BOY/PD1-H.TRANSFER DISNEYDISNEY.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 76,
            'template_name' => "PD2",
            'template_description' => "PD2",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT_TRANSFER/DISNEY_H_TRANSFER/GIRL_BOY/PD2-H.TRANSFER DISNEYDISNEY PIXAR.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 77,
            'template_name' => "PD3",
            'template_description' => "PD3",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT_TRANSFER/DISNEY_H_TRANSFER/GIRL_BOY/PD3-H.TRANSFER DISNEYDISNEY WINNIE.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 79,
            'template_name' => "PM1",
            'template_description' => "PM1",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT_TRANSFER/MARVEL_H_TRANSFER/PM1-H.TRANSFERMARVEL.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 81,
            'template_name' => "PSW1",
            'template_description' => "PSW1",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT_TRANSFER/STARWARS_H_TRANSFER/PSW1-H.TRANSFER STARWARSLUCASFILM.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 83,
            'template_name' => "PSMIBB",
            'template_description' => "PSMIBB",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT_TRANSFER/SMILEY_H_TRANSFER/SMILEY BABY/PSMIBB-SMILEY BABY.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 84,
            'template_name' => "PSMI",
            'template_description' => "PSMI",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT_TRANSFER/SMILEY_H_TRANSFER/SMILEY WORLD/PSMI-SMILEY WORLD.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 86,
            'template_name' => "Batman – PBA",
            'template_description' => "Batman – PBA",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT_TRANSFER/WANER H.TRANSFER/BATMAN/PBA-BATMAN.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 87,
            'template_name' => "LooneyTunes – PLT",
            'template_description' => "LooneyTunes – PLT",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT_TRANSFER/WANER H.TRANSFER/LOONEY TUNES/PLT-LOONEY TUNES.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 88,
            'template_name' => "Superman – PSM",
            'template_description' => "Superman – PSM",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT_TRANSFER/WANER H.TRANSFER/SUPERMAN/PSM-SUPERMAN.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 89,
            'template_name' => "Wonder woman - WWP",
            'template_description' => "Wonder woman - WWP",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT_TRANSFER/WANER H.TRANSFER/WONDER WOMEN/WWP-WONDER WOMAN PRINT LABEL.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);


            // Woven Labels 

            \App\OrchestraTemplate::create([
            'item_id' => 18,
            'template_name' => "L01A Main Label",
            'template_description' => "L01A Main Label",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 19,
            'template_name' => "L01A Size Label",
            'template_description' => "L01A Size Label",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 22,
            'template_name' => "WBB1 Main Label",
            'template_description' => "WBB1 Main Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WBB1_WOVEN_DISNEYBABY@DISNEY_M.L.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 23,
            'template_name' => "WBB1 Size Label",
            'template_description' => "WBB1 Size Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WBB1_WOVEN_DISNEYBABY@DISNEY_S.L.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 24,
            'template_name' => "WBB2 Main Label",
            'template_description' => "WBB2 Main Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WBB2 - WOVEN DISNEYBABY@ PIXAR M.L.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 25,
            'template_name' => "WBB2 Size Label",
            'template_description' => "WBB2 Size Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WBB2 - WOVEN DISNEYBABY@ PIXAR S.L.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 26,
            'template_name' => "WBB3 Main Label",
            'template_description' => "WBB3 Main Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WBB3 - WOVEN DISNEYBABY@WINNIE M.L.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 27,
            'template_name' => "WBB3 Size Label",
            'template_description' => "WBB3 Size Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WBB3 - WOVEN DISNEYBABY@WINNIE S.L.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);


            \App\OrchestraTemplate::create([
            'item_id' => 29,
            'template_name' => "WD1 Main Label",
            'template_description' => "WD1 Main Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WD1-Woven CDisney.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 30,
            'template_name' => "WD1 Size Label",
            'template_description' => "WD1 Size Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WD1-Woven CDisney.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);


            \App\OrchestraTemplate::create([
            'item_id' => 31,
            'template_name' => "WD2 Main Label",
            'template_description' => "WD2 Main Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WD2-Woven CDisneypixar.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 32,
            'template_name' => "WD2 Size Label",
            'template_description' => "WD2 Size Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WD2-SIZE LABEL.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 33,
            'template_name' => "WD3 Main Label",
            'template_description' => "WD3 Main Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WD3-Woven CDisneyWinnie.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 34,
            'template_name' => "WD3 Size Label",
            'template_description' => "WD3 Size Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WD3-SIZE LABEL.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);


            \App\OrchestraTemplate::create([
            'item_id' => 36,
            'template_name' => "WD1 Main Label",
            'template_description' => "WD1 Main Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WD1-Woven CDisney.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 37,
            'template_name' => "WD1 Size Label",
            'template_description' => "WD1 Size Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WD1-Woven CDisney.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);


            \App\OrchestraTemplate::create([
            'item_id' => 38,
            'template_name' => "WD2 Main Label",
            'template_description' => "WD2 Main Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WD2-Woven CDisneypixar.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 39,
            'template_name' => "WD2 Size Label",
            'template_description' => "WD2 Size Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WD2-SIZE LABEL.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 40,
            'template_name' => "WD3 Main Label",
            'template_description' => "WD3 Main Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WD3-Woven CDisneyWinnie.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 41,
            'template_name' => "WD3 Size Label",
            'template_description' => "WD3 Size Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WD3-SIZE LABEL.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);


            \App\OrchestraTemplate::create([
            'item_id' => 43,
            'template_name' => "WDM Main Label",
            'template_description' => "WDM Main Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WDM-WovenCMAVEL.png",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 44,
            'template_name' => "WDM Size Label",
            'template_description' => "WDM Size Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WDm-SIZE LABEL.png",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 46,
            'template_name' => "WSW1 Main Label",
            'template_description' => "WSW1 Main Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WSW1 - STARWARS M.L.png",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 47,
            'template_name' => "WSW1 Size Label",
            'template_description' => "WSW1 Size Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WSW1 - STARWARS S.L.png",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 49,
            'template_name' => "WSM1BB Main Label",
            'template_description' => "WSM1BB Main Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WSMIBB - SMILEY BB M.L.png",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 50,
            'template_name' => "WSM1BB Size Label",
            'template_description' => "WSM1BB Size Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WSMIBB - SMILEY BB S.L.png",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);


            \App\OrchestraTemplate::create([
            'item_id' => 51,
            'template_name' => "WSM1 Main Label",
            'template_description' => "WSM1 Main Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WSMI - SMILEY WORLD M.L.png",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 52,
            'template_name' => "WSM1 Size Label",
            'template_description' => "WSM1 Size Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WSMI - SMILEY WORLD S.L.png",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);


            \App\OrchestraTemplate::create([
            'item_id' => 54,
            'template_name' => "Batman - WBA Main Label",
            'template_description' => "Batman - WBA Main Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WBA- BATMAN WOVEN M.L.png",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 55,
            'template_name' => "Batman - WBA Size Label",
            'template_description' => "Batman - WBA Size Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WBA- BATMAN WOVEN S.L.png",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 56,
            'template_name' => "LooneyTunes - WLT Main Label",
            'template_description' => "LooneyTunes - WLT Main Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WLT-LONEYTUNES M.L.png",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 57,
            'template_name' => "LooneyTunes - WLT Size Label",
            'template_description' => "LooneyTunes - WLT Size Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WLT-LONEYTUNES S.L.png",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);


            \App\OrchestraTemplate::create([
            'item_id' => 58,
            'template_name' => "Superman - WSM Main Label",
            'template_description' => "Superman - WSM Main Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WSM - SUPERMAN M.L.png",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 59,
            'template_name' => "Superman - WSM Size Label",
            'template_description' => "Superman - WSM Size Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WSM - SUPERMAN S.L.png",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);



            \App\OrchestraTemplate::create([
            'item_id' => 60,
            'template_name' => "Wonder women - WWV Main Label",
            'template_description' => "Wonder women - WWV Main Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WWW_WonderWomen.png",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 61,
            'template_name' => "Wonder women - WWV Size Label",
            'template_description' => "Wonder women - WWV Size Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WWV - WONDER WOMAN S.L.png",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 16,
            ]);


            //Carton & Polybag Stickers

            \App\OrchestraTemplate::create([
            'item_id' => 99,
            'template_name' => "Solid Carton Stickers",
            'template_description' => "Solid Carton Stickers",
            'template_file' => "/storage/app/template/Orchestra/Stickers/solid_carton_sticker.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 98,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 100,
            'template_name' => "Solid Polybag Stickers",
            'template_description' => "Solid Polybag Stickers",
            'template_file' => "/storage/app/template/Orchestra/Stickers/solidpolysticker.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 98,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 101,
            'template_name' => "Standard Carton Stickers",
            'template_description' => "Standard Carton Stickers",
            'template_file' => "/storage/app/template/Orchestra/Stickers/std-carton-sticker.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 98,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 102,
            'template_name' => "Standard Polybag Stickers",
            'template_description' => "Standard Polybag Stickers",
            'template_file' => "/storage/app/template/Orchestra/Stickers/std-polybag-sticker.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 98,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 11,
            'template_name' => "L01 Main Label Satin",
            'template_description' => "L01 Main Label Satin",
            'template_file' => "/storage/app/template/Orchestra/Stickers/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 2,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 12,
            'template_name' => "L01 Size Label Satin",
            'template_description' => "L01 Size Label Satin",
            'template_file' => "/storage/app/template/Orchestra/Stickers/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 2,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 14,
            'template_name' => "L01B Main  Label Polycotton",
            'template_description' => "L01B Main  Label Polycotton",
            'template_file' => "/storage/app/template/Orchestra/Stickers/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 2,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 15,
            'template_name' => "L01B Size  Label Polycotton",
            'template_description' => "L01B Size  Label Polycotton",
            'template_file' => "/storage/app/template/Orchestra/Stickers/unknown",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 2,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 201,
            'template_name' => "L09 Print Label Dark",
            'template_description' => "L09 Print Label Dark",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT_TRANSFER/ORC DEPT H.TRANSFER/L09 - Print Label DARK COLOR.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 202,
            'template_name' => "L09 Print Label Light",
            'template_description' => "L09 Print Label Light",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT_TRANSFER/ORC DEPT H.TRANSFER/L09 - Print Label LIGHT COLOR.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            //Special Tags

            \App\OrchestraTemplate::create([
            'item_id' => 139,
            'template_name' => "T10A",
            'template_description' => "T10A",
            'template_file' => "/storage/app/template/Orchestra/Tags/Special_Tags/Merged/T10A.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 133,
            'template_name' => "T10C",
            'template_description' => "T10C",
            'template_file' => "/storage/app/template/Orchestra/Tags/Special_Tags/Merged/T10C.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 132,
            'template_name' => "T10M2",
            'template_description' => "T10M2",
            'template_file' => "/storage/app/template/Orchestra/Tags/Special_Tags/Merged/T10M2.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 138,
            'template_name' => "T10P",
            'template_description' => "T10P",
            'template_file' => "/storage/app/template/Orchestra/Tags/Special_Tags/Merged/T10P.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 130,
            'template_name' => "T10Q",
            'template_description' => "T10Q",
            'template_file' => "/storage/app/template/Orchestra/Tags/Special_Tags/Merged/T10Q.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 131,
            'template_name' => "T10R",
            'template_description' => "T10R",
            'template_file' => "/storage/app/template/Orchestra/Tags/Special_Tags/Merged/T10R.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 140,
            'template_name' => "T10W",
            'template_description' => "T10W",
            'template_file' => "/storage/app/template/Orchestra/Tags/Special_Tags/Merged/T10W.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 141,
            'template_name' => "T10Z",
            'template_description' => "T10Z",
            'template_file' => "/storage/app/template/Orchestra/Tags/Special_Tags/Merged/T10Z.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 129,
            'template_name' => "T10H",
            'template_description' => "T10H",
            'template_file' => "/storage/app/template/Orchestra/Tags/Special_Tags/Merged/T10H.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 129,
            'template_name' => "T10V",
            'template_description' => "T10V",
            'template_file' => "/storage/app/template/Orchestra/Tags/Special_Tags/Merged/T10V.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 129,
            'template_name' => "T10Y",
            'template_description' => "T10Y",
            'template_file' => "/storage/app/template/Orchestra/Tags/Special_Tags/Merged/T10Y.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            \App\OrchestraTemplate::create([
            'item_id' => 127,
            'template_name' => "T10R1",
            'template_description' => "T10R1",
            'template_file' => "/storage/app/template/Orchestra/Tags/Special_Tags/Merged/T10R1.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
            ]);

            
    }
}
