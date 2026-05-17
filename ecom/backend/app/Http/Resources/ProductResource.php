<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        $firstImage = $this->images->first();
        return [
            'title'    => $this->title,
            'image'    => $firstImage ? $firstImage->url : null,
            'category' => $this->category?->title,
            'sizes'    => $this->sizes->pluck('title'),
            'colors'   => $this->colors->map(fn($c) => ['title' => $c->title, 'code' => $c->code]),
        ];
    }
}
