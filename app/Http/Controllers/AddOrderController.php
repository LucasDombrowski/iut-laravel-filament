<?php

namespace App\Http\Controllers;

use App\Enums\CountryCodeEnum;
use App\Enums\OrderStatusEnum;
use App\Models\Order;
use Illuminate\Http\Request;

class AddOrderController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            "address"=>"required|array",
            "address.number" => "required",
            "address.street" => "required",
            "address.city" => "required",
            "address.complement" => "sometimes",
            "address.zip_code" => "required",
            "address.country_code" => "required|in:".implode(",",array_map(function($case){
                return $case->value;
            },CountryCodeEnum::cases())),
            "address.save" => "sometimes",
        ]);

        $user = $request->user();
        $address_key = "address_";
        $address_attributes = [];
        foreach($request->address as $key => $value){
            if($key !== "save"){
                $address_attributes[$address_key.$key] = $value;
            }
        }

        $order = Order::create(array_merge(
            [
                'user_id' => $user->id,
                "status"=> OrderStatusEnum::Pending
            ],
            $address_attributes
        ));

        $cart = $user->cart;
        foreach($cart->variants as $variant){
            if($variant->pivot->quantity > $variant->stock){
                return redirect()->route("cart.index")->withErrors([
                    "items" => "Some items are out of stock"
                ]);
            }
            $order->variants()->attach($variant->id, [
                "quantity" => $variant->pivot->quantity,
                "unit_price" => $variant->price
            ]);
            $variant->stock -= $variant->pivot->quantity;
            $variant->save();
        }

        if($cart->discount && $cart->discount->min_price <= $order->total_price && $cart->discount->start_at <= now() && $cart->discount->end_at >= now()){
            $order->discount_id = $cart->discount_id;
            $order->save();
        }

        if(isset($request->address["save"])){
            $user->addresses()->create(array_merge(
                array_combine(
                    array_map(function($key) use ($address_key){
                        return str_replace($address_key, "", $key);
                    }, array_keys($address_attributes)),
                    array_values($address_attributes)
                ),
                [
                    "user_id" => $user->id
                ]
            ));
        }

        $cart->variants()->detach();
        $cart->discount_id = null;
        $cart->save();

        return redirect()->route("account.show", ["tab" => "orders"]);
    }
}
