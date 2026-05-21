<template>
  <AdminLayout>
    <Head title="Corporate Inquiries" />

    <div class="space-y-6">
      <div>
        <h1 class="text-2xl font-bold font-display text-charcoal">Corporate Inquiries</h1>
        <p class="text-muted text-sm mt-1">Bulk and event order inquiries</p>
      </div>

      <div class="space-y-4">
        <div v-for="inq in inquiries.data" :key="inq.id"
          :class="inq.is_read ? 'bg-white opacity-75' : 'bg-white border-l-4 border-primary'"
          class="rounded-2xl shadow-sm border border-purple-100 p-6">
          <div class="flex items-start justify-between gap-4">
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-3 mb-3 flex-wrap">
                <div>
                  <p class="font-semibold text-charcoal text-lg">{{ inq.company_name }}</p>
                  <p class="text-sm text-muted">Contact: {{ inq.contact_name }}</p>
                </div>
                <span v-if="!inq.is_read" class="badge badge-purple text-xs">New</span>
              </div>

              <div class="grid grid-cols-2 md:grid-cols-3 gap-3 text-sm mb-4">
                <div class="bg-purple-50 rounded-xl p-3">
                  <p class="text-xs text-muted mb-1">Event Type</p>
                  <p class="font-semibold text-charcoal capitalize">{{ inq.event_type }}</p>
                </div>
                <div class="bg-purple-50 rounded-xl p-3">
                  <p class="text-xs text-muted mb-1">Expected Qty</p>
                  <p class="font-semibold text-charcoal">{{ inq.expected_quantity }} pcs</p>
                </div>
                <div class="bg-purple-50 rounded-xl p-3">
                  <p class="text-xs text-muted mb-1">Required Date</p>
                  <p class="font-semibold text-charcoal">{{ inq.required_date || 'Not specified' }}</p>
                </div>
              </div>

              <p class="text-sm text-muted">{{ inq.requirements }}</p>

              <!-- Design files -->
              <div v-if="inq.design_files?.length" class="mt-3">
                <p class="text-xs font-semibold text-charcoal mb-2">Design Files ({{ inq.design_files.length }})</p>
                <div class="flex flex-wrap gap-2">
                  <a v-for="(path, fi) in inq.design_files" :key="fi"
                    :href="'/storage/' + path"
                    target="_blank"
                    class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-purple-50 border border-purple-200 text-xs text-primary hover:bg-purple-100 transition-colors">
                    <PaperClipIcon class="w-3.5 h-3.5 shrink-0" />
                    {{ fileName(path) }}
                  </a>
                </div>
              </div>

              <div class="flex items-center gap-4 mt-3 text-xs text-muted">
                <a :href="'mailto:' + inq.email" class="hover:text-primary">{{ inq.email }}</a>
                <span>{{ inq.phone }}</span>
                <span>{{ inq.created_at }}</span>
              </div>
            </div>

            <div class="flex flex-col gap-2 shrink-0">
              <button v-if="!inq.is_read" @click="markRead(inq)"
                class="px-3 py-1.5 bg-primary text-white text-xs rounded-lg hover:bg-plum transition-colors">
                Mark Read
              </button>
              <a :href="'mailto:' + inq.email" class="px-3 py-1.5 border border-purple-200 text-primary text-xs rounded-lg hover:bg-purple-50 transition-colors text-center">
                Reply
              </a>
            </div>
          </div>
        </div>

        <div v-if="!inquiries.data?.length" class="py-16 text-center bg-white rounded-2xl border border-purple-100">
          <div class="text-4xl mb-3">🏢</div>
          <p class="text-muted">No corporate inquiries yet.</p>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="inquiries.last_page > 1" class="flex justify-center gap-2">
        <Link v-for="page in inquiries.links" :key="page.label"
          :href="page.url || '#'"
          :class="page.active ? 'bg-primary text-white' : 'bg-white text-charcoal hover:bg-purple-50'"
          class="px-3 py-1.5 rounded-lg text-sm border border-purple-100 transition-colors"
          v-html="page.label" />
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { PaperClipIcon } from '@heroicons/vue/24/outline'

defineProps({ inquiries: Object })

const markRead = (inq) => {
  router.patch(route('admin.messages.corporate.read', inq.id))
}

const fileName = (path) => path.split('/').pop()
</script>
