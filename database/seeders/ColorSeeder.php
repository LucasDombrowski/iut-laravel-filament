<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            ["label" => "Red", "hex" => "#FF0000"],
            ["label" => "Green", "hex" => "#00FF00"],
            ["label" => "Blue", "hex" => "#0000FF"],
            ["label" => "Yellow", "hex" => "#FFFF00"],
        ];

        foreach ($colors as $color) {
           Color::factory()->create($color);
        }
    }
}
