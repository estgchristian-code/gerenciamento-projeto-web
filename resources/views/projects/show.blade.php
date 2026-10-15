<x-slot name="title">{{ $project->name }}</x-slot>

<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $project->name }}</h1>
            <p class="mt-1 text-sm text-gray-600">Status: <span class="font-medium capitalize">{{ $statusLabel }}</span></p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('projects.edit', $project) }}"
               class="inline-flex items-center gap-1.5 px-3 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar
            </a>
            <form wire:submit.prevent="delete({{ $project->id }})"
                  onsubmit="return confirm('Tem certeza que deseja excluir este projeto? Todas as tarefas vinculadas também serão removidas.');"
                  class="inline">
                <button type="submit"
                        class="inline-flex items-center gap-1.5 px-3 py-2 border border-red-300 text-sm font-medium rounded-lg text-red-700 bg-red-50 hover:bg-red-100 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Excluir
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Informações do Projeto</h2>
        </div>
        <div class="p-6 space-y-4">
            @if ($project->description)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Descrição</dt>
                    <dd class="mt-1 text-gray-900 whitespace-pre-wrap">{{ $project->description }}</dd>
                </div>
            @endif

            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @match($project->status)
                                @case('planejamento')
                                    bg-blue-100 text-blue-800
                                    @break
                                @case('em_andamento')
                                    bg-yellow-100 text-yellow-800
                                    @break
                                @case('concluido')
                                    bg-green-100 text-green-800
                                    @break
                                @case('cancelado')
                                    bg-red-100 text-red-800
                                    @break
                                @default
                                    bg-gray-100 text-gray-800
                            @endmatch
                        ">
                            {{ $statusLabel }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Progresso</dt>
                    <dd class="mt-1">
                        <div class="flex items-center gap-3">
                            <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-gray-900 rounded-full transition-all duration-300" style="width: {{ $project->progress }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $project->progress }}%</span>
                        </div>
                    </dd>
                </div>
                @if ($project->start_date)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Data de Início</dt>
                        <dd class="mt-1 text-gray-900">{{ $project->start_date->format('d/m/Y') }}</dd>
                    </div>
                @endif
                @if ($project->due_date)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Data de Vencimento</dt>
                        <dd class="mt-1 text-gray-900">{{ $project->due_date->format('d/m/Y') }}</dd>
                    </div>
                @endif
                <div>
                    <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                    <dd class="mt-1 text-gray-900">{{ $project->created_at->format('d/m/Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Atualizado em</dt>
                    <dd class="mt-1 text-gray-900">{{ $project->updated_at->format('d/m/Y H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-lg font-semibold text-gray-900">Tarefas do Projeto</h2>
            <a href="#" class="text-sm text-gray-600 hover:text-gray-900 underline underline-offset-1">
                Nova Tarefa
            </a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse ($project->tasks as $task)
                <div class="p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-medium text-gray-900 truncate">{{ $task->title }}</h3>
                            @if ($task->description)
                                <p class="mt-1 text-sm text-gray-600 line-clamp-2">{{ $task->description }}</p>
                            @endif
                            <div class="mt-2 flex flex-wrap items-center gap-3 text-sm text-gray-500">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    @match($task->status)
                                        @case('todo')
                                            bg-gray-100 text-gray-800
                                            @break
                                        @case('in_progress')
                                            bg-blue-100 text-blue-800
                                            @break
                                        @case('done')
                                            bg-green-100 text-green-800
                                            @break
                                        @case('blocked')
                                            bg-red-100 text-red-800
                                            @break
                                        @default
                                            bg-gray-100 text-gray-800
                                    @endmatch
                                ">
                                    {{ $task->statusLabel }}
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    @match($task->priority)
                                        @case('low')
                                            bg-gray-100 text-gray-800
                                            @break
                                        @case('medium')
                                            bg-yellow-100 text-yellow-800
                                            @break
                                        @case('high')
                                            bg-red-100 text-red-800
                                            @break
                                        @default
                                            bg-gray-100 text-gray-800
                                    @endmatch
                                ">
                                    {{ $task->priorityLabel }}
                                </span>
                                @if ($task->due_date)
                                    <span>Vence: {{ $task->due_date->format('d/m/Y') }}</span>
                                @endif
                                @if ($task->user)
                                    <span>{{ $task->user->name }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2 sm:ml-4">
                            <a href="#" class="text-sm text-gray-600 hover:text-gray-900 underline underline-offset-1">Editar</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Nenhuma tarefa</h3>
                    <p class="mt-2 text-sm text-gray-600">Este projeto ainda não possui tarefas.</p>
                    <p class="mt-4 text-sm text-gray-500">A funcionalidade de tarefas será implementada em breve.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>