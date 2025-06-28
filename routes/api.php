<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderItemsController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProductImagesController;
use App\Http\Controllers\ProductSizesController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SizesController;
use App\Http\Controllers\OrdersController;

// CRUD Resources
Route::apiResource('brands', BrandsController::class);
Route::apiResource('carts', CartController::class);
Route::apiResource('cartitems', CartItemsController::class);
Route::apiResource('dashboard', DashboardController::class);
Route::apiResource('categories', CategoriesController::class);
Route::apiResource('login', LoginController::class);
Route::apiResource('orderitems', OrderItemsController::class);
Route::apiResource('payments', PaymentsController::class);
Route::apiResource('products', ProductsController::class);
Route::apiResource('product-images', ProductImagesController::class);
Route::apiResource('product-sizes', ProductSizesController::class);
Route::apiResource('reviews', ReviewsController::class);
Route::apiResource('users', UsersController::class);
Route::apiResource('sizes', SizesController::class);
Route::apiResource('orders', OrdersController::class);

Route::post('/admin/login', [UsersController::class, 'login']);
Route::delete('/product_images/delete_by_product/{product_id}', [ProductImagesController::class, 'deleteByProduct']);
Route::delete('/product_sizes/delete_by_product/{product_id}', [ProductSizesController::class, 'deleteByProduct']);

