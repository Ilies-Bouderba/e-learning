<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public string $name                  = '';
    public string $email                 = '';
    public string $role                  = 'student';
    public string $password              = '';
    public string $password_confirmation = '';
    public bool   $terms                 = false;

    protected array $rules = [
        'name'                  => 'required|string|max:255',
        'email'                 => 'required|email|unique:users,email',
        'role'                  => 'required|in:student,teacher',
        'password'              => 'required|string|min:8|confirmed',
        'terms'                 => 'accepted',
    ];

    public function register(): mixed
    {
        $this->validate();

        $user = User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'role'     => $this->role,
            'password' => Hash::make($this->password),
        ]);

        Auth::login($user);

        return match ($user->role) {
            'teacher' => redirect()->route('teacher.dashboard'),
            default   => redirect()->route('student.dashboard'),
        };
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
