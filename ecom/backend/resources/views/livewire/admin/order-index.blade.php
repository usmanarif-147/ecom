@php
    $statusStyles = [
        'pending'   => 'bg-amber-100 text-amber-800',
        'shipped'   => 'bg-blue-100 text-blue-800',
        'delivered' => 'bg-green-100 text-green-800',
        'cancelled' => 'bg-gray-200 text-gray-700',
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
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $order['id'] }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $order['customer'] }}</td>
                        <td class="px-4 py-3 text-gray-700">
                            {{ \Carbon\Carbon::parse($order['date'])->format('M j, Y') }}
                        </td>
                        <td class="px-4 py-3 text-right text-gray-700">{{ $order['items'] }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-gray-900">
                            ${{ number_format($order['total'], 2) }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusStyles[$order['status']] }}">
                                {{ ucfirst($order['status']) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button class="text-gray-700 hover:text-gray-900 font-medium">View</button>
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
</div>
