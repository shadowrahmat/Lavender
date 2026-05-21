<template>
  <AppLayout>
    <Head title="Create Account" />

    <section class="flex-1 flex">

      <!-- ── Left brand panel (lg+) ── -->
      <div class="hidden lg:flex w-5/12 xl:w-[45%] relative overflow-hidden bg-linear-to-br from-purple-900 via-purple-800 to-purple-600 flex-col items-center justify-center p-14 text-center select-none">

        <!-- Dot grid overlay -->
        <div class="absolute inset-0 opacity-[0.04]"
          style="background-image: radial-gradient(circle, white 1.5px, transparent 1.5px); background-size: 28px 28px;"></div>

        <!-- Decorative blobs -->
        <div class="absolute -top-32 -right-32 w-80 h-80 rounded-full bg-white/8 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-72 h-72 rounded-full bg-purple-400/20 blur-3xl pointer-events-none"></div>

        <!-- Content -->
        <div class="relative z-10">
          <img src="/images/Lavender-Logo.png" alt="Lavender Food & Bakery"
            class="h-16 w-auto object-contain mx-auto mb-8"
            style="filter: brightness(0) invert(1);">

          <h2 class="font-display text-4xl font-bold text-white mb-4 leading-snug">
            Join the<br>Lavender Family
          </h2>
          <p class="text-white/60 text-sm leading-relaxed max-w-xs mx-auto mb-10">
            Create your account and unlock exclusive deals, order history, and a personalised bakery experience.
          </p>

          <!-- Perks list -->
          <div class="flex flex-col gap-3 text-left max-w-xs mx-auto">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl bg-white/12 flex items-center justify-center shrink-0">
                <svg class="w-4.5 h-4.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
              </div>
              <span class="text-white/75 text-sm">Track all your orders easily</span>
            </div>
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl bg-white/12 flex items-center justify-center shrink-0">
                <svg class="w-4.5 h-4.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
              </div>
              <span class="text-white/75 text-sm">Exclusive discounts & offers</span>
            </div>
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl bg-white/12 flex items-center justify-center shrink-0">
                <svg class="w-4.5 h-4.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
              </div>
              <span class="text-white/75 text-sm">Save your favourite items</span>
            </div>
          </div>
        </div>
      </div>

      <!-- ── Right form panel ── -->
      <div class="flex-1 bg-white flex items-center justify-center px-6 py-12 lg:px-14 overflow-y-auto">
        <div class="w-full max-w-md">

          <h1 class="font-display text-3xl font-bold text-charcoal mb-1.5">Create Account</h1>
          <p class="text-muted text-sm mb-8">Join Lavender to enjoy exclusive benefits</p>

          <form @submit.prevent="submit" class="space-y-5">
            <div>
              <label class="text-sm font-medium text-charcoal mb-1.5 block">Full Name</label>
              <input v-model="form.name" type="text" class="input-field" :class="{'border-error': errors.name}"
                placeholder="Your full name" required>
              <p v-if="errors.name" class="text-error text-xs mt-1">{{ errors.name }}</p>
            </div>

            <div>
              <label class="text-sm font-medium text-charcoal mb-1.5 block">Email Address</label>
              <input v-model="form.email" type="email" class="input-field" :class="{'border-error': errors.email}"
                placeholder="your@email.com" required>
              <p v-if="errors.email" class="text-error text-xs mt-1">{{ errors.email }}</p>
            </div>

            <div>
              <label class="text-sm font-medium text-charcoal mb-1.5 block">Phone Number</label>
              <input v-model="form.phone" type="tel" class="input-field" :class="{'border-error': errors.phone}"
                placeholder="+8801XXXXXXXXX">
              <p v-if="errors.phone" class="text-error text-xs mt-1">{{ errors.phone }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="text-sm font-medium text-charcoal mb-1.5 block">Password</label>
                <input v-model="form.password" type="password" class="input-field"
                  :class="{'border-error': errors.password}" placeholder="••••••••" required>
                <p v-if="errors.password" class="text-error text-xs mt-1">{{ errors.password }}</p>
              </div>
              <div>
                <label class="text-sm font-medium text-charcoal mb-1.5 block">Confirm</label>
                <input v-model="form.password_confirmation" type="password" class="input-field"
                  placeholder="Repeat" required>
              </div>
            </div>

            <button type="submit" :disabled="processing" class="btn-primary w-full py-3.5 text-base">
              <span v-if="processing">Creating account...</span>
              <span v-else>Create Account</span>
            </button>
          </form>

          <div class="mt-6 pt-6 border-t border-purple-100">
            <p class="text-center text-sm text-muted">
              Already have an account?
              <Link :href="route('login')" class="text-primary font-semibold hover:underline">Sign in</Link>
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

const form = ref({ name: '', email: '', phone: '', password: '', password_confirmation: '' })
const errors = ref({})
const processing = ref(false)

const submit = () => {
  processing.value = true
  errors.value = {}
  router.post(route('register.store'), form.value, {
    onError: (errs) => { errors.value = errs; processing.value = false },
  })
}
</script>
