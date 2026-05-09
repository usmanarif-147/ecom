<script setup>
import StatusPill from './StatusPill.vue'

defineProps({
  order: { type: Object, required: true }
})

const formatDate = (iso) =>
  new Date(iso).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
</script>

<template>
  <div class="flex items-center justify-between py-4 px-4 hover:bg-gray-50">
    <div class="flex items-center space-x-4 min-w-0 flex-1">
      <div class="flex -space-x-2">
        <img v-for="(item, i) in order.items.slice(0, 3)" :key="i" :src="item.image" :alt="item.name"
          class="w-10 h-10 rounded-md object-cover border-2 border-white" />
      </div>
      <div class="min-w-0">
        <p class="text-sm font-medium text-gray-900 truncate">{{ order.id }}</p>
        <p class="text-xs text-gray-500">
          {{ formatDate(order.date) }} · {{ order.items.length }} item{{ order.items.length > 1 ? 's' : '' }}
        </p>
      </div>
    </div>

    <div class="flex items-center space-x-4 sm:space-x-6">
      <StatusPill :status="order.status" />
      <p class="text-sm font-semibold text-gray-900 w-20 text-right">${{ order.total.toFixed(2) }}</p>
      <button class="text-sm text-gray-700 hover:text-gray-900 font-medium">View</button>
    </div>
  </div>
</template>
