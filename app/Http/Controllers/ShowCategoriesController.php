<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class ShowCategoriesController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $categories = Category::all();
        return inertia("Category/Index", [
            "categories" => $categories,
        ]);
    }
}
