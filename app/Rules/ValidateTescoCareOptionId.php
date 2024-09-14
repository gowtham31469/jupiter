<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateTescoCareOptionId implements Rule
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
        // Your custom validation logic here
        // For example, allow numbers and special characters
        return preg_match('/^[0-9\-!@#$%^&*()_+={}[\]:;"\'<>,.?\/|\\\\]+$/i', $value) &&
        strlen($value) <= 8;
        
    }

    public function message()
    {
        return 'The :attribute is invalid.';
    }
}
