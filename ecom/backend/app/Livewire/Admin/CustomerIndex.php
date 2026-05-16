<?php

namespace App\Livewire\Admin;

use App\Data\AdminStaticData;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortDir = 'desc';
    public array $selected = [];
    public bool $selectAll = false;
    public array $deletedIds = [];
    public bool $showEmailModal = false;
    public string $emailSubject = '';
    public string $emailBody = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
        $this->selected = [];
        $this->selectAll = false;
    }

    public function updatingSortDir(): void
    {
        $this->resetPage();
    }

    public function updatedSelectAll(bool $value): void
    {
        $this->selected = $value ? $this->getAllFilteredIds() : [];
    }

    public function updatedSelected(): void
    {
        $filtered = $this->getAllFilteredIds();
        $this->selectAll = count($filtered) > 0 && count($this->selected) === count($filtered);
    }

    public function deleteSelected(): void
    {
        $count = count($this->selected);
        $this->deletedIds = array_unique(array_merge($this->deletedIds, $this->selected));
        $this->selected = [];
        $this->selectAll = false;
        session()->flash('message', "{$count} customers deleted.");
    }

    public function openEmailModal(): void
    {
        if (empty($this->selected)) {
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

        $all = collect(AdminStaticData::customers())->keyBy('id');
        $count = 0;

        foreach ($this->selected as $id) {
            $row = $all->get((int) $id);
            if ($row) {
                \Illuminate\Support\Facades\Mail::to($row['email'])
                    ->send(new \App\Mail\AdminBulkMail($this->emailSubject, $this->emailBody));
                $count++;
            }
        }

        session()->flash('message', "Email sent to {$count} customers.");
        $this->selected = [];
        $this->selectAll = false;
        $this->showEmailModal = false;
        $this->emailSubject = '';
        $this->emailBody = '';
    }

    public function render()
    {
        $rows = collect(AdminStaticData::customers())
            ->reject(fn ($c) => in_array($c['id'], $this->deletedIds));

        if ($this->search !== '') {
            $term = Str::lower($this->search);
            $rows = $rows->filter(
                fn ($c) => str_contains(Str::lower($c['name']), $term)
                    || str_contains(Str::lower($c['email']), $term)
                    || str_contains(Str::lower($c['address']), $term)
            );
        }

        $rows = $this->sortDir === 'asc'
            ? $rows->sortBy('orders_count')
            : $rows->sortByDesc('orders_count');

        $perPage = 10;
        $currentPage = Paginator::resolveCurrentPage('page');
        $items = $rows->values()->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $customers = new LengthAwarePaginator(
            $items,
            $rows->count(),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath(), 'pageName' => 'page']
        );

        return view('livewire.admin.customer-index', [
            'customers' => $customers,
        ]);
    }

    private function getAllFilteredIds(): array
    {
        $rows = collect(AdminStaticData::customers())
            ->reject(fn ($c) => in_array($c['id'], $this->deletedIds));

        if ($this->search !== '') {
            $term = Str::lower($this->search);
            $rows = $rows->filter(
                fn ($c) => str_contains(Str::lower($c['name']), $term)
                    || str_contains(Str::lower($c['email']), $term)
                    || str_contains(Str::lower($c['address']), $term)
            );
        }

        return $rows->pluck('id')->map(fn ($id) => (string) $id)->values()->all();
    }
}
