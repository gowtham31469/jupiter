<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ColumnModifyOrchestraWashCareLabelOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_wash_care_label_orders', function (Blueprint $table) {
            $table->dropColumn('care_instruction_id');
            
        });
	Schema::table('orchestra_wash_care_label_orders', function (Blueprint $table) {
           $table->string('care_instruction_id');
            
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
