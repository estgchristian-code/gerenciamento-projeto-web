<?php

namespace Tests\Feature;

use App\Livewire\Tasks\CreateTask;
use App\Livewire\Tasks\TaskList;
use App\Livewire\Tasks\TaskShow;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Livewire\Exceptions\PublicPropertyNotFoundException;
use Livewire\Livewire;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_belongs_to_a_project(): void
    {
        $project = Project::factory()->create();
        $task = Task::factory()->create(['project_id' => $project->id]);

        $this->assertTrue($task->project->is($project));
    }

    public function test_task_can_belong_to_a_user(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($task->user->is($user));
    }

    public function test_task_is_optional_owner_when_no_user_is_set(): void
    {
        $task = Task::factory()->create(['user_id' => null]);

        $this->assertNull($task->user);
    }

    public function test_project_has_many_tasks(): void
    {
        $project = Project::factory()->create();
        Task::factory()->count(3)->create(['project_id' => $project->id]);

        $this->assertCount(3, $project->fresh()->tasks);
    }

    public function test_factory_creates_a_persisted_task_for_an_existing_project(): void
    {
        $task = Task::factory()->create();

        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
        $this->assertDatabaseHas('projects', ['id' => $task->project_id]);
        $this->assertContains($task->status, Task::STATUSES);
        $this->assertContains($task->priority, Task::PRIORITIES);
    }

    public function test_task_defaults_to_todo_status_and_medium_priority(): void
    {
        $project = Project::factory()->create();

        $task = Task::create([
            'project_id' => $project->id,
            'title' => 'Tarefa sem status informado',
        ])->refresh();

        $this->assertSame(Task::STATUS_TODO, $task->status);
        $this->assertSame(Task::PRIORITY_MEDIUM, $task->priority);
    }

    public function test_deleting_a_project_deletes_its_tasks(): void
    {
        $project = Project::factory()->create();
        $task = Task::factory()->create(['project_id' => $project->id]);

        $project->delete();

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_task_cannot_reference_a_non_existing_project(): void
    {
        $this->expectException(QueryException::class);

        Task::factory()->create(['project_id' => 999999]);
    }

    public function test_allowed_statuses_and_priorities_are_defined(): void
    {
        $this->assertSame(
            [Task::STATUS_TODO, Task::STATUS_IN_PROGRESS, Task::STATUS_DONE, Task::STATUS_BLOCKED],
            Task::STATUSES,
        );

        $this->assertSame(
            [Task::PRIORITY_LOW, Task::PRIORITY_MEDIUM, Task::PRIORITY_HIGH],
            Task::PRIORITIES,
        );
    }

    public function test_validation_rules_accept_valid_task_data(): void
    {
        $project = Project::factory()->create();

        $validator = Validator::make([
            'project_id' => $project->id,
            'title' => 'Tarefa válida',
            'description' => 'Descrição da tarefa.',
            'status' => Task::STATUS_IN_PROGRESS,
            'priority' => Task::PRIORITY_HIGH,
            'due_date' => now()->addWeek()->toDateString(),
        ], (new Task)->rules());

        $this->assertFalse($validator->fails());
    }

    public function test_validation_rules_require_an_existing_project(): void
    {
        $validator = Validator::make([
            'title' => 'Sem projeto',
            'status' => Task::STATUS_TODO,
            'priority' => Task::PRIORITY_MEDIUM,
        ], (new Task)->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('project_id', $validator->errors()->toArray());
    }

    public function test_validation_rules_reject_invalid_status_and_priority(): void
    {
        $project = Project::factory()->create();

        $validator = Validator::make([
            'project_id' => $project->id,
            'title' => 'Valores inválidos',
            'status' => 'invalido',
            'priority' => 'urgente',
        ], (new Task)->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('status', $validator->errors()->toArray());
        $this->assertArrayHasKey('priority', $validator->errors()->toArray());
    }

    public function test_status_and_priority_labels_are_returned(): void
    {
        $task = Task::factory()->create([
            'status' => Task::STATUS_DONE,
            'priority' => Task::PRIORITY_LOW,
        ]);

        $this->assertSame('Concluída', $task->statusLabel);
        $this->assertSame('Baixa', $task->priorityLabel);
    }

    public function test_guest_can_access_tasks_index(): void
    {
        $this->get(route('tasks.index'))
            ->assertStatus(200)
            ->assertSee('Tarefas');
    }

    public function test_guest_can_access_task_create_form(): void
    {
        $this->get(route('tasks.create'))
            ->assertStatus(200)
            ->assertSee('Nova Tarefa');
    }

    public function test_guest_can_access_task_edit_form(): void
    {
        $task = Task::factory()->create();

        $this->get(route('tasks.edit', $task))
            ->assertStatus(200)
            ->assertSee('Editar Tarefa');
    }

    public function test_guest_can_view_a_task(): void
    {
        $project = Project::factory()->create(['name' => 'Projeto Vitrine']);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'title' => 'Tarefa Detalhada',
            'status' => Task::STATUS_DONE,
            'priority' => Task::PRIORITY_HIGH,
        ]);

        $this->get(route('tasks.show', $task))
            ->assertStatus(200)
            ->assertSee('Tarefa Detalhada')
            ->assertSee('Projeto Vitrine')
            ->assertSee('Concluída')
            ->assertSee('Alta');
    }

    public function test_guest_can_create_a_task_associated_with_a_project(): void
    {
        $project = Project::factory()->create();

        Livewire::test(CreateTask::class)
            ->set('project_id', $project->id)
            ->set('title', 'Nova Tarefa Teste')
            ->set('description', 'Descrição da nova tarefa.')
            ->set('status', Task::STATUS_IN_PROGRESS)
            ->set('priority', Task::PRIORITY_HIGH)
            ->set('due_date', '2026-12-31')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', [
            'project_id' => $project->id,
            'title' => 'Nova Tarefa Teste',
            'status' => Task::STATUS_IN_PROGRESS,
            'priority' => Task::PRIORITY_HIGH,
        ]);

        $this->assertNull(Task::where('title', 'Nova Tarefa Teste')->firstOrFail()->user_id);
    }

    public function test_task_description_cannot_exceed_ten_thousand_characters(): void
    {
        $project = Project::factory()->create();

        Livewire::test(CreateTask::class)
            ->set('project_id', $project->id)
            ->set('title', 'Tarefa com descrição longa')
            ->set('description', str_repeat('a', 10001))
            ->call('save')
            ->assertHasErrors(['description' => 'max']);

        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_task_accepts_description_with_ten_thousand_characters(): void
    {
        $project = Project::factory()->create();
        $description = str_repeat('a', 10000);

        Livewire::test(CreateTask::class)
            ->set('project_id', $project->id)
            ->set('title', 'Tarefa com descrição no limite')
            ->set('description', $description)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', [
            'project_id' => $project->id,
            'description' => $description,
        ]);
    }

    public function test_client_cannot_set_user_id_on_task_creation(): void
    {
        $user = User::factory()->create();

        $this->expectException(PublicPropertyNotFoundException::class);

        Livewire::test(CreateTask::class)
            ->set('user_id', $user->id);
    }

    public function test_client_cannot_change_user_id_on_task_edit(): void
    {
        $task = Task::factory()->create([
            'user_id' => User::factory()->create()->id,
        ]);

        $this->expectException(PublicPropertyNotFoundException::class);

        Livewire::test(CreateTask::class, ['task' => $task])
            ->set('user_id', User::factory()->create()->id);
    }

    public function test_user_id_is_not_part_of_task_validation_rules(): void
    {
        $this->assertArrayNotHasKey('user_id', (new Task)->rules());
    }

    public function test_existing_task_user_id_is_preserved_when_updating(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Tarefa Original',
        ]);

        Livewire::test(CreateTask::class, ['task' => $task])
            ->set('title', 'Tarefa Atualizada')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('tasks.index'));

        $this->assertSame($user->id, $task->fresh()->user_id);
    }

    public function test_task_creation_requires_a_project(): void
    {
        Livewire::test(CreateTask::class)
            ->set('title', 'Tarefa sem projeto')
            ->set('status', Task::STATUS_TODO)
            ->set('priority', Task::PRIORITY_MEDIUM)
            ->call('save')
            ->assertHasErrors(['project_id' => 'required']);

        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_task_creation_requires_a_title(): void
    {
        $project = Project::factory()->create();

        Livewire::test(CreateTask::class)
            ->set('project_id', $project->id)
            ->set('title', '')
            ->call('save')
            ->assertHasErrors(['title' => 'required']);

        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_task_creation_rejects_invalid_status_and_priority(): void
    {
        $project = Project::factory()->create();

        Livewire::test(CreateTask::class)
            ->set('project_id', $project->id)
            ->set('title', 'Valores inválidos')
            ->set('status', 'invalido')
            ->set('priority', 'urgente')
            ->call('save')
            ->assertHasErrors([
                'status' => 'in',
                'priority' => 'in',
            ]);

        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_guest_can_update_a_task(): void
    {
        $originalProject = Project::factory()->create();
        $newProject = Project::factory()->create();
        $task = Task::factory()->create([
            'project_id' => $originalProject->id,
            'title' => 'Tarefa Original',
            'status' => Task::STATUS_TODO,
            'priority' => Task::PRIORITY_MEDIUM,
        ]);

        Livewire::test(CreateTask::class, ['task' => $task])
            ->set('project_id', $newProject->id)
            ->set('title', 'Tarefa Atualizada')
            ->set('status', Task::STATUS_DONE)
            ->set('priority', Task::PRIORITY_LOW)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('tasks.index'));

        $task->refresh();

        $this->assertSame('Tarefa Atualizada', $task->title);
        $this->assertSame($newProject->id, $task->project_id);
        $this->assertSame(Task::STATUS_DONE, $task->status);
        $this->assertSame(Task::PRIORITY_LOW, $task->priority);
        $this->assertDatabaseCount('tasks', 1);
    }

    public function test_clearing_task_due_date_sets_it_to_null(): void
    {
        $task = Task::factory()->create([
            'due_date' => '2026-12-31',
        ]);

        Livewire::test(CreateTask::class, ['task' => $task])
            ->set('due_date', '')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('tasks.index'));

        $this->assertNull($task->fresh()->due_date);
    }

    public function test_guest_can_delete_a_task_from_the_list(): void
    {
        $task = Task::factory()->create();

        Livewire::test(TaskList::class)
            ->call('delete', $task->id)
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_guest_can_delete_a_task_from_its_detail_page(): void
    {
        $task = Task::factory()->create();

        Livewire::test(TaskShow::class, ['task' => $task])
            ->call('delete')
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_tasks_list_shows_project_status_priority_and_due_date(): void
    {
        $project = Project::factory()->create(['name' => 'Projeto Vitrine']);
        Task::factory()->create([
            'project_id' => $project->id,
            'title' => 'Tarefa Vitrine',
            'status' => Task::STATUS_IN_PROGRESS,
            'priority' => Task::PRIORITY_MEDIUM,
            'due_date' => '2026-05-20',
        ]);

        Livewire::test(TaskList::class)
            ->assertSee('Tarefa Vitrine')
            ->assertSee('Projeto Vitrine')
            ->assertSee('Em andamento')
            ->assertSee('Média')
            ->assertSee('20/05/2026');
    }

    public function test_guest_can_filter_tasks_by_status(): void
    {
        Task::factory()->done()->create(['title' => 'Tarefa Concluida X']);
        Task::factory()->todo()->create(['title' => 'Tarefa Pendente Y']);

        Livewire::test(TaskList::class)
            ->set('statusFilter', Task::STATUS_DONE)
            ->assertSee('Tarefa Concluida X')
            ->assertDontSee('Tarefa Pendente Y');
    }

    public function test_guest_can_filter_tasks_by_priority(): void
    {
        Task::factory()->high()->create(['title' => 'Tarefa Prioridade Alta']);
        Task::factory()->low()->create(['title' => 'Tarefa Prioridade Baixa']);

        Livewire::test(TaskList::class)
            ->set('priorityFilter', Task::PRIORITY_HIGH)
            ->assertSee('Tarefa Prioridade Alta')
            ->assertDontSee('Tarefa Prioridade Baixa');
    }

    public function test_guest_can_filter_tasks_by_project(): void
    {
        $projectA = Project::factory()->create();
        $projectB = Project::factory()->create();
        Task::factory()->create(['project_id' => $projectA->id, 'title' => 'Tarefa do Projeto A']);
        Task::factory()->create(['project_id' => $projectB->id, 'title' => 'Tarefa do Projeto B']);

        Livewire::test(TaskList::class)
            ->set('projectFilter', (string) $projectA->id)
            ->assertSee('Tarefa do Projeto A')
            ->assertDontSee('Tarefa do Projeto B');
    }
}
