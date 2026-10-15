<?php

use App\Livewire\Projects\CreateProject;
use App\Livewire\Projects\ProjectList;
use App\Livewire\Projects\ProjectShow;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('projects.index');
});

Route::get('/projects', ProjectList::class)->name('projects.index');
Route::get('/projects/create', CreateProject::class)->name('projects.create');
Route::get('/projects/{project}', ProjectShow::class)->name('projects.show');
Route::get('/projects/{project}/edit', CreateProject::class)->name('projects.edit');
