<script setup>
defineProps({
  product: { type: Object, required: true }
})
</script>

<template>
  <NuxtLink :to="`/product/${product.id}`" class="group block">
    <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
      <img :src="product.image" :alt="product.name"
        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
    </div>
    <div class="mt-3 flex justify-between items-start">
      <div>
        <h3 class="text-sm font-medium text-gray-900">{{ product.name }}</h3>
        <p class="text-xs text-gray-500 mt-1">{{ product.category }}</p>
      </div>
      <p class="text-sm font-semibold text-gray-900">${{ product.price.toFixed(2) }}</p>
    </div>
  </NuxtLink>
</template>
