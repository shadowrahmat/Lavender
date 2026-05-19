<template>
  <div class="min-h-screen bg-gradient-to-br from-purple-900 via-primary to-purple-700 flex items-center justify-center px-4 py-12">
    <Head title="Register" />

    <div class="w-full max-w-md">
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

      <div class="glass rounded-3xl p-8 shadow-2xl">
        <h1 class="font-display text-2xl font-bold text-charcoal mb-2">Create Account</h1>
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
          <div>
            <label class="text-sm font-medium text-charcoal mb-1.5 block">Password</label>
            <input v-model="form.password" type="password" class="input-field" :class="{'border-error': errors.password}"
              placeholder="••••••••" required>
            <p v-if="errors.password" class="text-error text-xs mt-1">{{ errors.password }}</p>
          </div>
          <div>
            <label class="text-sm font-medium text-charcoal mb-1.5 block">Confirm Password</label>
            <input v-model="form.password_confirmation" type="password" class="input-field"
              placeholder="Repeat password" required>
          </div>
          <button type="submit" :disabled="processing" class="btn-primary w-full py-3.5 text-base">
            <span v-if="processing">Creating account...</span>
            <span v-else>Create Account</span>
          </button>
        </form>

        <p class="text-center text-sm text-muted mt-6">
          Already have an account?
          <Link :href="route('login')" class="text-primary font-semibold hover:underline">Sign in</Link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'

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
