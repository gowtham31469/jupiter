<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrchestraWashCareLabelOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orchestra_wash_care_label_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uvc');
	    $table->integer('template_id');
 $table->integer('washing_id');
 $table->integer('whitening_id');
 $table->integer('dryer_id');
 $table->integer('ironing_id');
 $table->integer('cleaning_id');
 $table->integer('made_in_id');
 $table->integer('care_instruction_id');
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
        Schema::dropIfExists('orchestra_wash_care_label_orders');
    }
}
