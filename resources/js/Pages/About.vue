<template>
  <AppLayout>
    <Head title="About Us" />

    <!-- Hero -->
    <section class="relative py-24 bg-linear-to-br from-purple-900 to-primary overflow-hidden">
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 right-0 w-96 h-96 rounded-full bg-white blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full bg-white blur-3xl"></div>
      </div>
      <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
        <h1 class="font-display text-5xl md:text-6xl font-bold text-white mb-6 leading-tight">
          More Than a Bakery.<br>
          <span class="text-gradient-light">A Brand Built with Purpose.</span>
        </h1>
        <p class="text-white/80 text-xl leading-relaxed max-w-2xl mx-auto">
          Lavender Food & Bakery brings together fresh bakery items, warm customer service, and a meaningful mission to support education through every purchase.
        </p>
      </div>
    </section>

    <!-- Story -->
    <section class="py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-16 items-center">
          <div>
            <span class="badge badge-purple mb-4">Our Story</span>
            <h2 class="section-title mb-6">How Lavender Began</h2>
            <div class="space-y-4 text-muted text-lg leading-relaxed">
              <p>Lavender Food & Bakery was created to serve fresh, delicious, and beautifully prepared bakery items for everyday customers, families, offices, and special events.</p>
              <p>From cakes and pastries to breads, sweets, and savoury snacks, our goal is to make quality bakery products easy to enjoy.</p>
              <p>We started with a simple belief: that good food, made with care and quality ingredients, can bring people together and create memorable moments.</p>
            </div>
          </div>
          <div class="rounded-3xl bg-linear-to-br from-purple-100 to-purple-50 aspect-square flex items-center justify-center">
            <div class="text-center">
              <div class="w-28 h-28 rounded-3xl gradient-purple flex items-center justify-center mx-auto mb-6 shadow-xl shadow-purple-300">
                <BuildingStorefrontIcon class="w-14 h-14 text-white" />
              </div>
              <p class="font-display text-2xl font-semibold text-charcoal">Est. 2020</p>
              <p class="text-muted mt-1">Dhaka, Bangladesh</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Purpose -->
    <section class="py-20 bg-purple-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-16 items-center">
          <div class="rounded-3xl bg-linear-to-br from-purple-800 to-primary aspect-square flex items-center justify-center text-center p-10">
            <div>
              <div class="w-24 h-24 rounded-3xl bg-white/15 flex items-center justify-center mx-auto mb-6">
                <AcademicCapIcon class="w-12 h-12 text-white" />
              </div>
              <p class="font-display text-2xl font-bold text-white">Abinta Kabir Foundation School</p>
              <p class="text-white/70 mt-2">Supporting Education</p>
            </div>
          </div>
          <div>
            <span class="badge badge-purple mb-4">Our Purpose</span>
            <h2 class="section-title mb-6">Every Order Matters</h2>
            <p class="text-muted text-lg leading-relaxed mb-6">
              Lavender is more than a food brand. It is connected with the vision of <strong class="text-primary">Abinta Kabir Foundation School</strong>. Profit from every order contributes to supporting education for underprivileged children, making every purchase more meaningful.
            </p>
            <p class="text-muted text-lg leading-relaxed">
              When you enjoy our bakery items, you are not just treating yourself — you are helping a child access education and a brighter future.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- What We Offer -->
    <section class="py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
          <h2 class="section-title mb-4">What We Offer</h2>
          <p class="text-muted text-lg">A full range of fresh bakery items for every occasion and taste.</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
          <div v-for="item in offerings" :key="item.title"
            class="p-6 rounded-2xl bg-purple-50 text-center hover:bg-primary hover:text-white group transition-all duration-300 hover:-translate-y-1">
            <div class="w-14 h-14 rounded-2xl bg-white/70 group-hover:bg-white/20 flex items-center justify-center mx-auto mb-4 transition-all duration-300 group-hover:scale-110">
              <component :is="item.icon" class="w-7 h-7 text-primary group-hover:text-white transition-colors duration-300" />
            </div>
            <h3 class="font-semibold text-charcoal group-hover:text-white mb-2 transition-colors text-sm">{{ item.title }}</h3>
            <p class="text-muted text-xs group-hover:text-white/80 transition-colors leading-relaxed">{{ item.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Mission & Vision -->
    <section class="py-20 bg-linear-to-br from-purple-900 to-primary">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-8">
          <div class="glass rounded-3xl p-8">
            <div class="w-14 h-14 rounded-2xl bg-primary/20 flex items-center justify-center mb-5">
              <BoltIcon class="w-7 h-7 text-primary" />
            </div>
            <h3 class="font-display text-2xl font-bold text-charcoal mb-4">Our Mission</h3>
            <p class="text-muted leading-relaxed">
              To make fresh bakery items accessible, enjoyable, and meaningful while supporting a greater social purpose.
            </p>
          </div>
          <div class="glass rounded-3xl p-8">
            <div class="w-14 h-14 rounded-2xl bg-amber-100 flex items-center justify-center mb-5">
              <EyeIcon class="w-7 h-7 text-amber-600" />
            </div>
            <h3 class="font-display text-2xl font-bold text-charcoal mb-4">Our Vision</h3>
            <p class="text-muted leading-relaxed">
              To become a trusted bakery brand in Bangladesh known for quality, care, customer satisfaction, and positive community impact.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="py-20 bg-white text-center">
      <div class="max-w-2xl mx-auto px-4">
        <h2 class="section-title mb-4">Taste the Difference Today</h2>
        <p class="text-muted text-lg mb-8">Order fresh bakery items online and support a meaningful cause.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <Link :href="route('shop.index')" class="btn-primary">Shop Now</Link>
          <Link :href="route('contact')" class="btn-secondary">Get in Touch</Link>
        </div>
      </div>
    </section>

  </AppLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import {
  BuildingStorefrontIcon,
  AcademicCapIcon,
  CakeIcon,
  ArchiveBoxIcon,
  FireIcon,
  HeartIcon,
  MoonIcon,
  BuildingOfficeIcon,
  BoltIcon,
  EyeIcon,
} from '@heroicons/vue/24/outline'

const offerings = [
  { icon: CakeIcon,            title: 'Fresh Cakes & Pastries',  desc: 'Custom and ready-made cakes for every occasion' },
  { icon: ArchiveBoxIcon,      title: 'Bread & Bakery Items',     desc: 'Artisan breads baked fresh every morning' },
  { icon: FireIcon,            title: 'Savoury Snacks',           desc: 'Patties, rolls, puffs, and rice dishes' },
  { icon: HeartIcon,           title: 'Traditional Sweets',       desc: 'Bengali and South Asian sweet delights' },
  { icon: MoonIcon,            title: 'Ramadan Specials',         desc: 'Special iftar items for the holy month' },
  { icon: BuildingOfficeIcon,  title: 'Corporate & Bulk Orders',  desc: 'Catering for offices and events' },
]
</script>

<style scoped>
.text-gradient-light {
  background: linear-gradient(135deg, #E9D8F4, #F4E3C1);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
</style>
