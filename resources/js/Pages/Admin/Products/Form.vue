<template>
  <AdminLayout>
    <Head :title="product ? 'Edit Product' : 'Add Product'" />

    <div class="flex items-center justify-between mb-6">
      <h1 class="font-display text-2xl font-bold text-charcoal">{{ product ? 'Edit Product' : 'Add New Product' }}</h1>
      <Link :href="route('admin.products.index')" class="text-primary text-sm hover:underline">← Back to Products</Link>
    </div>

    <form @submit.prevent="submit" enctype="multipart/form-data">
      <div class="grid lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-5">
          <div class="bg-white rounded-2xl shadow-sm border border-purple-50 p-6">
            <h3 class="font-display font-semibold text-charcoal text-lg mb-4">Product Information</h3>
            <div class="space-y-4">
              <div>
                <label class="text-sm font-medium text-charcoal mb-1.5 block">Product Name *</label>
                <input v-model="form.name" type="text" class="input-field" required>
                <p v-if="errors.name" class="text-error text-xs mt-1">{{ errors.name }}</p>
              </div>
              <div>
                <label class="text-sm font-medium text-charcoal mb-1.5 block">Category *</label>
                <select v-model="form.category_id" class="input-field" required>
                  <option value="">Select Category</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
                <p v-if="errors.category_id" class="text-error text-xs mt-1">{{ errors.category_id }}</p>
              </div>
              <div>
                <label class="text-sm font-medium text-charcoal mb-1.5 block">Short Description</label>
                <textarea v-model="form.short_description" rows="2" class="input-field" placeholder="Brief product description"></textarea>
              </div>
              <div>
                <label class="text-sm font-medium text-charcoal mb-1.5 block">Full Description</label>
                <textarea v-model="form.description" rows="5" class="input-field" placeholder="Detailed product description"></textarea>
              </div>
              <div>
                <label class="text-sm font-medium text-charcoal mb-1.5 block">Ingredients</label>
                <textarea v-model="form.ingredients" rows="3" class="input-field" placeholder="List of ingredients"></textarea>
              </div>
            </div>
          </div>

          <!-- Pricing -->
          <div class="bg-white rounded-2xl shadow-sm border border-purple-50 p-6">
            <h3 class="font-display font-semibold text-charcoal text-lg mb-4">Pricing & Inventory</h3>
            <div class="grid md:grid-cols-2 gap-4">
              <div>
                <label class="text-sm font-medium text-charcoal mb-1.5 block">Regular Price (৳) *</label>
                <input v-model="form.price" type="number" step="0.01" class="input-field" required>
                <p v-if="errors.price" class="text-error text-xs mt-1">{{ errors.price }}</p>
              </div>
              <div>
                <label class="text-sm font-medium text-charcoal mb-1.5 block">Sale Price (৳)</label>
                <input v-model="form.discount_price" type="number" step="0.01" class="input-field" placeholder="Optional">
              </div>
              <div>
                <label class="text-sm font-medium text-charcoal mb-1.5 block">Stock Quantity *</label>
                <input v-model="form.stock" type="number" class="input-field" required>
                <p v-if="errors.stock" class="text-error text-xs mt-1">{{ errors.stock }}</p>
              </div>
              <div>
                <label class="text-sm font-medium text-charcoal mb-1.5 block">Weight</label>
                <input v-model="form.weight" type="text" class="input-field" placeholder="e.g. 200g, 1kg">
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-5">
          <!-- Image -->
          <div class="bg-white rounded-2xl shadow-sm border border-purple-50 p-6">
            <h3 class="font-display font-semibold text-charcoal text-lg mb-4">Product Image</h3>
            <div v-if="product?.featured_image_url" class="mb-4">
              <img :src="product.featured_image_url" alt="Current image" class="w-full aspect-square object-cover rounded-xl">
              <p class="text-xs text-muted mt-2 text-center">Current image</p>
            </div>
            <div class="border-2 border-dashed border-purple-200 rounded-xl p-6 text-center cursor-pointer hover:border-primary transition-colors"
              @click="$refs.imageInput.click()">
              <div class="text-4xl mb-2">📷</div>
              <p class="text-sm text-muted">Click to upload image</p>
              <p class="text-xs text-muted mt-1">PNG, JPG up to 2MB</p>
            </div>
            <input ref="imageInput" type="file" accept="image/*" class="hidden" @change="handleImageChange">
            <p v-if="imagePreview" class="text-xs text-success mt-2">New image selected ✓</p>
          </div>

          <!-- Settings -->
          <div class="bg-white rounded-2xl shadow-sm border border-purple-50 p-6">
            <h3 class="font-display font-semibold text-charcoal text-lg mb-4">Settings</h3>
            <div class="space-y-3">
              <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl hover:bg-purple-50 transition-colors">
                <input v-model="form.is_active" type="checkbox" class="rounded text-primary">
                <div>
                  <p class="font-medium text-charcoal text-sm">Active</p>
                  <p class="text-muted text-xs">Visible to customers</p>
                </div>
              </label>
              <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl hover:bg-purple-50 transition-colors">
                <input v-model="form.is_featured" type="checkbox" class="rounded text-primary">
                <div>
                  <p class="font-medium text-charcoal text-sm">Featured</p>
                  <p class="text-muted text-xs">Show in featured products section</p>
                </div>
              </label>
              <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl hover:bg-purple-50 transition-colors border border-purple-100">
                <input v-model="form.show_on_hero" type="checkbox" class="rounded text-primary">
                <div>
                  <p class="font-medium text-charcoal text-sm">Show on Hero Section</p>
                  <p class="text-muted text-xs">Display in homepage hero banner (max 3 products — latest 3 selected will show)</p>
                </div>
              </label>
            </div>
          </div>

          <!-- Submit -->
          <button type="submit" :disabled="processing" class="btn-primary w-full py-4 text-base">
            <span v-if="processing">{{ product ? 'Saving...' : 'Creating...' }}</span>
            <span v-else>{{ product ? 'Save Changes' : 'Create Product' }}</span>
          </button>
        </div>
      </div>
    </form>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  product: Object,
  categories: Array,
})

const errors = ref({})
const processing = ref(false)
const imagePreview = ref(null)

const form = ref({
  category_id: props.product?.category_id || '',
  name: props.product?.name || '',
  short_description: props.product?.short_description || '',
  description: props.product?.description || '',
  ingredients: props.product?.ingredients || '',
  price: props.product?.price || '',
  discount_price: props.product?.discount_price || '',
  weight: props.product?.weight || '',
  stock: props.product?.stock || 0,
  is_active:     props.product?.is_active ?? true,
  is_featured:   props.product?.is_featured ?? false,
  show_on_hero:  props.product?.show_on_hero ?? false,
  featured_image: null,
})

const handleImageChange = (e) => {
  const file = e.target.files[0]
  if (file) {
    form.value.featured_image = file
    imagePreview.value = URL.createObjectURL(file)
  }
}

const submit = () => {
  processing.value = true
  errors.value = {}

  const formData = new FormData()
  Object.entries(form.value).forEach(([key, val]) => {
    if (val !== null && val !== undefined) {
      formData.append(key, val === true ? 1 : val === false ? 0 : val)
    }
  })

  const url = props.product
    ? route('admin.products.update', props.product.id)
    : route('admin.products.store')

  if (props.product) formData.append('_method', 'PUT')

  router.post(url, formData, {
    forceFormData: true,
    onError: (errs) => { errors.value = errs; processing.value = false },
    onSuccess: () => { processing.value = false },
  })
}
</script>
