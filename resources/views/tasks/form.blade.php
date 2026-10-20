<x-slot name="title">{{ $task ? 'Editar Tarefa' : 'Nova Tarefa' }}</x-slot>

<div class="mx-auto max-w-3xl space-y-6">
    <nav class="flex items-center gap-2 text-sm text-slate-500" aria-label="Navegação">
        <a href="{{ route('tasks.index') }}" class="hover:text-indigo-600">Tarefas</a>
        <svg class="h-4 w-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="font-medium text-slate-700">{{ $task ? 'Editar' : 'Nova' }}</span>
    </nav>

    <div>
        <h1 class="bg-gradient-to-r from-blue-800 via-indigo-700 to-violet-700 bg-clip-text text-2xl font-bold tracking-tight text-transparent sm:text-3xl">{{ $task ? 'Editar Tarefa' : 'Nova Tarefa' }}</h1>
        <p class="mt-1 text-sm text-slate-500">
            {{ $task ? 'Atualize as informações da tarefa abaixo.' : 'Preencha os dados para criar uma nova tarefa.' }}
        </p>
    </div>

    <div class="card p-6 sm:p-8">
        <form wire:submit="save" class="space-y-6" novalidate>
            @if ($errors->any())
                <div class="rounded-xl border border-rose-200/80 bg-gradient-to-r from-rose-50 to-red-50 p-4 shadow-sm shadow-rose-600/10" role="alert">
                    <div class="flex gap-3">
                        <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.74-3L13.74 4a2 2 0 00-3.48 0L3.33 16a2 2 0 001.74 3z"/>
                        </svg>
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-rose-800">Corrija os campos destacados abaixo</h3>
                            <ul class="mt-1.5 list-disc space-y-1 pl-5 text-sm text-rose-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div>
                <label for="project_id" class="label">Projeto <span class="text-rose-500" aria-hidden="true">*</span></label>
                <select id="project_id" name="project_id" wire:model="project_id" required
                        class="input @error('project_id') input-error @enderror">
                    <option value="">Selecione um projeto</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
                @error('project_id')
                    <p class="field-error" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="title" class="label">Título <span class="text-rose-500" aria-hidden="true">*</span></label>
                <input type="text"
                       id="title"
                       name="title"
                       wire:model="title"
                       required
                       maxlength="255"
                       autocomplete="off"
                       placeholder="Ex.: Revisar requisitos funcionais"
                       class="input @error('title') input-error @enderror"
                       aria-describedby="{{ $errors->has('title') ? 'title-error' : '' }}">
                @error('title')
                    <p id="title-error" class="field-error" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="label">Descrição</label>
                <textarea id="description"
                          name="description"
                          wire:model="description"
                          rows="5"
                          placeholder="Detalhe o que precisa ser feito nesta tarefa..."
                          class="input @error('description') input-error @enderror"></textarea>
                @error('description')
                    <p class="field-error" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                <div>
                    <label for="status" class="label">Status <span class="text-rose-500" aria-hidden="true">*</span></label>
                    <select id="status" name="status" wire:model="status" required
                            class="input @error('status') input-error @enderror">
                        @foreach ($statusLabels as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="priority" class="label">Prioridade <span class="text-rose-500" aria-hidden="true">*</span></label>
                    <select id="priority" name="priority" wire:model="priority" required
                            class="input @error('priority') input-error @enderror">
                        @foreach ($priorityLabels as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('priority')
                        <p class="field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="due_date" class="label">Prazo</label>
                    <input type="date"
                           id="due_date"
                           name="due_date"
                           wire:model="due_date"
                           class="input @error('due_date') input-error @enderror"
                           aria-describedby="{{ $errors->has('due_date') ? 'due_date-error' : '' }}">
                    @error('due_date')
                        <p id="due_date-error" class="field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end">
                <a href="{{ route('tasks.index') }}" class="btn-secondary">Cancelar</a>
                <button type="submit" class="btn-primary" wire:loading.attr="disabled" wire:target="save">
                    <span wire:loading.remove wire:target="save" class="inline-flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $task ? 'Salvar alterações' : 'Criar tarefa' }}
                    </span>
                    <span wire:loading.inline-flex wire:target="save" class="items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Salvando...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
