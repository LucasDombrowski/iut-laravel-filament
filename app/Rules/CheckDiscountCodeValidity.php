<?php

namespace App\Rules;

use App\Models\Discount;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckDiscountCodeValidity implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //On cherche s'il existe une réduction dans la base de donnée avec le code donnée, encore en cours, et qui a déjà été utilisée
        $exists = Discount::where('code', $value)
            ->where('end_at', '>', now())
            ->where('start_at', '<', now())
            ->exists();
        if (!$exists) {
            $fail("This code is not valid.");
        }
    }
}
