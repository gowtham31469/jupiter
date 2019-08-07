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
            'id' => 1,
            'item_id' => 4,
            'template_name' => "Orchestra Label",
            'template_description' => "Orchestra Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/Wash_Care_Label/WITHOUT_DISNEY_Front.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 3,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 2,
            'item_id' => 4,
            'template_name' => "Orchestra Label",
            'template_description' => "Orchestra Label",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/Wash_Care_Label/WITHOUT_DISNEY_Back.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 3,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 3,
            'item_id' => 5,
            'template_name' => "DISNEY",
            'template_description' => "DISNEY",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/Wash_Care_Label/DISNEY_Front.svg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 3,
        ]);
        \App\OrchestraTemplate::create([
            'id' =>4 ,
            'item_id' => 5,
            'template_name' => "DISNEY",
            'template_description' => "DISNEY",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/Wash_Care_Label/DISNEY_Back.svg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 3,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 5,
            'item_id' => 6,
            'template_name' => "DISNEY BABY",
            'template_description' => "DISNEY BABY",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/Wash_Care_Label/DISNEY_BABY_Front.svg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 3,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 6,
            'item_id' => 6,
            'template_name' => "DISNEY BABY",
            'template_description' => "DISNEY BABY",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/Wash_Care_Label/DISNEY_BABY_Back.svg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 3,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 7,
            'item_id' => 8,
            'template_name' => "MARVEL",
            'template_description' => "MARVEL",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/Wash_Care_Label/MARVEL_Front.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 3,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 8,
            'item_id' => 8,
            'template_name' => "MARVEL",
            'template_description' => "MARVEL",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/Wash_Care_Label/MARVEL_Back.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 3,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 9,
            'item_id' => 9,
            'template_name' => "STARWARS",
            'template_description' => "STARWARS",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/Wash_Care_Label/STARWARS_Front.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 3,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 10,
            'item_id' => 9,
            'template_name' => "STARWARS",
            'template_description' => "STARWARS",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/Wash_Care_Label/STARWARS_Back.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 3,
        ]);
        \App\OrchestraTemplate::create([  //(Static)
            'id' => 11,
            'item_id' => 11, 
            'template_name' => "L01-SATIN LABEL",
            'template_description' => "L01-SATIN LABEL",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/MAIN_&_SIZE_LABEL_SATIN/L01-SATIN_LABEL_Front.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 10,
        ]);
        \App\OrchestraTemplate::create([ //Static
            'id' => 12,
            'item_id' => 11,
            'template_name' => "L01-SATIN LABEL",
            'template_description' => "L01-SATIN LABEL",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/MAIN_&_SIZE_LABEL_SATIN/L01-SATIN_LABEL_Back.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 10,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 13,
            'item_id' =>13 ,
            'template_name' => "L01B POLY COTTON LABEL",
            'template_description' => "L01B POLY COTTON LABEL",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/MAIN_&_SIZE_LABEL_POLY_COTTON/L01B_POLY_COTTON_LABEL_Front.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' =>12 ,
        ]);
        \App\OrchestraTemplate::create([
            'id' =>14 ,
            'item_id' =>13 ,
            'template_name' => "L01B POLY COTTON LABEL",
            'template_description' => "L01B POLY COTTON LABEL",
            'template_file' => "/storage/app/template/Orchestra/Labels/Printed_Labels/MAIN_&_SIZE_LABEL_POLY_COTTON/L01B_POLY_COTTON_LABEL_Back.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' =>12 ,
        ]);
        \App\OrchestraTemplate::create([  
            'id' =>15 ,
            'item_id' =>16 ,
            'template_name' => "L01A WOVEN LABEL",
            'template_description' => "L01A WOVEN LABEL",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/ORC_DEPT_WOVEN_LABEL/L01A_WOVEN_LABEL_Front.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 15,
        ]);
        \App\OrchestraTemplate::create([
            'id' =>16 ,
            'item_id' => 16,
            'template_name' => "L01A WOVEN LABEL",
            'template_description' => "L01A WOVEN LABEL",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/ORC_DEPT_WOVEN_LABEL/L01A_WOVEN_LABEL_Back.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 15,
        ]);
          //WBB1
        \App\OrchestraTemplate::create([
            'id' =>17 ,
            'item_id' => 22,
            'template_name' => "WBB1-Woven DISNEYBABY DISNEY",
            'template_description' => "WBB1-Woven DISNEYBABY DISNEY",
            'template_file' => 
            "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WBB1_WOVEN_DISNEYBABY@DISNEY_M.L.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 18,
        ]);
        \App\OrchestraTemplate::create([
            'id' =>18 ,
            'item_id' => 23,
            'template_name' => "WBB1-Woven DISNEYBABY DISNEY",
            'template_description' => "WBB1-Woven DISNEYBABY DISNEY",
            'template_file' =>
            "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WBB1_WOVEN_DISNEYBABY@DISNEY_S.L.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 18,
        ]);
        \App\OrchestraTemplate::create([
            'id' =>19 ,
            'item_id' => 24,
            'template_name' => "WBB2-Woven DISNEYBABY DISNEY PIXAR",
            'template_description' => "WBB2-Woven DISNEYBABY DISNEY PIXAR",
            'template_file' =>
            "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WBB2 - WOVEN DISNEYBABY@ PIXAR M.L.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 18,
        ]);
        \App\OrchestraTemplate::create([
            'id' =>20 ,
            'item_id' => 25,
            'template_name' => "WBB2-Woven DISNEYBABY DISNEY PIXAR",
            'template_description' =>
            "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WBB2 - WOVEN DISNEYBABY@ PIXAR S.L.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 18,
        ]);
        \App\OrchestraTemplate::create([
            'id' =>21 ,
            'item_id' => 26,
            'template_name' => "WBB3-Woven DISNEYBABY WINNIE",
            'template_description' => "WBB3-Woven DISNEYBABY WINNIE",
            'template_file' => 
             "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WBB3 - WOVEN DISNEYBABY@WINNIE M.L.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 18,
        ]);
        \App\OrchestraTemplate::create([
            'id' =>22 ,
            'item_id' => 27,
            'template_name' => "WBB3-Woven DISNEYBABY WINNIE",
            'template_description' => "WBB3-Woven DISNEYBABY WINNIE",
            'template_file' => 
             "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WBB3 - WOVEN DISNEYBABY@WINNIE S.L.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 18,
        ]);
        \App\OrchestraTemplate::create([
            'id' =>23 ,
            'item_id' => 29,
            'template_name' => "WD1-Woven Disney",
            'template_description' => "WD1-Woven Disney",
            'template_file' =>
             "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WD1-Woven CDisney.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 22,
        ]);
        \App\OrchestraTemplate::create([
            'id' =>24 ,
            'item_id' => 30,
            'template_name' => "WD1-Woven Disney",
            'template_description' => "WD1-Woven Disney",
            'template_file' => 
              "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WD1-SIZE LABEL.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 22,
        ]);
        \App\OrchestraTemplate::create([
            'id' =>25 ,
            'item_id' => 31,
            'template_name' => "WD2-Woven DisneyPixar",
            'template_description' => "WD2-Woven DisneyPixar",
            'template_file' => 
             "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WD2-Woven CDisneypixar.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 22,
        ]);
        \App\OrchestraTemplate::create([
            'id' =>26 ,
            'item_id' => 32,
            'template_name' => "WD2-Woven DisneyPixar",
            'template_description' => "WD2-Woven DisneyPixar",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WD2-SIZE LABEL.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 22,
        ]);
        \App\OrchestraTemplate::create([
            'id' =>27 ,
            'item_id' => 33,
            'template_name' => "WD3-Woven DisneyWinnie",
            'template_description' => "WD3-Woven DisneyWinnie",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WD3-Woven CDisneyWinnie.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 22,
        ]);
        \App\OrchestraTemplate::create([
            'id' =>28 ,
            'item_id' => 34,
            'template_name' => "WD3-Woven DisneyWinnie",
            'template_description' => "WD3-Woven DisneyWinnie",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WD3-SIZE LABEL.png",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 22,
        ]);
        
        \App\OrchestraTemplate::create([
            'id' => 29 ,
            'item_id' => 27,
            'template_name' => "WD1-Woven Disney",
            'template_description' => "WD1-Woven Disney",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/DISNEY_WOVEN_LABEL/GIRL/WD1-Woven_Disney_Front.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 26,
        ]);
        \App\OrchestraTemplate::create([
            'id' =>30 ,
            'item_id' => 27,
            'template_name' => "WD1-Woven Disney",
            'template_description' => "WD1-Woven Disney",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/DISNEY_WOVEN_LABEL/GIRL/WD1-Woven_Disney_Back.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 26,
        ]);
        \App\OrchestraTemplate::create([
            'id' =>31 ,
            'item_id' => 28,
            'template_name' => "WD2-Woven DisneyPixar",
            'template_description' => "WD2-Woven DisneyPixar",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/DISNEY_WOVEN_LABEL/GIRL/WD2-Woven_DisneyPixar_Front.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 26,
        ]);
        \App\OrchestraTemplate::create([
            'id' =>32 ,
            'item_id' => 28,
            'template_name' => "WD2-Woven ©DisneyPixar",
            'template_description' => "WD2-Woven DisneyPixar",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/DISNEY_WOVEN_LABEL/GIRL/WD2-Woven_DisneyPixar_Back.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 26,
        ]);
        \App\OrchestraTemplate::create([
            'id' =>33 ,
            'item_id' => 29,
            'template_name' => "WD3-Woven DisneyWinnie",
            'template_description' => "WD3-Woven DisneyWinnie",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/DISNEY_WOVEN_LABEL/GIRL/WD3-Woven_DisneyWinnie_Front.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 26,
        ]);
        \App\OrchestraTemplate::create([
            'id' =>34 ,
            'item_id' => 29,
            'template_name' => "WD3-Woven DisneyWinnie",
            'template_description' => "WD3-Woven DisneyWinnie",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/DISNEY_WOVEN_LABEL/GIRL/WD3-Woven_DisneyWinnie_Back.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 26,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 35,
            'item_id' => 43,
            'template_name' => "WDM",
            'template_description' => "WDM",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WDM-WovenCMAVEL.png",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 30,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 36,
            'item_id' => 44 ,
            'template_name' => "WDM",
            'template_description' => "WDM",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WDm-SIZE LABEL.png",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 30,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 37,
            'item_id' => 46,
            'template_name' => "WSW1 Main Label",
            'template_description' => "",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WSW1 - STARWARS M.L.png",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 32,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 38,
            'item_id' => 47,
            'template_name' => "WSW1 Size Label",
            'template_description' => "",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WSW1 - STARWARS S.L.png",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 32,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 39,
            'item_id' => 49,
            'template_name' => "WSMIBB-SMILEY BB MAIN AND SIZE LABELS",
            'template_description' => "WSMIBB-SMILEY BB MAIN AND SIZE LABELS",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WSMIBB - SMILEY BB M.L.png",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 34,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 40,
            'item_id' => 51,
            'template_name' => "WSMI-SMILEYWORLD WOVEN LABELS",
            'template_description' => "WSMI-SMILEYWORLD WOVEN LABELS",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WSMI - SMILEY WORLD M.L.png",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 34,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 41,
            'item_id' => 38,
            'template_name' => "WBA-BATMAN WOVEN LABELS",
            'template_description' => "WBA-BATMAN WOVEN LABELS",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN LABELS/WARNER WOVEN LABEL/BATMAN/WBA-BATMAN_WOVEN_LABELS_Front.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 37,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 42,
            'item_id' => 38,
            'template_name' => "WBA-BATMAN WOVEN LABELS",
            'template_description' => "WBA-BATMAN WOVEN LABELS",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN LABELS/WARNER WOVEN LABEL/BATMAN/WBA-BATMAN_WOVEN_LABELS_Back.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 37,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 43,
            'item_id' => 56,
            'template_name' => "WLT-LONEYTUNES WOVEN LABELS",
            'template_description' => "WLT-LONEYTUNES WOVEN LABELS",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WLT-LONEYTUNES M.L.png",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 37,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 44,
            'item_id' => 55,
            'template_name' => "WLT-LONEYTUNES WOVEN LABELS",
            'template_description' => "WLT-LONEYTUNES WOVEN LABELS",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WLT-LONEYTUNES S.L.png",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 37,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 45,
            'item_id' => 58,
            'template_name' => "WSM-SUPERMAN WOVEN LABELS",
            'template_description' => "",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WSM - SUPERMAN M.L.png",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 37,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 46,
            'item_id' => 59,
            'template_name' => "WSM-SUPERMAN WOVEN LABELS",
            'template_description' => "",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN_LABELS/WSM - SUPERMAN S.L.png",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 37,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 47,
            'item_id' => 41,
            'template_name' => "WWV-WONDER WOMAN WOVEN LABELS",
            'template_description' => "WWV-WONDER WOMAN WOVEN LABELS",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN LABELS/WARNER WOVEN LABEL/WONDER WOMEN/WWV-WONDER WOMAN WOVEN LABELS_Front.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 37,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 48,
            'item_id' => 41,
            'template_name' => "WWV-WONDER WOMAN WOVEN LABELS",
            'template_description' => "WWV-WONDER WOMAN WOVEN LABELS",
            'template_file' => "/storage/app/template/Orchestra/Labels/WOVEN LABELS/WARNER WOVEN LABEL/WONDER WOMEN/WWV-WONDER WOMAN WOVEN LABELS_Back.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 37,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 49,
            'item_id' => 44,
            'template_name' => "L09 - Print Label LIGHT COLOR",
            'template_description' => "L09 - Print Label LIGHT COLOR",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/ORC DEPT H.TRANSFER/L09-Print_Label_LIGHT_COLOR_Front.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 43,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 50,
            'item_id' => 44,
            'template_name' => "L09 - Print Label LIGHT COLOR",
            'template_description' => "L09 - Print Label LIGHT COLOR",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/ORC DEPT H.TRANSFER/L09-Print_Label_LIGHT_COLOR_Back.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 43,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 51,
            'item_id' => 47,
            'template_name' => "PBB1-H.TRANSFER DISNEY BABY DISNEY",
            'template_description' => "PBB1-H.TRANSFER DISNEY BABY DISNEY",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/DISNEY H.TRANSFER/BABY/PBB2-H.TRANSFER_DISNEY_BABY_DISNEY_PIXAR_Front.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 46,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 52,
            'item_id' => 47,
            'template_name' => "PBB1-H.TRANSFER DISNEY BABY DISNEY",
            'template_description' => "PBB1-H.TRANSFER DISNEY BABY DISNEY",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/DISNEY H.TRANSFER/BABY/PBB2-H.TRANSFER_DISNEY_BABY_DISNEY_PIXAR_Back.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 46,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 53,
            'item_id' => 48,
            'template_name' => "PBB2-H.TRANSFER DISNEY BABY DISNEY PIXAR",
            'template_description' => "PBB2-H.TRANSFER DISNEY BABY DISNEY PIXAR",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/DISNEY H.TRANSFER/BABY/PBB3-H.TRANSFER_DISNEY_BABY_DISNEY_WINNIE_Front.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 46,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 54,
            'item_id' => 48,
            'template_name' => "PBB2-H.TRANSFER DISNEY BABY DISNEY PIXAR",
            'template_description' => "PBB2-H.TRANSFER DISNEY BABY DISNEY PIXAR",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/DISNEY H.TRANSFER/BABY/PBB3-H.TRANSFER_DISNEY_BABY_DISNEY_WINNIE_Back.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 46,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 55,
            'item_id' => 49,
            'template_name' => "PBB3-H.TRANSFER DISNEY BABY DISNEY WINNIE",
            'template_description' => "PBB3-H.TRANSFER DISNEY BABY DISNEY WINNIE",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/DISNEY H.TRANSFER/BABY/PBB3-H.TRANSFER_DISNEY_BABY_DISNEY_WINNIE_Front.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 46,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 56,
            'item_id' => 49,
            'template_name' => "PBB3-H.TRANSFER DISNEY BABY DISNEY WINNIE",
            'template_description' => "PBB3-H.TRANSFER DISNEY BABY DISNEY WINNIE",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/DISNEY H.TRANSFER/BABY/PBB3-H.TRANSFER_DISNEY_BABY_DISNEY_WINNIE_Back.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 46,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 57,
            'item_id' => 51,
            'template_name' => "PD1-H.TRANSFER DISNEY DISNEY",
            'template_description' => "PD1-H.TRANSFER DISNEY DISNEY",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/DISNEY H.TRANSFER/BOY/PD1-H.TRANSFER_DISNEY_DISNEY_Front.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 50,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 58,
            'item_id' => 51,
            'template_name' => "PD1-H.TRANSFER DISNEY DISNEY",
            'template_description' => "PD1-H.TRANSFER DISNEY DISNEY",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/DISNEY H.TRANSFER/BOY/PD1-H.TRANSFER_DISNEY_DISNEY_Back.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 50,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 59,
            'item_id' => 52,
            'template_name' => "PD2-H.TRANSFER DISNEY_DISNEY PIXAR",
            'template_description' => "PD2-H.TRANSFER DISNEY_DISNEY PIXAR",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/DISNEY H.TRANSFER/BOY/PD2-H.TRANSFER_DISNEY_DISNEY_PIXAR_Front.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 50,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 60,
            'item_id' => 52,
            'template_name' => "PD2-H.TRANSFER DISNEY_DISNEY PIXAR",
            'template_description' => "PD2-H.TRANSFER DISNEY_DISNEY PIXAR",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/DISNEY H.TRANSFER/BOY/PD2-H.TRANSFER_DISNEY_DISNEY_PIXAR_Back.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 50,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 61,
            'item_id' => 53,
            'template_name' => "PD3-H.TRANSFER DISNEY DISNEY WINNIE",
            'template_description' => "PD3-H.TRANSFER DISNEY DISNEY WINNIE",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/DISNEY H.TRANSFER/BOY/PD3-H.TRANSFER_DISNEY_DISNEY_WINNIE_Front.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 50,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 62,
            'item_id' => 53,
            'template_name' => "PD3-H.TRANSFER DISNEY DISNEY WINNIE",
            'template_description' => "PD3-H.TRANSFER DISNEY DISNEY WINNIE",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/DISNEY H.TRANSFER/BOY/PD3-H.TRANSFER_DISNEY_DISNEY_WINNIE_Back.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 50,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 63,
            'item_id' => 55,
            'template_name' => "PD1-H.TRANSFER DISNEY DISNEY",
            'template_description' => "PD1-H.TRANSFER DISNEY DISNEY",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/DISNEY H.TRANSFER/GIRL/PD1-H.TRANSFER_DISNEY_DISNEY_Front.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 54,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 64,
            'item_id' => 55,
            'template_name' => "PD1-H.TRANSFER DISNEY DISNEY",
            'template_description' => "PD1-H.TRANSFER DISNEY DISNEY",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/DISNEY H.TRANSFER/GIRL/PD1-H.TRANSFER_DISNEY_DISNEY_Back.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 54,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 65,
            'item_id' => 56,
            'template_name' => "PD2-H.TRANSFER DISNEY_DISNEY PIXAR",
            'template_description' => "PD2-H.TRANSFER DISNEY_DISNEY PIXAR",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/DISNEY H.TRANSFER/GIRL/PD2-H.TRANSFER_DISNEY_DISNEY_PIXAR_Front.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 54,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 66,
            'item_id' => 56,
            'template_name' => "PD2-H.TRANSFER DISNEY_DISNEY PIXAR",
            'template_description' => "PD2-H.TRANSFER DISNEY_DISNEY PIXAR",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/DISNEY H.TRANSFER/GIRL/PD2-H.TRANSFER_DISNEY_DISNEY_PIXAR_Back.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 54,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 67,
            'item_id' => 57,
            'template_name' => "PD3-H.TRANSFER DISNEY DISNEY WINNIE",
            'template_description' => "PD3-H.TRANSFER DISNEY DISNEY WINNIE",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/DISNEY H.TRANSFER/BOY/PD3-H.TRANSFER_DISNEY_DISNEY_WINNIE_Front.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 54,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 68,
            'item_id' => 57,
            'template_name' => "PD3-H.TRANSFER DISNEY DISNEY WINNIE",
            'template_description' => "PD3-H.TRANSFER DISNEY DISNEY WINNIE",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/DISNEY H.TRANSFER/BOY/PD3-H.TRANSFER_DISNEY_DISNEY_WINNIE_Back.jpg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 54,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 69,
            'item_id' => 59,
            'template_name' => "PM1-H.TRANSFER_MARVEL",
            'template_description' => "PM1-H.TRANSFER_MARVEL",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/MARVEL H.TRANSFER/PM1-H.TRANSFER_MARVEL_Front.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 58,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 70,
            'item_id' => 59,
            'template_name' => "PM1-H.TRANSFER_MARVEL",
            'template_description' => "PM1-H.TRANSFER_MARVEL",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/MARVEL H.TRANSFER/PM1-H.TRANSFER_MARVEL_Back.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 58,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 71,
            'item_id' => 61,
            'template_name' => "PSW1-H.TRANSFER STARWARS LUCASFILM",
            'template_description' => "PSW1-H.TRANSFER STARWARS LUCASFILM",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/STARWARS H.TRANSFER/PSW1-H.TRANSFER_STARWARS_LUCASFILM_Front.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 60,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 72,
            'item_id' => 61,
            'template_name' => "PSW1-H.TRANSFER STARWARS LUCASFILM",
            'template_description' => "PSW1-H.TRANSFER STARWARS LUCASFILM",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/STARWARS H.TRANSFER/PSW1-H.TRANSFER_STARWARS_LUCASFILM_Back.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 60,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 73,
            'item_id' => 63,
            'template_name' => "PSMIBB-SMILEY BABY",
            'template_description' => "PSMIBB-SMILEY BABY",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/SMILEY H.TRANSFER/SMILEY BABY/PSMIBB-SMILEY_BABY_Front.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 62,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 74,
            'item_id' => 63,
            'template_name' => "PSMIBB-SMILEY BABY",
            'template_description' => "PSMIBB-SMILEY BABY",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/SMILEY H.TRANSFER/SMILEY BABY/PSMIBB-SMILEY_BABY_Back.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 75,
            'item_id' => 64,
            'template_name' => "PSMI-SMILEY WORLD",
            'template_description' => "PSMI-SMILEY WORLD",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/SMILEY H.TRANSFER/SMILEY WORLD/PSMI-SMILEY_WORLD_Front.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 62,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 76,
            'item_id' => 64,
            'template_name' => "PSMI-SMILEY WORLD",
            'template_description' => "PSMI-SMILEY WORLD",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/SMILEY H.TRANSFER/SMILEY WORLD/PSMI-SMILEY_WORLD_Back.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 62,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 77,
            'item_id' => 66,
            'template_name' => "PBA-BATMAN",
            'template_description' => "PBA-BATMAN",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/WANER H.TRANSFER/BATMAN/PBA-BATMAN_Front.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 65,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 78,
            'item_id' => 66,
            'template_name' => "PBA-BATMAN",
            'template_description' => "PBA-BATMAN",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/WANER H.TRANSFER/BATMAN/PBA-BATMAN_Back.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 65,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 79,
            'item_id' => 67,
            'template_name' => "PLT-LOONEY TUNES",
            'template_description' => "PLT-LOONEY TUNES",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/WANER H.TRANSFER/LOONEY TUNES/PLT-LOONEY_TUNES_Front.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 65,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 80,
            'item_id' => 67,
            'template_name' => "PLT-LOONEY TUNES",
            'template_description' => "PLT-LOONEY TUNES",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/WANER H.TRANSFER/LOONEY TUNES/PLT-LOONEY_TUNES_Back.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 65,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 81,
            'item_id' => 68,
            'template_name' => "PSM-SUPERMAN",
            'template_description' => "PSM-SUPERMAN",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/WANER H.TRANSFER/SUPERMAN/PSM-SUPERMAN_Front.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 65,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 82,
            'item_id' => 68,
            'template_name' => "PSM-SUPERMAN",
            'template_description' => "PSM-SUPERMAN",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/WANER H.TRANSFER/SUPERMAN/PSM-SUPERMAN_Back.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 65,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 83,
            'item_id' => 69,
            'template_name' => "WWP-WONDER WOMAN PRINT LABEL",
            'template_description' => "WWP-WONDER WOMAN PRINT LABEL",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/WANER H.TRANSFER/WONDER WOMEN/WWP-WONDER_WOMAN_PRINT_LABEL_Front.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 65,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 84,
            'item_id' => 69,
            'template_name' => "WWP-WONDER WOMAN PRINT LABEL",
            'template_description' => "WWP-WONDER WOMAN PRINT LABEL",
            'template_file' => "/storage/app/template/Orchestra/Labels/HEAT TRANSFER/WANER H.TRANSFER/WONDER WOMEN/WWP-WONDER_WOMAN_PRINT_LABEL_Back.jpg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 65,
        ]);
        \App\OrchestraTemplate::create([
            'id' => 85,
            'item_id' => 83,
            'template_name' => "T01A",
            'template_description' => "T01A",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T01A-01.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 82,
            ]);
            
            \App\OrchestraTemplate::create([
            'id' => 86,
            'item_id' => 83,
            'template_name' => "T01A",
            'template_description' => "T01A",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T01A-02.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 82,
            ]);
            
            
            \App\OrchestraTemplate::create([
            'id' => 87,
            'item_id' => 84,
            'template_name' => "T01B",
            'template_description' => "T01B",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T01B-01.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 82,
            ]);
            
            \App\OrchestraTemplate::create([
            'id' => 88,
            'item_id' => 84,
            'template_name' => "T01B",
            'template_description' => "T01B",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T01B-02.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 82,
            ]);
            
            
            \App\OrchestraTemplate::create([
            'id' => 89,
            'item_id' => 85,
            'template_name' => "T01C",
            'template_description' => "T01C",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T01C-01.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 82,
            ]);
            
            \App\OrchestraTemplate::create([
            'id' => 90,
            'item_id' => 85,
            'template_name' => "T01C",
            'template_description' => "T01C",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T01C-02.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 82,
            ]);
            
            
            \App\OrchestraTemplate::create([
            'id' => 91,
            'item_id' => 86,
            'template_name' => "T01D",
            'template_description' => "T01D",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T01D-01.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 82,
            ]);
            
            \App\OrchestraTemplate::create([
            'id' => 92,
            'item_id' => 86,
            'template_name' => "T01D",
            'template_description' => "T01D",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T01D-02.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 82,
            ]);
            
            
            
            \App\OrchestraTemplate::create([
            'id' => 93,
            'item_id' => 89,
            'template_name' => "T02A",
            'template_description' => "T02A",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T02A-01.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 88,
            ]);
            
            \App\OrchestraTemplate::create([
            'id' => 94,
            'item_id' => 89,
            'template_name' => "T02A",
            'template_description' => "T02A",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T02A-02.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 88,
            ]);
            
            \App\OrchestraTemplate::create([
            'id' => 95,
            'item_id' => 91,
            'template_name' => "T02C",
            'template_description' => "T02C",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T02C-01.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 88,
            ]);
            
            \App\OrchestraTemplate::create([
            'id' => 96,
            'item_id' => 91,
            'template_name' => "T02C",
            'template_description' => "T02C",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T02C-02.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 88,
            ]);
            
            
            
            \App\OrchestraTemplate::create([
            'id' => 97,
            'item_id' => 95,
            'template_name' => "T05A",
            'template_description' => "T05A",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T05A-01.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 89,
            ]);
            
            \App\OrchestraTemplate::create([
            'id' => 98,
            'item_id' => 95,
            'template_name' => "T05A",
            'template_description' => "T05A",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T05A-02.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 89,
            ]);
            
            \App\OrchestraTemplate::create([
            'id' => 99,
            'item_id' => 97,
            'template_name' => "T05C",
            'template_description' => "T05C",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T05C-01.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 89,
            ]);
            
            \App\OrchestraTemplate::create([
            'id' => 100,
            'item_id' => 97,
            'template_name' => "T05C",
            'template_description' => "T05C",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T05C-02.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 89,
            ]);
            
            \App\OrchestraTemplate::create([
            'id' => 101,
            'item_id' => 98,
            'template_name' => "T05D",
            'template_description' => "T05D",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T05D-01.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 89,
            ]);
            
            \App\OrchestraTemplate::create([
            'id' => 102,
            'item_id' => 98,
            'template_name' => "T05D",
            'template_description' => "T05D",
            'template_file' => "/storage/app/template/Orchestra/Tags/ORC_DEPARTMENT_TAG/T05D-02.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 89,
            ]);
            
            
            \App\OrchestraTemplate::create([
            'id' => 103,
            'item_id' => 171,
            'template_name' => "HM1",
            'template_description' => "HM1",
            'template_file' => "/storage/app/template/Orchestra/Tags/MATERNITY/HM1-01.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 167,
            ]);
            
            \App\OrchestraTemplate::create([
            'id' => 104,
            'item_id' => 171,
            'template_name' => "HM1",
            'template_description' => "HM1",
            'template_file' => "/storage/app/template/Orchestra/Tags/MATERNITY/HM1-02.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 167,
            ]);
            
            
            \App\OrchestraTemplate::create([
            'id' => 107,
            'item_id' => 147,
            'template_name' => "DM1",
            'template_description' => "DM1",
            'template_file' => "/storage/app/template/Orchestra/Tags/Marvel/front.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 146,
            ]);
            
            \App\OrchestraTemplate::create([
            'id' => 108,
            'item_id' => 147,
            'template_name' => "DM1",
            'template_description' => "DM1",
            'template_file' => "/storage/app/template/Orchestra/Tags/Marvel/back.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 146,
            ]);
            
            
            
            \App\OrchestraTemplate::create([
            'id' => 109,
            'item_id' => 136,
            'template_name' => "COD1",
            'template_description' => "COD1",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD1/front.svg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 135,
            ]);
            
            \App\OrchestraTemplate::create([
            'id' => 110,
            'item_id' => 136,
            'template_name' => "COD1",
            'template_description' => "COD1",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD1/back.svg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 135,
            ]);
            
            
            \App\OrchestraTemplate::create([
            'id' => 111,
            'item_id' => 137,
            'template_name' => "COD2",
            'template_description' => "COD2",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/front.svg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 135,
            ]);
            
            \App\OrchestraTemplate::create([
            'id' => 112,
            'item_id' => 137,
            'template_name' => "COD2",
            'template_description' => "COD2",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/back.svg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 135,
            ]);
            
            
            \App\OrchestraTemplate::create([
            'id' => 113,
            'item_id' => 141,
            'template_name' => "COD1",
            'template_description' => "COD1",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD1/front_girl.svg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 140,
            ]);
            
            \App\OrchestraTemplate::create([
            'id' => 114,
            'item_id' => 141,
            'template_name' => "COD1",
            'template_description' => "COD1",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD1/back_girl.svg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 140,
            ]);
            
            
            \App\OrchestraTemplate::create([
            'id' => 115,
            'item_id' => 142,
            'template_name' => "COD2",
            'template_description' => "COD2",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/front_girl.svg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 140,
            ]);
            
            \App\OrchestraTemplate::create([
            'id' => 116,
            'item_id' => 142,
            'template_name' => "COD2",
            'template_description' => "COD2",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/COD2/back_girl.svg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 140,
            ]);
            
            
            \App\OrchestraTemplate::create([
            'id' => 117,
            'item_id' => 132,
            'template_name' => "DBB1",
            'template_description' => "DBB1",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/DBB1/front.svg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 131,
            ]);
            
            \App\OrchestraTemplate::create([
            'id' => 118,
            'item_id' => 132,
            'template_name' => "DBB1",
            'template_description' => "DBB1",
            'template_file' => "/storage/app/template/Orchestra/Tags/DISNESY/DBB1/back.svg",
            'disney' => 1,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 131,
            ]);
            
            
            //Stickers 
            
            \App\OrchestraTemplate::create([
            'id' => 119,
            'item_id' => 72,
            'template_name' => "S03 SIZE STK",
            'template_description' => "S03 SIZE STK",
            'template_file' => "/storage/app/template/Orchestra/Stickers/Size_Sticker/S03_SIZE_STK_Front.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 71,
            ]);
            
            \App\OrchestraTemplate::create([
            'id' => 120,
            'item_id' => 72,
            'template_name' => "S03 SIZE STK",
            'template_description' => "S03 SIZE STK",
            'template_file' => "/storage/app/template/Orchestra/Stickers/Size_Sticker/S03_SIZE_STK_Back.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 71,
            ]);
            
            
            
            \App\OrchestraTemplate::create([
            'id' => 121,
            'item_id' => 73,
            'template_name' => "S06 AGE STICKER",
            'template_description' => "S06 AGE STICKER",
            'template_file' => "/storage/app/template/Orchestra/Stickers/Size_Sticker/S06_AGE_STICKER_Front.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 1,
            'hierarchy_id' => 71,
            ]);
            
            \App\OrchestraTemplate::create([
            'id' => 122,
            'item_id' => 73,
            'template_name' => "S06 AGE STICKER",
            'template_description' => "S06 AGE STICKER",
            'template_file' => "/storage/app/template/Orchestra/Stickers/Size_Sticker/S06_AGE_STICKER_Back.svg",
            'disney' => 0,
            'tax' => 0,
            'flag' => 0,
            'hierarchy_id' => 71,
            ]);

            \App\OrchestraTemplate::create([
                'id' => 123,
                'item_id' => 75,
                'template_name' => "S01 SIZE STRP STK-ANS",
                'template_description' => "S01 SIZE STRP STK-ANS",
                'template_file' => "/storage/app/template/Orchestra/Stickers/GARMENT SIZE STRIP/S01_SIZE_STRP_STK-ANS_Front.svg",
                'disney' => 0,
                'tax' => 0,
                'flag' => 1,
                'hierarchy_id' => 74,
                ]);
                
                \App\OrchestraTemplate::create([
                    'id' => 124,
                    'item_id' => 75,
                    'template_name' => "S01 SIZE STRP STK-ANS",
                    'template_description' => "S01 SIZE STRP STK-ANS",
                    'template_file' => "/storage/app/template/Orchestra/Stickers/GARMENT SIZE STRIP/S01_SIZE_STRP_STK-ANS_Back.svg",
                    'disney' => 0,
                    'tax' => 0,
                    'flag' => 0,
                    'hierarchy_id' => 74,
                ]);
                \App\OrchestraTemplate::create([
                    'id' => 126,
                    'item_id' => 77,
                    'template_name' => "S05 Sticker best price collant",
                    'template_description' => "S05 Sticker best price collant",
                    'template_file' => "/storage/app/template/Orchestra/Stickers/BEST PRICE/Sticker_best_price_collant_Front.svg",
                    'disney' => 0,
                    'tax' => 0,
                    'flag' => 1,
                    'hierarchy_id' => 76,
                    ]);
                    
                    \App\OrchestraTemplate::create([
                        'id' => 127,
                        'item_id' => 77,
                        'template_name' => "05 Sticker best price collant",
                        'template_description' => "05 Sticker best price collant",
                        'template_file' => "/storage/app/template/Orchestra/Stickers/BEST PRICE/Sticker_best_price_collant_Back.svg",
                        'disney' => 0,
                        'tax' => 0,
                        'flag' => 0,
                        'hierarchy_id' => 76,
                    ]);
		
		 \App\OrchestraTemplate::create([
                        'id' => 128,
                        'item_id' => 177,
                        'template_name' => "Solid Carton Stickers",
                        'template_description' => "Solid Carton Stickers",
                        'template_file' => "/storage/app/template/Orchestra/Stickers/solid_carton_sticker.svg",
                        'disney' => 0,
                        'tax' => 0,
                        'flag' => 0,
                        'hierarchy_id' => 176,
                    ]);
		
		 \App\OrchestraTemplate::create([
                        'id' => 129,
                        'item_id' => 178,
                        'template_name' => "Solid Poly Bag Stickers",
                        'template_description' => "Solid Poly Bag Stickers",
                        'template_file' => "/storage/app/template/Orchestra/Stickers/solidpolysticker.svg",
                        'disney' => 0,
                        'tax' => 0,
                        'flag' => 0,
                        'hierarchy_id' => 176,
                    ]);
		
		 \App\OrchestraTemplate::create([
                        'id' => 130,
                        'item_id' => 179,
                        'template_name' => "Standard Carton Stickers",
                        'template_description' => "Standard Carton Stickers",
                        'template_file' => "/storage/app/template/Orchestra/Stickers/std-carton-sticker.svg",
                        'disney' => 0,
                        'tax' => 0,
                        'flag' => 0,
                        'hierarchy_id' => 176,
                    ]);
		
		 \App\OrchestraTemplate::create([
                        'id' => 131,
                        'item_id' => 180,
                        'template_name' => "Standard Poly Bag Stickers",
                        'template_description' => "Standard Poly Bag Stickers",
                        'template_file' => "/storage/app/template/Orchestra/Stickers/std-polybag-sticker.svg",
                        'disney' => 0,
                        'tax' => 0,
                        'flag' => 0,
                        'hierarchy_id' => 176,
                    ]);

    }
}
