<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ChamadoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login-token', [AuthenticatedSessionController::class, 'store']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/tickets', [ChamadoController::class, 'index']);
    Route::get('/tickets/{id}', [ChamadoController::class, 'show']);
    Route::post('/tickets', [ChamadoController::class, 'store']);
    Route::patch('/tickets/{id}', [ChamadoController::class, 'updateStatus']);
    Route::delete('/tickets/{id}', [ChamadoController::class, 'destroy']);
});

Route::prefix('auth')->group(base_path('routes/auth.php'));
