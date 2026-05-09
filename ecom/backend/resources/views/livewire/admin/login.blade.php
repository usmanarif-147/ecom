<div class="w-full max-w-md">
    <div class="bg-white border border-gray-200 rounded-lg p-8 shadow-lg">
        <h1 class="text-2xl font-semibold text-gray-900">Admin sign in</h1>
        <p class="text-sm text-gray-600 mt-1">Access your dashboard.</p>

        <form wire:submit="submit" class="mt-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" wire:model="email" placeholder="admin@myshop.com"
                    class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <a href="#" class="text-xs text-gray-600 hover:text-gray-900">Forgot password?</a>
                </div>
                <input type="password" wire:model="password" placeholder="••••••••"
                    class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
            </div>

            <label class="flex items-center text-sm text-gray-700">
                <input type="checkbox" wire:model="remember"
                    class="h-4 w-4 text-gray-900 border-gray-300 rounded focus:ring-gray-900" />
                <span class="ml-2">Remember me on this device</span>
            </label>

            <button type="submit"
                class="w-full px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-md hover:bg-gray-800">
                Sign in
            </button>
        </form>
    </div>

    <p class="mt-6 text-center text-xs text-gray-400">
        Restricted area · admin access only
    </p>
</div>
