<template>
  <nav
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
    :class="isHeroMode ? 'bg-transparent hero-nav' : 'bg-white shadow-sm border-b border-purple-100/50'"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16 md:h-20">

        <!-- Logo -->
        <Link :href="route('home')" class="flex items-center shrink-0 group">
          <img
            src="/images/Lavender-Logo.png"
            alt="Lavender Food & Bakery"
            class="h-10 md:h-12 w-auto object-contain transition-all duration-300 group-hover:scale-[1.03]"
            :class="isHeroMode ? 'brightness-0 invert' : ''"
          >
        </Link>

        <!-- Desktop Nav -->
        <div class="hidden md:flex items-center gap-8">
          <Link :href="route('home')" class="nav-link" :class="isActive('home')">Home</Link>
          <Link :href="route('shop.index')" class="nav-link" :class="isActive('shop')">Shop</Link>
          <Link :href="route('about')" class="nav-link" :class="isActive('about')">About</Link>
          <Link :href="route('corporate')" class="nav-link" :class="isActive('corporate')">Corporate</Link>
          <Link :href="route('contact')" class="nav-link" :class="isActive('contact')">Contact</Link>
        </div>

        <!-- Right Actions -->
        <div class="flex items-center gap-1">

          <!-- Search Toggle -->
          <button @click="toggleSearch"
            class="icon-btn" :class="searchOpen ? 'text-primary bg-purple-100/70' : ''">
            <MagnifyingGlassIcon class="w-5 h-5" />
          </button>

          <!-- Wishlist (authenticated, desktop) -->
          <Link v-if="auth?.user" :href="route('wishlist.index')"
            class="relative icon-btn hidden md:flex">
            <HeartIcon class="w-5 h-5" />
            <span v-if="wishlist_count > 0"
              class="absolute -top-0.5 -right-0.5 w-4 h-4 rounded-full bg-primary text-white text-[10px] flex items-center justify-center font-bold leading-none">
              {{ wishlist_count }}
            </span>
          </Link>

          <!-- Cart -->
          <button @click="openCart" class="relative icon-btn">
            <ShoppingBagIcon class="w-5 h-5" />
            <span v-if="cart_count > 0"
              class="absolute -top-0.5 -right-0.5 w-4.5 h-4.5 rounded-full gradient-purple text-white text-[10px] flex items-center justify-center font-bold leading-none shadow-sm">
              {{ cart_count }}
            </span>
          </button>

          <!-- User Menu -->
          <div class="relative" v-if="auth?.user" ref="userMenuRef">
            <button @click="userMenuOpen = !userMenuOpen"
              class="flex items-center gap-1.5 px-1.5 py-1 rounded-xl hover:bg-purple-50 transition-all duration-200 ml-1">
              <div class="w-8 h-8 rounded-full gradient-purple flex items-center justify-center text-white text-sm font-semibold shadow-sm">
                {{ auth.user.name[0].toUpperCase() }}
              </div>
              <ChevronDownIcon
                class="w-3.5 h-3.5 hidden md:block transition-all duration-200"
                :class="[userMenuOpen ? 'rotate-180' : '', isHeroMode ? 'text-white/70' : 'text-muted']" />
            </button>
            <transition name="dropdown">
              <div v-if="userMenuOpen"
                class="absolute right-0 top-12 bg-white rounded-2xl shadow-xl border border-purple-100/60 py-2 z-50 min-w-52.5">
                <div class="px-4 py-2.5 border-b border-purple-50 mb-1">
                  <p class="text-sm font-semibold text-charcoal leading-tight">{{ auth.user.name }}</p>
                  <p class="text-xs text-muted mt-0.5 truncate">{{ auth.user.email }}</p>
                </div>
                <Link :href="route('account.dashboard')" class="dropdown-item">
                  <UserCircleIcon class="w-4 h-4" /> My Dashboard
                </Link>
                <Link :href="route('account.orders')" class="dropdown-item">
                  <ClipboardDocumentListIcon class="w-4 h-4" /> My Orders
                </Link>
                <Link :href="route('account.profile')" class="dropdown-item">
                  <Cog6ToothIcon class="w-4 h-4" /> Profile Settings
                </Link>
                <Link v-if="auth.user.role === 'admin'" :href="route('admin.dashboard')" class="dropdown-item text-primary font-medium">
                  <Squares2X2Icon class="w-4 h-4" /> Admin Panel
                </Link>
                <div class="border-t border-purple-50 my-1"></div>
                <Link :href="route('logout')" method="post" as="button"
                  class="dropdown-item text-rose-500 hover:bg-rose-50 hover:text-rose-600 w-full">
                  <ArrowRightOnRectangleIcon class="w-4 h-4" /> Sign Out
                </Link>
              </div>
            </transition>
          </div>

          <!-- Login (guest, desktop) -->
          <Link v-else :href="route('login')"
            class="hidden md:inline-flex btn-primary text-sm px-5 py-2 items-center gap-2 ml-1">
            <UserIcon class="w-4 h-4" />
            Login
          </Link>

          <!-- Mobile Hamburger -->
          <button @click="mobileMenuOpen = !mobileMenuOpen"
            class="md:hidden icon-btn ml-1">
            <XMarkIcon v-if="mobileMenuOpen" class="w-5 h-5" />
            <Bars3Icon v-else class="w-5 h-5" />
          </button>

        </div>
      </div>

      <!-- ── Search Bar ── right-aligned below nav row -->
      <transition name="slide-down">
        <div v-if="searchOpen" class="pb-3.5 pt-0.5">
          <div class="flex sm:justify-end">
            <div class="relative w-full sm:w-80 md:w-96" ref="searchContainer">

              <!-- Input -->
              <div class="relative flex items-center">
                <MagnifyingGlassIcon class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-muted pointer-events-none z-10" />
                <input
                  v-model="searchQuery"
                  @input="onSearchInput"
                  @keydown.enter.prevent="submitSearch"
                  @keydown.escape="closeSearch"
                  type="text"
                  placeholder="Search products…"
                  autocomplete="off"
                  autofocus
                  class="search-input"
                >
                <!-- Spinner -->
                <div v-if="searchLoading" class="absolute right-3.5 top-1/2 -translate-y-1/2">
                  <div class="w-3.5 h-3.5 border-2 border-primary/20 border-t-primary rounded-full animate-spin"></div>
                </div>
                <!-- Clear button -->
                <button v-else-if="searchQuery" @click="clearSearch"
                  class="absolute right-3.5 top-1/2 -translate-y-1/2 text-muted/60 hover:text-muted transition-colors p-0.5 rounded">
                  <XMarkIcon class="w-3.5 h-3.5" />
                </button>
              </div>

              <!-- Results dropdown -->
              <transition name="dropdown">
                <div v-if="showResults"
                  class="absolute top-full mt-2 left-0 right-0 bg-white rounded-2xl shadow-xl border border-purple-100/60 overflow-hidden z-50">

                  <template v-if="searchResults.length > 0">
                    <Link
                      v-for="product in searchResults" :key="product.id"
                      :href="route('shop.show', product.slug)"
                      @click="closeSearch"
                      class="flex items-center gap-3 px-4 py-3 hover:bg-purple-50/60 transition-colors group">
                      <img :src="product.featured_image_url" :alt="product.name"
                        class="w-10 h-10 object-cover rounded-xl bg-purple-50 shrink-0">
                      <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-charcoal truncate group-hover:text-primary transition-colors">
                          {{ product.name }}
                        </p>
                        <p class="text-xs text-muted">{{ product.category }}</p>
                      </div>
                      <p class="text-sm font-semibold text-primary shrink-0">
                        ৳{{ Number(product.final_price).toFixed(0) }}
                      </p>
                    </Link>

                    <!-- See all -->
                    <div class="border-t border-purple-50">
                      <button @click="submitSearch"
                        class="flex items-center justify-center gap-1.5 w-full px-4 py-2.5 text-xs text-primary font-medium hover:bg-purple-50/50 transition-colors">
                        <MagnifyingGlassIcon class="w-3.5 h-3.5" />
                        See all results for "{{ searchQuery }}"
                      </button>
                    </div>
                  </template>

                  <!-- No results -->
                  <div v-else-if="!searchLoading" class="px-4 py-5 text-center">
                    <p class="text-sm text-muted">
                      No products found for "<span class="text-charcoal font-medium">{{ searchQuery }}</span>"
                    </p>
                  </div>
                </div>
              </transition>

            </div>
          </div>
        </div>
      </transition>

      <!-- Mobile Menu -->
      <transition name="slide-down">
        <div v-if="mobileMenuOpen"
          class="md:hidden bg-white rounded-2xl shadow-xl border border-purple-100/60 p-3 mb-4">
          <div class="flex flex-col gap-0.5">
            <Link :href="route('home')" class="mobile-nav-link" @click="mobileMenuOpen = false">
              <HomeIcon class="w-5 h-5" /> Home
            </Link>
            <Link :href="route('shop.index')" class="mobile-nav-link" @click="mobileMenuOpen = false">
              <ShoppingBagIcon class="w-5 h-5" /> Shop
            </Link>
            <Link :href="route('about')" class="mobile-nav-link" @click="mobileMenuOpen = false">
              <InformationCircleIcon class="w-5 h-5" /> About
            </Link>
            <Link :href="route('corporate')" class="mobile-nav-link" @click="mobileMenuOpen = false">
              <BuildingOffice2Icon class="w-5 h-5" /> Corporate
            </Link>
            <Link :href="route('contact')" class="mobile-nav-link" @click="mobileMenuOpen = false">
              <PhoneIcon class="w-5 h-5" /> Contact
            </Link>
            <div class="border-t border-purple-100 my-1.5"></div>
            <Link v-if="!auth?.user" :href="route('login')" class="btn-primary text-center" @click="mobileMenuOpen = false">
              Login
            </Link>
            <Link v-if="!auth?.user" :href="route('register')" class="btn-secondary text-center mt-1" @click="mobileMenuOpen = false">
              Register
            </Link>
          </div>
        </div>
      </transition>
    </div>
  </nav>
  <div class="h-16 md:h-20"></div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import axios from 'axios'
import {
  MagnifyingGlassIcon,
  HeartIcon,
  ShoppingBagIcon,
  Bars3Icon,
  XMarkIcon,
  HomeIcon,
  InformationCircleIcon,
  PhoneIcon,
  BuildingOffice2Icon,
  UserIcon,
  UserCircleIcon,
  ChevronDownIcon,
  ClipboardDocumentListIcon,
  Cog6ToothIcon,
  Squares2X2Icon,
  ArrowRightOnRectangleIcon,
} from '@heroicons/vue/24/outline'

const page          = usePage()
const auth          = page.props.auth
const cart_count    = page.props.cart_count
const wishlist_count = page.props.wishlist_count

const scrolled        = ref(false)
const isHomePage      = computed(() => route().current('home'))
const isHeroMode      = computed(() => isHomePage.value && !scrolled.value)
const searchOpen      = ref(false)
const mobileMenuOpen  = ref(false)
const userMenuOpen    = ref(false)
const userMenuRef     = ref(null)
const searchContainer = ref(null)

const searchQuery   = ref('')
const searchResults = ref([])
const searchLoading = ref(false)
const showResults   = ref(false)
let debounceTimer   = null

const handleScroll = () => { scrolled.value = window.scrollY > 20 }

const isActive = (name) => {
  const active = route().current(name + '*')
  if (isHeroMode.value) {
    return active ? 'text-white font-semibold' : 'text-white/80'
  }
  return active ? 'text-primary font-semibold' : 'text-charcoal'
}

const openCart = () => { window.dispatchEvent(new Event('open-cart')) }

const toggleSearch = () => {
  searchOpen.value = !searchOpen.value
  if (!searchOpen.value) clearSearch()
}

const onSearchInput = () => {
  clearTimeout(debounceTimer)
  const q = searchQuery.value.trim()

  if (q.length < 2) {
    searchResults.value = []
    showResults.value   = false
    searchLoading.value = false
    return
  }

  searchLoading.value = true
  showResults.value   = true

  debounceTimer = setTimeout(async () => {
    try {
      const { data } = await axios.get(route('search'), { params: { q } })
      searchResults.value = data
    } catch {
      searchResults.value = []
    } finally {
      searchLoading.value = false
    }
  }, 280)
}

const clearSearch = () => {
  searchQuery.value   = ''
  searchResults.value = []
  showResults.value   = false
  clearTimeout(debounceTimer)
}

const closeSearch = () => {
  searchOpen.value = false
  clearSearch()
}

const submitSearch = () => {
  if (searchQuery.value.trim()) {
    router.get(route('shop.index'), { search: searchQuery.value })
    closeSearch()
  }
}

const handleOutsideClick = (e) => {
  if (userMenuRef.value && !userMenuRef.value.contains(e.target)) {
    userMenuOpen.value = false
  }
  if (searchContainer.value && !searchContainer.value.contains(e.target)) {
    showResults.value = false
  }
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
  document.addEventListener('click', handleOutsideClick)
})
onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
  document.removeEventListener('click', handleOutsideClick)
  clearTimeout(debounceTimer)
})
</script>

<style scoped>
@reference "../../css/app.css";

.icon-btn {
  @apply p-2.5 rounded-xl hover:bg-purple-50 text-muted hover:text-primary transition-all duration-200;
}

/* Transparent/hero state overrides */
.hero-nav .icon-btn {
  @apply text-white/80 hover:text-white hover:bg-white/10;
}
.hero-nav .nav-link {
  @apply hover:text-white;
}
.hero-nav .nav-link::after {
  @apply bg-white;
}

.nav-link {
  @apply text-sm font-medium transition-colors hover:text-primary relative;
}
.nav-link::after {
  content: '';
  @apply absolute -bottom-1 left-0 w-0 h-0.5 bg-primary transition-all duration-300 rounded-full;
}
.nav-link:hover::after {
  @apply w-full;
}
.mobile-nav-link {
  @apply flex items-center gap-3 px-4 py-2.5 rounded-xl text-charcoal hover:bg-purple-50 hover:text-primary transition-all duration-200 font-medium text-sm;
}
.dropdown-item {
  @apply flex items-center gap-3 px-4 py-2 text-sm text-charcoal hover:bg-purple-50 hover:text-primary transition-colors;
}

/* Compact, modern search input */
.search-input {
  width: 100%;
  padding: 0.5rem 2.5rem 0.5rem 2.375rem;
  border-radius: 12px;
  border: 1.5px solid #E9D8F4;
  background: rgba(253, 250, 255, 0.95);
  font-size: 0.8125rem;
  color: #1F1B24;
  outline: none;
  transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
}
.search-input::placeholder {
  color: #9d93a0;
}
.search-input:focus {
  border-color: #6F2C91;
  background: #fff;
  box-shadow: 0 0 0 3px rgba(111, 44, 145, 0.1);
}

/* Slide-down for search bar and mobile menu */
.slide-down-enter-active,
.slide-down-leave-active { transition: all 0.22s ease; }
.slide-down-enter-from,
.slide-down-leave-to    { opacity: 0; transform: translateY(-6px); }

/* Dropdown for user menu + results */
.dropdown-enter-active,
.dropdown-leave-active { transition: all 0.18s ease; }
.dropdown-enter-from,
.dropdown-leave-to    { opacity: 0; transform: translateY(-5px) scale(0.97); }
</style>
