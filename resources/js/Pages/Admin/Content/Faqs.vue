<template>
  <AdminLayout>
    <Head title="FAQ Management" />

    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold font-display text-charcoal">FAQ Management</h1>
          <p class="text-muted text-sm mt-1">Add, edit, and reorder frequently asked questions</p>
        </div>
        <button @click="openCreate" class="btn-primary flex items-center gap-2 text-sm py-2.5 px-5">
          <PlusIcon class="w-4 h-4" /> Add FAQ
        </button>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-2xl shadow-sm border border-purple-100 overflow-hidden">
        <table class="w-full">
          <thead class="bg-purple-50 border-b border-purple-100">
            <tr>
              <th class="text-left px-6 py-4 text-xs font-semibold text-muted uppercase tracking-wider w-10">#</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Question</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-muted uppercase tracking-wider hidden lg:table-cell">Answer Preview</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-muted uppercase tracking-wider w-16">Sort</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-muted uppercase tracking-wider w-24">Status</th>
              <th class="text-right px-6 py-4 text-xs font-semibold text-muted uppercase tracking-wider w-24">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-purple-50">
            <tr v-for="faq in faqs" :key="faq.id" class="hover:bg-purple-50/30 transition-colors">
              <td class="px-6 py-4 text-sm text-muted font-mono">{{ faq.sort_order }}</td>
              <td class="px-6 py-4">
                <p class="font-medium text-charcoal text-sm">{{ faq.question }}</p>
              </td>
              <td class="px-6 py-4 hidden lg:table-cell">
                <p class="text-xs text-muted line-clamp-2 max-w-xs">{{ faq.answer }}</p>
              </td>
              <td class="px-6 py-4">
                <span class="text-sm text-muted">{{ faq.sort_order }}</span>
              </td>
              <td class="px-6 py-4">
                <span class="badge text-xs" :class="faq.is_active ? 'badge-success' : 'badge-error'">
                  {{ faq.is_active ? 'Active' : 'Hidden' }}
                </span>
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-1.5">
                  <button @click="openEdit(faq)"
                    class="p-2 text-muted hover:text-primary hover:bg-purple-50 rounded-lg transition-colors">
                    <PencilIcon class="w-4 h-4" />
                  </button>
                  <button @click="deleteFaq(faq)"
                    class="p-2 text-muted hover:text-error hover:bg-red-50 rounded-lg transition-colors">
                    <TrashIcon class="w-4 h-4" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="!faqs.length" class="py-16 text-center">
          <div class="w-16 h-16 rounded-2xl bg-purple-50 flex items-center justify-center mx-auto mb-4">
            <QuestionMarkCircleIcon class="w-8 h-8 text-primary" />
          </div>
          <p class="text-muted text-sm">No FAQs yet. Add your first one.</p>
        </div>
      </div>
    </div>

    <!-- Create / Edit Modal -->
    <Teleport to="body">
      <div v-if="modalOpen" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-xl shadow-2xl">
          <div class="p-6 border-b border-purple-100 flex items-center justify-between">
            <h3 class="font-display font-semibold text-charcoal text-lg">
              {{ editing ? 'Edit FAQ' : 'New FAQ' }}
            </h3>
            <button @click="closeModal" class="p-2 text-muted hover:text-charcoal rounded-lg hover:bg-gray-100">
              <XMarkIcon class="w-5 h-5" />
            </button>
          </div>
          <form @submit.prevent="save" class="p-6 space-y-4">
            <div>
              <label class="block text-sm font-medium text-charcoal mb-1.5">Question *</label>
              <input v-model="form.question" type="text" class="input-field" required
                placeholder="e.g. Do you offer delivery?">
            </div>
            <div>
              <label class="block text-sm font-medium text-charcoal mb-1.5">Answer *</label>
              <textarea v-model="form.answer" rows="4" class="input-field resize-none" required
                placeholder="Write the answer here..."></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-charcoal mb-1.5">Sort Order</label>
                <input v-model.number="form.sort_order" type="number" class="input-field" min="0">
              </div>
              <div class="flex items-end pb-1">
                <label class="flex items-center gap-2.5 cursor-pointer">
                  <input v-model="form.is_active" type="checkbox" class="w-4 h-4 accent-primary rounded">
                  <span class="text-sm font-medium text-charcoal">Active (show on site)</span>
                </label>
              </div>
            </div>
            <div class="flex gap-3 pt-2">
              <button type="button" @click="closeModal" class="btn-secondary flex-1 py-2.5 text-sm">Cancel</button>
              <button type="submit" :disabled="saving" class="btn-primary flex-1 py-2.5 text-sm">
                {{ saving ? 'Saving…' : (editing ? 'Update FAQ' : 'Add FAQ') }}
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
import { PlusIcon, PencilIcon, TrashIcon, XMarkIcon, QuestionMarkCircleIcon } from '@heroicons/vue/24/outline'

const props = defineProps({ faqs: Array })

const modalOpen = ref(false)
const editing  = ref(null)
const saving   = ref(false)
const form     = ref({ question: '', answer: '', sort_order: 0, is_active: true })

const openCreate = () => {
  editing.value = null
  form.value = { question: '', answer: '', sort_order: props.faqs.length, is_active: true }
  modalOpen.value = true
}

const openEdit = (faq) => {
  editing.value = faq
  form.value = { question: faq.question, answer: faq.answer, sort_order: faq.sort_order, is_active: faq.is_active }
  modalOpen.value = true
}

const closeModal = () => { modalOpen.value = false }

const save = () => {
  saving.value = true
  if (editing.value) {
    router.patch(route('admin.faqs.update', editing.value.id), form.value, {
      onFinish: () => { saving.value = false; closeModal() },
    })
  } else {
    router.post(route('admin.faqs.store'), form.value, {
      onFinish: () => { saving.value = false; closeModal() },
    })
  }
}

const deleteFaq = (faq) => {
  if (!confirm(`Delete this FAQ?\n\n"${faq.question}"`)) return
  router.delete(route('admin.faqs.destroy', faq.id))
}
</script>
