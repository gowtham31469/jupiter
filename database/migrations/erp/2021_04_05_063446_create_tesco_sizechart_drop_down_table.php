<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTescoSizechartDropDownTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teso_size_chart_drop_down', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tesco_size_department_id')->unsigned();
            $table->string('name')->nullable();
            $table->boolean('height')->default(false);
            $table->boolean('weight')->default(false);
            $table->boolean('chest')->default(false);
            $table->boolean('bust')->default(false);
            $table->boolean('waist')->default(false);
            $table->timestamps();
            $table->foreign('tesco_size_department_id')->references('id')->on('tesco_size_department')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teso_size_chart_drop_down');
    }
}
