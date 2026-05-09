<?php

namespace App\Livewire\Admin;

use App\Data\AdminStaticData;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Orders · Admin')]
class OrderIndex extends Component
{
    public string $statusFilter = 'all';

    public function setFilter(string $status): void
    {
        $this->statusFilter = $status;
    }

    public function render()
    {
        $orders = AdminStaticData::orders();

        if ($this->statusFilter !== 'all') {
            $orders = array_values(array_filter($orders, fn ($o) => $o['status'] === $this->statusFilter));
        }

        return view('livewire.admin.order-index', [
            'orders'    => $orders,
            'pageTitle' => 'Orders',
        ]);
    }
}
