<?php

namespace App\Http\Controllers;

use App\Enums\CountryCodeEnum;
use Illuminate\Http\Request;

class AddAddressController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            "number" => "required",
            "street" => "required",
            "city" => "required",
            "postcode" => "required",
            "complement" => "sometimes",
            "zip_code" => "required",
            "country_code" => "required|in:".implode(",",array_map(function($case){
                return $case->value;
            },CountryCodeEnum::cases()))
        ]);

        $user = $request->user();

        $user->addresses()->create($request->all());

        return redirect()->route("user.index", ["tab" => "addresses"]);
    }
}
