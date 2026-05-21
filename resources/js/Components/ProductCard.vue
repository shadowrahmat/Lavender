<template>
  <div class="card group relative flex flex-col overflow-hidden h-full">

    <!-- Discount Badge -->
    <div v-if="product.discount_percentage > 0"
      class="absolute top-2 left-2 z-10 badge badge-error font-bold text-[10px] sm:text-xs shadow-sm">
      -{{ product.discount_percentage }}%
    </div>

    <!-- Wishlist Button -->
    <button v-if="auth?.user" @click.prevent="toggleWishlist"
      class="absolute top-2 right-2 z-10 w-7 h-7 sm:w-9 sm:h-9 rounded-full bg-white/90 backdrop-blur-sm shadow-md flex items-center justify-center transition-all duration-200 active:scale-90"
      :class="inWishlist ? 'text-rose-500 bg-rose-50' : 'text-gray-400 hover:text-rose-500 hover:bg-rose-50'">
      <HeartSolidIcon v-if="inWishlist" class="w-3 h-3 sm:w-4 sm:h-4" />
      <HeartIcon v-else class="w-3 h-3 sm:w-4 sm:h-4" />
    </button>

    <!-- Product Image -->
    <Link :href="route('shop.show', product.slug)" class="block overflow-hidden aspect-square bg-green-50 shrink-0">
      <img
        :src="product.featured_image_url || '/images/product-placeholder.jpg'"
        :alt="product.name"
        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
        loading="lazy"
      >
    </Link>

    <!-- Product Info -->
    <div class="p-2.5 sm:p-4 flex flex-col flex-1">

      <!-- Category badge — hidden on mobile to save space -->
      <span class="hidden sm:inline-flex badge badge-green mb-2 self-start text-xs">
        {{ product.category?.name }}
      </span>

      <!-- Product Name -->
      <Link :href="route('shop.show', product.slug)"
        class="font-display font-semibold text-charcoal text-lg leading-tight hover:text-primary transition-colors mb-2 line-clamp-2">
        {{ product.name }}
      </Link>

      <!-- Short description — visible on all screens, smaller on mobile -->
      <p v-if="product.short_description"
        class="text-muted text-[11px] sm:text-sm leading-relaxed mb-3 line-clamp-2 flex-1">
        {{ product.short_description }}
      </p>

      <!-- Price + Add to Cart: stacked layout -->
      <div class="mt-auto pt-2 sm:pt-3 border-t border-green-50">
        <!-- Price -->
        <div class="flex items-baseline gap-1 mb-1.5 sm:mb-2">
          <span class="text-sm sm:text-base font-bold text-primary leading-none">
            ৳{{ formatPrice(product.final_price) }}
          </span>
          <span v-if="product.discount_price" class="text-[10px] sm:text-xs text-muted line-through">
            ৳{{ formatPrice(product.price) }}
          </span>
        </div>

        <!-- Add to Cart Button — full width, touch-friendly -->
        <button
          @click.prevent="addToCart"
          :disabled="product.stock === 0 || adding"
          class="w-full btn-primary py-2 sm:py-2.5 text-[11px] sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-1 sm:gap-1.5 active:scale-95 transition-transform"
        >
          <span v-if="adding" class="flex items-center gap-1">
            <ArrowPathIcon class="w-3 h-3 sm:w-3.5 sm:h-3.5 animate-spin" />
            <span>Adding…</span>
          </span>
          <span v-else-if="product.stock === 0">Sold Out</span>
          <span v-else class="flex items-center gap-1 sm:gap-1.5">
            <ShoppingBagIcon class="w-3 h-3 sm:w-3.5 sm:h-3.5 shrink-0" />
            Add to Cart
          </span>
        </button>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { useToast } from 'vue-toastification'
import { HeartIcon, ShoppingBagIcon, ArrowPathIcon } from '@heroicons/vue/24/outline'
import { HeartIcon as HeartSolidIcon } from '@heroicons/vue/24/solid'

const props = defineProps({
  product: { type: Object, required: true },
  wishlistedIds: { type: Array, default: () => [] },
})

const page = usePage()
const auth = page.props.auth
const toast = useToast()
const adding = ref(false)

const inWishlist = computed(() => props.wishlistedIds.includes(props.product.id))
const formatPrice = (price) => Number(price).toFixed(2)

const addToCart = () => {
  adding.value = true
  router.post(route('cart.add'), { product_id: props.product.id, quantity: 1 }, {
    preserveScroll: true,
    onSuccess: () => { toast.success(`${props.product.name} added to cart`); adding.value = false },
    onError: () => { toast.error('Could not add item to cart.'); adding.value = false },
  })
}

const toggleWishlist = () => {
  router.post(route('wishlist.toggle'), { product_id: props.product.id }, {
    preserveScroll: true,
    onSuccess: () => {
      toast.success(inWishlist.value ? 'Removed from wishlist' : 'Added to wishlist!')
    },
  })
}
</script>
