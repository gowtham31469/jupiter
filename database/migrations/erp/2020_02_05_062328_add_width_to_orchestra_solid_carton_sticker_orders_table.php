<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWidthToOrchestraSolidCartonStickerOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_solid_carton_sticker_orders', function (Blueprint $table) {
            $table->integer('width')->nullable();   
        });

	Schema::table('orchestra_standard_carton_sticker_orders', function (Blueprint $table) {
            $table->integer('width')->nullable();   
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_solid_carton_sticker_orders', function (Blueprint $table) {
            $table->dropColumn('width');
        });

	Schema::table('orchestra_standard_carton_sticker_orders', function (Blueprint $table) {
            $table->dropColumn('width');
        });

    }
}
