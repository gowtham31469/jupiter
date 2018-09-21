<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaxToProfomaInvoiceItemDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profoma_invoice_item_details', function (Blueprint $table) {
            $table->double('tax')->after('unit_cost');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profoma_invoice_item_details', function (Blueprint $table) {
            $table->dropColumn('tax');
        });
    }
}
