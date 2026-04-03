<?php

use App\Models\Chamado;
use App\Models\ChamadoLog;

test('nao deve permitir editar status tickets sem autenticacao', function () {
    $chamado = Chamado::factory()->create();
    $data = [
        'status' => 'resolvido',
    ];
    $response = $this->patch("/chamados/$chamado->id/status", $data);
    $response->assertStatus(302);
    $response->assertRedirect('/');
});

test('deve permitir editar status tickets com autenticacao', function () {
    $this->authenticated();
    $chamado = Chamado::factory()->create([
        'status' => 'aberto',
    ]);
    $data = [
        'status' => 'resolvido',
    ];
    $response = $this->patch("/chamados/$chamado->id/status", $data);
    $response->assertStatus(302);
    $response->assertRedirect(route('chamados.show', $chamado));

    $chamado->refresh();
    $this->assertEquals('resolvido', $chamado->status->value);
});

test('atualizar status cria log de atividade', function () {
    $this->authenticated();
    $chamado = Chamado::factory()->create(['status' => 'aberto']);

    $this->patch("/chamados/$chamado->id/status", ['status' => 'em_andamento']);

    $log = ChamadoLog::where('chamado_id', $chamado->id)->first();
    expect($log)->not->toBeNull();
    expect($log->de)->toBe('aberto');
    expect($log->para)->toBe('em_andamento');
    expect($log->user_id)->toBe($this->user->id);
});

test('resolver chamado define resolved_at', function () {
    $this->authenticated();
    $chamado = Chamado::factory()->create(['status' => 'aberto']);

    $this->patch("/chamados/$chamado->id/status", ['status' => 'resolvido']);

    $chamado->refresh();
    expect($chamado->resolved_at)->not->toBeNull();
});
