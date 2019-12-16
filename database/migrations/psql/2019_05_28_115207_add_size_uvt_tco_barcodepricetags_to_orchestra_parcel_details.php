<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSizeUvtTcoBarcodepricetagsToOrchestraParcelDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_parcel_details', function (Blueprint $table) {
            $table->string('UVC_TCO')->nullable();
            $table->string('Size')->nullable();
            $table->string('BarcodeForPricesTags')->nullable();
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
            $table->dropColumn('UVC_TCO');
            $table->dropColumn('Size');
            $table->dropColumn('BarcodeForPricesTags');
        });
    }
}
