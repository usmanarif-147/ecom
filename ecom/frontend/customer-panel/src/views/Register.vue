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
      <h1 class="text-2xl font-semibold text-gray-900">Create your account</h1>
      <p class="text-sm text-gray-600 mt-1">Start shopping in under a minute.</p>

      <form @submit.prevent="submit" class="mt-6 space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Full name</label>
          <input type="text" placeholder="Jane Doe"
            class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" placeholder="you@example.com"
            class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Password</label>
          <input type="password" placeholder="At least 8 characters"
            class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Confirm password</label>
          <input type="password" placeholder="Repeat password"
            class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900" />
        </div>

        <label class="flex items-start text-sm text-gray-700">
          <input type="checkbox"
            class="mt-0.5 h-4 w-4 text-gray-900 border-gray-300 rounded focus:ring-gray-900" />
          <span class="ml-2">
            I agree to the
            <a href="#" class="font-medium text-gray-900 hover:underline">Terms</a>
            and
            <a href="#" class="font-medium text-gray-900 hover:underline">Privacy Policy</a>.
          </span>
        </label>

        <button type="submit"
          class="w-full px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-md hover:bg-gray-800">
          Create account
        </button>
      </form>
    </div>

    <p class="mt-6 text-center text-sm text-gray-600">
      Already have an account?
      <RouterLink to="/login" class="font-medium text-gray-900 hover:underline">Sign in</RouterLink>
    </p>
  </div>
</template>
