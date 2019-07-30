<?php

use Illuminate\Database\Seeder;

class OrchestraItemMasterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
            \App\OrchestraItemMaster::create([
                    'id'  =>1,
                    'company_id' => 1,
                    'parent_id' => 0,   // id : 1
                    'item_name' => "Labels",
                    'ref_no' => "",
                    'tag_name' => "",
                ]);

        
            \App\OrchestraItemMaster::create([
                    'id'  =>2,
                    'company_id' => 1,
                    'parent_id' => 1,   //id : 2
                    'item_name' => "Printed Labels",
                    'ref_no' => "",
                    'tag_name' => "",
                ]);

        
            \App\OrchestraItemMaster::create([
                    'id'  =>3,
                    'company_id' => 1,
                    'parent_id' => 2,       // id : 3
                    'item_name' => "Wash Care Label",
                    'ref_no' => "",
                    'tag_name' => "",
                ]);

        
            \App\OrchestraItemMaster::create([
                    'id'  =>4,
                    'company_id' => 1,
                    'parent_id' => 3,       //id : 4
                    'item_name' => "Orchestra Label",
                    'ref_no' => "",
                    'tag_name' => "",
                ]);

        
            \App\OrchestraItemMaster::create([
                    'id'  =>5,
                    'company_id' => 1,
                    'parent_id' => 3,       //id : 5
                    'item_name' => "Disney",
                    'ref_no' => "",
                    'tag_name' => "",
                ]);

        
            \App\OrchestraItemMaster::create([
                    'id'  =>6,
                    'company_id' => 1,
                    'parent_id' => 3,       //id : 6
                    'item_name' => "Disney Baby",
                    'ref_no' => "",
                    'tag_name' => "",
                ]);

        
            \App\OrchestraItemMaster::create([
                    'id'  =>7,
                    'company_id' => 1,
                    'parent_id' => 3,       //id : 7
                    'item_name' => "Disney Cars",
                    'ref_no' => "",
                    'tag_name' => "",
                ]);

        
            \App\OrchestraItemMaster::create([
                    'id'  =>8,
                    'company_id' => 1,
                    'parent_id' => 3,       //id : 8
                    'item_name' => "Marvel",
                    'ref_no' => "",
                    'tag_name' => "",
                ]);
        
        
            \App\OrchestraItemMaster::create([
                    'id'  =>9,
                    'company_id' => 1,
                    'parent_id' => 3,       //id : 9
                    'item_name' => "Star Wars",
                    'ref_no' => "",
                    'tag_name' => "",
                ]);

        
            \App\OrchestraItemMaster::create([
                    'id'  =>10,
                    'company_id' => 1,
                    'parent_id' => 2,       //id : 10
                    'item_name' => "Main & Size Label Satin",
                    'ref_no' => "",
                    'tag_name' => "",
                ]);

        
            \App\OrchestraItemMaster::create([
                    'id'  =>11,
                    'company_id' => 1,
                    'parent_id' => 10,       //id : 11
                    'item_name' => "L01",
                    'ref_no' => "",
                    'tag_name' => "",
                ]);

        
            \App\OrchestraItemMaster::create([
                    'id'  =>12,
                    'company_id' => 1,
                    'parent_id' => 2,       //id : 12
                    'item_name' => "Main & Size Label Polycotton",
                    'ref_no' => "",
                    'tag_name' => "",
                ]);

        
            \App\OrchestraItemMaster::create([
                    'id'  =>13,
                    'company_id' => 1,
                    'parent_id' => 12,       //id : 13
                    'item_name' => "L01B",
                    'ref_no' => "",
                    'tag_name' => "",
                ]);

                \App\OrchestraItemMaster::create([
                        'id'  =>14,
                        'company_id' => 1,
                        'parent_id' => 1,    
                        'item_name' => "Woven Labels",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);

                \App\OrchestraItemMaster::create([
                        'id'  =>15,
                        'company_id' => 1,
                        'parent_id' => 14,    
                        'item_name' => "Orchestra Department Label",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                \App\OrchestraItemMaster::create([
                        'id'  =>16 ,
                        'company_id' => 1,
                        'parent_id' => 15,    
                        'item_name' => "L01A",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  =>17,
                        'company_id' => 1,
                        'parent_id' => 14,    
                        'item_name' => "Disney Label",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  =>18,
                        'company_id' => 1,
                        'parent_id' => 17,    
                        'item_name' => "Baby",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  =>19,
                        'company_id' => 1,
                        'parent_id' => 18,    
                        'item_name' => "WBB1",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  =>20,
                        'company_id' => 1,
                        'parent_id' => 18,    
                        'item_name' => "WBB2",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  =>21,
                        'company_id' => 1,
                        'parent_id' => 18,    
                        'item_name' => "WBB3",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  =>22,
                        'company_id' => 1,
                        'parent_id' => 17,    
                        'item_name' => "Boy",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  =>23,
                        'company_id' => 1,
                        'parent_id' => 22,    
                        'item_name' => "WD1",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  =>24,
                        'company_id' => 1,
                        'parent_id' => 22,    
                        'item_name' => "WD2",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  =>25,
                        'company_id' => 1,
                        'parent_id' => 22,    
                        'item_name' => "WD3",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  =>26,
                        'company_id' => 1,
                        'parent_id' => 17,    
                        'item_name' => "Girl",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  =>27,
                        'company_id' => 1,
                        'parent_id' => 26,    
                        'item_name' => "WD1",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  =>28,
                        'company_id' => 1,
                        'parent_id' => 26,    
                        'item_name' => "WD2",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  =>29,
                        'company_id' => 1,
                        'parent_id' => 26,    
                        'item_name' => "WD3",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  =>30,
                        'company_id' => 1,
                        'parent_id' => 14,    
                        'item_name' => "Marvel",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  =>31,
                        'company_id' => 1,
                        'parent_id' => 30,    
                        'item_name' => "WDM",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  =>32,
                        'company_id' => 1,
                        'parent_id' => 14,    
                        'item_name' => "Star Wars",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  =>33,
                        'company_id' => 1,
                        'parent_id' => 32,    
                        'item_name' => "WSW1",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  =>34,
                        'company_id' => 1,
                        'parent_id' => 14,    
                        'item_name' => "Smiley",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  =>35,
                        'company_id' => 1,
                        'parent_id' => 34,    
                        'item_name' => "WSM1BB",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  =>36,
                        'company_id' => 1,
                        'parent_id' => 34,    
                        'item_name' => "WSM1",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  =>37,
                        'company_id' => 1,
                        'parent_id' => 14,    
                        'item_name' => "Warner",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  =>38,
                        'company_id' => 1,
                        'parent_id' => 37,    
                        'item_name' => "Batman - WBA",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  =>39,
                        'company_id' => 1,
                        'parent_id' => 37,    
                        'item_name' => "LooneyTunes - WLT",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  =>40,
                        'company_id' => 1,
                        'parent_id' => 37,    
                        'item_name' => "Superman - WSM",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  =>41,
                        'company_id' => 1,
                        'parent_id' => 37,    
                        'item_name' => "Wonder women - WWV",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 42,
                        'company_id' => 1,
                        'parent_id' => 1,    
                        'item_name' => "Heat Tranfers",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 43,
                        'company_id' => 1,
                        'parent_id' => 42 ,    
                        'item_name' => "Orchestra Department HT",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 44,
                        'company_id' => 1,
                        'parent_id' => 43,    
                        'item_name' => "L09",
                        'ref_no' => "",
                        'tag_name' => "heat_transfer",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 45,
                        'company_id' => 1,
                        'parent_id' => 42,    
                        'item_name' => "Disney HT",
                        'ref_no' => "",
                        'tag_name' => "heat_transfer",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 46,
                        'company_id' => 1,
                        'parent_id' => 45,    
                        'item_name' => "Baby",
                        'ref_no' => "",
                        'tag_name' => "heat_transfer",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 47,
                        'company_id' => 1,
                        'parent_id' => 46,    
                        'item_name' => "PBB1",
                        'ref_no' => "",
                        'tag_name' => "heat_transfer",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 48,
                        'company_id' => 1,
                        'parent_id' => 46 ,    
                        'item_name' => "PBB2",
                        'ref_no' => "",
                        'tag_name' => "heat_transfer",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 49,
                        'company_id' => 1,
                        'parent_id' => 46,    
                        'item_name' => "PBB3",
                        'ref_no' => "",
                        'tag_name' => "heat_transfer",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 50,
                        'company_id' => 1,
                        'parent_id' => 45,    
                        'item_name' => "Boy",
                        'ref_no' => "",
                        'tag_name' => "heat_transfer",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 51,
                        'company_id' => 1,
                        'parent_id' => 50,    
                        'item_name' => "PD1",
                        'ref_no' => "",
                        'tag_name' => "heat_transfer",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 52,
                        'company_id' => 1,
                        'parent_id' => 50,    
                        'item_name' => "PD2",
                        'ref_no' => "",
                        'tag_name' => "heat_transfer",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 53,
                        'company_id' => 1,
                        'parent_id' => 50,    
                        'item_name' => "PD3",
                        'ref_no' => "",
                        'tag_name' => "heat_transfer",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 54,
                        'company_id' => 1,
                        'parent_id' => 45,    
                        'item_name' => "Girl",
                        'ref_no' => "",
                        'tag_name' => "heat_transfer",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 55,
                        'company_id' => 1,
                        'parent_id' => 54,    
                        'item_name' => "PD1",
                        'ref_no' => "",
                        'tag_name' => "heat_transfer",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 56,
                        'company_id' => 1,
                        'parent_id' => 54,    
                        'item_name' => "PD2",
                        'ref_no' => "",
                        'tag_name' => "heat_transfer",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 57,
                        'company_id' => 1,
                        'parent_id' => 54,    
                        'item_name' => "PD3",
                        'ref_no' => "",
                        'tag_name' => "heat_transfer",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 58,
                        'company_id' => 1,
                        'parent_id' => 42,    
                        'item_name' => "Marvel",
                        'ref_no' => "",
                        'tag_name' => "heat_transfer",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 59,
                        'company_id' => 1,
                        'parent_id' => 58,    
                        'item_name' => "PM1",
                        'ref_no' => "",
                        'tag_name' => "heat_transfer",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 60,
                        'company_id' => 1,
                        'parent_id' => 42,    
                        'item_name' => "Star Wars",
                        'ref_no' => "",
                        'tag_name' => "heat_transfer",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 61,
                        'company_id' => 1,
                        'parent_id' => 60,    
                        'item_name' => "PSW1",
                        'ref_no' => "",
                        'tag_name' => "heat_transfer",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 62,
                        'company_id' => 1,
                        'parent_id' => 42,    
                        'item_name' => "Smiley",
                        'ref_no' => "",
                        'tag_name' => "heat_transfer",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 63,
                        'company_id' => 1,
                        'parent_id' => 62,    
                        'item_name' => "PSMIBB",
                        'ref_no' => "",
                        'tag_name' => "heat_transfer",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 64,
                        'company_id' => 1,
                        'parent_id' => 62,    
                        'item_name' => "PSMI",
                        'ref_no' => "",
                        'tag_name' => "heat_transfer",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 65,
                        'company_id' => 1,
                        'parent_id' => 42,    
                        'item_name' => "Warner",
                        'ref_no' => "",
                        'tag_name' => "heat_transfer",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 66,
                        'company_id' => 1,
                        'parent_id' => 65,    
                        'item_name' => "Batman – PBA",
                        'ref_no' => "",
                        'tag_name' => "heat_transfer",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 67,
                        'company_id' => 1,
                        'parent_id' => 65,    
                        'item_name' => "LooneyTunes – PLT",
                        'ref_no' => "",
                        'tag_name' => "heat_transfer",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 68,
                        'company_id' => 1,
                        'parent_id' => 65,    
                        'item_name' => "Superman – PSM",
                        'ref_no' => "",
                        'tag_name' => "heat_transfer",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 69,
                        'company_id' => 1,
                        'parent_id' => 65,    
                        'item_name' => "Wonder woman - WWP",
                        'ref_no' => "",
                        'tag_name' => "heat_transfer",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 70,
                        'company_id' => 1,
                        'parent_id' => 0,    
                        'item_name' => "Stickers",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 71,
                        'company_id' => 1,
                        'parent_id' => 70,    
                        'item_name' => "Size Stickers",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 72,
                        'company_id' => 1,
                        'parent_id' => 71,    
                        'item_name' => "S03",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 73,
                        'company_id' => 1,
                        'parent_id' => 71,    
                        'item_name' => "S06",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 74,
                        'company_id' => 1,
                        'parent_id' => 70,    
                        'item_name' => "Garment Size Strip",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 75,
                        'company_id' => 1,
                        'parent_id' => 74,    
                        'item_name' => "S01",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 76,
                        'company_id' => 1,
                        'parent_id' => 70,    
                        'item_name' => "Best Price",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 77,
                        'company_id' => 1,
                        'parent_id' => 76,    
                        'item_name' => "S05",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 78,
                        'company_id' => 2,
                        'parent_id' => 0,    
                        'item_name' => "Printed Boards",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 79,
                        'company_id' => 2,
                        'parent_id' => 78,    
                        'item_name' => "Orchestra",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 80,
                        'company_id' => 2,
                        'parent_id' => 79,    
                        'item_name' => "Tags",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 81,
                        'company_id' => 2,
                        'parent_id' => 80,    
                        'item_name' => "Department Hang Tag",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 82,
                        'company_id' => 2,
                        'parent_id' => 81,    
                        'item_name' => "Girl",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 83,
                        'company_id' => 2,
                        'parent_id' => 82,    
                        'item_name' => "T01A",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 84,
                        'company_id' => 2,
                        'parent_id' => 82,    
                        'item_name' => "T01B",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 85,
                        'company_id' => 2,
                        'parent_id' => 82,    
                        'item_name' => "T01C",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 86,
                        'company_id' => 2,
                        'parent_id' => 82,    
                        'item_name' => "T01D",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 87,
                        'company_id' => 2,
                        'parent_id' => 82,    
                        'item_name' => "T01E",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 88,
                        'company_id' => 2,
                        'parent_id' => 81,    
                        'item_name' => "Boy",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 89,
                        'company_id' => 2,
                        'parent_id' => 88,    
                        'item_name' => "T02A",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 90,
                        'company_id' => 2,
                        'parent_id' => 88,    
                        'item_name' => "T02B",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 91,
                        'company_id' => 2,
                        'parent_id' => 88,    
                        'item_name' => "T02C",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 92,
                        'company_id' => 2,
                        'parent_id' => 88,    
                        'item_name' => "T02D",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 93,
                        'company_id' => 2,
                        'parent_id' => 88,    
                        'item_name' => "T02E",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 94,
                        'company_id' => 2,
                        'parent_id' => 81,    
                        'item_name' => "Baby",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 95,
                        'company_id' => 2,
                        'parent_id' => 94,    
                        'item_name' => "T05A",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 96,
                        'company_id' => 2,
                        'parent_id' => 94,    
                        'item_name' => "T05B",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 97,
                        'company_id' => 2,
                        'parent_id' => 94,    
                        'item_name' => "T05C",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 98,
                        'company_id' => 2,
                        'parent_id' => 94,    
                        'item_name' => "T05D",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 99,
                        'company_id' => 2,
                        'parent_id' => 94,    
                        'item_name' => "T05E",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 100,
                        'company_id' => 2,
                        'parent_id' => 80,    
                        'item_name' => "Special Tags",
                        'ref_no' => "",
                        'tag_name' => "special",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 101,
                        'company_id' => 2,
                        'parent_id' => 100,    
                        'item_name' => "Plus Products",
                        'ref_no' => "",
                        'tag_name' => "special",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 102,
                        'company_id' => 2,
                        'parent_id' => 101,    
                        'item_name' => "T10R1",
                        'ref_no' => "",
                        'tag_name' => "special",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 103,
                        'company_id' => 2,
                        'parent_id' => 101,    
                        'item_name' => "T33",
                        'ref_no' => "",
                        'tag_name' => "special",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 104,
                        'company_id' => 2,
                        'parent_id' => 101,    
                        'item_name' => "T10H",
                        'ref_no' => "",
                        'tag_name' => "special",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 105,
                        'company_id' => 2,
                        'parent_id' => 101,    
                        'item_name' => "T10Q",
                        'ref_no' => "",
                        'tag_name' => "special",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 106,
                        'company_id' => 2,
                        'parent_id' => 101,    
                        'item_name' => "T10R",
                        'ref_no' => "",
                        'tag_name' => "special",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 107,
                        'company_id' => 2,
                        'parent_id' => 101,    
                        'item_name' => "T10M2",
                        'ref_no' => "",
                        'tag_name' => "special",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 108,
                        'company_id' => 2,
                        'parent_id' => 101,    
                        'item_name' => "T10C",
                        'ref_no' => "",
                        'tag_name' => "special",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 109,
                        'company_id' => 2,
                        'parent_id' => 101,    
                        'item_name' => "T10V",
                        'ref_no' => "",
                        'tag_name' => "special",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 110,
                        'company_id' => 2,
                        'parent_id' => 101,    
                        'item_name' => "T21",
                        'ref_no' => "",
                        'tag_name' => "special",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 111,
                        'company_id' => 2,
                        'parent_id' => 101,    
                        'item_name' => "T21A",
                        'ref_no' => "",
                        'tag_name' => "special",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 112,
                        'company_id' => 2,
                        'parent_id' => 101,    
                        'item_name' => "T10Y",
                        'ref_no' => "",
                        'tag_name' => "special",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 113,
                        'company_id' => 2,
                        'parent_id' => 101,    
                        'item_name' => "T10P",
                        'ref_no' => "",
                        'tag_name' => "special",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 114,
                        'company_id' => 2,
                        'parent_id' => 101,    
                        'item_name' => "T10A",
                        'ref_no' => "",
                        'tag_name' => "special",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 115,
                        'company_id' => 2,
                        'parent_id' => 101,    
                        'item_name' => "T10W",
                        'ref_no' => "",
                        'tag_name' => "special",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 116,
                        'company_id' => 2,
                        'parent_id' => 101,    
                        'item_name' => "T10Z",
                        'ref_no' => "",
                        'tag_name' => "special",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 117,
                        'company_id' => 2,
                        'parent_id' => 100,    
                        'item_name' => "Warnings",
                        'ref_no' => "",
                        'tag_name' => "special",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 118,
                        'company_id' => 2,
                        'parent_id' => 117,    
                        'item_name' => "T11F",
                        'ref_no' => "",
                        'tag_name' => "special",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 119,
                        'company_id' => 2,
                        'parent_id' => 117,    
                        'item_name' => "T11D",
                        'ref_no' => "",
                        'tag_name' => "special",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 120,
                        'company_id' => 2,
                        'parent_id' => 117,    
                        'item_name' => "T11A",
                        'ref_no' => "",
                        'tag_name' => "special",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 121,
                        'company_id' => 2,
                        'parent_id' => 117,    
                        'item_name' => "T11C",
                        'ref_no' => "",
                        'tag_name' => "special",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 122,
                        'company_id' => 2,
                        'parent_id' => 117,    
                        'item_name' => "T11D",
                        'ref_no' => "",
                        'tag_name' => "special",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 123,
                        'company_id' => 2,
                        'parent_id' => 80,    
                        'item_name' => "Pomme Frambosie",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 124,
                        'company_id' => 2,
                        'parent_id' => 123,    
                        'item_name' => "PTPF",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 125,
                        'company_id' => 2,
                        'parent_id' => 123,    
                        'item_name' => "T03",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 126,
                        'company_id' => 2,
                        'parent_id' => 79,    
                        'item_name' => "Header Cards",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 127,
                        'company_id' => 2,
                        'parent_id' => 79,    
                        'item_name' => "Insert Cards",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 128,
                        'company_id' => 2,
                        'parent_id' => 79,    
                        'item_name' => "Cascades",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 129,
                        'company_id' => 2,
                        'parent_id' => 78,    
                        'item_name' => "Disney",
                        'ref_no' => "",
                        'tag_name' => "disney",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 130,
                        'company_id' => 2,
                        'parent_id' => 129,    
                        'item_name' => "Hang Tags",
                        'ref_no' => "",
                        'tag_name' => "disney",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 131,
                        'company_id' => 2,
                        'parent_id' => 130,    
                        'item_name' => "Disney Baby",
                        'ref_no' => "",
                        'tag_name' => "disney",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 132,
                        'company_id' => 2,
                        'parent_id' => 131,    
                        'item_name' => "DBB1",
                        'ref_no' => "",
                        'tag_name' => "disney",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 133,
                        'company_id' => 2,
                        'parent_id' => 131,    
                        'item_name' => "DBB2",
                        'ref_no' => "",
                        'tag_name' => "disney",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 134,
                        'company_id' => 2,
                        'parent_id' => 131,    
                        'item_name' => "DBB3",
                        'ref_no' => "",
                        'tag_name' => "disney",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 135,
                        'company_id' => 2,
                        'parent_id' => 130,    
                        'item_name' => "Disney Boy",
                        'ref_no' => "",
                        'tag_name' => "disney",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 136,
                        'company_id' => 2,
                        'parent_id' => 135,    
                        'item_name' => "COD1",
                        'ref_no' => "",
                        'tag_name' => "disney",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 137,
                        'company_id' => 2,
                        'parent_id' => 135,    
                        'item_name' => "COD2",
                        'ref_no' => "",
                        'tag_name' => "disney",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 138,
                        'company_id' => 2,
                        'parent_id' => 135,    
                        'item_name' => "COD2x",
                        'ref_no' => "",
                        'tag_name' => "disney",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 139,
                        'company_id' => 2,
                        'parent_id' => 135,    
                        'item_name' => "COD3",
                        'ref_no' => "",
                        'tag_name' => "disney",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 140,
                        'company_id' => 2,
                        'parent_id' => 130,    
                        'item_name' => "Disney Girl",
                        'ref_no' => "",
                        'tag_name' => "disney",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 141,
                        'company_id' => 2,
                        'parent_id' => 140,    
                        'item_name' => "COD1",
                        'ref_no' => "",
                        'tag_name' => "disney",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 142,
                        'company_id' => 2,
                        'parent_id' => 140,    
                        'item_name' => "COD2",
                        'ref_no' => "",
                        'tag_name' => "disney",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 143,
                        'company_id' => 2,
                        'parent_id' => 140,    
                        'item_name' => "COD2x",
                        'ref_no' => "",
                        'tag_name' => "disney",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 144,
                        'company_id' => 2,
                        'parent_id' => 140,    
                        'item_name' => "COD3",
                        'ref_no' => "",
                        'tag_name' => "disney",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 145,
                        'company_id' => 2,
                        'parent_id' => 78,    
                        'item_name' => "Marvel",
                        'ref_no' => "",
                        'tag_name' => "marvel",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 146,
                        'company_id' => 2,
                        'parent_id' => 145,    
                        'item_name' => "Hang Tag",
                        'ref_no' => "",
                        'tag_name' => "marvel",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 147,
                        'company_id' => 2,
                        'parent_id' => 146,    
                        'item_name' => "Boy - DM1",
                        'ref_no' => "",
                        'tag_name' => "marvel",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 148,
                        'company_id' => 2,
                        'parent_id' => 146,    
                        'item_name' => "Girl - DM2",
                        'ref_no' => "",
                        'tag_name' => "marvel",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 149,
                        'company_id' => 2,
                        'parent_id' => 78,    
                        'item_name' => "Star Wars",
                        'ref_no' => "",
                        'tag_name' => "starwars",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 150,
                        'company_id' => 2,
                        'parent_id' => 149,    
                        'item_name' => "Hang Tag",
                        'ref_no' => "",
                        'tag_name' => "starwars",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 151,
                        'company_id' => 2,
                        'parent_id' => 150,    
                        'item_name' => "Boy - DSW",
                        'ref_no' => "",
                        'tag_name' => "starwars",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 152    ,
                        'company_id' => 2,
                        'parent_id' => 150,    
                        'item_name' => "Girl - DSW",
                        'ref_no' => "",
                        'tag_name' => "starwars",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 153,
                        'company_id' => 2,
                        'parent_id' => 78,    
                        'item_name' => "Smiley",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 154,
                        'company_id' => 2,
                        'parent_id' => 153,    
                        'item_name' => "Hang Tag",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 155,
                        'company_id' => 2,
                        'parent_id' => 154,    
                        'item_name' => "Smiley Baby - SMIBB1",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 156,
                        'company_id' => 2,
                        'parent_id' => 154,    
                        'item_name' => "Smiley Kids - SMI1",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 157,
                        'company_id' => 2,
                        'parent_id' => 78,    
                        'item_name' => "Warner",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 158,
                        'company_id' => 2,
                        'parent_id' => 157,    
                        'item_name' => "Hang Tag",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 159,
                        'company_id' => 2,
                        'parent_id' => 158,    
                        'item_name' => "Batman – HTBT",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 160,
                        'company_id' => 2,
                        'parent_id' => 158,    
                        'item_name' => "LooneyTunesClassic – HTLT",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 161,
                        'company_id' => 2,
                        'parent_id' => 158,    
                        'item_name' => "Superman – HTSM",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 162,
                        'company_id' => 2,
                        'parent_id' => 158,    
                        'item_name' => "Wonder woman - WWHT",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 163,
                        'company_id' => 2,
                        'parent_id' => 78,    
                        'item_name' => "Puriculture / Premaman",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 164,
                        'company_id' => 2,
                        'parent_id' => 163,    
                        'item_name' => "Hang Tag",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 165,
                        'company_id' => 2,
                        'parent_id' => 164,    
                        'item_name' => "Baby - T12",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 166,
                        'company_id' => 2,
                        'parent_id' => 78,    
                        'item_name' => "Maternity",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 167,
                        'company_id' => 2,
                        'parent_id' => 166,    
                        'item_name' => "Hang Tag",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 168,
                        'company_id' => 2,
                        'parent_id' => 167,    
                        'item_name' => "HM1BA",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 169,
                        'company_id' => 2,
                        'parent_id' => 167,    
                        'item_name' => "HM1BRA",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 170,
                        'company_id' => 2,
                        'parent_id' => 167,    
                        'item_name' => "HM1D",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 171,
                        'company_id' => 2,
                        'parent_id' => 167,    
                        'item_name' => "HM1",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 172,
                        'company_id' => 2,
                        'parent_id' => 167,    
                        'item_name' => "HM2BRA",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 173,
                        'company_id' => 2,
                        'parent_id' => 167,    
                        'item_name' => "MPT",
                        'ref_no' => "",
                        'tag_name' => "hang",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 174,
                        'company_id' => 2,
                        'parent_id' => 78,    
                        'item_name' => "Price Tag",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    \App\OrchestraItemMaster::create([
                        'id'  => 175,
                        'company_id' => 2,
                        'parent_id' => 174,    
                        'item_name' => "PT",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
					\App\OrchestraItemMaster::create([
                        'id'  => 176,
                        'company_id' => 2,
                        'parent_id' => 70,    
                        'item_name' => "Carton & Polybag Stickers",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
					\App\OrchestraItemMaster::create([
                        'id'  => 177,
                        'company_id' => 2,
                        'parent_id' => 176,    
                        'item_name' => "Solid Carton Stickers",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
					\App\OrchestraItemMaster::create([
                        'id'  => 178,
                        'company_id' => 2,
                        'parent_id' => 176,    
                        'item_name' => "Solid Polybag Stickers",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
					\App\OrchestraItemMaster::create([
                        'id'  => 179,
                        'company_id' => 2,
                        'parent_id' => 176,    
                        'item_name' => "Standard Carton Stickers",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
					\App\OrchestraItemMaster::create([
                        'id'  => 180,
                        'company_id' => 2,
                        'parent_id' => 176,    
                        'item_name' => "Standard Polybag Stickers",
                        'ref_no' => "",
                        'tag_name' => "",
                    ]);
                    
    } //run fucntion ends here
} // class ends here
