<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToTescoStickerOrdersCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_sticker_orders_count', function (Blueprint $table) {
            $table->string('order_status')->nullable()->after('count');
            $table->string('artwork_status')->nullable()->after('order_status');
            $table->string('print_status')->nullable()->after('artwork_status');
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
            $table->dropColumn('order_status');
            $table->dropColumn('artwork_status');
            $table->dropColumn('print_status');
        });
    }
}
