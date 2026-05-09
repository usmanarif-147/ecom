<script setup>
const links = [
  { to: '/dashboard', label: 'Dashboard', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
  { to: '/orders', label: 'Orders', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4' },
  { to: '/favorites', label: 'Favorites', icon: 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z' }
]
</script>

<template>
  <aside class="hidden md:flex md:flex-col md:w-60 bg-white border-r border-gray-200 h-screen sticky top-0">
    <div class="h-16 flex items-center px-6 border-b border-gray-200">
      <span class="text-lg font-bold tracking-tight text-gray-900">MYSHOP</span>
      <span class="ml-2 text-xs text-gray-500">/ Account</span>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-1">
      <RouterLink v-for="link in links" :key="link.to" :to="link.to"
        class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors"
        active-class="bg-gray-900 text-white"
        :class="$route.path !== link.to ? 'text-gray-700 hover:bg-gray-100' : ''">
        <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" :d="link.icon" />
        </svg>
        {{ link.label }}
      </RouterLink>
    </nav>

    <div class="px-3 py-4 border-t border-gray-200">
      <a href="#" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-md">
        <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        Sign out
      </a>
    </div>
  </aside>
</template>
