<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyConstraintsToCustomersChildTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_bill_address', function($table) {
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
        Schema::table('customer_contact_persons', function($table) {
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_bill_address', function($table) {
            $table->dropForeign('customer_bill_address_customer_id_foreign');
        });
        Schema::table('customer_contact_persons', function($table) {
            $table->dropForeign('customer_contact_persons_customer_id_foreign');
        });
    }
}
