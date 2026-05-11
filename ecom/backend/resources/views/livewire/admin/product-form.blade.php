<div class="max-w-3xl">
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-900">{{ $isEdit ? 'Edit Product' : 'New Product' }}</h2>
        <p class="text-sm text-gray-600 mt-1">
            {{ $isEdit ? 'Update product details below.' : 'Create a new product in your catalog.' }}
        </p>
    </div>

    <form wire:submit="save" enctype="multipart/form-data" class="bg-white border border-gray-200 rounded-lg p-6 space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" wire:model="title" required
                class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
            @error('title') <p class="text-rose-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Category</label>
                <select wire:model="category_id"
                    class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
                    <option value="">— Select —</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-rose-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select wire:model="status"
                    class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
                @error('status') <p class="text-rose-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Price (USD)</label>
                <input type="number" step="0.01" wire:model="price" required
                    class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
                @error('price') <p class="text-rose-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Cost (USD)</label>
                <input type="number" step="0.01" wire:model="cost" required
                    class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
                @error('cost') <p class="text-rose-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Stock</label>
                <input type="number" step="1" wire:model="stock" required
                    class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
                @error('stock') <p class="text-rose-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea wire:model="description" rows="4"
                class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900"></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Sizes</label>
            <div class="flex flex-wrap gap-3">
                @foreach ($sizes as $size)
                    <label class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm cursor-pointer hover:bg-gray-50">
                        <input type="checkbox" wire:model="sizeIds" value="{{ $size->id }}" class="mr-2"> {{ $size->title }}
                    </label>
                @endforeach
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Colors</label>
            <div class="flex flex-wrap gap-3">
                @foreach ($colors as $color)
                    <label class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm cursor-pointer hover:bg-gray-50">
                        <input type="checkbox" wire:model="colorIds" value="{{ $color->id }}" class="mr-2">
                        <span class="inline-block w-4 h-4 rounded-full border border-gray-200 mr-2" style="background-color: {{ $color->code }}"></span>
                        {{ $color->title }}
                    </label>
                @endforeach
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Images</label>
            @if ($isEdit && $images->isNotEmpty())
                <div class="mb-3">
                    <p class="text-xs text-gray-600 mb-2">Current images</p>
                    <div class="flex flex-wrap gap-3">
                        @foreach ($images as $img)
                            <div class="relative w-24 h-24 rounded-md overflow-hidden border border-gray-200">
                                <img src="{{ $img->url }}" class="w-full h-full object-cover" />
                                <button type="button" wire:click="removeExistingImage({{ $img->id }})"
                                    class="absolute top-1 right-1 w-5 h-5 bg-white/90 rounded-full text-xs text-rose-600 hover:bg-white border border-gray-200">×</button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            <input type="file" wire:model="newImages" multiple accept="image/*"
                class="block w-full text-sm" />
            @error('newImages.*') <p class="text-rose-600 text-sm mt-1">{{ $message }}</p> @enderror
            @if ($newImages)
                <div class="mt-3 flex flex-wrap gap-3">
                    @foreach ($newImages as $tmp)
                        <img src="{{ $tmp->temporaryUrl() }}" class="w-24 h-24 rounded-md object-cover border border-gray-200" />
                    @endforeach
                </div>
            @endif
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
