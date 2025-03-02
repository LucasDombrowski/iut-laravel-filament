<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name"=>fake()->word(),
            'description'=>fake()->paragraph(),
            'slug'=>fake()->slug()
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (\App\Models\Product $product) {
           $product->categories()->attach(Category::inRandomOrder()->take(3)->get());
        });
    }
}
