<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameOrchestraSolidPolyBagStickerOrderQtyToOrchestraSolidPolyBagStickerOrdersQty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('orchestra_solid_poly_bag_sticker_order_qty', 'orchestra_solid_poly_bag_sticker_orders_qty');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('orchestra_solid_poly_bag_sticker_orders_qty', 'orchestra_solid_poly_bag_sticker_order_qty');
    }
}
