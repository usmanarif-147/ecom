@php
    $statusStyles = [
        'Pending'   => 'bg-amber-100 text-amber-800',
        'Shipped'   => 'bg-blue-100 text-blue-800',
        'Delivered' => 'bg-green-100 text-green-800',
        'Cancelled' => 'bg-gray-200 text-gray-700',
    ];
    $filters = [
        ['key' => 'all',       'label' => 'All'],
        ['key' => 'pending',   'label' => 'Pending'],
        ['key' => 'shipped',   'label' => 'Shipped'],
        ['key' => 'delivered', 'label' => 'Delivered'],
        ['key' => 'cancelled', 'label' => 'Cancelled'],
    ];
@endphp

<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-semibold text-gray-900">Orders</h2>
        <p class="text-sm text-gray-600 mt-1">All orders placed in your store.</p>
    </div>

    <div class="flex flex-wrap gap-2">
        @foreach ($filters as $filter)
            <button wire:click="setFilter('{{ $filter['key'] }}')"
                @class([
                    'px-3 py-1.5 text-sm rounded-full border transition',
                    'bg-gray-900 text-white border-gray-900' => $statusFilter === $filter['key'],
                    'bg-white text-gray-700 border-gray-300 hover:border-gray-900' => $statusFilter !== $filter['key'],
                ])>
                {{ $filter['label'] }}
            </button>
        @endforeach
    </div>

    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-4 py-3 font-medium">Order</th>
                    <th class="text-left px-4 py-3 font-medium">Customer</th>
                    <th class="text-left px-4 py-3 font-medium">Date</th>
                    <th class="text-right px-4 py-3 font-medium">Items</th>
                    <th class="text-right px-4 py-3 font-medium">Total</th>
                    <th class="text-left px-4 py-3 font-medium">Status</th>
                    <th class="text-right px-4 py-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $order->order_number }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $order->customer_name }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-right text-gray-700">{{ $order->number_of_items }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-gray-900">
                            ${{ number_format($order->total_amount, 2) }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusStyles[$order->status->name] }}">
                                {{ $order->status->name }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button wire:click="viewOrder({{ $order->id }})"
                                class="text-gray-700 hover:text-gray-900 font-medium">View</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-gray-500">
                            No orders match this filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $orders->links() }}
    </div>

    @if ($showOrderModal && $selectedOrder)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <h3 class="text-lg font-semibold text-gray-900">Order {{ $selectedOrder->order_number }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusStyles[$selectedOrder->status->name] }}">
                            {{ $selectedOrder->status->name }}
                        </span>
                    </div>
                    <button wire:click="closeOrderModal"
                        class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Customer</p>
                            <p class="text-sm font-medium text-gray-900">{{ $selectedOrder->customer_name }}</p>
                            <p class="text-sm text-gray-600">{{ $selectedOrder->customer_email }}</p>
                            <p class="text-sm text-gray-600">{{ $selectedOrder->customer_phone_number }}</p>
                            <p class="text-sm text-gray-600">{{ $selectedOrder->customer_address }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Payment</p>
                            <p class="text-sm text-gray-900">
                                {{ $selectedOrder->payment_method === 'cod' ? 'Cash on Delivery' : $selectedOrder->payment_method }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-3">Items</p>
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wide">
                                    <tr>
                                        <th class="text-left px-4 py-3 font-medium">Product</th>
                                        <th class="text-left px-4 py-3 font-medium">Details</th>
                                        <th class="text-right px-4 py-3 font-medium">Unit Price</th>
                                        <th class="text-right px-4 py-3 font-medium">Qty</th>
                                        <th class="text-right px-4 py-3 font-medium">Line Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($selectedOrder->items as $item)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-3">
                                                    @if (!empty($item->product['image_url']))
                                                        <img src="{{ $item->product['image_url'] }}"
                                                            alt="{{ $item->product['title'] }}"
                                                            class="w-10 h-10 rounded-md object-cover flex-shrink-0" />
                                                    @else
                                                        <div class="w-10 h-10 rounded-md bg-gray-100 flex-shrink-0"></div>
                                                    @endif
                                                    <span class="font-medium text-gray-900">{{ $item->product['title'] }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-gray-600">
                                                <p>{{ $item->product['category_title'] }}</p>
                                                <p>Size: {{ $item->product['size_title'] }}</p>
                                                <div class="flex items-center gap-1.5 mt-0.5">
                                                    <span class="inline-block w-3 h-3 rounded-full border border-gray-300"
                                                        style="background-color: {{ $item->product['color']['code'] }}"></span>
                                                    <span>{{ $item->product['color']['title'] }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-right text-gray-700">
                                                ${{ number_format($item->product['price'], 2) }}
                                            </td>
                                            <td class="px-4 py-3 text-right text-gray-700">{{ $item->quantity }}</td>
                                            <td class="px-4 py-3 text-right font-semibold text-gray-900">
                                                ${{ number_format($item->total_amount, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <div class="space-y-1 text-sm text-right">
                            <div class="flex justify-between gap-12">
                                <span class="text-gray-500">Items</span>
                                <span class="text-gray-900">{{ $selectedOrder->number_of_items }}</span>
                            </div>
                            <div class="flex justify-between gap-12">
                                <span class="text-gray-500">Total (customer paid)</span>
                                <span class="font-semibold text-gray-900">${{ number_format($selectedOrder->total_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between gap-12">
                                <span class="text-gray-500">Cost (your cost)</span>
                                <span class="font-semibold text-gray-900">${{ number_format($selectedOrder->total_cost, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                    <button wire:click="closeOrderModal"
                        class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
