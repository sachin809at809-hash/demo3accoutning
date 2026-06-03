<?php

use Illuminate\Support\Facades\Route;
use Modules\OmniChat\Http\Controllers\WebhookController;

Route::group(['prefix' => 'omnichat'], function () {
    Route::post('/webhook/{platform}', [WebhookController::class, 'handle'])->name('omnichat.webhook.handle');
    Route::get('/webhook/{platform}', [WebhookController::class, 'verify'])->name('omnichat.webhook.verify'); // For Facebook/Instagram webhook verification
});
