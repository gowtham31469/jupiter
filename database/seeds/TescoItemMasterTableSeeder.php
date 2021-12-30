<?php

use Illuminate\Database\Seeder;

class TescoItemMasterTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tesco_item_master')->delete();
        
        \DB::table('tesco_item_master')->insert(array (
            0 => 
            array (
                'id' => 1,
                'company_id' => 1,
                'parent_id' => 0,
                'item_name' => 'Stickers',
                'ref_no' => 'Ref-01',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            1 => 
            array (
                'id' => 2,
                'company_id' => 1,
                'parent_id' => 1,
                'item_name' => 'Carton & Polybag Stickers',
                'ref_no' => 'Ref-02',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            2 => 
            array (
                'id' => 3,
                'company_id' => 1,
                'parent_id' => 2,
                'item_name' => 'CE',
                'ref_no' => 'Ref-03',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            3 => 
            array (
                'id' => 4,
                'company_id' => 1,
                'parent_id' => 2,
                'item_name' => 'UK',
                'ref_no' => 'Ref-04',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            4 => 
            array (
                'id' => 5,
                'company_id' => 1,
                'parent_id' => 3,
                'item_name' => 'TCL 1',
                'ref_no' => 'TCL 1',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            5 => 
            array (
                'id' => 6,
                'company_id' => 1,
                'parent_id' => 3,
                'item_name' => 'TCL 2',
                'ref_no' => 'TCL 2',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            6 => 
            array (
                'id' => 7,
                'company_id' => 1,
                'parent_id' => 3,
                'item_name' => 'TSL 1',
                'ref_no' => 'TSL 1',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            7 => 
            array (
                'id' => 8,
                'company_id' => 1,
                'parent_id' => 3,
                'item_name' => 'TCL 4',
                'ref_no' => 'TCL 4',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            8 => 
            array (
                'id' => 9,
                'company_id' => 1,
                'parent_id' => 3,
                'item_name' => 'TSL 3',
                'ref_no' => 'TSL 3',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            9 => 
            array (
                'id' => 11,
                'company_id' => 1,
                'parent_id' => 3,
                'item_name' => 'THL 3',
                'ref_no' => 'THL 3',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            10 => 
            array (
                'id' => 12,
                'company_id' => 1,
                'parent_id' => 4,
                'item_name' => 'TCL 1',
                'ref_no' => 'TCL 1',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            11 => 
            array (
                'id' => 13,
                'company_id' => 1,
                'parent_id' => 4,
                'item_name' => 'TCL 2',
                'ref_no' => 'TCL 2',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            12 => 
            array (
                'id' => 14,
                'company_id' => 1,
                'parent_id' => 4,
                'item_name' => 'TSL 1',
                'ref_no' => 'TSL 1',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            13 => 
            array (
                'id' => 15,
                'company_id' => 1,
                'parent_id' => 4,
                'item_name' => 'TCL 4',
                'ref_no' => 'TCL 4',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            14 => 
            array (
                'id' => 16,
                'company_id' => 1,
                'parent_id' => 4,
                'item_name' => 'TSL 3',
                'ref_no' => 'TSL 3',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            15 => 
            array (
                'id' => 18,
                'company_id' => 1,
                'parent_id' => 4,
                'item_name' => 'THL 3',
                'ref_no' => 'THL 3',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            16 => 
            array (
                'id' => 10,
                'company_id' => 1,
                'parent_id' => 3,
                'item_name' => 'THL 1',
                'ref_no' => 'THL 1',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            17 => 
            array (
                'id' => 17,
                'company_id' => 1,
                'parent_id' => 4,
                'item_name' => 'THL 1',
                'ref_no' => 'THL 1',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            19 => 
            array (
                'id' => 19,
                'company_id' => 1,
                'parent_id' => 3,
                'item_name' => 'TCL 1',
                'ref_no' => 'TCL 1',
                'tag_name' => '',
                'is_qrcode_required' => 1
            ),
            20 => 
            array (
                'id' => 20,
                'company_id' => 1,
                'parent_id' => 3,
                'item_name' => 'TCL 2',
                'ref_no' => 'TCL 2',
                'tag_name' => '',
                'is_qrcode_required' => 1
            ),
            21 => 
            array (
                'id' => 21,
                'company_id' => 1,
                'parent_id' => 3,
                'item_name' => 'TSL 1',
                'ref_no' => 'TSL 1',
                'tag_name' => '',
                'is_qrcode_required' => 1
            ),
            22 => 
            array (
                'id' => 22,
                'company_id' => 1,
                'parent_id' => 3,
                'item_name' => 'TCL 4',
                'ref_no' => 'TCL 4',
                'tag_name' => '',
                'is_qrcode_required' => 1
            ),
            23 => 
            array (
                'id' => 23,
                'company_id' => 1,
                'parent_id' => 3,
                'item_name' => 'TSL 3',
                'ref_no' => 'TSL 3',
                'tag_name' => '',
                'is_qrcode_required' => 1
            ),
            24 => 
            array (
                'id' => 24,
                'company_id' => 1,
                'parent_id' => 3,
                'item_name' => 'THL 1',
                'ref_no' => 'THL 1',
                'tag_name' => '',
                'is_qrcode_required' => 1
            ),
            25 => 
            array (
                'id' => 25,
                'company_id' => 1,
                'parent_id' => 3,
                'item_name' => 'THL 3',
                'ref_no' => 'THL 3',
                'tag_name' => '',
                'is_qrcode_required' => 1
            ),
            26 => 
            array (
                'id' => 26,
                'company_id' => 1,
                'parent_id' => 3,
                'item_name' => 'TCL 1',
                'ref_no' => 'TCL 1',
                'tag_name' => '',
                'is_qrcode_required' => 1
            ),
            27 => 
            array (
                'id' => 27,
                'company_id' => 1,
                'parent_id' => 3,
                'item_name' => 'TCL 2',
                'ref_no' => 'TCL 2',
                'tag_name' => '',
                'is_qrcode_required' => 1
            ),
            28 => 
            array (
                'id' => 28,
                'company_id' => 1,
                'parent_id' => 3,
                'item_name' => 'TCL 4',
                'ref_no' => 'TCL 4',
                'tag_name' => '',
                'is_qrcode_required' => 1
            ),
            29 => 
            array (
                'id' => 29,
                'company_id' => 1,
                'parent_id' => 4,
                'item_name' => 'TCL 1',
                'ref_no' => 'TCL 1',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            30 => 
            array (
                'id' => 30,
                'company_id' => 1,
                'parent_id' => 4,
                'item_name' => 'TCL 2',
                'ref_no' => 'TCL 2',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            31 => 
            array (
                'id' => 31,
                'company_id' => 1,
                'parent_id' => 4,
                'item_name' => 'TCL 4',
                'ref_no' => 'TCL 4',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            32 => 
            array (
                'id' => 32,
                'company_id' => 1,
                'parent_id' => 0,
                'item_name' => 'Labels',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            33 => 
            array (
                'id' => 33,
                'company_id' => 1,
                'parent_id' => 32,
                'item_name' => 'Printed Labels',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            34 => 
            array (
                'id' => 34,
                'company_id' => 1,
                'parent_id' => 33,
                'item_name' => 'GCL25W/M12723G',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            35 => 
            array (
                'id' => 35,
                'company_id' => 1,
                'parent_id' => 33,
                'item_name' => 'GCL25W/M13220G',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            36 => 
            array (
                'id' => 36,
                'company_id' => 1,
                'parent_id' => 33,
                'item_name' => 'GCL25W/M12723G',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            37 => 
            array (
                'id' => 37,
                'company_id' => 1,
                'parent_id' => 33,
                'item_name' => 'GCL25W/M13220G',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            38 =>
            array (
                'id' => 38,
                'company_id' => 1,
                'parent_id' => 3,
                'item_name' => 'TCL 5',
                'ref_no' => 'TCL 5',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            39 =>
            array (
                'id' => 39,
                'company_id' => 1,
                'parent_id' => 3,
                'item_name' => 'TSL 2',
                'ref_no' => 'TSL 2',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            40 =>
            array (
                'id' => 40,
                'company_id' => 1,
                'parent_id' => 4,
                'item_name' => 'TSL 2',
                'ref_no' => 'TSL 2',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            41 => 
            array (
                'id' => 41,
                'company_id' => 1,
                'parent_id' => 32,
                'item_name' => 'Swing Tickets',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            42 => 
            array (
                'id' => 42,
                'company_id' => 1,
                'parent_id' => 41,
                'item_name' => 'CE Non Rfid Swing Ticket',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            43 => 
            array (
                'id' => 43,
                'company_id' => 1,
                'parent_id' => 42,
                'item_name' => 'FFC/01/M13020C - CE F&F CORE NON-RFID BRAND TICKET INC. BOP',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            44 => 
            array (
                'id' => 44,
                'company_id' => 1,
                'parent_id' => 42,
                'item_name' => 'FFK/01/M13021C - CE F&F KIDS, SCHOOL & KIDS DRESS UP NON-RFID BRAND TICKET INC. BOP',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            45 => 
            array (
                'id' => 45,
                'company_id' => 1,
                'parent_id' => 42,
                'item_name' => 'FFC/01/M13022C - CE F&F CORE SOCKS & GIFTING NON-RFID BRAND TICKET INC. BOP',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            46 => 
            array (
                'id' => 46,
                'company_id' => 1,
                'parent_id' => 42,
                'item_name' => 'FFB/01/M13023C - CE FRED & FLO NON-RFID BRAND TICKET INC. BOP',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            47 => 
            array (
                'id' => 47,
                'company_id' => 1,
                'parent_id' => 42,
                'item_name' => 'GE/01/M13114C - Opt 1 - Core Ladieswear & Menswear (excluding Socks & Gifting)',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            48 => 
            array (
                'id' => 48,
                'company_id' => 1,
                'parent_id' => 42,
                'item_name' => 'GE/01/M13114C - Opt 2 - Kids, School and Kids Dress Up (excluding Socks & Gifting)',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            49 => 
            array (
                'id' => 49,
                'company_id' => 1,
                'parent_id' => 42,
                'item_name' => 'GE/01/M13114C - Opt 3 - Socks & Gifting (Adults, Kids & Baby)',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            50 => 
            array (
                'id' => 50,
                'company_id' => 1,
                'parent_id' => 42,
                'item_name' => 'GE/01/M13114C - Opt 4 - Fred & Flo',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            51 => 
            array (
                'id' => 51,
                'company_id' => 1,
                'parent_id' => 42,
                'item_name' => 'FFK/01/M13163C - CE F&F KIDS SOCKS & GIFTING NON-RFID BRAND TICKET INC. BOP',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            52 => 
            array (
                'id' => 52,
                'company_id' => 1,
                'parent_id' => 42,
                'item_name' => 'FFB/01/M13233C - CE FRED & FLO SOCKS & GIFTING NON-RFID BRAND TICKET INC. BOP',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            53 => 
            array (
                'id' => 53,
                'company_id' => 1,
                'parent_id' => 42,
                'item_name' => 'FFC/01/M13304C - CE F&F CORE CASHMERE LARGE NON-RFID BRAND TICKET INC. BOP',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
        ));
        
        
    }
}