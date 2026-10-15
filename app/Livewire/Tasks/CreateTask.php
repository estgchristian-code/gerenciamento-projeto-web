<?php

namespace App\Livewire\Tasks;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Nova Tarefa')]
class CreateTask extends Component
{
    public ?Task $task = null;
    public ?Project $project = null;
    public string $title = '';
    public ?string $description = null;
    public string $status = 'todo';
    public string $priority = 'medium';
    public $due_date = null;
    public $user_id = null;

    public function mount(?Project $project = null, ?Task $task = null): void
    {
        session()->forget('errors');

        if ($task) {
            $this->task = $task;
            $this->project = $task->project;
            $this->title = $task->title;
            $this->description = $task->description;
            $this->status = $task->status;
            $this->priority = $task->priority;
            $this->due_date = $task->due_date?->format('Y-m-d');
            $this->user_id = $task->user_id;
        } else {
            $this->project = $project;
        }
    }

    public function save(StoreTaskRequest $request): void
    {
        $validated = $request->validated();

        if ($this->task) {
            $this->task->update($validated);
            $this->dispatch('taskUpdated');
        } else {
            $this->project->tasks()->create($validated);
            $this->dispatch('taskCreated');
        }

        $this->dispatch('notify', message: $this->task ? 'Tarefa atualizada com sucesso!' : 'Tarefa criada com sucesso!');
        $this->redirect(route('projects.show', $this->project));
    }

    public function cancel(): void
    {
        $this->redirect(route('projects.show', $this->project));
    }

    public function render()
    {
        $projectMembers = $this->project ? $this->project->user->loadMissing('projects') : null;

        return view('tasks.form', [
            'project' => $this->project,
            'errors' => new \Illuminate\Support\ViewErrorBag([
                'default' => $this->getErrorBag(),
            ]),
        ]);
    }
}