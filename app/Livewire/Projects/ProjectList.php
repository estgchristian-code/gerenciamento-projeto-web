<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Meus Projetos')]
class ProjectList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    public function mount(): void
    {
        session()->forget('errors');
    }

    public function render()
    {
        $projects = auth()->user()
            ->projects()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->withCount('tasks')
            ->paginate(10);

        return view('projects.index', [
            'projects' => $projects,
            'errors' => new \Illuminate\Support\ViewErrorBag([
                'default' => $this->getErrorBag(),
            ]),
        ]);
    }

    public function delete(Project $project): void
    {
        $this->authorize('delete', $project);
        $project->delete();
        $this->dispatch('notify', message: 'Projeto excluído com sucesso!');
    }

    public function getStatusLabelsProperty(): array
    {
        return [
            'planejamento' => 'Planejamento',
            'em_andamento' => 'Em andamento',
            'concluido' => 'Concluído',
            'cancelado' => 'Cancelado',
        ];
    }
}