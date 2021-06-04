<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalCareInstructionsToTescoWashcareOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_washcare_orders', function (Blueprint $table) {

            $table->unsignedInteger('additional_care_id_1')->nullable(true)->index();
            $table->json('additional_care_text_1')->nullable();

            $table->unsignedInteger('additional_care_id_2')->nullable(true)->index();
            $table->json('additional_care_text_2')->nullable();

            $table->unsignedInteger('additional_care_id_3')->nullable(true)->index();
            $table->json('additional_care_text_3')->nullable();

            $table->foreign('additional_care_id_1')->references('id')->on('tesco_care_additional_instructions')->onDelete('cascade');
            $table->foreign('additional_care_id_2')->references('id')->on('tesco_care_additional_instructions')->onDelete('cascade');
            $table->foreign('additional_care_id_3')->references('id')->on('tesco_care_additional_instructions')->onDelete('cascade');
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
            $table->dropForeign('tesco_washcare_orders_additional_care_id_1_foreign');
            $table->dropColumn('additional_care_id_1');
            $table->dropColumn('additional_care_text_1');

            $table->dropForeign('tesco_washcare_orders_additional_care_id_2_foreign');
            $table->dropColumn('additional_care_id_2');
            $table->dropColumn('additional_care_text_2');

            $table->dropForeign('tesco_washcare_orders_additional_care_id_3_foreign');
            $table->dropColumn('additional_care_id_3');
            $table->dropColumn('additional_care_text_3');
        });
    }
}
