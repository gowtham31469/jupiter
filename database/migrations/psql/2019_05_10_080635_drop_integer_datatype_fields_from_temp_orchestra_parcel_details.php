<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropIntegerDatatypeFieldsFromTempOrchestraParcelDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_orchestra_parcel_details', function (Blueprint $table) {
            $table->dropColumn('EditionNumber');
            $table->dropColumn('VersionNumber');
            $table->dropColumn('IsSTD');
            $table->dropColumn('Quantity');
            $table->dropColumn('SupplierAddress');
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
            $table->integer('EditionNumber')->nullable();
            $table->integer('VersionNumber')->nullable();
            $table->integer('IsSTD')->nullable();
            $table->integer('Quantity')->nullable();
            $table->string('SupplierAddress')->nullable();
        });
    }
}
