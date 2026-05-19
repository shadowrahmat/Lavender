<template>
  <AppLayout>
    <Head title="Contact Us" />

    <!-- Hero -->
    <div class="gradient-soft py-16 text-center border-b border-purple-100">
      <div class="max-w-4xl mx-auto px-4">
        <div class="w-16 h-16 rounded-2xl gradient-purple flex items-center justify-center mx-auto mb-5 shadow-lg shadow-purple-300">
          <ChatBubbleLeftRightIcon class="w-8 h-8 text-white" />
        </div>
        <h1 class="font-display text-4xl md:text-5xl font-bold text-charcoal mb-4">Get in Touch</h1>
        <p class="text-muted text-lg max-w-xl mx-auto">Have a question or want to place an order? We'd love to hear from you.</p>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <div class="grid md:grid-cols-2 gap-12">

        <!-- Contact Form -->
        <div class="card p-8">
          <h2 class="font-display text-2xl font-semibold text-charcoal mb-6">Send a Message</h2>
          <form @submit.prevent="submit" class="space-y-5">
            <div class="grid md:grid-cols-2 gap-4">
              <div>
                <label class="text-sm font-medium text-charcoal mb-1.5 block">Your Name *</label>
                <input v-model="form.name" type="text" class="input-field" placeholder="Full name" required>
                <p v-if="errors.name" class="text-error text-xs mt-1">{{ errors.name }}</p>
              </div>
              <div>
                <label class="text-sm font-medium text-charcoal mb-1.5 block">Phone</label>
                <input v-model="form.phone" type="tel" class="input-field" placeholder="+8801XXXXXXXXX">
              </div>
            </div>
            <div>
              <label class="text-sm font-medium text-charcoal mb-1.5 block">Email Address *</label>
              <input v-model="form.email" type="email" class="input-field" placeholder="your@email.com" required>
              <p v-if="errors.email" class="text-error text-xs mt-1">{{ errors.email }}</p>
            </div>
            <div>
              <label class="text-sm font-medium text-charcoal mb-1.5 block">Subject</label>
              <input v-model="form.subject" type="text" class="input-field" placeholder="How can we help?">
            </div>
            <div>
              <label class="text-sm font-medium text-charcoal mb-1.5 block">Message *</label>
              <textarea v-model="form.message" rows="5" class="input-field" placeholder="Your message..." required></textarea>
              <p v-if="errors.message" class="text-error text-xs mt-1">{{ errors.message }}</p>
            </div>
            <button type="submit" :disabled="processing" class="btn-primary w-full py-3.5 flex items-center justify-center gap-2">
              <PaperAirplaneIcon v-if="!processing" class="w-4 h-4" />
              <ArrowPathIcon v-else class="w-4 h-4 animate-spin" />
              {{ processing ? 'Sending…' : 'Send Message' }}
            </button>
          </form>
        </div>

        <!-- Contact Info -->
        <div class="space-y-5">
          <div v-for="info in contactInfo" :key="info.label"
            class="flex gap-4 p-6 card items-start hover:shadow-md transition-shadow duration-200">
            <div class="w-12 h-12 rounded-2xl gradient-purple flex items-center justify-center shrink-0 shadow-md">
              <component :is="info.icon" class="w-6 h-6 text-white" />
            </div>
            <div class="flex-1 min-w-0">
              <p class="font-semibold text-charcoal mb-1">{{ info.label }}</p>
              <p class="text-muted text-sm leading-relaxed" v-html="info.value"></p>
              <a v-if="info.href" :href="info.href"
                class="inline-flex items-center gap-1.5 text-primary text-sm hover:underline mt-2 font-medium">
                {{ info.action }}
                <ArrowRightIcon class="w-3.5 h-3.5" />
              </a>
            </div>
          </div>

          <!-- Map placeholder -->
          <div class="rounded-3xl overflow-hidden bg-purple-50 aspect-video flex items-center justify-center border-2 border-dashed border-purple-200">
            <div class="text-center p-8">
              <div class="w-16 h-16 rounded-2xl gradient-purple flex items-center justify-center mx-auto mb-4 shadow-md">
                <MapPinIcon class="w-8 h-8 text-white" />
              </div>
              <p class="font-semibold text-charcoal mb-1">Find Us Here</p>
              <p class="text-muted text-sm mb-3">AJ Height, Uttar Badda, Dhaka</p>
              <a href="https://maps.google.com" target="_blank"
                class="inline-flex items-center gap-1.5 text-primary text-sm font-medium hover:underline">
                Open Google Maps
                <ArrowTopRightOnSquareIcon class="w-3.5 h-3.5" />
              </a>
            </div>
          </div>
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
  ChatBubbleLeftRightIcon,
  MapPinIcon,
  PhoneIcon,
  EnvelopeIcon,
  ClockIcon,
  PaperAirplaneIcon,
  ArrowPathIcon,
  ArrowRightIcon,
  ArrowTopRightOnSquareIcon,
} from '@heroicons/vue/24/outline'

const toast = useToast()
const form = ref({ name: '', email: '', phone: '', subject: '', message: '' })
const errors = ref({})
const processing = ref(false)

const submit = () => {
  processing.value = true
  errors.value = {}
  router.post(route('contact.send'), form.value, {
    onSuccess: () => {
      form.value = { name: '', email: '', phone: '', subject: '', message: '' }
      processing.value = false
      toast.success('Message sent successfully!')
    },
    onError: (errs) => { errors.value = errs; processing.value = false },
  })
}

const contactInfo = [
  {
    icon: MapPinIcon,
    label: 'Visit Our Bakery',
    value: 'AJ Height, Uttar Badda, Cha-72, 1/D Progoti Shoroni, Dhaka 1212',
    href: 'https://maps.google.com',
    action: 'Get Directions',
  },
  {
    icon: PhoneIcon,
    label: 'Call Us',
    value: '+8801730-728954<br>+8801730728905',
    href: 'tel:+8801730728954',
    action: 'Call Now',
  },
  {
    icon: EnvelopeIcon,
    label: 'Email Us',
    value: 'hello@lavenderfoodnbakery.com',
    href: 'mailto:hello@lavenderfoodnbakery.com',
    action: 'Send Email',
  },
  {
    icon: ClockIcon,
    label: 'Opening Hours',
    value: 'Daily: 9:00 AM – 11:00 PM',
    href: null,
    action: null,
  },
]
</script>
