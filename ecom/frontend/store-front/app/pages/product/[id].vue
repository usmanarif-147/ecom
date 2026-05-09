<script setup>
import { ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import { products } from '~/data/products.js'

const route = useRoute()
const product = computed(() => products.find(p => p.id === Number(route.params.id)))

const selectedSize = ref(null)
const selectedColor = ref(null)
const quantity = ref(1)
</script>

<template>
  <div v-if="product" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <nav class="text-sm text-gray-500 mb-6">
      <NuxtLink to="/" class="hover:text-gray-900">Shop</NuxtLink>
      <span class="mx-2">/</span>
      <span class="text-gray-900">{{ product.category }}</span>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
      <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
        <img :src="product.image" :alt="product.name" class="w-full h-full object-cover" />
      </div>

      <div>
        <p class="text-sm text-gray-500">{{ product.category }}</p>
        <h1 class="text-3xl font-bold text-gray-900 mt-1">{{ product.name }}</h1>
        <p class="text-2xl font-semibold text-gray-900 mt-4">${{ product.price.toFixed(2) }}</p>

        <p class="text-sm text-gray-700 mt-6 leading-relaxed">{{ product.description }}</p>

        <div class="mt-8">
          <h3 class="text-sm font-semibold text-gray-900 mb-2">Size</h3>
          <div class="flex flex-wrap gap-2">
            <button v-for="size in product.sizes" :key="size" @click="selectedSize = size" :class="[
              'px-4 py-2 text-sm border rounded-md transition',
              selectedSize === size
                ? 'border-gray-900 bg-gray-900 text-white'
                : 'border-gray-300 text-gray-700 hover:border-gray-900'
            ]">
              {{ size }}
            </button>
          </div>
        </div>

        <div class="mt-6">
          <h3 class="text-sm font-semibold text-gray-900 mb-2">Color</h3>
          <div class="flex flex-wrap gap-2">
            <button v-for="color in product.colors" :key="color" @click="selectedColor = color" :class="[
              'px-4 py-2 text-sm border rounded-md transition',
              selectedColor === color
                ? 'border-gray-900 bg-gray-900 text-white'
                : 'border-gray-300 text-gray-700 hover:border-gray-900'
            ]">
              {{ color }}
            </button>
          </div>
        </div>

        <div class="mt-6">
          <h3 class="text-sm font-semibold text-gray-900 mb-2">Quantity</h3>
          <div class="inline-flex items-center border border-gray-300 rounded-md">
            <button @click="quantity = Math.max(1, quantity - 1)"
              class="px-3 py-2 text-gray-600 hover:text-gray-900">−</button>
            <span class="px-4 text-sm font-medium">{{ quantity }}</span>
            <button @click="quantity++" class="px-3 py-2 text-gray-600 hover:text-gray-900">+</button>
          </div>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row gap-3">
          <NuxtLink to="/cart"
            class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gray-900 text-white text-sm font-medium rounded-md hover:bg-gray-800">
            Add to Cart
          </NuxtLink>
          <button
            class="flex-1 inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50">
            Save for Later
          </button>
        </div>

        <p class="mt-6 text-xs text-gray-500">
          {{ product.stock }} in stock · Free shipping on orders over $75
        </p>
      </div>
    </div>
  </div>

  <div v-else class="max-w-7xl mx-auto px-4 py-20 text-center">
    <p class="text-gray-500">Product not found.</p>
    <NuxtLink to="/" class="text-sm text-gray-900 underline mt-4 inline-block">Back to shop</NuxtLink>
  </div>
</template>
