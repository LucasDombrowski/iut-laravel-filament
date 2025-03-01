<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Rules\CheckCartItemStock;
use App\Rules\CheckDiscountCodeValidity;
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
            "items.*" => new CheckCartItemStock($request->user()),
            "code" => ["sometimes","nullable","exists:discounts,code", new CheckDiscountCodeValidity]
        ]);

        $cart = $request->user()->cart;
        foreach($request->items as $item){
            $cart->variants()->updateExistingPivot($item["id"], [
                "quantity" => $item["quantity"]
            ]);
        }

        if($request->has("code")){
            $cart->discount_id = Discount::where("code", $request->code)->where("end_at", ">", now())->where("start_at", "<", now())->first()->id;
            $cart->save();
        }

        return redirect()->route("cart.index");
    }
}
