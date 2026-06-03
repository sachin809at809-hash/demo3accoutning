<?php

use Illuminate\Support\Facades\Route;
use Modules\Ecommerce\Http\Controllers\StorefrontController;

Route::group(['prefix' => 'store'], function () {
    Route::get('/', [StorefrontController::class, 'home'])->name('ecommerce.store.index');
    Route::get('/checkout', [StorefrontController::class, 'checkout'])->name('ecommerce.store.checkout');
    Route::post('/checkout/process', [StorefrontController::class, 'processCheckout'])->name('ecommerce.store.checkout.process');
    
    // Cart
    Route::get('/cart', [\Modules\Ecommerce\Http\Controllers\CartController::class, 'index'])->name('ecommerce.store.cart');
    Route::post('/cart/add/{id}', [\Modules\Ecommerce\Http\Controllers\CartController::class, 'add'])->name('ecommerce.store.cart.add');
    Route::post('/cart/update', [\Modules\Ecommerce\Http\Controllers\CartController::class, 'update'])->name('ecommerce.store.cart.update');
    Route::post('/cart/remove', [\Modules\Ecommerce\Http\Controllers\CartController::class, 'remove'])->name('ecommerce.store.cart.remove');
    
    // Product Details
    Route::get('/product/{slug}', [StorefrontController::class, 'product'])->name('ecommerce.store.product');
    
    // Map dynamically generated pages
    Route::get('/{slug}', [StorefrontController::class, 'page'])->name('ecommerce.store.page');
});
