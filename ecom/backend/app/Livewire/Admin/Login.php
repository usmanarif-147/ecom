<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.auth')]
#[Title('Sign in · Admin')]
class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function submit()
    {
        // Static UI only — no real auth.
        return redirect()->route('admin.dashboard');
    }

    public function render()
    {
        return view('livewire.admin.login');
    }
}
