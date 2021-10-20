<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TescoSingleStickerOrderRequest extends FormRequest
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
            'po_number'             => ['required', 'digits_between:7,10'],
            'eqos'                  => ['required', 'max:7'],
            'uk_style'              => ['required', 'max:8'],
            'ce_style'              => ['required', 'max:8'],
            'season_code'           => ['required', 'max:6'],
            'supplier'              => ['required', 'numeric', 'exists:tesco_suppliers,id'],
            'description'           => ['required', 'max:45'],
            'manufacture_date'      => ['required'],
            'department'            => ['required', 'numeric', 'exists:tesco_department,id'],
            'brand'                 => ['required', 'numeric', 'exists:tesco_brand,id'],
            'rfid'                  => ['required'],
            'tag_at_source'         => ['required'],
            'ocn_no'                => ['required', 'numeric'],

            'zone'                  => ['required'],
            'type'                  => ['required'],
            'templates'             => ['required'],
            'created_by'            => ['nullable', 'sometimes'],
            'full_description'      => ['nullable', 'sometimes'],
            'qr_code'               => ['nullable', 'sometimes'],

            'orms_tpn.*'            => ['required', 'numeric', 'digits:13'],
            'barcode.*'             => ['required', 'numeric', 'min:13', 'max:14'],
            'case_size.*'           => ['required', 'numeric', 'max:6'],
            'size.*'                => ['nullable', 'sometimes', 'max:9'],
            'sticker_count.*'       => ['required', 'nullable'],
            
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
            'supplier.required'  => 'Tesco Supplier is required.',
        ];
    }
}
