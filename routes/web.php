<?php

use App\Livewire\Projects\CreateProject;
use App\Livewire\Projects\ProjectList;
use App\Livewire\Projects\ProjectShow;
use App\Livewire\Tasks\CreateTask;
use App\Livewire\Tasks\TaskList;
use App\Livewire\Tasks\TaskShow;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('projects.index');
});

Route::get('/projects', ProjectList::class)->name('projects.index');
Route::get('/projects/create', CreateProject::class)->name('projects.create');
Route::get('/projects/{project}', ProjectShow::class)->name('projects.show');
Route::get('/projects/{project}/edit', CreateProject::class)->name('projects.edit');

Route::get('/tasks', TaskList::class)->name('tasks.index');
Route::get('/tasks/create', CreateTask::class)->name('tasks.create');
Route::get('/tasks/{task}', TaskShow::class)->name('tasks.show');
Route::get('/tasks/{task}/edit', CreateTask::class)->name('tasks.edit');
