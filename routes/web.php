<?php

use App\Http\Controllers\AddToCartController;
use App\Http\Controllers\ShowCartController;
use App\Http\Controllers\ShowProductController;
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
});

Route::get("/products/{slug}", ShowProductController::class)->name("product.show");
