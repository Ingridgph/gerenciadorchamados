<?php

use App\Http\Controllers\ChamadoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;

// Teste de rota simples
Route::get('/test', function () {
    return response()->json(['message' => 'Test OK']);
});

// Debug: listar usuários (apenas ambiente de desenvolvimento)
Route::get('/debug/users', function () {
    return response()->json(\App\Models\User::select('id','email','name')->get());
});

// Autenticação
Route::post('/auth/login', function (Request $request) {
    $email = $request->input('email');
    $password = $request->input('password');

    if (!$email || !$password) {
        return response()->json(['message' => 'Email e senha são obrigatórios'], 422);
    }

    $user = \App\Models\User::where('email', $email)->first();
    if (! $user || ! \Illuminate\Support\Facades\Hash::check($password, $user->password)) {
        return response()->json(['message' => 'Credenciais inválidas'], 401);
    }

    $token = $user->createToken('api-token')->plainTextToken;
    return response()->json(['token' => $token]);
})->withoutMiddleware(\Illuminate\Auth\Middleware\RedirectIfAuthenticated::class);

Route::post('/auth/register', [RegisteredUserController::class, 'store'])
    ->withoutMiddleware(\Illuminate\Auth\Middleware\RedirectIfAuthenticated::class);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/tickets', [ChamadoController::class, 'index']);
    Route::get('/tickets/{id}', [ChamadoController::class, 'show']);
    Route::post('/tickets', [ChamadoController::class, 'store']);
    Route::patch('/tickets/{id}/status', [ChamadoController::class, 'updateStatus']);
    Route::delete('/tickets/{id}', [ChamadoController::class, 'destroy']);
});

