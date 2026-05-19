<template>
  <AppLayout>
    <Head title="Shop" />

    <!-- Shop Header -->
    <div class="gradient-soft py-14 text-center">
      <div class="max-w-7xl mx-auto px-4">
        <h1 class="font-display text-4xl md:text-5xl font-bold text-charcoal mb-4">Our Bakery Shop</h1>
        <p class="text-muted text-lg">Fresh, delicious bakery items delivered to your door.</p>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

      <!-- Mobile filter toggle bar -->
      <div class="flex items-center gap-3 mb-4 lg:hidden">
        <button @click="showFilters = !showFilters"
          class="flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 border-purple-200 text-sm font-semibold text-charcoal bg-white active:bg-purple-50 transition-colors">
          <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
          </svg>
          Filters
          <span v-if="filters.category || filters.min_price || filters.max_price"
            class="w-2 h-2 rounded-full bg-primary shrink-0"></span>
        </button>
        <!-- Mobile sort -->
        <select v-model="localSort" @change="applySort" class="input-field text-sm flex-1 py-2.5">
          <option value="latest">Newest First</option>
          <option value="price_asc">Price: Low → High</option>
          <option value="price_desc">Price: High → Low</option>
          <option value="popular">Most Popular</option>
        </select>
      </div>

      <!-- Mobile filter drawer -->
      <div v-if="showFilters" class="lg:hidden card p-5 mb-4">
        <div class="flex items-center justify-between mb-4">
          <h3 class="font-semibold text-charcoal">Filters</h3>
          <button @click="showFilters = false" class="text-muted hover:text-charcoal transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
        <p class="text-xs font-semibold text-charcoal mb-2 uppercase tracking-wider">Categories</p>
        <div class="flex flex-wrap gap-2 mb-4">
          <button @click="clearCategory"
            :class="!filters.category ? 'bg-primary text-white' : 'bg-purple-50 text-charcoal'"
            class="px-3 py-1.5 rounded-full text-xs font-semibold transition-colors">
            All
          </button>
          <button v-for="cat in categories" :key="cat.id"
            @click="setCategory(cat.slug); showFilters = false"
            :class="filters.category === cat.slug ? 'bg-primary text-white' : 'bg-purple-50 text-charcoal'"
            class="px-3 py-1.5 rounded-full text-xs font-semibold transition-colors">
            {{ cat.name }}
          </button>
        </div>
        <p class="text-xs font-semibold text-charcoal mb-2 uppercase tracking-wider">Price Range</p>
        <div class="flex gap-2 mb-3">
          <input v-model="localMinPrice" type="number" placeholder="Min ৳" class="input-field text-sm py-2">
          <input v-model="localMaxPrice" type="number" placeholder="Max ৳" class="input-field text-sm py-2">
        </div>
        <button @click="applyPriceFilter; showFilters = false" class="btn-primary w-full text-sm py-2.5">Apply Price</button>
      </div>

      <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filters (desktop only) -->
        <aside class="hidden lg:block lg:w-64 shrink-0">
          <div class="card p-6 sticky top-24">
            <h3 class="font-display font-semibold text-charcoal text-lg mb-5">Filters</h3>

            <!-- Categories -->
            <div class="mb-6">
              <p class="text-sm font-semibold text-charcoal mb-3 uppercase tracking-wider">Categories</p>
              <div class="space-y-2">
                <button @click="clearCategory"
                  :class="!filters.category ? 'bg-primary text-white' : 'bg-purple-50 text-charcoal hover:bg-purple-100'"
                  class="w-full text-left px-4 py-2.5 rounded-xl text-sm font-medium transition-colors">
                  All Products
                </button>
                <button v-for="cat in categories" :key="cat.id"
                  @click="setCategory(cat.slug)"
                  :class="filters.category === cat.slug ? 'bg-primary text-white' : 'bg-purple-50 text-charcoal hover:bg-purple-100'"
                  class="w-full text-left px-4 py-2.5 rounded-xl text-sm font-medium transition-colors">
                  {{ cat.name }}
                </button>
              </div>
            </div>

            <!-- Sort -->
            <div class="mb-6">
              <p class="text-sm font-semibold text-charcoal mb-3 uppercase tracking-wider">Sort By</p>
              <select v-model="localSort" @change="applySort" class="input-field text-sm">
                <option value="latest">Newest First</option>
                <option value="price_asc">Price: Low to High</option>
                <option value="price_desc">Price: High to Low</option>
                <option value="popular">Most Popular</option>
              </select>
            </div>

            <!-- Price Range -->
            <div>
              <p class="text-sm font-semibold text-charcoal mb-3 uppercase tracking-wider">Price Range</p>
              <div class="flex gap-2">
                <input v-model="localMinPrice" type="number" placeholder="Min ৳" class="input-field text-sm">
                <input v-model="localMaxPrice" type="number" placeholder="Max ৳" class="input-field text-sm">
              </div>
              <button @click="applyPriceFilter" class="btn-primary w-full mt-3 text-sm py-2.5">Apply</button>
            </div>
          </div>
        </aside>

        <!-- Products Grid -->
        <div class="flex-1">
          <!-- Search Bar & Results Info -->
          <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between mb-8">
            <div>
              <p class="text-muted text-sm">
                Showing {{ products.total }} products
                <span v-if="filters.category"> in <strong class="text-primary">{{ currentCategoryName }}</strong></span>
                <span v-if="filters.search"> for "<strong class="text-primary">{{ filters.search }}</strong>"</span>
              </p>
            </div>
            <form @submit.prevent="applySearch" class="flex gap-2 w-full sm:w-auto">
              <input v-model="localSearch" type="text" placeholder="Search products..." class="input-field text-sm flex-1 sm:w-64">
              <button type="submit" class="btn-primary px-5 text-sm py-2.5">Search</button>
            </form>
          </div>

          <!-- Products -->
          <div v-if="products.data?.length > 0" class="grid grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            <ProductCard v-for="product in products.data" :key="product.id" :product="product" />
          </div>
          <div v-else class="flex flex-col items-center justify-center py-20 text-center">
            <div class="text-6xl mb-4">🔍</div>
            <h3 class="font-display text-xl font-semibold text-charcoal mb-2">No products found</h3>
            <p class="text-muted mb-6">Try adjusting your search or filter criteria.</p>
            <button @click="clearFilters" class="btn-primary">Clear Filters</button>
          </div>

          <!-- Pagination -->
          <div v-if="products.last_page > 1" class="flex justify-center gap-2 mt-12">
            <template v-for="link in products.links" :key="link.label">
              <Link v-if="link.url" :href="link.url"
                :class="link.active ? 'bg-primary text-white' : 'bg-white text-charcoal hover:bg-purple-50'"
                class="px-4 py-2 rounded-xl text-sm font-medium border border-purple-100 transition-colors"
                v-html="link.label">
              </Link>
              <span v-else :class="link.active ? 'bg-primary text-white' : 'bg-gray-50 text-muted'"
                class="px-4 py-2 rounded-xl text-sm font-medium border border-purple-100" v-html="link.label">
              </span>
            </template>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import ProductCard from '@/Components/ProductCard.vue'

const props = defineProps({
  products: Object,
  categories: Array,
  filters: Object,
})

const showFilters = ref(false)
const localSearch = ref(props.filters?.search || '')
const localSort = ref(props.filters?.sort || 'latest')
const localMinPrice = ref(props.filters?.min_price || '')
const localMaxPrice = ref(props.filters?.max_price || '')

const currentCategoryName = computed(() => {
  return props.categories?.find(c => c.slug === props.filters?.category)?.name || ''
})

const applyFilters = (extra = {}) => {
  router.get(route('shop.index'), {
    ...props.filters,
    search: localSearch.value,
    sort: localSort.value,
    min_price: localMinPrice.value,
    max_price: localMaxPrice.value,
    ...extra,
  }, { preserveScroll: true })
}

const setCategory = (slug) => applyFilters({ category: slug })
const clearCategory = () => applyFilters({ category: '' })
const applySort = () => applyFilters()
const applySearch = () => applyFilters()
const applyPriceFilter = () => applyFilters()
const clearFilters = () => router.get(route('shop.index'))
</script>
