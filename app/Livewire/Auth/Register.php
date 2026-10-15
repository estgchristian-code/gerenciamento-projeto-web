<?php

namespace App\Livewire\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]
class Register extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(): void
    {
        Session::forget('errors');
    }

    public function register(RegisterRequest $request): void
    {
        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        $this->redirect(route('dashboard', absolute: false));
    }

    public function render(): View
    {
        $livewireErrorBag = $this->getErrorBag();

        return view('auth.register', [
            'errors' => new \Illuminate\Support\ViewErrorBag([
                'default' => $livewireErrorBag,
            ]),
        ]);
    }
}