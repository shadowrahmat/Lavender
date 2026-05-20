<template>
  <AppLayout>
    <Head title="Freshly Baked Happiness" />

    <!-- ======= HERO SECTION ======= -->
    <section
      ref="heroRef"
      class="-mt-16 md:-mt-20 relative min-h-[85vh] sm:min-h-screen flex items-center overflow-hidden select-none hero-section-bg"
      @mousemove="onMouseMove"
    >
      <!-- Hero grid lines -->
      <div class="absolute inset-0 hero-grid pointer-events-none"></div>

      <!-- Ambient glow — right side behind image cluster -->
      <div class="absolute -right-20 top-1/2 -translate-y-1/2 w-175 h-175 rounded-full bg-purple-500/15 blur-[130px] pointer-events-none"></div>

      <!-- Ambient glow — bottom left accent -->
      <div class="absolute -left-20 bottom-0 w-112.5 h-112.5 rounded-full bg-violet-700/10 blur-[120px] pointer-events-none"></div>

      <!-- Animated particles (twinkling dots + glowing orbs + drifting specks) -->
      <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div v-for="p in bgParticles" :key="p.id" class="absolute rounded-full" :style="p.style"></div>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10">
        <div class="grid md:grid-cols-2 gap-6 lg:gap-16 items-center pt-24 pb-10 sm:pt-28 sm:pb-14 lg:py-32">

          <!-- ── Left: Copy ── -->
          <div class="text-center md:text-left" :style="blobStyle(-8)">

            <!-- Live indicator -->
            <div class="inline-flex items-center gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-white/90 text-xs sm:text-sm font-medium mb-4 sm:mb-8 hero-fade-up" style="animation-delay:0.1s">
              <span class="relative flex h-2 w-2 sm:h-2.5 sm:w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-success opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 sm:h-2.5 sm:w-2.5 bg-success"></span>
              </span>
              Fresh Bakery Items Available Now
            </div>

            <!-- Headline — staggered lines -->
            <h1 class="hero-heading font-display font-bold leading-[1.1] mb-3 sm:mb-6">
              <span class="block text-[1.875rem] sm:text-5xl md:text-[2.75rem] lg:text-7xl text-white hero-fade-up" style="animation-delay:0.22s">Freshly Baked</span>
              <span class="block text-[1.875rem] sm:text-5xl md:text-[2.75rem] lg:text-7xl text-white hero-fade-up" style="animation-delay:0.38s">Happiness,</span>
              <span class="block text-[1.875rem] sm:text-5xl md:text-[2.75rem] lg:text-7xl text-white/85 hero-fade-up" style="animation-delay:0.54s">Made with Care</span>
            </h1>

            <!-- Body -->
            <p class="hero-body text-white/90 text-sm sm:text-base leading-relaxed mb-5 sm:mb-10 max-w-sm sm:max-w-lg mx-auto md:mx-0 hero-fade-up" style="animation-delay:0.7s">
              Discover cakes, pastries, breads, sweets, and savoury bakery items crafted fresh every day by Lavender Food & Bakery.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-row gap-3 sm:gap-4 justify-center md:justify-start hero-fade-up" style="animation-delay:0.86s">
              <Link :href="route('shop.index')" class="btn-hero-main group">
                <ShoppingBagIcon class="w-4 h-4 sm:w-5 sm:h-5 transition-transform duration-300 group-hover:-translate-y-0.5 group-hover:scale-110" />
                <span>Shop Now</span>
                <ArrowRightIcon class="w-3.5 h-3.5 sm:w-4 sm:h-4 transition-transform duration-300 group-hover:translate-x-1.5 opacity-70" />
              </Link>
              <Link :href="route('corporate')" class="btn-hero-ghost group">
                <CakeIcon class="w-4 h-4 sm:w-5 sm:h-5 transition-transform duration-300 group-hover:rotate-12" />
                <span>Pre-Order</span>
              </Link>
            </div>

            <!-- Trust pills — hidden on small mobile to reduce section height -->
            <div class="hidden sm:flex flex-wrap gap-2 sm:gap-3 justify-center md:justify-start mt-6 sm:mt-10 hero-fade-up" style="animation-delay:1.02s">
              <div v-for="b in trustBadges" :key="b.text"
                class="flex items-center gap-1.5 px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-full bg-white/8 border border-white/10 backdrop-blur-sm text-white/65 text-[10px] sm:text-xs font-medium">
                <component :is="b.icon" class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-success/90 shrink-0" />
                {{ b.text }}
              </div>
            </div>
          </div>

          <!-- ── Right: Cards column (md+) ── -->
          <div class="hidden md:flex items-start justify-center" :style="blobStyle(8)">

            <!-- Tablet (md to lg): 2+1 stacked card grid -->
            <div class="lg:hidden w-full space-y-3 hero-fade-up" style="animation-delay:0.7s">
              <!-- Trending pill -->
              <div class="inline-flex items-center gap-2 app-card-pill whitespace-nowrap">
                <span class="text-sm leading-none">🔥</span>
                <div>
                  <p class="text-white text-[11px] font-bold leading-tight">Trending Now</p>
                  <p class="text-white/50 text-[9px]">Fresh from the oven</p>
                </div>
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse ml-1 shrink-0"></span>
              </div>
              <div v-if="heroProducts?.length" class="grid grid-cols-2 gap-3">
                <Link v-if="heroProducts?.[0]" :href="route('shop.show', heroProducts[0].slug)" class="food-img-card block" style="height:175px;">
                  <img :src="heroProducts[0].featured_image_url" :alt="heroProducts[0].name" class="w-full h-full object-cover">
                  <div class="food-img-label">
                    <p class="text-white/70 text-[9px] mb-0.5">{{ heroProducts[0].category?.name }}</p>
                    <p class="text-white text-xs font-bold leading-tight line-clamp-1">{{ heroProducts[0].name }}</p>
                    <p class="text-white/90 text-[10px] font-semibold mt-0.5">৳{{ heroProducts[0].final_price }}</p>
                  </div>
                </Link>
                <Link v-if="heroProducts?.[1]" :href="route('shop.show', heroProducts[1].slug)" class="food-img-card block" style="height:175px;">
                  <img :src="heroProducts[1].featured_image_url" :alt="heroProducts[1].name" class="w-full h-full object-cover">
                  <div class="food-img-label">
                    <p class="text-white/70 text-[9px] mb-0.5">{{ heroProducts[1].category?.name }}</p>
                    <p class="text-white text-xs font-bold leading-tight line-clamp-1">{{ heroProducts[1].name }}</p>
                    <p class="text-white/90 text-[10px] font-semibold mt-0.5">৳{{ heroProducts[1].final_price }}</p>
                  </div>
                </Link>
              </div>
              <Link v-if="heroProducts?.[2]" :href="route('shop.show', heroProducts[2].slug)" class="food-img-card block w-full" style="height:125px;">
                <img :src="heroProducts[2].featured_image_url" :alt="heroProducts[2].name" class="w-full h-full object-cover">
                <div class="food-img-label">
                  <p class="text-white/70 text-[9px] mb-0.5">{{ heroProducts[2].category?.name }}</p>
                  <p class="text-white text-xs font-bold leading-tight line-clamp-1">{{ heroProducts[2].name }}</p>
                  <p class="text-white/90 text-[10px] font-semibold mt-0.5">৳{{ heroProducts[2].final_price }}</p>
                </div>
              </Link>
              <div class="flex gap-3 pt-1">
                <div class="app-card-sm flex items-center gap-2.5 flex-1">
                  <div class="w-8 h-8 rounded-xl bg-emerald-500/20 flex items-center justify-center shrink-0">
                    <CheckCircleIcon class="w-4 h-4 text-emerald-400" />
                  </div>
                  <div>
                    <p class="text-white font-bold text-sm leading-tight">{{ ordersCount }}+</p>
                    <p class="text-white/50 text-[10px]">Orders Today</p>
                  </div>
                </div>
                <div class="app-card-sm flex items-center gap-2.5 flex-1">
                  <div class="w-8 h-8 rounded-xl bg-amber-400/15 flex items-center justify-center shrink-0">
                    <StarIcon class="w-4 h-4 text-amber-400" style="fill:rgba(251,191,36,0.4)" />
                  </div>
                  <div>
                    <p class="text-white font-bold text-sm leading-tight">4.9 ★</p>
                    <p class="text-white/50 text-[10px]">5k+ Reviews</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Desktop (lg+): floating card collage -->
            <div class="hidden lg:block relative" style="width:420px; height:500px;">

              <!-- Ambient glow -->
              <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <div class="w-64 h-64 rounded-full bg-purple-400/20 blur-[70px]"></div>
              </div>

              <!-- ── Card 1 — left column, main tall card, -3° tilt ── -->
              <div class="float-wrap absolute z-20" style="top:52px; left:0; --float-dur:4.5s; --float-delay:0s">
                <Link v-if="heroProducts?.[0]"
                  :href="route('shop.show', heroProducts[0].slug)"
                  class="food-img-card hero-fade-up block"
                  style="width:196px; height:298px; transform:rotate(-3deg); animation-delay:0.6s">
                  <img :src="heroProducts[0].featured_image_url" :alt="heroProducts[0].name" class="w-full h-full object-cover">
                  <div class="food-img-label">
                    <p class="text-white/65 text-[10px] mb-0.5">{{ heroProducts[0].category?.name }}</p>
                    <p class="text-white text-xs font-bold leading-tight line-clamp-1">{{ heroProducts[0].name }}</p>
                    <p class="text-white/90 text-[11px] font-semibold mt-1">৳{{ heroProducts[0].final_price }}</p>
                  </div>
                </Link>
                <div v-else class="food-img-card food-img-card--placeholder hero-fade-up"
                  style="width:196px; height:298px; transform:rotate(-3deg); animation-delay:0.6s">
                  <div class="food-img-label"><p class="text-white/40 text-xs">No product set</p></div>
                </div>
              </div>

              <!-- ── Card 2 — right column, top, +5° tilt ── -->
              <div class="float-wrap absolute z-10" style="top:18px; right:5px; --float-dur:5.2s; --float-delay:0.7s">
                <Link v-if="heroProducts?.[1]"
                  :href="route('shop.show', heroProducts[1].slug)"
                  class="food-img-card hero-fade-up block"
                  style="width:192px; height:175px; transform:rotate(5deg); animation-delay:0.75s">
                  <img :src="heroProducts[1].featured_image_url" :alt="heroProducts[1].name" class="w-full h-full object-cover">
                  <div class="food-img-label">
                    <p class="text-white/65 text-[9px] mb-0.5">{{ heroProducts[1].category?.name }}</p>
                    <p class="text-white text-[11px] font-bold leading-tight line-clamp-1">{{ heroProducts[1].name }}</p>
                    <p class="text-white/90 text-[10px] font-semibold mt-0.5">৳{{ heroProducts[1].final_price }}</p>
                  </div>
                </Link>
              </div>

              <!-- ── Card 3 — right column, bottom, -2° tilt ── -->
              <!-- top:225px keeps a 32px gap below Card 2 (18+175+32=225), safe even with ±12px float -->
              <div class="float-wrap absolute z-10" style="top:225px; right:5px; --float-dur:5.8s; --float-delay:1.4s">
                <Link v-if="heroProducts?.[2]"
                  :href="route('shop.show', heroProducts[2].slug)"
                  class="food-img-card hero-fade-up block"
                  style="width:192px; height:175px; transform:rotate(-2deg); animation-delay:0.9s">
                  <img :src="heroProducts[2].featured_image_url" :alt="heroProducts[2].name" class="w-full h-full object-cover">
                  <div class="food-img-label">
                    <p class="text-white/65 text-[9px] mb-0.5">{{ heroProducts[2].category?.name }}</p>
                    <p class="text-white text-[11px] font-bold leading-tight line-clamp-1">{{ heroProducts[2].name }}</p>
                    <p class="text-white/90 text-[10px] font-semibold mt-0.5">৳{{ heroProducts[2].final_price }}</p>
                  </div>
                </Link>
              </div>

              <!-- ── Trending pill — above Card 1 ── -->
              <div class="float-wrap absolute z-30" style="top:8px; left:5px; --float-dur:4s; --float-delay:0.3s">
                <div class="app-card-pill flex items-center gap-2 whitespace-nowrap hero-fade-up" style="animation-delay:0.5s">
                  <span class="text-sm leading-none">🔥</span>
                  <div>
                    <p class="text-white text-[11px] font-bold leading-tight">Trending Now</p>
                    <p class="text-white/50 text-[9px]">Fresh from the oven</p>
                  </div>
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse ml-1 shrink-0"></span>
                </div>
              </div>

              <!-- ── Orders stat — bottom left, below all cards ── -->
              <!-- Card 1 bottom: 52+298=350px. Stat top ≈ 440px. Clear. -->
              <div class="float-wrap absolute z-30" style="bottom:14px; left:5px; --float-dur:5.5s; --float-delay:0.6s">
                <div class="app-card-sm flex items-center gap-2.5 hero-fade-up" style="animation-delay:1.05s">
                  <div class="w-9 h-9 rounded-xl bg-emerald-500/20 flex items-center justify-center shrink-0">
                    <CheckCircleIcon class="w-4 h-4 text-emerald-400" />
                  </div>
                  <div>
                    <p class="text-white font-bold text-sm leading-tight">{{ ordersCount }}+</p>
                    <p class="text-white/50 text-[10px]">Orders Today</p>
                  </div>
                </div>
              </div>

              <!-- ── Rating stat — bottom right, below Card 3 ── -->
              <!-- Card 3 bottom: 225+175=400px. Stat top ≈ 440px. Clear. -->
              <div class="float-wrap absolute z-30" style="bottom:14px; right:5px; --float-dur:5s; --float-delay:1.2s">
                <div class="app-card-sm flex items-center gap-2.5 hero-fade-up" style="animation-delay:1.15s">
                  <div class="w-9 h-9 rounded-xl bg-amber-400/15 flex items-center justify-center shrink-0">
                    <StarIcon class="w-4 h-4 text-amber-400" style="fill:rgba(251,191,36,0.4)" />
                  </div>
                  <div>
                    <p class="text-white font-bold text-sm leading-tight">4.9 ★</p>
                    <p class="text-white/50 text-[10px]">5k+ Reviews</p>
                  </div>
                </div>
              </div>

              <!-- Sparkle dots -->
              <div v-for="s in sparkles" :key="s.id" class="sparkle-dot absolute rounded-full bg-white" :style="s.style"></div>

            </div>
          </div>

        </div>

        <!-- ── Mobile (<md): horizontal card strip + stats ── -->
        <div class="md:hidden pb-8 hero-fade-up" style="animation-delay:0.9s">
          <div v-if="heroProducts?.length" class="flex gap-3 overflow-x-auto pb-3 snap-x snap-mandatory scrollbar-none -mx-4 px-4 sm:-mx-6 sm:px-6">
            <Link
              v-for="(product, i) in heroProducts"
              :key="product.id"
              :href="route('shop.show', product.slug)"
              class="shrink-0 snap-start food-img-card"
              style="width:68vw; height:46vw; max-width:260px; max-height:180px;"
            >
              <img :src="product.featured_image_url" :alt="product.name" class="w-full h-full object-cover">
              <div class="food-img-label">
                <p class="text-white/70 text-[10px] mb-0.5">{{ product.category?.name }}</p>
                <p class="text-white text-xs font-bold leading-tight line-clamp-1">{{ product.name }}</p>
                <p class="text-white/90 text-[11px] font-semibold mt-1">৳{{ product.final_price }}</p>
              </div>
            </Link>
          </div>
          <div v-if="heroProducts?.length" class="flex justify-center gap-1.5 mt-3">
            <span v-for="(p, i) in heroProducts" :key="i" class="w-1.5 h-1.5 rounded-full bg-white/30"></span>
          </div>
          <div class="flex items-center justify-center gap-3 mt-4">
            <div class="app-card-sm flex items-center gap-2.5">
              <div class="w-8 h-8 rounded-xl bg-emerald-500/20 flex items-center justify-center shrink-0">
                <CheckCircleIcon class="w-4 h-4 text-emerald-400" />
              </div>
              <div>
                <p class="text-white font-bold text-sm leading-tight">{{ ordersCount }}+</p>
                <p class="text-white/50 text-[10px]">Orders Today</p>
              </div>
            </div>
            <div class="app-card-sm flex items-center gap-2.5">
              <div class="w-8 h-8 rounded-xl bg-amber-400/15 flex items-center justify-center shrink-0">
                <StarIcon class="w-4 h-4 text-amber-400" style="fill:rgba(251,191,36,0.4)" />
              </div>
              <div>
                <p class="text-white font-bold text-sm leading-tight">4.9 ★</p>
                <p class="text-white/50 text-[10px]">5k+ Reviews</p>
              </div>
            </div>
          </div>
          <!-- Scroll indicator — inline at end of card strip on mobile -->
          <div class="flex flex-col items-center gap-1.5 text-white/30 text-[11px] tracking-widest uppercase font-medium mt-5">
            <span>Scroll</span>
            <ChevronDownIcon class="w-4 h-4 animate-bounce" />
          </div>
        </div>

      </div>

      <!-- Scroll indicator — absolute at bottom of hero on md+ -->
      <div class="hidden md:flex absolute bottom-8 left-1/2 -translate-x-1/2 flex-col items-center gap-1.5 text-white/30 text-[11px] tracking-widest uppercase font-medium">
        <span>Scroll</span>
        <ChevronDownIcon class="w-4 h-4 animate-bounce" />
      </div>
    </section>
    <!-- ======= END HERO ======= -->

    <!-- Featured Categories -->
    <section class="py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
          <h2 class="section-title mb-4">Explore Our Bakery Favorites</h2>
          <p class="text-muted text-lg max-w-2xl mx-auto">
            From soft breads to rich cakes and savoury bites, find fresh items for every craving, celebration, and daily treat.
          </p>
        </div>
        <div class="grid grid-cols-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4">
          <Link v-for="category in categories" :key="category.id"
            :href="route('shop.index', { category: category.slug })"
            class="group flex flex-col items-center p-3 sm:p-5 rounded-2xl bg-purple-50 hover:bg-primary transition-all duration-300 hover:shadow-xl hover:shadow-purple-200 hover:-translate-y-1 active:scale-95">
            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl bg-white/70 group-hover:bg-white/20 flex items-center justify-center mb-2 sm:mb-3 transition-all duration-300 group-hover:scale-110">
              <component :is="getCategoryIcon(category.slug)" class="w-5 h-5 sm:w-7 sm:h-7 text-primary group-hover:text-white transition-colors duration-300" />
            </div>
            <span class="font-semibold text-[11px] sm:text-sm text-center text-charcoal group-hover:text-white transition-colors leading-tight">{{ category.name }}</span>
            <span class="text-[10px] sm:text-xs text-muted mt-0.5 sm:mt-1 group-hover:text-white/70 transition-colors">{{ category.products_count }} items</span>
          </Link>
        </div>
      </div>
    </section>

    <!-- Popular Products -->
    <section class="py-20 bg-purple-50/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 sm:mb-14 gap-3 sm:gap-4">
          <div>
            <h2 class="section-title mb-2 sm:mb-3">Loved by Our Customers</h2>
            <p class="text-muted text-sm sm:text-lg max-w-xl">A handpicked selection of Lavender's favorite bakery items.</p>
          </div>
          <Link :href="route('shop.index')" class="btn-secondary whitespace-nowrap text-sm sm:text-base py-2 sm:py-3">View All Products</Link>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
          <ProductCard v-for="product in featuredProducts" :key="product.id" :product="product" />
        </div>
      </div>
    </section>

    <!-- Freshly Baked Promise -->
    <section class="py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
          <h2 class="section-title mb-4">Baked Fresh. Served with Love.</h2>
          <p class="text-muted text-lg max-w-2xl mx-auto">Every item is prepared with attention to taste, freshness, and presentation.</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
          <div v-for="promise in promises" :key="promise.title"
            class="text-center p-6 rounded-2xl bg-purple-50 hover:bg-primary hover:text-white group transition-all duration-300 hover:-translate-y-1">
            <div class="w-14 h-14 rounded-2xl bg-white/60 group-hover:bg-white/20 flex items-center justify-center mx-auto mb-4 transition-all duration-300">
              <component :is="promise.icon" class="w-7 h-7 text-primary group-hover:text-white transition-colors duration-300" />
            </div>
            <h3 class="font-display font-semibold text-charcoal group-hover:text-white mb-2 transition-colors text-sm">{{ promise.title }}</h3>
            <p class="text-muted text-xs group-hover:text-white/80 transition-colors">{{ promise.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Pre-Order Cake Section -->
    <section class="py-20 bg-linear-to-br from-purple-900 to-primary relative overflow-hidden">
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 right-0 w-96 h-96 rounded-full bg-white blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 rounded-full bg-white blur-3xl"></div>
      </div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid md:grid-cols-2 gap-12 items-center">
          <div class="text-white">
            <h2 class="font-display text-4xl md:text-5xl font-bold mb-4 leading-tight">Planning a Celebration?</h2>
            <p class="text-white/80 text-lg leading-relaxed mb-8">
              Order custom cakes for birthdays, anniversaries, office events, and family gatherings. Choose your size, flavor, design preference, and pickup or delivery time.
            </p>
            <Link :href="route('corporate')"
              class="inline-flex items-center gap-3 bg-white text-primary px-8 py-4 rounded-full font-bold text-lg hover:bg-cream transition-all shadow-xl hover:-translate-y-0.5 hover:shadow-2xl duration-300">
              <CakeIcon class="w-6 h-6" />
              Start Cake Pre-Order
            </Link>
          </div>
          <div class="glass rounded-3xl p-8 text-center">
            <div class="w-24 h-24 rounded-3xl gradient-purple flex items-center justify-center mx-auto mb-6 shadow-xl shadow-purple-900/30">
              <CakeIcon class="w-12 h-12 text-white" />
            </div>
            <h3 class="font-display text-2xl font-bold text-charcoal mb-4">Custom Cakes</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
              <div class="bg-purple-50 rounded-xl p-3">
                <p class="font-semibold text-primary">Custom Design</p>
                <p class="text-muted">Your vision</p>
              </div>
              <div class="bg-purple-50 rounded-xl p-3">
                <p class="font-semibold text-primary">Multiple Sizes</p>
                <p class="text-muted">0.5kg – 5kg+</p>
              </div>
              <div class="bg-purple-50 rounded-xl p-3">
                <p class="font-semibold text-primary">Any Flavor</p>
                <p class="text-muted">Your choice</p>
              </div>
              <div class="bg-purple-50 rounded-xl p-3">
                <p class="font-semibold text-primary">On-Time</p>
                <p class="text-muted">Guaranteed</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Corporate Orders -->
    <section class="py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-12 items-center">
          <div class="order-2 md:order-1 grid grid-cols-2 gap-4">
            <div v-for="corp in corporateFeatures" :key="corp.title"
              class="p-5 rounded-2xl bg-purple-50 border border-purple-100 hover:border-primary/30 hover:bg-purple-100/60 transition-all duration-200 group">
              <div class="w-10 h-10 rounded-xl gradient-purple flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-200">
                <component :is="corp.icon" class="w-5 h-5 text-white" />
              </div>
              <p class="font-semibold text-charcoal text-sm">{{ corp.title }}</p>
              <p class="text-muted text-xs mt-1">{{ corp.desc }}</p>
            </div>
          </div>
          <div class="order-1 md:order-2">
            <span class="badge badge-purple mb-4">For Businesses</span>
            <h2 class="section-title mb-4">Bakery Solutions for Offices & Events</h2>
            <p class="text-muted text-lg leading-relaxed mb-8">
              Need snacks, pastries, sweets, or bakery boxes for meetings, events, training sessions, or corporate gifts? Lavender Food & Bakery supports bulk and corporate orders.
            </p>
            <Link :href="route('corporate')" class="btn-primary">Request Corporate Order</Link>
          </div>
        </div>
      </div>
    </section>

    <!-- Social Impact -->
    <section class="py-20 bg-linear-to-br from-purple-50 to-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center">
          <div class="w-20 h-20 rounded-2xl gradient-purple flex items-center justify-center mx-auto mb-6 shadow-lg shadow-purple-300">
            <AcademicCapIcon class="w-10 h-10 text-white" />
          </div>
          <h2 class="section-title mb-4">Every Purchase Supports a Greater Purpose</h2>
          <p class="text-muted text-lg leading-relaxed mb-8">
            Lavender Food & Bakery is connected with the mission of <strong class="text-primary">Abinta Kabir Foundation School</strong>. Profit from every purchase helps support education for underprivileged children.
          </p>
          <Link :href="route('about')" class="btn-primary">Learn About Our Story</Link>
        </div>
      </div>
    </section>

    <!-- Why Choose Lavender -->
    <section class="py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
          <h2 class="section-title mb-4">Why Customers Choose Lavender</h2>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="(reason, i) in reasons" :key="i"
            class="flex gap-4 p-6 rounded-2xl hover:bg-purple-50 transition-all duration-200 group hover:-translate-y-0.5">
            <div class="w-10 h-10 rounded-xl gradient-purple flex items-center justify-center shrink-0 shadow-md group-hover:shadow-purple-300 transition-shadow">
              <span class="text-white font-bold text-sm">{{ i + 1 }}</span>
            </div>
            <div class="flex items-center">
              <p class="font-medium text-charcoal text-sm leading-relaxed">{{ reason }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Testimonials -->
    <section class="py-20 bg-purple-50/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
          <h2 class="section-title mb-4">What Our Customers Say</h2>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
          <div v-for="testimonial in testimonials" :key="testimonial.name" class="card p-6">
            <div class="flex gap-1 mb-4">
              <StarIcon v-for="i in 5" :key="i" class="w-4 h-4 text-warning" />
            </div>
            <p class="text-muted text-sm leading-relaxed mb-6 italic">"{{ testimonial.text }}"</p>
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full gradient-purple flex items-center justify-center text-white font-semibold text-sm">
                {{ testimonial.name[0] }}
              </div>
              <div>
                <p class="font-semibold text-charcoal text-sm">{{ testimonial.name }}</p>
                <p class="text-muted text-xs">{{ testimonial.role }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Location Section -->
    <section class="py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
          <h2 class="section-title mb-4">Visit Lavender Food & Bakery</h2>
          <p class="text-muted text-lg">Drop by our outlet or order online for fresh bakery items made with care.</p>
        </div>
        <div class="grid md:grid-cols-2 gap-8 items-start">
          <div class="rounded-3xl overflow-hidden bg-purple-50 aspect-video flex items-center justify-center border-2 border-dashed border-purple-200">
            <div class="text-center p-8">
              <div class="w-16 h-16 rounded-2xl gradient-purple flex items-center justify-center mx-auto mb-4">
                <MapPinIcon class="w-8 h-8 text-white" />
              </div>
              <p class="font-semibold text-charcoal">AJ Height, Uttar Badda</p>
              <p class="text-muted text-sm mt-1">Cha-72, 1/D Progoti Shoroni, Dhaka 1212</p>
              <a href="https://maps.google.com" target="_blank" class="mt-4 btn-primary inline-block text-sm">Open in Google Maps</a>
            </div>
          </div>
          <div class="space-y-4">
            <div v-for="info in contactInfo" :key="info.label"
              class="flex gap-4 p-5 rounded-2xl bg-purple-50 items-center hover:bg-purple-100/60 transition-colors duration-200">
              <div class="w-12 h-12 rounded-xl gradient-purple flex items-center justify-center shrink-0">
                <component :is="info.icon" class="w-6 h-6 text-white" />
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-xs text-muted">{{ info.label }}</p>
                <p class="font-semibold text-charcoal text-sm mt-0.5">{{ info.value }}</p>
              </div>
              <a v-if="info.href" :href="info.href" class="btn-primary text-sm px-4 py-2 shrink-0">{{ info.action }}</a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- More Bakery Favorites -->
    <section class="py-20 bg-purple-50/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 sm:mb-14 gap-3 sm:gap-4">
          <div>
            <h2 class="section-title mb-2 sm:mb-3">More Bakery Favorites</h2>
            <p class="text-muted text-sm sm:text-lg">More freshly prepared items for every taste.</p>
          </div>
          <Link :href="route('shop.index')" class="btn-secondary whitespace-nowrap text-sm sm:text-base py-2 sm:py-3">Browse All</Link>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
          <ProductCard v-for="product in popularProducts" :key="product.id" :product="product" />
        </div>
      </div>
    </section>

  </AppLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import ProductCard from '@/Components/ProductCard.vue'
import {
  ShoppingBagIcon, CakeIcon, CheckCircleIcon, SparklesIcon,
  HeartIcon, ArrowRightIcon, ChevronDownIcon,
  SunIcon, CubeIcon, MapPinIcon, PhoneIcon, ClockIcon,
  BuildingOfficeIcon, GiftIcon, DocumentTextIcon, CalendarDaysIcon,
  AcademicCapIcon, StarIcon, TagIcon, ArchiveBoxIcon, FireIcon, MoonIcon,
} from '@heroicons/vue/24/outline'

// ── Props ──
const props = defineProps({
  heroProducts:     Array,
  featuredProducts: Array,
  categories:       Array,
  popularProducts:  Array,
})

// ── Hero: Mouse parallax ──
const heroRef = ref(null)
const mouseX  = ref(0)
const mouseY  = ref(0)

const onMouseMove = (e) => {
  if (!heroRef.value) return
  const r = heroRef.value.getBoundingClientRect()
  mouseX.value = (e.clientX - r.left - r.width  / 2) / r.width
  mouseY.value = (e.clientY - r.top  - r.height / 2) / r.height
}

const blobStyle = (px) => ({
  transform: `translate(${mouseX.value * px}px, ${mouseY.value * px}px)`,
  transition: 'transform 0.15s ease-out',
})

// ── Hero: Banner image slider ──
const bannerImages = [
  '/banner/Cake-scaled1.webp',
  '/banner/Savory-scaled2.webp',
  '/banner/Sweets-scaled3.webp',
  '/banner/Cake-2-scaled4.webp',
]
const currentBanner = ref(0)
const prevBanner = ref(-1)
let bannerTimer = null
let transitionTimer = null

// ── Hero: Count-up for daily orders ──
const ordersCount = ref(0)
let countTimer = null
onMounted(() => {
  bannerTimer = setInterval(() => {
    prevBanner.value = currentBanner.value
    currentBanner.value = (currentBanner.value + 1) % bannerImages.length
    clearTimeout(transitionTimer)
    transitionTimer = setTimeout(() => { prevBanner.value = -1 }, 2200)
  }, 5500)

  countTimer = setInterval(() => {
    if (ordersCount.value < 200) { ordersCount.value += 4 }
    else { ordersCount.value = 200; clearInterval(countTimer) }
  }, 16)
})
onUnmounted(() => {
  clearInterval(bannerTimer)
  clearInterval(countTimer)
  clearTimeout(transitionTimer)
})

// ── Hero: Background particles (twinkling + glowing orbs + drifting) ──
const bgParticles = [
  // Small white twinkling dots scattered across background
  { id: 0,  style: { top: '8%',  left: '12%', width: '2px', height: '2px', background: 'white', opacity: '0.40', animation: 'twinkle 2.8s ease-in-out 0.3s  infinite alternate' } },
  { id: 1,  style: { top: '22%', left: '78%', width: '3px', height: '3px', background: 'white', opacity: '0.28', animation: 'twinkle 3.4s ease-in-out 1.1s  infinite alternate' } },
  { id: 2,  style: { top: '65%', left: '6%',  width: '2px', height: '2px', background: 'white', opacity: '0.32', animation: 'twinkle 2.5s ease-in-out 0.7s  infinite alternate' } },
  { id: 3,  style: { top: '80%', left: '88%', width: '3px', height: '3px', background: 'white', opacity: '0.22', animation: 'twinkle 3.8s ease-in-out 2.0s  infinite alternate' } },
  { id: 4,  style: { top: '45%', left: '94%', width: '2px', height: '2px', background: 'white', opacity: '0.30', animation: 'twinkle 3.1s ease-in-out 0.5s  infinite alternate' } },
  { id: 5,  style: { top: '15%', left: '52%', width: '2px', height: '2px', background: 'white', opacity: '0.20', animation: 'twinkle 4.2s ease-in-out 0.9s  infinite alternate' } },
  { id: 6,  style: { top: '55%', left: '30%', width: '2px', height: '2px', background: 'white', opacity: '0.25', animation: 'twinkle 3.6s ease-in-out 1.6s  infinite alternate' } },
  { id: 7,  style: { top: '38%', left: '62%', width: '3px', height: '3px', background: 'white', opacity: '0.18', animation: 'twinkle 5.0s ease-in-out 0.4s  infinite alternate' } },
  { id: 8,  style: { top: '72%', left: '42%', width: '2px', height: '2px', background: 'white', opacity: '0.28', animation: 'twinkle 2.9s ease-in-out 2.2s  infinite alternate' } },
  { id: 9,  style: { top: '5%',  left: '35%', width: '2px', height: '2px', background: 'white', opacity: '0.22', animation: 'twinkle 3.3s ease-in-out 0.8s  infinite alternate' } },
  // Glowing purple / violet orbs
  { id: 10, style: { top: '28%', left: '18%', width: '5px', height: '5px', background: 'rgba(167,139,250,0.9)', boxShadow: '0 0 10px 3px rgba(167,139,250,0.55)', animation: 'glow-pulse 4.2s ease-in-out 0s   infinite alternate' } },
  { id: 11, style: { top: '68%', left: '74%', width: '6px', height: '6px', background: 'rgba(139,92,246,0.85)',  boxShadow: '0 0 14px 4px rgba(139,92,246,0.50)',  animation: 'glow-pulse 5.5s ease-in-out 1.2s  infinite alternate' } },
  { id: 12, style: { top: '16%', left: '87%', width: '4px', height: '4px', background: 'rgba(196,181,253,0.9)', boxShadow: '0 0 8px  2px rgba(196,181,253,0.60)', animation: 'glow-pulse 3.8s ease-in-out 0.5s  infinite alternate' } },
  { id: 13, style: { top: '52%', left: '4%',  width: '5px', height: '5px', background: 'rgba(167,139,250,0.8)', boxShadow: '0 0 10px 3px rgba(167,139,250,0.45)', animation: 'glow-pulse 6.0s ease-in-out 2.3s  infinite alternate' } },
  { id: 14, style: { top: '85%', left: '55%', width: '4px', height: '4px', background: 'rgba(216,180,254,0.85)',boxShadow: '0 0 8px  2px rgba(216,180,254,0.55)', animation: 'glow-pulse 4.8s ease-in-out 1.7s  infinite alternate' } },
  // Drifting upward specks (loop from bottom, staggered via negative delay)
  { id: 15, style: { top: '100%', left: '18%', width: '2px', height: '2px', background: 'rgba(200,170,255,0.7)', animation: 'drift-up  9s linear -4s  infinite' } },
  { id: 16, style: { top: '100%', left: '42%', width: '3px', height: '3px', background: 'rgba(255,255,255,0.5)', animation: 'drift-up 12s linear -7s  infinite' } },
  { id: 17, style: { top: '100%', left: '65%', width: '2px', height: '2px', background: 'rgba(200,170,255,0.6)', animation: 'drift-up  8s linear -2s  infinite' } },
  { id: 18, style: { top: '100%', left: '80%', width: '2px', height: '2px', background: 'rgba(255,255,255,0.4)', animation: 'drift-up 14s linear -9s  infinite' } },
  { id: 19, style: { top: '100%', left: '7%',  width: '2px', height: '2px', background: 'rgba(200,170,255,0.5)', animation: 'drift-up 10s linear -5s  infinite' } },
  // Gently floating medium particles
  { id: 20, style: { top: '40%', left: '48%', width: '3px', height: '3px', background: 'rgba(255,255,255,0.18)', animation: 'float-particle  8s ease-in-out 1.0s infinite' } },
  { id: 21, style: { top: '74%', left: '22%', width: '2px', height: '2px', background: 'rgba(255,255,255,0.15)', animation: 'float-particle  9s ease-in-out 0.0s infinite' } },
  { id: 22, style: { top: '12%', left: '66%', width: '3px', height: '3px', background: 'rgba(200,170,255,0.20)', animation: 'float-particle 11s ease-in-out 2.5s infinite' } },
  { id: 23, style: { top: '60%', left: '96%', width: '2px', height: '2px', background: 'rgba(255,255,255,0.15)', animation: 'float-particle 10s ease-in-out 1.5s infinite' } },
]

// ── Hero: Sparkle dots inside orbit visual ──
const sparkles = [
  { id: 0, style: { top:  '8%', left: '22%', width: '3px', height: '3px', opacity: '0.45', animation: 'twinkle 2.6s ease-in-out 0.2s  infinite alternate' } },
  { id: 1, style: { top: '18%', left: '72%', width: '2px', height: '2px', opacity: '0.35', animation: 'twinkle 3.3s ease-in-out 1.4s  infinite alternate' } },
  { id: 2, style: { top: '78%', left: '15%', width: '3px', height: '3px', opacity: '0.40', animation: 'twinkle 2.9s ease-in-out 0.8s  infinite alternate' } },
  { id: 3, style: { top: '85%', left: '80%', width: '2px', height: '2px', opacity: '0.30', animation: 'twinkle 3.7s ease-in-out 2.1s  infinite alternate' } },
  { id: 4, style: { top: '50%', left: '90%', width: '3px', height: '3px', opacity: '0.38', animation: 'twinkle 2.4s ease-in-out 0.4s  infinite alternate' } },
  { id: 5, style: { top: '92%', left: '48%', width: '2px', height: '2px', opacity: '0.28', animation: 'twinkle 3.0s ease-in-out 1.7s  infinite alternate' } },
  { id: 6, style: { top: '38%', left: '5%',  width: '3px', height: '3px', opacity: '0.42', animation: 'twinkle 2.2s ease-in-out 0.6s  infinite alternate' } },
  { id: 7, style: { top: '62%', left: '58%', width: '2px', height: '2px', opacity: '0.25', animation: 'twinkle 4.0s ease-in-out 1.2s  infinite alternate' } },
]

// ── Category icon map ──
const categoryIconMap = {
  'cake-pastry':  CakeIcon,
  'bread-bakery': ArchiveBoxIcon,
  'savoury':      FireIcon,
  'sweets':       HeartIcon,
  'ramadan-menu': MoonIcon,
}
const getCategoryIcon = (slug) => categoryIconMap[slug] ?? TagIcon

// ── Page data ──
const trustBadges = [
  { text: 'Fresh daily batches',        icon: CheckCircleIcon },
  { text: 'Custom cake orders',         icon: CakeIcon },
  { text: 'Corporate snack packages',   icon: BuildingOfficeIcon },
]

const promises = [
  { icon: SunIcon,          title: 'Fresh Daily Production', desc: 'Every item baked fresh every morning.' },
  { icon: SparklesIcon,     title: 'Quality Ingredients',    desc: 'Only the finest ingredients used.' },
  { icon: CheckCircleIcon,  title: 'Hygienic Preparation',   desc: 'Strict hygiene standards maintained.' },
  { icon: CubeIcon,         title: 'Carefully Packed',       desc: 'Packaging that preserves freshness.' },
]

const corporateFeatures = [
  { icon: BuildingOfficeIcon, title: 'Office Snack Boxes', desc: 'Curated for daily meetings' },
  { icon: CalendarDaysIcon,   title: 'Event Catering',     desc: 'Large-scale event support' },
  { icon: GiftIcon,           title: 'Corporate Gifts',    desc: 'Branded bakery boxes' },
  { icon: DocumentTextIcon,   title: 'Custom Menus',       desc: 'Tailored to your needs' },
]

const reasons = [
  'Fresh bakery items prepared daily',
  'Wide variety of sweet and savoury choices',
  'Easy online ordering experience',
  'Pre-order support for special occasions',
  'Bulk order support for corporate needs',
  'Purpose-driven brand with social impact',
]

const testimonials = [
  { name: 'Sarah Ahmed',  role: 'Regular Customer',       text: "The chocolate cake was absolutely divine! Ordered for my daughter's birthday and everyone was impressed. Will definitely order again." },
  { name: 'Karim Hassan', role: 'Office Manager',         text: 'We order corporate snack boxes every week for our team meetings. The quality is consistently excellent and delivery is always on time.' },
  { name: 'Fatima Khan',  role: 'Home Baker Enthusiast',  text: 'The pastries are simply stunning. The cream eclairs melt in your mouth. Lavender is hands down the best bakery in Dhaka!' },
]

const contactInfo = [
  { icon: MapPinIcon, label: 'Address',       value: 'AJ Height, Uttar Badda, Cha-72, 1/D Progoti Shoroni, Dhaka 1212', href: 'https://maps.google.com', action: 'Directions' },
  { icon: PhoneIcon,  label: 'Phone',         value: '+8801730-728954 · +8801730728905', href: 'tel:+8801730728954', action: 'Call Now' },
  { icon: ClockIcon,  label: 'Opening Hours', value: '9:00 AM – 11:00 PM (Daily)', href: null, action: null },
]
</script>

<style scoped>
@reference "../../css/app.css";

/* ── Hero section solid background ── */
.hero-section-bg {
  background: linear-gradient(135deg,
    #080115  0%,
    #110228  35%,
    #1c0440  65%,
    #0e0222  100%
  );
}

/* ── Food image cards (foreground collage) ── */
.food-img-card {
  position: relative;
  border-radius: 24px;
  overflow: hidden;
  box-shadow:
    0 28px 70px rgba(0, 0, 0, 0.60),
    0 8px 24px  rgba(0, 0, 0, 0.40),
    0 0 0 1px   rgba(255, 255, 255, 0.08);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.food-img-card:hover {
  box-shadow:
    0 36px 80px rgba(0, 0, 0, 0.65),
    0 12px 32px rgba(0, 0, 0, 0.45),
    0 0 0 1px   rgba(255, 255, 255, 0.12);
}
@media (max-width: 767px) {
  .food-img-card,
  .food-img-card:hover { box-shadow: none; }
}

/* Label strip at the bottom of each image card */
.food-img-label {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 28px 12px 10px;
  background: linear-gradient(to top, rgba(0,0,0,0.78) 0%, transparent 100%);
}

/* Empty placeholder card */
.food-img-card--placeholder {
  background: rgba(255,255,255,0.05);
  display: flex;
  align-items: flex-end;
  border: 1px dashed rgba(255,255,255,0.15);
}

/* Hide scrollbar on mobile card strip */
.scrollbar-none { scrollbar-width: none; }
.scrollbar-none::-webkit-scrollbar { display: none; }

/* ── Hero grid lines ── */
.hero-grid {
  background-image:
    linear-gradient(rgba(180, 120, 255, 0.07) 1px, transparent 1px),
    linear-gradient(90deg, rgba(180, 120, 255, 0.07) 1px, transparent 1px);
  background-size: 64px 64px;
}

/* ── Hero Entrance Animations ── */
.hero-fade-up {
  animation: hero-rise 0.75s cubic-bezier(0.22, 1, 0.36, 1) both;
  opacity: 0;
}
@keyframes hero-rise {
  from { opacity: 0; transform: translateY(32px); }
  to   { opacity: 1; transform: translateY(0);    }
}

/* ── CTA Buttons ── */
.btn-hero-main {
  display: inline-flex;
  align-items: center;
  gap: 0.375rem;
  padding: 0.625rem 1.25rem;
  border-radius: 9999px;
  font-weight: 700;
  font-size: 0.8125rem;
  color: #6F2C91;
  background: white;
  box-shadow: 0 8px 32px rgba(0,0,0,0.2), 0 0 0 1px rgba(255,255,255,0.1);
  transition: all 0.3s ease;
  white-space: nowrap;
}
@media (min-width: 640px) {
  .btn-hero-main {
    gap: 0.5rem;
    padding: 0.875rem 1.75rem;
    font-size: 1rem;
  }
}
.btn-hero-main:hover {
  background: #f8f0ff;
  transform: translateY(-2px);
  box-shadow: 0 16px 48px rgba(0,0,0,0.25);
}
.btn-hero-main:active { transform: scale(0.97); }

.btn-hero-ghost {
  display: inline-flex;
  align-items: center;
  gap: 0.375rem;
  padding: 0.625rem 1.25rem;
  border-radius: 9999px;
  font-weight: 700;
  font-size: 0.8125rem;
  color: white;
  background: rgba(255,255,255,0.08);
  border: 2px solid rgba(255,255,255,0.25);
  backdrop-filter: blur(8px);
  transition: all 0.3s ease;
  white-space: nowrap;
}
@media (min-width: 640px) {
  .btn-hero-ghost {
    gap: 0.5rem;
    padding: 0.875rem 1.75rem;
    font-size: 1rem;
  }
}
.btn-hero-ghost:hover {
  background: rgba(255,255,255,0.18);
  border-color: rgba(255,255,255,0.5);
  transform: translateY(-2px);
}
.btn-hero-ghost:active { transform: scale(0.97); }


/* ── Floating App UI Cards ── */
.app-card {
  background: rgba(255, 255, 255, 0.10);
  backdrop-filter: blur(24px);
  -webkit-backdrop-filter: blur(24px);
  border: 1px solid rgba(255, 255, 255, 0.20);
  border-radius: 20px;
  padding: 14px;
  box-shadow:
    0 8px 32px rgba(0, 0, 0, 0.40),
    0 2px 8px rgba(0, 0, 0, 0.25),
    inset 0 1px 0 rgba(255, 255, 255, 0.15);
}

.app-card-sm {
  background: rgba(255, 255, 255, 0.10);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.18);
  border-radius: 16px;
  padding: 10px 14px;
  box-shadow:
    0 4px 20px rgba(0, 0, 0, 0.35),
    inset 0 1px 0 rgba(255, 255, 255, 0.12);
}

.app-card-pill {
  background: rgba(255, 255, 255, 0.12);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.20);
  border-radius: 9999px;
  padding: 10px 16px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35);
}

/* Float wrapper — handles vertical bob; inner div handles rotation independently */
.float-wrap {
  animation: float-bob var(--float-dur, 4s) ease-in-out var(--float-delay, 0s) infinite;
}
@keyframes float-bob {
  0%, 100% { transform: translateY(0px);   }
  50%       { transform: translateY(-12px); }
}

/* ── Particle animations ── */
@keyframes twinkle {
  from { opacity: 0.1;  transform: scale(0.8); }
  to   { opacity: 0.7;  transform: scale(1.3); }
}

/* Glowing orb pulse */
@keyframes glow-pulse {
  0%   { opacity: 0.15; transform: scale(0.8); }
  50%  { opacity: 0.85; transform: scale(1.6); }
  100% { opacity: 0.20; transform: scale(0.9); }
}

/* Drift upward from bottom to top, then reset */
@keyframes drift-up {
  0%   { transform: translateY(0)      translateX(0);    opacity: 0;    }
  6%   { opacity: 0.45; }
  88%  { opacity: 0.30; }
  100% { transform: translateY(-110vh) translateX(25px); opacity: 0;    }
}

/* Gentle floating movement */
@keyframes float-particle {
  0%   { transform: translateY(0px)   translateX(0px);  opacity: 0.15; }
  25%  { transform: translateY(-20px) translateX(12px); opacity: 0.35; }
  50%  { transform: translateY(-32px) translateX(-6px); opacity: 0.20; }
  75%  { transform: translateY(-14px) translateX(-16px);opacity: 0.30; }
  100% { transform: translateY(0px)   translateX(0px);  opacity: 0.15; }
}
</style>
