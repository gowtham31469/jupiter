<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerContactPersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_contact_persons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned();
            $table->string('contact_person_name');
            $table->string('contact_person_email');
            $table->bigInteger('contact_person_phone');
            $table->boolean('default_contact_person');
            $table->timestamps();
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
        Schema::dropIfExists('customer_contact_persons');
    }
}
