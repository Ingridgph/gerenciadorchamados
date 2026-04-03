<x-guest-layout>
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <x-ui.input
            label="Email"
            name="email"
            type="email"
            placeholder="seu@email.com"
            required
            autofocus
            :icon="'<svg class=\'h-4 w-4 text-gray-400\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207\'/></svg>'"
        />

        <x-ui.input
            label="Senha"
            name="password"
            type="password"
            placeholder="Digite sua senha"
            required
            :icon="'<svg class=\'h-4 w-4 text-gray-400\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z\'/></svg>'"
        />

        <x-ui.button class="w-full h-11" type="submit">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            Entrar
        </x-ui.button>
    </form>
</x-guest-layout>
