<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEqosDataTypeInTescoWashcareOrdersTable extends Migration
{
    
    public function __construct()
    {
        \Illuminate\Support\Facades\DB::getDoctrineSchemaManager()
        ->getDatabasePlatform()->registerDoctrineTypeMapping('json', 'string');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tesco_washcare_orders', function (Blueprint $table) {
            $table->string('eqos_site_code')->nullable(true)->change();
            $table->bigInteger('total_qty')->nullable(true)->change();
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
            $table->integer('eqos_site_code')->nullable(true)->change();
            $table->integer('total_qty')->nullable(true)->change();
        });
    }
}
