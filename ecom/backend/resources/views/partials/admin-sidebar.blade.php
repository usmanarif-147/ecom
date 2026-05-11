@php
    $links = [
        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        ['route' => 'admin.categories.index', 'label' => 'Categories', 'icon' => 'M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a4 4 0 014-4z'],
        ['route' => 'admin.colors.index', 'label' => 'Colors', 'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 15l-7-7m0 0l-3.586 3.586a2 2 0 000 2.828L13 18a2 2 0 002.828 0L19 14.828a2 2 0 000-2.828L13.414 6.414'],
        ['route' => 'admin.sizes.index', 'label' => 'Sizes', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
        ['route' => 'admin.products.index', 'label' => 'Products', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
        ['route' => 'admin.orders.index', 'label' => 'Orders', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
    ];
@endphp

<aside class="hidden md:flex md:flex-col md:w-60 bg-gray-900 text-gray-100 h-screen sticky top-0">
    <div class="h-16 flex items-center px-6 border-b border-gray-800">
        <span class="text-lg font-bold tracking-tight text-white">MYSHOP</span>
        <span class="ml-2 text-xs text-gray-400">/ Admin</span>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-1">
        @foreach ($links as $link)
            <a href="{{ route($link['route']) }}"
                class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors
                    {{ request()->routeIs($link['route']) || request()->routeIs(str_replace('.index', '.*', $link['route']))
                        ? 'bg-white text-gray-900'
                        : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}" />
                </svg>
                {{ $link['label'] }}
            </a>
        @endforeach
    </nav>

    <div class="px-3 py-4 border-t border-gray-800">
        @livewire('admin.logout', ['variant' => 'sidebar'])
    </div>
</aside>
