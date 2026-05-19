<template>
  <AdminLayout>
    <Head title="Coupons" />

    <div class="space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold font-display text-charcoal">Coupons</h1>
          <p class="text-muted text-sm mt-1">Manage discount coupons</p>
        </div>
        <button @click="openCreate" class="btn-primary flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          New Coupon
        </button>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-2xl shadow-sm border border-purple-100 overflow-hidden">
        <table class="w-full text-sm">
          <thead class="bg-purple-50 border-b border-purple-100">
            <tr>
              <th class="text-left px-6 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Code</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Type / Value</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Min. Order</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Usage</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Expires</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Status</th>
              <th class="text-right px-6 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-purple-50">
            <tr v-for="coupon in coupons" :key="coupon.id" class="hover:bg-purple-50/30 transition-colors">
              <td class="px-6 py-4">
                <span class="font-mono font-bold text-primary bg-purple-50 px-3 py-1 rounded-lg text-sm">{{ coupon.code }}</span>
              </td>
              <td class="px-6 py-4 text-charcoal font-medium">
                {{ coupon.type === 'percentage' ? coupon.value + '%' : '৳' + coupon.value }} off
              </td>
              <td class="px-6 py-4 text-muted">৳{{ coupon.minimum_order || 0 }}</td>
              <td class="px-6 py-4 text-muted">
                {{ coupon.used_count }} / {{ coupon.usage_limit || '∞' }}
              </td>
              <td class="px-6 py-4 text-muted">{{ coupon.expires_at || 'No expiry' }}</td>
              <td class="px-6 py-4">
                <span :class="coupon.is_active ? 'badge-success' : 'badge-error'" class="badge text-xs">
                  {{ coupon.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                  <button @click="openEdit(coupon)" class="p-2 text-muted hover:text-primary hover:bg-purple-50 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                  </button>
                  <button @click="deleteCoupon(coupon)" class="p-2 text-muted hover:text-error hover:bg-red-50 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="!coupons.length" class="py-16 text-center">
          <div class="text-4xl mb-3">🎟️</div>
          <p class="text-muted">No coupons yet.</p>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <Teleport to="body">
      <div v-if="modalOpen" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl">
          <div class="p-6 border-b border-purple-100 flex items-center justify-between">
            <h3 class="font-display font-semibold text-charcoal text-lg">{{ editing ? 'Edit' : 'New' }} Coupon</h3>
            <button @click="closeModal" class="p-2 text-muted hover:text-charcoal rounded-lg hover:bg-gray-100">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>
          <form @submit.prevent="save" class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div class="col-span-2">
                <label class="block text-sm font-medium text-charcoal mb-1">Coupon Code *</label>
                <input v-model="form.code" type="text" class="input-field uppercase font-mono font-bold" required placeholder="LAVENDER10">
              </div>
              <div>
                <label class="block text-sm font-medium text-charcoal mb-1">Type *</label>
                <select v-model="form.type" class="input-field">
                  <option value="percentage">Percentage (%)</option>
                  <option value="fixed">Fixed Amount (৳)</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-charcoal mb-1">Value *</label>
                <input v-model.number="form.value" type="number" class="input-field" required min="0" step="0.01"
                  :placeholder="form.type === 'percentage' ? 'e.g. 10' : 'e.g. 50'">
              </div>
              <div>
                <label class="block text-sm font-medium text-charcoal mb-1">Minimum Order (৳)</label>
                <input v-model.number="form.minimum_order" type="number" class="input-field" min="0" step="0.01" placeholder="0">
              </div>
              <div>
                <label class="block text-sm font-medium text-charcoal mb-1">Usage Limit</label>
                <input v-model.number="form.usage_limit" type="number" class="input-field" min="1" placeholder="Unlimited">
              </div>
              <div class="col-span-2">
                <label class="block text-sm font-medium text-charcoal mb-1">Expiry Date</label>
                <input v-model="form.expires_at" type="date" class="input-field">
              </div>
              <div class="col-span-2 flex items-center gap-3">
                <input v-model="form.is_active" type="checkbox" id="coupon_active" class="w-4 h-4 accent-primary">
                <label for="coupon_active" class="text-sm font-medium text-charcoal">Active</label>
              </div>
            </div>
            <div class="flex gap-3 pt-2">
              <button type="button" @click="closeModal" class="btn-secondary flex-1">Cancel</button>
              <button type="submit" :disabled="saving" class="btn-primary flex-1">
                {{ saving ? 'Saving...' : 'Save Coupon' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </Teleport>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineProps({ coupons: Array })

const modalOpen = ref(false)
const editing = ref(null)
const saving = ref(false)
const form = ref({ code: '', type: 'percentage', value: '', minimum_order: '', usage_limit: '', expires_at: '', is_active: true })

const openCreate = () => {
  editing.value = null
  form.value = { code: '', type: 'percentage', value: '', minimum_order: '', usage_limit: '', expires_at: '', is_active: true }
  modalOpen.value = true
}

const openEdit = (coupon) => {
  editing.value = coupon
  form.value = {
    code: coupon.code, type: coupon.type, value: coupon.value,
    minimum_order: coupon.minimum_order, usage_limit: coupon.usage_limit,
    expires_at: coupon.expires_at, is_active: coupon.is_active
  }
  modalOpen.value = true
}

const closeModal = () => { modalOpen.value = false }

const save = () => {
  saving.value = true
  if (editing.value) {
    router.patch(route('admin.coupons.update', editing.value.id), form.value, {
      onFinish: () => { saving.value = false; closeModal() }
    })
  } else {
    router.post(route('admin.coupons.store'), form.value, {
      onFinish: () => { saving.value = false; closeModal() }
    })
  }
}

const deleteCoupon = (coupon) => {
  if (!confirm(`Delete coupon "${coupon.code}"?`)) return
  router.delete(route('admin.coupons.destroy', coupon.id))
}
</script>
