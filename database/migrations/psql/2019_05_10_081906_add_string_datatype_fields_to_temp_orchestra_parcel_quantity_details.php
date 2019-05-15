<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStringDatatypeFieldsToTempOrchestraParcelQuantityDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_orchestra_parcel_quantity_details', function (Blueprint $table) {
            $table->string('EditionNumber')->nullable();
            $table->string('VersionNumber')->nullable();
            $table->string('Quantity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temp_orchestra_parcel_quantity_details', function (Blueprint $table) {
            $table->dropColumn('EditionNumber');
            $table->dropColumn('VersionNumber');
            $table->dropColumn('Quantity');
        });
    }
}