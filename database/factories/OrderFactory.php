<?php

namespace Database\Factories;

use App\Enums\OrderStatusEnum;
use App\Models\Discount;
use App\Models\Order;
use App\Models\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status'=>fake()->randomElement(array_map(fn($status)=>$status->value,OrderStatusEnum::cases())),
            'address_number'=>fake()->buildingNumber(),
            'address_street'=>fake()->streetName(),
            'address_complement'=>fake()->secondaryAddress(),
            'address_city'=>fake()->city(),
            'address_zip_code'=>fake()->postcode(),
            'address_country_code'=>fake()->countryCode(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function(Order $order) {
           $variants = Variant::inRandomOrder()->limit(3)->get();
           foreach($variants as $variant) {
               $order->variants()->attach($variant, [
                'quantity'=>fake()->numberBetween(1,5),
                'unit_price'=>$variant->price
               ]);
           }
           if(random_int(1,2)===1){
               $order->discount_id = Discount::inRandomOrder()->first()->id;
               $order->save();
           }
        });
    }
}
