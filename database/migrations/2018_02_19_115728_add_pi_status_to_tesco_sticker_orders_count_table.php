<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPiStatusToTescoStickerOrdersCountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_sticker_orders_count', function (Blueprint $table)
        {
            $table->string('po_number')->after('artwork_status')->nullable();
            $table->string('po_file')->after('po_number')->nullable();
            $table->string('po_status')->after('po_file')->nullable();
            $table->string('pi_status')->after('po_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_sticker_orders_count', function (Blueprint $table) {
            $table->dropColumn('po_number');
            $table->dropColumn('po_file');
            $table->dropColumn('pi_status');
            $table->dropColumn('po_status');
        });
    }
}
