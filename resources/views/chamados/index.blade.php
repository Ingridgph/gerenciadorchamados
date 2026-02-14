<x-app-layout>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold tracking-tight">Chamados</h2>
        <a href="{{ route('chamados.create') }}">
            <x-ui.button size="sm">Novo Chamado</x-ui.button>
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="mb-4">
        <form method="GET" action="{{ route('chamados.index') }}" class="flex flex-wrap items-center gap-2">
            <div class="w-full sm:w-auto sm:max-w-xs">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Buscar..." 
                    class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                >
            </div>
            <div class="w-full sm:w-auto">
                <select name="status" class="flex h-9 w-full items-center justify-between whitespace-nowrap rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50 [&>span]:line-clamp-1">
                    <option value="" {{ request('status') == '' ? 'selected' : '' }}>Status: Todos</option>
                    <option value="aberto" {{ request('status') == 'aberto' ? 'selected' : '' }}>Aberto</option>
                    <option value="em_andamento" {{ request('status') == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                    <option value="resolvido" {{ request('status') == 'resolvido' ? 'selected' : '' }}>Resolvido</option>
                </select>
            </div>
            <div class="w-full sm:w-auto">
                 <select name="prioridade" class="flex h-9 w-full items-center justify-between whitespace-nowrap rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50 [&>span]:line-clamp-1">
                    <option value="" {{ request('prioridade') == '' ? 'selected' : '' }}>Prioridade: Todas</option>
                    <option value="baixa" {{ request('prioridade') == 'baixa' ? 'selected' : '' }}>Baixa</option>
                    <option value="media" {{ request('prioridade') == 'media' ? 'selected' : '' }}>Média</option>
                    <option value="alta" {{ request('prioridade') == 'alta' ? 'selected' : '' }}>Alta</option>
                </select>
            </div>
            <div class="flex gap-2">
                <x-ui.button type="submit" size="sm" variant="secondary">Filtrar</x-ui.button>
                @if(request()->anyFilled(['search', 'status', 'prioridade']))
                    <a href="{{ route('chamados.index') }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-3">
                        Limpar
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="rounded-md border bg-card">
        <div class="relative w-full overflow-auto">
            <table class="w-full caption-bottom text-sm">
                <thead class="[&_tr]:border-b">
                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">ID</th>
                        <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Título</th>
                        <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Solicitante</th>
                        <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Prioridade</th>
                        <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Status</th>
                        <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Data</th>
                        <th class="h-10 px-4 text-right align-middle font-medium text-muted-foreground">Ações</th>
                    </tr>
                </thead>
                <tbody class="[&_tr:last-child]:border-0">
                    @forelse ($chamados as $chamado)
                        <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                            <td class="p-3 align-middle font-medium">#{{ $chamado->id }}</td>
                            <td class="p-3 align-middle">{{ $chamado->titulo }}</td>
                            <td class="p-3 align-middle">{{ $chamado->solicitante->name ?? 'N/A' }}</td>
                            <td class="p-3 align-middle">
                                @php
                                    $prioVariant = match($chamado->prioridade->value) {
                                        'alta' => 'destructive',
                                        'media' => 'warning',
                                        'baixa' => 'success',
                                        default => 'default',
                                    };
                                @endphp
                                <x-ui.badge :variant="$prioVariant">{{ $chamado->prioridade->value }}</x-ui.badge>
                            </td>
                            <td class="p-3 align-middle">
                                @php
                                    $statusVariant = match($chamado->status->value) {
                                        'aberto' => 'secondary',
                                        'em_andamento' => 'info',
                                        'resolvido' => 'success',
                                        default => 'default',
                                    };
                                @endphp
                                <x-ui.badge :variant="$statusVariant">{{ $chamado->status->value }}</x-ui.badge>
                            </td>
                            <td class="p-3 align-middle">{{ $chamado->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-3 align-middle text-right flex justify-end gap-2 items-center">
                                <a href="{{ route('chamados.show', $chamado) }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-8 w-8 text-primary" title="Ver Detalhes">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                @can('delete', $chamado)
                                    <form action="{{ route('chamados.destroy', $chamado) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este chamado?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-8 w-8 text-destructive" title="Excluir">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-4 text-center text-muted-foreground">Nenhum chamado encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $chamados->links() }}
    </div>
</x-app-layout>
