<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSizeBreakToOrchestraStandardPolyBagStickerOrdersQtyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_standard_poly_bag_sticker_orders_qty', function (Blueprint $table) {
            $table->integer('size_break')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_standard_poly_bag_sticker_orders_qty', function (Blueprint $table) {
            $table->dropColumn('dispatch_mode');
        });
    }
}
