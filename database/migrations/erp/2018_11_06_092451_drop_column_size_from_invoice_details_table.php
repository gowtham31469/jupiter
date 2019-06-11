<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnSizeFromInvoiceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_details', function (Blueprint $table) {
            $table->dropColumn('size');
        });
        Schema::table('invoice_details', function (Blueprint $table) {
            $table->string('size')->default('null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_details', function (Blueprint $table) {
            $table->addColumn('size');
        });
        Schema::table('invoice_details', function (Blueprint $table) {
            $table->dropColumn('size');
        });
    }
}
