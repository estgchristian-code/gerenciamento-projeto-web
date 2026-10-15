<?php

namespace App\Livewire\Auth;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]
class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public function mount(): void
    {
        Session::forget('errors');
    }

    public function login(LoginRequest $request): void
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials, $this->remember)) {
            $request->session()->regenerate();

            $this->redirectIntended(route('dashboard', absolute: false));
        }

        $this->addError('email', 'As credenciais fornecidas não conferem com nossos registros.');
    }

    public function render(): View
    {
        $livewireErrorBag = $this->getErrorBag();

        return view('auth.login', [
            'errors' => new \Illuminate\Support\ViewErrorBag([
                'default' => $livewireErrorBag,
            ]),
        ]);
    }
}