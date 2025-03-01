<?php

namespace App\Http\Controllers;

use App\Models\Variant;
use Illuminate\Http\Request;

class AddToCartController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Variant $variant)
    {
        $request->validate([
            "quantity" => "required|numeric|min:1|max:".$variant->stock,
        ]);

        $cart = $request->user()->cart;

        //On regarde si le produit est déjà dans le panier
        $existingVariant = $cart->variants()->find($variant->id);

        //Si le produit est déjà dans le panier, on incrémente la quantité
        if ($existingVariant) {
            $cart->variants()->updateExistingPivot($variant->id, [
                "quantity" => $existingVariant->pivot->quantity + $request->quantity
            ]);
        } else {
            //Sinon, on ajoute le produit au panier
            $cart->variants()->attach($variant->id, [
                "quantity" => $request->quantity
            ]);
        }
        
        return redirect()->route("cart.index");
    }
}
