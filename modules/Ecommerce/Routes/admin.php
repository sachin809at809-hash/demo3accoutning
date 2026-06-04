<?php

use Illuminate\Support\Facades\Route;
use Modules\Ecommerce\Http\Controllers\DashboardController;
use Modules\Ecommerce\Http\Controllers\BuilderController;
use Modules\Ecommerce\Http\Controllers\ZoneController;
use Modules\Ecommerce\Http\Controllers\ProductController;
use Modules\Ecommerce\Http\Controllers\CategoryController;

Route::admin('ecommerce', function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Website Builder
    Route::get('/builder', [BuilderController::class, 'index'])->name('builder');
    Route::get('/builder/load/{page}', [BuilderController::class, 'load'])->name('builder.load');
    Route::post('/builder/save/{page}', [BuilderController::class, 'save'])->name('builder.save');
    
    // Delivery Zones
    Route::get('/zones', [ZoneController::class, 'index'])->name('zones');
    Route::post('/zones', [ZoneController::class, 'store'])->name('zones.store');
    Route::delete('/zones/{id}', [ZoneController::class, 'destroy'])->name('zones.destroy');
    
    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    
    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    
    // Orders
    Route::get('/orders', [\Modules\Ecommerce\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [\Modules\Ecommerce\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/status', [\Modules\Ecommerce\Http\Controllers\OrderController::class, 'updateStatus'])->name('orders.update_status');
    
    // Settings & Integrations
    Route::get('/settings', [\Modules\Ecommerce\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\Modules\Ecommerce\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/sync/woocommerce', [\Modules\Ecommerce\Http\Controllers\SettingController::class, 'syncWooCommerce'])->name('settings.sync.woocommerce');

    // New ERP Features
    Route::get('/inventory', [\Modules\Ecommerce\Http\Controllers\InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/sms', [\Modules\Ecommerce\Http\Controllers\SmsController::class, 'index'])->name('sms.index');
});
