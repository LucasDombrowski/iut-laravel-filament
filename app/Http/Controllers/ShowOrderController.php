<?php

namespace App\Http\Controllers;

use App\Enums\CountryCodeEnum;
use Illuminate\Http\Request;

class ShowOrderController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $cart = $request->user()->cart;
        $variants = $cart->variants->load('product', 'color', 'size');
        $items = array_map(function($variant){
            return [
                "variant"=>$variant,
                "quantity"=>$variant["pivot"]["quantity"],
            ];
        },$variants->toArray());
        if(count($items) === 0){
            return redirect()->route("cart.index");
        }
        
        return inertia("Order/Create", [
            "cart" => [
                "items" => $items,
                "discount" => $cart->discount
            ],
            "savedAddresses"=>$request->user()->addresses,
            "countries"=>array_combine(
                array_map(function($case){
                    return $case->value;
                },CountryCodeEnum::cases()),
                array_map(function($case){
                    return $case->label();
                },CountryCodeEnum::cases())
            )
        ]);
    }
}
