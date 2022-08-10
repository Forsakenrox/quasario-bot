<?php

use App\Http\Controllers\BotController;
use App\Telegram\Commands\TestCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Commands\HelpCommand;
use Telegram\Bot\Laravel\Facades\Telegram;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::post('/webhook', function () {
//     Telegram::getWebhookUpdates();
//     $update = Telegram::commandsHandler(true);
//     Log::info("1");
//     return 'ok';
// });
Route::group(['middleware' => ['throttle:1000,1']], function ($router) {
    Route::post('/webhook', [BotController::class, 'handle']);
});