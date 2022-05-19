<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class OrmsTpn implements Rule
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
        $orms_tpns = explode(',', $value);
        foreach($orms_tpns as $orms_tpn)
        {
            if(strlen($orms_tpn) != 13)
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
