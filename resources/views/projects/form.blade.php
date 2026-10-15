<x-slot name="title">{{ $project ? 'Editar Projeto' : 'Novo Projeto' }}</x-slot>

<div class="mx-auto max-w-3xl space-y-6">
    <nav class="flex items-center gap-2 text-sm text-slate-500" aria-label="Navegação">
        <a href="{{ route('projects.index') }}" class="hover:text-slate-900">Projetos</a>
        <svg class="h-4 w-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="font-medium text-slate-700">{{ $project ? 'Editar' : 'Novo' }}</span>
    </nav>

    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">{{ $project ? 'Editar Projeto' : 'Novo Projeto' }}</h1>
        <p class="mt-1 text-sm text-slate-500">
            {{ $project ? 'Atualize as informações do projeto abaixo.' : 'Preencha os dados para criar um novo projeto.' }}
        </p>
    </div>

    <div class="card p-6 sm:p-8">
        <form wire:submit="save" class="space-y-6" novalidate>
            @if ($errors->any())
                <div class="rounded-xl border border-rose-200 bg-rose-50 p-4" role="alert">
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
                <label for="name" class="label">Nome do Projeto <span class="text-rose-500" aria-hidden="true">*</span></label>
                <input type="text"
                       id="name"
                       name="name"
                       wire:model="name"
                       required
                       maxlength="255"
                       autocomplete="off"
                       placeholder="Ex.: Redesenho do portal institucional"
                       class="input @error('name') input-error @enderror"
                       aria-describedby="{{ $errors->has('name') ? 'name-error' : '' }}">
                @error('name')
                    <p id="name-error" class="field-error" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="label">Descrição</label>
                <textarea id="description"
                          name="description"
                          wire:model="description"
                          rows="5"
                          placeholder="Descreva o objetivo, o escopo e as principais entregas do projeto..."
                          class="input @error('description') input-error @enderror"></textarea>
                @error('description')
                    <p class="field-error" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="status" class="label">Status <span class="text-rose-500" aria-hidden="true">*</span></label>
                    <select id="status" name="status" wire:model="status" required
                            class="input @error('status') input-error @enderror">
                        <option value="planejamento">Planejamento</option>
                        <option value="em_andamento">Em andamento</option>
                        <option value="concluido">Concluído</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                    @error('status')
                        <p class="field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="start_date" class="label">Data de Início</label>
                    <input type="date"
                           id="start_date"
                           name="start_date"
                           wire:model="start_date"
                           class="input @error('start_date') input-error @enderror"
                           aria-describedby="{{ $errors->has('start_date') ? 'start_date-error' : '' }}">
                    @error('start_date')
                        <p id="start_date-error" class="field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="due_date" class="label">Data de Vencimento</label>
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
                <a href="{{ route('projects.index') }}" class="btn-secondary">Cancelar</a>
                <button type="submit" class="btn-primary" wire:loading.attr="disabled" wire:target="save">
                    <span wire:loading.remove wire:target="save" class="inline-flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $project ? 'Salvar alterações' : 'Criar projeto' }}
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
