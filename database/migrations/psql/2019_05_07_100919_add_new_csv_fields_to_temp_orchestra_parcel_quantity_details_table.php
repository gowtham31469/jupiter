<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewCsvFieldsToTempOrchestraParcelQuantityDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_orchestra_parcel_quantity_details', function (Blueprint $table) {
            $table->string('Country_Code')->nullable();
            $table->string('Season_Code')->nullable();
            $table->string('Currency')->nullable();
            $table->string('Price')->nullable();
            $table->string('Club_Price')->nullable();
            $table->string('Nom_Appel')->nullable();
            $table->integer('Last_Change_Date')->nullable();
            $table->string('Supplier_Code')->nullable();
            $table->string('Supplier_Name')->nullable();
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
            $table->dropColumn('Country_Code');
            $table->dropColumn('Season_Code');
            $table->dropColumn('Currency');
            $table->dropColumn('Price');
            $table->dropColumn('Club_Price');
            $table->dropColumn('Nom_Appel');
            $table->dropColumn('Last_Change_Date');
            $table->dropColumn('Supplier_Code');
            $table->dropColumn('Supplier_Name');
        });
    }
}
