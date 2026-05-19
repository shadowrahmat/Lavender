<template>
  <AdminLayout>
    <Head title="Orders" />

    <div class="flex items-center justify-between mb-6">
      <h1 class="font-display text-2xl font-bold text-charcoal">Orders</h1>
      <!-- Filters -->
      <div class="flex gap-3">
        <select v-model="statusFilter" @change="applyFilter" class="input-field text-sm w-40 py-2">
          <option value="">All Status</option>
          <option value="pending">Pending</option>
          <option value="confirmed">Confirmed</option>
          <option value="processing">Processing</option>
          <option value="out_for_delivery">Out for Delivery</option>
          <option value="completed">Completed</option>
          <option value="cancelled">Cancelled</option>
        </select>
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-purple-50 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-purple-50/50">
            <tr>
              <th class="text-left px-5 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Order #</th>
              <th class="text-left px-5 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Customer</th>
              <th class="text-left px-5 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Date</th>
              <th class="text-left px-5 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Status</th>
              <th class="text-left px-5 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Payment</th>
              <th class="text-right px-5 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Total</th>
              <th class="text-right px-5 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-purple-50">
            <tr v-for="order in orders.data" :key="order.id"
              class="hover:bg-purple-50/20 transition-colors">
              <td class="px-5 py-4">
                <p class="font-medium text-charcoal text-sm">{{ order.order_number }}</p>
              </td>
              <td class="px-5 py-4 text-sm text-charcoal">{{ order.customer }}</td>
              <td class="px-5 py-4 text-sm text-muted">{{ order.created_at }}</td>
              <td class="px-5 py-4">
                <span :class="{
                  'badge-warning': order.status === 'pending',
                  'badge-success': order.status === 'completed',
                  'badge-error': order.status === 'cancelled',
                  'badge-purple': !['pending', 'completed', 'cancelled'].includes(order.status),
                }" class="badge text-xs">{{ order.status_label }}</span>
              </td>
              <td class="px-5 py-4">
                <span class="text-xs text-muted uppercase">{{ order.payment_method }}</span>
                <span :class="order.payment_status === 'paid' ? 'text-success' : 'text-warning'"
                  class="block text-xs font-medium">{{ order.payment_status }}</span>
              </td>
              <td class="px-5 py-4 text-right font-semibold text-charcoal text-sm">
                ৳{{ Number(order.total).toFixed(2) }}
              </td>
              <td class="px-5 py-4">
                <Link :href="route('admin.orders.show', order.id)"
                  class="text-xs text-primary hover:underline px-3 py-1.5 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                  View
                </Link>
              </td>
            </tr>
            <tr v-if="!orders.data?.length">
              <td colspan="7" class="px-5 py-12 text-center text-muted text-sm">No orders found</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="orders.last_page > 1" class="flex justify-center gap-2 p-5 border-t border-purple-50">
        <template v-for="link in orders.links" :key="link.label">
          <Link v-if="link.url" :href="link.url"
            :class="link.active ? 'bg-primary text-white' : 'bg-white text-charcoal hover:bg-purple-50'"
            class="px-4 py-2 rounded-xl text-sm font-medium border border-purple-100 transition-colors"
            v-html="link.label">
          </Link>
        </template>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  orders: Object,
  filters: Object,
})

const statusFilter = ref(props.filters?.status || '')

const applyFilter = () => {
  router.get(route('admin.orders.index'), { status: statusFilter.value }, { preserveState: true })
}
</script>
