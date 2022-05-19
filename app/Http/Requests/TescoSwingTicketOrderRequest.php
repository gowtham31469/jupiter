<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TescoSwingTicketOrderRequest extends FormRequest
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
            'order_type'              => ['nullable', 'sometimes', Rule::in(['newOrder', 'repeatOrder'])],
            'swing_primary_size_id'   => ['required', 'numeric', 'exists:tesco_size_department,id'],
            'swing_template_id'       => ['required', 'numeric', 'exists:tesco_item_master,id'],
            'swing_24_char_desc'      => ['required', 'max:45'],
            'swing_product_desc'      => ['required', 'numeric', 'exists:tesco_product_descriptions,id'],
            'swing_po_number'         => ['required',  'max:7'],
            'swing_product_benefit'   => ['sometimes', 'nullable', 'exists:tesco_product_benefits,id'],
            'swing_mm_product_benefit'=> ['sometimes', 'nullable', 'exists:tesco_mm_product_benefits,id'],
            'swing_cz_price'          => ['required', 'numeric',  'digits_between:1,6'],
            'swing_sk_price'          => ['required', 'numeric',  'between:0.00,99999.99'],
            'swing_hu_price'          => ['required', 'numeric',  'digits_between:1,6'],
            'swing_tesco_supplier'    => ['required', 'numeric', 'exists:tesco_suppliers,id'],
            'swing_eqos'              => ['required', 'numeric',  'digits_between:1,7'],
            'swing_season_code'       => ['sometimes', 'numeric', 'exists:tesco_season_codes,id'],
            'swing_option_id'         => ['required', 'max:9'],
            'swing_wee_symbol'        => ['sometimes', 'nullable', 'exists:tesco_wee_symbols,id'],
            'swing_ce_mark'           => ['sometimes'],
            'swing_machine_washable'  => ['sometimes'],
            'swing_woolmark_logo'     => ['sometimes', 'nullable', 'exists:tesco_woolmark_logos,id'],
            'swing_style_number'      => ['sometimes', 'max:8'],
            'swing_coo'               => ['sometimes', 'numeric', 'exists:tesco_countries,id'],

            'swing_quantity.*'        => ['nullable', 'sometimes', 'numeric'],
            'swing_ean.*'             => ['nullable', 'sometimes', 'digits:13'],
            'secondary_size_id.*'     => ['nullable', 'sometimes', 'numeric'],
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
            'swing_ean.*.digits' => 'EAN Barcode must be 13 digits.',
        ];
    }
}
