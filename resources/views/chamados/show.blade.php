<x-app-layout>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-3xl font-bold tracking-tight">Chamado #{{ $chamado->id }}</h2>
        <a href="{{ route('chamados.index') }}">
            <x-ui.button variant="outline">Voltar</x-ui.button>
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid gap-6 md:grid-cols-3">
        <div class="md:col-span-2 space-y-6">
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6 border-b">
                    <h3 class="font-semibold leading-none tracking-tight">{{ $chamado->titulo }}</h3>
                    <p class="text-sm text-muted-foreground">
                        Criado em {{ $chamado->created_at->format('d/m/Y H:i') }} por {{ $chamado->solicitante->name ?? 'Desconhecido' }}
                    </p>
                </div>
                <div class="p-6">
                    <p class="whitespace-pre-line">{{ $chamado->descricao }}</p>
                </div>
            </div>

            <!-- TODO: Add Comments Section here if needed later -->
        </div>

        <div class="md:col-span-1 space-y-6">
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6 border-b">
                    <h3 class="font-semibold leading-none tracking-tight">Detalhes</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <span class="text-sm font-medium text-muted-foreground block">Status</span>
                        @php
                            $statusVariant = match($chamado->status->value) {
                                'aberto' => 'secondary',
                                'em_andamento' => 'info',
                                'resolvido' => 'success',
                                default => 'default',
                            };
                        @endphp
                        <x-ui.badge :variant="$statusVariant" class="mt-1">{{ $chamado->status->value }}</x-ui.badge>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-muted-foreground block">Prioridade</span>
                        @php
                            $prioVariant = match($chamado->prioridade->value) {
                                'alta' => 'destructive',
                                'media' => 'warning',
                                'baixa' => 'success',
                                default => 'default',
                            };
                        @endphp
                        <x-ui.badge :variant="$prioVariant" class="mt-1">{{ $chamado->prioridade->value }}</x-ui.badge>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-muted-foreground block">Responsável</span>
                        <span class="text-sm">{{ $chamado->responsavel->name ?? 'Não atribuído' }}</span>
                    </div>
                    @if($chamado->resolved_at)
                    <div>
                        <span class="text-sm font-medium text-muted-foreground block">Resolvido em</span>
                        <span class="text-sm">{{ $chamado->resolved_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6 border-b">
                    <h3 class="font-semibold leading-none tracking-tight">Atualizar Status</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('chamados.updateStatus', $chamado) }}">
                        @csrf
                        @method('PATCH')
                        <x-ui.select 
                            name="status" 
                            :value="$chamado->status->value"
                            :options="[
                                'aberto' => 'Aberto',
                                'em_andamento' => 'Em Andamento',
                                'resolvido' => 'Resolvido'
                            ]" 
                        />
                        <x-ui.button class="w-full mt-2">Atualizar</x-ui.button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
