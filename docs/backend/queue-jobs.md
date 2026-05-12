# CSV Import — Queues & Jobs

## Problems
1. Large CSV uploads block the HTTP request and time out.
2. No early structural check — a wrong CSV runs the whole loop before failing.
3. Whole file is read into a PHP array → memory overflow on 1M rows.
4. One `INSERT` + two `sync()` queries per row → millions of queries for big files.
5. One bad row breaks the import; there is no dirty/duplicate handling.
6. Progress bar shows fake hard-coded values, not real progress.
7. User must stay on the page; navigating away kills the import.
8. No final report — user has no idea how many rows were inserted, duplicated, or rejected.

## Use cases
1. Admin uploads a CSV with anywhere from 10 to 1,000,000 product rows.
2. System rejects the file early if its columns don’t match the products table.
3. Big files are split into smaller chunk files so they can be processed in parallel.
4. Each row is validated (required fields present, category/size/color exist).
5. Duplicate rows are skipped; rows with missing required columns are marked "dirty".
6. Valid rows are inserted into the DB in bulk.
7. Progress (%) updates live while the user works on other pages.
8. When done, user sees a full report:
   - total rows inserted
   - total duplicate rows
   - total rows with missing required data
   - the actual duplicate rows (so the user can fix them)
   - the actual rows with missing data (so the user can fix them)

## Solutions
1. Move the uploaded CSV from temp storage to MinIO so background jobs can read it later.
2. Create an `import_jobs` row (status, total, processed, inserted, duplicate, missing, started_at, finished_at, batch_id).
3. Dispatch a queued **"SplitCsv"** job and return immediately — UI is never blocked.
4. The Split job reads the CSV row-by-row (streaming, no full-file array) and writes chunk files of ~10k rows each to MinIO.
5. The Split job then dispatches a `Bus::batch()` of **"ImportChunk"** jobs — one per chunk file.
6. Each ImportChunk job validates rows, separates them into:
   - **valid** → bulk insert into `products` + pivots (chunked, inside a DB transaction)
   - **duplicate** → append to `duplicates.csv` in MinIO + bump counter
   - **missing data** → append to `missing.csv` in MinIO + bump counter
7. The batch's `then()` callback marks the `import_jobs` row as `done` and triggers cleanup.
8. `ProgressReport` uses `wire:poll.2s` to read progress from `import_jobs` (or `$batch->progress()`).
9. A small badge/toast in the admin layout notifies when the import finishes.
10. Final report screen shows totals + links to download `duplicates.csv` and `missing.csv`.
11. After report is acknowledged, delete the source CSV and chunk files from MinIO.

## Laravel tools required
1. Queues (`config/queue.php`) with the Redis driver.
2. A long-running `php artisan queue:work` worker (runs in the app container).
3. Two job classes — `App\Jobs\SplitCsv` and `App\Jobs\ImportChunk`.
4. **`Bus::batch()`** — to dispatch and track many ImportChunk jobs as one unit, with `progress()`, `then()`, `catch()`, `finally()` callbacks.
5. Eloquent bulk `insert()` for products and pivot tables.
6. `Storage::disk('s3')` (MinIO) for the uploaded CSV, chunk files, and report files.
7. Migrations for `import_jobs` table and Laravel's built-in `job_batches` table (`php artisan queue:batches-table`).
8. Livewire `wire:poll` for live progress in the browser.
9. `Validator` facade for per-row validation.
10. PHP `fgetcsv()` for streaming reads.

## What I will learn at the end
1. Why some tasks must run outside the HTTP request.
2. How Laravel queues, workers, and jobs fit together.
3. How to track long-running work using a DB-backed status model.
4. How to stream large files without exhausting memory.
5. How to split one big workload into many small jobs.
6. How to use **`Bus::batch()`** to run many jobs together and react to "all done".
7. How to insert thousands of rows efficiently using bulk inserts.
8. How to show live progress in Livewire with polling.
9. How to design a clean **upload → split → batch → process → report → notify** flow.

## What kind of problems can I solve after learn this
1. Bulk imports/exports of any kind (orders, customers, inventory).
2. Sending thousands of emails or SMS without blocking the user.
3. Generating heavy reports (PDF/Excel) in the background.
4. Image/video pipelines — resize, transcode, push to S3.
5. Periodic data syncs with external APIs (rate-limited, retryable).
6. Any long-running task that must not freeze the UI.
7. Distributing work across multiple workers to scale horizontally.
8. Fan-out / fan-in workflows where many small jobs feed one final "report" step.
