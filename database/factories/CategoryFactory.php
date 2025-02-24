<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
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
            'description'=>fake()->sentence(),
            'slug'=>fake()->slug(),
            'thumbnail'=>"https://picsum.photos/1920/1080?random=".fake()->numberBetween(1,9999),
            'parent_id'=>null
        ];
    }
}
