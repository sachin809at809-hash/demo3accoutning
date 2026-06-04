<?php

use Illuminate\Support\Facades\Route;
use Modules\OmniChat\Http\Controllers\InboxController;
use Modules\OmniChat\Http\Controllers\SettingsController;

/**
 * 'admin' middleware and 'omni-chat' prefix applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */

Route::admin('omni-chat', function () {
    Route::get('/inbox', [InboxController::class, 'index'])->name('inbox');
    Route::get('/inbox/{conversation}', [InboxController::class, 'show'])->name('inbox.show');
    Route::post('/inbox/{conversation}/reply', [InboxController::class, 'reply'])->name('inbox.reply');
    Route::post('/inbox/{conversation}/assign', [InboxController::class, 'assign'])->name('inbox.assign');
    
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    
    // Canned Responses
    Route::get('/canned-responses', [\Modules\OmniChat\Http\Controllers\CannedResponseController::class, 'index'])->name('canned_responses.index');
    Route::post('/canned-responses', [\Modules\OmniChat\Http\Controllers\CannedResponseController::class, 'store'])->name('canned_responses.store');
    Route::delete('/canned-responses/{id}', [\Modules\OmniChat\Http\Controllers\CannedResponseController::class, 'destroy'])->name('canned_responses.destroy');
});
