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
