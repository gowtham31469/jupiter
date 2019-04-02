<?php

use Illuminate\Database\Seeder;

class GerberItemMasterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         \App\GerberItemMaster::create([
				'id' =>1,
				'company_id' => 1,
				'parent_id' => 0,
				'item_name' => "Labels",
				'ref_no' => "",
				'tag_name' => "",
			]);
		\App\GerberItemMaster::create([
				'id' =>2,
				'company_id' => 1,
				'parent_id' => 0,
				'item_name' => "Stickers",
				'ref_no' => "",
				'tag_name' => "",
		]);	
		\App\GerberItemMaster::create([
				'id' =>3,
				'company_id' => 1,
				'parent_id' => 0,
				'item_name' => "Printed Boards",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\GerberItemMaster::create([
				'id' =>4,
				'company_id' => 1,
				'parent_id' => 1,
				'item_name' => "Printed Label",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\GerberItemMaster::create([
				'id' =>5,
				'company_id' => 1,
				'parent_id' => 1,
				'item_name' => "Woven Label",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\GerberItemMaster::create([
				'id' =>6,
				'company_id' => 1,
				'parent_id' => 1,
				'item_name' => "Heat Transfers",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\GerberItemMaster::create([
				'id' =>7,
				'company_id' => 1,
				'parent_id' => 1,
				'item_name' => "Cotton",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\GerberItemMaster::create([
				'id' =>8,
				'company_id' => 1,
				'parent_id' => 1,
				'item_name' => "Others",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\GerberItemMaster::create([
				'id' =>9,
				'company_id' => 1,
				'parent_id' => 2,
				'item_name' => "Barcode Sticker",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\GerberItemMaster::create([
				'id' =>10,
				'company_id' => 1,
				'parent_id' => 2,
				'item_name' => "Hanger Sticker",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\GerberItemMaster::create([
				'id' =>11,
				'company_id' => 1,
				'parent_id' => 2,
				'item_name' => "Price Sticker",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\GerberItemMaster::create([
				'id' =>12,
				'company_id' => 1,
				'parent_id' => 2,
				'item_name' => "Fab Friendly Sticker",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\GerberItemMaster::create([
				'id' =>13,
				'company_id' => 1,
				'parent_id' => 2,
				'item_name' => "Carton & Polybag Sticker",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\GerberItemMaster::create([
				'id' =>14,
				'company_id' => 1,
				'parent_id' => 2,
				'item_name' => "Others",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\GerberItemMaster::create([
				'id' =>15,
				'company_id' => 2,
				'parent_id' => 2,
				'item_name' => "Size Sticker",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\GerberItemMaster::create([
				'id' =>16,
				'company_id' => 2,
				'parent_id' => 2,
				'item_name' => "CMYK Sticker",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\GerberItemMaster::create([
				'id' =>17,
				'company_id' => 2,
				'parent_id' => 2,
				'item_name' => "Barcode Sticker",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\GerberItemMaster::create([
				'id' =>18,
				'company_id' => 2,
				'parent_id' => 2,
				'item_name' => "Others",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\GerberItemMaster::create([
				'id' =>19,
				'company_id' => 2,
				'parent_id' => 3,
				'item_name' => "Tag",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\GerberItemMaster::create([
				'id' =>20,
				'company_id' => 2,
				'parent_id' => 3,
				'item_name' => "Insert Card",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\GerberItemMaster::create([
				'id' =>21,
				'company_id' => 2,
				'parent_id' => 3,
				'item_name' => "Photo card",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\GerberItemMaster::create([
				'id' =>22,
				'company_id' => 2,
				'parent_id' => 3,
				'item_name' => "Cascade/Wrapband",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\GerberItemMaster::create([
				'id' =>23,
				'company_id' => 2,
				'parent_id' => 3,
				'item_name' => "Inner Box",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\GerberItemMaster::create([
				'id' =>24,
				'company_id' => 2,
				'parent_id' => 3,
				'item_name' => "Rider Card",
				'ref_no' => "",
				'tag_name' => "",
		]);
		\App\GerberItemMaster::create([
				'id' =>25,
				'company_id' => 2,
				'parent_id' => 3,
				'item_name' => "Others",
				'ref_no' => "",
				'tag_name' => "",
		]);
    }
}
