<template>
  <AppLayout>
    <Head :title="`Order ${order.order_number}`" />
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="flex items-center justify-between mb-8">
        <h1 class="font-display text-2xl font-bold text-charcoal">Order #{{ order.order_number }}</h1>
        <Link :href="route('account.orders')" class="text-primary text-sm hover:underline">← Back to Orders</Link>
      </div>

      <!-- Status -->
      <div class="card p-5 mb-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl gradient-purple flex items-center justify-center">
          <span class="text-xl">📦</span>
        </div>
        <div>
          <p class="text-muted text-sm">Order Status</p>
          <span :class="{
            'badge-warning': order.status === 'pending',
            'badge-success': order.status === 'completed',
            'badge-error': order.status === 'cancelled',
            'badge-purple': !['pending', 'completed', 'cancelled'].includes(order.status),
          }" class="badge text-base px-4 py-1.5">{{ order.status_label }}</span>
        </div>
        <p class="ml-auto text-sm text-muted">Placed on {{ order.created_at }}</p>
      </div>

      <!-- Items -->
      <div class="card p-6 mb-6">
        <h3 class="font-display font-semibold text-charcoal text-lg mb-4">Order Items</h3>
        <div class="space-y-4">
          <div v-for="item in order.items" :key="item.id" class="flex gap-4 items-center">
            <img :src="item.product_image" :alt="item.product_name" class="w-16 h-16 object-cover rounded-xl">
            <div class="flex-1">
              <p class="font-semibold text-charcoal">{{ item.product_name }}</p>
              <p class="text-muted text-sm">৳{{ Number(item.price).toFixed(2) }} × {{ item.quantity }}</p>
            </div>
            <p class="font-bold text-charcoal">৳{{ Number(item.total).toFixed(2) }}</p>
          </div>
        </div>
      </div>

      <!-- Summary -->
      <div class="card p-6 mb-6">
        <h3 class="font-display font-semibold text-charcoal text-lg mb-4">Order Summary</h3>
        <div class="space-y-3">
          <div class="flex justify-between text-sm">
            <span class="text-muted">Subtotal</span>
            <span class="font-medium">৳{{ Number(order.subtotal).toFixed(2) }}</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-muted">Delivery Charge</span>
            <span class="font-medium">{{ order.delivery_charge == 0 ? 'FREE' : '৳' + Number(order.delivery_charge).toFixed(2) }}</span>
          </div>
          <div v-if="order.discount > 0" class="flex justify-between text-sm">
            <span class="text-success">Discount</span>
            <span class="text-success font-medium">-৳{{ Number(order.discount).toFixed(2) }}</span>
          </div>
          <hr class="border-purple-100">
          <div class="flex justify-between font-bold text-lg">
            <span>Total</span>
            <span class="text-primary">৳{{ Number(order.total).toFixed(2) }}</span>
          </div>
        </div>
      </div>

      <!-- Delivery Info -->
      <div class="card p-6">
        <h3 class="font-display font-semibold text-charcoal text-lg mb-4">Delivery Information</h3>
        <p class="text-muted text-sm mb-1">{{ order.delivery_address }}</p>
        <p class="text-muted text-sm">{{ order.delivery_city }}</p>
        <p class="mt-3 text-sm"><span class="font-medium text-charcoal">Payment:</span> {{ order.payment_method.toUpperCase() }} · {{ order.payment_status }}</p>
      </div>
    </div>
  </AppLayout>
</template>
<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
defineProps({ order: Object })
</script>
