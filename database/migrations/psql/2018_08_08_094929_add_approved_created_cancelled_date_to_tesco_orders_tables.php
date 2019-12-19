<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApprovedCreatedCancelledDateToTescoOrdersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('tesco_tcl1_orders', function (Blueprint $table) {
           $table->timestamp('order_created_at')->after('order_placed_by')->nullable();
           $table->timestamp('order_approved_at')->after('order_created_at')->nullable();
           $table->timestamp('order_cancelled_at')->after('order_approved_at')->nullable();
       });

       Schema::table('tesco_tcl2_orders', function (Blueprint $table) {
           $table->timestamp('order_created_at')->after('order_placed_by')->nullable();
           $table->timestamp('order_approved_at')->after('order_created_at')->nullable();
           $table->timestamp('order_cancelled_at')->after('order_approved_at')->nullable();
       });

       Schema::table('tesco_tsl1_orders', function (Blueprint $table) {
           $table->timestamp('order_created_at')->after('order_placed_by')->nullable();
           $table->timestamp('order_approved_at')->after('order_created_at')->nullable();
           $table->timestamp('order_cancelled_at')->after('order_approved_at')->nullable();
       });

       Schema::table('tesco_tcl4_orders', function (Blueprint $table) {
           $table->timestamp('order_created_at')->after('order_placed_by')->nullable();
           $table->timestamp('order_approved_at')->after('order_created_at')->nullable();
           $table->timestamp('order_cancelled_at')->after('order_approved_at')->nullable();
       });

       Schema::table('tesco_tsl3_orders', function (Blueprint $table) {
           $table->timestamp('order_created_at')->after('order_placed_by')->nullable();
           $table->timestamp('order_approved_at')->after('order_created_at')->nullable();
           $table->timestamp('order_cancelled_at')->after('order_approved_at')->nullable();
       });

       Schema::table('tesco_thl1_orders', function (Blueprint $table) {
           $table->timestamp('order_created_at')->after('order_placed_by')->nullable();
           $table->timestamp('order_approved_at')->after('order_created_at')->nullable();
           $table->timestamp('order_cancelled_at')->after('order_approved_at')->nullable();
       });

       Schema::table('tesco_thl3_orders', function (Blueprint $table) {
           $table->timestamp('order_created_at')->after('order_placed_by')->nullable();
           $table->timestamp('order_approved_at')->after('order_created_at')->nullable();
           $table->timestamp('order_cancelled_at')->after('order_approved_at')->nullable();
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
           $table->dropColumn('order_created_at');
           $table->dropColumn('order_approved_at');
           $table->dropColumn('order_cancelled_at');
        });
        Schema::table('tesco_tcl2_orders', function (Blueprint $table) {
           $table->dropColumn('order_created_at');
           $table->dropColumn('order_approved_at');
           $table->dropColumn('order_cancelled_at');
        });
        Schema::table('tesco_tsl1_orders', function (Blueprint $table) {
           $table->dropColumn('order_created_at');
           $table->dropColumn('order_approved_at');
           $table->dropColumn('order_cancelled_at');
        });
        Schema::table('tesco_tcl4_orders', function (Blueprint $table) {
           $table->dropColumn('order_created_at');
           $table->dropColumn('order_approved_at');
           $table->dropColumn('order_cancelled_at');
        });
        Schema::table('tesco_tsl3_orders', function (Blueprint $table) {
           $table->dropColumn('edit_permission');
        });
        Schema::table('tesco_thl1_orders', function (Blueprint $table) {
           $table->dropColumn('order_created_at');
           $table->dropColumn('order_approved_at');
           $table->dropColumn('order_cancelled_at');
        });
        Schema::table('tesco_thl3_orders', function (Blueprint $table) {
           $table->dropColumn('order_created_at');
           $table->dropColumn('order_approved_at');
           $table->dropColumn('order_cancelled_at');
        });
    }
}
