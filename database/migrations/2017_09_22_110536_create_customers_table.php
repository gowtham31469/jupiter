<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
          $table->increments('id');
          $table->string('company_name');
          $table->string('factory_code');
          $table->string('contact_person');
          $table->string('contact_email');
          $table->integer('phone_no');
          $table->string('courier_choice');
          $table->string('account_no');
          $table->string('tin_number');
          $table->string('pan_number');
          $table->string('email')->unique();
          $table->string('password');
          $table->rememberToken();
          $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer');
    }
}
