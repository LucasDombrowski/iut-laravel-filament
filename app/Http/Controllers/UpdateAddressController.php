<?php

namespace App\Http\Controllers;

use App\Enums\CountryCodeEnum;
use App\Models\Address;
use Illuminate\Http\Request;

class UpdateAddressController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Address $address)
    {
        $request->validate([
            "number" => "sometimes",
            "street" => "sometimes",
            "city" => "sometimes",
            "complement" => "sometimes",
            "zip_code" => "sometimes",
            "country_code" => "sometimes|in:".implode(",",array_map(function($case){
                return $case->value;
            },CountryCodeEnum::cases()))
        ]);

        $user = $request->user();
        if($user->cannot("update", $address)){
            abort(403);
        }
        $address->update($request->all());
        return redirect()->route("account.show", ["tab" => "addresses"]);

    }
}
