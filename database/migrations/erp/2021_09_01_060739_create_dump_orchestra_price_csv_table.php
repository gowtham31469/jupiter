<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDumpOrchestraPriceCsvTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dump_orchestra_price_csv', function (Blueprint $table) {
			$table->string('SKU CODE')->nullable();
			$table->string('SEASON CODE')->nullable();
			$table->string('SKU BARCODE')->nullable();
            $table->string('COUNTRY CODE')->nullable();
			$table->string('CURRENCY')->nullable();
			$table->string('PRICE')->nullable();
			$table->string('CLUB PRICE')->nullable();
			$table->string('NOM_APPEL')->nullable();
			$table->string('SUPPLIER_CODE')->nullable();
			$table->string('SUPPLIER_NAME')->nullable();
			$table->string('LAST_CHANGE_DATE')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dump_orchestra_price_csv');
    }
}
