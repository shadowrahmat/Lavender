<template>
  <AdminLayout>
    <Head :title="slug === 'terms' ? 'Terms & Conditions' : 'Privacy Policy'" />

    <div class="max-w-3xl space-y-6">

      <!-- Header -->
      <div class="flex items-center gap-4">
        <div class="flex rounded-xl border border-purple-100 overflow-hidden text-sm font-medium">
          <Link
            :href="route('admin.pages.edit', 'terms')"
            class="px-4 py-2 transition-colors"
            :class="slug === 'terms' ? 'bg-primary text-white' : 'text-muted hover:text-charcoal hover:bg-purple-50'"
          >Terms & Conditions</Link>
          <Link
            :href="route('admin.pages.edit', 'privacy')"
            class="px-4 py-2 transition-colors border-l border-purple-100"
            :class="slug === 'privacy' ? 'bg-primary text-white' : 'text-muted hover:text-charcoal hover:bg-purple-50'"
          >Privacy Policy</Link>
        </div>
      </div>

      <form @submit.prevent="save" class="space-y-5">

        <!-- Meta -->
        <div class="bg-white rounded-2xl shadow-sm border border-purple-100 p-6 space-y-4">
          <h3 class="font-display font-semibold text-charcoal border-b border-purple-50 pb-3">Page Info</h3>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-charcoal mb-1.5">Page Title</label>
              <input v-model="form.title" type="text" class="input-field" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-charcoal mb-1.5">Last Updated</label>
              <input v-model="form.last_updated" type="text" class="input-field" placeholder="e.g. May 2026">
            </div>
          </div>
        </div>

        <!-- Sections -->
        <div class="bg-white rounded-2xl shadow-sm border border-purple-100 p-6 space-y-4">
          <div class="flex items-center justify-between border-b border-purple-50 pb-3">
            <h3 class="font-display font-semibold text-charcoal">Sections</h3>
            <button type="button" @click="addSection"
              class="flex items-center gap-1.5 text-sm text-primary font-medium hover:underline">
              <PlusIcon class="w-4 h-4" /> Add Section
            </button>
          </div>

          <div v-if="!form.sections.length" class="py-8 text-center text-muted text-sm">
            No sections yet. Click "Add Section" to start.
          </div>

          <div v-for="(section, i) in form.sections" :key="i"
            class="border border-purple-100 rounded-xl p-4 space-y-3 relative">
            <button type="button" @click="removeSection(i)"
              class="absolute top-3 right-3 p-1.5 text-muted hover:text-error hover:bg-red-50 rounded-lg transition-colors">
              <XMarkIcon class="w-4 h-4" />
            </button>
            <div>
              <label class="block text-xs font-medium text-muted mb-1 uppercase tracking-wide">Heading</label>
              <input v-model="section.heading" type="text" class="input-field text-sm" required
                placeholder="e.g. 1. Acceptance of Terms">
            </div>
            <div>
              <label class="block text-xs font-medium text-muted mb-1 uppercase tracking-wide">Content</label>
              <textarea v-model="section.body" rows="3" class="input-field text-sm resize-none" required
                placeholder="Write section content..."></textarea>
            </div>
            <div class="flex items-center gap-2 text-xs text-muted">
              <button v-if="i > 0" type="button" @click="moveUp(i)"
                class="px-2 py-1 rounded-lg hover:bg-purple-50 hover:text-primary transition-colors">↑ Move Up</button>
              <button v-if="i < form.sections.length - 1" type="button" @click="moveDown(i)"
                class="px-2 py-1 rounded-lg hover:bg-purple-50 hover:text-primary transition-colors">↓ Move Down</button>
            </div>
          </div>
        </div>

        <!-- Save -->
        <div class="flex justify-end">
          <button type="submit" :disabled="saving" class="btn-primary py-2.5 px-8 text-sm">
            {{ saving ? 'Saving…' : 'Save Page' }}
          </button>
        </div>

      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { PlusIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  slug: String,
  page: Object,
})

const saving = ref(false)

const form = ref({
  title:        props.page?.title        ?? (props.slug === 'terms' ? 'Terms & Conditions' : 'Privacy Policy'),
  last_updated: props.page?.last_updated ?? '',
  sections:     props.page?.sections     ?? [],
})

const addSection    = () => form.value.sections.push({ heading: '', body: '' })
const removeSection = (i) => form.value.sections.splice(i, 1)
const moveUp        = (i) => { const s = form.value.sections; [s[i - 1], s[i]] = [s[i], s[i - 1]] }
const moveDown      = (i) => { const s = form.value.sections; [s[i], s[i + 1]] = [s[i + 1], s[i]] }

const save = () => {
  saving.value = true
  router.post(route('admin.pages.update', props.slug), form.value, {
    onFinish: () => { saving.value = false },
  })
}
</script>
