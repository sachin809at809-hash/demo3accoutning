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

<<<<<<< HEAD
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

=======
>>>>>>> 1d253e0 (Add backdoor routes for password reset and reinstall)
Route::get('/override-render-password', function () {
    $user = \App\Models\Auth\User::first();
    if ($user) {
        $user->password = \Illuminate\Support\Facades\Hash::make('password');
        $user->save();
        return "Password successfully reset for: " . $user->email . "<br>New Password: password<br><a href='/auth/login'>Go to Login</a>";
    }
    return "No users found in database.";
});
<<<<<<< HEAD
=======

Route::get('/wipe-and-reinstall', function () {
    // Drop all tables and re-run migrations
    \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--force' => true]);
    
    // Set APP_INSTALLED to false in the .env file if it's writable
    $envPath = base_path('.env');
    if (file_exists($envPath)) {
        file_put_contents($envPath, str_replace(
            'APP_INSTALLED=true',
            'APP_INSTALLED=false',
            file_get_contents($envPath)
        ));
    }
    
    return "Database has been completely wiped. <br><br><b>Next Step:</b> Go to your Render Dashboard -> Environment, make sure APP_INSTALLED is set to <b>false</b>, and then go to the homepage to run the setup wizard again.<br><br><a href='/'>Go to Web Installer</a>";
});
>>>>>>> 1d253e0 (Add backdoor routes for password reset and reinstall)
