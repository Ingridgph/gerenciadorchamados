<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ChamadoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// 1. Rota PÚBLICA (Não precisa de token)
Route::post('/login-token', [AuthenticatedSessionController::class, 'store']);

// 2. Rotas PROTEGIDAS (Só passa com o Bearer Token no Header)
Route::middleware(['auth:sanctum'])->group(function () {

    // Ver dados do usuário logado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Operações de Chamados (Agora estão seguras!)
    Route::get('/chamados', [ChamadoController::class, 'index']);
    Route::get('/chamados/{id}', [ChamadoController::class, 'show']);
    Route::post('/chamados', [ChamadoController::class, 'store']);
    Route::put('/chamados/{id}', [ChamadoController::class, 'update']);
    Route::delete('/chamados/{id}', [ChamadoController::class, 'destroy']);
});

Route::prefix('auth')->group(base_path('routes/auth.php'));
