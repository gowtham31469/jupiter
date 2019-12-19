<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePiFileTypeInProformaInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profoma_invoices', function (Blueprint $table) {
            $table->string('pi_file')->nullable()->change();	
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profoma_invoices', function (Blueprint $table) {
            $table->integer('pi_file')->nullable()->change();
        });
    }
}
