<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShowCartController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $cart = $request->user()->cart;
        $items = array_map(function($variant){
            return [
                "variant"=>$variant,
                "quantity"=>$variant["pivot"]["quantity"],
            ];
        },$cart->variants->load('product', 'color', 'size')->toArray());
        return inertia("Cart/Index", [
            "cartItems" => $items,
        ]);
        
    }
}
