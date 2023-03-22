<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsQrCodeRequiresToOrchestraItemMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_item_master', function (Blueprint $table) {
            $table->tinyInteger('is_qrcode_required')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_item_master', function (Blueprint $table) {
            $table->dropColumn('is_qrcode_required');
        });
    }
}
