<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfomaInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profoma_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned();
            $table->integer('customer_id')->unsigned();
            $table->string('shipping_address');
            $table->string('billing_address');
            $table->integer('total_quantity');
            $table->double('sub_total');
            $table->double('tax');
            $table->double('bill_amount');
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profoma_invoices');
    }
}
