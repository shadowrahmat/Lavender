<template>
  <AppLayout>
    <Head title="My Account" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid lg:grid-cols-4 gap-8">
        <!-- Sidebar -->
        <aside class="lg:col-span-1">
          <div class="card p-6 mb-4">
            <div class="flex flex-col items-center text-center">
              <div class="w-20 h-20 rounded-full gradient-purple flex items-center justify-center text-white text-3xl font-bold font-display mb-3">
                {{ user.name[0].toUpperCase() }}
              </div>
              <h3 class="font-semibold text-charcoal text-lg">{{ user.name }}</h3>
              <p class="text-muted text-sm">{{ user.email }}</p>
            </div>
          </div>
          <nav class="card overflow-hidden">
            <Link :href="route('account.dashboard')" class="account-nav-link" :class="isActive('account.dashboard')">
              <span>🏠</span> Dashboard
            </Link>
            <Link :href="route('account.orders')" class="account-nav-link" :class="isActive('account.orders')">
              <span>📦</span> My Orders
            </Link>
            <Link :href="route('wishlist.index')" class="account-nav-link" :class="isActive('wishlist')">
              <span>❤️</span> Wishlist
            </Link>
            <Link :href="route('account.profile')" class="account-nav-link" :class="isActive('account.profile')">
              <span>👤</span> Profile
            </Link>
            <Link :href="route('logout')" method="post" as="button" class="account-nav-link w-full text-left text-error hover:bg-red-50">
              <span>🚪</span> Logout
            </Link>
          </nav>
        </aside>

        <!-- Main Content -->
        <div class="lg:col-span-3 space-y-6">
          <!-- Stats -->
          <div class="grid grid-cols-3 gap-4">
            <div class="card p-5 text-center">
              <p class="text-3xl font-bold text-primary font-display">{{ stats.total_orders }}</p>
              <p class="text-muted text-sm mt-1">Total Orders</p>
            </div>
            <div class="card p-5 text-center">
              <p class="text-3xl font-bold text-success font-display">{{ stats.completed_orders }}</p>
              <p class="text-muted text-sm mt-1">Completed</p>
            </div>
            <div class="card p-5 text-center">
              <p class="text-3xl font-bold text-primary font-display">{{ stats.wishlist_count }}</p>
              <p class="text-muted text-sm mt-1">Wishlist Items</p>
            </div>
          </div>

          <!-- Recent Orders -->
          <div class="card p-6">
            <div class="flex items-center justify-between mb-5">
              <h2 class="font-display font-semibold text-charcoal text-xl">Recent Orders</h2>
              <Link :href="route('account.orders')" class="text-primary text-sm hover:underline">View All</Link>
            </div>
            <div v-if="recentOrders?.length" class="space-y-3">
              <div v-for="order in recentOrders" :key="order.order_number"
                class="flex items-center justify-between p-4 bg-purple-50 rounded-xl">
                <div>
                  <p class="font-semibold text-charcoal text-sm">{{ order.order_number }}</p>
                  <p class="text-muted text-xs">{{ order.created_at }}</p>
                </div>
                <span :class="{
                  'badge-warning': order.status === 'pending',
                  'badge-success': order.status === 'completed',
                  'badge-error': order.status === 'cancelled',
                  'badge-purple': !['pending', 'completed', 'cancelled'].includes(order.status),
                }" class="badge text-xs">{{ order.status_label }}</span>
                <p class="font-bold text-primary">৳{{ Number(order.total).toFixed(2) }}</p>
              </div>
            </div>
            <div v-else class="text-center py-8">
              <div class="text-4xl mb-3">📦</div>
              <p class="text-muted">No orders yet. <Link :href="route('shop.index')" class="text-primary hover:underline">Start Shopping</Link></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

defineProps({
  user: Object,
  stats: Object,
  recentOrders: Array,
})

const isActive = (name) => route().current(name + '*') ? 'bg-purple-50 text-primary font-semibold' : 'text-charcoal hover:bg-purple-50'
</script>

<style scoped>
@reference "../../../css/app.css";
.account-nav-link {
  @apply flex items-center gap-3 px-5 py-3.5 text-sm transition-colors border-b border-purple-50 last:border-b-0;
}
</style>
