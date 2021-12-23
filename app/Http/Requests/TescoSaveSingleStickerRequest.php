<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TescoSaveSingleStickerRequest extends FormRequest
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
            'po_number'             => ['required', 'min:7', 'max:9'],
            'uk_style'              => ['nullable', 'max:8',],
            'ce_style'              => ['nullable', 'max:8',],
            'season_code'           => ['nullable', 'sometimes', 'max:6'],
            'supplier'              => ['nullable', 'sometimes', 'numeric', 'exists:tesco_suppliers,id'],
            'description'           => ['nullable', 'sometimes', 'max:45'],
            'department'            => ['nullable', 'sometimes', 'numeric', 'exists:tesco_department,id'],
            'brand'                 => ['nullable', 'sometimes', 'numeric', 'exists:tesco_brand,id'],
            'ocn_no'                => ['nullable', 'sometimes', 'numeric'],

            'orms_tpn.*'            => ['nullable', 'sometimes', 'numeric'],
            'barcode.*'             => ['nullable', 'sometimes', 'numeric'],
            'case_size.*'           => ['nullable', 'sometimes', 'numeric'],
            'size.*'                => ['nullable', 'sometimes'],
            
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
            'po_number.required' => 'PO Number is required.',
            'po_number.size'     => 'PO Number must be between 7 and 10 digits.',
            'ce_style.required'  => 'CE Style Number is required.',
            'uk_style.required'  => 'UK Style Number is required.',
        ];
    }
}
