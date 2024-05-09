<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTescoGarmentStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tesco_garment_statements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('english');
            $table->string('polish')->nullable(true);
            $table->string('czech')->nullable(true);
            $table->string('slovak')->nullable(true);
            $table->string('hungarian')->nullable(true);
            $table->boolean('is_enabled')->default('0');
            $table->unsignedInteger('template_id')->nullable(true)->index();
            $table->foreign('template_id')->references('id')->on('tesco_item_master')->onDelete('cascade');
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
        Schema::dropIfExists('tesco_garment_statements');
    }
}
