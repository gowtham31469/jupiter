<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPoPackingTypeToTempOrchestraItemDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_orchestra_item_details', function (Blueprint $table) {
            $table->tinyInteger('Po_Packing_Type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temp_orchestra_item_details', function (Blueprint $table) {
            $table->dropColumn('Po_Packing_Type');
        });
    }
}
