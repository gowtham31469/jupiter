<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobcardMainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobcard_main_table', function (Blueprint $table) {
            $table->increments('id');
            $table->string('work_order_id');  
            $table->integer('company_id')->unsigned();          
            $table->integer('customer_id')->unsigned();
            $table->integer('buyer_id')->unsigned();
            $table->date('created_date'); 
            $table->string('material')->nullable();
            $table->string('article')->nullable();
            $table->string('style_number')->nullable();
            $table->string('reference')->nullable();
            $table->string('order_type')->nullable();
            $table->string('dispatch_mode')->nullable();
            $table->date('order_date'); 
            $table->double('length'); 
            $table->double('repeat_gap');
            $table->integer('machine');
            $table->date('customer_del_date');
            $table->double('width'); 
            $table->double('roll_width'); 
            $table->integer('film_no');
            $table->date('act_del_date');
            $table->integer('length_side');
            $table->double('excess_percentage');
            $table->integer('teeth_no');
            $table->string('invoice_num');
            $table->integer('no_of_stickers');
            $table->integer('finish_type');
            $table->string('print_color')->nullable();
            $table->string('compiled_by');
            $table->string('barcode_read');
            $table->string('operator');
            $table->string('checker');
            $table->string('authorized_by');
            $table->string('axicon_verifier');
            $table->string('approved_by');
            $table->string('packer');
            $table->date('approval_date');
            $table->string('roll_issued_by');
            $table->string('cutter');
            $table->string('cartons');
            $table->string('file_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobcard_main_table');
    }
}
