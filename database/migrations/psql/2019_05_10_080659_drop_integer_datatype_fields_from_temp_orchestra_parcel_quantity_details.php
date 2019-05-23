<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropIntegerDatatypeFieldsFromTempOrchestraParcelQuantityDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_orchestra_parcel_quantity_details', function (Blueprint $table) {
            $table->dropColumn('EditionNumber');
            $table->dropColumn('VersionNumber');
            $table->dropColumn('Quantity');
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
            $table->integer('EditionNumber')->nullable();
            $table->integer('VersionNumber')->nullable();
            $table->integer('Quantity')->nullable();
        });
    }
}