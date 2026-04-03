<x-app-layout>
    @section('page-title', 'Dashboard')

    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold tracking-tight text-gray-900">Dashboard</h2>
        <p class="mt-1 text-sm text-gray-500">Acompanhe o status dos seus chamados em tempo real.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <x-ui.stat-card
            title="Total de Chamados"
            :value="$stats['total']"
            color="purple"
            subtitle="Todos os chamados"
            :icon="'<svg class=\'h-5 w-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2\'/></svg>'"
        />

        <x-ui.stat-card
            title="Abertos"
            :value="$stats['aberto']"
            color="yellow"
            subtitle="Aguardando atendimento"
            :icon="'<svg class=\'h-5 w-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z\'/></svg>'"
        />

        <x-ui.stat-card
            title="Em Andamento"
            :value="$stats['em_andamento']"
            color="blue"
            subtitle="Sendo resolvidos"
            :icon="'<svg class=\'h-5 w-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M13 10V3L4 14h7v7l9-11h-7z\'/></svg>'"
        />

        <x-ui.stat-card
            title="Resolvidos"
            :value="$stats['resolvido']"
            color="green"
            subtitle="Concluidos com sucesso"
            :icon="'<svg class=\'h-5 w-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z\'/></svg>'"
        />
    </div>

    <!-- Recent Chamados -->
    <x-ui.card :padding="false">
        <div class="px-6 py-4 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Chamados Recentes</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Ultimos chamados registrados no sistema</p>
                </div>
                <x-ui.button variant="outline" size="sm" :href="route('chamados.index')">
                    Ver todos
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </x-ui.button>
            </div>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentChamados as $chamado)
                <a href="{{ route('chamados.show', $chamado) }}" class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50/50 transition-colors group">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl flex-shrink-0
                        {{ $chamado->status->value === 'resolvido' ? 'bg-emerald-50 text-emerald-600' :
                           ($chamado->status->value === 'em_andamento' ? 'bg-blue-50 text-blue-600' : 'bg-amber-50 text-amber-600') }}">
                        @if($chamado->status->value === 'resolvido')
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        @elseif($chamado->status->value === 'em_andamento')
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        @else
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate group-hover:text-primary transition-colors">{{ $chamado->titulo }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $chamado->solicitante->name ?? 'N/A' }} &middot; {{ $chamado->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        @php
                            $prioVariant = match($chamado->prioridade->value) {
                                'alta' => 'destructive',
                                'media' => 'warning',
                                'baixa' => 'success',
                                default => 'default',
                            };
                        @endphp
                        <x-ui.badge :variant="$prioVariant">{{ ucfirst($chamado->prioridade->value) }}</x-ui.badge>
                        <svg class="h-4 w-4 text-gray-300 group-hover:text-gray-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            @empty
                <x-ui.empty-state
                    title="Nenhum chamado ainda"
                    description="Crie seu primeiro chamado para comecar."
                    actionLabel="Novo Chamado"
                    :actionUrl="route('chamados.create')"
                />
            @endforelse
        </div>
    </x-ui.card>
</x-app-layout>
