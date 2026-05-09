<script setup>
import { computed } from 'vue'

const props = defineProps({
  status: { type: String, required: true }
})

const styles = computed(() => {
  const map = {
    pending: 'bg-amber-100 text-amber-800',
    shipped: 'bg-blue-100 text-blue-800',
    delivered: 'bg-green-100 text-green-800',
    cancelled: 'bg-gray-200 text-gray-700'
  }
  return map[props.status] || 'bg-gray-100 text-gray-700'
})

const label = computed(() => props.status.charAt(0).toUpperCase() + props.status.slice(1))
</script>

<template>
  <span :class="['inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium', styles]">
    {{ label }}
  </span>
</template>
