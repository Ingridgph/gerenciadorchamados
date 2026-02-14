<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\TicketController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'create'])->name('login');
    Route::post('/', [AuthController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    Route::resource('chamados', TicketController::class);
    Route::patch('/chamados/{chamado}/status', [TicketController::class, 'updateStatus'])->name('chamados.updateStatus');
});
