<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCasesPerCartonToTescoTcl2OrdersQuantyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_tcl2_orders_quanty', function (Blueprint $table) {
            $table->integer('cases_per_carton')->default('0')->after('units_per_carton');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_tcl2_orders_quanty', function (Blueprint $table) {
            $table->dropColumn('cases_per_carton');
        });
    }
}
