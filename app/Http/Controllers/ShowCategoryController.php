<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;

class ShowCategoryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $slug)
    {
        $request->validate([
            "colors" => "array",
            "colors.*" => "integer|exists:colors,id",
            "sizes" => "array",
            "sizes.*" => "integer|exists:sizes,id",
            "search" => "string",
            "page" => "numeric",
        ]);
        $category = Category::where('slug', $slug)->firstOrFail();
        $limit = 12;
        //On récupère les produits de la catégorie en prenant en compte la pagination
        $products = $category->products()->with("variants.color","variants.size","categories");
        if($request->query("colors")){
            $products->whereHas("variants", function($query) use ($request){
                $query->whereIn("color_id", $request->query("colors"));
            });
        }
        if($request->query("sizes")){
            $products->whereHas("variants", function($query) use ($request){
                $query->whereIn("size_id", $request->query("sizes"));
            });
        }
        if($request->query("search")){
            $products->where("name", "like", "%".$request->query("search")."%");
        }
        $products = $products->paginate($limit);
        return inertia("Category/Show", [
            "category" => $category->load("children"),
            "products" => $products->items(),
            "colors"=>Color::all(),
            "sizes"=>Size::all(),
            "selectedColors"=>$request->query("colors") ? array_map(function($value){
                return intval($value);
            },$request->query("colors")) : [],
            "selectedSizes"=>$request->query("sizes") ? array_map(function($value){
                return intval($value);
            },$request->query("sizes")) : [],
            "baseSearch"=>$request->query("search") ?? "",
            "meta"=>[
                "currentPage"=>$products->currentPage(),
                "lastPage"=>$products->lastPage(),
                "total"=>$products->total(),
                "previousPageUrl"=>$products->previousPageUrl(),
                "nextPageUrl"=>$products->nextPageUrl()
            ]
        ]);
    }
}
