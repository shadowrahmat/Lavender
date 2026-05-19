<template>
  <AppLayout>
    <Head title="My Orders" />
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="flex items-center justify-between mb-8">
        <h1 class="font-display text-3xl font-bold text-charcoal">My Orders</h1>
        <Link :href="route('account.dashboard')" class="text-primary text-sm hover:underline">← Back to Dashboard</Link>
      </div>

      <div v-if="orders.data?.length" class="space-y-4">
        <div v-for="order in orders.data" :key="order.id"
          class="card p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
          <div>
            <p class="font-bold text-charcoal">{{ order.order_number }}</p>
            <p class="text-muted text-sm mt-0.5">{{ order.created_at }} · {{ order.items_count }} items</p>
            <p class="text-muted text-sm">{{ order.payment_method.toUpperCase() }}</p>
          </div>
          <span :class="{
            'badge-warning': order.status === 'pending',
            'badge-success': order.status === 'completed',
            'badge-error': order.status === 'cancelled',
            'badge-purple': !['pending', 'completed', 'cancelled'].includes(order.status),
          }" class="badge">{{ order.status_label }}</span>
          <div class="text-right">
            <p class="font-bold text-primary text-lg">৳{{ Number(order.total).toFixed(2) }}</p>
            <Link :href="route('account.orders.show', order.id)" class="text-sm text-primary hover:underline">View Details</Link>
          </div>
        </div>
      </div>
      <div v-else class="text-center py-20">
        <div class="text-7xl mb-4">📦</div>
        <h2 class="font-display text-2xl font-bold text-charcoal mb-3">No orders yet</h2>
        <Link :href="route('shop.index')" class="btn-primary">Start Shopping</Link>
      </div>
    </div>
  </AppLayout>
</template>
<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
defineProps({ orders: Object })
</script>
