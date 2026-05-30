<?php

use Illuminate\Support\Facades\Route;

/**
 * 'guest' middleware applied to all routes
 *
 * @see \App\Providers\Route::mapGuestRoutes
 * @see \modules\PaypalStandard\Routes\guest.php for module example
 */

Route::group(['prefix' => 'auth'], function () {
    Route::get('login', 'Auth\Login@create')->name('login');
    Route::post('login', 'Auth\Login@store')->name('login.store');

    Route::get('forgot', 'Auth\Forgot@create')->name('forgot');
    Route::post('forgot', 'Auth\Forgot@store')->name('forgot.store');

    //Route::get('reset', 'Auth\Reset@create');
    Route::get('reset/{token}', 'Auth\Reset@create')->name('reset');
    Route::post('reset', 'Auth\Reset@store')->name('reset.store');

    Route::get('register/{token}', 'Auth\Register@create')->name('register');
    Route::post('register', 'Auth\Register@store')->name('register.store');
});

Route::get('/', function () {
    return redirect()->route('login');
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
