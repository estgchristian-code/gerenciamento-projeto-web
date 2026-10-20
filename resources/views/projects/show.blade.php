<x-slot name="title">{{ $project->name }}</x-slot>

<div class="mx-auto max-w-4xl space-y-6">
    <nav class="flex items-center gap-2 text-sm text-slate-500" aria-label="Navegação">
        <a href="{{ route('projects.index') }}" class="hover:text-indigo-600">Projetos</a>
        <svg class="h-4 w-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="truncate font-medium text-slate-700">{{ $project->name }}</span>
    </nav>

    <div class="card overflow-hidden">
        <div class="bg-gradient-to-br from-blue-700 via-indigo-600 to-violet-600 px-6 py-6 sm:px-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="min-w-0">
                    <h1 class="text-2xl font-bold tracking-tight text-white break-words sm:text-3xl">{{ $project->name }}</h1>
                    <div class="mt-3 flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white/20 px-2.5 py-1 text-xs font-medium text-white ring-1 ring-inset ring-white/30 backdrop-blur">
                            <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                            {{ $statusLabel }}
                        </span>
                        <span class="text-xs text-white/90">Atualizado em {{ $project->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
                <div class="flex flex-shrink-0 flex-wrap gap-2">
                    <a href="{{ route('projects.edit', $project) }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-white/15 px-3 py-2 text-sm font-medium text-white ring-1 ring-inset ring-white/30 backdrop-blur transition hover:bg-white/25">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </a>
                    <form wire:submit.prevent="delete"
                          onsubmit="return confirm('Tem certeza que deseja excluir este projeto? Todas as tarefas vinculadas também serão removidas.');">
                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-rose-600 to-red-600 px-3 py-2 text-sm font-medium text-white shadow-md shadow-rose-900/20 transition hover:from-rose-700 hover:to-red-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Excluir
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="p-6 sm:p-8">
            @if ($project->description)
                <div>
                    <h2 class="text-sm font-medium text-slate-500">Descrição</h2>
                    <p class="mt-2 whitespace-pre-wrap break-words text-slate-800">{{ $project->description }}</p>
                </div>
            @else
                <p class="text-sm italic text-slate-500">Nenhuma descrição informada para este projeto.</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div class="card p-6">
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-slate-500">Status</dt>
                    <dd class="mt-1.5"><x-status-badge :status="$project->status" :label="$statusLabel" /></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500">Progresso</dt>
                    <dd class="mt-1.5">
                        <div class="flex items-center gap-3">
                            <div class="h-2 flex-1 overflow-hidden rounded-full bg-indigo-100/70">
                                <div class="h-full rounded-full bg-gradient-to-r from-blue-600 via-indigo-500 to-violet-500 transition-all duration-300" style="width: {{ $project->progress }}%"></div>
                            </div>
                            <span class="text-sm font-semibold text-slate-900">{{ $project->progress }}%</span>
                        </div>
                    </dd>
                </div>
            </dl>
        </div>

        <div class="card p-6">
            <dl class="grid grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-slate-500">Data de Início</dt>
                    <dd class="mt-1.5 text-sm text-slate-800">{{ $project->start_date?->format('d/m/Y') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500">Data de Vencimento</dt>
                    <dd class="mt-1.5 text-sm text-slate-800">{{ $project->due_date?->format('d/m/Y') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500">Criado em</dt>
                    <dd class="mt-1.5 text-sm text-slate-800">{{ $project->created_at->format('d/m/Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500">Atualizado em</dt>
                    <dd class="mt-1.5 text-sm text-slate-800">{{ $project->updated_at->format('d/m/Y H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="card overflow-hidden">
        <div class="flex flex-col gap-3 border-b border-slate-100 p-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Tarefas do Projeto</h2>
                <p class="mt-0.5 text-sm text-slate-500">{{ $project->tasks->count() }} {{ $project->tasks->count() === 1 ? 'tarefa' : 'tarefas' }} neste projeto</p>
            </div>
            <a href="{{ route('tasks.create', ['project' => $project->id]) }}" class="btn-primary self-start sm:self-auto">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nova Tarefa
            </a>
        </div>

        @if ($project->tasks->isNotEmpty())
            <ul class="divide-y divide-slate-100">
                @foreach ($project->tasks as $task)
                    <li class="p-4 transition-colors hover:bg-indigo-50/50 sm:px-6">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('tasks.show', $task) }}" class="block truncate text-base font-medium text-slate-900 hover:text-indigo-600">{{ $task->title }}</a>
                                @if ($task->description)
                                    <p class="mt-1 line-clamp-2 break-words text-sm text-slate-600">{{ $task->description }}</p>
                                @endif
                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                    <x-status-badge :status="$task->status" :label="$task->statusLabel" />
                                    <span class="inline-flex items-center rounded-full bg-gradient-to-r from-slate-50 to-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-200">
                                        {{ $task->priorityLabel }}
                                    </span>
                                    @if ($task->due_date)
                                        <span class="text-sm text-slate-500">Vence: {{ $task->due_date->format('d/m/Y') }}</span>
                                    @endif
                                    @if ($task->user)
                                        <span class="text-sm text-slate-500">{{ $task->user->name }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="flex flex-col items-center justify-center px-6 py-14 text-center">
                <span class="flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-indigo-50 to-violet-50 text-indigo-400">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </span>
                <h3 class="mt-4 text-lg font-semibold text-slate-900">Nenhuma tarefa cadastrada</h3>
                <p class="mt-1 max-w-sm text-sm text-slate-500">Este projeto ainda não possui tarefas. Use o botão <span class="font-medium text-slate-700">Nova Tarefa</span> acima para adicionar a primeira.</p>
            </div>
        @endif
    </div>
</div>
