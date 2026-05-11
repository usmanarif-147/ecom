<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900">Colors</h2>
            <p class="text-sm text-gray-600 mt-1">Manage the colors customers can choose from.</p>
        </div>
    </div>

    @if (session()->has('message'))
        <p class="text-sm text-green-700">{{ session('message') }}</p>
    @endif

    <form wire:submit="save" class="flex flex-col sm:flex-row gap-3">
        <input type="text" wire:model="title" placeholder="Color name (e.g. Navy)"
            class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
        <input type="color" wire:model="code" class="h-10 w-16 border border-gray-300 rounded-md cursor-pointer" />
        <button type="submit"
            class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-md hover:bg-gray-800">
            Add Color
        </button>
    </form>
    @error('title')
        <p class="text-rose-600 text-sm">{{ $message }}</p>
    @enderror
    @error('code')
        <p class="text-rose-600 text-sm">{{ $message }}</p>
    @enderror

    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-4 py-3 font-medium w-12">Swatch</th>
                    <th class="text-left px-4 py-3 font-medium">Title</th>
                    <th class="text-left px-4 py-3 font-medium">Code</th>
                    <th class="text-right px-4 py-3 font-medium">Products</th>
                    <th class="text-right px-4 py-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($colors as $color)
                    <tr class="hover:bg-gray-50">
                        @if ($editingId === $color->id)
                            <td class="px-4 py-3">
                                <span class="inline-block w-6 h-6 rounded-md border border-gray-200"
                                    style="background-color: {{ $editingCode }}"></span>
                            </td>
                            <td class="px-4 py-3" colspan="3">
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <input type="text" wire:model="editingTitle" wire:keydown.enter="update"
                                        wire:keydown.escape="cancelEdit"
                                        class="flex-1 border border-gray-300 rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
                                    <input type="color" wire:model="editingCode"
                                        class="h-9 w-14 border border-gray-300 rounded-md cursor-pointer" />
                                </div>
                                @error('editingTitle')
                                    <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                @error('editingCode')
                                    <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </td>
                            <td class="px-4 py-3 text-right space-x-3">
                                <button type="button" wire:click="update"
                                    class="text-gray-700 hover:text-gray-900 font-medium">
                                    Save
                                </button>
                                <button type="button" wire:click="cancelEdit"
                                    class="text-gray-500 hover:text-gray-700 font-medium">
                                    Cancel
                                </button>
                            </td>
                        @else
                            <td class="px-4 py-3">
                                <span class="inline-block w-6 h-6 rounded-md border border-gray-200"
                                    style="background-color: {{ $color->code }}"></span>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $color->title }}</td>
                            <td class="px-4 py-3"><code class="text-xs text-gray-700">{{ $color->code }}</code></td>
                            <td class="px-4 py-3 text-right text-gray-700">{{ $color->products_count }}</td>
                            <td class="px-4 py-3 text-right space-x-3">
                                <button type="button" wire:click="startEdit({{ $color->id }})"
                                    class="text-gray-700 hover:text-gray-900 font-medium">
                                    Edit
                                </button>
                                <button type="button" wire:click="delete({{ $color->id }})"
                                    wire:confirm="Delete this color?"
                                    class="text-rose-600 hover:text-rose-700 font-medium">
                                    Delete
                                </button>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-gray-500">No colors yet. Add one above.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
