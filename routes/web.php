<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Projects\CreateProject;
use App\Livewire\Projects\ProjectList;
use App\Livewire\Projects\ProjectShow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('layouts.app', [
            'title' => 'Dashboard',
            'slot' => view('dashboard.content')->render(),
        ]);
    })->name('dashboard');
    
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');

    Route::get('/projects', ProjectList::class)->name('projects.index');
    Route::get('/projects/create', CreateProject::class)->name('projects.create');
    Route::get('/projects/{project}', ProjectShow::class)->name('projects.show');
    Route::get('/projects/{project}/edit', CreateProject::class)->name('projects.edit');
});