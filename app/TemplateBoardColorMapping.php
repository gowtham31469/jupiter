<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemplateBoardColorMapping extends Model
{
    protected $table = 'template_board_color_mapping';

    protected $fillable = [
        'template_id',
        'buyer_template_table',
        'color_bar',
        'print_spec_bar',
        'tone_bar',
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
        'front_color_count',
        'back_color_count',
        'back_color_text',
        'repeats',
        'no_of_plates',
        'cutting',
        'printing',
        'coating',
        'uv',
        'lamination',
        'lamination_spec',
        'pasting',
        'pasting_board',
        'die_cutting',
        'window_patch',
        'window_patch_gsm',
        'folder_gluer',
        'embossing',
        'debossing',
        'perforation',
        'hole',
        'hole_size',
        'eyelet',
        'eyelet_spec',
        'string_required',
        'string_spec',
        'foil',
    ];

    public function colorMatches()
    {
        return $this->hasMany('App\TemplateColorMatchMapping', 'template_board_color_id')->orderBy('id');
    }

    public function frontColors()
    {
        return $this->hasMany('App\template_front_colors_list','template_board_color_id')->orderBy('id');
    }
}
