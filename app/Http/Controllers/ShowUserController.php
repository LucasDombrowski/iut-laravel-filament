<?php

namespace App\Http\Controllers;

use App\Enums\CountryCodeEnum;
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
        $orders = $user->orders;
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
