<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRatioTypeToTescoOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('tesco_tcl1_orders_quanty', function (Blueprint $table) {
           $table->string('ratio_pack_type')->after('count')->nullable();
       });

       Schema::table('tesco_tcl2_orders_quanty', function (Blueprint $table) {
           $table->string('ratio_pack_type')->after('count')->nullable();
       });

       Schema::table('tesco_tsl1_orders_quanty', function (Blueprint $table) {
           $table->string('ratio_pack_type')->after('count')->nullable();
       });

       Schema::table('tesco_thl1_orders_quanty', function (Blueprint $table) {
           $table->string('ratio_pack_type')->after('count')->nullable();
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_tcl1_orders_quanty', function (Blueprint $table) {
           $table->dropColumn('ratio_pack_type');
        });
        Schema::table('tesco_tcl2_orders_quanty', function (Blueprint $table) {
           $table->dropColumn('ratio_pack_type');
        });
        Schema::table('tesco_tsl1_orders_quanty', function (Blueprint $table) {
           $table->dropColumn('ratio_pack_type');
        });
        Schema::table('tesco_thl1_orders_quanty', function (Blueprint $table) {
           $table->dropColumn('ratio_pack_type');
        });
    }
}
