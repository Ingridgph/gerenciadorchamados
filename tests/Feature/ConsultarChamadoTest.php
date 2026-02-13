<?php

use App\Models\Chamado;

test('não deve permitir acessar tickets sem autenticação', function () {
    $response = $this->getJson('/api/tickets');
    $response->assertStatus(401);
});

test('Deve permitir acessar tickets com autenticação', function () {
    $this->authenticated();
    Chamado::factory()->count(5)->create();

    $response = $this->getJson('/api/tickets');
    $response->assertStatus(200);
    $this->assertCount(5, $response->json('data'));
});
