<?php

namespace App\Livewire\Admin;

use App\Enums\OrderStatus;
use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderIndex extends Component
{
    use WithPagination;

    public string $statusFilter = 'all';
    public bool $showOrderModal = false;
    public ?int $selectedOrderId = null;

    public function setFilter(string $status): void
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function viewOrder(int $id): void
    {
        $this->selectedOrderId = $id;
        $this->showOrderModal = true;
    }

    public function closeOrderModal(): void
    {
        $this->showOrderModal = false;
        $this->selectedOrderId = null;
    }

    public function render()
    {
        $statusEnum = match ($this->statusFilter) {
            'pending'   => OrderStatus::Pending,
            'shipped'   => OrderStatus::Shipped,
            'delivered' => OrderStatus::Delivered,
            'cancelled' => OrderStatus::Cancelled,
            default     => null,
        };

        $query = Order::query()->latest();
        if ($statusEnum) {
            $query->where('status', $statusEnum);
        }
        $orders = $query->paginate(20);

        $selectedOrder = $this->selectedOrderId
            ? Order::with('items')->find($this->selectedOrderId)
            : null;

        return view('livewire.admin.order-index', compact('orders', 'selectedOrder') + ['pageTitle' => 'Orders']);
    }
}
