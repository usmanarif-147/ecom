<?php

namespace App\Jobs;

use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Color;
use App\Models\ImportJob;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ImportChunk implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 600;

    private const REQUIRED_FIELDS = ['category', 'title', 'price', 'cost', 'stock', 'status'];

    public function __construct(public int $importJobId, public string $chunkKey) {}

    public function handle(): void
    {
        if ($this->batch()?->cancelled()) {
            return;
        }

        // 1) Cache valid lookup IDs once per chunk
        $validCategoryIds = Category::pluck('id')->all();
        $validSizeIds     = Size::pluck('id')->all();
        $validColorIds    = Color::pluck('id')->all();

        // 2) Stream the chunk CSV and parse all rows
        $stream = Storage::disk('s3')->readStream($this->chunkKey);
        if (! is_resource($stream)) {
            Log::channel('queue')->error("ImportChunk: cannot read {$this->chunkKey}");
            return;
        }

        $header = fgetcsv($stream);
        $parsed = [];
        while (($row = fgetcsv($stream)) !== false) {
            $parsed[] = array_combine($header, $row);
        }
        fclose($stream);

        // 3) Look up existing (title, category_id) combos — scoped to titles in this chunk
        $titlesInChunk = array_unique(array_column($parsed, 'title'));
        $existingKeys = Product::whereIn('title', $titlesInChunk)
            ->get(['title', 'category_id'])
            ->mapWithKeys(fn($p) => ["{$p->category_id}|{$p->title}" => true])
            ->all();

        // 4) Classify rows
        $valid = $duplicate = $missing = [];
        $seenInChunk = [];

        foreach ($parsed as $row) {
            // Missing required fields
            foreach (self::REQUIRED_FIELDS as $field) {
                if (! isset($row[$field]) || $row[$field] === '') {
                    $missing[] = $row;
                    continue 2;
                }
            }

            // Foreign keys must exist
            $catId = (int) $row['category'];
            if (! in_array($catId, $validCategoryIds, true)) {
                $missing[] = $row;
                continue;
            }

            $sizes  = $row['size']  !== '' ? array_map('intval', explode('|', $row['size']))  : [];
            $colors = $row['color'] !== '' ? array_map('intval', explode('|', $row['color'])) : [];

            if (array_diff($sizes, $validSizeIds) || array_diff($colors, $validColorIds)) {
                $missing[] = $row;
                continue;
            }

            // Duplicate?
            $key = "{$catId}|{$row['title']}";
            if (isset($existingKeys[$key]) || isset($seenInChunk[$key])) {
                $duplicate[] = $row;
                continue;
            }
            $seenInChunk[$key] = true;

            // Valid — stash parsed sizes/colors for the insert step
            $row['_sizes']  = $sizes;
            $row['_colors'] = $colors;
            $valid[]        = $row;
        }

        // 5) Bulk insert valid rows inside a single transaction
        $insertedCount = 0;
        if (! empty($valid)) {
            $insertedCount = $this->insertValidRows($valid);
        }

        // 6) Persist dirty rows to per-chunk files (no race condition)
        $chunkIndex = pathinfo($this->chunkKey, PATHINFO_FILENAME); // "0", "1", ...
        $folder     = dirname(dirname($this->chunkKey));            // imports/{uuid}

        if (! empty($duplicate)) {
            $this->writeCsv("{$folder}/duplicates/{$chunkIndex}.csv", $header, $duplicate);
        }
        if (! empty($missing)) {
            $this->writeCsv("{$folder}/missing/{$chunkIndex}.csv", $header, $missing);
        }

        // 7) Atomic counter update on the ImportJob row
        $processed = count($parsed);
        ImportJob::where('id', $this->importJobId)->update([
            'processed_rows' => DB::raw("processed_rows + {$processed}"),
            'inserted_rows'  => DB::raw("inserted_rows + {$insertedCount}"),
            'duplicate_rows' => DB::raw("duplicate_rows + " . count($duplicate)),
            'missing_rows'   => DB::raw("missing_rows + "   . count($missing)),
        ]);

        Log::channel('queue')->info(
            "ImportChunk done: chunk={$this->chunkKey} "
                . "valid={$insertedCount} dup=" . count($duplicate) . " miss=" . count($missing)
        );
    }

    private function insertValidRows(array $rows): int
    {
        $count = 0;
        DB::transaction(function () use ($rows, &$count) {
            foreach ($rows as $row) {
                $product = Product::create([
                    'category_id' => (int) $row['category'],
                    'title'       => $row['title'],
                    'description' => $row['description'] !== '' ? $row['description'] : null,
                    'price'       => $row['price'],
                    'cost'        => $row['cost'],
                    'stock'       => (int) $row['stock'],
                    'status'      => ProductStatus::from((int) $row['status']),
                ]);

                if (! empty($row['_sizes'])) {
                    $product->sizes()->attach($row['_sizes']);
                }
                if (! empty($row['_colors'])) {
                    $product->colors()->attach($row['_colors']);
                }
                $count++;
            }
        });
        return $count;
    }

    private function writeCsv(string $key, array $header, array $rows): void
    {
        $fh = fopen('php://temp', 'r+');
        fputcsv($fh, $header);

        foreach ($rows as $row) {
            // Strip private fields like _sizes / _colors that we added in memory
            $clean = array_filter(
                $row,
                fn($k) => ! str_starts_with($k, '_'),
                ARRAY_FILTER_USE_KEY
            );
            // Re-order by header so the CSV columns stay aligned
            $ordered = array_map(fn($col) => $clean[$col] ?? '', $header);
            fputcsv($fh, $ordered);
        }
        rewind($fh);

        Storage::disk('s3')->put($key, $fh);
        fclose($fh);
    }

    public function failed(Throwable $e): void
    {
        Log::channel('queue')
            ->error("ImportChunk failed: {$this->chunkKey}: {$e->getMessage()}");
    }
}
