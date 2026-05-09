<script setup>
import { cartItems } from '~/data/products.js'

const cartCount = cartItems.reduce((sum, item) => sum + item.quantity, 0)
</script>

<template>
  <header class="sticky top-0 z-40 bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <NuxtLink to="/" class="text-xl font-bold tracking-tight text-gray-900">
          MYSHOP
        </NuxtLink>

        <nav class="hidden md:flex space-x-8">
          <NuxtLink to="/" class="text-sm font-medium text-gray-700 hover:text-gray-900">
            Shop
          </NuxtLink>
          <a href="#" class="text-sm font-medium text-gray-700 hover:text-gray-900">New Arrivals</a>
          <a href="#" class="text-sm font-medium text-gray-700 hover:text-gray-900">Sale</a>
        </nav>

        <div class="flex items-center space-x-4">
          <a href="#" class="text-sm font-medium text-gray-700 hover:text-gray-900">
            Login
          </a>
          <NuxtLink to="/cart" class="relative inline-flex items-center text-gray-700 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span v-if="cartCount > 0"
              class="absolute -top-2 -right-2 bg-gray-900 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
              {{ cartCount }}
            </span>
          </NuxtLink>
        </div>
      </div>
    </div>
  </header>
</template>
