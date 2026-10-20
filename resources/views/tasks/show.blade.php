<x-slot name="title">{{ $task->title }}</x-slot>

<div class="mx-auto max-w-4xl space-y-6">
    <nav class="flex items-center gap-2 text-sm text-slate-500" aria-label="Navegação">
        <a href="{{ route('tasks.index') }}" class="hover:text-indigo-600">Tarefas</a>
        <svg class="h-4 w-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="truncate font-medium text-slate-700">{{ $task->title }}</span>
    </nav>

    <div class="card overflow-hidden">
        <div class="bg-gradient-to-br from-blue-700 via-indigo-600 to-violet-600 px-6 py-6 sm:px-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="min-w-0">
                    <h1 class="text-2xl font-bold tracking-tight text-white break-words sm:text-3xl">{{ $task->title }}</h1>
                    <div class="mt-3 flex flex-wrap items-center gap-2">
                        <x-status-badge :status="$task->status" :label="$statusLabel" class="bg-white/20 text-white ring-white/30 backdrop-blur" />
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white/20 px-2.5 py-1 text-xs font-medium text-white ring-1 ring-inset ring-white/30 backdrop-blur">
                            {{ $priorityLabel }}
                        </span>
                        @if ($task->project)
                            <a href="{{ route('projects.show', $task->project) }}" class="min-w-0 break-words text-xs text-white/90 underline-offset-2 hover:underline">
                                {{ $task->project->name }}
                            </a>
                        @endif
                    </div>
                </div>
                <div class="flex flex-shrink-0 flex-wrap gap-2">
                    <a href="{{ route('tasks.edit', $task) }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-white/15 px-3 py-2 text-sm font-medium text-white ring-1 ring-inset ring-white/30 backdrop-blur transition hover:bg-white/25">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </a>
                    <form wire:submit.prevent="delete"
                          onsubmit="return confirm('Tem certeza que deseja excluir esta tarefa?');">
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
            @if ($task->description)
                <div>
                    <h2 class="text-sm font-medium text-slate-500">Descrição</h2>
                    <p class="mt-2 whitespace-pre-wrap break-words text-slate-800">{{ $task->description }}</p>
                </div>
            @else
                <p class="text-sm italic text-slate-500">Nenhuma descrição informada para esta tarefa.</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div class="card p-6">
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-slate-500">Status</dt>
                    <dd class="mt-1.5"><x-status-badge :status="$task->status" :label="$statusLabel" /></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500">Prioridade</dt>
                    <dd class="mt-1.5"><x-priority-badge :priority="$task->priority" :label="$priorityLabel" /></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500">Prazo</dt>
                    <dd class="mt-1.5 text-sm text-slate-800">{{ $task->due_date?->format('d/m/Y') ?? '—' }}</dd>
                </div>
            </dl>
        </div>

        <div class="card p-6">
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-slate-500">Projeto</dt>
                    <dd class="mt-1.5 text-sm">
                        @if ($task->project)
                            <a href="{{ route('projects.show', $task->project) }}" class="break-words font-medium text-indigo-600 hover:text-violet-600">
                                {{ $task->project->name }}
                            </a>
                        @else
                            <span class="text-slate-400">—</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500">Responsável</dt>
                    <dd class="mt-1.5 text-sm text-slate-800">{{ $task->user?->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500">Criado em</dt>
                    <dd class="mt-1.5 text-sm text-slate-800">{{ $task->created_at->format('d/m/Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500">Atualizado em</dt>
                    <dd class="mt-1.5 text-sm text-slate-800">{{ $task->updated_at->format('d/m/Y H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
