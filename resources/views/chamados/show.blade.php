<x-app-layout>
    @section('page-title', 'Chamado #' . $chamado->id)

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('chamados.index') }}" class="hover:text-primary transition-colors">Chamados</a>
        <svg class="h-4 w-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-900 font-medium">#{{ $chamado->id }}</span>
    </div>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-8">
        <div class="space-y-2">
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">{{ $chamado->titulo }}</h2>
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
            </div>
            <p class="text-sm text-gray-500">
                Criado em {{ $chamado->created_at->format('d/m/Y') }} as {{ $chamado->created_at->format('H:i') }}
                &middot; {{ $chamado->created_at->diffForHumans() }}
            </p>
        </div>
        <x-ui.button variant="outline" :href="route('chamados.index')">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Voltar
        </x-ui.button>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description Card -->
            <x-ui.card :padding="false">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        Descricao
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-sm leading-relaxed text-gray-700 whitespace-pre-line">{{ $chamado->descricao }}</p>
                </div>
            </x-ui.card>

            <!-- Activity Log -->
            @if($chamado->logs && $chamado->logs->count() > 0)
                <x-ui.card :padding="false">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Historico de Atividades
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($chamado->logs as $log)
                                <div class="flex gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-50 flex-shrink-0">
                                        <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-700">
                                            <span class="font-medium">{{ $log->user->name ?? 'Sistema' }}</span>
                                            alterou o status de
                                            <x-ui.badge variant="default">{{ $log->de }}</x-ui.badge>
                                            para
                                            <x-ui.badge variant="info">{{ $log->para }}</x-ui.badge>
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $log->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </x-ui.card>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Details Card -->
            <x-ui.card :padding="false">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-900">Detalhes</h3>
                </div>
                <div class="p-6 space-y-5">
                    <!-- Status -->
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Status</span>
                        <x-ui.badge :variant="$statusVariant">
                            <span class="w-1.5 h-1.5 rounded-full
                                {{ $chamado->status->value === 'resolvido' ? 'bg-emerald-500' :
                                   ($chamado->status->value === 'em_andamento' ? 'bg-blue-500' : 'bg-amber-500') }}">
                            </span>
                            {{ $statusLabel }}
                        </x-ui.badge>
                    </div>

                    <!-- Priority -->
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Prioridade</span>
                        @php
                            $prioVariant = match($chamado->prioridade->value) {
                                'alta' => 'destructive',
                                'media' => 'warning',
                                'baixa' => 'success',
                                default => 'default',
                            };
                        @endphp
                        <x-ui.badge :variant="$prioVariant">{{ ucfirst($chamado->prioridade->value) }}</x-ui.badge>
                    </div>

                    <hr class="border-gray-100">

                    <!-- Requester -->
                    <div>
                        <span class="text-sm text-gray-500 block mb-2">Solicitante</span>
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-blue-400 to-violet-500 text-white text-xs font-bold flex-shrink-0">
                                {{ strtoupper(substr($chamado->solicitante->name ?? 'N', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $chamado->solicitante->name ?? 'Desconhecido' }}</p>
                                <p class="text-xs text-gray-400">{{ $chamado->solicitante->email ?? '' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Responsible -->
                    <div>
                        <span class="text-sm text-gray-500 block mb-2">Responsavel</span>
                        @if($chamado->responsavel)
                            <div class="flex items-center gap-2.5">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 text-white text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($chamado->responsavel->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $chamado->responsavel->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $chamado->responsavel->email }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-gray-400 italic">Nao atribuido</p>
                        @endif
                    </div>

                    @if($chamado->resolved_at)
                        <hr class="border-gray-100">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Resolvido em</span>
                            <span class="text-sm font-medium text-gray-900">{{ $chamado->resolved_at->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            </x-ui.card>

            <!-- Update Status Card -->
            <x-ui.card :padding="false">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Atualizar Status
                    </h3>
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
                        <x-ui.button class="w-full" type="submit" variant="success">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Atualizar
                        </x-ui.button>
                    </form>
                </div>
            </x-ui.card>
        </div>
    </div>
</x-app-layout>
