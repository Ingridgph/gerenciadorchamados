<x-app-layout>
    <div class="mb-6">
        <h2 class="text-3xl font-bold tracking-tight">Dashboard</h2>
    </div>

    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <x-ui.card>
            <div class="flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="tracking-tight text-sm font-medium">Total de Chamados</h3>
            </div>
            <div class="text-2xl font-bold">{{ $stats['total'] }}</div>
        </x-ui.card>
        <x-ui.card>
            <div class="flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="tracking-tight text-sm font-medium">Abertos</h3>
            </div>
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['aberto'] }}</div>
        </x-ui.card>
        <x-ui.card>
            <div class="flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="tracking-tight text-sm font-medium">Em Andamento</h3>
            </div>
            <div class="text-2xl font-bold text-blue-600">{{ $stats['em_andamento'] }}</div>
        </x-ui.card>
        <x-ui.card>
            <div class="flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="tracking-tight text-sm font-medium">Resolvidos</h3>
            </div>
            <div class="text-2xl font-bold text-green-600">{{ $stats['resolvido'] }}</div>
        </x-ui.card>
    </div>
</x-app-layout>
