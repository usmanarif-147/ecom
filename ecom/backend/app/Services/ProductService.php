<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    public function paginatePublic(Request $request): LengthAwarePaginator
    {
        $query = Product::active()
            ->searchTitle($request->input('search'))
            ->inCategory($request->integer('category') ?: null)
            ->hasSize($request->integer('size') ?: null)
            ->hasColor($request->integer('color') ?: null)
            ->with([
                'category',
                'sizes',
                'colors',
                'images' => fn($q) => $q->orderBy('id', 'asc'),
            ]);

        match ($request->input('sort')) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default      => $query->latest(),
        };

        return $query->paginate(12)->withQueryString();
    }
}
