<template>
  <AppLayout>
    <Head title="Checkout" />

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <h1 class="font-display text-3xl md:text-4xl font-bold text-charcoal mb-8">Checkout</h1>

      <form @submit.prevent="submitOrder">
        <div class="grid lg:grid-cols-3 gap-8">
          <!-- Left: Delivery & Payment -->
          <div class="lg:col-span-2 space-y-6">
            <!-- Delivery Information -->
            <div class="card p-6">
              <h2 class="font-display font-semibold text-charcoal text-xl mb-5 flex items-center gap-2">
                <span class="w-8 h-8 rounded-full bg-primary text-white text-sm flex items-center justify-center font-bold">1</span>
                Delivery Information
              </h2>
              <div class="grid md:grid-cols-2 gap-4">
                <div>
                  <label class="text-sm font-medium text-charcoal mb-1.5 block">Full Name *</label>
                  <input v-model="form.name" type="text" class="input-field" :class="{'border-error': errors.name}"
                    :placeholder="user?.name || 'Enter full name'">
                  <p v-if="errors.name" class="text-error text-xs mt-1">{{ errors.name }}</p>
                </div>
                <div>
                  <label class="text-sm font-medium text-charcoal mb-1.5 block">Email Address *</label>
                  <input v-model="form.email" type="email" class="input-field" :class="{'border-error': errors.email}"
                    :placeholder="user?.email || 'your@email.com'">
                  <p v-if="errors.email" class="text-error text-xs mt-1">{{ errors.email }}</p>
                </div>
                <div>
                  <label class="text-sm font-medium text-charcoal mb-1.5 block">Phone Number *</label>
                  <input v-model="form.phone" type="tel" class="input-field" :class="{'border-error': errors.phone}"
                    :placeholder="user?.phone || '+8801XXXXXXXXX'">
                  <p v-if="errors.phone" class="text-error text-xs mt-1">{{ errors.phone }}</p>
                </div>
                <div>
                  <label class="text-sm font-medium text-charcoal mb-1.5 block">City *</label>
                  <input v-model="form.city" type="text" class="input-field" :class="{'border-error': errors.city}"
                    placeholder="e.g. Dhaka">
                  <p v-if="errors.city" class="text-error text-xs mt-1">{{ errors.city }}</p>
                </div>
                <div class="md:col-span-2">
                  <label class="text-sm font-medium text-charcoal mb-1.5 block">Delivery Address *</label>
                  <textarea v-model="form.address" rows="3" class="input-field" :class="{'border-error': errors.address}"
                    placeholder="Enter your full delivery address"></textarea>
                  <p v-if="errors.address" class="text-error text-xs mt-1">{{ errors.address }}</p>
                </div>
                <div class="md:col-span-2">
                  <label class="text-sm font-medium text-charcoal mb-1.5 block">Order Notes (optional)</label>
                  <textarea v-model="form.notes" rows="2" class="input-field"
                    placeholder="Any special instructions for your order?"></textarea>
                </div>
              </div>
            </div>

            <!-- Payment Method -->
            <div class="card p-6">
              <h2 class="font-display font-semibold text-charcoal text-xl mb-5 flex items-center gap-2">
                <span class="w-8 h-8 rounded-full bg-primary text-white text-sm flex items-center justify-center font-bold">2</span>
                Payment Method
              </h2>
              <div class="space-y-3">
                <label v-for="method in paymentMethods" :key="method.value"
                  :class="form.payment_method === method.value ? 'border-primary bg-purple-50' : 'border-purple-100'"
                  class="flex items-center gap-4 p-4 rounded-2xl border-2 cursor-pointer transition-all hover:border-purple-300">
                  <input v-model="form.payment_method" :value="method.value" type="radio" class="text-primary">
                  <div class="text-2xl">{{ method.icon }}</div>
                  <div class="flex-1">
                    <p class="font-semibold text-charcoal">{{ method.label }}</p>
                    <p class="text-muted text-sm">{{ method.desc }}</p>
                  </div>
                  <span v-if="method.value === 'cod'" class="badge badge-success text-xs">Available</span>
                  <span v-else class="badge badge-warning text-xs">Coming Soon</span>
                </label>
              </div>
            </div>
          </div>

          <!-- Right: Order Summary -->
          <div class="lg:col-span-1">
            <div class="card p-6 sticky top-24">
              <h3 class="font-display font-semibold text-charcoal text-xl mb-5">Order Summary</h3>

              <!-- Items -->
              <div class="space-y-3 mb-5 max-h-60 overflow-y-auto">
                <div v-for="item in cart.items" :key="item.id"
                  class="flex gap-3 items-center">
                  <img :src="item.image" :alt="item.name"
                    class="w-12 h-12 object-cover rounded-xl shrink-0">
                  <div class="flex-1 min-w-0">
                    <p class="font-medium text-charcoal text-sm truncate">{{ item.name }}</p>
                    <p class="text-muted text-xs">x{{ item.quantity }}</p>
                  </div>
                  <p class="font-semibold text-charcoal text-sm shrink-0">৳{{ formatPrice(item.total) }}</p>
                </div>
              </div>

              <hr class="border-purple-100 mb-4">

              <!-- Totals -->
              <div class="space-y-2 mb-6">
                <div class="flex justify-between text-sm">
                  <span class="text-muted">Subtotal</span>
                  <span class="font-medium text-charcoal">৳{{ formatPrice(cart.subtotal) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-muted">Delivery</span>
                  <span :class="cart.delivery_charge === 0 ? 'text-success font-semibold' : ''">
                    {{ cart.delivery_charge === 0 ? 'FREE' : '৳' + formatPrice(cart.delivery_charge) }}
                  </span>
                </div>
                <div v-if="cart.discount > 0" class="flex justify-between text-sm">
                  <span class="text-success">Coupon Discount</span>
                  <span class="text-success font-semibold">-৳{{ formatPrice(cart.discount) }}</span>
                </div>
                <hr class="border-purple-100">
                <div class="flex justify-between font-bold text-lg">
                  <span class="text-charcoal">Total</span>
                  <span class="text-primary">৳{{ formatPrice(cart.total) }}</span>
                </div>
              </div>

              <!-- Submit -->
              <button type="submit" :disabled="submitting"
                class="btn-primary w-full py-4 text-lg">
                <span v-if="submitting" class="flex items-center justify-center gap-2">
                  <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                  </svg>
                  Placing Order...
                </span>
                <span v-else>Place Order 🎉</span>
              </button>

              <p class="text-center text-xs text-muted mt-4">
                By placing your order, you agree to our
                <Link :href="route('terms')" class="text-primary hover:underline">Terms & Conditions</Link>.
              </p>
            </div>
          </div>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  cart: Object,
  user: Object,
  userAddress: Object,
})

const errors = ref({})
const submitting = ref(false)

const form = ref({
  name: props.user?.name || '',
  email: props.user?.email || '',
  phone: props.user?.phone || '',
  address: props.userAddress?.address_line || '',
  city: props.userAddress?.city || 'Dhaka',
  payment_method: 'cod',
  notes: '',
})

const paymentMethods = [
  { value: 'cod', icon: '💵', label: 'Cash on Delivery', desc: 'Pay when your order arrives' },
  { value: 'bkash', icon: '📱', label: 'bKash', desc: 'Mobile financial service' },
  { value: 'nagad', icon: '💳', label: 'Nagad', desc: 'Digital payment service' },
]

const formatPrice = (p) => Number(p || 0).toFixed(2)

const submitOrder = () => {
  submitting.value = true
  errors.value = {}
  router.post(route('checkout.store'), form.value, {
    onError: (errs) => {
      errors.value = errs
      submitting.value = false
    },
    onSuccess: () => {
      submitting.value = false
    },
  })
}
</script>
