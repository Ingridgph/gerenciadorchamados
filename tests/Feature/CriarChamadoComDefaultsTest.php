<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

uses(RefreshDatabase::class);

it('creates chamado with default status and prioridade', function () {
    // seed users
    $this->seed(\Database\Seeders\UserSeeder::class);

    $user = User::where('email', 'admin@test.com')->first();
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Authorization' => 'Bearer '.$token,
    ])->postJson('/api/tickets', [
        'titulo' => 'Chamado pelo teste',
        'descricao' => 'Descrição do chamado',
    ]);

    $response->assertStatus(201)->assertJson(fn (AssertableJson $json) =>
        $json->where('data.titulo', 'Chamado pelo teste')
             ->etc()
    );

    $this->assertDatabaseHas('chamado', [
        'titulo' => 'Chamado pelo teste',
        'status' => 'aberto',
        'prioridade' => 'media',
    ]);
});
