<?php

namespace Tests\Feature;

use App\Livewire\Projects\CreateProject;
use App\Livewire\Projects\ProjectList;
use App\Livewire\Projects\ProjectShow;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_root_redirects_to_projects_index(): void
    {
        $this->get('/')->assertRedirect(route('projects.index'));
    }

    public function test_guest_can_access_projects_index(): void
    {
        $response = $this->get(route('projects.index'));

        $response->assertStatus(200);
        $response->assertSee('Meus Projetos');
    }

    public function test_guest_can_access_project_create_form(): void
    {
        $this->get(route('projects.create'))
            ->assertStatus(200)
            ->assertSee('Novo Projeto');
    }

    public function test_guest_can_access_project_edit_form(): void
    {
        $project = Project::factory()->create(['name' => 'Projeto Editável']);

        $response = $this->get(route('projects.edit', $project));
        $response->assertStatus(200);
        $response->assertSee('Editar Projeto');
    }

    public function test_guest_can_create_project(): void
    {
        Livewire::test(CreateProject::class)
            ->set('name', 'Projeto Teste')
            ->set('description', 'Descrição do projeto teste')
            ->set('status', 'planejamento')
            ->set('start_date', '2024-01-01')
            ->set('due_date', '2024-12-31')
            ->call('save')
            ->assertRedirect(route('projects.index'));

        $this->assertDatabaseHas('projects', [
            'name' => 'Projeto Teste',
            'description' => 'Descrição do projeto teste',
            'status' => 'planejamento',
        ]);
    }

    public function test_project_creation_requires_name(): void
    {
        Livewire::test(CreateProject::class)
            ->set('name', '')
            ->set('status', 'planejamento')
            ->call('save')
            ->assertHasErrors(['name' => 'required']);
    }

    public function test_project_creation_requires_status(): void
    {
        Livewire::test(CreateProject::class)
            ->set('name', 'Projeto sem status')
            ->set('status', '')
            ->call('save')
            ->assertHasErrors(['status' => 'required']);
    }

    public function test_project_status_must_be_valid(): void
    {
        Livewire::test(CreateProject::class)
            ->set('name', 'Projeto inválido')
            ->set('status', 'invalido')
            ->call('save')
            ->assertHasErrors(['status' => 'in']);
    }

    public function test_due_date_cannot_be_before_start_date(): void
    {
        Livewire::test(CreateProject::class)
            ->set('name', 'Projeto com datas')
            ->set('status', 'planejamento')
            ->set('start_date', '2024-12-31')
            ->set('due_date', '2024-01-01')
            ->call('save')
            ->assertHasErrors(['due_date' => 'after_or_equal']);
    }

    public function test_guest_can_view_project(): void
    {
        $project = Project::factory()->create([
            'name' => 'Meu Projeto',
            'status' => 'planejamento',
        ]);

        $this->get(route('projects.show', $project))
            ->assertStatus(200)
            ->assertSee('Meu Projeto')
            ->assertSee('Planejamento');
    }

    public function test_guest_can_edit_project(): void
    {
        $project = Project::factory()->create([
            'name' => 'Projeto Original',
            'status' => 'planejamento',
        ]);

        Livewire::test(CreateProject::class, ['project' => $project])
            ->set('name', 'Projeto Atualizado')
            ->set('description', 'Nova descrição')
            ->set('status', 'em_andamento')
            ->call('save')
            ->assertRedirect(route('projects.index'));

        $project->refresh();

        $this->assertEquals('Projeto Atualizado', $project->name);
        $this->assertEquals('Nova descrição', $project->description);
        $this->assertEquals('em_andamento', $project->status);
    }

    public function test_guest_can_delete_project_from_list(): void
    {
        $project = Project::factory()->create(['name' => 'Projeto para Excluir']);

        Livewire::test(ProjectList::class)
            ->call('delete', $project->id)
            ->assertRedirect(route('projects.index'));

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_guest_can_delete_project_from_show(): void
    {
        $project = Project::factory()->create(['name' => 'Projeto Detalhe']);

        Livewire::test(ProjectShow::class, ['project' => $project])
            ->call('delete')
            ->assertRedirect(route('projects.index'));

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_projects_list_shows_projects_created_by_anyone(): void
    {
        Project::factory()->create(['name' => 'Projeto de Outro']);
        Project::factory()->create(['name' => 'Projeto Qualquer']);

        $this->get(route('projects.index'))
            ->assertStatus(200)
            ->assertSee('Projeto de Outro')
            ->assertSee('Projeto Qualquer');
    }

    public function test_guest_can_filter_projects_by_status(): void
    {
        $visible = Project::factory()->create(['status' => 'concluido', 'name' => 'Projeto Visível']);
        Project::factory()->create(['status' => 'planejamento', 'name' => 'Projeto Oculto']);

        Livewire::test(ProjectList::class)
            ->set('statusFilter', 'concluido')
            ->assertSee($visible->name)
            ->assertDontSee('Projeto Oculto');
    }

    public function test_projects_list_shows_projects(): void
    {
        Project::factory()->count(3)->create(['name' => 'Projeto Listado']);

        Livewire::test(ProjectList::class)
            ->assertSee('Meus Projetos')
            ->assertSee('Projeto Listado');
    }

    public function test_creating_a_project_flashes_success_message(): void
    {
        Livewire::test(CreateProject::class)
            ->set('name', 'Projeto com Feedback')
            ->set('status', 'planejamento')
            ->call('save')
            ->assertRedirect(route('projects.index'));

        $this->assertNotNull(session('success'));
    }

    public function test_deleting_a_project_flashes_success_message(): void
    {
        $project = Project::factory()->create();

        Livewire::test(ProjectList::class)
            ->call('delete', $project->id)
            ->assertRedirect(route('projects.index'));

        $this->assertNotNull(session('success'));
    }

    public function test_clearing_filters_resets_search_and_status(): void
    {
        Livewire::test(ProjectList::class)
            ->set('search', 'algo')
            ->set('statusFilter', 'concluido')
            ->call('clearFilters')
            ->assertSet('search', '')
            ->assertSet('statusFilter', '');
    }

    public function test_projects_list_shows_empty_state_when_no_projects_exist(): void
    {
        Livewire::test(ProjectList::class)
            ->assertSee('Nenhum projeto cadastrado');
    }
}
