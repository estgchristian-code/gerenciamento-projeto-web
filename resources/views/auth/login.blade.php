<x-slot name="title">Entrar</x-slot>
<x-slot name="subtitle">Acesse sua conta para continuar</x-slot>

<x-slot name="footer">
    <p class="text-sm text-slate-600">
        Não tem uma conta?
        <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-violet-600 underline underline-offset-2">
            Cadastre-se
        </a>
    </p>
</x-slot>

<form wire:submit="login" class="space-y-5" novalidate>
    @if ($errors->any())
        <div class="rounded-lg border border-rose-200/80 bg-gradient-to-r from-rose-50 to-red-50 p-4 shadow-sm shadow-rose-600/10" role="alert">
            <div class="flex">
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-rose-800">Erro ao tentar entrar</h3>
                    <div class="mt-1 text-sm text-rose-700">
                        <ul class="list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div>
        <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">E-mail</label>
        <input
            id="email"
            name="email"
            type="email"
            wire:model.defer="email"
            required
            autocomplete="email"
            class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-colors duration-150 sm:text-sm @error('email') border-rose-300 text-rose-900 placeholder-rose-300 focus:ring-rose-500/30 @enderror"
            placeholder="seu@email.com"
            aria-describedby="{{ $errors->has('email') ? 'email-error' : '' }}"
        >
        @error('email')
            <p id="email-error" class="mt-1.5 text-sm text-rose-600" role="alert">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Senha</label>
        <input
            id="password"
            name="password"
            type="password"
            wire:model.defer="password"
            required
            autocomplete="current-password"
            class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-colors duration-150 sm:text-sm @error('password') border-rose-300 text-rose-900 placeholder-rose-300 focus:ring-rose-500/30 @enderror"
            placeholder="••••••••"
            aria-describedby="{{ $errors->has('password') ? 'password-error' : '' }}"
        >
        @error('password')
            <p id="password-error" class="mt-1.5 text-sm text-rose-600" role="alert">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <input
                id="remember"
                name="remember"
                type="checkbox"
                wire:model.defer="remember"
                class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-2 focus:ring-indigo-500/40 focus:ring-offset-2 transition-colors duration-150"
                aria-describedby="remember-desc"
            >
            <label for="remember" class="ml-2 block text-sm text-slate-700">Lembrar-me</label>
        </div>

        <div class="text-sm">
            <a href="#" class="text-slate-500 hover:text-indigo-600 underline underline-offset-1 transition-colors duration-150" aria-label="Recuperação de senha não implementada">
                Esqueceu a senha?
            </a>
        </div>
    </div>

    <div>
        <button
            type="submit"
            class="group relative w-full flex justify-center items-center py-2.5 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-blue-700 via-indigo-600 to-violet-600 shadow-md shadow-indigo-600/25 hover:from-blue-800 hover:via-indigo-700 hover:to-violet-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-150"
            wire:loading.attr="disabled"
        >
            <span wire:loading.remove>Entrar</span>
            <span wire:loading class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Entrando...
            </span>
        </button>
    </div>
</form>