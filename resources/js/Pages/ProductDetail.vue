<template>
  <AppLayout>
    <Head :title="product.name" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Breadcrumb -->
      <nav class="flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-muted mb-6 sm:mb-8 overflow-hidden">
        <Link :href="route('home')" class="hover:text-primary transition-colors shrink-0">Home</Link>
        <span class="shrink-0">›</span>
        <Link :href="route('shop.index')" class="hover:text-primary transition-colors shrink-0">Shop</Link>
        <span class="shrink-0">›</span>
        <Link :href="route('shop.index', { category: product.category.slug })" class="hover:text-primary transition-colors shrink-0 hidden sm:inline">{{ product.category.name }}</Link>
        <span class="shrink-0 hidden sm:inline">›</span>
        <span class="text-charcoal font-medium truncate">{{ product.name }}</span>
      </nav>

      <!-- Product Detail -->
      <div class="grid md:grid-cols-2 gap-8 sm:gap-12 mb-12 sm:mb-20">
        <!-- Image Gallery -->
        <div>
          <div class="aspect-square rounded-3xl overflow-hidden bg-purple-50 mb-4">
            <img :src="selectedImage || product.featured_image_url"
              :alt="product.name"
              class="w-full h-full object-cover">
          </div>
          <div v-if="product.images?.length" class="flex gap-3 overflow-x-auto pb-2">
            <button @click="selectedImage = product.featured_image_url"
              :class="!selectedImage || selectedImage === product.featured_image_url ? 'ring-2 ring-primary' : ''"
              class="w-20 h-20 rounded-xl overflow-hidden shrink-0">
              <img :src="product.featured_image_url" :alt="product.name" class="w-full h-full object-cover">
            </button>
            <button v-for="img in product.images" :key="img.image_url"
              @click="selectedImage = img.image_url"
              :class="selectedImage === img.image_url ? 'ring-2 ring-primary' : ''"
              class="w-20 h-20 rounded-xl overflow-hidden shrink-0">
              <img :src="img.image_url" :alt="product.name" class="w-full h-full object-cover">
            </button>
          </div>
        </div>

        <!-- Product Info -->
        <div>
          <span class="badge badge-purple mb-4">{{ product.category.name }}</span>
          <h1 class="font-display text-3xl md:text-4xl font-bold text-charcoal mb-4">{{ product.name }}</h1>

          <!-- Rating -->
          <div v-if="product.reviews?.length" class="flex items-center gap-3 mb-4">
            <div class="flex gap-1">
              <svg v-for="i in 5" :key="i"
                :class="i <= Math.round(product.average_rating) ? 'text-warning' : 'text-gray-200'"
                class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
              </svg>
            </div>
            <span class="text-muted text-sm">{{ product.average_rating }} ({{ product.reviews.length }} reviews)</span>
          </div>

          <!-- Price -->
          <div class="flex items-end gap-4 mb-6">
            <span class="font-display text-4xl font-bold text-primary">৳{{ formatPrice(product.final_price) }}</span>
            <span v-if="product.discount_price" class="text-xl text-muted line-through">৳{{ formatPrice(product.price) }}</span>
            <span v-if="product.discount_percentage > 0" class="badge badge-error font-bold">
              {{ product.discount_percentage }}% OFF
            </span>
          </div>

          <p class="text-muted text-lg leading-relaxed mb-6">{{ product.short_description }}</p>

          <!-- Details -->
          <div class="grid grid-cols-2 gap-4 mb-6 p-4 bg-purple-50 rounded-2xl">
            <div v-if="product.weight">
              <p class="text-xs text-muted uppercase tracking-wider">Weight</p>
              <p class="font-semibold text-charcoal">{{ product.weight }}</p>
            </div>
            <div v-if="product.sku">
              <p class="text-xs text-muted uppercase tracking-wider">SKU</p>
              <p class="font-semibold text-charcoal">{{ product.sku }}</p>
            </div>
            <div>
              <p class="text-xs text-muted uppercase tracking-wider">Availability</p>
              <p :class="product.stock > 0 ? 'text-success' : 'text-error'" class="font-semibold">
                {{ product.stock > 0 ? `In Stock (${product.stock})` : 'Out of Stock' }}
              </p>
            </div>
          </div>

          <!-- Quantity & Add to Cart -->
          <div class="flex flex-col xs:flex-row items-stretch xs:items-center gap-3 mb-6">
            <div class="flex items-center border-2 border-purple-200 rounded-full overflow-hidden self-start xs:self-auto">
              <button @click="qty > 1 ? qty-- : null"
                class="px-4 py-3 text-primary hover:bg-purple-50 active:bg-purple-100 transition-colors font-bold text-lg">−</button>
              <span class="px-4 py-3 font-semibold text-charcoal min-w-12 text-center">{{ qty }}</span>
              <button @click="qty < product.stock ? qty++ : null"
                class="px-4 py-3 text-primary hover:bg-purple-50 active:bg-purple-100 transition-colors font-bold text-lg">+</button>
            </div>
            <button @click="addToCart"
              :disabled="product.stock === 0 || adding"
              class="btn-primary flex-1 disabled:opacity-50 disabled:cursor-not-allowed active:scale-95 transition-transform">
              <span v-if="adding">Adding...</span>
              <span v-else-if="product.stock === 0">Out of Stock</span>
              <span v-else>Add to Cart — ৳{{ (product.final_price * qty).toFixed(2) }}</span>
            </button>
          </div>

          <button v-if="auth?.user" @click="toggleWishlist"
            class="flex items-center gap-2 text-muted hover:text-error transition-colors text-sm mb-6">
            <svg class="w-5 h-5" :fill="inWishlist ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            {{ inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}
          </button>

          <!-- Tags -->
          <div v-if="product.tags?.length" class="flex flex-wrap gap-2">
            <span v-for="tag in product.tags" :key="tag" class="badge badge-purple">{{ tag }}</span>
          </div>
        </div>
      </div>

      <!-- Tabs: Description / Ingredients / Reviews -->
      <div class="mb-20">
        <div class="flex gap-2 border-b border-purple-100 mb-8">
          <button v-for="tab in tabs" :key="tab.key"
            @click="activeTab = tab.key"
            :class="activeTab === tab.key
              ? 'border-b-2 border-primary text-primary font-semibold'
              : 'text-muted hover:text-charcoal'"
            class="px-6 py-3 text-sm font-medium transition-colors">
            {{ tab.label }}
          </button>
        </div>

        <div v-if="activeTab === 'description'" class="prose prose-purple max-w-none text-muted leading-relaxed">
          <p>{{ product.description }}</p>
        </div>
        <div v-if="activeTab === 'ingredients'" class="text-muted leading-relaxed">
          <p>{{ product.ingredients }}</p>
        </div>
        <div v-if="activeTab === 'reviews'">
          <div v-if="product.reviews?.length" class="space-y-4">
            <div v-for="review in product.reviews" :key="review.id"
              class="p-5 rounded-2xl bg-purple-50">
              <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-full gradient-purple flex items-center justify-center text-white text-sm font-semibold">
                  {{ review.user?.name?.[0] || 'U' }}
                </div>
                <div>
                  <p class="font-semibold text-charcoal text-sm">{{ review.user?.name || 'Customer' }}</p>
                  <p class="text-xs text-muted">{{ review.created_at }}</p>
                </div>
                <div class="ml-auto flex gap-1">
                  <svg v-for="i in 5" :key="i"
                    :class="i <= review.rating ? 'text-warning' : 'text-gray-200'"
                    class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                  </svg>
                </div>
              </div>
              <p class="text-muted text-sm">{{ review.comment }}</p>
            </div>
          </div>
          <p v-else class="text-muted text-center py-8">No reviews yet. Be the first to review!</p>
        </div>
      </div>

      <!-- Related Products -->
      <div v-if="related?.length">
        <h2 class="section-title mb-8">You May Also Like</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
          <ProductCard v-for="p in related" :key="p.id" :product="p" />
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import { useToast } from 'vue-toastification'
import AppLayout from '@/Layouts/AppLayout.vue'
import ProductCard from '@/Components/ProductCard.vue'

const props = defineProps({
  product: Object,
  related: Array,
})

const page = usePage()
const auth = page.props.auth
const toast = useToast()

const selectedImage = ref(null)
const qty = ref(1)
const adding = ref(false)
const inWishlist = ref(false)
const activeTab = ref('description')

const tabs = [
  { key: 'description', label: 'Description' },
  { key: 'ingredients', label: 'Ingredients' },
  { key: 'reviews', label: `Reviews (${props.product.reviews?.length || 0})` },
]

const formatPrice = (p) => Number(p).toFixed(2)

const addToCart = () => {
  adding.value = true
  router.post(route('cart.add'), {
    product_id: props.product.id,
    quantity: qty.value,
  }, {
    preserveScroll: true,
    onSuccess: () => {
      toast.success(`${props.product.name} added to cart!`)
      adding.value = false
    },
    onError: () => {
      toast.error('Could not add to cart.')
      adding.value = false
    },
  })
}

const toggleWishlist = () => {
  router.post(route('wishlist.toggle'), {
    product_id: props.product.id,
  }, {
    preserveScroll: true,
    onSuccess: () => {
      inWishlist.value = !inWishlist.value
      toast.success(inWishlist.value ? 'Added to wishlist!' : 'Removed from wishlist.')
    },
  })
}
</script>
