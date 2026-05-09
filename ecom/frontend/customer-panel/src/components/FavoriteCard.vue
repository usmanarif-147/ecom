<script setup>
defineProps({
  product: { type: Object, required: true }
})
</script>

<template>
  <div class="group relative bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
    <button class="absolute top-2 right-2 z-10 p-2 rounded-full bg-white/90 backdrop-blur hover:bg-white">
      <svg class="w-5 h-5 text-rose-500" fill="currentColor" viewBox="0 0 24 24">
        <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
      </svg>
    </button>

    <div class="aspect-square bg-gray-100 overflow-hidden">
      <img :src="product.image" :alt="product.name"
        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
    </div>

    <div class="p-4">
      <p class="text-xs text-gray-500">{{ product.category }}</p>
      <h3 class="text-sm font-medium text-gray-900 mt-1 line-clamp-1">{{ product.name }}</h3>
      <div class="flex items-center justify-between mt-3">
        <p class="text-sm font-semibold text-gray-900">${{ product.price.toFixed(2) }}</p>
        <button
          class="px-3 py-1.5 bg-gray-900 text-white text-xs font-medium rounded-md hover:bg-gray-800">
          Add to Cart
        </button>
      </div>
    </div>
  </div>
</template>
