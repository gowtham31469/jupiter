<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeNotNullConstraintsInCustomerContactPersons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_contact_persons', function (Blueprint $table) {
            $table->string('contact_person_name')->nullable(true)->change();
            $table->string('contact_person_email')->nullable(true)->change();
            $table->bigInteger('contact_person_phone')->nullable(true)->change();
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_contact_persons', function (Blueprint $table) {
            $table->string('contact_person_name')->nullable(false)->change();
            $table->string('contact_person_email')->nullable(false)->change();
            $table->bigInteger('contact_person_phone')->nullable(false)->change();
        });
    }
}
