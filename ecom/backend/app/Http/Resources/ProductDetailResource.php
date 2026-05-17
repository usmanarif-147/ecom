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
            'category'    => $this->category ? ['id' => $this->category->id, 'title' => $this->category->title] : null,
            'sizes'       => $this->sizes->map(fn($s) => ['id' => $s->id, 'title' => $s->title])->values(),
            'colors'      => $this->colors->map(fn($c) => ['id' => $c->id, 'title' => $c->title, 'code' => $c->code])->values(),
            'images'      => $this->images->map(fn($i) => $i->url)->values(),
        ];
    }
}
