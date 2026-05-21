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
                <p v-if="form.errors.company_name" class="text-error text-xs mt-1">{{ form.errors.company_name }}</p>
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
                <p v-if="form.errors.email" class="text-error text-xs mt-1">{{ form.errors.email }}</p>
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

            <!-- File Upload -->
            <div>
              <label class="text-sm font-medium text-charcoal mb-1.5 block">
                Design Files
                <span class="text-muted font-normal ml-1">(optional — images or PDF, max 5 files, 5 MB each)</span>
              </label>

              <!-- Drop zone -->
              <div
                class="drop-zone"
                :class="{ 'drop-zone-over': isDragging }"
                @dragover.prevent="isDragging = true"
                @dragleave.prevent="isDragging = false"
                @drop.prevent="onDrop"
                @click="fileInput?.click()"
              >
                <input
                  ref="fileInput"
                  type="file"
                  multiple
                  accept=".jpg,.jpeg,.png,.gif,.pdf"
                  class="hidden"
                  @change="onFileChange"
                />
                <div class="flex flex-col items-center gap-2 pointer-events-none">
                  <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                    <ArrowUpTrayIcon class="w-5 h-5 text-primary" />
                  </div>
                  <p class="text-sm text-charcoal font-medium">Drop files here or <span class="text-primary">browse</span></p>
                  <p class="text-xs text-muted">JPG, PNG, GIF, PDF · up to 5 MB each</p>
                </div>
              </div>

              <p v-if="form.errors.design_files" class="text-error text-xs mt-1">{{ form.errors.design_files }}</p>

              <!-- Selected files preview -->
              <div v-if="selectedFiles.length" class="mt-3 space-y-2">
                <div v-for="(file, i) in selectedFiles" :key="i"
                  class="flex items-center gap-3 p-2.5 rounded-xl bg-purple-50 border border-purple-100">

                  <!-- Thumbnail or icon -->
                  <div class="w-10 h-10 rounded-lg overflow-hidden bg-white border border-purple-100 shrink-0 flex items-center justify-center">
                    <img v-if="previews[i]" :src="previews[i]" class="w-full h-full object-cover" />
                    <DocumentIcon v-else class="w-5 h-5 text-primary" />
                  </div>

                  <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-charcoal truncate">{{ file.name }}</p>
                    <p class="text-xs text-muted">{{ formatSize(file.size) }}</p>
                  </div>

                  <button type="button" @click.stop="removeFile(i)"
                    class="w-6 h-6 rounded-full bg-white border border-purple-200 flex items-center justify-center hover:bg-red-50 hover:border-red-300 transition-colors shrink-0">
                    <XMarkIcon class="w-3.5 h-3.5 text-muted hover:text-error" />
                  </button>
                </div>
              </div>
            </div>

            <button type="submit" :disabled="form.processing"
              class="btn-primary w-full py-3.5 text-base flex items-center justify-center gap-2">
              <ArrowPathIcon v-if="form.processing" class="w-4 h-4 animate-spin" />
              <PaperAirplaneIcon v-else class="w-4 h-4" />
              {{ form.processing ? 'Submitting…' : 'Submit Inquiry' }}
            </button>
          </form>
        </div>

      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
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
  ArrowUpTrayIcon,
  DocumentIcon,
  XMarkIcon,
} from '@heroicons/vue/24/outline'

const toast = useToast()
const fileInput = ref(null)
const isDragging = ref(false)
const selectedFiles = ref([])
const previews = ref([])

const form = useForm({
  company_name: '', contact_name: '', email: '', phone: '',
  event_type: '', expected_quantity: '', required_date: '', requirements: '',
  design_files: [],
})

const addFiles = (files) => {
  const allowed = Array.from(files).filter(f => {
    const ok = /\.(jpe?g|png|gif|pdf)$/i.test(f.name) && f.size <= 5 * 1024 * 1024
    if (!ok) toast.error(`${f.name} is not allowed (must be JPG/PNG/GIF/PDF under 5 MB).`)
    return ok
  })
  const remaining = 5 - selectedFiles.value.length
  const toAdd = allowed.slice(0, remaining)
  if (allowed.length > remaining) toast.warning(`Only ${remaining} more file(s) can be added (max 5).`)

  toAdd.forEach(file => {
    selectedFiles.value.push(file)
    if (file.type.startsWith('image/')) {
      const reader = new FileReader()
      reader.onload = e => previews.value.push(e.target.result)
      reader.readAsDataURL(file)
    } else {
      previews.value.push(null)
    }
  })
  form.design_files = selectedFiles.value
}

const onFileChange = (e) => addFiles(e.target.files)

const onDrop = (e) => {
  isDragging.value = false
  addFiles(e.dataTransfer.files)
}

const removeFile = (index) => {
  selectedFiles.value.splice(index, 1)
  previews.value.splice(index, 1)
  form.design_files = selectedFiles.value
  if (fileInput.value) fileInput.value.value = ''
}

const formatSize = (bytes) => {
  if (bytes < 1024) return `${bytes} B`
  if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`
  return `${(bytes / (1024 * 1024)).toFixed(1)} MB`
}

const submit = () => {
  form.post(route('corporate.inquiry'), {
    forceFormData: true,
    onSuccess: () => {
      form.reset()
      selectedFiles.value = []
      previews.value = []
      if (fileInput.value) fileInput.value.value = ''
      toast.success('Inquiry submitted! We will contact you shortly.')
    },
    onError: () => {},
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

<style scoped>
@reference "../../css/app.css";

.drop-zone {
  @apply border-2 border-dashed border-purple-200 rounded-2xl p-6 cursor-pointer
         flex items-center justify-center text-center
         hover:border-primary hover:bg-purple-50/50 transition-all duration-200;
}
.drop-zone-over {
  @apply border-primary bg-purple-50;
}
</style>
