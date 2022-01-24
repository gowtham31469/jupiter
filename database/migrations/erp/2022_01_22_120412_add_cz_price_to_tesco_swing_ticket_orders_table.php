<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCzPriceToTescoSwingTicketOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_swingticket_orders', function (Blueprint $table) {
            $table->decimal('cz_selling_price', 7, 2)->nullable(true)->after('24_char_desc');
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
            $table->dropColumn('cz_selling_price');
        });
    }
}
