<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ProjectShow extends Component
{
    public Project $project;

    public function mount(Project $project): void
    {
        $this->project = $project->load(['tasks' => fn ($q) => $q->with('user')->latest()]);
    }

    public function delete(): void
    {
        $this->project->delete();

        session()->flash('success', 'Projeto excluído com sucesso!');
        $this->redirect(route('projects.index'));
    }

    public function render()
    {
        return view('projects.show', [
            'statusLabel' => $this->statusLabel,
        ]);
    }

    public function getStatusLabelProperty(): string
    {
        return match ($this->project->status) {
            'planejamento' => 'Planejamento',
            'em_andamento' => 'Em andamento',
            'concluido' => 'Concluído',
            'cancelado' => 'Cancelado',
            default => $this->project->status,
        };
    }
}
