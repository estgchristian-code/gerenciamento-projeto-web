<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Autenticação' }} - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-100 via-white to-indigo-100/70 flex items-center justify-center py-12 px-4 font-sans text-slate-900 antialiased sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-block" aria-label="{{ config('app.name') }}">
                <svg class="mx-auto h-10 w-10 text-indigo-600 drop-shadow-sm" fill="currentColor" viewBox="0 0 120 120" aria-hidden="true">
                    <path d="M60 0C26.86 0 0 26.86 0 60s26.86 60 60 60 60-26.86 60-60S93.14 0 60 0zm0 108C33.42 108 12 86.58 12 60S33.42 12 60 12s48 21.42 48 48-21.42 48-48 48z"/>
                </svg>
            </a>
            <h1 class="mt-6 text-2xl font-semibold text-slate-900">{{ $title ?? 'Entrar' }}</h1>
            <p class="mt-2 text-sm text-slate-500">{{ $subtitle ?? 'Acesse sua conta para continuar' }}</p>
        </div>

        <div class="rounded-xl border border-indigo-100/80 bg-white/95 shadow-xl shadow-indigo-500/10">
            <div class="p-6 sm:p-8">
                {{ $slot }}

                @if (isset($footer))
                    <div class="mt-6 pt-6 border-t border-slate-100 text-center">
                        {{ $footer }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>