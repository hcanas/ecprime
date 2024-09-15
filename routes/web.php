<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductProfileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('shop', ShopController::class)->name('shop');
Route::get('about_us', AboutUsController::class)->name('about_us');
Route::get('contact_us', [ContactUsController::class, 'index'])->name('contact_us.index');
Route::post('contact_us', [ContactUsController::class, 'store'])->name('contact_us.store')
    ->middleware(HandlePrecognitiveRequests::class);

Route::get('cart', CartController::class)->name('cart');

Route::get('product/{id}', ProductProfileController::class)->name('product.profile');
Route::post('quotations', [QuotationController::class, 'store'])->name('quotations.store')
    ->middleware(HandlePrecognitiveRequests::class);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::resource('users', UserController::class)->except('show', 'store');
    Route::resource('products', ProductController::class)->except('show', 'store', 'update');
    Route::resource('quotations', QuotationController::class)->except('store');
    Route::resource('purchase_orders', PurchaseOrderController::class)->only('index', 'show', 'edit');

    Route::middleware(HandlePrecognitiveRequests::class)->group(function () {
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::resource('products', ProductController::class)->only('store', 'update');
        Route::resource('quotations', QuotationController::class)->only('update');
        Route::resource('purchase_orders', PurchaseOrderController::class)->only('update');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
