<?php

namespace Database\Factories;

use App\Models\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Variant>
 */
class VariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "price"=>fake()->numberBetween(1000,10000),
            "stock"=>fake()->numberBetween(0,100),
            "thumbnail"=>"https://picsum.photos/1920/1080?random=".fake()->numberBetween(1,1000)
        ];
    }
}
