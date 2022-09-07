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
            'color_match.*'         => ['sometimes', 'numeric', 'exists:color_match,id'],
            'board_category'        => ['sometimes', 'nullable', 'numeric', 'exists:board_category,id'], 
            'board_name'            => ['sometimes', 'nullable', 'numeric', 'exists:boards,id'], 
            'fsc_non_fsc'           => [Rule::in(['0', '1'])], 
            'gsm'                   => ['sometimes', 'nullable', 'numeric'], 
            'thickness'             => ['sometimes', 'nullable', 'numeric'], 
            'board_size_length'     => ['sometimes', 'nullable', 'numeric'], 
            'board_size_width'      => ['sometimes', 'nullable', 'numeric'], 
            'print_size_length'     => ['sometimes', 'nullable', 'numeric'], 
            'print_size_width'      => ['sometimes', 'nullable', 'numeric'], 
            'design_size_length'    => ['sometimes', 'nullable', 'numeric'], 
            'design_size_width'     => ['sometimes', 'nullable', 'numeric'], 
            'cut_as_1'              => ['sometimes', 'nullable', 'numeric'], 
            'cut_as_2'              => ['sometimes', 'nullable', 'numeric'], 
            'front_color_count'     => ['sometimes', 'nullable', 'numeric'], 
            'back_color_count'      => ['sometimes', 'nullable', 'numeric'], 
            'back_color_1'          => ['sometimes'], 
            'repeats'               => ['sometimes', 'nullable', 'numeric'], 
            'no_of_plates'          => ['sometimes', 'nullable', 'numeric'], 
            'cutting'               => ['sometimes', 'nullable', Rule::in(['0', '1'])], 
            'printing'              => ['sometimes', 'nullable', Rule::in(['0', '1'])], 
            'coating'               => ['sometimes', 'nullable', Rule::in(['matt', 'gloss', 'no'])], 
            'uv'                    => ['sometimes', 'nullable', Rule::in(['0', '1'])], 
            'lamination'            => ['sometimes', 'nullable', Rule::in(['0', '1'])], 
            'lamination_spec'       => ['sometimes', Rule::in(['matt', 'gloss']), 'nullable'], 
            'pasting'               => ['sometimes', 'nullable', Rule::in(['0', '1'])], 
            'pasting_board'         => ['sometimes'], 
            'die_cutting'           => ['sometimes', 'nullable', Rule::in(['0', '1'])], 
            'window_patch'          => ['sometimes', 'nullable', Rule::in(['0', '1'])], 
            'window_patch_gsm'      => ['sometimes', 'numeric', 'nullable'], 
            'folder_gluer'          => ['sometimes', 'nullable', Rule::in(['0', '1'])], 
            'embossing'             => ['sometimes', 'nullable', Rule::in(['0', '1'])], 
            'debossing'             => ['sometimes', 'nullable', Rule::in(['0', '1'])], 
            'perforation'           => ['sometimes', 'nullable', Rule::in(['0', '1'])], 
            'hole'                  => ['sometimes', 'nullable', Rule::in(['0', '1'])], 
            'hole_size'             => ['sometimes', 'numeric', 'nullable'], 
            'eyelet'                => ['sometimes', 'nullable', Rule::in(['0', '1'])], 
            'eyelet_spec'           => ['sometimes', 'nullable'], 
            'string_required'       => ['sometimes', 'nullable', Rule::in(['0', '1'])], 
            'string_spec'           => ['sometimes', 'nullable'], 
            'foil'                  => ['sometimes', 'nullable', Rule::in(['0', '1'])], 
            
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
