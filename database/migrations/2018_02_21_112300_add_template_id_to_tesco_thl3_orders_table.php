<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTemplateIdToTescoThl3OrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_thl3_orders', function (Blueprint $table) {
            $table->integer('template_id')->unsigned()->after('id');
            $table->foreign('template_id')->references('id')->on('tesco_item_master')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_thl3_orders', function (Blueprint $table) {
            $table->dropColumn('template_id');
        });
    }
}
