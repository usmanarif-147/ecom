<script setup>
import { useRoute } from 'vue-router'
import { customer } from '../data/customer.js'

const route = useRoute()
</script>

<template>
  <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 md:px-8 sticky top-0 z-30">
    <h1 class="text-lg font-semibold text-gray-900">{{ route.meta.title || 'My Account' }}</h1>

    <div class="flex items-center space-x-4">
      <button class="p-2 text-gray-500 hover:text-gray-900 rounded-full hover:bg-gray-100">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
      </button>

      <div class="flex items-center space-x-3">
        <div class="w-9 h-9 rounded-full bg-gray-900 text-white flex items-center justify-center text-sm font-semibold">
          {{ customer.name.charAt(0) }}
        </div>
        <div class="hidden sm:block leading-tight">
          <div class="text-sm font-medium text-gray-900">{{ customer.name }}</div>
          <div class="text-xs text-gray-500">{{ customer.email }}</div>
        </div>
      </div>
    </div>
  </header>
</template>
