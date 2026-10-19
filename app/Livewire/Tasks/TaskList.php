<?php

namespace App\Livewire\Tasks;

use App\Models\Project;
use App\Models\Task;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Tarefas')]
class TaskList extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public string $priorityFilter = '';

    public string $projectFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingPriorityFilter(): void
    {
        $this->resetPage();
    }

    public function updatingProjectFilter(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->priorityFilter = '';
        $this->projectFilter = '';
        $this->resetPage();
    }

    public function delete(Task $task): void
    {
        $task->delete();

        session()->flash('success', 'Tarefa excluída com sucesso!');
        $this->redirect(route('tasks.index'));
    }

    public function render()
    {
        $hasFilters = $this->search !== ''
            || $this->statusFilter !== ''
            || $this->priorityFilter !== ''
            || $this->projectFilter !== '';

        $tasks = Task::query()
            ->with('project')
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->priorityFilter, fn ($q) => $q->where('priority', $this->priorityFilter))
            ->when($this->projectFilter, fn ($q) => $q->where('project_id', $this->projectFilter))
            ->latest()
            ->paginate(9);

        return view('tasks.index', [
            'tasks' => $tasks,
            'statusLabels' => Task::statusLabels(),
            'priorityLabels' => Task::priorityLabels(),
            'projects' => Project::orderBy('name')->get(['id', 'name']),
            'hasFilters' => $hasFilters,
        ]);
    }
}
