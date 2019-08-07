<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeBuyerPoOcnNotNullConstraintsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::table('history', function (Blueprint $table) {
                 $table->string('buyer_po')->nullable(true)->change();
                 $table->string('ocn')->nullable(true)->change();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('history', function (Blueprint $table) {
                 $table->string('buyer_po')->nullable(false)->change();
                 $table->string('ocn')->nullable(false)->change();
            });
    }
}
