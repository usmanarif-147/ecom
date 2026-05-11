<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900">Sizes</h2>
            <p class="text-sm text-gray-600 mt-1">Manage the size labels customers can choose from (e.g. S, M, L, 32).</p>
        </div>
    </div>

    @if (session()->has('message'))
        <p class="text-sm text-green-700">{{ session('message') }}</p>
    @endif

    <form wire:submit="save" class="flex gap-3">
        <input type="text" wire:model="title" placeholder="New size (e.g. M or 32)"
            class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
        <button type="submit"
            class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-md hover:bg-gray-800">
            Add Size
        </button>
    </form>
    @error('title')
        <p class="text-rose-600 text-sm">{{ $message }}</p>
    @enderror

    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-4 py-3 font-medium">Title</th>
                    <th class="text-right px-4 py-3 font-medium">Products</th>
                    <th class="text-right px-4 py-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($sizes as $size)
                    <tr class="hover:bg-gray-50">
                        @if ($editingId === $size->id)
                            <td class="px-4 py-3" colspan="2">
                                <input type="text" wire:model="editingTitle"
                                    wire:keydown.enter="update"
                                    wire:keydown.escape="cancelEdit"
                                    class="w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
                                @error('editingTitle')
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
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $size->title }}</td>
                            <td class="px-4 py-3 text-right text-gray-700">{{ $size->products_count }}</td>
                            <td class="px-4 py-3 text-right space-x-3">
                                <button type="button" wire:click="startEdit({{ $size->id }})"
                                    class="text-gray-700 hover:text-gray-900 font-medium">
                                    Edit
                                </button>
                                <button type="button" wire:click="delete({{ $size->id }})"
                                    wire:confirm="Delete this size?"
                                    class="text-rose-600 hover:text-rose-700 font-medium">
                                    Delete
                                </button>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-10 text-center text-gray-500">No sizes yet. Add one above.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
