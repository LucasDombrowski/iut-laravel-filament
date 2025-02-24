<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'url'=>"https://picsum.photos/".fake()->numberBetween(200,1920)."/".fake()->numberBetween(200,1080)."?random=".fake()->numberBetween(1,9999),
            'alt'=>fake()->sentence(),
        ];
    }
}
