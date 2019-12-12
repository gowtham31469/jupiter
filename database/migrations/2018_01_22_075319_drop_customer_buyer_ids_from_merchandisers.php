<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropCustomerBuyerIdsFromMerchandisers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchandisers', function (Blueprint $table) {
            //$table->dropForeign('merchandisers_buyer_id_foreign');
            $table->dropColumn('buyer_id');
            //$table->dropForeign('merchandisers_customer_id_foreign');
            $table->dropColumn('customer_id');
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
            $table->addColumn('buyer_id');
            $table->foreign('buyer_id')->references('id')->on('buyers');
            $table->addColumn('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
        });
    }
}
