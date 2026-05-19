<template>
  <aside class="hidden md:flex w-64 bg-[#150e20] min-h-screen flex-col sticky top-0 h-screen border-r border-white/5">

    <!-- Brand -->
    <div class="px-5 py-5 border-b border-white/8">
      <Link :href="route('admin.dashboard')" class="flex items-center gap-3 group">
        <img
          src="/images/Lavender-Logo.png"
          alt="Lavender"
          class="h-8 w-auto object-contain"
          style="filter: brightness(0) invert(1); opacity: 0.92;"
        >
      </Link>
      <p class="text-[10px] text-gray-500 tracking-widest uppercase mt-2 font-medium">Admin Panel</p>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 overflow-y-auto space-y-0.5">

      <p class="nav-section-label">Overview</p>
      <SidebarLink :href="route('admin.dashboard')" :active="route().current('admin.dashboard*')">
        <template #icon><Squares2X2Icon class="w-4.5 h-4.5" /></template>
        Dashboard
      </SidebarLink>

      <p class="nav-section-label mt-5">Catalogue</p>
      <SidebarLink :href="route('admin.products.index')" :active="route().current('admin.products*')">
        <template #icon><ArchiveBoxIcon class="w-4.5 h-4.5" /></template>
        Products
      </SidebarLink>
      <SidebarLink :href="route('admin.categories.index')" :active="route().current('admin.categories*')">
        <template #icon><TagIcon class="w-4.5 h-4.5" /></template>
        Categories
      </SidebarLink>
      <SidebarLink :href="route('admin.coupons.index')" :active="route().current('admin.coupons*')">
        <template #icon><TicketIcon class="w-4.5 h-4.5" /></template>
        Coupons
      </SidebarLink>

      <p class="nav-section-label mt-5">Operations</p>
      <SidebarLink :href="route('admin.orders.index')" :active="route().current('admin.orders*')">
        <template #icon><ClipboardDocumentListIcon class="w-4.5 h-4.5" /></template>
        All Orders
      </SidebarLink>

      <p class="nav-section-label mt-5">Messages</p>
      <SidebarLink :href="route('admin.messages.contacts')" :active="route().current('admin.messages.contacts*')">
        <template #icon><EnvelopeIcon class="w-4.5 h-4.5" /></template>
        Contact Messages
      </SidebarLink>
      <SidebarLink :href="route('admin.messages.corporate')" :active="route().current('admin.messages.corporate*')">
        <template #icon><BuildingOffice2Icon class="w-4.5 h-4.5" /></template>
        Corporate Inquiries
      </SidebarLink>

      <p class="nav-section-label mt-5">System</p>
      <SidebarLink :href="route('admin.settings')" :active="route().current('admin.settings*')">
        <template #icon><Cog6ToothIcon class="w-4.5 h-4.5" /></template>
        Settings
      </SidebarLink>
      <SidebarLink :href="route('home')" :active="false">
        <template #icon><ArrowTopRightOnSquareIcon class="w-4.5 h-4.5" /></template>
        View Website
      </SidebarLink>

    </nav>

    <!-- Logout -->
    <div class="p-3 border-t border-white/8">
      <Link :href="route('logout')" method="post" as="button"
        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-500 hover:text-rose-400 hover:bg-rose-500/10 transition-all duration-200 text-sm group">
        <ArrowRightOnRectangleIcon class="w-4.5 h-4.5 transition-transform duration-200 group-hover:translate-x-0.5" />
        <span>Logout</span>
      </Link>
    </div>

  </aside>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import SidebarLink from '@/Components/SidebarLink.vue'
import {
  Squares2X2Icon,
  ArchiveBoxIcon,
  TagIcon,
  TicketIcon,
  ClipboardDocumentListIcon,
  EnvelopeIcon,
  BuildingOffice2Icon,
  Cog6ToothIcon,
  ArrowTopRightOnSquareIcon,
  ArrowRightOnRectangleIcon,
} from '@heroicons/vue/24/outline'
</script>

<style scoped>
@reference "../../css/app.css";
.nav-section-label {
  @apply text-[10px] text-gray-600 uppercase tracking-widest font-semibold px-3 mb-1.5 mt-2 block;
}
</style>
