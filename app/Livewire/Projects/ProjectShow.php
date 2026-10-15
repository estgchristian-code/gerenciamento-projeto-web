<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
class ProjectShow extends Component
{
    public Project $project;

    public function mount(Project $project): void
    {
        $this->authorize('view', $project);
        $this->project = $project->load(['tasks' => fn ($q) => $q->with('user')->latest()]);
    }

    public function render()
    {
        return view('projects.show', [
            'errors' => new \Illuminate\Support\ViewErrorBag([
                'default' => $this->getErrorBag(),
            ]),
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