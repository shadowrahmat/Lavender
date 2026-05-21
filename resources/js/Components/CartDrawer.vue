<template>
  <!-- Overlay -->
  <transition name="fade">
    <div v-if="isOpen" class="fixed inset-0 bg-black/50 z-40 backdrop-blur-sm" @click="close"></div>
  </transition>

  <!-- Drawer -->
  <transition name="slide-right">
    <div v-if="isOpen"
      class="fixed right-0 top-0 h-full w-full max-w-md bg-white z-50 flex flex-col shadow-2xl">

      <!-- Header -->
      <div class="flex items-center justify-between px-6 py-5 border-b border-purple-100 gradient-soft">
        <div class="flex items-center gap-3">
          <ShoppingBagIcon class="w-5 h-5 text-primary" />
          <h2 class="font-display text-xl font-semibold text-charcoal">
            Your Cart
            <span v-if="cart.count > 0" class="ml-2 badge badge-purple">{{ cart.count }}</span>
          </h2>
        </div>
        <button @click="close"
          class="p-2 rounded-full hover:bg-purple-100 text-muted hover:text-charcoal transition-all duration-200">
          <XMarkIcon class="w-5 h-5" />
        </button>
      </div>

      <!-- Items -->
      <div class="flex-1 overflow-y-auto px-6 py-4">

        <!-- Loading -->
        <div v-if="loading" class="flex items-center justify-center h-40">
          <div class="w-8 h-8 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
        </div>

        <!-- Empty -->
        <div v-else-if="!cart.items?.length"
          class="flex flex-col items-center justify-center h-48 text-center">
          <div class="w-16 h-16 rounded-2xl bg-purple-50 flex items-center justify-center mb-4">
            <ShoppingBagIcon class="w-8 h-8 text-primary/40" />
          </div>
          <p class="text-charcoal font-semibold mb-1">Your cart is empty</p>
          <p class="text-muted text-sm mb-4">Add some delicious items to get started</p>
          <Link :href="route('shop.index')" @click="close" class="btn-primary text-sm">
            Browse Shop
          </Link>
        </div>

        <!-- Items List -->
        <div v-else class="space-y-4">
          <div v-for="item in cart.items" :key="item.id"
            class="flex gap-4 p-4 bg-purple-50/50 rounded-2xl items-start group">
            <img :src="item.image" :alt="item.name"
              class="w-16 h-16 object-cover rounded-xl shrink-0">
            <div class="flex-1 min-w-0">
              <p class="font-semibold text-charcoal text-sm leading-tight truncate">{{ item.name }}</p>
              <p class="text-xs text-muted mt-0.5">{{ item.category }}</p>
              <p class="text-primary font-bold mt-1">৳{{ item.price }}</p>
              <!-- Quantity Controls -->
              <div class="flex items-center gap-2 mt-2">
                <button @click="updateQty(item, -1)"
                  class="w-7 h-7 rounded-full bg-white border border-purple-200 text-primary hover:bg-primary hover:text-white transition-all duration-150 flex items-center justify-center">
                  <MinusIcon class="w-3 h-3" />
                </button>
                <span class="text-sm font-semibold text-charcoal w-5 text-center">{{ item.quantity }}</span>
                <button @click="updateQty(item, 1)"
                  class="w-7 h-7 rounded-full bg-white border border-purple-200 text-primary hover:bg-primary hover:text-white transition-all duration-150 flex items-center justify-center">
                  <PlusIcon class="w-3 h-3" />
                </button>
                <button @click="removeItem(item.id)"
                  class="ml-auto p-1.5 rounded-lg text-gray-300 hover:text-rose-500 hover:bg-rose-50 transition-all duration-150">
                  <TrashIcon class="w-4 h-4" />
                </button>
              </div>
            </div>
            <p class="font-bold text-charcoal text-sm shrink-0">৳{{ (item.price * item.quantity).toFixed(2) }}</p>
          </div>
        </div>

      </div>

      <!-- Footer Summary -->
      <div v-if="cart.items?.length" class="px-6 py-5 border-t border-purple-100 bg-white">
        <div class="space-y-2 mb-4">
          <div class="flex justify-between text-sm text-muted">
            <span>Subtotal</span>
            <span>৳{{ cart.subtotal?.toFixed(2) }}</span>
          </div>
          <div class="flex justify-between text-sm text-muted">
            <span>Delivery</span>
            <span :class="cart.delivery_charge === 0 ? 'text-success font-semibold' : ''">
              {{ cart.delivery_charge === 0 ? 'FREE' : '৳' + cart.delivery_charge }}
            </span>
          </div>
          <div class="flex justify-between font-bold text-charcoal pt-2 border-t border-purple-100">
            <span>Total</span>
            <span class="text-primary text-lg">৳{{ cart.total?.toFixed(2) }}</span>
          </div>
        </div>
        <div class="flex gap-3">
          <Link :href="route('cart.index')" @click="close" class="btn-secondary flex-1 text-center text-sm">
            View Cart
          </Link>
          <Link :href="route('checkout.index')" @click="close" class="btn-primary flex-1 text-center text-sm">
            Checkout
          </Link>
        </div>
        <p class="text-center text-xs text-muted mt-3">Free delivery on orders over ৳500</p>
      </div>

    </div>
  </transition>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import axios from 'axios'
import {
  XMarkIcon,
  ShoppingBagIcon,
  TrashIcon,
  PlusIcon,
  MinusIcon,
} from '@heroicons/vue/24/outline'

const isOpen = ref(false)
const loading = ref(false)
const cart = ref({ items: [], count: 0, subtotal: 0, delivery_charge: 60, total: 0 })

const open = async () => { isOpen.value = true; await fetchCart() }
const close = () => { isOpen.value = false }

const fetchCart = async () => {
  loading.value = true
  try {
    const res = await axios.get(route('cart.json'))
    cart.value = res.data
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

const updateQty = (item, delta) => {
  const newQty = item.quantity + delta
  if (newQty < 1) return removeItem(item.id)
  if (newQty > item.stock) return
  router.patch(route('cart.update', item.id), { quantity: newQty }, {
    preserveScroll: true, onSuccess: fetchCart,
  })
}

const removeItem = (id) => {
  router.delete(route('cart.remove', id), {
    preserveScroll: true, onSuccess: fetchCart,
  })
}

onMounted(() => window.addEventListener('open-cart', open))
</script>

<style scoped>
.slide-right-enter-active, .slide-right-leave-active {
  transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}
.slide-right-enter-from, .slide-right-leave-to { transform: translateX(100%); }
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
