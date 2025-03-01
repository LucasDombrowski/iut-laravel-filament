<?php

use App\Http\Controllers\AddAddressController;
use App\Http\Controllers\AddToCartController;
use App\Http\Controllers\DeleteFromCartController;
use App\Http\Controllers\ShowCartController;
use App\Http\Controllers\ShowCategoriesController;
use App\Http\Controllers\ShowCategoryController;
use App\Http\Controllers\ShowProductController;
use App\Http\Controllers\ShowUserController;
use App\Http\Controllers\UpdateCartController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::post('/cart/{variant}',AddToCartController::class)->name("cart.add");
    Route::get('/cart',ShowCartController::class)->name("cart.index");
    Route::put('/cart',UpdateCartController::class)->name("cart.update");
    Route::delete('/cart/{variant}',DeleteFromCartController::class)->name("cart.remove");
    Route::get('/account',ShowUserController::class)->name("account.show");
    Route::post('/addresses',AddAddressController::class)->name("addresses.add");
});

Route::get("/products/{slug}", ShowProductController::class)->name("products.show");
Route::get('/categories/{slug}', ShowCategoryController::class)->name("categories.show");
Route::get('/categories',ShowCategoriesController::class)->name("categories.index");
