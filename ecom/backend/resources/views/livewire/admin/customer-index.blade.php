<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900">Customers</h2>
            <p class="text-sm text-gray-600 mt-1">Browse and manage your customer list.</p>
        </div>
    </div>

    @if (session()->has('message'))
        <p class="text-sm text-green-700">{{ session('message') }}</p>
    @endif

    <div class="flex flex-col sm:flex-row gap-3">
        <input type="text" wire:model.live.debounce.250ms="search" placeholder="Search name, email, or address…"
            class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
        <select wire:model.live="sortDir"
            class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
            <option value="desc">Orders ↓</option>
            <option value="asc">Orders ↑</option>
        </select>
    </div>

    @if (count($selected) > 0)
        <div class="flex items-center gap-4 px-4 py-3 bg-gray-100 rounded-md text-sm">
            <span class="font-medium text-gray-700">
                {{ count($selected) }} selected{{ $selectAll ? ' (all)' : '' }}
            </span>
            <button type="button" wire:click="deleteSelected"
                wire:confirm="Delete the selected customers? This cannot be undone."
                class="text-rose-600 hover:text-rose-700 font-medium">Delete selected</button>
            <button type="button" wire:click="openEmailModal"
                class="text-gray-700 hover:text-gray-900 font-medium">Send email</button>
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-4 py-3 text-left font-medium w-8">
                        <input type="checkbox" wire:model.live="selectAll"
                            class="rounded border-gray-300 text-gray-900 focus:ring-gray-900" />
                    </th>
                    <th class="text-left px-4 py-3 font-medium">Name</th>
                    <th class="text-left px-4 py-3 font-medium">Email</th>
                    <th class="text-left px-4 py-3 font-medium">Address</th>
                    <th class="text-right px-4 py-3 font-medium">Orders</th>
                    <th class="text-left px-4 py-3 font-medium">Payment Method</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($customers as $customer)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <input type="checkbox" wire:model.live="selected" value="{{ $customer['id'] }}"
                                class="rounded border-gray-300 text-gray-900 focus:ring-gray-900" />
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $customer['name'] }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $customer['email'] }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $customer['address'] }}</td>
                        <td class="px-4 py-3 text-right text-gray-900">{{ $customer['orders_count'] }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $customer['payment_method'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-gray-500">No customers match your filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $customers->links() }}
    </div>

    @if ($showEmailModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4 p-6 space-y-4">
                <h3 class="text-lg font-semibold text-gray-900">Send Bulk Email</h3>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                    <input type="text" wire:model.live="emailSubject"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
                    @error('emailSubject') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Body</label>
                    <textarea wire:model.live="emailBody" rows="6"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900"></textarea>
                    @error('emailBody') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" wire:click="closeEmailModal"
                        class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">Cancel</button>
                    <button type="button" wire:click="sendBulkEmail"
                        class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-md hover:bg-gray-800">Send</button>
                </div>
            </div>
        </div>
    @endif
</div>
