<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeNotNullConstraintsInErpOrderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('erp_order_details', function (Blueprint $table) {
            $table->string('style_no')->nullable(true)->change();
            $table->string('ocn_number')->nullable(true)->change();
            $table->string('buyer_po_number')->nullable(true)->change();
            $table->string('zone')->nullable(true)->change();
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
            $table->string('style_no')->nullable(false)->change();
            $table->string('ocn_number')->nullable(false)->change();
            $table->string('buyer_po_number')->nullable(false)->change();
            $table->string('zone')->nullable(false)->change();
        });
    }
}
