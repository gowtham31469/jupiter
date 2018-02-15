<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddZoneTemplateTypeToTescoPolybagCartonStickerOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_polybag_carton_sticker_orders', function (Blueprint $table) {
            $table->string('zone')->after('order_id');
            $table->string('template_type')->after('zone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_polybag_carton_sticker_orders', function (Blueprint $table) {
            $table->dropColumn('zone');
            $table->dropColumn('template_type');
        });
    }
}
