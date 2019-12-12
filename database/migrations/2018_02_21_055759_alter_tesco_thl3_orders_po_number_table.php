<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTescoThl3OrdersPoNumberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_thl3_orders', function (Blueprint $table) {
            $table->dropColumn('po_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_thl3_orders', function (Blueprint $table) {
            $table->string('po_number')->after('template_type');
        });
    }
}
