<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class EmailDomainRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $allowedEmailDomains = ['aol.com', 'gmail.com', 'outlook.com', 'yahoo.com', 'protonmail.com', 'tutanota.com'];
        $domain = substr(strrchr($value, "@"), 1);
        return in_array($domain, $allowedEmailDomains);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O domínio do email não é permitido.';
    }
}
