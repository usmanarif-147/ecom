<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public array $selectedEmails = [];
    public bool $selectAll = false;
    public bool $showEmailModal = false;
    public string $emailSubject = '';
    public string $emailBody = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
        $this->selectAll = false;
        $this->selectedEmails = [];
    }

    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            $all = $this->getFilteredCustomers();
            $this->selectedEmails = $all->pluck('customer_email')->values()->all();
        } else {
            $this->selectedEmails = [];
        }
    }

    public function updatedSelectedEmails(): void
    {
        $total = $this->getFilteredCustomers()->count();
        $this->selectAll = $total > 0 && count($this->selectedEmails) === $total;
    }

    public function openEmailModal(): void
    {
        if (empty($this->selectedEmails)) {
            return;
        }
        $this->showEmailModal = true;
    }

    public function closeEmailModal(): void
    {
        $this->showEmailModal = false;
        $this->emailSubject = '';
        $this->emailBody = '';
        $this->resetValidation();
    }

    public function sendBulkEmail(): void
    {
        $this->validate([
            'emailSubject' => 'required',
            'emailBody'    => 'required',
        ]);

        $count = 0;
        foreach ($this->selectedEmails as $email) {
            \Illuminate\Support\Facades\Mail::to($email)
                ->send(new \App\Mail\AdminBulkMail($this->emailSubject, $this->emailBody));
            $count++;
        }

        session()->flash('message', "Email sent to {$count} customers.");
        $this->selectedEmails = [];
        $this->selectAll = false;
        $this->showEmailModal = false;
        $this->emailSubject = '';
        $this->emailBody = '';
    }

    public function render()
    {
        $all = $this->getFilteredCustomers();

        $perPage = 10;
        $page = $this->getPage();

        $customers = new LengthAwarePaginator(
            $all->forPage($page, $perPage)->values(),
            $all->count(),
            $perPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()]
        );

        return view('livewire.admin.customer-index', compact('customers'));
    }

    private function getFilteredCustomers()
    {
        $all = Order::latest()->get()->unique('customer_email')->values();

        if ($this->search) {
            $term = strtolower($this->search);
            $all = $all->filter(fn ($o) =>
                str_contains(strtolower($o->customer_name), $term) ||
                str_contains(strtolower($o->customer_email), $term) ||
                str_contains(strtolower($o->customer_phone_number ?? ''), $term) ||
                str_contains(strtolower($o->customer_address), $term)
            )->values();
        }

        return $all;
    }
}
