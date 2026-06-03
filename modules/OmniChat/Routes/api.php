<?php

use Illuminate\Support\Facades\Route;
use Modules\OmniChat\Http\Controllers\WebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your module. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Webhooks don't need authentication middleware because the providers (Meta, TikTok) don't send auth tokens.
// They use signature verification or challenge verification instead.
Route::group(['prefix' => 'omni-chat/webhooks'], function () {
    // GET route for webhook verification (e.g. Meta hub_challenge)
    Route::get('/{platform}', [WebhookController::class, 'verify']);
    
    // POST route for receiving actual messages
    Route::post('/{platform}', [WebhookController::class, 'handle']);
});
