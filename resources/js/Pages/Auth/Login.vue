<template>
  <AppLayout>
    <Head title="Sign In" />

    <section class="flex-1 flex">

      <!-- ── Left brand panel (lg+) ── -->
      <div class="hidden lg:flex w-5/12 xl:w-[45%] relative overflow-hidden bg-linear-to-br from-purple-900 via-purple-800 to-purple-600 flex-col items-center justify-center p-14 text-center select-none">

        <!-- Dot grid overlay -->
        <div class="absolute inset-0 opacity-[0.04]"
          style="background-image: radial-gradient(circle, white 1.5px, transparent 1.5px); background-size: 28px 28px;"></div>

        <!-- Decorative blobs -->
        <div class="absolute -top-32 -right-32 w-80 h-80 rounded-full bg-white/8 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-72 h-72 rounded-full bg-purple-400/20 blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 right-0 w-40 h-40 rounded-full bg-white/5 blur-2xl pointer-events-none"></div>

        <!-- Content -->
        <div class="relative z-10">
          <img src="/images/Lavender-Logo.png" alt="Lavender Food & Bakery"
            class="h-16 w-auto object-contain mx-auto mb-8"
            style="filter: brightness(0) invert(1);">

          <h2 class="font-display text-4xl font-bold text-white mb-4 leading-snug">
            Freshly Baked,<br>Lovingly Made
          </h2>
          <p class="text-white/60 text-sm leading-relaxed max-w-xs mx-auto mb-10">
            Premium cakes, pastries, and bakery treats crafted with care and delivered fresh to your door.
          </p>

          <!-- Trust badges -->
          <div class="flex flex-col gap-3 text-left max-w-xs mx-auto">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl bg-white/12 flex items-center justify-center shrink-0">
                <svg class="w-4.5 h-4.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
              </div>
              <span class="text-white/75 text-sm">Premium quality ingredients</span>
            </div>
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl bg-white/12 flex items-center justify-center shrink-0">
                <svg class="w-4.5 h-4.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
              </div>
              <span class="text-white/75 text-sm">Fresh daily delivery</span>
            </div>
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl bg-white/12 flex items-center justify-center shrink-0">
                <svg class="w-4.5 h-4.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
              </div>
              <span class="text-white/75 text-sm">Supporting Abinta Kabir Foundation</span>
            </div>
          </div>
        </div>
      </div>

      <!-- ── Right form panel ── -->
      <div class="flex-1 bg-white flex items-center justify-center px-6 py-12 lg:px-14 overflow-y-auto">
        <div class="w-full max-w-md">

          <h1 class="font-display text-3xl font-bold text-charcoal mb-1.5">Welcome Back</h1>
          <p class="text-muted text-sm mb-8">Sign in to your account to continue</p>

          <form @submit.prevent="submit" class="space-y-5">
            <div>
              <label class="text-sm font-medium text-charcoal mb-1.5 block">Email Address</label>
              <input v-model="form.email" type="email" class="input-field" :class="{'border-error': errors.email}"
                placeholder="your@email.com" required>
              <p v-if="errors.email" class="text-error text-xs mt-1">{{ errors.email }}</p>
            </div>

            <div>
              <label class="text-sm font-medium text-charcoal mb-1.5 block">Password</label>
              <div class="relative">
                <input v-model="form.password" :type="showPassword ? 'text' : 'password'"
                  class="input-field pr-12" :class="{'border-error': errors.password}"
                  placeholder="••••••••" required>
                <button type="button" @click="showPassword = !showPassword"
                  class="absolute right-4 top-1/2 -translate-y-1/2 text-muted hover:text-primary transition-colors">
                  <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                  <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                  </svg>
                </button>
              </div>
              <p v-if="errors.password" class="text-error text-xs mt-1">{{ errors.password }}</p>
            </div>

            <div class="flex items-center justify-between">
              <label class="flex items-center gap-2 text-sm text-muted cursor-pointer select-none">
                <input v-model="form.remember" type="checkbox" class="rounded text-primary">
                Remember me
              </label>
              <Link :href="route('password.request')" class="text-sm text-primary font-medium hover:underline">
                Forgot password?
              </Link>
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

          <div class="mt-6 pt-6 border-t border-purple-100">
            <p class="text-center text-sm text-muted">
              Don't have an account?
              <Link :href="route('register')" class="text-primary font-semibold hover:underline">Create one</Link>
            </p>
          </div>
        </div>
      </div>

    </section>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

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
