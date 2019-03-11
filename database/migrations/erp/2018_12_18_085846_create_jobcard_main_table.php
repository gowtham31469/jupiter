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
            $table->string('material');
            $table->string('article')->nullable();
            $table->string('style_number')->nullable();
            $table->string('reference')->nullable();
            $table->string('order_type')->nullable();
            $table->string('dispatch_mode');
            $table->date('order_date'); 
            $table->double('length')->nullable(); 
            $table->double('repeat_gap')->nullable();
            $table->integer('machine');
            $table->date('customer_del_date');
            $table->double('width')->nullable(); 
            $table->double('roll_width')->nullable(); 
            $table->integer('film_no')->nullable();
            $table->date('act_del_date')->nullable();
            $table->integer('length_side')->nullable();
            $table->integer('excess_percentage')->nullable();
            $table->integer('teeth_no')->nullable();
            $table->string('invoice_num')->nullable();
            $table->integer('no_of_stickers')->nullable();
            $table->integer('finish_type');
            $table->string('print_color')->nullable();
            $table->string('compiled_by')->nullable();
            $table->string('barcode_read')->nullable();
            $table->string('operator')->nullable();
            $table->string('checker')->nullable();
            $table->string('authorized_by')->nullable();
            $table->string('axicon_verifier')->nullable();
            $table->string('approved_by')->nullable();
            $table->string('packer')->nullable();
            $table->date('approval_date')->nullable();
            $table->string('roll_issued_by')->nullable();
            $table->string('cutter')->nullable();
            $table->string('cartons')->nullable();
            $table->string('file_path')->nullable();
            $table->jsonb('template_quantity_json')->nullable();
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
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
