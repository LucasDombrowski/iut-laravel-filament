<?php

namespace App\Rules;

use App\Models\User;
use App\Models\Variant;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckCartItemStock implements ValidationRule
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $variant = Variant::find($value["id"]);
        if(!$variant){
            $fail("Le produit n'existe pas");
            return;
        }
        $cart = $this->user->cart;
        $existingVariant = $cart->variants()->find($variant->id);
        if(!$existingVariant){
            $fail("Le produit n'est pas dans le panier");
            return;
        }
        $quantity = $value["quantity"];
        if($quantity > $variant->stock){
            $fail("Le stock du produit est insuffisant");
            return;
        }
    }
}
