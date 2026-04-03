<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Chamados') }} - Gerenciador</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50/50" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen flex">

            <!-- Mobile Overlay -->
            <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-300"
                 x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-in duration-200"
                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 @click="sidebarOpen = false"
                 class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm md:hidden" style="display: none;"></div>

            <!-- Sidebar -->
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                   class="fixed inset-y-0 left-0 z-50 w-[272px] bg-sidebar-bg transform transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:z-auto flex flex-col">

                <!-- Logo -->
                <div class="flex items-center gap-3 px-6 h-16 border-b border-white/[0.08]">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary">
                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-white tracking-tight">Chamados</span>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                    <p class="px-3 mb-2 text-[11px] font-semibold uppercase tracking-wider text-white/30">Menu</p>

                    <a href="{{ route('dashboard') }}"
                       class="sidebar-link {{ request()->routeIs('dashboard') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 12a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1v-7z"/>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('chamados.index') }}"
                       class="sidebar-link {{ request()->routeIs('chamados.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        Chamados
                    </a>
                </nav>

                <!-- User Section -->
                <div class="px-3 py-4 border-t border-white/[0.08]">
                    <div class="flex items-center gap-3 px-3 py-2">
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-blue-400 to-violet-500 text-white text-sm font-bold flex-shrink-0">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-white/40 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="sidebar-link sidebar-link-inactive w-full text-red-400/70 hover:text-red-400 hover:bg-red-500/10">
                            <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Sair
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 flex flex-col min-w-0">
                <!-- Top Bar -->
                <header class="sticky top-0 z-30 flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8 bg-white/80 backdrop-blur-md border-b border-gray-100">
                    <!-- Mobile menu button -->
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden flex items-center justify-center h-9 w-9 rounded-xl hover:bg-gray-100 transition-colors">
                        <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Page title (shown on desktop) -->
                    <div class="hidden md:block">
                        <h1 class="text-lg font-semibold text-gray-900">
                            @hasSection('page-title')
                                @yield('page-title')
                            @endif
                        </h1>
                    </div>

                    <!-- Right side -->
                    <div class="flex items-center gap-3">
                        <span class="hidden sm:inline-flex items-center gap-1.5 text-xs font-medium text-gray-500 bg-gray-50 px-3 py-1.5 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Online
                        </span>
                    </div>
                </header>

                <!-- Content -->
                <div class="flex-1 p-4 sm:p-6 lg:p-8 animate-in">
                    <x-ui.toast />
                    {{ $slot }}
                </div>
            </main>
        </div>
    </body>
</html>
