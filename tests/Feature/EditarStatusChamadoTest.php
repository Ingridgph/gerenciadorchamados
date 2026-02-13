<?php

use App\Models\Chamado;

test('não deve permitir acessar tickets sem autenticação', function () {
    $chamado = Chamado::factory()->create();
    $data = [
        'status' => 'resolvido',
    ];
    $response = $this->patchJson("/api/tickets/$chamado->id", $data);
    $response->assertStatus(401);
});

test('Deve permitir acessar tickets com autenticação', function () {
    $this->authenticated();
    $chamado = Chamado::factory()->create();
    $data = [
        'status' => 'resolvido',
    ];
    $response = $this->patchJson("/api/tickets/$chamado->id", $data);
    $response->assertStatus(200);

$chamado->refresh();
    $this->assertEquals('resolvido', $chamado->status->value);

});
