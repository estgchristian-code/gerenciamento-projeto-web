<?php

namespace App\Livewire\Projects;

use App\Http\Requests\StoreProjectRequest;
use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
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
            $this->authorize('update', $project);
            $this->project = $project;
            $this->name = $project->name;
            $this->description = $project->description;
            $this->status = $project->status;
            $this->start_date = $project->start_date?->format('Y-m-d');
            $this->due_date = $project->due_date?->format('Y-m-d');
        }
    }

    public function save(StoreProjectRequest $request): void
    {
        $validated = $request->validated();

        if ($this->project) {
            $this->project->update($validated);
            $this->dispatch('notify', message: 'Projeto atualizado com sucesso!');
        } else {
            auth()->user()->projects()->create($validated);
            $this->dispatch('notify', message: 'Projeto criado com sucesso!');
        }

        $this->redirect(route('projects.index'));
    }

    public function cancel(): void
    {
        $this->redirect(route('projects.index'));
    }

    public function render()
    {
        return view('projects.form', [
            'errors' => new \Illuminate\Support\ViewErrorBag([
                'default' => $this->getErrorBag(),
            ]),
        ]);
    }
}