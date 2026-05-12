@use(App\Enums\ProductStatus)
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900">Products</h2>
            <p class="text-sm text-gray-600 mt-1">Manage your store's catalog.</p>
        </div>
        <a href="{{ route('admin.products.create') }}"
            class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-md hover:bg-gray-800">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Product
        </a>
    </div>

    @if (session()->has('message'))
        <p class="text-sm text-green-700">{{ session('message') }}</p>
    @endif

    <div class="flex flex-col sm:flex-row gap-3">
        <input type="text" wire:model.live.debounce.250ms="search" placeholder="Search by name…"
            class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
        <select wire:model.live="categoryId"
            class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
            <option value="">All categories</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->title }}</option>
            @endforeach
        </select>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-4 py-3 font-medium">Product</th>
                    <th class="text-left px-4 py-3 font-medium">Category</th>
                    <th class="text-right px-4 py-3 font-medium">Price</th>
                    <th class="text-right px-4 py-3 font-medium">Stock</th>
                    <th class="text-left px-4 py-3 font-medium">Status</th>
                    <th class="text-right px-4 py-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                @if ($product->images->isEmpty())
                                    <div class="w-10 h-10 rounded-md bg-gray-200"></div>
                                @else
                                    <img src="{{ $product->images->first()->url }}" alt="{{ $product->title }}"
                                        class="w-10 h-10 rounded-md object-cover" />
                                @endif
                                <span class="ml-3 font-medium text-gray-900">{{ $product->title }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-700">{{ $product->category?->title }}</td>
                        <td class="px-4 py-3 text-right text-gray-900">${{ number_format($product->price, 2) }}</td>
                        <td class="px-4 py-3 text-right text-gray-700">{{ $product->stock }}</td>
                        <td class="px-4 py-3">
                            @if ($product->status === ProductStatus::Active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-700">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <a href="{{ route('admin.products.edit', $product->id) }}"
                                class="text-gray-700 hover:text-gray-900 font-medium">Edit</a>
                            <button type="button" wire:click="delete({{ $product->id }})"
                                wire:confirm="Delete this product?"
                                class="text-rose-600 hover:text-rose-700 font-medium">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-gray-500">No products match your filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $products->links() }}
    </div>
</div>
