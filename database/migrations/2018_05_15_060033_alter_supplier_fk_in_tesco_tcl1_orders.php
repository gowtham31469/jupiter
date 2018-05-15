<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSupplierFkInTescoTcl1Orders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_tcl1_orders', function (Blueprint $table) {
            $table->dropForeign('tesco_tcl1_orders_tesco_supplier_id_foreign');
            $table->foreign('tesco_supplier_id')->references('id')->on('tesco_suppliers');
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
            $table->dropForeign('tesco_tcl1_orders_tesco_supplier_id_foreign');
            $table->foreign('tesco_supplier_id')->references('id')->on('customers');
        });
    }
}
