<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStringDatatypeFieldsToTempOrchestraParcelDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_orchestra_parcel_details', function (Blueprint $table) {
            $table->string('EditionNumber')->nullable();
            $table->string('VersionNumber')->nullable();
            $table->string('IsSTD')->nullable();
            $table->string('Quantity')->nullable();
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
