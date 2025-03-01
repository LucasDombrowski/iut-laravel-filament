<?php

namespace App\Http\Controllers;

use App\Enums\CountryCodeEnum;
use App\Models\Order;
use Illuminate\Http\Request;

class ShowUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = $request->user();
        $addresses = $user->addresses;
        $orders = array_map(function(array $order){
            return [
                ...$order,
                "variants"=>array_map(function(array $variant){
                    return [
                        "variant"=>$variant,
                        "quantity"=>$variant["pivot"]["quantity"],
                        "unit_price"=>$variant["pivot"]["unit_price"]
                    ];
                },$order["variants"])
            ];
        },$user->orders->load('variants.product', 'variants.color', 'variants.size')->toArray());
        return inertia("User/Index", [
            "addresses" => $addresses,
            "orders" => $orders,
            "countries"=>array_map(function($case){
                return [
                    "code"=>$case->value,
                    "name"=>$case->label()
                ];
            },CountryCodeEnum::cases()),
            "startSlide"=>$request->query("tab") === "orders" ? 1 : 0,
        ]);
    }
}
