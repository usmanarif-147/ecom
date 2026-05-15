<?php

namespace App\Jobs;

use App\Enums\ProductImportStatus;
use App\Models\ImportJob;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SplitCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 600;

    private const REQUIRED_COLUMNS = [
        'category',
        'title',
        'description',
        'price',
        'cost',
        'stock',
        'status',
        'size',
        'color',
    ];
    private const CHUNK_SIZE = 10000;

    public function __construct(public int $importJobId) {}

    public function handle(): void
    {
        dd('this is handle method');
        $importJob = ImportJob::findOrFail($this->importJobId);

        $importJob->update([
            'status'     => ProductImportStatus::Running,
            'started_at' => now(),
        ]);

        Log::channel('queue')->info("SplitCsv started for import {$this->importJobId}");

        $stream = Storage::disk('s3')->readStream($importJob->file_path);
        if (! is_resource($stream)) {
            $this->markFailed($importJob, 'Cannot open source CSV from storage.');
            return;
        }

        $header = fgetcsv($stream);
        if (! $header || array_diff(self::REQUIRED_COLUMNS, $header)) {
            fclose($stream);
            $this->markFailed($importJob, 'CSV header is missing required columns.');
            return;
        }

        $folder     = dirname($importJob->file_path);  // imports/{uuid}
        $buffer     = [];
        $chunkIndex = 0;
        $totalRows  = 0;
        $chunkKeys  = [];

        while (($row = fgetcsv($stream)) !== false) {
            $buffer[]   = $row;
            $totalRows++;

            if (count($buffer) >= self::CHUNK_SIZE) {
                $chunkKeys[] = $this->writeChunk($folder, $chunkIndex, $header, $buffer);
                $buffer      = [];
                $chunkIndex++;
            }
        }
        fclose($stream);

        if (! empty($buffer)) {
            $chunkKeys[] = $this->writeChunk($folder, $chunkIndex, $header, $buffer);
        }

        $jobs = array_map(
            fn(string $key) => new ImportChunk($this->importJobId, $key),
            $chunkKeys,
        );

        $importJobId = $this->importJobId;

        $batch = Bus::batch($jobs)
            ->name("Product import {$importJobId}")
            ->allowFailures()
            ->then(function (Batch $batch) use ($importJobId) {
                ImportJob::where('id', $importJobId)->update([
                    'status'      => ProductImportStatus::Done,
                    'finished_at' => now(),
                ]);
                Log::channel('queue')->info("Batch finished cleanly for import {$importJobId}");
            })
            ->catch(function (Batch $batch, Throwable $e) use ($importJobId) {
                Log::channel('queue')
                    ->error("Batch error for import {$importJobId}: {$e->getMessage()}");
            })
            ->finally(function (\Illuminate\Bus\Batch $batch) use ($importJobId) {
                $job = ImportJob::find($importJobId);
                if (! $job) {
                    return;
                }

                // imports/{uuid}/source.csv  ->  imports/{uuid}
                $prefix = dirname($job->file_path);

                Storage::disk('s3')->deleteDirectory($prefix);

                Log::channel('queue')->info("Cleanup done for import {$importJobId}: removed {$prefix}");
            })
            ->dispatch();

        $importJob->update([
            'total_rows' => $totalRows,
            'batch_id'   => $batch->id,
        ]);

        Log::channel('queue')->info(
            "SplitCsv done: {$totalRows} rows -> " . count($chunkKeys) . " chunks, batch={$batch->id}"
        );
    }

    private function writeChunk(string $folder, int $index, array $header, array $rows): string
    {
        $key = "{$folder}/chunks/{$index}.csv";

        $fh = fopen('php://temp', 'r+');
        fputcsv($fh, $header);
        foreach ($rows as $row) {
            fputcsv($fh, $row);
        }
        rewind($fh);

        Storage::disk('s3')->put($key, $fh);
        fclose($fh);

        return $key;
    }

    public function failed(Throwable $e): void
    {
        ImportJob::where('id', $this->importJobId)->update([
            'status' => ProductImportStatus::Failed,
            'error'  => $e->getMessage(),
        ]);
        Log::channel('queue')
            ->error("SplitCsv failed for import {$this->importJobId}: {$e->getMessage()}");
    }

    private function markFailed(string $reason): void
    {
        $job = ImportJob::find($this->importJobId);

        if (! $job) {
            return;
        }

        $job->update([
            'status'      => ProductImportStatus::Failed,
            'error'       => $reason,
            'finished_at' => now(),
        ]);

        // Best-effort cleanup — safe if the dir doesn't exist yet
        $prefix = dirname($job->file_path);
        Storage::disk('s3')->deleteDirectory($prefix);

        Log::channel('queue')->warning("SplitCsv failed for import {$this->importJobId}: {$reason}");
    }
}
