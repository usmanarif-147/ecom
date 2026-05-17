<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\ProductStatus;
use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function placeOrder(array $data): Order
    {
        $order = DB::transaction(function () use ($data) {
            $totalAmount = '0';
            $totalCost   = '0';
            $numberOfItems = 0;
            $itemRows = [];

            foreach ($data['items'] as $index => $item) {
                $product = Product::with(['category', 'images', 'sizes', 'colors'])
                    ->lockForUpdate()
                    ->find($item['product_id']);

                if (!$product || $product->status !== ProductStatus::Active) {
                    throw ValidationException::withMessages([
                        "items.{$index}.product_id" => ["Product is not available."],
                    ]);
                }

                if ($product->stock < $item['quantity']) {
                    throw ValidationException::withMessages([
                        "items.{$index}.quantity" => ["Only {$product->stock} left of {$product->title}."],
                    ]);
                }

                if (!$product->sizes->contains('id', $item['size_id'])) {
                    throw ValidationException::withMessages([
                        "items.{$index}.size_id" => ["Size is not offered for {$product->title}."],
                    ]);
                }

                if (!$product->colors->contains('id', $item['color_id'])) {
                    throw ValidationException::withMessages([
                        "items.{$index}.color_id" => ["Color is not offered for {$product->title}."],
                    ]);
                }

                $product->decrement('stock', $item['quantity']);

                $size  = $product->sizes->firstWhere('id', $item['size_id']);
                $color = $product->colors->firstWhere('id', $item['color_id']);

                $snapshot = [
                    'id'             => $product->id,
                    'title'          => $product->title,
                    'price'          => $product->price,
                    'cost'           => $product->cost,
                    'image_url'      => $product->images->first()?->url,
                    'category_title' => $product->category?->title,
                    'size_title'     => $size->title,
                    'color'          => [
                        'title' => $color->title,
                        'code'  => $color->code,
                    ],
                ];

                $lineAmount = bcmul((string) $product->price, (string) $item['quantity'], 2);
                $lineCost   = bcmul((string) $product->cost, (string) $item['quantity'], 2);
                $totalAmount = bcadd($totalAmount, $lineAmount, 2);
                $totalCost   = bcadd($totalCost, $lineCost, 2);
                $numberOfItems += $item['quantity'];

                $itemRows[] = [
                    'product_id'   => $product->id,
                    'product'      => $snapshot,
                    'quantity'     => $item['quantity'],
                    'total_amount' => $lineAmount,
                ];
            }

            $order = Order::create([
                'order_number'        => 'ORD-' . strtoupper(Str::random(12)),
                'customer_name'       => $data['customer']['name'],
                'customer_email'      => $data['customer']['email'],
                'customer_phone_number' => $data['customer']['phone_number'],
                'customer_address'    => $data['customer']['address'],
                'payment_method'      => $data['payment_method'],
                'number_of_items'     => $numberOfItems,
                'total_amount'        => $totalAmount,
                'total_cost'          => $totalCost,
                'status'              => OrderStatus::Pending,
            ]);

            foreach ($itemRows as $row) {
                $order->items()->create($row);
            }

            return $order;
        });

        Mail::to($order->customer_email)->queue(new OrderConfirmation($order));

        return $order->load('items');
    }
}
