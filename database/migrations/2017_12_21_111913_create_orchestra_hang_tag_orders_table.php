<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrchestraHangTagOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orchestra_hang_tag_orders', function (Blueprint $table) {
            $table->increments('id');
	    $table->string('uvc');
	    $table->integer('template_front_id');
            $table->integer('template_back_id');
 	    $table->date('delivery_date');
	    $table->longText('svg_path');
            $table->integer('status');
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
        Schema::dropIfExists('orchestra_hang_tag_orders');
    }
}
