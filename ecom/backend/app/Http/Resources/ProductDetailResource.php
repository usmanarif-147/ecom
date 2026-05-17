<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'price'       => $this->price,
            'stock'       => $this->stock,
            'views'       => $this->views,
            'status'      => $this->status->name,
            'category'    => $this->category?->title,
            'sizes'       => $this->sizes->pluck('title'),
            'colors'      => $this->colors->map(fn($c) => ['title' => $c->title, 'code' => $c->code]),
            'images'      => $this->images->map(fn($i) => $i->url)->values(),
        ];
    }
}
