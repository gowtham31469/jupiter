<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPoStatusDescriptionToOrchestraParcelQuantityDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_parcel_quantity_details', function (Blueprint $table) {
            $table->string('Po_Status_Code_Description')->nullable();
            $table->string('Po_Status_Code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_parcel_quantity_details', function (Blueprint $table) {
            $table->dropColumn('Po_Status_Code_Description');
            $table->dropColumn('Po_Status_Code');
        });
    }
}
