<template>
  <AdminLayout>
    <Head title="Admin Dashboard" />

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
      <div v-for="stat in statCards" :key="stat.label"
        class="bg-white rounded-2xl p-5 shadow-sm border border-purple-50 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
        <div class="flex items-center justify-between mb-4">
          <div class="w-11 h-11 rounded-xl flex items-center justify-center" :class="stat.bg">
            <component :is="stat.icon" class="w-5 h-5" :class="stat.iconColor" />
          </div>
          <span :class="stat.change >= 0 ? 'text-success bg-success/10' : 'text-error bg-error/10'"
            class="text-xs font-semibold px-2 py-1 rounded-lg">
            {{ stat.change >= 0 ? '+' : '' }}{{ stat.change }}%
          </span>
        </div>
        <p class="text-2xl font-bold text-charcoal font-display">{{ stat.value }}</p>
        <p class="text-muted text-sm mt-0.5">{{ stat.label }}</p>
      </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
      <!-- Recent Orders -->
      <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-purple-50 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5 border-b border-purple-50">
          <h3 class="font-display font-semibold text-charcoal text-lg">Recent Orders</h3>
          <Link :href="route('admin.orders.index')" class="text-primary text-sm hover:underline flex items-center gap-1">
            View All <ArrowRightIcon class="w-3.5 h-3.5" />
          </Link>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-purple-50/60">
              <tr>
                <th class="text-left px-6 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Order</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Customer</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Status</th>
                <th class="text-right px-6 py-3 text-xs font-semibold text-muted uppercase tracking-wider">Total</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-purple-50">
              <tr v-for="order in recentOrders" :key="order.id"
                class="hover:bg-purple-50/30 transition-colors">
                <td class="px-6 py-4">
                  <Link :href="route('admin.orders.show', order.id)"
                    class="font-medium text-charcoal text-sm hover:text-primary transition-colors">
                    {{ order.order_number }}
                  </Link>
                  <p class="text-xs text-muted mt-0.5">{{ order.created_at }}</p>
                </td>
                <td class="px-6 py-4 text-sm text-charcoal">{{ order.customer }}</td>
                <td class="px-6 py-4">
                  <span :class="{
                    'badge-warning': order.status === 'pending',
                    'badge-success': order.status === 'completed',
                    'badge-error': order.status === 'cancelled',
                    'badge-purple': !['pending', 'completed', 'cancelled'].includes(order.status),
                  }" class="badge text-xs">{{ order.status_label }}</span>
                </td>
                <td class="px-6 py-4 text-right font-semibold text-charcoal text-sm">
                  ৳{{ Number(order.total).toFixed(2) }}
                </td>
              </tr>
              <tr v-if="!recentOrders?.length">
                <td colspan="4" class="px-6 py-10 text-center text-muted text-sm">No orders yet</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Right Column -->
      <div class="space-y-5">

        <!-- Top Products -->
        <div class="bg-white rounded-2xl shadow-sm border border-purple-50 p-5">
          <h3 class="font-display font-semibold text-charcoal text-base mb-4">Top Products</h3>
          <div class="space-y-3">
            <div v-for="(product, i) in topProducts" :key="i" class="flex items-center gap-3">
              <span class="w-6 h-6 rounded-full bg-purple-100 text-primary text-xs font-bold flex items-center justify-center shrink-0">
                {{ i + 1 }}
              </span>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-charcoal truncate">{{ product.product_name }}</p>
                <p class="text-xs text-muted">{{ product.sold }} sold</p>
              </div>
              <p class="text-sm font-semibold text-primary shrink-0">৳{{ Number(product.revenue).toFixed(0) }}</p>
            </div>
            <p v-if="!topProducts?.length" class="text-muted text-sm text-center py-4">No sales data yet</p>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-sm border border-purple-50 p-5">
          <h3 class="font-display font-semibold text-charcoal text-base mb-4">Quick Actions</h3>
          <div class="space-y-2">
            <Link :href="route('admin.products.create')"
              class="flex items-center gap-3 p-3 rounded-xl bg-purple-50 hover:bg-primary hover:text-white text-charcoal transition-all duration-200 text-sm group">
              <PlusCircleIcon class="w-5 h-5 text-primary group-hover:text-white transition-colors" />
              Add New Product
            </Link>
            <Link :href="route('admin.orders.index', { status: 'pending' })"
              class="flex items-center gap-3 p-3 rounded-xl bg-warning/10 hover:bg-warning hover:text-white text-charcoal transition-all duration-200 text-sm group">
              <ClockIcon class="w-5 h-5 text-warning group-hover:text-white transition-colors" />
              View Pending Orders
              <span v-if="stats.pending_orders > 0" class="ml-auto badge badge-warning text-xs">
                {{ stats.pending_orders }}
              </span>
            </Link>
            <Link :href="route('home')" target="_blank"
              class="flex items-center gap-3 p-3 rounded-xl bg-purple-50 hover:bg-purple-100 text-charcoal transition-all duration-200 text-sm group">
              <ArrowTopRightOnSquareIcon class="w-5 h-5 text-muted group-hover:text-primary transition-colors" />
              View Live Website
            </Link>
          </div>
        </div>

      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import {
  ShoppingBagIcon,
  BanknotesIcon,
  ClockIcon,
  UsersIcon,
  ArrowRightIcon,
  PlusCircleIcon,
  ArrowTopRightOnSquareIcon,
} from '@heroicons/vue/24/outline'

const props = defineProps({
  stats: Object,
  recentOrders: Array,
  salesData: Array,
  topProducts: Array,
})

const statCards = computed(() => [
  {
    icon: ShoppingBagIcon,
    label: 'Total Orders',
    value: props.stats.total_orders,
    bg: 'bg-purple-100',
    iconColor: 'text-primary',
    change: 12,
  },
  {
    icon: BanknotesIcon,
    label: 'Total Revenue',
    value: '৳' + Number(props.stats.total_revenue).toFixed(0),
    bg: 'bg-success/10',
    iconColor: 'text-success',
    change: 8,
  },
  {
    icon: ClockIcon,
    label: 'Pending Orders',
    value: props.stats.pending_orders,
    bg: 'bg-warning/10',
    iconColor: 'text-warning',
    change: -3,
  },
  {
    icon: UsersIcon,
    label: 'Customers',
    value: props.stats.total_customers,
    bg: 'bg-blue-50',
    iconColor: 'text-blue-500',
    change: 5,
  },
])
</script>
