<?php

namespace App\Http\Controllers;

use App\Models\Variant;
use Illuminate\Http\Request;

class DeleteFromCartController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Variant $variant)
    {
        $cart = $request->user()->cart;
        $cart->variants()->detach($variant->id);
        return redirect()->route("cart.index");
    }
}
