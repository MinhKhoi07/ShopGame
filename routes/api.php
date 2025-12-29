<?php

use App\Http\Controllers\Api\GameApiController;
use App\Http\Controllers\Api\VNPayController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/health', fn () => ['status' => 'ok']);

    Route::get('/games', [GameApiController::class, 'index']);
    Route::get('/games/{game}', [GameApiController::class, 'show']);

    Route::post('/payments/vnpay/create', [VNPayController::class, 'create']);
    Route::match(['get', 'post'], '/payments/vnpay/callback', [VNPayController::class, 'callback']);
});
