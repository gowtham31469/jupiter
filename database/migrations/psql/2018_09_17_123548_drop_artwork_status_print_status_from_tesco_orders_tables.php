<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropArtworkStatusPrintStatusFromTescoOrdersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_tcl1_orders', function (Blueprint $table) {
           $table->dropColumn('artwork_status');
           $table->dropColumn('print_status');
        });
        Schema::table('tesco_tcl2_orders', function (Blueprint $table) {
           $table->dropColumn('artwork_status');
           $table->dropColumn('print_status');
        });
        Schema::table('tesco_tsl1_orders', function (Blueprint $table) {
           $table->dropColumn('artwork_status');
           $table->dropColumn('print_status');
        });
        Schema::table('tesco_tcl4_orders', function (Blueprint $table) {
           $table->dropColumn('artwork_status');
           $table->dropColumn('print_status');
        });
        Schema::table('tesco_tsl3_orders', function (Blueprint $table) {
           $table->dropColumn('artwork_status');
           $table->dropColumn('print_status');
        });
        Schema::table('tesco_thl1_orders', function (Blueprint $table) {
           $table->dropColumn('artwork_status');
           $table->dropColumn('print_status');
        });
        Schema::table('tesco_thl3_orders', function (Blueprint $table) {
           $table->dropColumn('artwork_status');
           $table->dropColumn('print_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_tcl1_orders', function (Blueprint $table) {
           $table->string('artwork_status')->after('order_status')->nullable();
           $table->string('print_status')->after('artwork_status')->nullable();
       });

       Schema::table('tesco_tcl2_orders', function (Blueprint $table) {
           $table->string('artwork_status')->after('order_status')->nullable();
           $table->string('print_status')->after('artwork_status')->nullable();
       });

       Schema::table('tesco_tsl1_orders', function (Blueprint $table) {
           $table->string('artwork_status')->after('order_status')->nullable();
           $table->string('print_status')->after('artwork_status')->nullable();
       });

       Schema::table('tesco_tcl4_orders', function (Blueprint $table) {
           $table->string('artwork_status')->after('order_status')->nullable();
           $table->string('print_status')->after('artwork_status')->nullable();
       });

       Schema::table('tesco_tsl3_orders', function (Blueprint $table) {
           $table->string('artwork_status')->after('order_status')->nullable();
           $table->string('print_status')->after('artwork_status')->nullable();
       });

       Schema::table('tesco_thl1_orders', function (Blueprint $table) {
           $table->string('artwork_status')->after('order_status')->nullable();
           $table->string('print_status')->after('artwork_status')->nullable();
       });

       Schema::table('tesco_thl3_orders', function (Blueprint $table) {
           $table->string('artwork_status')->after('order_status')->nullable();
           $table->string('print_status')->after('artwork_status')->nullable();
       });
    }
}
