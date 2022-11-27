<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpectrraJobcardMatchToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spectrra_jobcard_match_to_tables', function (Blueprint $table) {
            $table->increments('id');

            $table->bigInteger('spectrra_jobcard_main_id')->nullable(true)->index();

            $table->string('color_match_text')->nullable(true);

            $table->foreign('spectrra_jobcard_main_id')->references('id')->on('spectrra_jobcard_main_tables')->onDelete('cascade');

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
        Schema::dropIfExists('spectrra_jobcard_match_to_tables');
    }
}
