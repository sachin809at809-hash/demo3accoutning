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

    return Route::get($base . '/vendor/livewire/livewire/dist/livewire.min.js', $handle);
});

Route::get('/debug-log', function () {
    $logFile = storage_path('logs/laravel.log');
    if (!file_exists($logFile)) {
        return 'No log file found.';
    }
    return response(file_get_contents($logFile))->header('Content-Type', 'text/plain');
});
