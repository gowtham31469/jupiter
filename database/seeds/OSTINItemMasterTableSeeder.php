<?php

use Illuminate\Database\Seeder;

class OSTINItemMasterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\OSTINItemMaster::create([
				'company_id' => 1,
				'parent_id' => 0,
				'item_name' => "Labels",
				'ref_no' => "",
				'tag_name' => "",
			]);
		\App\OSTINItemMaster::create([
				'company_id' => 1,
				'parent_id' => 0,
				'item_name' => "Stickers",
				'ref_no' => "",
				'tag_name' => "",
		]);	
		\App\OSTINItemMaster::create([
				'company_id' => 1,
				'parent_id' => 0,
				'item_name' => "Printed Boards",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\OSTINItemMaster::create([
				'company_id' => 1,
				'parent_id' => 1,
				'item_name' => "Printed Label",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\OSTINItemMaster::create([
				'company_id' => 1,
				'parent_id' => 1,
				'item_name' => "Woven Label",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\OSTINItemMaster::create([
				'company_id' => 1,
				'parent_id' => 1,
				'item_name' => "Heat Transfers",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\OSTINItemMaster::create([
				'company_id' => 1,
				'parent_id' => 1,
				'item_name' => "Cotton",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\OSTINItemMaster::create([
				'company_id' => 1,
				'parent_id' => 1,
				'item_name' => "Others",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\OSTINItemMaster::create([
				'company_id' => 1,
				'parent_id' => 2,
				'item_name' => "Barcode Sticker",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\OSTINItemMaster::create([
				'company_id' => 1,
				'parent_id' => 2,
				'item_name' => "Hanger Sticker",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\OSTINItemMaster::create([
				'company_id' => 1,
				'parent_id' => 2,
				'item_name' => "Price Sticker",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\OSTINItemMaster::create([
				'company_id' => 1,
				'parent_id' => 2,
				'item_name' => "Fab Friendly Sticker",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\OSTINItemMaster::create([
				'company_id' => 1,
				'parent_id' => 2,
				'item_name' => "Carton & Polybag Sticker",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\OSTINItemMaster::create([
				'company_id' => 1,
				'parent_id' => 2,
				'item_name' => "Others",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\OSTINItemMaster::create([
				'company_id' => 2,
				'parent_id' => 2,
				'item_name' => "Size Sticker",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\OSTINItemMaster::create([
				'company_id' => 2,
				'parent_id' => 2,
				'item_name' => "CMYK Sticker",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\OSTINItemMaster::create([
				'company_id' => 2,
				'parent_id' => 2,
				'item_name' => "Barcode Sticker",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\OSTINItemMaster::create([
				'company_id' => 2,
				'parent_id' => 2,
				'item_name' => "Others",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\OSTINItemMaster::create([
				'company_id' => 2,
				'parent_id' => 3,
				'item_name' => "Tag",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\OSTINItemMaster::create([
				'company_id' => 2,
				'parent_id' => 3,
				'item_name' => "Insert Card",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\OSTINItemMaster::create([
				'company_id' => 2,
				'parent_id' => 3,
				'item_name' => "Photo card",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\OSTINItemMaster::create([
				'company_id' => 2,
				'parent_id' => 3,
				'item_name' => "Cascade/Wrapband",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\OSTINItemMaster::create([
				'company_id' => 2,
				'parent_id' => 3,
				'item_name' => "Inner Box",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\OSTINItemMaster::create([
				'company_id' => 2,
				'parent_id' => 3,
				'item_name' => "Rider Card",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\OSTINItemMaster::create([
				'company_id' => 2,
				'parent_id' => 3,
				'item_name' => "Others",
				'ref_no' => "",
				'tag_name' => "",
		]);
    }
}
