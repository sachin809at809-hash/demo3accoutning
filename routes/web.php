<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

/**
 * 'web' middleware applied to all routes
 *
 * @see \App\Providers\Route::mapWebRoutes
 */

 Livewire::setScriptRoute(function ($handle) {
    $base = request()->getBasePath();

    return Route::get($base . '/vendor/livewire/livewire.min.js', $handle);
});

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/livewire/update', $handle);
});

Route::get('/debug-log', function () {
    try {
        $logFile = storage_path('logs/laravel.log');
        if (!file_exists($logFile)) {
            return 'No log file found.';
        }
        $fp = fopen($logFile, 'r');
        fseek($fp, -10000, SEEK_END);
        $content = fread($fp, 10000);
        fclose($fp);
        return response($content)->header('Content-Type', 'text/plain');
    } catch (\Exception $e) {
        return $e->getMessage();
    }
});

Route::get('/debug-perms', function () {
    return response(shell_exec('ls -la /app/public/js/auth/'))->header('Content-Type', 'text/plain');
});

// Load Ecommerce Storefront routes globally
Route::middleware(['web', 'company.identify'])->group(function () {
    if (file_exists(base_path('modules/Ecommerce/Routes/storefront.php'))) {
        require base_path('modules/Ecommerce/Routes/storefront.php');
    }
});

// Load OmniChat Webhook routes globally
Route::middleware(['web', 'company.identify'])->group(function () {
    if (file_exists(base_path('modules/OmniChat/Routes/webhook.php'))) {
        require base_path('modules/OmniChat/Routes/webhook.php');
    }
});
