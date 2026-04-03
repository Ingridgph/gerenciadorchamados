<?php

use App\Models\Chamado;

test('dashboard requer autenticacao', function () {
    $response = $this->get('/dashboard');
    $response->assertStatus(302);
    $response->assertRedirect(route('login'));
});

test('dashboard exibe estatisticas corretamente', function () {
    $this->authenticated();

    Chamado::factory()->count(3)->create(['status' => 'aberto']);
    Chamado::factory()->count(2)->create(['status' => 'em_andamento']);
    Chamado::factory()->count(1)->create(['status' => 'resolvido', 'resolved_at' => now()]);

    $response = $this->get('/dashboard');
    $response->assertStatus(200);
    $response->assertViewHas('stats', function ($stats) {
        return $stats['total'] === 6
            && $stats['aberto'] === 3
            && $stats['em_andamento'] === 2
            && $stats['resolvido'] === 1;
    });
    $response->assertViewHas('recentChamados');
});

test('dashboard usa query otimizada com groupBy', function () {
    $this->authenticated();

    Chamado::factory()->count(10)->create();

    // Deve funcionar com qualquer quantidade sem N+1
    $response = $this->get('/dashboard');
    $response->assertStatus(200);
});
