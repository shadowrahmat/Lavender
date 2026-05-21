<template>
  <AppLayout>
    <Head title="Terms & Conditions" />
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <h1 class="font-display text-4xl font-bold text-charcoal mb-4">{{ title }}</h1>
      <p class="text-muted mb-10">Last updated: {{ lastUpdated }}</p>
      <div class="space-y-6 text-muted leading-relaxed">
        <section v-for="(section, i) in sections" :key="i">
          <h2 class="font-display text-2xl font-semibold text-charcoal mb-3">{{ section.heading }}</h2>
          <p>{{ section.body }}</p>
        </section>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({ page: Object })

const fallbackSections = [
  { heading: '1. Acceptance of Terms', body: 'By using our website and services, you agree to these Terms & Conditions. If you do not agree, please do not use our services.' },
  { heading: '2. Orders & Payment', body: 'All orders are subject to availability. We reserve the right to cancel or refuse any order. Payment must be completed before order fulfillment for online payment methods.' },
  { heading: '3. Delivery', body: 'Delivery times are estimates and may vary based on location and demand. We strive to deliver within the estimated time but cannot guarantee exact delivery times.' },
  { heading: '4. Refund Policy', body: 'Due to the perishable nature of bakery items, we do not accept returns. If there is a quality issue, please contact us within 2 hours of delivery.' },
  { heading: '5. Contact', body: 'For any queries regarding these terms, please contact us at hello@lavenderfoodnbakery.com or +8801730-728954.' },
]

const title       = computed(() => props.page?.title ?? 'Terms & Conditions')
const lastUpdated = computed(() => props.page?.last_updated ?? 'January 2024')
const sections    = computed(() => props.page?.sections?.length ? props.page.sections : fallbackSections)
</script>
