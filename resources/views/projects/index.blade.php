<x-slot name="title">Meus Projetos</x-slot>

<div class="flex flex-col gap-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold text-gray-900">Meus Projetos</h1>
        <a href="{{ route('projects.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Novo Projeto
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="p-4 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <label for="search" class="sr-only">Buscar projetos</label>
                    <input type="text"
                           id="search"
                           wire:model.debounce.300ms="search"
                           placeholder="Buscar por nome..."
                           class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-transparent transition-colors">
                </div>
                <div class="w-full sm:w-auto">
                    <label for="status-filter" class="sr-only">Filtrar por status</label>
                    <select id="status-filter"
                            wire:model="statusFilter"
                            class="w-full sm:w-48 rounded-lg border border-gray-300 px-4 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-transparent transition-colors">
                        <option value="">Todos os status</option>
                        @foreach ($statusLabels as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse ($projects as $project)
                <div class="p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('projects.show', $project) }}"
                                   class="text-lg font-semibold text-gray-900 hover:text-gray-700 truncate block">
                                    {{ $project->name }}
                                </a>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @switch($project->status)
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
                                    @endswitch
                                ">
                                    {{ $statusLabels[$project->status] ?? $project->status }}
                                </span>
                            </div>
                            @if ($project->description)
                                <p class="mt-1 text-sm text-gray-600 line-clamp-2">{{ $project->description }}</p>
                            @endif
                            <div class="mt-2 flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                @if ($project->start_date)
                                    <span>Início: {{ $project->start_date->format('d/m/Y') }}</span>
                                @endif
                                @if ($project->due_date)
                                    <span>Vencimento: {{ $project->due_date->format('d/m/Y') }}</span>
                                @endif
                                <span>{{ $project->tasks_count }} tarefa{{ $project->tasks_count !== 1 ? 's' : '' }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 sm:ml-4">
                            <a href="{{ route('projects.edit', $project) }}"
                               class="text-sm text-gray-600 hover:text-gray-900 underline underline-offset-1">
                                Editar
                            </a>
                            <form wire:submit.prevent="delete({{ $project->id }})"
                                  onsubmit="return confirm('Tem certeza que deseja excluir este projeto? Todas as tarefas vinculadas também serão removidas.');"
                                  class="inline">
                                <button type="submit"
                                        class="text-sm text-red-600 hover:text-red-900 underline underline-offset-1">
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Nenhum projeto encontrado</h3>
                    <p class="mt-2 text-sm text-gray-600">Comece criando seu primeiro projeto.</p>
                    <a href="{{ route('projects.create') }}"
                       class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Criar primeiro projeto
                    </a>
                </div>
            @endforelse
        </div>

        {{ $projects->links() }}
    </div>
</div>