<template>
  <AdminLayout>
    <Head title="Orders" />

    <div class="flex items-center justify-between mb-6">
      <h1 class="font-display text-2xl font-bold text-charcoal">Orders</h1>
      <div class="flex gap-3">
        <select v-model="statusFilter" @change="applyFilter" class="input-field text-sm w-44 py-2">
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
                  'badge-error':   order.status === 'cancelled',
                  'badge-purple':  !['pending','completed','cancelled'].includes(order.status),
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
              <td class="px-5 py-4 text-right">
                <button @click="openOrder(order.id)"
                  class="text-xs text-primary px-3 py-1.5 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                  View
                </button>
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

    <!-- ── Order View Modal ── -->
    <Teleport to="body">
      <Transition name="modal">
        <div v-if="showOrder" class="fixed inset-0 z-50 flex items-center justify-center p-4">

          <!-- Backdrop -->
          <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeOrder"></div>

          <!-- Modal card -->
          <div class="relative w-full max-w-3xl max-h-[92vh] bg-white rounded-3xl shadow-2xl flex flex-col overflow-hidden">

            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-purple-50 shrink-0">
              <div v-if="order">
                <h2 class="font-semibold text-charcoal text-lg">{{ order.order_number }}</h2>
                <p class="text-muted text-xs mt-0.5">{{ order.created_at }}</p>
              </div>
              <div v-else class="h-10 w-48 bg-purple-50 rounded-xl animate-pulse"></div>
              <button @click="closeOrder" class="w-9 h-9 rounded-full hover:bg-purple-50 flex items-center justify-center transition-colors text-muted hover:text-charcoal ml-4">
                <XMarkIcon class="w-5 h-5" />
              </button>
            </div>

            <!-- Loading skeleton -->
            <div v-if="loadingOrder" class="flex-1 p-6 space-y-4">
              <div v-for="i in 4" :key="i" class="h-16 bg-purple-50 rounded-2xl animate-pulse"></div>
            </div>

            <!-- Content -->
            <div v-else-if="order" class="flex-1 overflow-y-auto">
              <div class="grid md:grid-cols-3 gap-0 divide-y md:divide-y-0 md:divide-x divide-purple-50">

                <!-- Left: Items + totals -->
                <div class="md:col-span-2 p-6 space-y-4">

                  <!-- Status update -->
                  <div class="flex items-center gap-3 p-4 bg-purple-50 rounded-2xl">
                    <div class="flex-1">
                      <p class="text-xs font-semibold text-muted uppercase tracking-wider mb-1.5">Update Status</p>
                      <select v-model="newStatus" class="input-field text-sm py-2">
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="processing">Processing</option>
                        <option value="out_for_delivery">Out for Delivery</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                      </select>
                    </div>
                    <button @click="updateStatus" :disabled="updating"
                      class="btn-primary text-sm px-5 py-2.5 mt-5 shrink-0">
                      {{ updating ? 'Saving…' : 'Update' }}
                    </button>
                  </div>

                  <!-- Items -->
                  <div class="bg-white border border-purple-50 rounded-2xl overflow-hidden">
                    <p class="px-4 py-3 text-xs font-semibold text-muted uppercase tracking-wider border-b border-purple-50">Order Items</p>
                    <div class="divide-y divide-purple-50">
                      <div v-for="item in order.items" :key="item.id" class="flex items-center gap-3 px-4 py-3">
                        <img :src="item.product_image" :alt="item.product_name"
                          class="w-12 h-12 object-cover rounded-xl bg-purple-50 shrink-0">
                        <div class="flex-1 min-w-0">
                          <p class="font-medium text-charcoal text-sm truncate">{{ item.product_name }}</p>
                          <p class="text-muted text-xs">৳{{ Number(item.price).toFixed(2) }} × {{ item.quantity }}</p>
                        </div>
                        <p class="font-bold text-charcoal text-sm shrink-0">৳{{ Number(item.total).toFixed(2) }}</p>
                      </div>
                    </div>
                  </div>

                  <!-- Totals -->
                  <div class="bg-purple-50/40 rounded-2xl p-4 space-y-2">
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
                    <div class="flex justify-between font-bold text-base">
                      <span>Total</span>
                      <span class="text-primary">৳{{ Number(order.total).toFixed(2) }}</span>
                    </div>
                  </div>
                </div>

                <!-- Right: Customer + Delivery + Payment -->
                <div class="p-6 space-y-5">

                  <!-- Current status badge -->
                  <div>
                    <p class="text-xs font-semibold text-muted uppercase tracking-wider mb-2">Current Status</p>
                    <span :class="{
                      'badge-warning': order.status === 'pending',
                      'badge-success': order.status === 'completed',
                      'badge-error':   order.status === 'cancelled',
                      'badge-purple':  !['pending','completed','cancelled'].includes(order.status),
                    }" class="badge text-xs">{{ order.status_label }}</span>
                  </div>

                  <!-- Customer -->
                  <div>
                    <p class="text-xs font-semibold text-muted uppercase tracking-wider mb-2">Customer</p>
                    <p class="font-semibold text-charcoal text-sm">{{ order.customer.name }}</p>
                    <p class="text-muted text-xs mt-0.5">{{ order.customer.email }}</p>
                    <p class="text-muted text-xs">{{ order.customer.phone }}</p>
                  </div>

                  <!-- Delivery -->
                  <div>
                    <p class="text-xs font-semibold text-muted uppercase tracking-wider mb-2">Delivery</p>
                    <p class="text-muted text-sm leading-relaxed">{{ order.delivery_address }}</p>
                    <p class="text-muted text-sm">{{ order.delivery_city }}</p>
                  </div>

                  <!-- Payment -->
                  <div>
                    <p class="text-xs font-semibold text-muted uppercase tracking-wider mb-2">Payment</p>
                    <p class="text-sm text-charcoal uppercase font-medium">{{ order.payment_method }}</p>
                    <span :class="order.payment_status === 'paid' ? 'badge-success' : 'badge-warning'"
                      class="badge text-xs mt-1">{{ order.payment_status }}</span>
                  </div>

                  <!-- Notes -->
                  <div v-if="order.notes">
                    <p class="text-xs font-semibold text-muted uppercase tracking-wider mb-2">Notes</p>
                    <p class="text-muted text-sm leading-relaxed">{{ order.notes }}</p>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </Transition>
    </Teleport>

  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useToast } from 'vue-toastification'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import axios from 'axios'

const props = defineProps({
  orders: Object,
  filters: Object,
})

const toast = useToast()
const statusFilter = ref(props.filters?.status || '')

const applyFilter = () => {
  router.get(route('admin.orders.index'), { status: statusFilter.value }, { preserveState: true })
}

// ── Order Modal ──
const showOrder    = ref(false)
const loadingOrder = ref(false)
const order        = ref(null)
const newStatus    = ref('')
const updating     = ref(false)

const openOrder = async (id) => {
  showOrder.value    = true
  loadingOrder.value = true
  order.value        = null
  document.body.style.overflow = 'hidden'

  try {
    const { data } = await axios.get(route('admin.orders.data', id))
    order.value    = data
    newStatus.value = data.status
  } catch {
    toast.error('Failed to load order.')
    closeOrder()
  } finally {
    loadingOrder.value = false
  }
}

const closeOrder = () => {
  showOrder.value = false
  order.value     = null
  document.body.style.overflow = ''
}

const updateStatus = () => {
  if (!order.value) return
  updating.value = true
  router.patch(route('admin.orders.status', order.value.id), { status: newStatus.value }, {
    preserveScroll: true,
    onSuccess: () => {
      toast.success('Status updated!')
      order.value.status = newStatus.value
      updating.value = false
      // refresh the table row in background
      router.reload({ only: ['orders'] })
    },
    onError: () => {
      toast.error('Failed to update status.')
      updating.value = false
    },
  })
}
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active { transition: opacity 0.25s ease; }
.modal-enter-from,
.modal-leave-to    { opacity: 0; }

.modal-enter-active .relative,
.modal-leave-active .relative { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
.modal-enter-from .relative    { transform: scale(0.95); }
.modal-leave-to .relative      { transform: scale(0.95); }
</style>
