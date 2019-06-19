<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('history_id')->unsigned();
            $table->foreign('history_id')->references('id')->on('history');
            $table->string('table_name');
            $table->integer('table_id');
            $table->integer('action_id')->unsigned();
            $table->foreign('action_id')->references('id')->on('actions');
            $table->integer('created_by');
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
        Schema::dropIfExists('event_history');
    }
}
