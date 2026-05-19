<template>
  <div class="min-h-screen gradient-purple flex items-center justify-center px-4">
    <Head title="Forgot Password" />
    <div class="w-full max-w-md">
      <div class="glass rounded-3xl p-8 shadow-2xl">
        <div class="text-center mb-6">
          <div class="text-5xl mb-4">🔐</div>
          <h1 class="font-display text-2xl font-bold text-charcoal mb-2">Forgot Password?</h1>
          <p class="text-muted text-sm">Enter your email and we'll send you a reset link.</p>
        </div>
        <form class="space-y-5">
          <div>
            <label class="text-sm font-medium text-charcoal mb-1.5 block">Email Address</label>
            <input type="email" class="input-field" placeholder="your@email.com">
          </div>
          <button type="submit" class="btn-primary w-full py-3.5">Send Reset Link</button>
        </form>
        <p class="text-center text-sm text-muted mt-6">
          Remember your password?
          <Link :href="route('login')" class="text-primary font-semibold hover:underline">Sign in</Link>
        </p>
      </div>
    </div>
  </div>
</template>
<script setup>
import { Head, Link } from '@inertiajs/vue3'
</script>
