<template>
  <AppLayout>
    <Head title="My Wishlist" />
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <h1 class="font-display text-3xl md:text-4xl font-bold text-charcoal mb-8">My Wishlist</h1>
      <div v-if="wishlists?.length" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
        <ProductCard v-for="item in wishlists" :key="item.id" :product="item.product" />
      </div>
      <div v-else class="flex flex-col items-center justify-center py-20 text-center">
        <div class="text-8xl mb-6">❤️</div>
        <h2 class="font-display text-2xl font-bold text-charcoal mb-3">Your wishlist is empty</h2>
        <p class="text-muted mb-8">Save items you love to find them easily later.</p>
        <Link :href="route('shop.index')" class="btn-primary">Browse Products</Link>
      </div>
    </div>
  </AppLayout>
</template>
<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import ProductCard from '@/Components/ProductCard.vue'
defineProps({ wishlists: Array })
</script>
