<script setup>
import { favorites } from '../data/customer.js'
import FavoriteCard from '../components/FavoriteCard.vue'
</script>

<template>
  <div class="space-y-6">
    <div class="flex items-end justify-between">
      <div>
        <h2 class="text-2xl font-semibold text-gray-900">Saved Items</h2>
        <p class="text-sm text-gray-600 mt-1">Products you've saved for later.</p>
      </div>
      <span class="text-sm text-gray-500">{{ favorites.length }} items</span>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
      <FavoriteCard v-for="product in favorites" :key="product.id" :product="product" />
    </div>
  </div>
</template>
