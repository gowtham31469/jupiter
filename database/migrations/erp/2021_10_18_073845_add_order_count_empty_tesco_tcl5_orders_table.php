<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderCountEmptyTescoTcl5OrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_tcl5_orders', function (Blueprint $table) {
            $table->smallInteger('is_order_count_empty')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_tcl5_orders', function (Blueprint $table) {
            $table->dropColumn('is_order_count_empty');
        });
    }
}
