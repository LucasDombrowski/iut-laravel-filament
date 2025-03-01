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
        if($cart->discount){
            //On regarde si le code promo est toujours valide
            if($cart->discount->end_at < now() || $cart->discount->start_at > now()){
                $cart->discount_id = null;
                $cart->save();
            }
        }
        $items = array_map(function($variant){
            return [
                "variant"=>$variant,
                "quantity"=>$variant["pivot"]["quantity"],
            ];
        },$cart->variants->load('product', 'color', 'size')->toArray());
        return inertia("Cart/Index", [
            "cartItems" => $items,
            "cartDiscount" => $cart->discount
        ]);
        
    }
}
