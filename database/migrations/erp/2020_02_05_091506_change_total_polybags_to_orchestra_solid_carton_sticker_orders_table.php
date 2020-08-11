<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTotalPolybagsToOrchestraSolidCartonStickerOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_standard_carton_sticker_orders', function (Blueprint $table) {
            $table->integer('total_polybag')->nullable()->change();
        });

	Schema::table('orchestra_standard_poly_bag_sticker_orders', function (Blueprint $table) {
            $table->integer('total_polybag')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_standard_carton_sticker_orders', function (Blueprint $table) {
            $table->integer('total_polybag')->change();
        });

	Schema::table('orchestra_standard_poly_bag_sticker_orders', function (Blueprint $table) {
            $table->integer('total_polybag')->change();
        });
    }
}
