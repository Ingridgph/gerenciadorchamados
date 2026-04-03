<x-app-layout>
    @section('page-title', 'Novo Chamado')

    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
            <a href="{{ route('chamados.index') }}" class="hover:text-primary transition-colors">Chamados</a>
            <svg class="h-4 w-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-900 font-medium">Novo Chamado</span>
        </div>
        <h2 class="text-2xl font-bold tracking-tight text-gray-900">Novo Chamado</h2>
        <p class="mt-1 text-sm text-gray-500">Preencha as informacoes abaixo para abrir um novo chamado.</p>
    </div>

    <div class="max-w-2xl">
        <x-ui.card>
            <form method="POST" action="{{ route('chamados.store') }}" class="space-y-1">
                @csrf

                <x-ui.input
                    label="Titulo"
                    name="titulo"
                    placeholder="Ex: Erro ao acessar o sistema de pagamentos"
                    required
                    autofocus
                />

                <div class="space-y-1.5 mb-5">
                    <label for="descricao" class="block text-sm font-medium text-gray-700">
                        Descricao
                    </label>
                    <textarea
                        id="descricao"
                        name="descricao"
                        rows="5"
                        placeholder="Descreva o problema com o maximo de detalhes possivel..."
                        required
                        class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all duration-150 resize-none"
                    >{{ old('descricao') }}</textarea>
                    @error('descricao')
                        <p class="flex items-center gap-1 text-xs font-medium text-red-600 mt-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <x-ui.select
                    label="Prioridade"
                    name="prioridade"
                    placeholder="Selecione a prioridade"
                    :options="[
                        'baixa' => 'Baixa - Pode ser resolvido sem urgencia',
                        'media' => 'Media - Importante, mas nao urgente',
                        'alta' => 'Alta - Requer atencao imediata'
                    ]"
                    required
                />

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <x-ui.button variant="ghost" :href="route('chamados.index')">
                        Cancelar
                    </x-ui.button>
                    <x-ui.button type="submit">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Criar Chamado
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
</x-app-layout>
