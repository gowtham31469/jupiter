<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeNullConstraintsInProformaInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profoma_invoices', function (Blueprint $table) {
            $table->string('template_type')->nullable(true)->change();
            $table->string('buyer_po_no')->nullable(true)->change();
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
            $table->string('template_type')->nullable(false)->change();
            $table->string('buyer_po_no')->nullable(false)->change();
        });
    }
}
