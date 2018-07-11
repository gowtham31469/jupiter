<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRefNoToProfomaInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profoma_invoices', function (Blueprint $table) {
            $table->string('ref_no')->after('uvc_style_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profoma_invoices', function (Blueprint $table) {
            $table->dropColumn('ref_no');
        });
    }
}
