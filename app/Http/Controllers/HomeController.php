<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // Récupérer les produits les plus vendus en comptant les commandes via leurs variantes
        $most_selled_products = Product::withCount(['variants as orders_count' => function ($query) {
            $query->join('order_variant', 'variants.id', '=', 'order_variant.variant_id');
        }])
        ->orderByDesc('orders_count')
        ->take(8)
        ->get()
        ->load('variants.color', 'variants.size', 'variants.product','categories');

        // Récupérer 8 produits aléatoires
        $random_products = Product::inRandomOrder()->take(8)->get()->load('variants.color', 'variants.size', 'variants.product','categories');

        // Récupérer toutes les catégories
        $categories = Category::all();

        // Récupérer une réduction aléatoire
        $random_discount = Discount::inRandomOrder()->where('start_at', '<=', now())->where('end_at', '>=', now())->first();

        return inertia("Home", [
            "categories" => $categories,
            "topProducts" => $most_selled_products,
            "highlightedProducts" => $random_products,
            "discount" => $random_discount
        ]);
    }
}
