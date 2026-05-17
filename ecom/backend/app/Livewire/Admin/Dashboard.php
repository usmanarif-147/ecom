<?php

namespace App\Livewire\Admin;

use App\Data\AdminStaticData;
use App\Models\Order;
// use Livewire\Attributes\Layout;
// use Livewire\Attributes\Title;
use Livewire\Component;

// #[Layout('components.layouts.admin')]
// #[Title('Dashboard · Admin')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'stats'        => AdminStaticData::stats(),
            'recentOrders' => Order::latest()->take(5)->get(),
            'topProducts'  => AdminStaticData::topProducts(),
            'pageTitle'    => 'Dashboard',
        ]);
    }
}
