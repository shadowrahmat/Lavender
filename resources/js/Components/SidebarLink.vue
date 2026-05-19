<template>
  <Link :href="href"
    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200"
    :class="active
      ? 'bg-primary text-white shadow-lg shadow-purple-900/30'
      : 'text-gray-400 hover:text-white hover:bg-gray-700/50'">
    <slot name="icon" />
    <slot />
  </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({
  href: String,
  active: { type: Boolean, default: false },
})
</script>
