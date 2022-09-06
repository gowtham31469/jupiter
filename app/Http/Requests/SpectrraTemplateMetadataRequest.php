<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SpectrraTemplateMetadataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [ 
            'template_id_spectrra'  => ['required', 'numeric'],
            'buyer_spectrra'        => ['required'],
            'color_bar'             => [Rule::in(['0', '1'])],
            'print_spec_bar'        => [Rule::in(['0', '1'])],
            'tone_bar'              => [Rule::in(['0', '1'])],
            'color_match.*'         => ['required', 'numeric', 'exists:color_match,id'],
            'board_category'        => ['numeric', 'exists:board_category,id'], 
            'board_name'            => ['numeric', 'exists:boards,id'], 
            'fsc_non_fsc'           => [Rule::in(['0', '1'])], 
            'gsm'                   => ['numeric'], 
            'thickness'             => ['numeric'], 
            'board_size_length'     => ['numeric'], 
            'board_size_width'      => ['numeric'], 
            'print_size_length'     => ['numeric'], 
            'print_size_width'      => ['numeric'], 
            'design_size_length'    => ['numeric'], 
            'design_size_width'     => ['numeric'], 
            'cut_as_1'              => ['numeric'], 
            'cut_as_2'              => ['numeric'], 
            'front_color_count'     => ['numeric'], 
            'back_color_count'      => ['numeric'], 
            'back_color_1'          => ['sometimes'], 
            'repeats'               => ['numeric'], 
            'no_of_plates'          => ['numeric'], 
            'cutting'               => [Rule::in(['0', '1'])], 
            'printing'              => [Rule::in(['0', '1'])], 
            'coating'               => [Rule::in(['matt', 'gloss', 'no'])], 
            'uv'                    => [Rule::in(['0', '1'])], 
            'lamination'            => [Rule::in(['0', '1'])], 
            'lamination_spec'       => ['sometimes', Rule::in(['matt', 'gloss']), 'nullable'], 
            'pasting'               => [Rule::in(['0', '1'])], 
            'pasting_board'         => ['sometimes'], 
            'die_cutting'           => [Rule::in(['0', '1'])], 
            'window_patch'          => [Rule::in(['0', '1'])], 
            'window_patch_gsm'      => ['sometimes', 'numeric', 'nullable'], 
            'folder_gluer'          => [Rule::in(['0', '1'])], 
            'embossing'             => [Rule::in(['0', '1'])], 
            'debossing'             => [Rule::in(['0', '1'])], 
            'perforation'           => [Rule::in(['0', '1'])], 
            'hole'                  => [Rule::in(['0', '1'])], 
            'hole_size'             => ['sometimes', 'numeric', 'nullable'], 
            'eyelet'                => [Rule::in(['0', '1'])], 
            'eyelet_spec'           => ['sometimes', 'nullable'], 
            'string_required'       => [Rule::in(['0', '1'])], 
            'string_spec'           => ['sometimes', 'nullable'], 
            'foil'                  => [Rule::in(['0', '1'])], 
            
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
        ];
    }
}
