<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSizeTextToTescoSwingticketOrdersQtyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_swingticket_orders_quanty', function (Blueprint $table) {
            $table->string('uk_size')->nullable(true);
            $table->string('ce_size')->nullable(true);
            $table->json('selected_secondary_sizes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_swingticket_orders_quanty', function (Blueprint $table) {
            $table->dropColumn('uk_size');
            $table->dropColumn('ce_size');
            $table->dropColumn('selected_secondary_sizes');
        });
    }
}
