<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaxToTescoTaxCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_tax_code', function (Blueprint $table) {
           $table->double('tax')->after('tax_type')->nullable();
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_tax_code', function (Blueprint $table) {
           $table->dropColumn('tax');
        });
    }
}
