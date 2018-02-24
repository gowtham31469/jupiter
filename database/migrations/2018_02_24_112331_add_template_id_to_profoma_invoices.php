<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTemplateIdToProfomaInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profoma_invoices', function (Blueprint $table) {
            $table->integer('template_id')->unsigned()->after('customer_id');
            $table->string('uvc_style_no')->after('template_id');
            $table->string('buyer_po_no')->after('template_id');
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
        Schema::table('profoma_invoices', function (Blueprint $table) {
            //
        });
    }
}
