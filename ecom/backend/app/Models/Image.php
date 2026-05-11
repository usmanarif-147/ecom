<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable('product_id', 'path')]
class Image extends Model
{
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected function url(): Attribute
    {
        return Attribute::make(get: function () {
            if (str_starts_with($this->path, 'http')) {
                return $this->path;
            }
            if (str_starts_with($this->path, 'images/')) {
                return asset($this->path);
            }
            return Storage::disk('s3')->temporaryUrl($this->path, now()->addHour());
        });
    }
}
