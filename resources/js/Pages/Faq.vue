<template>
  <AppLayout>
    <Head title="FAQ" />
    <div class="gradient-soft py-16 text-center mb-16">
      <h1 class="font-display text-4xl md:text-5xl font-bold text-charcoal mb-4">Frequently Asked Questions</h1>
      <p class="text-muted text-lg">Everything you need to know about Lavender Food & Bakery.</p>
    </div>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
      <div class="space-y-4">
        <div v-for="(faq, i) in faqs" :key="i" class="card overflow-hidden">
          <button @click="openIndex = openIndex === i ? null : i"
            class="w-full flex items-center justify-between p-6 text-left">
            <span class="font-semibold text-charcoal pr-4">{{ faq.q }}</span>
            <svg :class="openIndex === i ? 'rotate-180' : ''"
              class="w-5 h-5 text-primary shrink-0 transition-transform"
              fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </button>
          <div v-show="openIndex === i" class="px-6 pb-6 text-muted text-sm leading-relaxed border-t border-purple-50 pt-4">
            {{ faq.a }}
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
<script setup>
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const openIndex = ref(null)
const faqs = [
  { q: 'Where is Lavender Food & Bakery located?', a: 'We are located at AJ Height, Uttar Badda, Cha-72, 1/D Progoti Shoroni, Dhaka 1212. You can visit us daily from 9:00 AM to 11:00 PM.' },
  { q: 'Do you offer delivery?', a: 'Yes, we offer delivery to most areas in Dhaka. Delivery charge is ৳60, and orders above ৳500 get free delivery.' },
  { q: 'Can I pre-order a custom cake?', a: 'Absolutely! We accept custom cake orders for birthdays, anniversaries, and events. Please use our corporate order form or contact us directly at least 48 hours in advance.' },
  { q: 'Are your bakery items fresh every day?', a: 'Yes! All our bakery items are prepared fresh daily. We start baking early in the morning to ensure freshness for our customers.' },
  { q: 'Do you support corporate or bulk orders?', a: 'Yes, we have a dedicated corporate order service. We supply bakery boxes, snacks, and pastries for offices, events, and corporate gatherings. Please use our corporate order form.' },
  { q: 'What payment methods do you accept?', a: 'We currently accept Cash on Delivery (COD). bKash and Nagad payment options are coming soon.' },
  { q: 'How do I track my order?', a: 'After placing your order, you will receive an order number. Use our Order Tracking page to check the current status of your order.' },
  { q: 'Do you have Ramadan specials?', a: 'Yes! During Ramadan, we offer a special iftar menu with traditional sweets and snacks. Check our Ramadan Menu category in the shop.' },
]
</script>
