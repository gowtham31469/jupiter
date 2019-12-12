<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTescoMetadataSupplierIdInTescoOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_tcl1_orders', function (Blueprint $table) {
            $table->integer('tesco_metadata_supplier_id')->unsigned()->after('tesco_supplier_id');
            $table->foreign('tesco_metadata_supplier_id')->references('id')->on('tesco_suppliers');
        });

        Schema::table('tesco_tcl2_orders', function (Blueprint $table) {
            $table->integer('tesco_metadata_supplier_id')->unsigned()->after('tesco_supplier_id');
            $table->foreign('tesco_metadata_supplier_id')->references('id')->on('tesco_suppliers');
        });

        Schema::table('tesco_tsl1_orders', function (Blueprint $table) {
            $table->integer('tesco_metadata_supplier_id')->unsigned()->after('tesco_supplier_id');
            $table->foreign('tesco_metadata_supplier_id')->references('id')->on('tesco_suppliers');
        });

        Schema::table('tesco_tcl4_orders', function (Blueprint $table) {
            $table->integer('tesco_metadata_supplier_id')->unsigned()->after('tesco_supplier_id');
            $table->foreign('tesco_metadata_supplier_id')->references('id')->on('tesco_suppliers');
        });

        Schema::table('tesco_tsl3_orders', function (Blueprint $table) {
            $table->integer('tesco_metadata_supplier_id')->unsigned()->after('tesco_supplier_id');
            $table->foreign('tesco_metadata_supplier_id')->references('id')->on('tesco_suppliers');
        });

        Schema::table('tesco_thl1_orders', function (Blueprint $table) {
            $table->integer('tesco_metadata_supplier_id')->unsigned()->after('tesco_supplier_id');
            $table->foreign('tesco_metadata_supplier_id')->references('id')->on('tesco_suppliers');
        });

        Schema::table('tesco_thl3_orders', function (Blueprint $table) {
            $table->integer('tesco_metadata_supplier_id')->unsigned()->after('tesco_supplier_id');
            $table->foreign('tesco_metadata_supplier_id')->references('id')->on('tesco_suppliers');
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
           $table->dropForeign('tesco_tcl1_orders_tesco_metadata_supplier_id_foreign');
           $table->dropColumn('tesco_metadata_supplier_id');
        });
        Schema::table('tesco_tcl2_orders', function (Blueprint $table) {
           $table->dropForeign('tesco_tcl2_orders_tesco_metadata_supplier_id_foreign');
           $table->dropColumn('tesco_metadata_supplier_id');
        });
        Schema::table('tesco_tsl1_orders', function (Blueprint $table) {
           $table->dropForeign('tesco_tsl1_orders_tesco_metadata_supplier_id_foreign');
           $table->dropColumn('tesco_metadata_supplier_id');
        });
        Schema::table('tesco_tcl4_orders', function (Blueprint $table) {
           $table->dropForeign('tesco_tcl4_orders_tesco_metadata_supplier_id_foreign');
           $table->dropColumn('tesco_metadata_supplier_id');
        });
        Schema::table('tesco_tsl3_orders', function (Blueprint $table) {
           $table->dropForeign('tesco_tsl3_orders_tesco_metadata_supplier_id_foreign');
           $table->dropColumn('tesco_metadata_supplier_id');
        });
        Schema::table('tesco_thl1_orders', function (Blueprint $table) {
           $table->dropForeign('tesco_thl1_orders_tesco_metadata_supplier_id_foreign');
           $table->dropColumn('tesco_metadata_supplier_id');
        });
        Schema::table('tesco_thl3_orders', function (Blueprint $table) {
           $table->dropForeign('tesco_thl3_orders_tesco_metadata_supplier_id_foreign');
           $table->dropColumn('tesco_metadata_supplier_id');
        });
        
    }
}
