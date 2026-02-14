<x-guest-layout>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <x-ui.input label="Email" name="email" type="email" required autofocus />
        <x-ui.input label="Senha" name="password" type="password" required />

        <div class="flex items-center justify-end mt-4">
            <x-ui.button class="w-full">
                Entrar
            </x-ui.button>
        </div>
    </form>
</x-guest-layout>
