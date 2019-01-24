<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBuyerPoNumberToErpOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('erp_orders', function (Blueprint $table) {
           $table->dropColumn('buyer_po_number');
        });

        Schema::table('erp_order_details', function (Blueprint $table) {
             $table->string('buyer_po_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('erp_order_details', function (Blueprint $table) {
            $table->dropColumn('buyer_po_number');
        });
        Schema::table('erp_orders', function (Blueprint $table) {
            $table->addColumn('buyer_po_number');
        });
    }
}
