<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class TaskShow extends Component
{
    public Task $task;

    public function mount(Task $task): void
    {
        $this->task = $task->load(['project', 'user']);
    }

    public function delete(): void
    {
        $this->task->delete();

        session()->flash('success', 'Tarefa excluída com sucesso!');
        $this->redirect(route('tasks.index'));
    }

    public function render()
    {
        return view('tasks.show', [
            'statusLabel' => $this->task->statusLabel,
            'priorityLabel' => $this->task->priorityLabel,
        ]);
    }
}
