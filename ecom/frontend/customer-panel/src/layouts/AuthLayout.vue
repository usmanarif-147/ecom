<template>
  <div class="min-h-screen bg-gray-50 flex flex-col">
    <header class="px-6 py-5 flex items-center justify-between">
      <RouterLink to="/dashboard" class="text-lg font-bold tracking-tight text-gray-900">
        MYSHOP
      </RouterLink>
      <a href="http://localhost:3000/" class="text-sm text-gray-600 hover:text-gray-900">
        ← Back to store
      </a>
    </header>

    <main class="flex-1 flex items-center justify-center px-4 pb-12">
      <RouterView />
    </main>
  </div>
</template>
