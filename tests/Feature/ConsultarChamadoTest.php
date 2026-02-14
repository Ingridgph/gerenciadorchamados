<?php

use App\Models\Chamado;

test('não deve permitir acessar tickets sem autenticação', function () {
    $response = $this->get('/chamados');
    $response->assertStatus(302);
    $response->assertRedirect(route('login'));
});

test('Deve permitir acessar tickets com autenticação', function () {
    $this->authenticated();
    Chamado::factory()->count(5)->create();

    $response = $this->get('/chamados');
    $response->assertStatus(200);
    $response->assertViewHas('chamados', function ($chamados) {
        return $chamados->count() === 5;
    });

});
