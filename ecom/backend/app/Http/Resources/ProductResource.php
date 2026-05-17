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
            'category' => $this->category ? ['id' => $this->category->id, 'title' => $this->category->title] : null,
            'sizes'    => $this->sizes->map(fn($s) => ['id' => $s->id, 'title' => $s->title])->values(),
            'colors'   => $this->colors->map(fn($c) => ['id' => $c->id, 'title' => $c->title, 'code' => $c->code])->values(),
        ];
    }
}
