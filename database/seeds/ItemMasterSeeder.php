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
                'is_qrcode_required' => 0,
                'is_non_artwork_template' => 1
            ),
            1 => 
            array (
                'id' => 2,
                'company_id' => 1,
                'parent_id' => 0,
                'item_name' => 'Stickers',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0,
                'is_non_artwork_template' => 1
            ),
            2 => 
            array (
                'id' => 3,
                'company_id' => 1,
                'parent_id' => 0,
                'item_name' => 'Printed Board',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0,
                'is_non_artwork_template' => 1
            ),
            3 => 
            array (
                'id' => 4,
                'company_id' => 1,
                'parent_id' => 1,
                'item_name' => 'Printed Labels',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0,
                'is_non_artwork_template' => 1
            ),
            4 => 
            array (
                'id' => 5,
                'company_id' => 1,
                'parent_id' => 1,
                'item_name' => 'Woven Labels',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0,
                'is_non_artwork_template' => 1
            ),
            5 => 
            array (
                'id' => 6,
                'company_id' => 1,
                'parent_id' => 1,
                'item_name' => 'Heat Transfers',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0,
                'is_non_artwork_template' => 1
            ),
            6 => 
            array (
                'id' => 7,
                'company_id' => 1,
                'parent_id' => 1,
                'item_name' => 'Cotton',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0,
                'is_non_artwork_template' => 1
            ),
            7 => 
            array (
                'id' => 8,
                'company_id' => 1,
                'parent_id' => 1,
                'item_name' => 'Others',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0,
                'is_non_artwork_template' => 1
            ),
            8 => 
            array (
                'id' => 9,
                'company_id' => 1,
                'parent_id' => 2,
                'item_name' => 'Barcode Stickers',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0,
                'is_non_artwork_template' => 1
            ),
            9 => 
            array (
                'id' => 10,
                'company_id' => 1,
                'parent_id' => 2,
                'item_name' => 'Hanger Stickers',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0,
                'is_non_artwork_template' => 1
            ),
            10 => 
            array (
                'id' => 11,
                'company_id' => 1,
                'parent_id' => 2,
                'item_name' => 'Price Stickers',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0,
                'is_non_artwork_template' => 1
            ),
            11 => 
            array (
                'id' => 12,
                'company_id' => 1,
                'parent_id' => 2,
                'item_name' => 'Fab Friendly Stickers',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0,
                'is_non_artwork_template' => 1
            ),
            12 => 
            array (
                'id' => 13,
                'company_id' => 1,
                'parent_id' => 2,
                'item_name' => 'Carton & Polybag Stickers',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0,
                'is_non_artwork_template' => 1
            ),
            13 => 
            array (
                'id' => 14,
                'company_id' => 2,
                'parent_id' => 2,
                'item_name' => 'Size Stickers',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0,
                'is_non_artwork_template' => 1
            ),
            14 => 
            array (
                'id' => 15,
                'company_id' => 2,
                'parent_id' => 2,
                'item_name' => 'CMYK Stickers',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0,
                'is_non_artwork_template' => 1
            ),
            15 => 
            array (
                'id' => 16,
                'company_id' => 1,
                'parent_id' => 2,
                'item_name' => 'Others',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0,
                'is_non_artwork_template' => 1
            ),
            16 => 
            array (
                'id' => 17,
                'company_id' => 2,
                'parent_id' => 3,
                'item_name' => 'Tags',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0,
                'is_non_artwork_template' => 1
            ),
            17 => 
            array (
                'id' => 18,
                'company_id' => 2,
                'parent_id' => 3,
                'item_name' => 'Insert Cards',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0,
                'is_non_artwork_template' => 1
            ),
            18 => 
            array (
                'id' => 19,
                'company_id' => 2,
                'parent_id' => 3,
                'item_name' => 'Photo cards',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0,
                'is_non_artwork_template' => 1
            ),
            19 => 
            array (
                'id' => 20,
                'company_id' => 2,
                'parent_id' => 3,
                'item_name' => 'Cascades/Wrapbands',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0,
                'is_non_artwork_template' => 1
            ),
            20 => 
            array (
                'id' => 21,
                'company_id' => 2,
                'parent_id' => 3,
                'item_name' => 'Inner Box',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0,
                'is_non_artwork_template' => 1
            ),
            21 => 
            array (
                'id' => 22,
                'company_id' => 2,
                'parent_id' => 3,
                'item_name' => 'Rider Card',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0,
                'is_non_artwork_template' => 1
            ),
            22 => 
            array (
                'id' => 23,
                'company_id' => 2,
                'parent_id' => 3,
                'item_name' => 'Others',
                'ref_no' => '',
                'tag_name' => '',
                'is_qrcode_required' => 0,
                'is_non_artwork_template' => 1
            )
        ));

        return true;
    }
}
