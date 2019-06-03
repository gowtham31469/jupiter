<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPoPackingTypeToOrchestraItemDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_item_details', function (Blueprint $table) {
            $table->string('Po_Packing_Type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_item_details', function (Blueprint $table) {
            $table->dropColumn('Po_Packing_Type');
        });
    }
}
