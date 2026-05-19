<template>
  <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-purple-100 safe-area-inset">
    <div class="flex items-center justify-around py-2 px-2">

      <Link :href="route('home')" class="bottom-nav-item" :class="isActive('home')">
        <HomeIcon class="w-6 h-6" />
        <span>Home</span>
      </Link>

      <Link :href="route('shop.index')" class="bottom-nav-item" :class="isActive('shop')">
        <ShoppingBagIcon class="w-6 h-6" />
        <span>Shop</span>
      </Link>

      <!-- Cart (center, elevated) -->
      <button @click="openCart" class="relative flex flex-col items-center gap-1 -mt-6">
        <div class="w-14 h-14 rounded-2xl gradient-purple flex items-center justify-center shadow-lg shadow-purple-300 relative">
          <ShoppingCartIcon class="w-6 h-6 text-white" />
          <span v-if="cart_count > 0"
            class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-warning text-white text-[10px] flex items-center justify-center font-bold shadow">
            {{ cart_count }}
          </span>
        </div>
        <span class="text-xs text-primary font-semibold">Cart</span>
      </button>

      <Link v-if="auth?.user" :href="route('wishlist.index')" class="bottom-nav-item relative" :class="isActive('wishlist')">
        <HeartIcon class="w-6 h-6" />
        <span v-if="wishlist_count > 0"
          class="absolute top-0 right-1 w-4 h-4 rounded-full bg-primary text-white text-[10px] flex items-center justify-center font-bold">
          {{ wishlist_count }}
        </span>
        <span>Saved</span>
      </Link>
      <Link v-else :href="route('login')" class="bottom-nav-item" :class="isActive('login')">
        <UserIcon class="w-6 h-6" />
        <span>Login</span>
      </Link>

      <Link v-if="auth?.user" :href="route('account.dashboard')" class="bottom-nav-item" :class="isActive('account')">
        <div class="w-7 h-7 rounded-full gradient-purple flex items-center justify-center text-white text-xs font-semibold">
          {{ auth.user.name[0].toUpperCase() }}
        </div>
        <span>Account</span>
      </Link>
      <Link v-else :href="route('register')" class="bottom-nav-item" :class="isActive('register')">
        <UserPlusIcon class="w-6 h-6" />
        <span>Sign Up</span>
      </Link>

    </div>
  </nav>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import {
  HomeIcon,
  ShoppingBagIcon,
  ShoppingCartIcon,
  HeartIcon,
  UserIcon,
  UserPlusIcon,
} from '@heroicons/vue/24/outline'

const page = usePage()
const auth = page.props.auth
const cart_count = page.props.cart_count
const wishlist_count = page.props.wishlist_count

const isActive = (name) => route().current(name + '*') ? 'text-primary' : 'text-muted'
const openCart = () => window.dispatchEvent(new Event('open-cart'))
</script>

<style scoped>
@reference "../../css/app.css";
.bottom-nav-item {
  @apply flex flex-col items-center gap-1 text-xs font-medium transition-colors hover:text-primary min-w-12;
}
.safe-area-inset {
  padding-bottom: env(safe-area-inset-bottom, 0);
}
</style>
