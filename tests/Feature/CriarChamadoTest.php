<?php

use App\Models\Chamado;

test('criar chamado requer autenticacao', function () {
    $response = $this->post('/chamados', [
        'titulo' => 'Teste',
        'descricao' => 'Descricao teste',
        'prioridade' => 'alta',
    ]);
    $response->assertStatus(302);
    $response->assertRedirect(route('login'));
});

test('criar chamado com dados validos', function () {
    $this->authenticated();

    $response = $this->post('/chamados', [
        'titulo' => 'Chamado de Teste',
        'descricao' => 'Descricao do chamado de teste completa',
        'prioridade' => 'alta',
    ]);

    $response->assertStatus(302);
    $response->assertRedirect(route('chamados.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('chamado', [
        'titulo' => 'Chamado de Teste',
        'prioridade' => 'alta',
        'status' => 'aberto',
    ]);
});

test('criar chamado com dados invalidos falha', function () {
    $this->authenticated();

    $response = $this->post('/chamados', [
        'titulo' => '',
        'descricao' => '',
    ]);

    $response->assertSessionHasErrors(['titulo', 'descricao']);
});

test('criar chamado atribui solicitante automaticamente', function () {
    $this->authenticated();

    $this->post('/chamados', [
        'titulo' => 'Teste Solicitante',
        'descricao' => 'Verificando atribuicao automatica',
        'prioridade' => 'baixa',
    ]);

    $chamado = Chamado::where('titulo', 'Teste Solicitante')->first();
    expect($chamado->solicitante_id)->toBe($this->user->id);
});

test('exibir formulario de criacao', function () {
    $this->authenticated();

    $response = $this->get('/chamados/create');
    $response->assertStatus(200);
});
