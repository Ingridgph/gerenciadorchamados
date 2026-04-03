<?php

use App\Models\Chamado;

test('admin pode excluir chamado', function () {
    $this->authenticatedAdmin();

    $chamado = Chamado::factory()->create();

    $response = $this->delete("/chamados/{$chamado->id}");
    $response->assertStatus(302);
    $response->assertRedirect(route('chamados.index'));

    $this->assertSoftDeleted('chamado', ['id' => $chamado->id]);
});

test('usuario comum nao pode excluir chamado', function () {
    $this->authenticated();

    $chamado = Chamado::factory()->create();

    $response = $this->delete("/chamados/{$chamado->id}");
    $response->assertStatus(403);
});
