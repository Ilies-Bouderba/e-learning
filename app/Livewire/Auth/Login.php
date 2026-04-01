<?php

// app/Livewire/Auth/Login.php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    protected array $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', 'These credentials do not match our records.');

            return;
        }

        session()->regenerate();

        return match (auth()->user()->role) {
            'admin' => redirect()->route('dashboard.admin'),
            'teacher' => redirect()->route('dashboard.teacher'),
            'student' => redirect()->route('dashboard.student'),
            default => redirect('/'),
        };
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
