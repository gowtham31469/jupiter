<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBigintegerDatatypeFieldsToTempOrchestraParcelDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_orchestra_parcel_details', function (Blueprint $table) {
            $table->bigInteger('EditionNumber')->nullable();
            $table->bigInteger('VersionNumber')->nullable();
            $table->bigInteger('IsSTD')->nullable();
            $table->bigInteger('Quantity')->nullable();
            $table->text('SupplierAddress')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temp_orchestra_parcel_details', function (Blueprint $table) {
            $table->dropColumn('EditionNumber');
            $table->dropColumn('VersionNumber');
            $table->dropColumn('IsSTD');
            $table->dropColumn('Quantity');
            $table->dropColumn('SupplierAddress');
        });
    }
}
