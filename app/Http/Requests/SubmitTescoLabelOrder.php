<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitTescoLabelOrder extends FormRequest
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
            'tesco_care_po'  => ['required', ''],
            'tesco_care_ce_style'  => ['required'],
            'tesco_care_uk_style'  => ['required']
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
            'tesco_care_po.required' => 'PO Number is required.',
            'tesco_care_ce_style.required'  => 'Option ID is required',
            'tesco_care_uk_style.required'  => 'UK Style Number is required',
        ];
    }
}
