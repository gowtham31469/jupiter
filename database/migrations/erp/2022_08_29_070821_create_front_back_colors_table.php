<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrontBackColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_front_colors_list', function (Blueprint $table) {
            $table->increments('id');

            $table->bigInteger('template_board_color_id')->nullable(true)->index();

            $table->string('front_color_text')->nullable(true);

            $table->foreign('template_board_color_id')->references('id')->on('template_board_color_mapping')->onDelete('cascade');

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
        Schema::dropIfExists('template_front_colors_list');
    }
}
