<x-app-layout>
    @section('page-title', 'Chamados')

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Chamados</h2>
            <p class="mt-1 text-sm text-gray-500">Gerencie todos os chamados do sistema.</p>
        </div>
        <x-ui.button :href="route('chamados.create')">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Novo Chamado
        </x-ui.button>
    </div>

    <!-- Filters -->
    <x-ui.card class="mb-6">
        <form method="GET" action="{{ route('chamados.index') }}" class="flex flex-col sm:flex-row items-start sm:items-end gap-3">
            <div class="w-full sm:w-64">
                <x-ui.input
                    name="search"
                    placeholder="Buscar por titulo ou descricao..."
                    :value="request('search')"
                    :icon="'<svg class=\'h-4 w-4 text-gray-400\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z\'/></svg>'"
                />
            </div>
            <div class="w-full sm:w-44">
                <x-ui.select
                    name="status"
                    :options="['' => 'Todos os Status', 'aberto' => 'Aberto', 'em_andamento' => 'Em Andamento', 'resolvido' => 'Resolvido']"
                    :value="request('status')"
                />
            </div>
            <div class="w-full sm:w-44">
                <x-ui.select
                    name="prioridade"
                    :options="['' => 'Todas Prioridades', 'baixa' => 'Baixa', 'media' => 'Media', 'alta' => 'Alta']"
                    :value="request('prioridade')"
                />
            </div>
            <div class="flex gap-2 mb-5">
                <x-ui.button type="submit" variant="secondary" size="sm">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filtrar
                </x-ui.button>
                @if(request()->anyFilled(['search', 'status', 'prioridade']))
                    <x-ui.button variant="ghost" size="sm" :href="route('chamados.index')">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Limpar
                    </x-ui.button>
                @endif
            </div>
        </form>
    </x-ui.card>

    <!-- Table -->
    <x-ui.card :padding="false">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">ID</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Titulo</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 hidden md:table-cell">Solicitante</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Prioridade</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 hidden lg:table-cell">Data</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Acoes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($chamados as $chamado)
                        <tr class="hover:bg-gray-50/50 transition-colors table-row-enter">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-mono font-semibold text-gray-500">#{{ $chamado->id }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('chamados.show', $chamado) }}" class="text-sm font-medium text-gray-900 hover:text-primary transition-colors">
                                    {{ Str::limit($chamado->titulo, 45) }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                <div class="flex items-center gap-2">
                                    <div class="flex h-7 w-7 items-center justify-center rounded-full bg-gray-100 text-xs font-semibold text-gray-600">
                                        {{ strtoupper(substr($chamado->solicitante->name ?? 'N', 0, 1)) }}
                                    </div>
                                    <span class="text-sm text-gray-600">{{ $chamado->solicitante->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $prioVariant = match($chamado->prioridade->value) {
                                        'alta' => 'destructive',
                                        'media' => 'warning',
                                        'baixa' => 'success',
                                        default => 'default',
                                    };
                                @endphp
                                <x-ui.badge :variant="$prioVariant">
                                    @if($chamado->prioridade->value === 'alta')
                                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L10 4.414l-3.293 3.293a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                                    @endif
                                    {{ ucfirst($chamado->prioridade->value) }}
                                </x-ui.badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusVariant = match($chamado->status->value) {
                                        'aberto' => 'warning',
                                        'em_andamento' => 'info',
                                        'resolvido' => 'success',
                                        default => 'default',
                                    };
                                    $statusLabel = match($chamado->status->value) {
                                        'aberto' => 'Aberto',
                                        'em_andamento' => 'Em Andamento',
                                        'resolvido' => 'Resolvido',
                                        default => $chamado->status->value,
                                    };
                                @endphp
                                <x-ui.badge :variant="$statusVariant">
                                    <span class="w-1.5 h-1.5 rounded-full
                                        {{ $chamado->status->value === 'resolvido' ? 'bg-emerald-500' :
                                           ($chamado->status->value === 'em_andamento' ? 'bg-blue-500' : 'bg-amber-500') }}">
                                    </span>
                                    {{ $statusLabel }}
                                </x-ui.badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap hidden lg:table-cell">
                                <span class="text-sm text-gray-500">{{ $chamado->created_at->format('d/m/Y') }}</span>
                                <span class="text-xs text-gray-400 block">{{ $chamado->created_at->format('H:i') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('chamados.show', $chamado) }}"
                                       class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-gray-400 hover:text-primary hover:bg-blue-50 transition-all"
                                       title="Ver Detalhes">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    @can('delete', $chamado)
                                        <form action="{{ route('chamados.destroy', $chamado) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este chamado?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-all"
                                                    title="Excluir">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-ui.empty-state
                                    title="Nenhum chamado encontrado"
                                    description="Nenhum chamado corresponde aos filtros selecionados. Tente ajustar sua busca ou crie um novo chamado."
                                    actionLabel="Novo Chamado"
                                    :actionUrl="route('chamados.create')"
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>

    @if($chamados->hasPages())
        <div class="mt-6">
            {{ $chamados->links() }}
        </div>
    @endif
</x-app-layout>
