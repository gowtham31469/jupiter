<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTogExclusiveTrimsFromTescoWashcareOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_washcare_orders', function (Blueprint $table) {
            $table->dropColumn('tog'); 
            $table->dropColumn('exclusive_trims');
            $table->dropColumn('contains_non_textile_part');
            $table->dropColumn('part_of_set');
            $table->dropColumn('exclusive_trims_text');
            $table->dropColumn('contains_non_textile_part_text');
            $table->dropColumn('part_of_set_text');
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
            $table->integer('tog')->nullable(true);
            $table->boolean('exclusive_trims')->default(0);
            $table->boolean('contains_non_textile_part')->default(0);
            $table->boolean('part_of_set')->default(0);
            $table->json('exclusive_trims_text')->nullable();
            $table->json('contains_non_textile_part_text')->nullable();
            $table->json('part_of_set_text')->nullable();
        });
    }
}
