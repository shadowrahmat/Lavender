<template>
  <div class="min-h-screen flex flex-col bg-white">
    <Navbar />
    <main class="flex-1 flex flex-col pb-20 md:pb-0">
      <slot />
    </main>
    <Footer />
    <MobileBottomNav />
    <CartDrawer />
    <FlashMessage />
  </div>
</template>

<script setup>
import Navbar from '@/Components/Navbar.vue'
import Footer from '@/Components/Footer.vue'
import MobileBottomNav from '@/Components/MobileBottomNav.vue'
import CartDrawer from '@/Components/CartDrawer.vue'
import FlashMessage from '@/Components/FlashMessage.vue'
</script>
