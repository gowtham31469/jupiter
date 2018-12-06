<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveHsnCodeToInvoiceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_details', function (Blueprint $table) {
           $table->dropColumn('hsn_code');
        });

        Schema::table('invoice_details', function (Blueprint $table) {
           $table->integer('hsn_code')->default(0);
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
            $table->addColumn('hsn_code');
        });
        Schema::table('invoice_details', function (Blueprint $table) {
            $table->dropColumn('hsn_code');
        });
    }
}
