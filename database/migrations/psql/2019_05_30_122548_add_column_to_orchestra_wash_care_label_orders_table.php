<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToOrchestraWashCareLabelOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_wash_care_label_orders', function (Blueprint $table) {
            $table->text('merchandiser_comment')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orchestra_wash_care_label_orders', function (Blueprint $table) {
            $table->text('merchandiser_comment')->nullable(false)->change();
        });
    }
}
