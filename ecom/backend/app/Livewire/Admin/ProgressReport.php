<?php

namespace App\Livewire\Admin;

use App\Enums\ProductImportStatus;
use App\Models\ImportJob;
use Livewire\Attributes\On;
use Livewire\Component;

class ProgressReport extends Component
{
    public ?int $importJobId = null;
    public ?int $lastStatus  = null;

    public function mount(): void
    {
        $active = ImportJob::where('user_id', auth()->id())
            ->whereIn('status', [
                ProductImportStatus::Queued->value,
                ProductImportStatus::Running->value,
            ])
            ->latest('id')
            ->first();

        if ($active) {
            $this->importJobId = $active->id;
            $this->lastStatus  = $active->status->value;
        }
    }

    #[On('import-started')]
    public function show(int $importJobId): void
    {
        $this->importJobId = $importJobId;
        $this->lastStatus  = ProductImportStatus::Queued->value;
    }

    public function render()
    {
        $importJob = $this->importJobId ? ImportJob::find($this->importJobId) : null;

        // Notify siblings (e.g. ProductIndex) once on terminal transition
        if ($importJob) {
            $currentStatus = $importJob->status->value;
            $isTerminal    = in_array($currentStatus, [
                ProductImportStatus::Done->value,
                ProductImportStatus::Failed->value,
            ], true);

            if ($isTerminal && $currentStatus !== $this->lastStatus) {
                $this->dispatch('import-finished', importJobId: $this->importJobId);
            }
            $this->lastStatus = $currentStatus;
        }

        return view('livewire.admin.progress-report', [
            'importJob' => $importJob,
        ]);
    }
}
