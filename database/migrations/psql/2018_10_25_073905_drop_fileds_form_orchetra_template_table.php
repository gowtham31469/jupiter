<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropFiledsFormOrchetraTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_template', function (Blueprint $table) {
           $table->dropForeign('orchestra_template_tax_code_id_foreign');
           $table->dropColumn('tax_code_id');
           $table->dropColumn('outside_tamilnadu');
           $table->dropColumn('inside_tamilnadu');
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
           $table->integer('outside_tamilnadu')->nullable();
           $table->integer('inside_tamilnadu')->nullable();

           $table->unsignedBigInteger('tax_code_id')->nullable();
           $table->foreign('tax_code_id')->references('id')->on('orchestra_taxcode')->onDelete('cascade');

       });
    }
}
