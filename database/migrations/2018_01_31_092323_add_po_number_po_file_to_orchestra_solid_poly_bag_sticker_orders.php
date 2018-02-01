<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPoNumberPoFileToOrchestraSolidPolyBagStickerOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_solid_poly_bag_sticker_orders', function (Blueprint $table) {
            $table->string('po_number')->after('svg_path')->nullable();
            $table->string('po_file')->after('po_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_solid_poly_bag_sticker_orders', function (Blueprint $table) {
            $table->dropColumn('po_number');
             $table->dropColumn('po_file');
        });
    }
}
