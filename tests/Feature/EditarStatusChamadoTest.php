<?php

use App\Models\Chamado;

test('não deve permitir editar status tickets sem autenticação', function () {
    $chamado = Chamado::factory()->create();
    $data = [
        'status' => 'resolvido',
    ];
    $response = $this->patchJson("/api/tickets/{$chamado->id}/status", $data);
    $response->assertStatus(401);
});

test('Deve permitir editar status tickets com autenticação', function () {
    $this->authenticated();
    $chamado = Chamado::factory()->create([
        'status' => 'aberto',
    ]);
    $data = [
        'status' => 'resolvido',
    ];
    $response = $this->patchJson("/api/tickets/{$chamado->id}/status", $data);
    $response->assertStatus(200);

    $chamado->refresh();
    $this->assertEquals('resolvido', $chamado->status->value);

});

test('Deve criar log de auditoria ao alterar status', function () {
    $this->authenticated();
    $chamado = Chamado::factory()->create([
        'status' => 'aberto',
    ]);

    $response = $this->patchJson("/api/tickets/{$chamado->id}/status", [
        'status' => 'em_andamento',
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('chamado_logs', [
        'chamado_id' => $chamado->id,
        'de' => 'aberto',
        'para' => 'em_andamento',
        'user_id' => $this->user->id,
    ]);
});

test('Deve preencher resolved_at ao marcar como RESOLVIDO', function () {
    $this->authenticated();
    $chamado = Chamado::factory()->create([
        'status' => 'em_andamento',
        'resolved_at' => null,
    ]);

    $response = $this->patchJson("/api/tickets/{$chamado->id}/status", [
        'status' => 'resolvido',
    ]);

    $response->assertStatus(200);

    $chamado->refresh();
    $this->assertEquals('resolvido', $chamado->status->value);
    $this->assertNotNull($chamado->resolved_at);

    $this->assertDatabaseHas('chamado_logs', [
        'chamado_id' => $chamado->id,
        'de' => 'em_andamento',
        'para' => 'resolvido',
    ]);
});
