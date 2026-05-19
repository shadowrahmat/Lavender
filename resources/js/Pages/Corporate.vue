<template>
  <AppLayout>
    <Head title="Corporate Orders" />

    <!-- Hero -->
    <section class="gradient-purple py-24 text-center relative overflow-hidden">
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 right-0 w-80 h-80 rounded-full bg-white blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-60 h-60 rounded-full bg-white blur-3xl"></div>
      </div>
      <div class="max-w-4xl mx-auto px-4 relative z-10">
        <div class="w-16 h-16 rounded-2xl bg-white/15 flex items-center justify-center mx-auto mb-6">
          <BuildingOfficeIcon class="w-8 h-8 text-white" />
        </div>
        <span class="inline-block badge badge-purple mb-4 px-4 py-2 text-sm border border-white/20">For Businesses & Events</span>
        <h1 class="font-display text-5xl md:text-6xl font-bold text-white mb-6 leading-tight">
          Bakery Solutions for<br>Offices & Events
        </h1>
        <p class="text-white/80 text-xl max-w-2xl mx-auto">
          Need snacks, pastries, sweets, or bakery boxes for meetings, events, or corporate gifts? We've got you covered.
        </p>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <div class="grid lg:grid-cols-2 gap-16 items-start">

        <!-- Info -->
        <div>
          <h2 class="section-title mb-6">Why Choose Lavender for Corporate?</h2>
          <div class="space-y-4 mb-8">
            <div v-for="feature in features" :key="feature.title"
              class="flex gap-4 p-4 rounded-2xl bg-purple-50 hover:bg-purple-100/80 transition-colors duration-200 group">
              <div class="w-11 h-11 rounded-xl gradient-purple flex items-center justify-center shrink-0 shadow-md group-hover:scale-105 transition-transform duration-200">
                <component :is="feature.icon" class="w-5 h-5 text-white" />
              </div>
              <div>
                <h3 class="font-semibold text-charcoal text-sm">{{ feature.title }}</h3>
                <p class="text-muted text-sm mt-0.5">{{ feature.desc }}</p>
              </div>
            </div>
          </div>

          <!-- Popular Items -->
          <div class="card p-6">
            <h3 class="font-display font-semibold text-charcoal text-lg mb-4">Popular Corporate Items</h3>
            <div class="grid grid-cols-2 gap-2.5">
              <div v-for="item in popularItems" :key="item"
                class="flex gap-2 items-center text-sm text-muted">
                <CheckCircleIcon class="w-4 h-4 text-success shrink-0" />
                {{ item }}
              </div>
            </div>
          </div>
        </div>

        <!-- Inquiry Form -->
        <div class="card p-8 sticky top-24">
          <h2 class="font-display text-2xl font-semibold text-charcoal mb-6">Request Corporate Order</h2>
          <form @submit.prevent="submit" class="space-y-4">
            <div class="grid md:grid-cols-2 gap-4">
              <div>
                <label class="text-sm font-medium text-charcoal mb-1.5 block">Company Name *</label>
                <input v-model="form.company_name" type="text" class="input-field" required>
                <p v-if="errors.company_name" class="text-error text-xs mt-1">{{ errors.company_name }}</p>
              </div>
              <div>
                <label class="text-sm font-medium text-charcoal mb-1.5 block">Contact Person *</label>
                <input v-model="form.contact_name" type="text" class="input-field" required>
              </div>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
              <div>
                <label class="text-sm font-medium text-charcoal mb-1.5 block">Email *</label>
                <input v-model="form.email" type="email" class="input-field" required>
                <p v-if="errors.email" class="text-error text-xs mt-1">{{ errors.email }}</p>
              </div>
              <div>
                <label class="text-sm font-medium text-charcoal mb-1.5 block">Phone *</label>
                <input v-model="form.phone" type="tel" class="input-field" required>
              </div>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
              <div>
                <label class="text-sm font-medium text-charcoal mb-1.5 block">Event Type</label>
                <select v-model="form.event_type" class="input-field">
                  <option value="">Select type</option>
                  <option>Office Meeting</option>
                  <option>Training Session</option>
                  <option>Corporate Event</option>
                  <option>Birthday Celebration</option>
                  <option>Annual Gathering</option>
                  <option>Other</option>
                </select>
              </div>
              <div>
                <label class="text-sm font-medium text-charcoal mb-1.5 block">Expected Quantity</label>
                <input v-model="form.expected_quantity" type="number" class="input-field" placeholder="e.g. 50">
              </div>
            </div>
            <div>
              <label class="text-sm font-medium text-charcoal mb-1.5 block">Required Date</label>
              <input v-model="form.required_date" type="date" class="input-field">
            </div>
            <div>
              <label class="text-sm font-medium text-charcoal mb-1.5 block">Requirements / Notes</label>
              <textarea v-model="form.requirements" rows="4" class="input-field"
                placeholder="Describe your requirements, dietary preferences, etc."></textarea>
            </div>
            <button type="submit" :disabled="processing"
              class="btn-primary w-full py-3.5 text-base flex items-center justify-center gap-2">
              <ArrowPathIcon v-if="processing" class="w-4 h-4 animate-spin" />
              <PaperAirplaneIcon v-else class="w-4 h-4" />
              {{ processing ? 'Submitting…' : 'Submit Inquiry' }}
            </button>
          </form>
        </div>

      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { useToast } from 'vue-toastification'
import {
  BuildingOfficeIcon,
  ArchiveBoxIcon,
  TruckIcon,
  GiftIcon,
  DocumentTextIcon,
  BanknotesIcon,
  CheckCircleIcon,
  PaperAirplaneIcon,
  ArrowPathIcon,
} from '@heroicons/vue/24/outline'

const toast = useToast()
const processing = ref(false)
const errors = ref({})
const form = ref({
  company_name: '', contact_name: '', email: '', phone: '',
  event_type: '', expected_quantity: '', required_date: '', requirements: '',
})

const submit = () => {
  processing.value = true
  router.post(route('corporate.inquiry'), form.value, {
    onSuccess: () => {
      form.value = {
        company_name: '', contact_name: '', email: '', phone: '',
        event_type: '', expected_quantity: '', required_date: '', requirements: '',
      }
      processing.value = false
      toast.success('Inquiry submitted! We will contact you shortly.')
    },
    onError: (errs) => { errors.value = errs; processing.value = false },
  })
}

const features = [
  { icon: ArchiveBoxIcon,    title: 'Bulk Orders Available',  desc: 'Minimum 20 pieces, no maximum limit.' },
  { icon: TruckIcon,         title: 'On-Time Delivery',       desc: 'Reliable delivery to your office or venue.' },
  { icon: GiftIcon,          title: 'Custom Packaging',       desc: 'Branded boxes available on request.' },
  { icon: DocumentTextIcon,  title: 'Custom Menus',           desc: 'We tailor our menu to your specific needs.' },
  { icon: BanknotesIcon,     title: 'Corporate Pricing',      desc: 'Special rates for bulk and repeat orders.' },
  { icon: CheckCircleIcon,   title: 'Quality Guaranteed',     desc: 'Same freshness standards as retail orders.' },
]

const popularItems = [
  'Assorted Pastry Boxes', 'Corporate Snack Boxes', 'Custom Birthday Cakes',
  'Chicken Patties (bulk)', 'Mixed Sweet Boxes', 'Bread Assortment',
  'Mini Sandwiches', 'Cream Pastries', 'Gulab Jamun Trays', 'Rosogolla Trays',
]
</script>
