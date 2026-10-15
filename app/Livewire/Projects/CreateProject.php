<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CreateProject extends Component
{
    public ?Project $project = null;

    public string $name = '';

    public ?string $description = null;

    public string $status = 'planejamento';

    public ?string $start_date = null;

    public ?string $due_date = null;

    public function mount(?Project $project = null): void
    {
        session()->forget('errors');

        if ($project) {
            $this->project = $project;
            $this->name = $project->name;
            $this->description = $project->description;
            $this->status = $project->status;
            $this->start_date = $project->start_date?->format('Y-m-d');
            $this->due_date = $project->due_date?->format('Y-m-d');
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:planejamento,em_andamento,concluido,cancelado'],
            'start_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome do projeto é obrigatório.',
            'name.max' => 'O nome do projeto não pode ter mais de 255 caracteres.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status deve ser: planejamento, em andamento, concluído ou cancelado.',
            'start_date.date' => 'A data de início deve ser uma data válida.',
            'due_date.date' => 'A data de vencimento deve ser uma data válida.',
            'due_date.after_or_equal' => 'A data de vencimento não pode ser anterior à data de início.',
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->project) {
            $this->project->update($validated);
            session()->flash('success', 'Projeto atualizado com sucesso!');
        } else {
            Project::create($validated);
            session()->flash('success', 'Projeto criado com sucesso!');
        }

        $this->redirect(route('projects.index'));
    }

    public function cancel(): void
    {
        $this->redirect(route('projects.index'));
    }

    public function render()
    {
        return view('projects.form');
    }
}
