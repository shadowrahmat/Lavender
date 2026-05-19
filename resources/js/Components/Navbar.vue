<template>
  <nav
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
    :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-sm border-b border-purple-100/80' : 'bg-transparent'"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16 md:h-20">

        <!-- Logo -->
        <Link :href="route('home')" class="flex items-center shrink-0 group">
          <img
            src="/images/Lavender-Logo.png"
            alt="Lavender Food & Bakery"
            class="h-10 md:h-12 w-auto object-contain transition-transform duration-300 group-hover:scale-[1.03]"
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

          <!-- Search -->
          <button @click="searchOpen = !searchOpen"
            class="icon-btn" :class="searchOpen ? 'text-primary bg-purple-50' : ''">
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

          <!-- User Menu (authenticated) -->
          <div class="relative" v-if="auth?.user">
            <button @click="userMenuOpen = !userMenuOpen"
              class="flex items-center gap-1.5 px-1.5 py-1 rounded-xl hover:bg-purple-50 transition-all duration-200 ml-1">
              <div class="w-8 h-8 rounded-full gradient-purple flex items-center justify-center text-white text-sm font-semibold shadow-sm">
                {{ auth.user.name[0].toUpperCase() }}
              </div>
              <ChevronDownIcon
                class="w-3.5 h-3.5 text-muted hidden md:block transition-transform duration-200"
                :class="userMenuOpen ? 'rotate-180' : ''" />
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

      <!-- Search Bar -->
      <transition name="slide-down">
        <div v-if="searchOpen" class="pb-4">
          <form @submit.prevent="submitSearch" class="flex gap-2">
            <div class="relative flex-1">
              <MagnifyingGlassIcon class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted pointer-events-none" />
              <input v-model="searchQuery" type="text" placeholder="Search cakes, bread, sweets..."
                class="input-field pl-10 w-full" autofocus>
            </div>
            <button type="submit" class="btn-primary px-6">Search</button>
          </form>
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
import { ref, onMounted, onUnmounted } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
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

const page = usePage()
const auth = page.props.auth
const cart_count = page.props.cart_count
const wishlist_count = page.props.wishlist_count

const scrolled = ref(false)
const searchOpen = ref(false)
const mobileMenuOpen = ref(false)
const userMenuOpen = ref(false)
const searchQuery = ref('')

const handleScroll = () => { scrolled.value = window.scrollY > 20 }
onMounted(() => window.addEventListener('scroll', handleScroll))
onUnmounted(() => window.removeEventListener('scroll', handleScroll))

const isActive = (name) => {
  return route().current(name + '*') ? 'text-primary font-semibold' : 'text-charcoal'
}

const openCart = () => { window.dispatchEvent(new Event('open-cart')) }

const submitSearch = () => {
  if (searchQuery.value.trim()) {
    router.get(route('shop.index'), { search: searchQuery.value })
    searchOpen.value = false
  }
}

const closeMenus = (e) => {
  if (!e.target.closest('.relative')) userMenuOpen.value = false
}
onMounted(() => document.addEventListener('click', closeMenus))
onUnmounted(() => document.removeEventListener('click', closeMenus))
</script>

<style scoped>
@reference "../../css/app.css";

.icon-btn {
  @apply p-2.5 rounded-xl hover:bg-purple-50 text-muted hover:text-primary transition-all duration-200;
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

.slide-down-enter-active, .slide-down-leave-active { transition: all 0.25s ease; }
.slide-down-enter-from, .slide-down-leave-to { opacity: 0; transform: translateY(-8px); }

.dropdown-enter-active, .dropdown-leave-active { transition: all 0.2s ease; }
.dropdown-enter-from, .dropdown-leave-to { opacity: 0; transform: translateY(-6px) scale(0.97); }
</style>
