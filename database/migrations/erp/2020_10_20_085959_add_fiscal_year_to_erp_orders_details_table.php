<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFiscalYearToErpOrdersDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('erp_order_details', function (Blueprint $table) {
            $table->date('fiscal_year_start')->nullable()->index();
            $table->date('fiscal_year_end')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('erp_order_details', function (Blueprint $table) {
            $table->dropIndex('erp_order_details_fiscal_year_start_index');
            $table->dropIndex('erp_order_details_fiscal_year_end_index');
            $table->dropColumn('fiscal_year_start');
            $table->dropColumn('fiscal_year_end');
        });
    }
}
