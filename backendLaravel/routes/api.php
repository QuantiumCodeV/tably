<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TableController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\OrderController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Управление столиками
Route::prefix('tables')->group(function () {
    Route::get('/{id}', [TableController::class, 'getTableById']);
});

// Управление сессиями
Route::prefix('session')->group(function () {
    Route::post('/create', [SessionController::class, 'create']);
    Route::get('/{sessionId}', [SessionController::class, 'getSession']);
    Route::put('/{sessionId}/status', [SessionController::class, 'updateStatus']);
});

// Управление заказами
Route::prefix('orders')->group(function () {
    Route::post('/create', [OrderController::class, 'store']);
    Route::get('/{orderId}', [OrderController::class, 'getOrder']);
    Route::put('/{orderId}/status', [OrderController::class, 'updateStatus']);
    Route::put('/{orderId}/payment-status', [OrderController::class, 'updatePaymentStatus']);
});
