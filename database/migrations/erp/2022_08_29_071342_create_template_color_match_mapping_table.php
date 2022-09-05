<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateColorMatchMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_color_match_mapping', function (Blueprint $table) {
            $table->increments('id');

            $table->bigInteger('template_board_color_id')->nullable(true)->index();

            $table->string('color_match_text')->nullable(true);

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
        Schema::dropIfExists('template_color_match_mapping');
    }
}
