<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToOrchestraParcelTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_parcel_details', function (Blueprint $table) {
            $table->index(['UVC']);
            $table->index(['BuyerPONo']);
            $table->index(['SKU_Code']);
            $table->index(['Po_Change_Date']);

            $table->index(['UVC', 'BuyerPONo']);
            $table->index(['UVC', 'BuyerPONo', 'SKU_Code']);
            $table->index(['UVC', 'BuyerPONo', 'SKU_Code', 'Po_Change_Date']);
        });

        Schema::table('orchestra_parcel_quantity_details', function (Blueprint $table) {
            $table->index(['UVC_TCO']);
            $table->index(['BuyerPONo']);
            $table->index(['Country_Code']);
            $table->index(['Last_Change_Date']);

            $table->index(['UVC_TCO', 'BuyerPONo']);
            $table->index(['UVC_TCO', 'BuyerPONo', 'Country_Code']);
            $table->index(['UVC_TCO', 'BuyerPONo', 'Last_Change_Date', 'Country_Code']);
        });

        Schema::table('temp_orchestra_parcel_details', function (Blueprint $table) {
            $table->index(['UVC']);
            $table->index(['BuyerPONo']);
            $table->index(['SKU_Code']);
            $table->index(['Po_Change_Date']);

            $table->index(['UVC', 'BuyerPONo']);
            $table->index(['UVC', 'BuyerPONo', 'SKU_Code']);
            $table->index(['UVC', 'BuyerPONo', 'SKU_Code', 'Po_Change_Date']);
        });

        Schema::table('temp_orchestra_parcel_quantity_details', function (Blueprint $table) {
            $table->index(['UVC_TCO']);
            $table->index(['BuyerPONo']);
            $table->index(['Country_Code']);
            $table->index(['Last_Change_Date']);

            $table->index(['UVC_TCO', 'BuyerPONo']);
            $table->index(['UVC_TCO', 'BuyerPONo', 'Country_Code']);
            $table->index(['UVC_TCO', 'BuyerPONo', 'Last_Change_Date', 'Country_Code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_parcel_details', function (Blueprint $table) {
            $table->dropIndex(['UVC']);
            $table->dropIndex(['BuyerPONo']);
            $table->dropIndex(['SKU_Code']);
            $table->dropIndex(['Po_Change_Date']);

            $table->dropIndex(['UVC', 'BuyerPONo']);
            $table->dropIndex(['UVC', 'BuyerPONo', 'SKU_Code']);
            $table->dropIndex(['UVC', 'BuyerPONo', 'SKU_Code', 'Po_Change_Date']);
        });

        Schema::table('orchestra_parcel_quantity_details', function (Blueprint $table) {
            $table->dropIndex(['UVC_TCO']);
            $table->dropIndex(['BuyerPONo']);
            $table->dropIndex(['Country_Code']);
            $table->dropIndex(['Last_Change_Date']);

            $table->dropIndex(['UVC_TCO', 'BuyerPONo']);
            $table->dropIndex(['UVC_TCO', 'BuyerPONo', 'Country_Code']);
            $table->dropIndex(['UVC_TCO', 'BuyerPONo', 'Last_Change_Date', 'Country_Code']);
        });

        Schema::table('temp_orchestra_parcel_details', function (Blueprint $table) {
            $table->dropIndex(['UVC']);
            $table->dropIndex(['BuyerPONo']);
            $table->dropIndex(['SKU_Code']);
            $table->dropIndex(['Po_Change_Date']);

            $table->dropIndex(['UVC', 'BuyerPONo']);
            $table->dropIndex(['UVC', 'BuyerPONo', 'SKU_Code']);
            $table->dropIndex(['UVC', 'BuyerPONo', 'SKU_Code', 'Po_Change_Date']);
        });

        Schema::table('temp_orchestra_parcel_quantity_details', function (Blueprint $table) {
            $table->dropIndex(['UVC_TCO']);
            $table->dropIndex(['BuyerPONo']);
            $table->dropIndex(['Country_Code']);
            $table->dropIndex(['Last_Change_Date']);

            $table->dropIndex(['UVC_TCO', 'BuyerPONo']);
            $table->dropIndex(['UVC_TCO', 'BuyerPONo', 'Country_Code']);
            $table->dropIndex(['UVC_TCO', 'BuyerPONo', 'Last_Change_Date', 'Country_Code']);
        });
    }
}
