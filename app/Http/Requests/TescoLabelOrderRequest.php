<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Rules\ValidateTescoCarePo;
use App\Rules\ValidateTescoCareOptionId;
use Illuminate\Foundation\Http\FormRequest;

class TescoLabelOrderRequest extends FormRequest
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
            'tesco_care_po'                    => ['required', new ValidateTescoCarePo],
            'tesco_care_eqos'                  => ['required', 'numeric', 'digits:7'],
            'tesco_care_ce_style'              => ['required', new ValidateTescoCareOptionId],
            'tesco_care_uk_style'              => ['required', 'size:8'],
            'tesco_care_fabric_code'           => ['nullable', 'numeric', 'digits:5'],
            'tesco_care_leather_id'            => ['nullable'],
            'tesco_care_coo'                   => ['required', 'numeric', 'exists:tesco_countries,id'],
            'tesco_care_supplier'              => ['required', 'numeric', 'exists:tesco_suppliers,id'],
            'order_type'                       => ['nullable', 'sometimes', Rule::in(['newOrder', 'repeatOrder'])],
            'primary_size_id'                  => ['sometimes'],
            'tesco_care_uk_ce_mark'            => ['nullable'],
            
            'tesco_care_wash_instruction'      => ['required', 'numeric', 'exists:tesco_care_symbols,id'],
            'tesco_care_bleach_instruction'    => ['required', 'numeric', 'exists:tesco_care_symbols,id'],
            'tesco_care_dry_instruction'       => ['required', 'numeric', 'exists:tesco_care_symbols,id'],
            'tesco_care_iron_instruction'      => ['required', 'numeric', 'exists:tesco_care_symbols,id'],
            'tesco_care_dry_clean_instruction' => ['required', 'numeric', 'exists:tesco_care_symbols,id'],

            'tesco_care_garment_component.*'   => ['nullable', 'numeric', 'sometimes', 'exists:tesco_components,id'],
            'tesco_care_fiber.*'               => ['nullable', 'numeric', 'sometimes', 'exists:tesco_fabriclists,id'],
            'fiber_total_percentage.*'         => ['nullable', 'sometimes', 'numeric', Rule::in(['0', '100']), 'max:100'],
            'fiber_individual_percentage.*'    => ['nullable', 'sometimes', 'digits_between:1,100'],

            'tesco_care_kids_warning'          => ['sometimes', 'nullable', 'numeric', 'exists:tesco_kids_warning_statements,id' ],
            'tesco_care_additional_instruction_1'   => ['sometimes', 'nullable', 'numeric', 'exists:tesco_care_additional_instructions,id' ],
            'tesco_care_additional_instruction_2'   => ['sometimes', 'nullable', 'numeric', 'exists:tesco_care_additional_instructions,id' ],
            'tesco_care_additional_instruction_3'   => ['sometimes', 'nullable', 'numeric', 'exists:tesco_care_additional_instructions,id' ],

            'quantity.*'                       => ['nullable', 'sometimes', 'numeric'],
            'uk_ean.*'                         => ['nullable', 'sometimes', 'digits:13'],
            'ce_ean.*'                         => ['nullable', 'sometimes', 'digits:13'],
            'secondary_size_id.*'              => ['nullable', 'sometimes', 'numeric'],
            
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
            'tesco_care_po.size' => 'PO Number must be between 1 and 10 digits.',
            'tesco_care_eqos.required' => 'EQOS Supplier Number is required.',
            'tesco_care_eqos.size' => 'EQOS Supplier Number must be between 1 and 10 digits.',
            'tesco_care_ce_style.required'  => 'Option ID is required.',
            'tesco_care_uk_style.required'  => 'UK Style Number is required.',
            'tesco_care_coo.required'  => 'Country of Origin is required.',
            'tesco_care_supplier.required'  => 'Tesco Supplier is required.',
            'tesco_care_wash_instruction.required'  => 'Wash Instruction is required.',
            'tesco_care_bleach_instruction.required'  => 'Bleach Instruction is required.',
            'tesco_care_dry_instruction.required'  => 'Drying Instruction is required.',
            'tesco_care_iron_instruction.required'  => 'Ironing Instruction is required.',
            'tesco_care_dry_clean_instruction.required'  => 'Dry Clean Instruction is required.',
            'uk_ean.*.digits' => 'UK ean barcode must be 13 digits.',
            'ce_ean.*.digits' => 'CE ean barcode must be 13 digits.',
        ];
    }
}
