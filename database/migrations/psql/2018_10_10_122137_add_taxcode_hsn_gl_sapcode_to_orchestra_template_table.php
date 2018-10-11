<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaxcodeHsnGlSapcodeToOrchestraTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_template', function (Blueprint $table) {
           $table->string('sap_ref')->after('template_file')->nullable();
           $table->string('gl_code')->after('sap_ref')->nullable();
           $table->string('hsn_code')->after('gl_code')->nullable();
           $table->string('color')->after('hsn_code')->nullable();
           $table->string('material')->after('color')->nullable();
           $table->integer('dimension_height')->after('material')->nullable();
           $table->integer('dimension_width')->after('dimension_height')->nullable();

           $table->unsignedBigInteger('tax_code_id')->after('dimension_width')->nullable();
           $table->foreign('tax_code_id')->references('id')->on('orchestra_taxcode')->onDelete('cascade');

       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('orchestra_template', function (Blueprint $table) {
           $table->dropColumn('sap_ref');
           $table->dropColumn('gl_code');
           $table->dropColumn('hsn_code');
           $table->dropColumn('color');
           $table->dropColumn('material');
           $table->dropForeign('orchestra_template_taxcode_id_foreign');
           $table->dropColumn('tax_code_id');
           $table->dropColumn('dimension_height');
           $table->dropColumn('dimension_width');
        });
    }
}
