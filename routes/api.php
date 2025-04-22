<?php

use App\Http\Controllers\CartItemsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderItemsController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\ProductSizesController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SizesController;
use App\Http\Controllers\OrdersController;

Route::get('/brands', [BrandsController::class, 'index']);
Route::get('/carts', [CartController::class, 'index']);
Route::get('/cartitems', [CartItemsController::class, 'index']);
Route::resource('/dashboard', DashboardController::class);
Route::resource('/categories', CategoriesController::class);
Route::get('/login', [LoginController::class, 'index']);
Route::get('/orderitems', [OrderItemsController::class, 'index']);
Route::get('/payments', [PaymentsController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/product-images', [ProductImageController::class, 'index']);
Route::get('/product-sizes', [ProductSizesController::class, 'index']);
Route::get('/reviews', [ReviewsController::class, 'index']);
Route::get('/users', [UsersController::class, 'index']);
Route::get('/sizes', [SizesController::class, 'index']);
Route::get('/orders', [OrdersController::class, 'index']);