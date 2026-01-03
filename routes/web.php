<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'home'])->name('home');

Route::post('/orders', [OrderController::class, 'store']);

Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Public Product Routes
Route::get('/products', [ProductController::class, 'list'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'detail'])->name('products.show');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/orders/{orderNumber}/receipt', [CheckoutController::class, 'viewReceipt'])->name('orders.receipt.view');
Route::get('/orders/{orderNumber}/receipt/download', [CheckoutController::class, 'downloadReceipt'])->name('orders.receipt.download');

// Order Tracking Routes
Route::get('/track-order', [OrderTrackingController::class, 'show'])->name('orders.track');
Route::post('/track-order', [OrderTrackingController::class, 'search'])->name('orders.search');

// Admin Authentication Routes (Public)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [\App\Http\Controllers\AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [\App\Http\Controllers\AdminAuthController::class, 'login'])->name('login.post');
    Route::post('logout', [\App\Http\Controllers\AdminAuthController::class, 'logout'])->name('logout');
});

// Admin Routes (Protected)
Route::prefix('admin')->name('admin.')->middleware('admin.auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/statistics', [DashboardController::class, 'statistics'])->name('dashboard.statistics');
    Route::get('dashboard/revenue', [DashboardController::class, 'revenue'])->name('dashboard.revenue');
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/status/{status}', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [\App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
});
