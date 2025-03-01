<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class DeleteAddressController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Address $address)
    {
        $user = $request->user();
        if($user->cannot("delete", $address)){
            abort(403);
        }
        $address->delete();
        return redirect()->route("account.show", ["tab" => "addresses"]);
    }
}
