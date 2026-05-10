<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function submit()
    {
        if (Auth::attempt(
            ['email' => $this->email, 'password' => $this->password],
            $this->remember
        )) {
            session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        $this->addError('email', 'Invalid email or password.');
    }

    public function render()
    {
        return view('livewire.admin.login');
    }
}
