<template>
  <div class="min-h-screen bg-gradient-to-br from-purple-900 via-primary to-purple-700 flex items-center justify-center px-4 py-12">
    <Head title="Login" />

    <div class="w-full max-w-md">
      <!-- Logo -->
      <div class="text-center mb-8">
        <Link :href="route('home')" class="inline-flex items-center gap-3">
          <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center backdrop-blur-sm border border-white/30">
            <span class="text-white font-bold text-2xl font-display">L</span>
          </div>
          <div class="text-left">
            <span class="font-display text-2xl font-bold text-white">Lavender</span>
            <span class="block text-white/70 text-sm -mt-1">Food & Bakery</span>
          </div>
        </Link>
      </div>

      <!-- Card -->
      <div class="glass rounded-3xl p-8 shadow-2xl">
        <h1 class="font-display text-2xl font-bold text-charcoal mb-2">Welcome Back</h1>
        <p class="text-muted text-sm mb-8">Sign in to your account to continue</p>

        <form @submit.prevent="submit" class="space-y-5">
          <div>
            <label class="text-sm font-medium text-charcoal mb-1.5 block">Email Address</label>
            <input v-model="form.email" type="email" class="input-field"
              :class="{'border-error': errors.email}" placeholder="your@email.com" required>
            <p v-if="errors.email" class="text-error text-xs mt-1">{{ errors.email }}</p>
          </div>

          <div>
            <label class="text-sm font-medium text-charcoal mb-1.5 block">Password</label>
            <div class="relative">
              <input v-model="form.password" :type="showPassword ? 'text' : 'password'"
                class="input-field pr-12" :class="{'border-error': errors.password}"
                placeholder="••••••••" required>
              <button type="button" @click="showPassword = !showPassword"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-muted hover:text-charcoal">
                <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                </svg>
              </button>
            </div>
            <p v-if="errors.password" class="text-error text-xs mt-1">{{ errors.password }}</p>
          </div>

          <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-muted cursor-pointer">
              <input v-model="form.remember" type="checkbox" class="rounded text-primary">
              Remember me
            </label>
            <Link :href="route('password.request')" class="text-sm text-primary hover:underline">Forgot password?</Link>
          </div>

          <button type="submit" :disabled="processing" class="btn-primary w-full py-3.5 text-base">
            <span v-if="processing" class="flex items-center justify-center gap-2">
              <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
              </svg>
              Signing in...
            </span>
            <span v-else>Sign In</span>
          </button>
        </form>

        <p class="text-center text-sm text-muted mt-6">
          Don't have an account?
          <Link :href="route('register')" class="text-primary font-semibold hover:underline">Create one</Link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'

const form = ref({ email: '', password: '', remember: false })
const errors = ref({})
const processing = ref(false)
const showPassword = ref(false)

const submit = () => {
  processing.value = true
  errors.value = {}
  router.post(route('login.store'), form.value, {
    onError: (errs) => {
      errors.value = errs
      processing.value = false
    },
  })
}
</script>
