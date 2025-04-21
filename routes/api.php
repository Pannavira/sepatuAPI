<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\CartController;


Route::get('/brands', [BrandsController::class, 'index']);
Route::get('/carts', [CartController::class, 'index']);