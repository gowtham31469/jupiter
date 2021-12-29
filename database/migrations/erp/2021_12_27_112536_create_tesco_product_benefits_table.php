<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTescoProductBenefitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tesco_product_benefits', function (Blueprint $table) {
            $table->increments('id');
            $table->string('english');
            $table->string('polish')->nullable(true);
            $table->string('czech')->nullable(true);
            $table->string('slovak')->nullable(true);
            $table->string('hungarian')->nullable(true);
            $table->boolean('is_enabled')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tesco_product_benefits');
    }
}
