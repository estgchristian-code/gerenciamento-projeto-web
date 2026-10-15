<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Meus Projetos')]
class ProjectList extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->resetPage();
    }

    public function delete(Project $project): void
    {
        $project->delete();

        session()->flash('success', 'Projeto excluído com sucesso!');
        $this->redirect(route('projects.index'));
    }

    public function render()
    {
        $hasFilters = $this->search !== '' || $this->statusFilter !== '';

        $projects = Project::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->withCount('tasks')
            ->paginate(9);

        return view('projects.index', [
            'projects' => $projects,
            'statusLabels' => $this->statusLabels,
            'hasFilters' => $hasFilters,
        ]);
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
