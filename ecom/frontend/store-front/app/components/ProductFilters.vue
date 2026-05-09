<script setup>
import { categories } from '~/data/products.js'

defineProps({
  modelValue: { type: Object, required: true }
})
defineEmits(['update:modelValue'])
</script>

<template>
  <aside class="space-y-6">
    <div>
      <h3 class="text-sm font-semibold text-gray-900 mb-3">Category</h3>
      <div class="space-y-2">
        <label class="flex items-center text-sm">
          <input type="radio" :checked="!modelValue.category" value=""
            @change="$emit('update:modelValue', { ...modelValue, category: '' })"
            class="h-4 w-4 text-gray-900 focus:ring-gray-900 border-gray-300" />
          <span class="ml-2 text-gray-700">All</span>
        </label>
        <label v-for="cat in categories" :key="cat" class="flex items-center text-sm">
          <input type="radio" :checked="modelValue.category === cat" :value="cat"
            @change="$emit('update:modelValue', { ...modelValue, category: cat })"
            class="h-4 w-4 text-gray-900 focus:ring-gray-900 border-gray-300" />
          <span class="ml-2 text-gray-700">{{ cat }}</span>
        </label>
      </div>
    </div>

    <div>
      <h3 class="text-sm font-semibold text-gray-900 mb-3">Max Price</h3>
      <input type="range" min="0" max="250" step="10" :value="modelValue.maxPrice"
        @input="$emit('update:modelValue', { ...modelValue, maxPrice: Number($event.target.value) })"
        class="w-full accent-gray-900" />
      <div class="flex justify-between text-xs text-gray-500 mt-1">
        <span>$0</span>
        <span class="font-semibold text-gray-900">${{ modelValue.maxPrice }}</span>
      </div>
    </div>

    <div>
      <h3 class="text-sm font-semibold text-gray-900 mb-3">Sort By</h3>
      <select :value="modelValue.sort"
        @change="$emit('update:modelValue', { ...modelValue, sort: $event.target.value })"
        class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-900">
        <option value="featured">Featured</option>
        <option value="price-asc">Price: Low to High</option>
        <option value="price-desc">Price: High to Low</option>
        <option value="name">Name (A–Z)</option>
      </select>
    </div>
  </aside>
</template>
