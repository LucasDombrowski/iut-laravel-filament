<?php

namespace App\Http\Controllers;

use App\Rules\CheckCartItemStock;
use Illuminate\Http\Request;

class UpdateCartController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            "items" => "required|array",
            "items.*.id" => "required|exists:variants,id",
            "items.*.quantity"=>"required|numeric|min:1",
            "items.*" => new CheckCartItemStock($request->user())
        ]);

        $cart = $request->user()->cart;
        foreach($request->items as $item){
            $cart->variants()->updateExistingPivot($item["id"], [
                "quantity" => $item["quantity"]
            ]);
        }

        return redirect()->route("cart.index");
    }
}
