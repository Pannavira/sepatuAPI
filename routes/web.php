<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryPageController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminProductsController;
use App\Http\Controllers\Admin\AdminCategoriesController;
use App\Http\Controllers\Admin\AdminSizeController;
use App\Http\Controllers\Admin\AdminProductSizesController;
use App\Http\Controllers\Admin\AdminOrdersController;

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

//ADMIN
Route::prefix('admin')->middleware('admin.auth')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
});

Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');

// Admin Product Routes
Route::prefix('admin')->middleware('admin.auth')->group(function () {
    Route::get('/products', [\App\Http\Controllers\Admin\AdminProductsController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create', [\App\Http\Controllers\Admin\AdminProductsController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [\App\Http\Controllers\Admin\AdminProductsController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{id}/edit', [\App\Http\Controllers\Admin\AdminProductsController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{id}', [\App\Http\Controllers\Admin\AdminProductsController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}', [\App\Http\Controllers\Admin\AdminProductsController::class, 'destroy'])->name('admin.products.destroy');
});

// Kategori
Route::prefix('admin/categories')->middleware('auth')->name('admin.categories.')->group(function () {
    Route::get('/', [AdminCategoriesController::class, 'index'])->name('index');
    Route::post('/', [AdminCategoriesController::class, 'store'])->name('store');
    Route::put('/{id}', [AdminCategoriesController::class, 'update'])->name('update');
    Route::delete('/{id}', [AdminCategoriesController::class, 'destroy'])->name('destroy');
});

// Admin SIZE
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/sizes', [\App\Http\Controllers\Admin\AdminSizeController::class, 'index'])->name('admin.sizes.index');
    Route::post('/sizes', [\App\Http\Controllers\Admin\AdminSizeController::class, 'store'])->name('admin.sizes.store');
    Route::put('/sizes/{id}', [\App\Http\Controllers\Admin\AdminSizeController::class, 'update'])->name('admin.sizes.update');
    Route::delete('/sizes/{id}', [\App\Http\Controllers\Admin\AdminSizeController::class, 'destroy'])->name('admin.sizes.destroy');
});

Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    Route::resource('product-sizes', AdminProductSizesController::class);
});

//Admin Orders
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    Route::resource('orders', AdminOrdersController::class)->only(['index', 'show']);
});





Route::get('/category/{id}', [CategoryPageController::class, 'show'])->name('category.show');
Route::middleware(['auth'])->group(function () {

    //CART
    Route::get('/cart', [CartController::class, 'showCartPage'])->name('cart.show');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::put('/cart/update/{id}', [CartController::class, 'updateCartItem'])->name('cart.update');
    Route::delete('/cart/delete/{id}', [CartController::class, 'delete'])->name('cart.delete');


});