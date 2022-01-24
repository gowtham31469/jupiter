<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSkPriceToTescoSwingTicketOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_swingticket_orders', function (Blueprint $table) {
            $table->decimal('sk_selling_price', 7, 2)->nullable(true)->after('24_char_desc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_swingticket_orders', function (Blueprint $table) {
            $table->dropColumn('sk_selling_price');
        });
    }
}
