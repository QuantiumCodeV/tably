<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrCodeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/tables/{table}/qrcode', [QrCodeController::class, 'show'])->name('table.qrcode');
    Route::get('/tables/{table}/qrcode/download', [QrCodeController::class, 'generateForTable'])->name('table.qrcode.download');
    Route::get('/qrcode/generate/{table}', [QrCodeController::class, 'generate'])->name('qrcode.generate');
});
