<?php

namespace App\Http\Requests;
use App\Rules\Tpnd;
use App\Rules\Numeric;
use App\Rules\OrmsTpn;
use App\Rules\EANBarcode;
use App\Rules\ITFBarcode;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TescoRatioStickerOrderRequest extends FormRequest
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
            'eqos'                  => ['required', 'max:7'],
            'uk_style'              => ['required', 'max:8'],
            'ce_style'              => ['required', 'max:8'],
            'season_code'           => ['required', 'max:6'],
            'supplier'              => ['required', 'numeric', 'exists:tesco_suppliers,id'],
            'description'           => ['required', 'max:45'],
            'manufacture_date'      => ['required'],
            'department'            => ['required', 'numeric', 'exists:tesco_department,id'],
            'brand'                 => ['required', 'numeric', 'exists:tesco_brand,id'],
            'rfid'                  => ['required', Rule::in(['0', '1'])],
            'tag_at_source'         => ['required', Rule::in(['0', '1'])],
            'ocn_no'                => ['required', 'numeric'],

            'zone'                  => ['required', Rule::in(['CE', 'UK'])],
            'type'                  => ['required', Rule::in(['ratio'])],
            'templates'             => ['required'],
            'created_by'            => ['nullable', 'sometimes'],
            'full_description'      => ['nullable', 'sometimes'],
            'qr_code'               => ['nullable', 'sometimes'],

            'orms_tpn'            => ['sometimes', new Numeric, new OrmsTpn],
            'tpnd'                => ['sometimes', new Numeric, new Tpnd],
            'itf_barcode'         => ['sometimes', new ITFBarcode, new Numeric],
            'ean_barcode'         => ['sometimes', new EANBarcode, new Numeric],
            'cases_per_carton'    => ['sometimes', new Numeric],
            'units_per_carton'    => ['sometimes', new Numeric],
            
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
            'ce_style.required'  => 'CE Style Number is required.',
            'uk_style.required'  => 'UK Style Number is required.',
            'supplier.required'  => 'Tesco Supplier is required.',
        ];
    }
}
