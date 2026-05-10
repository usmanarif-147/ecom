<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Logout extends Component
{

    public function submit()
    {
        session()->flush();
        auth()->logout();

        return redirect()->route('admin.login');
    }

    public function render()
    {
        return view('livewire.admin.logout-button');
    }
}
