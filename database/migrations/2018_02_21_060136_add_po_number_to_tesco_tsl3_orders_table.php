<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPoNumberToTescoTsl3OrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_tsl3_orders', function (Blueprint $table) {
            $table->string('po_number')->after('template_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_tsl3_orders', function (Blueprint $table) {
            $table->dropColumn('po_number');
        });
    }
}
