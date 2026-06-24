<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\OrderHistoryController;

// ── Products Routes ──────────────────────────────────────
Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// ── Cart Routes ──────────────────────────────────────────
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update/{key}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{key}', [CartController::class, 'remove'])->name('cart.remove');

// ── Checkout Routes ──────────────────────────────────────
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{id}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::post('/checkout/success/{order}/upload-proof', [CheckoutController::class, 'uploadProof'])->name('checkout.uploadProof');
Route::get('/orders/{order}/status', [CheckoutController::class, 'getStatus'])->name('orders.getStatus');

// ── Customer Auth Routes ─────────────────────────────────
Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('customer.register');
Route::post('/register', [CustomerAuthController::class, 'register'])->name('customer.register.post');
Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('customer.login');
Route::post('/login', [CustomerAuthController::class, 'login'])->name('customer.login.post');
Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');

// ── Customer Order History & Profile (auth required) ────────────────
Route::middleware('auth')->group(function () {
    Route::get('/my-orders', [OrderHistoryController::class, 'index'])->name('customer.orders');
    
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('customer.profile');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('customer.profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('customer.profile.password');
});

// ── Admin Auth Routes ─────────────────────────────────────
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// ── Admin CRUD Routes (admin.only — must have is_admin = true) ────
Route::prefix('admin')->name('admin.')->middleware('admin.only')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/products/create', [AdminController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminController::class, 'destroy'])->name('products.destroy');
    Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.updateStatus');
    Route::patch('/orders/{order}/delivery-minutes', [AdminController::class, 'updateDeliveryMinutes'])->name('orders.updateDeliveryMinutes');
    Route::post('/orders/{order}/verify-payment', [AdminController::class, 'verifyPayment'])->name('orders.verifyPayment');
    Route::delete('/orders/{order}', [AdminController::class, 'destroyOrder'])->name('orders.destroy');
});

// ── Language Switcher ────────────────────────────────────
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'km'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');
