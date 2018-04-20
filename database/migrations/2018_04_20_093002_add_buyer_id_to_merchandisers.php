<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBuyerIdToMerchandisers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchandisers', function (Blueprint $table) {
            $table->integer('buyer_id')->unsigned()->nullable()->after('is_active');
            $table->foreign('buyer_id')->references('id')->on('buyers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchandisers', function (Blueprint $table) {
            $table->dropForeign('merchandisers_buyer_id_foreign');
            $table->dropColumn('buyer_id');
        });
    }
}
