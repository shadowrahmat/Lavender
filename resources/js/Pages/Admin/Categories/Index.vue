<template>
  <AdminLayout>
    <Head title="Categories" />

    <div class="space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold font-display text-charcoal">Categories</h1>
          <p class="text-muted text-sm mt-1">Manage product categories</p>
        </div>
        <button @click="openCreate" class="btn-primary flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Add Category
        </button>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-2xl shadow-sm border border-purple-100 overflow-hidden">
        <table class="w-full">
          <thead class="bg-purple-50 border-b border-purple-100">
            <tr>
              <th class="text-left px-6 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Icon</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Name</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Slug</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Products</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Sort</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Status</th>
              <th class="text-right px-6 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-purple-50">
            <tr v-for="cat in categories" :key="cat.id" class="hover:bg-purple-50/30 transition-colors">
              <td class="px-6 py-4 text-2xl">{{ cat.icon || '📦' }}</td>
              <td class="px-6 py-4">
                <p class="font-semibold text-charcoal">{{ cat.name }}</p>
                <p class="text-xs text-muted mt-0.5">{{ cat.description }}</p>
              </td>
              <td class="px-6 py-4 text-sm text-muted font-mono">{{ cat.slug }}</td>
              <td class="px-6 py-4">
                <span class="badge badge-purple">{{ cat.products_count }}</span>
              </td>
              <td class="px-6 py-4 text-sm text-muted">{{ cat.sort_order }}</td>
              <td class="px-6 py-4">
                <span :class="cat.is_active ? 'badge-success' : 'badge-error'" class="badge text-xs">
                  {{ cat.is_active ? 'Active' : 'Hidden' }}
                </span>
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                  <button @click="openEdit(cat)" class="p-2 text-muted hover:text-primary hover:bg-purple-50 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                  </button>
                  <button @click="deleteCategory(cat)" :disabled="cat.products_count > 0"
                    class="p-2 text-muted hover:text-error hover:bg-red-50 rounded-lg transition-colors disabled:opacity-30 disabled:cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="!categories.length" class="py-16 text-center">
          <div class="text-4xl mb-3">📂</div>
          <p class="text-muted">No categories yet.</p>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <Teleport to="body">
      <div v-if="modalOpen" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl">
          <div class="p-6 border-b border-purple-100 flex items-center justify-between">
            <h3 class="font-display font-semibold text-charcoal text-lg">{{ editing ? 'Edit' : 'New' }} Category</h3>
            <button @click="closeModal" class="p-2 text-muted hover:text-charcoal rounded-lg hover:bg-gray-100">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>
          <form @submit.prevent="save" class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div class="col-span-2">
                <label class="block text-sm font-medium text-charcoal mb-1">Name *</label>
                <input v-model="form.name" @input="autoSlug" type="text" class="input-field" required placeholder="e.g. Cake & Pastry">
              </div>
              <div class="col-span-2">
                <label class="block text-sm font-medium text-charcoal mb-1">Slug *</label>
                <input v-model="form.slug" type="text" class="input-field font-mono text-sm" required placeholder="cake-pastry">
              </div>
              <div>
                <label class="block text-sm font-medium text-charcoal mb-1">Icon (emoji)</label>
                <input v-model="form.icon" type="text" class="input-field text-center text-2xl" placeholder="🎂" maxlength="4">
              </div>
              <div>
                <label class="block text-sm font-medium text-charcoal mb-1">Sort Order</label>
                <input v-model.number="form.sort_order" type="number" class="input-field" min="0">
              </div>
              <div class="col-span-2">
                <label class="block text-sm font-medium text-charcoal mb-1">Description</label>
                <textarea v-model="form.description" rows="2" class="input-field resize-none" placeholder="Short description..."></textarea>
              </div>
              <div class="col-span-2 flex items-center gap-3">
                <input v-model="form.is_active" type="checkbox" id="cat_active" class="w-4 h-4 accent-primary">
                <label for="cat_active" class="text-sm font-medium text-charcoal">Active (show in shop)</label>
              </div>
            </div>
            <div class="flex gap-3 pt-2">
              <button type="button" @click="closeModal" class="btn-secondary flex-1">Cancel</button>
              <button type="submit" :disabled="saving" class="btn-primary flex-1">
                {{ saving ? 'Saving...' : 'Save Category' }}
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

const props = defineProps({ categories: Array })

const modalOpen = ref(false)
const editing = ref(null)
const saving = ref(false)
const form = ref({ name: '', slug: '', icon: '', description: '', sort_order: 0, is_active: true })

const openCreate = () => {
  editing.value = null
  form.value = { name: '', slug: '', icon: '', description: '', sort_order: 0, is_active: true }
  modalOpen.value = true
}

const openEdit = (cat) => {
  editing.value = cat
  form.value = { name: cat.name, slug: cat.slug, icon: cat.icon || '', description: cat.description || '', sort_order: cat.sort_order, is_active: cat.is_active }
  modalOpen.value = true
}

const closeModal = () => { modalOpen.value = false }

const autoSlug = () => {
  if (!editing.value) {
    form.value.slug = form.value.name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '')
  }
}

const save = () => {
  saving.value = true
  if (editing.value) {
    router.patch(route('admin.categories.update', editing.value.id), form.value, {
      onFinish: () => { saving.value = false; closeModal() }
    })
  } else {
    router.post(route('admin.categories.store'), form.value, {
      onFinish: () => { saving.value = false; closeModal() }
    })
  }
}

const deleteCategory = (cat) => {
  if (!confirm(`Delete "${cat.name}"?`)) return
  router.delete(route('admin.categories.destroy', cat.id))
}
</script>
