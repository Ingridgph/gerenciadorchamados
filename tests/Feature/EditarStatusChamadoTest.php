<?php

use App\Models\Chamado;

test('não deve permitir editar status tickets sem autenticação', function () {
    $chamado = Chamado::factory()->create();
    $data = [
        'status' => 'resolvido',
    ];
    $response = $this->patch("/chamados/$chamado->id/status", $data);
    $response->assertStatus(302);
    $response->assertRedirect('/');
});

test('Deve permitir editar status tickets com autenticação', function () {
    $this->authenticated();
    $chamado = Chamado::factory()->create([
        'status' => 'aberto',
    ]);
    $data = [
        'status' => 'resolvido',
    ];
    $response = $this->patch("/chamados/$chamado->id/status", $data);
    $response->assertStatus(302);
    $response->assertRedirect(route('chamados.index'));

    $chamado->refresh();
    $this->assertEquals('resolvido', $chamado->status->value);

});
