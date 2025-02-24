<?php

namespace Database\Seeders;

use App\Models\Color;
use App\Models\Image;
use App\Models\Product;
use App\Models\Size;
use App\Models\Variant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $colors = Color::all();
        $sizes = Size::all();

        foreach($products as $product) {
            foreach($colors as $color) {
                foreach($sizes as $size) {
                    Variant::factory()
                    ->has(Image::factory(6))
                    ->create([
                        "product_id" => $product->id,
                        "color_id" => $color->id,
                        "size_id" => $size->id,
                    ]);
                }
            }
        }
    }
}
