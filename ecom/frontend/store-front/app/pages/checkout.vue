<script setup>
import { computed } from 'vue'
import { products, cartItems } from '~/data/products.js'

const lines = computed(() =>
  cartItems.map(item => {
    const p = products.find(pr => pr.id === item.productId)
    return { ...item, product: p, lineTotal: p.price * item.quantity }
  })
)

const subtotal = computed(() => lines.value.reduce((s, l) => s + l.lineTotal, 0))
const shipping = computed(() => (subtotal.value > 75 ? 0 : 7.99))
const tax = computed(() => subtotal.value * 0.08)
const total = computed(() => subtotal.value + shipping.value + tax.value)
</script>

<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

    <div class="lg:grid lg:grid-cols-3 lg:gap-10">
      <form class="lg:col-span-2 space-y-8" @submit.prevent>
        <section>
          <h2 class="text-lg font-semibold text-gray-900">Contact</h2>
          <div class="mt-4">
            <label class="block text-sm text-gray-700">Email address</label>
            <input type="email" placeholder="you@example.com"
              class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
          </div>
        </section>

        <section>
          <h2 class="text-lg font-semibold text-gray-900">Shipping address</h2>
          <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm text-gray-700">First name</label>
              <input type="text"
                class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
            </div>
            <div>
              <label class="block text-sm text-gray-700">Last name</label>
              <input type="text"
                class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
            </div>
            <div class="sm:col-span-2">
              <label class="block text-sm text-gray-700">Address</label>
              <input type="text"
                class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
            </div>
            <div>
              <label class="block text-sm text-gray-700">City</label>
              <input type="text"
                class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
            </div>
            <div>
              <label class="block text-sm text-gray-700">Postal code</label>
              <input type="text"
                class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
            </div>
            <div class="sm:col-span-2">
              <label class="block text-sm text-gray-700">Country</label>
              <select
                class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
                <option>United States</option>
                <option>Canada</option>
                <option>United Kingdom</option>
                <option>Pakistan</option>
              </select>
            </div>
          </div>
        </section>

        <section>
          <h2 class="text-lg font-semibold text-gray-900">Payment</h2>
          <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
              <label class="block text-sm text-gray-700">Card number</label>
              <input type="text" placeholder="1234 5678 9012 3456"
                class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
            </div>
            <div>
              <label class="block text-sm text-gray-700">Expiry</label>
              <input type="text" placeholder="MM/YY"
                class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
            </div>
            <div>
              <label class="block text-sm text-gray-700">CVC</label>
              <input type="text" placeholder="123"
                class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
            </div>
          </div>
        </section>
      </form>

      <aside class="mt-10 lg:mt-0">
        <div class="bg-gray-50 rounded-lg p-6 sticky top-20">
          <h2 class="text-lg font-semibold text-gray-900">Order Summary</h2>

          <ul class="mt-4 divide-y divide-gray-200">
            <li v-for="line in lines" :key="line.productId" class="py-3 flex">
              <div class="w-14 h-14 bg-gray-100 rounded-md overflow-hidden flex-shrink-0">
                <img :src="line.product.image" :alt="line.product.name" class="w-full h-full object-cover" />
              </div>
              <div class="ml-3 flex-1 text-sm">
                <p class="font-medium text-gray-900 line-clamp-1">{{ line.product.name }}</p>
                <p class="text-xs text-gray-500">Qty {{ line.quantity }}</p>
              </div>
              <p class="text-sm font-medium text-gray-900">${{ line.lineTotal.toFixed(2) }}</p>
            </li>
          </ul>

          <dl class="mt-4 space-y-2 text-sm border-t border-gray-200 pt-4">
            <div class="flex justify-between">
              <dt class="text-gray-600">Subtotal</dt>
              <dd class="font-medium">${{ subtotal.toFixed(2) }}</dd>
            </div>
            <div class="flex justify-between">
              <dt class="text-gray-600">Shipping</dt>
              <dd class="font-medium">{{ shipping === 0 ? 'Free' : `$${shipping.toFixed(2)}` }}</dd>
            </div>
            <div class="flex justify-between">
              <dt class="text-gray-600">Tax</dt>
              <dd class="font-medium">${{ tax.toFixed(2) }}</dd>
            </div>
            <div class="border-t border-gray-200 pt-2 flex justify-between">
              <dt class="font-semibold text-gray-900">Total</dt>
              <dd class="font-semibold text-gray-900">${{ total.toFixed(2) }}</dd>
            </div>
          </dl>

          <button type="button"
            class="mt-6 w-full inline-flex justify-center items-center px-6 py-3 bg-gray-900 text-white text-sm font-medium rounded-md hover:bg-gray-800">
            Place Order
          </button>
        </div>
      </aside>
    </div>
  </div>
</template>
