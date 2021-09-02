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
			$table->string('SKU CODE')->nullable()->default(null);
			$table->string('SEASON CODE')->nullable()->default(null);
			$table->string('SKU BARCODE')->nullable()->default(null);
            $table->string('COUNTRY CODE')->nullable()->default(null);
			$table->string('CURRENCY')->nullable()->default(null);
			$table->string('PRICE')->nullable()->default(null);
			$table->string('CLUB PRICE')->nullable()->default(null);
			$table->string('NOM_APPEL')->nullable()->default(null);
			$table->string('SUPPLIER_CODE')->nullable()->default(null);
			$table->string('SUPPLIER_NAME')->nullable()->default(null);
			$table->string('LAST_CHANGE_DATE')->nullable()->default(null);
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
