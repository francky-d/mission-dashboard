<?php

namespace App\Rules;

use App\Models\AllowedEmailDomain as AllowedEmailDomainModel;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AllowedEmailDomain implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! AllowedEmailDomainModel::isEmailAllowed($value)) {
            $fail(__('The email domain is not allowed. Please use an authorized company email.'));
        }
    }
}
