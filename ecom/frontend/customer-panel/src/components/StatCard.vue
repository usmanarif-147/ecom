<script setup>
defineProps({
  label: { type: String, required: true },
  value: { type: [String, Number], required: true },
  icon: { type: String, required: true },
  accent: { type: String, default: 'bg-gray-100 text-gray-700' }
})
</script>

<template>
  <div class="bg-white border border-gray-200 rounded-lg p-5">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm text-gray-500">{{ label }}</p>
        <p class="text-2xl font-semibold text-gray-900 mt-1">{{ value }}</p>
      </div>
      <div :class="['w-10 h-10 rounded-md flex items-center justify-center', accent]">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" :d="icon" />
        </svg>
      </div>
    </div>
  </div>
</template>
