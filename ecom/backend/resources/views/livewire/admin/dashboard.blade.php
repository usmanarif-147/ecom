@php
    $iconPaths = [
        'dollar' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'cart'   => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z',
        'box'    => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
        'users'  => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
    ];
    $statusStyles = [
        'pending'   => 'bg-amber-100 text-amber-800',
        'shipped'   => 'bg-blue-100 text-blue-800',
        'delivered' => 'bg-green-100 text-green-800',
        'cancelled' => 'bg-gray-200 text-gray-700',
    ];
@endphp

<div class="space-y-8">
    <section>
        <h2 class="text-2xl font-semibold text-gray-900">Welcome, Admin</h2>
        <p class="text-sm text-gray-600 mt-1">Here's what's happening with your store today.</p>
    </section>

    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($stats as $stat)
            <div class="bg-white border border-gray-200 rounded-lg p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">{{ $stat['label'] }}</p>
                        <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $stat['value'] }}</p>
                        <p class="text-xs mt-1 {{ $stat['positive'] ? 'text-green-600' : 'text-rose-600' }}">
                            {{ $stat['change'] }} <span class="text-gray-400">vs last month</span>
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-md bg-gray-100 text-gray-700 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPaths[$stat['icon']] }}" />
                        </svg>
                    </div>
                </div>
            </div>
        @endforeach
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <section class="lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-700 hover:text-gray-900 font-medium">View all →</a>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wide">
                        <tr>
                            <th class="text-left px-4 py-3 font-medium">Order</th>
                            <th class="text-left px-4 py-3 font-medium">Customer</th>
                            <th class="text-left px-4 py-3 font-medium">Status</th>
                            <th class="text-right px-4 py-3 font-medium">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($recentOrders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $order['id'] }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $order['customer'] }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusStyles[$order['status']] }}">
                                        {{ ucfirst($order['status']) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-900">${{ number_format($order['total'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Top Products</h3>
                <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-700 hover:text-gray-900 font-medium">View all →</a>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg divide-y divide-gray-200">
                @foreach ($topProducts as $product)
                    <div class="flex items-center px-4 py-3">
                        <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-10 h-10 rounded-md object-cover" />
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $product['name'] }}</p>
                            <p class="text-xs text-gray-500">{{ $product['category'] }}</p>
                        </div>
                        <p class="text-sm font-semibold text-gray-900">${{ number_format($product['price'], 2) }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</div>
