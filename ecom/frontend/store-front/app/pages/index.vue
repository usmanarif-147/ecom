<script setup>
import { ref, computed } from 'vue'
import { products } from '~/data/products.js'

const filters = ref({
  category: '',
  maxPrice: 250,
  sort: 'featured'
})

const filtered = computed(() => {
  let list = products.filter(p => {
    if (filters.value.category && p.category !== filters.value.category) return false
    if (p.price > filters.value.maxPrice) return false
    return true
  })

  if (filters.value.sort === 'price-asc') list = [...list].sort((a, b) => a.price - b.price)
  else if (filters.value.sort === 'price-desc') list = [...list].sort((a, b) => b.price - a.price)
  else if (filters.value.sort === 'name') list = [...list].sort((a, b) => a.name.localeCompare(b.name))

  return list
})
</script>

<template>
  <div>
    <section class="bg-gray-900 text-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
        <h1 class="text-4xl md:text-5xl font-bold tracking-tight">Spring Collection</h1>
        <p class="mt-4 text-lg text-gray-300 max-w-2xl">
          Fresh styles, refined fits. Discover pieces designed for everyday wear.
        </p>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="lg:grid lg:grid-cols-4 lg:gap-8">
        <ProductFilters v-model="filters" class="mb-8 lg:mb-0" />

        <div class="lg:col-span-3">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-semibold text-gray-900">All Products</h2>
            <span class="text-sm text-gray-500">{{ filtered.length }} items</span>
          </div>

          <div v-if="filtered.length" class="grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-10">
            <ProductCard v-for="product in filtered" :key="product.id" :product="product" />
          </div>

          <div v-else class="text-center py-16">
            <p class="text-gray-500">No products match your filters.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
