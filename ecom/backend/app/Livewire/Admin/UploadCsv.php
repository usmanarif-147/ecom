<?php

namespace App\Livewire\Admin;

use App\Enums\ProductImportStatus;
use App\Jobs\SplitCsv;
use App\Models\ImportJob;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadCsv extends Component
{
    use WithFileUploads;

    public $csv = null;
    public $counter = 0;

    public $rules = [];

    public function import()
    {
        $this->validate([
            'csv' => 'required|file|mimetypes:text/plain,text/csv,application/vnd.ms-excel|max:204800', // 50 MB
        ]);

        $uuid = (string) Str::uuid();
        $key  = "imports/{$uuid}/source.csv";

        $this->csv->storeAs("imports/{$uuid}", 'source.csv', 's3');

        $importJob = ImportJob::create([
            'user_id'   => auth()->id(),
            'file_path' => $key,
            'status'    => ProductImportStatus::Queued,
        ]);

        SplitCsv::dispatch($importJob->id);

        $this->reset('csv');
        $this->dispatch('import-started', importJobId: $importJob->id);

        session()->flash('message', 'Import queued. You can keep working — progress will appear below.');
    }


    public function render()
    {
        return view('livewire.admin.upload-csv');
    }
}
