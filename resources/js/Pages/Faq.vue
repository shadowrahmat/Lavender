<template>
  <AppLayout>
    <Head title="FAQ" />

    <div class="gradient-soft py-16 text-center mb-16">
      <h1 class="font-display text-4xl md:text-5xl font-bold text-charcoal mb-4">Frequently Asked Questions</h1>
      <p class="text-muted text-lg">Everything you need to know about Lavender Food & Bakery.</p>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
      <div class="space-y-3">
        <div
          v-for="(faq, i) in faqs" :key="i"
          class="faq-card"
          :class="{ 'faq-card-open': openIndex === i }"
        >
          <!-- Question row -->
          <button
            @click="openIndex = openIndex === i ? null : i"
            class="w-full flex items-center gap-4 p-5 text-left group"
          >
            <span class="faq-number shrink-0" :class="openIndex === i ? 'faq-number-active' : ''">
              {{ String(i + 1).padStart(2, '0') }}
            </span>
            <span
              class="flex-1 font-semibold text-sm sm:text-base leading-snug transition-colors duration-200"
              :class="openIndex === i ? 'text-primary' : 'text-charcoal group-hover:text-primary'"
            >
              {{ faq.q }}
            </span>
            <span
              class="shrink-0 w-7 h-7 rounded-full flex items-center justify-center transition-all duration-300"
              :class="openIndex === i ? 'bg-primary text-white' : 'bg-purple-100 text-primary'"
            >
              <svg
                class="w-3.5 h-3.5 transition-transform duration-300"
                :class="openIndex === i ? 'rotate-180' : ''"
                fill="none" stroke="currentColor" viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
              </svg>
            </span>
          </button>

          <!-- Answer — CSS grid height animation -->
          <div class="accordion-body" :class="{ 'accordion-body-open': openIndex === i }">
            <div class="overflow-hidden">
              <p class="px-5 pb-5 pl-[4.25rem] text-muted text-sm leading-relaxed">
                {{ faq.a }}
              </p>
            </div>
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

<style scoped>
@reference "../../css/app.css";

.faq-card {
  @apply bg-white rounded-2xl border border-purple-100/60 shadow-sm overflow-hidden;
  border-left: 3px solid transparent;
  transition: border-left-color 0.2s ease, box-shadow 0.2s ease;
}
.faq-card-open {
  @apply shadow-md;
  border-left-color: #6F2C91;
}

.faq-number {
  @apply w-8 h-8 rounded-xl bg-purple-50 text-primary text-xs font-bold flex items-center justify-center shrink-0 transition-all duration-200;
}
.faq-number-active {
  @apply bg-primary text-white;
}

/* CSS grid height animation — no JS hooks, always smooth */
.accordion-body {
  display: grid;
  grid-template-rows: 0fr;
  transition: grid-template-rows 0.32s cubic-bezier(0.4, 0, 0.2, 1);
}
.accordion-body-open {
  grid-template-rows: 1fr;
}
</style>
