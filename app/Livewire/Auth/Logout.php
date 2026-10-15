<?php

namespace App\Livewire\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]
class Logout extends Component
{
    public function logout(): void
    {
        Auth::logout();

        $this->redirect(route('login', absolute: false));
    }

    public function render(): View
    {
        return view('auth.logout');
    }
}