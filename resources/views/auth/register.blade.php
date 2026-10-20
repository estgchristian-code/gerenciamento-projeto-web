<x-slot name="title">Cadastrar</x-slot>
<x-slot name="subtitle">Crie sua conta para começar</x-slot>

<x-slot name="footer">
    <p class="text-sm text-slate-600">
        Já tem uma conta?
        <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-violet-600">
            Entrar
        </a>
    </p>
</x-slot>

<form wire:submit="register" class="space-y-6">
    @if ($errors->any())
        <div class="rounded-md bg-gradient-to-r from-rose-50 to-red-50 p-4 shadow-sm shadow-rose-600/10 ring-1 ring-inset ring-rose-200/80" role="alert">
            <div class="flex">
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-rose-800">Erro ao tentar cadastrar</h3>
                    <div class="mt-2 text-sm text-rose-700">
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
        <label for="name" class="sr-only">Nome</label>
        <input
            id="name"
            name="name"
            type="text"
            wire:model.defer="name"
            required
            autocomplete="name"
            class="appearance-none rounded-md relative block w-full px-3 py-2 border border-slate-300 placeholder-slate-400 text-slate-900 focus:outline-none focus:ring-indigo-500/30 focus:border-indigo-500 focus:z-10 sm:text-sm @error('name') border-rose-300 text-rose-900 placeholder-rose-300 focus:border-rose-500 focus:ring-rose-500/30 @enderror"
            placeholder="Nome"
        >
        @error('name')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="sr-only">E-mail</label>
        <input
            id="email"
            name="email"
            type="email"
            wire:model.defer="email"
            required
            autocomplete="email"
            class="appearance-none rounded-md relative block w-full px-3 py-2 border border-slate-300 placeholder-slate-400 text-slate-900 focus:outline-none focus:ring-indigo-500/30 focus:border-indigo-500 focus:z-10 sm:text-sm @error('email') border-rose-300 text-rose-900 placeholder-rose-300 focus:border-rose-500 focus:ring-rose-500/30 @enderror"
            placeholder="E-mail"
        >
        @error('email')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="sr-only">Senha</label>
        <input
            id="password"
            name="password"
            type="password"
            wire:model.defer="password"
            required
            autocomplete="new-password"
            class="appearance-none rounded-md relative block w-full px-3 py-2 border border-slate-300 placeholder-slate-400 text-slate-900 focus:outline-none focus:ring-indigo-500/30 focus:border-indigo-500 focus:z-10 sm:text-sm @error('password') border-rose-300 text-rose-900 placeholder-rose-300 focus:border-rose-500 focus:ring-rose-500/30 @enderror"
            placeholder="Senha (mínimo 8 caracteres)"
        >
        @error('password')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password_confirmation" class="sr-only">Confirmar senha</label>
        <input
            id="password_confirmation"
            name="password_confirmation"
            type="password"
            wire:model.defer="password_confirmation"
            required
            autocomplete="new-password"
            class="appearance-none rounded-md relative block w-full px-3 py-2 border border-slate-300 placeholder-slate-400 text-slate-900 focus:outline-none focus:ring-indigo-500/30 focus:border-indigo-500 focus:z-10 sm:text-sm @error('password_confirmation') border-rose-300 text-rose-900 placeholder-rose-300 focus:border-rose-500 focus:ring-rose-500/30 @enderror"
            placeholder="Confirmar senha"
        >
        @error('password_confirmation')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <button
            type="submit"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-blue-700 via-indigo-600 to-violet-600 shadow-md shadow-indigo-600/25 hover:from-blue-800 hover:via-indigo-700 hover:to-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
            wire:loading.attr="disabled"
        >
            <span wire:loading.remove>Cadastrar</span>
            <span wire:loading class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Cadastrando...
            </span>
        </button>
    </div>
</form>