<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOcnNumberPoUploaddateToTescoOrdersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_tcl1_orders', function (Blueprint $table) {
           $table->string('ocn_number')->after('is_order_count_empty')->nullable();
           $table->timestamp('po_uploaded_at')->after('po_status')->nullable();
       });

       Schema::table('tesco_tcl2_orders', function (Blueprint $table) {
           $table->string('ocn_number')->after('is_order_count_empty')->nullable();
           $table->timestamp('po_uploaded_at')->after('po_status')->nullable();
       });

       Schema::table('tesco_tsl1_orders', function (Blueprint $table) {
           $table->string('ocn_number')->after('is_order_count_empty')->nullable();
           $table->timestamp('po_uploaded_at')->after('po_status')->nullable();
       });

       Schema::table('tesco_tcl4_orders', function (Blueprint $table) {
           $table->string('ocn_number')->after('is_order_count_empty')->nullable();
           $table->timestamp('po_uploaded_at')->after('po_status')->nullable();
       });

       Schema::table('tesco_tsl3_orders', function (Blueprint $table) {
           $table->string('ocn_number')->after('is_order_count_empty')->nullable();
           $table->timestamp('po_uploaded_at')->after('po_status')->nullable();
       });

       Schema::table('tesco_thl1_orders', function (Blueprint $table) {
           $table->string('ocn_number')->after('is_order_count_empty')->nullable();
           $table->timestamp('po_uploaded_at')->after('po_status')->nullable();
       });

       Schema::table('tesco_thl3_orders', function (Blueprint $table) {
           $table->string('ocn_number')->after('is_order_count_empty')->nullable();
           $table->timestamp('po_uploaded_at')->after('po_status')->nullable();
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
           $table->dropColumn('ocn_number');
           $table->dropColumn('po_uploaded_at');
        });
        Schema::table('tesco_tcl2_orders', function (Blueprint $table) {
           $table->dropColumn('ocn_number');
           $table->dropColumn('po_uploaded_at');
        });
        Schema::table('tesco_tsl1_orders', function (Blueprint $table) {
           $table->dropColumn('ocn_number');
           $table->dropColumn('po_uploaded_at');
        });
        Schema::table('tesco_tcl4_orders', function (Blueprint $table) {
           $table->dropColumn('ocn_number');
           $table->dropColumn('po_uploaded_at');
        });
        Schema::table('tesco_tsl3_orders', function (Blueprint $table) {
           $table->dropColumn('ocn_number');
           $table->dropColumn('po_uploaded_at');
        });
        Schema::table('tesco_thl1_orders', function (Blueprint $table) {
           $table->dropColumn('ocn_number');
           $table->dropColumn('po_uploaded_at');
        });
        Schema::table('tesco_thl3_orders', function (Blueprint $table) {
           $table->dropColumn('ocn_number');
           $table->dropColumn('po_uploaded_at');
        });
    }
}
