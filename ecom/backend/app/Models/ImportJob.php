<?php

namespace App\Models;

use App\Enums\ProductImportStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(
    'user_id',
    'file_path',
    'status',
    'batch_id',
    'total_rows',
    'processed_rows',
    'inserted_rows',
    'duplicate_rows',
    'missing_rows',
    'started_at',
    'finished_at',
    'error',
)]

class ImportJob extends Model
{
    protected function casts(): array
    {
        return [
            'status'       => ProductImportStatus::class,
            'started_at'   => 'datetime',
            'finished_at'  => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
