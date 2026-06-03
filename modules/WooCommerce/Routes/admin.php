<?php

use Illuminate\Support\Facades\Route;
use Modules\WooCommerce\Http\Controllers\Main;

/**
 * 'admin' middleware and 'woo-commerce' prefix applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */

Route::admin('woo-commerce', function () {
    Route::get('/', [Main::class, 'index'])->name('woo-commerce.index');
    Route::post('/settings', [Main::class, 'updateSettings'])->name('woo-commerce.settings.update');
    Route::get('/sync', [Main::class, 'sync'])->name('woo-commerce.sync');
});
