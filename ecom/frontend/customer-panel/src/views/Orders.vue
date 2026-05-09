<script setup>
import { ref } from 'vue'
import { orders } from '../data/customer.js'
import OrderRow from '../components/OrderRow.vue'

const filters = ['All', 'Pending', 'Shipped', 'Delivered', 'Cancelled']
const activeFilter = ref('All')
</script>

<template>
  <div class="space-y-6">
    <div>
      <h2 class="text-2xl font-semibold text-gray-900">Your Orders</h2>
      <p class="text-sm text-gray-600 mt-1">Track and manage your order history.</p>
    </div>

    <div class="flex flex-wrap gap-2">
      <button v-for="f in filters" :key="f" @click="activeFilter = f" :class="[
        'px-3 py-1.5 text-sm rounded-full border transition',
        activeFilter === f
          ? 'bg-gray-900 text-white border-gray-900'
          : 'bg-white text-gray-700 border-gray-300 hover:border-gray-900'
      ]">
        {{ f }}
      </button>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg divide-y divide-gray-200">
      <OrderRow v-for="order in orders" :key="order.id" :order="order" />
    </div>
  </div>
</template>
