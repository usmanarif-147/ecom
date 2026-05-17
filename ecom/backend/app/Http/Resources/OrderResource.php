<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'order_number'          => $this->order_number,
            'customer_name'         => $this->customer_name,
            'customer_email'        => $this->customer_email,
            'customer_phone_number' => $this->customer_phone_number,
            'customer_address'      => $this->customer_address,
            'payment_method'        => $this->payment_method,
            'number_of_items'       => $this->number_of_items,
            'total_amount'          => $this->total_amount,
            'status'                => $this->status->name,
            'items'                 => $this->whenLoaded('items', fn() =>
                $this->items->map(fn($item) => [
                    'product'      => $item->product,
                    'quantity'     => $item->quantity,
                    'total_amount' => $item->total_amount,
                ])
            ),
            'created_at'            => $this->created_at,
        ];
    }
}
