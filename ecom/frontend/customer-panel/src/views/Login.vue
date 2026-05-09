<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const submit = () => {
  // Static UI only — no real auth.
  router.push('/dashboard')
}
</script>

<template>
  <div class="w-full max-w-md">
    <div class="bg-white border border-gray-200 rounded-lg p-8 shadow-sm">
      <h1 class="text-2xl font-semibold text-gray-900">Welcome back</h1>
      <p class="text-sm text-gray-600 mt-1">Sign in to your account.</p>

      <form @submit.prevent="submit" class="mt-6 space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" placeholder="you@example.com"
            class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
        </div>

        <div>
          <div class="flex items-center justify-between">
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <a href="#" class="text-xs text-gray-600 hover:text-gray-900">Forgot password?</a>
          </div>
          <input type="password" placeholder="••••••••"
            class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
        </div>

        <label class="flex items-center text-sm text-gray-700">
          <input type="checkbox"
            class="h-4 w-4 text-gray-900 border-gray-300 rounded focus:ring-gray-900" />
          <span class="ml-2">Remember me</span>
        </label>

        <button type="submit"
          class="w-full px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-md hover:bg-gray-800">
          Sign in
        </button>
      </form>
    </div>

    <p class="mt-6 text-center text-sm text-gray-600">
      Don't have an account?
      <RouterLink to="/register" class="font-medium text-gray-900 hover:underline">Create one</RouterLink>
    </p>
  </div>
</template>
