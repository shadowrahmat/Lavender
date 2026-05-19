<template>
  <AdminLayout>
    <Head title="Contact Messages" />

    <div class="space-y-6">
      <div>
        <h1 class="text-2xl font-bold font-display text-charcoal">Contact Messages</h1>
        <p class="text-muted text-sm mt-1">Customer messages from the contact form</p>
      </div>

      <div class="space-y-4">
        <div v-for="msg in messages.data" :key="msg.id"
          :class="msg.is_read ? 'bg-white opacity-75' : 'bg-white border-l-4 border-primary'"
          class="rounded-2xl shadow-sm border border-purple-100 p-6">
          <div class="flex items-start justify-between gap-4">
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-full gradient-purple flex items-center justify-center text-white font-bold shrink-0">
                  {{ msg.name[0].toUpperCase() }}
                </div>
                <div>
                  <p class="font-semibold text-charcoal">{{ msg.name }}</p>
                  <div class="flex items-center gap-3 text-sm text-muted">
                    <a :href="'mailto:' + msg.email" class="hover:text-primary">{{ msg.email }}</a>
                    <span v-if="msg.phone">· {{ msg.phone }}</span>
                  </div>
                </div>
                <span v-if="!msg.is_read" class="ml-auto badge badge-purple text-xs">New</span>
              </div>
              <p class="font-semibold text-charcoal mb-2">{{ msg.subject }}</p>
              <p class="text-muted text-sm leading-relaxed">{{ msg.message }}</p>
              <p class="text-xs text-muted mt-3">{{ msg.created_at }}</p>
            </div>
            <div class="flex flex-col gap-2 shrink-0">
              <button v-if="!msg.is_read" @click="markRead(msg)"
                class="px-3 py-1.5 bg-primary text-white text-xs rounded-lg hover:bg-plum transition-colors">
                Mark Read
              </button>
              <a :href="'mailto:' + msg.email" class="px-3 py-1.5 border border-purple-200 text-primary text-xs rounded-lg hover:bg-purple-50 transition-colors text-center">
                Reply
              </a>
            </div>
          </div>
        </div>

        <div v-if="!messages.data?.length" class="py-16 text-center bg-white rounded-2xl border border-purple-100">
          <div class="text-4xl mb-3">📭</div>
          <p class="text-muted">No messages yet.</p>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="messages.last_page > 1" class="flex justify-center gap-2">
        <Link v-for="page in messages.links" :key="page.label"
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

defineProps({ messages: Object })

const markRead = (msg) => {
  router.patch(route('admin.messages.contacts.read', msg.id))
}
</script>
