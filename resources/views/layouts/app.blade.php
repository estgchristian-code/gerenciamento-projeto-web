<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Painel' }} - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-gray-50 font-sans antialiased">
    <header class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" aria-label="Navegação principal">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-8">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2" aria-label="{{ config('app.name') }}">
                        <svg class="h-8 w-8 text-gray-900" fill="currentColor" viewBox="0 0 120 120" aria-hidden="true">
                            <path d="M60 0C26.86 0 0 26.86 0 60s26.86 60 60 60 60-26.86 60-60S93.14 0 60 0zm0 108C33.42 108 12 86.58 12 60S33.42 12 60 12s48 21.42 48 48-21.42 48-48 48z"/>
                        </svg>
                        <span class="text-xl font-semibold text-gray-900 hidden sm:block">{{ config('app.name') }}</span>
                    </a>

                    <div class="hidden md:flex items-center gap-6" aria-label="Navegação principal">
                        <a href="{{ route('dashboard') }}"
                           class="text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors duration-150">
                            Dashboard
                        </a>
                        <a href="{{ route('projects.index') }}"
                           class="text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors duration-150">
                            Projetos
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex items-center gap-3">
                        <span class="text-sm text-gray-700" aria-hidden="true">{{ auth()->user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST" aria-label="Sair do sistema">
                            @csrf
                            <button type="submit"
                                    class="text-sm text-gray-600 hover:text-gray-900 underline underline-offset-1 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 rounded">
                                Sair
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}

        @if (isset($footer))
            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                {{ $footer }}
            </div>
        @endif
    </main>

    @livewireScripts
</body>
</html>