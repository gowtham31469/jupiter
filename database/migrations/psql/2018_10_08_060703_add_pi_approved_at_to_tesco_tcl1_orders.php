<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPiApprovedAtToTescoTcl1Orders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_tcl1_orders', function (Blueprint $table) {
           $table->timestamp('pi_approved_at')->after('po_uploaded_at')->nullable();
       });

       Schema::table('tesco_tcl2_orders', function (Blueprint $table) {
           $table->timestamp('pi_approved_at')->after('po_uploaded_at')->nullable();
       });

       Schema::table('tesco_tsl1_orders', function (Blueprint $table) {
           $table->timestamp('pi_approved_at')->after('po_uploaded_at')->nullable();
       });

       Schema::table('tesco_tcl4_orders', function (Blueprint $table) {
           $table->timestamp('pi_approved_at')->after('po_uploaded_at')->nullable();
       });

       Schema::table('tesco_tsl3_orders', function (Blueprint $table) {
           $table->timestamp('pi_approved_at')->after('po_uploaded_at')->nullable();
       });

       Schema::table('tesco_thl1_orders', function (Blueprint $table) {
           $table->timestamp('pi_approved_at')->after('po_uploaded_at')->nullable();
       });

       Schema::table('tesco_thl3_orders', function (Blueprint $table) {
           $table->timestamp('pi_approved_at')->after('po_uploaded_at')->nullable();
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
           $table->dropColumn('pi_approved_at');
        });
        Schema::table('tesco_tcl2_orders', function (Blueprint $table) {
           $table->dropColumn('pi_approved_at');
        });
        Schema::table('tesco_tsl1_orders', function (Blueprint $table) {
           $table->dropColumn('pi_approved_at');
        });
        Schema::table('tesco_tcl4_orders', function (Blueprint $table) {
           $table->dropColumn('pi_approved_at');
        });
        Schema::table('tesco_tsl3_orders', function (Blueprint $table) {
           $table->dropColumn('pi_approved_at');
        });
        Schema::table('tesco_thl1_orders', function (Blueprint $table) {
           $table->dropColumn('pi_approved_at');
        });
        Schema::table('tesco_thl3_orders', function (Blueprint $table) {
           $table->dropColumn('pi_approved_at');
        });
    }
}
