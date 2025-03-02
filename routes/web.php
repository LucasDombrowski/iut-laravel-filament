<?php

use App\Http\Controllers\AddAddressController;
use App\Http\Controllers\AddOrderController;
use App\Http\Controllers\AddToCartController;
use App\Http\Controllers\DeleteAddressController;
use App\Http\Controllers\DeleteFromCartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ShowCartController;
use App\Http\Controllers\ShowCategoriesController;
use App\Http\Controllers\ShowCategoryController;
use App\Http\Controllers\ShowOrderController;
use App\Http\Controllers\ShowProductController;
use App\Http\Controllers\ShowUserController;
use App\Http\Controllers\UpdateAddressController;
use App\Http\Controllers\UpdateCartController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::post('/cart/{variant}',AddToCartController::class)->name("cart.add");
    Route::get('/cart',ShowCartController::class)->name("cart.index");
    Route::put('/cart',UpdateCartController::class)->name("cart.update");
    Route::delete('/cart/{variant}',DeleteFromCartController::class)->name("cart.remove");
    Route::get('/account',ShowUserController::class)->name("account.show");
    Route::post('/addresses',AddAddressController::class)->name("addresses.add");
    Route::delete('/addreses/{address}',DeleteAddressController::class)->name("addresses.delete");
    Route::put('/addresses/{address}',UpdateAddressController::class)->name("addresses.update");
    Route::get("/checkout",ShowOrderController::class)->name("checkout");
    Route::post("/checkout",AddOrderController::class)->name("checkout.add");
});

Route::get('/',HomeController::class)->name('home');
Route::get("/products/{slug}", ShowProductController::class)->name("products.show");
Route::get('/categories/{slug}', ShowCategoryController::class)->name("categories.show");
Route::get('/categories',ShowCategoriesController::class)->name("categories.index");
