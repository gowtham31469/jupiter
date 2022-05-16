<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($buyer_item_master)
    {
        \DB::table($buyer_item_master)->insert(array (
            0 => 
            array (
                'id' => 1,
                'company_id' => 1,
                'parent_id' => 0,
                'item_name' => 'Label',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            1 => 
            array (
                'id' => 2,
                'company_id' => 1,
                'parent_id' => 0,
                'item_name' => 'Stickers',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            2 => 
            array (
                'id' => 3,
                'company_id' => 1,
                'parent_id' => 0,
                'item_name' => 'Printed Board',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            3 => 
            array (
                'id' => 4,
                'company_id' => 1,
                'parent_id' => 1,
                'item_name' => 'Printed Label',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            4 => 
            array (
                'id' => 5,
                'company_id' => 1,
                'parent_id' => 1,
                'item_name' => 'Woven Label',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            5 => 
            array (
                'id' => 6,
                'company_id' => 1,
                'parent_id' => 1,
                'item_name' => 'Heat Transfers',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            6 => 
            array (
                'id' => 7,
                'company_id' => 1,
                'parent_id' => 1,
                'item_name' => 'Cotton',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            7 => 
            array (
                'id' => 8,
                'company_id' => 1,
                'parent_id' => 1,
                'item_name' => 'Others',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            8 => 
            array (
                'id' => 9,
                'company_id' => 1,
                'parent_id' => 2,
                'item_name' => 'Barcode Sticker',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            9 => 
            array (
                'id' => 10,
                'company_id' => 1,
                'parent_id' => 2,
                'item_name' => 'Hanger Sticker',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            10 => 
            array (
                'id' => 11,
                'company_id' => 1,
                'parent_id' => 2,
                'item_name' => 'Price Sticker',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            11 => 
            array (
                'id' => 12,
                'company_id' => 1,
                'parent_id' => 2,
                'item_name' => 'Fab Friendly Sticker',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            12 => 
            array (
                'id' => 13,
                'company_id' => 1,
                'parent_id' => 2,
                'item_name' => 'Carton & Polybag Sticker',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            13 => 
            array (
                'id' => 14,
                'company_id' => 1,
                'parent_id' => 2,
                'item_name' => 'Others',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            14 => 
            array (
                'id' => 15,
                'company_id' => 2,
                'parent_id' => 2,
                'item_name' => 'Size Sticker',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            15 => 
            array (
                'id' => 16,
                'company_id' => 2,
                'parent_id' => 2,
                'item_name' => 'CMYK Sticker',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            16 => 
            array (
                'id' => 17,
                'company_id' => 2,
                'parent_id' => 3,
                'item_name' => 'Tag',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            17 => 
            array (
                'id' => 18,
                'company_id' => 2,
                'parent_id' => 3,
                'item_name' => 'Insert Card',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            18 => 
            array (
                'id' => 19,
                'company_id' => 2,
                'parent_id' => 3,
                'item_name' => 'Photo card',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            19 => 
            array (
                'id' => 20,
                'company_id' => 2,
                'parent_id' => 3,
                'item_name' => 'Cascade/Wrapband',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            20 => 
            array (
                'id' => 21,
                'company_id' => 2,
                'parent_id' => 3,
                'item_name' => 'Inner Box',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            21 => 
            array (
                'id' => 22,
                'company_id' => 2,
                'parent_id' => 3,
                'item_name' => 'Rider Card',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            ),
            22 => 
            array (
                'id' => 23,
                'company_id' => 2,
                'parent_id' => 3,
                'item_name' => 'Others',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0
            )
        ));

        return true;
    }
}
