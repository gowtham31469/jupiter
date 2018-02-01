<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFinalQtyToOrchestraSpecialTagOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_special_tag_orders', function (Blueprint $table) {
             $table->integer('final_qty')->nullable()->after('po_file');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_special_tag_orders', function (Blueprint $table) {
            //
        });
    }
}
