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
        'pasting_gsm',
        'die_cutting',
        'window_patch',
        'window_patch_film',
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

    protected $casts = [
        'color_bar' => 'integer',
        'print_spec_bar' => 'integer',
        'tone_bar' => 'integer',
        'fsc_non_fsc' => 'integer',
        'cutting' => 'integer',
        'printing' => 'integer',
        'uv' => 'integer',
        'lamination' => 'integer',
        'pasting' => 'integer',
        'die_cutting' => 'integer',
        'window_patch' => 'integer',
        'folder_gluer' => 'integer',
        'embossing' => 'integer',
        'debossing' => 'integer',
        'perforation' => 'integer',
        'hole' => 'integer',
        'eyelet' => 'integer',
        'string_required' => 'integer',
        'foil' => 'integer',
    ];

    public function colorMatches()
    {
        return $this->hasMany('App\TemplateColorMatchMapping', 'template_board_color_id')->select([
            'template_board_color_id',
            'color_match_text',
        ]);
    }

    public function frontColors()
    {
        return $this->hasMany('App\TemplateFrontColorsList','template_board_color_id')->select([
            'template_board_color_id',
            'front_color_text',
        ]);
    }
}
