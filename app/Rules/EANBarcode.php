<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EANBarcode implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $barcodes = explode(',', $value);
        foreach($barcodes as $barcode)
        {
            if(strlen($barcode) != 13)
            {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be 13 digits.';
    }
}
