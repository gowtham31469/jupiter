<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NonartworkJCRequest extends FormRequest
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
            'material' => ['required'],
            'disp_mode' => ['required'],
            'machine' => ['required'],
            'aw_app_date' => ['required'],
            'cus_del_date' => ['required'],
            'teeth_no' => ['required'],
            'finish_type' => ['required'],
            'compiled_by' => ['required'],
        ];
    }
}
