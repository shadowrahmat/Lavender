<template>
  <AdminLayout>
    <Head :title="`Order ${order.order_number}`" />

    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="font-display text-2xl font-bold text-charcoal">{{ order.order_number }}</h1>
        <p class="text-muted text-sm">Placed on {{ order.created_at }}</p>
      </div>
      <Link :href="route('admin.orders.index')" class="text-primary text-sm hover:underline">← Back to Orders</Link>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
      <!-- Order Items & Summary -->
      <div class="lg:col-span-2 space-y-5">
        <div class="bg-white rounded-2xl shadow-sm border border-purple-50 overflow-hidden">
          <div class="p-5 border-b border-purple-50">
            <h3 class="font-display font-semibold text-charcoal">Order Items</h3>
          </div>
          <div class="divide-y divide-purple-50">
            <div v-for="item in order.items" :key="item.id"
              class="flex gap-4 items-center p-5">
              <img :src="item.product_image" :alt="item.product_name"
                class="w-16 h-16 object-cover rounded-xl bg-purple-50">
              <div class="flex-1">
                <p class="font-semibold text-charcoal">{{ item.product_name }}</p>
                <p class="text-muted text-sm">৳{{ Number(item.price).toFixed(2) }} × {{ item.quantity }}</p>
              </div>
              <p class="font-bold text-charcoal">৳{{ Number(item.total).toFixed(2) }}</p>
            </div>
          </div>
          <!-- Totals -->
          <div class="p-5 bg-purple-50/30">
            <div class="space-y-2">
              <div class="flex justify-between text-sm">
                <span class="text-muted">Subtotal</span>
                <span>৳{{ Number(order.subtotal).toFixed(2) }}</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-muted">Delivery</span>
                <span>{{ order.delivery_charge == 0 ? 'FREE' : '৳' + Number(order.delivery_charge).toFixed(2) }}</span>
              </div>
              <div v-if="order.discount > 0" class="flex justify-between text-sm">
                <span class="text-success">Discount</span>
                <span class="text-success">-৳{{ Number(order.discount).toFixed(2) }}</span>
              </div>
              <hr class="border-purple-200">
              <div class="flex justify-between font-bold text-lg">
                <span>Total</span>
                <span class="text-primary">৳{{ Number(order.total).toFixed(2) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Sidebar -->
      <div class="space-y-5">
        <!-- Status Update -->
        <div class="bg-white rounded-2xl shadow-sm border border-purple-50 p-5">
          <h3 class="font-display font-semibold text-charcoal mb-4">Update Status</h3>
          <div class="mb-4">
            <span :class="{
              'badge-warning': order.status === 'pending',
              'badge-success': order.status === 'completed',
              'badge-error': order.status === 'cancelled',
              'badge-purple': !['pending', 'completed', 'cancelled'].includes(order.status),
            }" class="badge">{{ order.status_label }}</span>
          </div>
          <select v-model="newStatus" class="input-field text-sm mb-3">
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="processing">Processing</option>
            <option value="out_for_delivery">Out for Delivery</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
          </select>
          <button @click="updateStatus" :disabled="updating" class="btn-primary w-full text-sm py-2.5">
            {{ updating ? 'Updating...' : 'Update Status' }}
          </button>
        </div>

        <!-- Customer Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-purple-50 p-5">
          <h3 class="font-display font-semibold text-charcoal mb-4">Customer</h3>
          <div class="space-y-2">
            <p class="font-semibold text-charcoal">{{ order.customer.name }}</p>
            <p class="text-muted text-sm">{{ order.customer.email }}</p>
            <p class="text-muted text-sm">{{ order.customer.phone }}</p>
          </div>
        </div>

        <!-- Delivery Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-purple-50 p-5">
          <h3 class="font-display font-semibold text-charcoal mb-4">Delivery Address</h3>
          <p class="text-muted text-sm leading-relaxed">{{ order.delivery_address }}</p>
          <p class="text-muted text-sm">{{ order.delivery_city }}</p>
        </div>

        <!-- Payment -->
        <div class="bg-white rounded-2xl shadow-sm border border-purple-50 p-5">
          <h3 class="font-display font-semibold text-charcoal mb-3">Payment</h3>
          <p class="text-sm"><span class="text-muted">Method:</span> {{ order.payment_method.toUpperCase() }}</p>
          <p class="text-sm mt-1"><span class="text-muted">Status:</span>
            <span :class="order.payment_status === 'paid' ? 'text-success' : 'text-warning'" class="font-semibold ml-1">
              {{ order.payment_status }}
            </span>
          </p>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useToast } from 'vue-toastification'

const props = defineProps({ order: Object })
const toast = useToast()
const newStatus = ref(props.order.status)
const updating = ref(false)

const updateStatus = () => {
  updating.value = true
  router.patch(route('admin.orders.status', props.order.id), { status: newStatus.value }, {
    onSuccess: () => { toast.success('Status updated!'); updating.value = false },
    onError: () => { toast.error('Failed to update.'); updating.value = false },
  })
}
</script>
