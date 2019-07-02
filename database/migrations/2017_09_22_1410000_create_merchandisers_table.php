<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchandisersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::create('merchandisers', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('buyer_id')->unsigned();
          $table->integer('customer_id')->unsigned();
          $table->string('merchandiser_name');
          $table->string('email');
          $table->string('password');
          $table->string('merchandiser_address');
          $table->bigInteger('merchandiser_phone');
          $table->rememberToken();
          $table->timestamps();
          $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
          $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('merchandisers');
    }
}
