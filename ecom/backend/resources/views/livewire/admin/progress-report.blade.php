@use(App\Enums\ProductImportStatus)

@php
    $isActive = $importJob && in_array($importJob->status, [ProductImportStatus::Queued, ProductImportStatus::Running]);
    $percent =
        $importJob && $importJob->total_rows
            ? min(100, (int) round(($importJob->processed_rows / $importJob->total_rows) * 100))
            : 0;
@endphp

<div @if ($isActive) wire:poll.2s @endif class="my-4">
    @if (!$importJob)
        {{-- nothing in-flight, render empty so the slot collapses --}}
    @elseif ($importJob->status === ProductImportStatus::Queued)
        <div class="rounded-md border border-gray-200 bg-gray-50 p-4 text-sm text-gray-700">
            <div class="font-medium">Import queued</div>
            <div class="text-xs text-gray-500 mt-1">Waiting for the worker to pick this up…</div>
        </div>
    @elseif ($importJob->status === ProductImportStatus::Running)
        <div class="rounded-md border border-blue-200 bg-blue-50 p-4">
            <div class="flex items-center justify-between text-sm font-medium text-blue-900">
                <span>Importing</span>
                <span>{{ number_format($importJob->processed_rows) }}
                    / {{ number_format($importJob->total_rows ?? 0) }} rows</span>
            </div>
            <div class="w-full bg-blue-200/60 rounded-full h-2.5 mt-3 overflow-hidden">
                <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300"
                    style="width: {{ $percent }}%"></div>
            </div>
            <div class="text-xs text-blue-900/70 mt-2">
                {{ $percent }}% &middot;
                inserted {{ number_format($importJob->inserted_rows) }} &middot;
                duplicates {{ number_format($importJob->duplicate_rows) }} &middot;
                missing {{ number_format($importJob->missing_rows) }}
            </div>
        </div>
    @elseif ($importJob->status === ProductImportStatus::Done)
        <div class="rounded-md border border-green-200 bg-green-50 p-4 text-sm text-green-900">
            <div class="font-medium">✓ Import complete</div>
            <div class="text-xs mt-1">
                inserted {{ number_format($importJob->inserted_rows) }} &middot;
                duplicates {{ number_format($importJob->duplicate_rows) }} &middot;
                missing {{ number_format($importJob->missing_rows) }}
            </div>
        </div>
    @elseif ($importJob->status === ProductImportStatus::Failed)
        <div class="rounded-md border border-rose-200 bg-rose-50 p-4 text-sm text-rose-900">
            <div class="font-medium">✗ Import failed</div>
            <div class="text-xs mt-1">{{ $importJob->error }}</div>
        </div>
    @endif
</div>
