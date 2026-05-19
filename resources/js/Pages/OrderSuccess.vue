<template>
  <AppLayout>
    <Head title="Order Placed Successfully" />
    <div class="min-h-[80vh] flex items-center justify-center px-4">
      <div class="text-center max-w-md">
        <div class="w-24 h-24 rounded-full bg-success/10 flex items-center justify-center mx-auto mb-6">
          <svg class="w-12 h-12 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
        </div>
        <h1 class="font-display text-4xl font-bold text-charcoal mb-3">Order Placed!</h1>
        <p class="text-muted text-lg mb-4">Thank you for your order. We'll start preparing it right away!</p>
        <div class="bg-purple-50 rounded-2xl p-5 mb-8">
          <p class="text-sm text-muted mb-1">Your Order Number</p>
          <p class="font-display text-2xl font-bold text-primary">{{ orderNumber }}</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <Link :href="route('order.track')" class="btn-primary">Track Your Order</Link>
          <Link :href="route('shop.index')" class="btn-secondary">Continue Shopping</Link>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
defineProps({ orderNumber: String })
</script>
