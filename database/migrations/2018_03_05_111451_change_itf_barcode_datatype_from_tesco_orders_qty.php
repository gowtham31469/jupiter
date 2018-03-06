<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeItfBarcodeDatatypeFromTescoOrdersQty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('tesco_tcl1_orders_quanty', function (Blueprint $table) {
            $table->string('itf_barcode')->change();
            $table->string('ean_barcode')->change();
        });
        Schema::table('tesco_tcl2_orders_quanty', function (Blueprint $table) {
            $table->string('itf_barcode')->change();
            $table->string('ean_barcode')->change();
        });
        Schema::table('tesco_tsl1_orders_quanty', function (Blueprint $table) {
            $table->string('itf_barcode')->change();
            $table->string('ean_barcode')->change();
        });
        Schema::table('tesco_tcl4_orders_quanty', function (Blueprint $table) {
            $table->string('itf_barcode')->change();
            $table->string('ean_barcode')->change();
        });
        Schema::table('tesco_tsl3_orders_quanty', function (Blueprint $table) {
            $table->string('itf_barcode')->change();
            $table->string('ean_barcode')->change();
        });
        Schema::table('tesco_thl1_orders_quanty', function (Blueprint $table) {
            $table->string('itf_barcode')->change();
            $table->string('ean_barcode')->change();
        });
        Schema::table('tesco_thl3_orders_quanty', function (Blueprint $table) {
            $table->string('itf_barcode')->change();
            $table->string('ean_barcode')->change();
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
            $table->bigInteger('itf_barcode')->change();
            $table->bigInteger('ean_barcode')->change();
        });
       Schema::table('tesco_tcl2_orders_quanty', function (Blueprint $table) {
            $table->bigInteger('itf_barcode')->change();
            $table->bigInteger('ean_barcode')->change();
        });
       Schema::table('tesco_tsl1_orders_quanty', function (Blueprint $table) {
            $table->bigInteger('itf_barcode')->change();
            $table->bigInteger('ean_barcode')->change();
        });
       Schema::table('tesco_tcl4_orders_quanty', function (Blueprint $table) {
            $table->bigInteger('itf_barcode')->change();
            $table->bigInteger('ean_barcode')->change();
        });
       Schema::table('tesco_tsl3_orders_quanty', function (Blueprint $table) {
            $table->bigInteger('itf_barcode')->change();
            $table->bigInteger('ean_barcode')->change();
        });
       Schema::table('tesco_thl1_orders_quanty', function (Blueprint $table) {
            $table->bigInteger('itf_barcode')->change();
            $table->bigInteger('ean_barcode')->change();
        });
       Schema::table('tesco_thl3_orders_quanty', function (Blueprint $table) {
            $table->bigInteger('itf_barcode')->change();
            $table->bigInteger('ean_barcode')->change();
        });
    }
}
