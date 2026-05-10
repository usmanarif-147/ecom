<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Logout extends Component
{
    public string $variant = 'topbar';

    public function mount(string $variant = 'topbar'): void
    {
        $this->variant = $variant;
    }

    public function submit()
    {
        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function render()
    {
        return view('livewire.admin.logout-button');
    }
}
