<x-slot name="title">Entrar</x-slot>
<x-slot name="subtitle">Acesse sua conta para continuar</x-slot>

<x-slot name="footer">
    <p class="text-sm text-gray-600">
        Não tem uma conta?
        <a href="{{ route('register') }}" class="font-medium text-gray-900 hover:text-gray-700 underline underline-offset-2">
            Cadastre-se
        </a>
    </p>
</x-slot>

<form wire:submit="login" class="space-y-5" novalidate>
    @if ($errors->any())
        <div class="rounded-lg bg-red-50 border border-red-100 p-4" role="alert">
            <div class="flex">
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-red-800">Erro ao tentar entrar</h3>
                    <div class="mt-1 text-sm text-red-700">
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
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">E-mail</label>
        <input
            id="email"
            name="email"
            type="email"
            wire:model.defer="email"
            required
            autocomplete="email"
            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-transparent transition-colors duration-150 sm:text-sm @error('email') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-200 @enderror"
            placeholder="seu@email.com"
            aria-describedby="{{ $errors->has('email') ? 'email-error' : '' }}"
        >
        @error('email')
            <p id="email-error" class="mt-1.5 text-sm text-red-600" role="alert">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Senha</label>
        <input
            id="password"
            name="password"
            type="password"
            wire:model.defer="password"
            required
            autocomplete="current-password"
            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-transparent transition-colors duration-150 sm:text-sm @error('password') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-200 @enderror"
            placeholder="••••••••"
            aria-describedby="{{ $errors->has('password') ? 'password-error' : '' }}"
        >
        @error('password')
            <p id="password-error" class="mt-1.5 text-sm text-red-600" role="alert">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <input
                id="remember"
                name="remember"
                type="checkbox"
                wire:model.defer="remember"
                class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-colors duration-150"
                aria-describedby="remember-desc"
            >
            <label for="remember" class="ml-2 block text-sm text-gray-700">Lembrar-me</label>
        </div>

        <div class="text-sm">
            <a href="#" class="text-gray-500 hover:text-gray-700 underline underline-offset-1 transition-colors duration-150" aria-label="Recuperação de senha não implementada">
                Esqueceu a senha?
            </a>
        </div>
    </div>

    <div>
        <button
            type="submit"
            class="group relative w-full flex justify-center items-center py-2.5 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-150"
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