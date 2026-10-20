<x-slot name="title">Tarefas</x-slot>

<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="bg-gradient-to-r from-blue-800 via-indigo-700 to-violet-700 bg-clip-text text-2xl font-bold tracking-tight text-transparent sm:text-3xl">Tarefas</h1>
            <p class="mt-1 text-sm text-slate-500">Acompanhe o andamento de todas as tarefas dos seus projetos.</p>
        </div>
        <a href="{{ route('tasks.create') }}" class="btn-primary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nova Tarefa
        </a>
    </div>

    <div class="card p-4 sm:p-5">
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
            <div class="relative sm:col-span-2 xl:col-span-1">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-400/80">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                    </svg>
                </span>
                <label for="search" class="sr-only">Buscar tarefas</label>
                <input type="text"
                       id="search"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Buscar por título da tarefa..."
                       autocomplete="off"
                       class="input pl-10">
            </div>

            <div>
                <label for="status-filter" class="sr-only">Filtrar por status</label>
                <select id="status-filter" wire:model.live="statusFilter" class="input">
                    <option value="">Todos os status</option>
                    @foreach ($statusLabels as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="priority-filter" class="sr-only">Filtrar por prioridade</label>
                <select id="priority-filter" wire:model.live="priorityFilter" class="input">
                    <option value="">Todas as prioridades</option>
                    @foreach ($priorityLabels as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="project-filter" class="sr-only">Filtrar por projeto</label>
                <select id="project-filter" wire:model.live="projectFilter" class="input">
                    <option value="">Todos os projetos</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-3 flex items-center justify-between gap-2">
            <span wire:loading.inline-flex wire:target="search,statusFilter,priorityFilter,projectFilter" class="items-center gap-2 text-sm text-slate-500">
                <svg class="h-4 w-4 animate-spin text-indigo-600" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Filtrando...
            </span>
            @if ($hasFilters)
                <button type="button" wire:click="clearFilters" class="btn-ghost ml-auto whitespace-nowrap">Limpar filtros</button>
            @endif
        </div>
    </div>

    <div class="relative">
        <div wire:loading.flex wire:target="search,statusFilter,priorityFilter,projectFilter,clearFilters,page"
             class="absolute inset-0 z-10 hidden items-center justify-center rounded-xl bg-white/60 backdrop-blur-sm">
            <span class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-medium text-indigo-600 shadow-sm shadow-indigo-500/10 ring-1 ring-indigo-100">
                <svg class="h-4 w-4 animate-spin text-indigo-600" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Carregando tarefas...
            </span>
        </div>

        @if ($tasks->isNotEmpty())
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($tasks as $task)
                    <article class="card flex flex-col p-5 transition-shadow duration-150 hover:border-indigo-200/80 hover:shadow-[0_1px_2px_rgba(15,23,42,0.04),0_20px_48px_-24px_rgba(79,70,229,0.45)]">
                        <div class="flex items-start justify-between gap-3">
                            <a href="{{ route('tasks.show', $task) }}"
                               class="line-clamp-2 break-words text-base font-semibold text-slate-900 hover:text-indigo-600">
                                {{ $task->title }}
                            </a>
                            <x-status-badge :status="$task->status" :label="$task->statusLabel" class="flex-shrink-0" />
                        </div>

                        <p class="mt-2 line-clamp-2 flex-1 break-words text-sm text-slate-600">
                            {{ $task->description ?: 'Sem descrição informada.' }}
                        </p>

                        <dl class="mt-4 space-y-2 border-t border-slate-100 pt-4 text-sm text-slate-500">
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-indigo-400/80" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                                </svg>
                                @if ($task->project)
                                    <a href="{{ route('projects.show', $task->project) }}" class="truncate hover:text-indigo-600">
                                        {{ $task->project->name }}
                                    </a>
                                @else
                                    <span class="italic">Sem projeto</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-indigo-400/80" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>
                                    @if ($task->due_date)
                                        Prazo {{ $task->due_date->format('d/m/Y') }}
                                    @else
                                        Sem prazo
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-slate-400">Prioridade</span>
                                <x-priority-badge :priority="$task->priority" :label="$task->priorityLabel" />
                            </div>
                        </dl>

                        <div class="mt-5 flex items-center justify-between gap-2 border-t border-slate-100 pt-4">
                            <a href="{{ route('tasks.show', $task) }}" class="btn-secondary px-3 py-2">
                                Ver detalhes
                            </a>
                            <div class="flex items-center gap-1">
                                <a href="{{ route('tasks.edit', $task) }}" class="btn-ghost" aria-label="Editar {{ $task->title }}">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Editar
                                </a>
                                <form wire:submit.prevent="delete({{ $task->id }})"
                                      onsubmit="return confirm('Tem certeza que deseja excluir esta tarefa?');">
                                    <button type="submit" class="btn-danger" aria-label="Excluir {{ $task->title }}">
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

            @if ($tasks->hasPages())
                <div class="mt-6">
                    {{ $tasks->links() }}
                </div>
            @endif
        @else
            <div class="card flex flex-col items-center justify-center px-6 py-16 text-center">
                <span class="flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-indigo-50 to-violet-50 text-indigo-400">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </span>
                @if ($hasFilters)
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Nenhuma tarefa encontrada</h3>
                    <p class="mt-1 max-w-sm text-sm text-slate-500">
                        Não encontramos tarefas com os filtros aplicados. Tente ajustar a busca ou limpar os filtros.
                    </p>
                    <button type="button" wire:click="clearFilters" class="btn-secondary mt-5">Limpar filtros</button>
                @else
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Nenhuma tarefa cadastrada</h3>
                    <p class="mt-1 max-w-sm text-sm text-slate-500">
                        Você ainda não possui tarefas. Use o botão <span class="font-medium text-slate-700">Nova Tarefa</span> acima para criar a primeira.
                    </p>
                @endif
            </div>
        @endif
    </div>
</div>
