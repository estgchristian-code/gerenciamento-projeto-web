<x-slot name="title">{{ $project ? 'Editar Projeto' : 'Novo Projeto' }}</x-slot>

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ $project ? 'Editar Projeto' : 'Novo Projeto' }}</h1>
        <p class="mt-1 text-gray-600">{{ $project ? 'Atualize as informações do projeto.' : 'Preencha os dados para criar um novo projeto.' }}</p>
    </div>

    <form wire:submit="save" class="space-y-6" novalidate>
        @if ($errors->any())
            <div class="rounded-lg bg-red-50 border border-red-100 p-4" role="alert">
                <div class="flex">
                    <div class="ml-3 flex-1">
                        <h3 class="text-sm font-medium text-red-800">Erro ao salvar o projeto</h3>
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
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nome do Projeto <span class="text-red-500" aria-hidden="true">*</span></label>
            <input type="text"
                   id="name"
                   name="name"
                   wire:model.defer="name"
                   required
                   maxlength="255"
                   autocomplete="off"
                   class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-transparent transition-colors duration-150 @error('name') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-200 @enderror"
                   placeholder="Nome do projeto"
                   aria-describedby="{{ $errors->has('name') ? 'name-error' : '' }}">
            @error('name')
                <p id="name-error" class="mt-1.5 text-sm text-red-600" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Descrição</label>
            <textarea id="description"
                      name="description"
                      wire:model.defer="description"
                      rows="4"
                      class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-transparent transition-colors duration-150 @error('description') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-200 @enderror"
                      placeholder="Descreva o projeto..."></textarea>
            @error('description')
                <p class="mt-1.5 text-sm text-red-600" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Status <span class="text-red-500" aria-hidden="true">*</span></label>
                <select id="status"
                        name="status"
                        wire:model.defer="status"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-transparent transition-colors duration-150 @error('status') border-red-300 text-red-900 focus:ring-red-200 @enderror">
                    <option value="planejamento">Planejamento</option>
                    <option value="em_andamento">Em andamento</option>
                    <option value="concluido">Concluído</option>
                    <option value="cancelado">Cancelado</option>
                </select>
                @error('status')
                    <p class="mt-1.5 text-sm text-red-600" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1.5">Data de Início</label>
                <input type="date"
                       id="start_date"
                       name="start_date"
                       wire:model.defer="start_date"
                       class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-transparent transition-colors duration-150 @error('start_date') border-red-300 text-red-900 focus:ring-red-200 @enderror"
                       aria-describedby="{{ $errors->has('start_date') ? 'start_date-error' : '' }}">
                @error('start_date')
                    <p id="start_date-error" class="mt-1.5 text-sm text-red-600" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1.5">Data de Vencimento</label>
                <input type="date"
                       id="due_date"
                       name="due_date"
                       wire:model.defer="due_date"
                       class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-transparent transition-colors duration-150 @error('due_date') border-red-300 text-red-900 focus:ring-red-200 @enderror"
                       aria-describedby="{{ $errors->has('due_date') ? 'due_date-error' : '' }}">
                @error('due_date')
                    <p id="due_date-error" class="mt-1.5 text-sm text-red-600" role="alert">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 pt-4 border-t border-gray-100">
            <button type="submit"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-150"
                    wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $project ? 'Atualizar' : 'Criar' }} Projeto</span>
                <span wire:loading class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Salvando...
                </span>
            </button>
            <a href="{{ route('projects.index') }}"
               class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-colors duration-150">
                Cancelar
            </a>
        </div>
    </form>
</div>