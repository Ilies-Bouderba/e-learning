<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Login extends Component
{
     #[Rule('required|email')]
    public string $email = '';

    #[Rule('required|min:8')]
    public string $password = '';

    public bool $remember = false;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', 'These credentials do not match our records.');
            return;
        }

        session()->regenerate();

        return redirect()->intended('/');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
