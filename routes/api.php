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

    Route::get('/chamados', [ChamadoController::class, 'index']);
    Route::get('/chamados/{id}', [ChamadoController::class, 'show']);
    Route::post('/chamados', [ChamadoController::class, 'store']);
    Route::put('/chamados/{id}', [ChamadoController::class, 'update']);
    Route::delete('/chamados/{id}', [ChamadoController::class, 'destroy']);
});

Route::prefix('auth')->group(base_path('routes/auth.php'));
