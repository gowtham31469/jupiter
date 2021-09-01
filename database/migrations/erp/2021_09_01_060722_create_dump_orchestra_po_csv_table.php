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
			$table->string('PO')->nullable();
			$table->string('PO LINE NUMBER')->nullable();
			$table->string('PO LINE SUFFIX')->nullable();
			$table->string('PO STATUS CODE')->nullable();
			$table->string('PO STATUS CODE DESCRIPTION')->nullable();
			$table->string('SEASON CODE')->nullable();
			$table->string('SKU CODE')->nullable();
			$table->string('STYLE CODE')->nullable();
			$table->string('REFERENCE CODE')->nullable();
			$table->string('ACTIVITY CODE')->nullable();
			$table->string('ACTIVITY LABEL')->nullable();
			$table->string('UNIVERSE CODE')->nullable();
			$table->string('UNIVERSE LABEL')->nullable();
			$table->string('DEPARTMENT CODE')->nullable();
			$table->string('DEPARTMENT LABEL')->nullable();
			$table->string('FAMILY CODE')->nullable();
			$table->string('FAMILY LABEL')->nullable();
			$table->string('SUB FAMILY CODE')->nullable();
			$table->string('SUB FAMILY LABEL')->nullable();
			$table->string('THEME CODE')->nullable();
			$table->string('THEME LABEL')->nullable();
			$table->string('BRAND CODE')->nullable();
			$table->string('BRAND LABEL')->nullable();
			$table->string('PRODUCT NAME')->nullable();
			$table->string('DESCRIPTION FR')->nullable();
			$table->string('BUYER EMAIL')->nullable();
			$table->string('AGENT CODE')->nullable();
			$table->string('AGENT NAME')->nullable();
			$table->string('AGENT EMAIL')->nullable();
			$table->string('AGENT ADDRESS')->nullable();
			$table->string('AGENT COUNTRY')->nullable();
			$table->string('SUPPLIER CODE')->nullable();
			$table->string('SUPPLIER NAME')->nullable();
			$table->string('SUPPLIER EMAIL')->nullable();
			$table->string('SUPPLIER ADDRESS')->nullable();
			$table->string('SUPPLIER COUNTRY')->nullable();
			$table->string('FACTORY CODE')->nullable();
			$table->string('FACTORY NAME')->nullable();
			$table->string('FACTORY EMAIL')->nullable();
			$table->string('FACTORY ADDRESS')->nullable();
			$table->string('MADE IN CODE')->nullable();
			$table->string('MADE IN LABEL')->nullable();
			$table->string('FIRST ETD SHIPMENT DATE')->nullable();	
			$table->string('SHIPMENT  DESTINATION CODE')->nullable();
			$table->string('SHIPMENT  DESTINATION LABEL')->nullable();	
			$table->string('INCOTERMS CODE')->nullable();
			$table->string('INCOTERMS CITY LABEL')->nullable();	
			$table->string('PO PACKING TYPE')->nullable();
			$table->string('SKU BARCODE')->nullable();
			$table->string('SET_')->nullable();
			$table->string('NUMBER OF PIECES')->nullable();
			$table->string('COLOR CODE')->nullable();
			$table->string('COLOR LABEL')->nullable();
			$table->string('STANDARD CODE')->nullable();
			$table->string('STANDARD BARCODE')->nullable();
			$table->string('SIZE RANGE CODE')->nullable();
			$table->string('SIZE RANGE LABEL')->nullable();
			$table->string('SIZE CODE')->nullable();
			$table->string('SIZE LABEL')->nullable();
			$table->string('SIZE BREAKDOWN')->nullable();
			$table->string('TOTAL STANDARD-SOLID PARCEL NUMBER')->nullable();
			$table->string('QTY PER SKU-PO')->nullable();
			$table->string('PRODUCT TYPE')->nullable();
			$table->string('LICENCE CODE')->nullable();
			$table->string('LICENCE LABEL')->nullable();
			$table->string('LICENCE CHARACTER CODE')->nullable();	
			$table->string('LICENCE CHARACTER LABEL')->nullable();	
			$table->string('START PRINT DATE')->nullable();
			$table->string('COMPOSITION LABEL')->nullable();	
			$table->string('PO CHANGE DATE')->nullable();
			$table->string('PURCHASE PRICE')->nullable();
			$table->string('CURRENCY')->nullable();
			$table->string('PO AMOUNT')->nullable();
			$table->string('TYPOLOGIE')->nullable();
			$table->string('PAYMENT CONDITION CODE')->nullable();	
			$table->string('PAYMENT TERMS')->nullable();
			$table->string('PAYMENT TERMS LABEL')->nullable();
            // $table->string('SUPPLIER CODE')->nullable();
			// $table->string('SUPPLIER NAME')->nullable();
			// $table->string('SUPPLIER ADDRESS')->nullable();
			// $table->string('SUPPLIER EMAIL')->nullable();
			// $table->string('SUPPLIER COUNTRY')->nullable();
			// $table->string('PO')->nullable();
			// $table->string('REFERENCE CODE')->nullable();
			// $table->string('THEME LABEL')->nullable();
			// $table->string('PO PACKING TYPE')->nullable();
			// $table->string('QTY PER SKU-PO')->nullable();
			// $table->string('PRODUCT NAME')->nullable();
			// $table->string('SEASON CODE')->nullable();
			// $table->string('SKU CODE')->nullable();
			// $table->string('PO LINE NUMBER')->nullable();
			// $table->string('PO LINE SUFFIX')->nullable();
			// $table->string('PO STATUS CODE')->nullable();
			// $table->string('PO STATUS CODE DESCRIPTION')->nullable();
			// $table->string('STYLE CODE')->nullable();
			// $table->string('ACTIVITY CODE')->nullable();
			// $table->string('ACTIVITY LABEL')->nullable();
			// $table->string('UNIVERSE CODE')->nullable();
			// $table->string('UNIVERSE LABEL')->nullable();
			// $table->string('DEPARTMENT CODE')->nullable();
			// $table->string('DEPARTMENT LABEL')->nullable();
			// $table->string('FAMILY CODE')->nullable();
			// $table->string('FAMILY LABEL')->nullable();
			// $table->string('SUB FAMILY CODE')->nullable();
			// $table->string('SUB FAMILY LABEL')->nullable();
			// $table->string('THEME CODE')->nullable();
			// $table->string('BRAND CODE')->nullable();
			// $table->string('BRAND LABEL')->nullable();
			// $table->string('DESCRIPTION FR')->nullable();
			// $table->string('BUYER EMAIL')->nullable();
			// $table->string('AGENT CODE')->nullable();
			// $table->string('AGENT NAME')->nullable();
			// $table->string('AGENT EMAIL')->nullable();
			// $table->string('AGENT ADDRESS')->nullable();
			// $table->string('AGENT COUNTRY')->nullable();
			// $table->string('FACTORY NAME')->nullable();
			// $table->string('FACTORY EMAIL')->nullable();
			// $table->string('FACTORY ADDRESS')->nullable();
			// $table->string('MADE IN CODE')->nullable();
			// $table->string('MADE IN LABEL')->nullable();
			// $table->string('FIRST ETD SHIPMENT DATE')->nullable();
			// $table->string('SHIPMENT  DESTINATION CODE')->nullable();
			// $table->string('SHIPMENT  DESTINATION LABEL')->nullable();
			// $table->string('INCOTERMS CODE')->nullable();
			// $table->string('INCOTERMS CITY LABEL')->nullable();
			// $table->string('SKU BARCODE')->nullable();
			// $table->string('SET_')->nullable();
			// $table->string('NUMBER OF PIECES')->nullable();
			// $table->string('COLOR CODE')->nullable();
			// $table->string('COLOR LABEL')->nullable();
			// $table->string('STANDARD CODE')->nullable();
			// $table->string('STANDARD BARCODE')->nullable();
			// $table->string('SIZE RANGE CODE')->nullable();
			// $table->string('SIZE RANGE LABEL')->nullable();
			// $table->string('SIZE CODE')->nullable();
			// $table->string('SIZE LABEL')->nullable();
			// $table->string('SIZE BREAKDOWN')->nullable();
			// $table->string('TOTAL STANDARD-SOLID PARCEL NUMBER')->nullable();
			// $table->string('PRODUCT TYPE')->nullable();
			// $table->string('LICENCE CODE')->nullable();
			// $table->string('LICENCE LABEL')->nullable();
			// $table->string('LICENCE CHARACTER CODE')->nullable();
			// $table->string('LICENCE CHARACTER LABEL')->nullable();
			// $table->string('START PRINT DATE')->nullable();
			// $table->string('COMPOSITION LABEL')->nullable();
			// $table->string('PO CHANGE DATE')->nullable();
			// $table->string('PURCHASE PRICE')->nullable();
			// $table->string('PO AMOUNT')->nullable(); 
			// $table->string('TYPOLOGIE')->nullable();
			// $table->string('PAYMENT CONDITION CODE')->nullable();
			// $table->string('PAYMENT TERMS')->nullable();
			// $table->string('PAYMENT TERMS LABEL')->nullable();
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
