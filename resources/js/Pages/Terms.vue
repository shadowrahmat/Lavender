<template>
  <AppLayout>
    <Head title="Terms & Conditions" />
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <h1 class="font-display text-4xl font-bold text-charcoal mb-4">Terms & Conditions</h1>
      <p class="text-muted mb-10">Last updated: January 2024</p>
      <div class="space-y-6 text-muted leading-relaxed">
        <section>
          <h2 class="font-display text-2xl font-semibold text-charcoal mb-3">1. Acceptance of Terms</h2>
          <p>By using our website and services, you agree to these Terms & Conditions. If you do not agree, please do not use our services.</p>
        </section>
        <section>
          <h2 class="font-display text-2xl font-semibold text-charcoal mb-3">2. Orders & Payment</h2>
          <p>All orders are subject to availability. We reserve the right to cancel or refuse any order. Payment must be completed before order fulfillment for online payment methods.</p>
        </section>
        <section>
          <h2 class="font-display text-2xl font-semibold text-charcoal mb-3">3. Delivery</h2>
          <p>Delivery times are estimates and may vary based on location and demand. We strive to deliver within the estimated time but cannot guarantee exact delivery times.</p>
        </section>
        <section>
          <h2 class="font-display text-2xl font-semibold text-charcoal mb-3">4. Refund Policy</h2>
          <p>Due to the perishable nature of bakery items, we do not accept returns. If there is a quality issue, please contact us within 2 hours of delivery.</p>
        </section>
        <section>
          <h2 class="font-display text-2xl font-semibold text-charcoal mb-3">5. Contact</h2>
          <p>For any queries regarding these terms, please contact us at hello@lavenderfoodnbakery.com or +8801730-728954.</p>
        </section>
      </div>
    </div>
  </AppLayout>
</template>
<script setup>
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
</script>
