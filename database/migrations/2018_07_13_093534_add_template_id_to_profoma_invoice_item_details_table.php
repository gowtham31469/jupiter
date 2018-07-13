<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTemplateIdToProfomaInvoiceItemDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profoma_invoice_item_details', function (Blueprint $table) {
            $table->integer('template_id')->unsigned()->after('pi_id');
            $table->string('ref_no')->after('material');
            $table->foreign('template_id')->references('id')->on('tesco_item_master')->onDelete('cascade');
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
            $table->dropColumn('template_id');
            $table->dropColumn('ref_no');
        });
    }
}
