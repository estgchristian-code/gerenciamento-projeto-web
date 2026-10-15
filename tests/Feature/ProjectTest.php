<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $projectData = [
            'name' => 'Projeto Teste',
            'description' => 'Descrição do projeto',
            'status' => 'planejamento',
            'start_date' => '2024-01-01',
            'due_date' => '2024-12-31',
        ];

        $response = $this->actingAs($this->user)
            ->from(route('projects.index'))
            ->post(route('projects.store'), $projectData);

        $response->assertRedirect(route('projects.index'));
        $this->assertDatabaseHas('projects', [
            'name' => 'Projeto Teste',
            'user_id' => $this->user->id,
            'status' => 'planejamento',
        ]);
    }

    public function test_project_creation_requires_name(): void
    {
        $response = $this->actingAs($this->user)
            ->from(route('projects.create'))
            ->post(route('projects.store'), [
                'description' => 'Sem nome',
                'status' => 'planejamento',
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_project_creation_requires_status(): void
    {
        $response = $this->actingAs($this->user)
            ->from(route('projects.create'))
            ->post(route('projects.store'), [
                'name' => 'Projeto sem status',
            ]);

        $response->assertSessionHasErrors('status');
    }

    public function test_project_status_must_be_valid(): void
    {
        $response = $this->actingAs($this->user)
            ->from(route('projects.create'))
            ->post(route('projects.store'), [
                'name' => 'Projeto inválido',
                'status' => 'invalido',
            ]);

        $response->assertSessionHasErrors('status');
    }

    public function test_due_date_cannot_be_before_start_date(): void
    {
        $response = $this->actingAs($this->user)
            ->from(route('projects.create'))
            ->post(route('projects.store'), [
                'name' => 'Projeto com datas',
                'status' => 'planejamento',
                'start_date' => '2024-12-31',
                'due_date' => '2024-01-01',
            ]);

        $response->assertSessionHasErrors('due_date');
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

        $response = $this->actingAs($this->user)
            ->get(route('projects.show', $project));

        $response->assertStatus(403);
    }

    public function test_user_can_edit_own_project(): void
    {
        $project = Project::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Projeto Original',
            'status' => 'planejamento',
        ]);

        $response = $this->actingAs($this->user)
            ->from(route('projects.edit', $project))
            ->put(route('projects.update', $project), [
                'name' => 'Projeto Atualizado',
                'description' => 'Nova descrição',
                'status' => 'em_andamento',
            ]);

        $response->assertRedirect(route('projects.index'));
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

        $response = $this->actingAs($this->user)
            ->from(route('projects.edit', $project))
            ->put(route('projects.update', $project), [
                'name' => 'Tentativa de Edição',
            ]);

        $response->assertStatus(403);
    }

    public function test_user_can_delete_own_project(): void
    {
        $project = Project::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Projeto para Excluir',
        ]);

        $response = $this->actingAs($this->user)
            ->from(route('projects.index'))
            ->delete(route('projects.destroy', $project));

        $response->assertRedirect(route('projects.index'));
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_user_cannot_delete_other_user_project(): void
    {
        $project = Project::factory()->create([
            'user_id' => $this->otherUser->id,
            'name' => 'Projeto do Outro',
        ]);

        $response = $this->actingAs($this->user)
            ->from(route('projects.index'))
            ->delete(route('projects.destroy', $project));

        $response->assertStatus(403);
    }

    public function test_projects_list_is_isolated_by_user(): void
    {
        Project::factory()->count(3)->create(['user_id' => $this->user->id]);
        Project::factory()->count(2)->create(['user_id' => $this->otherUser->id]);

        $response = $this->actingAs($this->user)->get(route('projects.index'));
        
        $response->assertStatus(200);
        // Should only see own projects
        $this->assertEquals(3, $response->viewData('projects')->count());
    }

    public function test_user_cannot_access_create_page_as_guest(): void
    {
        $response = $this->get(route('projects.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_user_cannot_access_edit_page_as_guest(): void
    {
        $project = Project::factory()->create();
        $response = $this->get(route('projects.edit', $project));
        $response->assertRedirect(route('login'));
    }
}