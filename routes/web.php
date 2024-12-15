<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'getProducts'])->name('products');
    Route::get('/{product}', [ProductController::class, 'detailProducts'])->name('products.detail');
});

Route::prefix('cart')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add/{product}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('/{product}', [CartController::class, 'removeFromCart'])->name('cart.remove');
});

Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/admin', function () {
    return view('dashboard-admin');
})->middleware(['auth', 'verified', AdminMiddleware::class])->name('admin');


Route::prefix('admin/user')->middleware(['auth', 'verified', AdminMiddleware::class])->name('admin.user.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('show/{user}', [UserController::class, 'show'])->name('show');
    Route::delete('destroy/{user}', [UserController::class, 'destroy'])->name('destroy');
});


Route::prefix('admin/product')->middleware(['auth', 'verified', AdminMiddleware::class])->name('admin.product.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('create', [ProductController::class, 'create'])->name('create');
    Route::post('store', [ProductController::class, 'store'])->name('store');
    Route::get('detail/{product}', [ProductController::class, 'show'])->name('detail');
    Route::get('edit/{product}', [ProductController::class, 'edit'])->name('edit');
    Route::put('update/{product}', [ProductController::class, 'update'])->name('update');
    Route::delete('destroy/{product}', [ProductController::class, 'destroy'])->name('destroy'); // Route destroy
});

require __DIR__.'/auth.php';
