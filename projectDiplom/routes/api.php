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

Route::get('/tables/{id}', [TableController::class, 'getTableById']);

// Управление сессиями

Route::post('/session/create', [SessionController::class, 'create']);
Route::get('/session/{sessionId}', [SessionController::class, 'getSession']);
Route::put('/session/{sessionId}/status', [SessionController::class, 'updateStatus']);

Route::post('/orders/create', [OrderController::class, 'create']);
Route::get('/orders/{orderId}', [OrderController::class, 'getOrder']);
Route::put('/orders/{orderId}/status', [OrderController::class, 'updateStatus']);
Route::put('/orders/{orderId}/payment-status', [OrderController::class, 'updatePaymentStatus']);
