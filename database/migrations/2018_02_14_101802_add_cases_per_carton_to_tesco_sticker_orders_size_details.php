<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCasesPerCartonToTescoStickerOrdersSizeDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_sticker_orders_size_details', function (Blueprint $table) {
            $table->integer('cases_per_carton')->after('units_per_carton');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_sticker_orders_size_details', function (Blueprint $table) {
            $table->dropColumn('cases_per_carton');
        });
    }
}
