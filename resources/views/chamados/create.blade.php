<x-app-layout>
    <div class="mb-6">
        <h2 class="text-3xl font-bold tracking-tight">Novo Chamado</h2>
    </div>

    <div class="rounded-lg border bg-card text-card-foreground shadow-sm max-w-2xl">
        <div class="p-6">
            <form method="POST" action="{{ route('chamados.store') }}">
                @csrf

                <x-ui.input label="Título" name="titulo" required autofocus />

                <div class="grid w-full items-center gap-1.5 mb-4">
                    <label for="descricao" class="text-sm font-medium leading-none text-gray-700 dark:text-gray-300">
                        Descrição
                    </label>
                    <textarea 
                        id="descricao" 
                        name="descricao" 
                        rows="4" 
                        class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        required
                    >{{ old('descricao') }}</textarea>
                    @error('descricao')
                        <p class="text-[0.8rem] font-medium text-destructive mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <x-ui.select 
                    label="Prioridade" 
                    name="prioridade" 
                    :options="[
                        'baixa' => 'Baixa',
                        'media' => 'Média',
                        'alta' => 'Alta'
                    ]" 
                    required 
                />

                <div class="flex items-center justify-end mt-4">
                    <a href="{{ route('chamados.index') }}" class="text-sm font-medium text-muted-foreground hover:underline mr-4">
                        Cancelar
                    </a>
                    <x-ui.button>
                        Criar Chamado
                    </x-ui.button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
