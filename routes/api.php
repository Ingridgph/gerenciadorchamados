<?php

use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login-token', [AuthenticatedSessionController::class, 'store']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/tickets', [TicketController::class, 'index']);
    Route::get('/tickets/{chamado}', [TicketController::class, 'show']);
    Route::post('/tickets', [TicketController::class, 'store']);
    Route::patch('/tickets/{chamado}/status', [TicketController::class, 'updateStatus']);
    Route::delete('/tickets/{chamado}', [TicketController::class, 'destroy']);
});
