<?php

namespace App\Models;

use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable('category_id', 'title', 'description', 'price', 'cost', 'stock', 'status')]
class Product extends Model
{
    protected function casts(): array
    {
        return [
            'status' => ProductStatus::class,
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function sizes(): BelongsToMany
    {
        return $this->belongsToMany(Size::class, 'product_size', 'product_id', 'size_id');
    }

    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(Color::class, 'color_product', 'product_id', 'color_id');
    }

    public function scopeActive($q)
    {
        return $q->where('status', ProductStatus::Active);
    }

    public function scopeSearchTitle($q, ?string $term)
    {
        if (!$term) return $q;
        return $q->where('title', 'ilike', "%{$term}%");
    }

    public function scopeInCategory($q, ?int $id)
    {
        if (!$id) return $q;
        return $q->where('category_id', $id);
    }

    public function scopeHasSize($q, ?int $id)
    {
        if (!$id) return $q;
        return $q->whereHas('sizes', fn($s) => $s->where('sizes.id', $id));
    }

    public function scopeHasColor($q, ?int $id)
    {
        if (!$id) return $q;
        return $q->whereHas('colors', fn($c) => $c->where('colors.id', $id));
    }
}
