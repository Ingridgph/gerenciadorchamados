<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Login - {{ config('app.name', 'Chamados') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased login-gradient min-h-screen flex items-center justify-center p-4">
        <!-- Background decorative elements -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-violet-500/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-blue-600/5 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-primary shadow-lg shadow-blue-500/25 mb-4">
                    <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white">Gerenciador de Chamados</h1>
                <p class="mt-2 text-sm text-gray-400">Acesse sua conta para continuar</p>
            </div>

            <!-- Card -->
            <div class="login-card animate-in">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <p class="mt-8 text-center text-xs text-gray-500">
                &copy; {{ date('Y') }} Gerenciador de Chamados. Todos os direitos reservados.
            </p>
        </div>
    </body>
</html>
