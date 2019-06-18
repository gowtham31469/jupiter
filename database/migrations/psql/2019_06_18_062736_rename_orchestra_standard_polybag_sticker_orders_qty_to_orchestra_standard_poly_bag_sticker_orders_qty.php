<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameOrchestraStandardPolybagStickerOrdersQtyToOrchestraStandardPolyBagStickerOrdersQty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('orchestra_standard_polybag_sticker_orders_qty', 'orchestra_standard_poly_bag_sticker_orders_qty');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('orchestra_standard_poly_bag_sticker_orders_qty', 'orchestra_standard_polybag_sticker_orders_qty');
    }
}
