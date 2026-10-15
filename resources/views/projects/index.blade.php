<x-slot name="title">Meus Projetos</x-slot>

<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Meus Projetos</h1>
            <p class="mt-1 text-sm text-slate-500">Organize, acompanhe e gerencie todos os seus projetos em um só lugar.</p>
        </div>
        <a href="{{ route('projects.create') }}" class="btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Novo Projeto
        </a>
    </div>

    <div class="card p-4 sm:p-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                    </svg>
                </span>
                <label for="search" class="sr-only">Buscar projetos</label>
                <input type="text"
                       id="search"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Buscar por nome do projeto..."
                       autocomplete="off"
                       class="input pl-10">
            </div>

            <div class="sm:w-56">
                <label for="status-filter" class="sr-only">Filtrar por status</label>
                <select id="status-filter" wire:model.live="statusFilter" class="input">
                    <option value="">Todos os status</option>
                    @foreach ($statusLabels as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <span wire:loading.inline-flex wire:target="search,statusFilter" class="items-center gap-2 text-sm text-slate-500">
                    <svg class="h-4 w-4 animate-spin text-indigo-600" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Filtrando...
                </span>
                @if ($hasFilters)
                    <button type="button" wire:click="clearFilters" class="btn-ghost whitespace-nowrap">Limpar filtros</button>
                @endif
            </div>
        </div>
    </div>

    <div class="relative">
        <div wire:loading.flex wire:target="search,statusFilter,clearFilters,page"
             class="absolute inset-0 z-10 hidden items-center justify-center rounded-xl bg-white/60 backdrop-blur-sm">
            <span class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-medium text-slate-600 shadow-sm ring-1 ring-slate-200">
                <svg class="h-4 w-4 animate-spin text-indigo-600" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Carregando projetos...
            </span>
        </div>

        @if ($projects->isNotEmpty())
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($projects as $project)
                    <article class="card flex flex-col p-5 transition-shadow duration-150 hover:shadow-md">
                        <div class="flex items-start justify-between gap-3">
                            <a href="{{ route('projects.show', $project) }}"
                               class="line-clamp-2 text-base font-semibold text-slate-900 hover:text-indigo-600">
                                {{ $project->name }}
                            </a>
                            <x-status-badge :status="$project->status" :label="$statusLabels[$project->status] ?? $project->status" class="flex-shrink-0" />
                        </div>

                        <p class="mt-2 line-clamp-3 flex-1 text-sm text-slate-600">
                            {{ $project->description ?: 'Sem descrição informada.' }}
                        </p>

                        <dl class="mt-4 space-y-2 border-t border-slate-100 pt-4 text-sm text-slate-500">
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>
                                    @if ($project->start_date)
                                        Início {{ $project->start_date->format('d/m/Y') }}
                                    @else
                                        Sem data de início
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>
                                    @if ($project->due_date)
                                        Vencimento {{ $project->due_date->format('d/m/Y') }}
                                    @else
                                        Sem vencimento
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <span>{{ $project->tasks_count }} {{ $project->tasks_count === 1 ? 'tarefa' : 'tarefas' }}</span>
                            </div>
                        </dl>

                        <div class="mt-5 flex items-center justify-between gap-2 border-t border-slate-100 pt-4">
                            <a href="{{ route('projects.show', $project) }}" class="btn-secondary px-3 py-2">
                                Ver detalhes
                            </a>
                            <div class="flex items-center gap-1">
                                <a href="{{ route('projects.edit', $project) }}" class="btn-ghost" aria-label="Editar {{ $project->name }}">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Editar
                                </a>
                                <form wire:submit.prevent="delete({{ $project->id }})"
                                      onsubmit="return confirm('Tem certeza que deseja excluir este projeto? Todas as tarefas vinculadas também serão removidas.');">
                                    <button type="submit" class="btn-danger" aria-label="Excluir {{ $project->name }}">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Excluir
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            @if ($projects->hasPages())
                <div class="mt-6">
                    {{ $projects->links() }}
                </div>
            @endif
        @else
            <div class="card flex flex-col items-center justify-center px-6 py-16 text-center">
                <span class="flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                    </svg>
                </span>
                @if ($hasFilters)
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Nenhum projeto encontrado</h3>
                    <p class="mt-1 max-w-sm text-sm text-slate-500">
                        Não encontramos projetos com os filtros aplicados. Tente ajustar a busca ou limpar os filtros.
                    </p>
                    <button type="button" wire:click="clearFilters" class="btn-secondary mt-5">Limpar filtros</button>
                @else
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Nenhum projeto cadastrado</h3>
                    <p class="mt-1 max-w-sm text-sm text-slate-500">
                        Você ainda não possui projetos. Use o botão <span class="font-medium text-slate-700">Novo Projeto</span> acima para criar o primeiro.
                    </p>
                @endif
            </div>
        @endif
    </div>
</div>
