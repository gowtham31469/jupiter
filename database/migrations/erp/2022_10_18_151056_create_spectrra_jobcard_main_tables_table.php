<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpectrraJobcardMainTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spectrra_jobcard_main_tables', function (Blueprint $table) {
            $table->increments('id');
            $table->string('work_order_id');  
            $table->integer('company_id')->unsigned();          
            $table->integer('customer_id')->unsigned();          
            $table->string('customer_name'); 
            $table->integer('buyer_id')->unsigned();          
            $table->string('buyer_name'); 
            $table->date('created_date'); 
            $table->string('article')->nullable(true);
            $table->string('style_number');
            $table->string('reference');
            $table->date('order_date'); 
            $table->date('customer_del_date')->nullable(true);
            $table->string('invoice_num')->nullable(true);
            $table->date('invoice_date')->nullable(true);
            $table->string('customer_po_number');
            $table->string('erp_id');
            $table->date('po_verified_date')->nullable(true);
            $table->integer('total_order_qty');

            $table->boolean('color_bar')->default(false);
            $table->boolean('print_spec_bar')->default(false);
            $table->boolean('tone_bar')->default(false);
            $table->string('front_colors')->nullable(true);
            $table->string('back_color')->nullable(true);
            $table->bigInteger('board_category_id')->nullable(true)->index();
            $table->string('board_category_text')->nullable(true);
            $table->bigInteger('board_id')->nullable(true)->index();
            $table->string('board_text')->nullable(true);
            $table->boolean('fsc_non_fsc')->nullable(true)->default(false);
            $table->integer('gsm_value')->nullable(true);
            $table->integer('thickness')->nullable(true);
            $table->integer('board_size_length')->nullable(true);
            $table->integer('board_size_width')->nullable(true);
            $table->integer('print_size_length')->nullable(true);
            $table->integer('print_size_width')->nullable(true);
            $table->integer('design_size_length')->nullable(true);
            $table->integer('design_size_width')->nullable(true);
            $table->string('cut_as')->nullable(true);
            $table->integer('repeats')->nullable(true);
            $table->string('die_no')->nullable(true);
            $table->boolean('cutting')->nullable(true)->default(false);
            $table->boolean('printing')->nullable(true)->default(false);
            $table->string('coating')->nullable(true);
            $table->boolean('uv')->nullable(true)->default(false);
            $table->string('lamination')->nullable(true);
            $table->string('pasting_board')->nullable(true);
            $table->integer('pasting_gsm')->nullable(true);
            $table->boolean('die_cutting')->nullable(true)->default(false);
            $table->integer('window_patch')->nullable(true);
            $table->boolean('folder_gluer')->nullable(true)->default(false);
            $table->boolean('embossing')->nullable(true)->default(false);
            $table->boolean('debossing')->nullable(true)->default(false);
            $table->boolean('perforation')->nullable(true)->default(false);
            $table->integer('hole_size')->nullable(true);
            $table->string('eyelet')->nullable(true);
            $table->string('string_spec')->nullable(true);
            $table->boolean('foil')->nullable(true)->default(false);
            $table->string('file_path')->nullable();
            $table->boolean('is_non_artwork_template')->default(false);
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('board_category_id')->references('id')->on('board_category')->onDelete('cascade');
            $table->foreign('board_id')->references('id')->on('boards')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spectrra_jobcard_main_tables');
    }
}
