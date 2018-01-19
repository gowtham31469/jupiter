<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMerchandiserCommentApprovedDateToOrchestrawashcareLabelOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orchestra_wash_care_label_orders', function (Blueprint $table) {
            $table->longText('merchandiser_comment')->after('svg_path');
            $table->date('approved_date')->after('delivery_date');
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
            $table->dropColumn('merchandiser_comment');
            $table->dropColumn('approved_date');
        });
    }
}
