<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $role = 'student';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $terms = false;

    public function register()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:student,teacher',
            'password' => 'required|min:8|confirmed',
            'terms' => 'accepted',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'password' => Hash::make($this->password),
        ]);

        Auth::login($user);

        session()->regenerate();

        if ($role = "student") {
            return redirect('/dashboard/student');
        }else {
            return redirect('/dashboard/teacher');
        }

    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
