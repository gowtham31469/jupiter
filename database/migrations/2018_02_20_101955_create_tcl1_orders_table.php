<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTcl1OrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tesco_tcl1_orders', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('order_id');
            $table->string('zone');
            $table->string('template_type');    
            $table->bigInteger('po_number');
            $table->string('uk_style_ref');
            $table->string('ce_style_ref');
            $table->string('season_code');
            $table->mediumText('short_description');
            $table->string('eqos_site_code');
            $table->date('manufacture_date');
            $table->integer('tesco_department_id')->unsigned();
            $table->integer('tesco_brand_id')->unsigned();
            $table->integer('tesco_supplier_id')->unsigned();
            $table->boolean('rfid_compliant')->default('0');
            $table->boolean('tag_at_source')->default('0');
            $table->string('order_status')->nullable();
            $table->string('purchase_order_no')->nullable();
            $table->string('po_file')->nullable();
            $table->string('po_status')->nullable();
            $table->string('pi_status')->nullable();
            $table->timestamps();
            $table->foreign('tesco_department_id')->references('id')->on('tesco_department')->onDelete('cascade');
            $table->foreign('tesco_brand_id')->references('id')->on('tesco_brand')->onDelete('cascade');
            $table->foreign('tesco_supplier_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tesco_tcl1_orders');
    }
}
