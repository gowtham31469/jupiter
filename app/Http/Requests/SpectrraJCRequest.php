<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpectrraJCRequest extends FormRequest
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
            'color_bar' => ['required'],
            'color_match' => ['required'],
            'front_colors' => ['required'],
            'back_colors' => ['required'],
            'board_category' => ['required'],
            'board_name' => ['required'],
            'fsc_non_fsc' => ['required'],
            'gsm_value' => ['required'],
            'repeats' => ['required'],
            'die_no' => ['required'],
            'cutting' => ['required'],
            'printing' => ['required'],
            'die_cutting' => ['required'],
            'workorder_id' => ['required'],
            'article' => ['required'],
            'erp_id' => ['required'],
        ];
    }
}
