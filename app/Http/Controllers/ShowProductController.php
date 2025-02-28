<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShowProductController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return inertia("Product/Show", [
            "product" => Product::where("slug", $request->slug)->firstOrFail()->load('variants.images','variants.color','variants.size','categories'),
        ]);
    }
}
