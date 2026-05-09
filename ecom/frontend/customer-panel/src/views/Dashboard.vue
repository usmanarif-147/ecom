<script setup>
import { RouterLink } from 'vue-router'
import { customer, stats, orders, favorites } from '../data/customer.js'
import StatCard from '../components/StatCard.vue'
import OrderRow from '../components/OrderRow.vue'
import FavoriteCard from '../components/FavoriteCard.vue'

const recentOrders = orders.slice(0, 3)
const recentFavorites = favorites.slice(0, 4)
</script>

<template>
  <div class="space-y-8">
    <section>
      <h2 class="text-2xl font-semibold text-gray-900">Welcome back, {{ customer.name }}</h2>
      <p class="text-sm text-gray-600 mt-1">Here's a quick look at your account.</p>
    </section>

    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <StatCard label="Total Orders" :value="stats.totalOrders" accent="bg-blue-50 text-blue-700"
        icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
      <StatCard label="In Transit" :value="stats.inTransit" accent="bg-amber-50 text-amber-700"
        icon="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
      <StatCard label="Delivered" :value="stats.delivered" accent="bg-green-50 text-green-700"
        icon="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
      <StatCard label="Saved Items" :value="stats.saved" accent="bg-rose-50 text-rose-700"
        icon="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
    </section>

    <section>
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
        <RouterLink to="/orders" class="text-sm text-gray-700 hover:text-gray-900 font-medium">
          View all →
        </RouterLink>
      </div>
      <div class="bg-white border border-gray-200 rounded-lg divide-y divide-gray-200">
        <OrderRow v-for="order in recentOrders" :key="order.id" :order="order" />
      </div>
    </section>

    <section>
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Recently Saved</h3>
        <RouterLink to="/favorites" class="text-sm text-gray-700 hover:text-gray-900 font-medium">
          View all →
        </RouterLink>
      </div>
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <FavoriteCard v-for="product in recentFavorites" :key="product.id" :product="product" />
      </div>
    </section>
  </div>
</template>
