<?php

namespace App\Livewire\Tasks;

use App\Models\Project;
use App\Models\Task;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CreateTask extends Component
{
    public ?Task $task = null;

    public ?int $project_id = null;

    public string $title = '';

    public ?string $description = null;

    public string $status = Task::STATUS_TODO;

    public string $priority = Task::PRIORITY_MEDIUM;

    public ?string $due_date = null;

    public function mount(?Task $task = null, ?int $project = null): void
    {
        session()->forget('errors');

        if ($task) {
            $this->task = $task;
            $this->project_id = $task->project_id;
            $this->title = $task->title;
            $this->description = $task->description;
            $this->status = $task->status;
            $this->priority = $task->priority;
            $this->due_date = $task->due_date?->format('Y-m-d');

            return;
        }

        if ($project) {
            $this->project_id = $project;
        }
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return (new Task)->rules();
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'project_id.required' => 'Selecione o projeto da tarefa.',
            'project_id.integer' => 'O projeto selecionado é inválido.',
            'project_id.exists' => 'O projeto selecionado é inválido.',
            'title.required' => 'O título da tarefa é obrigatório.',
            'title.max' => 'O título da tarefa não pode ter mais de 255 caracteres.',
            'description.max' => 'A descrição da tarefa não pode ter mais de 10.000 caracteres.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status selecionado é inválido.',
            'priority.required' => 'A prioridade é obrigatória.',
            'priority.in' => 'A prioridade selecionada é inválida.',
            'due_date.date' => 'O prazo deve ser uma data válida.',
        ];
    }

    public function save(): void
    {
        $this->due_date = $this->due_date ?: null;

        $validated = $this->validate();

        if ($this->task) {
            $this->task->update($validated);
            session()->flash('success', 'Tarefa atualizada com sucesso!');
        } else {
            Task::create($validated);
            session()->flash('success', 'Tarefa criada com sucesso!');
        }

        $this->redirect(route('tasks.index'));
    }

    public function cancel(): void
    {
        $this->redirect(route('tasks.index'));
    }

    public function render()
    {
        return view('tasks.form', [
            'projects' => Project::orderBy('name')->get(['id', 'name']),
            'statusLabels' => Task::statusLabels(),
            'priorityLabels' => Task::priorityLabels(),
        ]);
    }
}
