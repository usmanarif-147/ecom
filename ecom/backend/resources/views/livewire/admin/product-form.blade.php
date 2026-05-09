<div class="max-w-3xl">
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-900">{{ $isEdit ? 'Edit Product' : 'New Product' }}</h2>
        <p class="text-sm text-gray-600 mt-1">
            {{ $isEdit ? 'Update product details below.' : 'Create a new product in your catalog.' }}
        </p>
    </div>

    <form wire:submit="save" class="bg-white border border-gray-200 rounded-lg p-6 space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" wire:model="name" required
                class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Category</label>
                <select wire:model="category"
                    class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select wire:model="status"
                    class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
                    <option value="active">Active</option>
                    <option value="draft">Draft</option>
                    <option value="archived">Archived</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Price (USD)</label>
                <input type="number" step="0.01" wire:model="price" required
                    class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Stock</label>
                <input type="number" wire:model="stock" required
                    class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Image URL</label>
            <input type="url" wire:model="image" placeholder="https://…"
                class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea wire:model="description" rows="4"
                class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900"></textarea>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-2 border-t border-gray-200">
            <a href="{{ route('admin.products.index') }}"
                class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 border border-gray-300 rounded-md hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit"
                class="px-4 py-2 text-sm font-medium bg-gray-900 text-white rounded-md hover:bg-gray-800">
                {{ $isEdit ? 'Save Changes' : 'Create Product' }}
            </button>
        </div>
    </form>
</div>
