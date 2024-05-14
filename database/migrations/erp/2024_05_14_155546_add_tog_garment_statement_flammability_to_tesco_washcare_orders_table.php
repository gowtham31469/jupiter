<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTogGarmentStatementFlammabilityToTescoWashcareOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_washcare_orders', function (Blueprint $table) {
            $table->decimal('tog',6,2)->nullable();
            $table->integer('woolmark_logo_id')->nullable(true)->index();
            $table->string('woolmark_batch_code')->nullable(true);
            $table->boolean('uk_ce_mark')->default('0');
            $table->integer('flammability_id')->nullable(true)->index();
            $table->integer('garment_statement_id')->nullable(true)->index();

            $table->foreign('woolmark_logo_id')->references('id')->on('tesco_woolmark_logos')->onDelete('cascade');
            $table->foreign('flammability_id')->references('id')->on('tesco_flammabilities')->onDelete('cascade');
            $table->foreign('garment_statement_id')->references('id')->on('tesco_garment_statements')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tesco_washcare_orders', function (Blueprint $table) {
            $table->dropColumn('tog');
            $table->dropForeign('tesco_washcare_orders_woolmark_logo_id_foreign');
            $table->dropColumn('woolmark_logo_id');
            $table->dropColumn('woolmark_batch_code');
            $table->dropColumn('uk_ce_mark');
            $table->dropForeign('tesco_washcare_orders_flammability_id_foreign');
            $table->dropColumn('flammability_id');
            $table->dropForeign('tesco_washcare_orders_garment_statement_id_foreign');
            $table->dropColumn('garment_statement_id');
        });
    }
}
