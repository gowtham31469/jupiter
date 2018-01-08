<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMerchandiserToOrchestraWashCareLabelOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_wash_care_label_orders', function (Blueprint $table) {
             $table->integer('merchandisers_id')->unsigned()->after('uvc');
             $table->foreign('merchandisers_id')->references('id')->on('merchandisers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_wash_care_label_orders', function (Blueprint $table) {
            //
        });
    }
}
