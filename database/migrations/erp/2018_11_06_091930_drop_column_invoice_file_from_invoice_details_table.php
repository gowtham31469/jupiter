<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnInvoiceFileFromInvoiceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice', function (Blueprint $table) {
            $table->dropColumn('invoice_file');
        });
        Schema::table('invoice', function (Blueprint $table) {
            $table->string('invoice_file')->default('null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice', function (Blueprint $table) {
            $table->addColumn('invoice_file');
        });
        Schema::table('invoice', function (Blueprint $table) {
            $table->dropColumn('invoice_file');
        });
    }
}
