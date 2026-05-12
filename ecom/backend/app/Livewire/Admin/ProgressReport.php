<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\On;
use Livewire\Component;

class ProgressReport extends Component
{
    public $show = false;
    public $counter = 0;

    #[On('show-progress-bar')]
    public function showProgressBar($counter)
    {
        $this->counter = $counter;
        if ($this->counter > 0) {
            $this->show = true;
        }
    }

    public function render()
    {
        return view('livewire.admin.progress-report');
    }
}
