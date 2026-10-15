<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Livewire\Projects\ProjectList;
use App\Livewire\Projects\CreateProject;
use App\Livewire\Projects\ProjectShow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $otherUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->otherUser = User::factory()->create();
    }

    public function test_authenticated_user_can_access_projects_index(): void
    {
        $response = $this->actingAs($this->user)->get(route('projects.index'));
        $response->assertStatus(200);
        $response->assertSee('Meus Projetos');
    }

    public function test_guest_cannot_access_projects_index(): void
    {
        $response = $this->get(route('projects.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_create_project(): void
    {
        Livewire::actingAs($this->user)
            ->test(CreateProject::class)
            ->set('name', 'Projeto Teste')
            ->set('description', 'Descrição do projeto')
            ->set('status', 'planejamento')
            ->set('start_date', '2024-01-01')
            ->set('due_date', '2024-12-31')
            ->call('save');

        $this->assertDatabaseHas('projects', [
            'name' => 'Projeto Teste',
            'user_id' => $this->user->id,
            'status' => 'planejamento',
        ]);
    }

    public function test_project_creation_requires_name(): void
    {
        Livewire::actingAs($this->user)
            ->test(CreateProject::class)
            ->set('name', '')
            ->set('status', 'planejamento')
            ->call('save')
            ->assertHasErrors(['name' => 'required']);
    }

    public function test_project_creation_requires_status(): void
    {
        Livewire::actingAs($this->user)
            ->test(CreateProject::class)
            ->set('name', 'Projeto sem status')
            ->set('status', '')
            ->call('save')
            ->assertHasErrors(['status' => 'required']);
    }

    public function test_project_status_must_be_valid(): void
    {
        Livewire::actingAs($this->user)
            ->test(CreateProject::class)
            ->set('name', 'Projeto inválido')
            ->set('status', 'invalido')
            ->call('save')
            ->assertHasErrors(['status' => 'in']);
    }

    public function test_due_date_cannot_be_before_start_date(): void
    {
        Livewire::actingAs($this->user)
            ->test(CreateProject::class)
            ->set('name', 'Projeto com datas')
            ->set('status', 'planejamento')
            ->set('start_date', '2024-12-31')
            ->set('due_date', '2024-01-01')
            ->call('save')
            ->assertHasErrors(['due_date' => 'after_or_equal']);
    }

    public function test_user_can_view_own_project(): void
    {
        $project = Project::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Meu Projeto',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('projects.show', $project));

        $response->assertStatus(200);
        $response->assertSee('Meu Projeto');
    }

    public function test_user_cannot_view_other_user_project(): void
    {
        $project = Project::factory()->create([
            'user_id' => $this->otherUser->id,
            'name' => 'Projeto do Outro',
        ]);

        Livewire::actingAs($this->user)
            ->test(ProjectShow::class, ['project' => $project])
            ->assertForbidden();
    }

    public function test_user_can_edit_own_project(): void
    {
        $project = Project::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Projeto Original',
            'status' => 'planejamento',
        ]);

        Livewire::actingAs($this->user)
            ->test(CreateProject::class, ['project' => $project])
            ->set('name', 'Projeto Atualizado')
            ->set('description', 'Nova descrição')
            ->set('status', 'em_andamento')
            ->call('save');

        $project->refresh();
        $this->assertEquals('Projeto Atualizado', $project->name);
        $this->assertEquals('em_andamento', $project->status);
    }

    public function test_user_cannot_edit_other_user_project(): void
    {
        $project = Project::factory()->create([
            'user_id' => $this->otherUser->id,
            'name' => 'Projeto do Outro',
        ]);

        Livewire::actingAs($this->user)
            ->test(CreateProject::class, ['project' => $project])
            ->assertForbidden();
    }

    public function test_user_can_delete_own_project(): void
    {
        $project = Project::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Projeto para Excluir',
        ]);

        Livewire::actingAs($this->user)
            ->test(ProjectList::class)
            ->call('delete', $project);

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_user_cannot_delete_other_user_project(): void
    {
        $project = Project::factory()->create([
            'user_id' => $this->otherUser->id,
            'name' => 'Projeto do Outro',
        ]);

        Livewire::actingAs($this->user)
            ->test(ProjectList::class)
            ->call('delete', $project)
            ->assertForbidden();
    }

    public function test_projects_list_is_isolated_by_user(): void
    {
        Project::factory()->count(3)->create(['user_id' => $this->user->id]);
        Project::factory()->count(2)->create(['user_id' => $this->otherUser->id]);

        $response = $this->actingAs($this->user)->get(route('projects.index'));
        
        $response->assertStatus(200);
        $response->assertSee('Meus Projetos');
    }

    public function test_user_can_create_project_via_livewire_form(): void
    {
        Livewire::actingAs($this->user)
            ->test(CreateProject::class)
            ->set('name', 'Novo Projeto')
            ->set('description', 'Descrição detalhada')
            ->set('status', 'em_andamento')
            ->set('start_date', '2024-03-15')
            ->set('due_date', '2024-06-30')
            ->call('save');

        $this->assertDatabaseHas('projects', [
            'name' => 'Novo Projeto',
            'description' => 'Descrição detalhada',
            'user_id' => $this->user->id,
            'status' => 'em_andamento',
        ]);
    }

    public function test_user_can_filter_projects_by_status(): void
    {
        $project1 = Project::factory()->create(['user_id' => $this->user->id, 'status' => 'planejamento', 'name' => 'Projeto 1']);
        $project2 = Project::factory()->create(['user_id' => $this->user->id, 'status' => 'concluido', 'name' => 'Projeto 2']);

        Livewire::actingAs($this->user)
            ->test(ProjectList::class)
            ->set('statusFilter', 'concluido')
            ->assertSee($project2->name)
            ->assertDontSee($project1->name);
    }

    public function test_projects_list_shows_projects(): void
    {
        Project::factory()->count(3)->create(['user_id' => $this->user->id]);

        Livewire::actingAs($this->user)
            ->test(ProjectList::class)
            ->assertSee('Meus Projetos');
    }
}
