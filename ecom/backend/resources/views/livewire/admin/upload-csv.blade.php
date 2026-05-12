<div>
    <form wire:submit="import" enctype="multipart/form-data"
        class="bg-white border border-gray-200 rounded-lg p-6 space-y-5">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Csv</label>
            <input type="file" wire:model="csv" accept=".csv" class="block w-full text-sm" />
            @error('csv')
                <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-end space-x-3 pt-2 border-t border-gray-200">
            <a href="{{ route('admin.products.index') }}"
                class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 border border-gray-300 rounded-md hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit"
                class="px-4 py-2 text-sm font-medium bg-gray-900 text-white rounded-md hover:bg-gray-800">
                Upload Csv
            </button>
        </div>
    </form>
</div>
