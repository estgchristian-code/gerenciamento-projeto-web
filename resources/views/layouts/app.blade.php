<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Projetos' }} - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased">
    <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/80 backdrop-blur">
        <nav class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8" aria-label="Navegação principal">
            <div class="flex items-center gap-3">
                <a href="{{ route('projects.index') }}" class="flex items-center gap-2.5" aria-label="{{ config('app.name') }}">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-indigo-500 to-violet-600 text-white shadow-sm">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                        </svg>
                    </span>
                    <span class="text-base font-semibold tracking-tight text-slate-900 sm:text-lg">{{ config('app.name') }}</span>
                </a>

                <div class="ml-6 hidden items-center gap-1 md:flex">
                    <a href="{{ route('projects.index') }}"
                       class="rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('projects.*') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        Projetos
                    </a>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="button"
                        class="btn-ghost md:hidden"
                        x-data
                        @click="$dispatch('toggle-mobile-menu')"
                        aria-label="Abrir menu">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </nav>

        <div x-data="{ open: false }"
             @toggle-mobile-menu.window="open = !open"
             x-show="open"
             x-cloak
             x-transition
             class="border-t border-slate-200 bg-white md:hidden">
            <div class="space-y-1 px-4 py-3">
                <a href="{{ route('projects.index') }}"
                   class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 {{ request()->routeIs('projects.*') ? 'bg-slate-100 text-slate-900' : '' }}">
                    Projetos
                </a>
            </div>
        </div>
    </header>

    @if (session('success') || session('error'))
        <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
            @if (session('success'))
                <div x-data="{ show: true }"
                     x-init="setTimeout(() => show = false, 4000)"
                     x-show="show"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm"
                     role="status">
                    <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="flex-1 text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                    <button type="button" @click="show = false" class="text-emerald-600 hover:text-emerald-800" aria-label="Fechar">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50 p-4 shadow-sm" role="alert">
                    <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.74-3L13.74 4a2 2 0 00-3.48 0L3.33 16a2 2 0 001.74 3z"/>
                    </svg>
                    <p class="flex-1 text-sm font-medium text-rose-800">{{ session('error') }}</p>
                </div>
            @endif
        </div>
    @endif

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        {{ $slot }}
    </main>

    <footer class="mx-auto max-w-7xl px-4 pb-8 pt-4 sm:px-6 lg:px-8">
        <p class="text-center text-xs text-slate-400">
            {{ config('app.name') }} &middot; {{ now()->year }}
        </p>
    </footer>

    @livewireScripts
</body>
</html>
