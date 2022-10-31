<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateBoardColorMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_board_color_mapping', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('template_id')->nullable(true)->index();
            $table->string('buyer_template_table')->nullable(true);
            $table->boolean('color_bar')->default(false);
            $table->boolean('print_spec_bar')->default(false);
            $table->boolean('tone_bar')->default(false);
            $table->bigInteger('board_category_id')->nullable(true)->index();
            $table->string('board_category_text')->nullable(true);
            $table->bigInteger('board_id')->nullable(true)->index();
            $table->string('board_text')->nullable(true);
            $table->boolean('fsc_non_fsc')->nullable(true)->default(false);
            $table->integer('gsm_value')->nullable(true);
            $table->integer('thickness')->nullable(true);
            $table->integer('board_size_length')->nullable(true);
            $table->integer('board_size_width')->nullable(true);
            $table->integer('print_size_length')->nullable(true);
            $table->integer('print_size_width')->nullable(true);
            $table->integer('design_size_length')->nullable(true);
            $table->integer('design_size_width')->nullable(true);
            $table->string('cut_as')->nullable(true);
            $table->integer('front_color_count')->nullable(true);
            $table->integer('back_color_count')->nullable(true);
            $table->string('back_color_text')->nullable(true);
            $table->integer('repeats')->nullable(true);
            $table->integer('no_of_plates')->nullable(true);
            $table->boolean('cutting')->nullable(true)->default(false);
            $table->boolean('printing')->nullable(true)->default(false);
            $table->string('coating')->nullable(true);
            $table->boolean('uv')->nullable(true)->default(false);
            $table->boolean('lamination')->nullable(true)->default(false);
            $table->string('lamination_spec')->nullable(true);
            $table->boolean('pasting')->nullable(true)->default(false);
            $table->string('pasting_board')->nullable(true);
            $table->integer('pasting_gsm')->nullable(true);
            $table->boolean('die_cutting')->nullable(true)->default(false);
            $table->boolean('window_patch')->nullable(true)->default(false);
            $table->integer('window_patch_film')->nullable(true);
            $table->boolean('folder_gluer')->nullable(true)->default(false);
            $table->boolean('embossing')->nullable(true)->default(false);
            $table->boolean('debossing')->nullable(true)->default(false);
            $table->boolean('perforation')->nullable(true)->default(false);
            $table->boolean('hole')->nullable(true)->default(false);
            $table->integer('hole_size')->nullable(true);
            $table->boolean('eyelet')->nullable(true)->default(false);
            $table->string('eyelet_spec')->nullable(true);
            $table->boolean('string_required')->nullable(true)->default(false);
            $table->string('string_spec')->nullable(true);
            $table->boolean('foil')->nullable(true)->default(false);
            $table->timestamps();

            $table->foreign('board_category_id')->references('id')->on('board_category')->onDelete('cascade');
            $table->foreign('board_id')->references('id')->on('boards')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('template_board_color_mapping');
    }
}
