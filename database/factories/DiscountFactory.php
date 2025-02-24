<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discount>
 */
class DiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "code"=>fake()->word(),
            'type'=>fake()->randomElement(['percentage','fixed']),
            'value'=>fake()->numberBetween(1,100),
            'start_at'=>fake()->dateTimeBetween('-1 year', 'now'),
            'end_at'=>fake()->dateTimeBetween('now', '+1 year'),
            'min_price'=>fake()->numberBetween(1,100),
        ];
    }
}
