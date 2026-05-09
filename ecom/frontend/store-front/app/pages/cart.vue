<script setup>
import { computed } from 'vue'
import { products, cartItems } from '~/data/products.js'

const lines = computed(() =>
  cartItems.map(item => {
    const p = products.find(pr => pr.id === item.productId)
    return {
      ...item,
      product: p,
      lineTotal: p.price * item.quantity
    }
  })
)

const subtotal = computed(() => lines.value.reduce((s, l) => s + l.lineTotal, 0))
const shipping = computed(() => (subtotal.value > 75 ? 0 : 7.99))
const total = computed(() => subtotal.value + shipping.value)
</script>

<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

    <div v-if="lines.length" class="lg:grid lg:grid-cols-3 lg:gap-8">
      <ul class="lg:col-span-2 divide-y divide-gray-200 border-t border-b border-gray-200">
        <li v-for="line in lines" :key="line.productId" class="flex py-6">
          <div class="w-24 h-24 bg-gray-100 rounded-md overflow-hidden flex-shrink-0">
            <img :src="line.product.image" :alt="line.product.name" class="w-full h-full object-cover" />
          </div>

          <div class="ml-4 flex-1 flex flex-col">
            <div class="flex justify-between">
              <div>
                <h3 class="text-sm font-medium text-gray-900">
                  <NuxtLink :to="`/product/${line.product.id}`" class="hover:underline">
                    {{ line.product.name }}
                  </NuxtLink>
                </h3>
                <p class="mt-1 text-xs text-gray-500">
                  {{ line.size }} · {{ line.color }}
                </p>
              </div>
              <p class="text-sm font-semibold text-gray-900">${{ line.lineTotal.toFixed(2) }}</p>
            </div>

            <div class="mt-auto flex items-center justify-between">
              <div class="inline-flex items-center border border-gray-300 rounded-md">
                <button class="px-3 py-1 text-gray-600 hover:text-gray-900">−</button>
                <span class="px-3 text-sm font-medium">{{ line.quantity }}</span>
                <button class="px-3 py-1 text-gray-600 hover:text-gray-900">+</button>
              </div>
              <button class="text-sm text-gray-500 hover:text-gray-900">Remove</button>
            </div>
          </div>
        </li>
      </ul>

      <aside class="mt-10 lg:mt-0">
        <div class="bg-gray-50 rounded-lg p-6">
          <h2 class="text-lg font-semibold text-gray-900">Order Summary</h2>
          <dl class="mt-6 space-y-3 text-sm">
            <div class="flex justify-between">
              <dt class="text-gray-600">Subtotal</dt>
              <dd class="font-medium text-gray-900">${{ subtotal.toFixed(2) }}</dd>
            </div>
            <div class="flex justify-between">
              <dt class="text-gray-600">Shipping</dt>
              <dd class="font-medium text-gray-900">
                {{ shipping === 0 ? 'Free' : `$${shipping.toFixed(2)}` }}
              </dd>
            </div>
            <div class="border-t border-gray-200 pt-3 flex justify-between">
              <dt class="font-semibold text-gray-900">Total</dt>
              <dd class="font-semibold text-gray-900">${{ total.toFixed(2) }}</dd>
            </div>
          </dl>

          <NuxtLink to="/checkout"
            class="mt-6 w-full inline-flex justify-center items-center px-6 py-3 bg-gray-900 text-white text-sm font-medium rounded-md hover:bg-gray-800">
            Proceed to Checkout
          </NuxtLink>
          <NuxtLink to="/" class="mt-3 block text-center text-sm text-gray-600 hover:text-gray-900">
            Continue shopping
          </NuxtLink>
        </div>
      </aside>
    </div>

    <div v-else class="text-center py-20">
      <p class="text-gray-500">Your cart is empty.</p>
      <NuxtLink to="/" class="mt-4 inline-block text-sm text-gray-900 underline">
        Browse products
      </NuxtLink>
    </div>
  </div>
</template>
