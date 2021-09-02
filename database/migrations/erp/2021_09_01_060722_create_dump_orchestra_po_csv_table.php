<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDumpOrchestraPoCsvTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dump_orchestra_po_csv', function (Blueprint $table) {
			$table->string('PO')->nullable()->default(null);
			$table->string('PO LINE NUMBER')->nullable()->default(null);
			$table->string('PO LINE SUFFIX')->nullable()->default(null);
			$table->string('PO STATUS CODE')->nullable()->default(null);
			$table->string('PO STATUS CODE DESCRIPTION')->nullable()->default(null);
			$table->string('SEASON CODE')->nullable()->default(null);
			$table->string('SKU CODE')->nullable()->default(null);
			$table->string('STYLE CODE')->nullable()->default(null);
			$table->string('REFERENCE CODE')->nullable()->default(null);
			$table->string('ACTIVITY CODE')->nullable()->default(null);
			$table->string('ACTIVITY LABEL')->nullable()->default(null);
			$table->string('UNIVERSE CODE')->nullable()->default(null);
			$table->string('UNIVERSE LABEL')->nullable()->default(null);
			$table->string('DEPARTMENT CODE')->nullable()->default(null);
			$table->string('DEPARTMENT LABEL')->nullable()->default(null);
			$table->string('FAMILY CODE')->nullable()->default(null);
			$table->string('FAMILY LABEL')->nullable()->default(null);
			$table->string('SUB FAMILY CODE')->nullable()->default(null);
			$table->string('SUB FAMILY LABEL')->nullable()->default(null);
			$table->string('THEME CODE')->nullable()->default(null);
			$table->string('THEME LABEL')->nullable()->default(null);
			$table->string('BRAND CODE')->nullable()->default(null);
			$table->string('BRAND LABEL')->nullable()->default(null);
			$table->string('PRODUCT NAME')->nullable()->default(null);
			$table->string('DESCRIPTION FR')->nullable()->default(null);
			$table->string('BUYER EMAIL')->nullable()->default(null);
			$table->string('AGENT CODE')->nullable()->default(null);
			$table->string('AGENT NAME')->nullable()->default(null);
			$table->string('AGENT EMAIL')->nullable()->default(null);
			$table->string('AGENT ADDRESS')->nullable()->default(null);
			$table->string('AGENT COUNTRY')->nullable()->default(null);
			$table->string('SUPPLIER CODE')->nullable()->default(null);
			$table->string('SUPPLIER NAME')->nullable()->default(null);
			$table->string('SUPPLIER EMAIL')->nullable()->default(null);
			$table->string('SUPPLIER ADDRESS')->nullable()->default(null);
			$table->string('SUPPLIER COUNTRY')->nullable()->default(null);
			$table->string('FACTORY CODE')->nullable()->default(null);
			$table->string('FACTORY NAME')->nullable()->default(null);
			$table->string('FACTORY EMAIL')->nullable()->default(null);
			$table->string('FACTORY ADDRESS')->nullable()->default(null);
			$table->string('MADE IN CODE')->nullable()->default(null);
			$table->string('MADE IN LABEL')->nullable()->default(null);
			$table->string('FIRST ETD SHIPMENT DATE')->nullable()->default(null);	
			$table->string('SHIPMENT  DESTINATION CODE')->nullable()->default(null);
			$table->string('SHIPMENT  DESTINATION LABEL')->nullable()->default(null);	
			$table->string('INCOTERMS CODE')->nullable()->default(null);
			$table->string('INCOTERMS CITY LABEL')->nullable()->default(null);	
			$table->string('PO PACKING TYPE')->nullable()->default(null);
			$table->string('SKU BARCODE')->nullable()->default(null);
			$table->string('SET_')->nullable()->default(null);
			$table->string('NUMBER OF PIECES')->nullable()->default(null);
			$table->string('COLOR CODE')->nullable()->default(null);
			$table->string('COLOR LABEL')->nullable()->default(null);
			$table->string('STANDARD CODE')->nullable()->default(null);
			$table->string('STANDARD BARCODE')->nullable()->default(null);
			$table->string('SIZE RANGE CODE')->nullable()->default(null);
			$table->string('SIZE RANGE LABEL')->nullable()->default(null);
			$table->string('SIZE CODE')->nullable()->default(null);
			$table->string('SIZE LABEL')->nullable()->default(null);
			$table->string('SIZE BREAKDOWN')->nullable()->default(null);
			$table->string('TOTAL STANDARD-SOLID PARCEL NUMBER')->nullable()->default(null);
			$table->string('QTY PER SKU-PO')->nullable()->default(null);
			$table->string('PRODUCT TYPE')->nullable()->default(null);
			$table->string('LICENCE CODE')->nullable()->default(null);
			$table->string('LICENCE LABEL')->nullable()->default(null);
			$table->string('LICENCE CHARACTER CODE')->nullable()->default(null);	
			$table->string('LICENCE CHARACTER LABEL')->nullable()->default(null);	
			$table->string('START PRINT DATE')->nullable()->default(null);
			$table->string('COMPOSITION LABEL')->nullable()->default(null);	
			$table->string('PO CHANGE DATE')->nullable()->default(null);
			$table->string('PURCHASE PRICE')->nullable()->default(null);
			$table->string('CURRENCY')->nullable()->default(null);
			$table->string('PO AMOUNT')->nullable()->default(null);
			$table->string('TYPOLOGIE')->nullable()->default(null);
			$table->string('PAYMENT CONDITION CODE')->nullable()->default(null);	
			$table->string('PAYMENT TERMS')->nullable()->default(null);
			$table->string('PAYMENT TERMS LABEL')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dump_orchestra_po_csv');
    }
}
