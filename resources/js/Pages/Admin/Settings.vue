<template>
  <AdminLayout>
    <Head title="Settings" />

    <div class="max-w-3xl space-y-6">
      <div>
        <h1 class="text-2xl font-bold font-display text-charcoal">Website Settings</h1>
        <p class="text-muted text-sm mt-1">Manage global site configuration</p>
      </div>

      <form @submit.prevent="save" class="space-y-6">
        <!-- General -->
        <div class="bg-white rounded-2xl shadow-sm border border-purple-100 p-6 space-y-4">
          <h3 class="font-display font-semibold text-charcoal text-lg border-b border-purple-100 pb-3">General</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-charcoal mb-1">Site Name</label>
              <input v-model="form.site_name" type="text" class="input-field">
            </div>
            <div>
              <label class="block text-sm font-medium text-charcoal mb-1">Tagline</label>
              <input v-model="form.site_tagline" type="text" class="input-field">
            </div>
          </div>
          <div class="flex items-center gap-3 p-4 bg-yellow-50 rounded-xl border border-yellow-200">
            <input v-model="form.maintenance_mode" type="checkbox" id="maintenance" class="w-4 h-4 accent-warning">
            <label for="maintenance" class="text-sm font-medium text-charcoal">Enable Maintenance Mode</label>
          </div>
        </div>

        <!-- Contact -->
        <div class="bg-white rounded-2xl shadow-sm border border-purple-100 p-6 space-y-4">
          <h3 class="font-display font-semibold text-charcoal text-lg border-b border-purple-100 pb-3">Contact</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-charcoal mb-1">Email</label>
              <input v-model="form.email" type="email" class="input-field">
            </div>
            <div>
              <label class="block text-sm font-medium text-charcoal mb-1">Phone 1</label>
              <input v-model="form.phone_1" type="text" class="input-field">
            </div>
            <div>
              <label class="block text-sm font-medium text-charcoal mb-1">Phone 2</label>
              <input v-model="form.phone_2" type="text" class="input-field">
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-charcoal mb-1">Address</label>
              <textarea v-model="form.address" rows="2" class="input-field resize-none"></textarea>
            </div>
          </div>
        </div>

        <!-- Delivery -->
        <div class="bg-white rounded-2xl shadow-sm border border-purple-100 p-6 space-y-4">
          <h3 class="font-display font-semibold text-charcoal text-lg border-b border-purple-100 pb-3">Delivery</h3>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-charcoal mb-1">Delivery Charge (৳)</label>
              <input v-model.number="form.delivery_charge" type="number" class="input-field" min="0" step="0.01">
            </div>
            <div>
              <label class="block text-sm font-medium text-charcoal mb-1">Free Delivery Above (৳)</label>
              <input v-model.number="form.free_delivery_above" type="number" class="input-field" min="0" step="0.01">
            </div>
          </div>
        </div>

        <!-- Social -->
        <div class="bg-white rounded-2xl shadow-sm border border-purple-100 p-6 space-y-4">
          <h3 class="font-display font-semibold text-charcoal text-lg border-b border-purple-100 pb-3">Social Media</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-charcoal mb-1">Facebook URL</label>
              <input v-model="form.facebook" type="url" class="input-field" placeholder="https://facebook.com/...">
            </div>
            <div>
              <label class="block text-sm font-medium text-charcoal mb-1">Instagram URL</label>
              <input v-model="form.instagram" type="url" class="input-field" placeholder="https://instagram.com/...">
            </div>
          </div>
        </div>

        <div class="flex justify-end">
          <button type="submit" :disabled="saving" class="btn-primary px-8">
            {{ saving ? 'Saving...' : 'Save All Settings' }}
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ settings: Object })

const saving = ref(false)
const form = ref({
  site_name: props.settings?.site_name || 'Lavender Food & Bakery',
  site_tagline: props.settings?.site_tagline || '',
  email: props.settings?.email || '',
  phone_1: props.settings?.phone_1 || '',
  phone_2: props.settings?.phone_2 || '',
  address: props.settings?.address || '',
  delivery_charge: props.settings?.delivery_charge || 60,
  free_delivery_above: props.settings?.free_delivery_above || 500,
  facebook: props.settings?.facebook || '',
  instagram: props.settings?.instagram || '',
  maintenance_mode: props.settings?.maintenance_mode === '1' || false,
})

const save = () => {
  saving.value = true
  router.post(route('admin.settings.update'), form.value, {
    onFinish: () => { saving.value = false }
  })
}
</script>
