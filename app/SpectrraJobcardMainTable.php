<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpectrraJobcardMainTable extends Model
{
    /**
      * The table associated with the model.
      *
      * @var string
      */
      protected $table = 'spectrra_jobcard_main_tables';

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
      protected $fillable = [
            'work_order_id',  
            'company_id',          
            'customer_name', 
            'buyer_name', 
            'created_date', 
            'article',
            'style_number',
            'reference',
            'order_date', 
            'customer_del_date',
            'invoice_num',
            'invoice_date',
            'customer_po_number',
            'erp_id',
            'po_verified_date',
            'total_order_qty',
            'color_bar',
            'print_spec_bar',
            'tone_bar',
            'front_colors',
            'back_color',
            'board_category_id',
            'board_category_text',
            'board_id',
            'board_text',
            'fsc_non_fsc',
            'gsm_value',
            'thickness',
            'board_size_length',
            'board_size_width',
            'print_size_length',
            'print_size_width',
            'design_size_length',
            'design_size_width',
            'cut_as',
            'repeats',
            'die_no',
            'cutting',
            'printing',
            'coating',
            'uv',
            'lamination',
            'pasting_board',
            'pasting_gsm',
            'die_cutting',
            'window_patch',
            'folder_gluer',
            'embossing',
            'debossing',
            'perforation',
            'hole_size',
            'eyelet',
            'string_spec',
            'foil',
            'is_non_artwork_template',
      ];

      public function colorMatches()
      {
            return $this->hasMany('App\SpectrraJobcardMatchToTable', 'spectrra_jobcard_main_id')->select([
                  'spectrra_jobcard_main_id',
                  'color_match_text',
            ]);
      }
}
