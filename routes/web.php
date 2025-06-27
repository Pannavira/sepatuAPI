<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryPageController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index']);

Route::get('/produk/{id}', [ProductsController::class, 'detail'])->name('produk.detail');

//LOGIN
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//REGISTER
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');


Route::get('/category/{id}', [CategoryPageController::class, 'show'])->name('category.show');
Route::middleware(['auth'])->group(function () {

    //CART
    Route::get('/cart', [CartController::class, 'showCartPage'])->name('cart.show');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::put('/cart/update/{id}', [CartController::class, 'updateCartItem'])->name('cart.update');
    Route::delete('/cart/delete/{id}', [CartController::class, 'delete'])->name('cart.delete');


});