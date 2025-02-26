<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalesController;

// Home
Route::get('/', [HomeController::class, 'index']);

// Products
Route::prefix('/category')->group(function () {
    Route::get('/food-beverage', [ProductController::class, 'showProducts'])->name('products.food');
    Route::get('/beauty-health', [ProductController::class, 'showProducts'])->name('products.beauty');
    Route::get('/home-care', [ProductController::class, 'showProducts'])->name('products.home');
    Route::get('/baby-kid', [ProductController::class, 'showProducts'])->name('products.baby');
});


// Route::get('/category/{category}', [ProductController::class, 'showProducts'])->name('products.show');


// User
Route::get('/user/{id}/name/{name}', [UserController::class, 'showProfile']);

// Sales
Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
Route::post('/sales', [SalesController::class, 'processTransaction'])->name('sales.process');