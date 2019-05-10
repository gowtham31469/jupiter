<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBigintegerDatatypeFieldsToOrchestraParcelQuantityDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_parcel_quantity_details', function (Blueprint $table) {
            $table->bigInteger('EditionNumber')->nullable();
            $table->bigInteger('VersionNumber')->nullable();
            $table->bigInteger('Quantity')->nullable();
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
            $table->dropColumn('EditionNumber');
            $table->dropColumn('VersionNumber');
            $table->dropColumn('Quantity');
        });
    }
}
