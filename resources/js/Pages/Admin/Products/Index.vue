<template>
  <AdminLayout>
    <Head title="Products" />

    <div class="flex items-center justify-between mb-6">
      <h1 class="font-display text-2xl font-bold text-charcoal">Products</h1>
      <Link :href="route('admin.products.create')" class="btn-primary text-sm px-5 py-2.5">
        + Add Product
      </Link>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-purple-50 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-purple-50/50">
            <tr>
              <th class="text-left px-5 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Product</th>
              <th class="text-left px-5 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Category</th>
              <th class="text-left px-5 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Price</th>
              <th class="text-left px-5 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Stock</th>
              <th class="text-left px-5 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Status</th>
              <th class="text-right px-5 py-4 text-xs font-semibold text-muted uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-purple-50">
            <tr v-for="product in products.data" :key="product.id"
              class="hover:bg-purple-50/20 transition-colors">
              <td class="px-5 py-4">
                <div class="flex items-center gap-3">
                  <img :src="product.featured_image_url" :alt="product.name"
                    class="w-12 h-12 object-cover rounded-xl bg-purple-50">
                  <div>
                    <p class="font-medium text-charcoal text-sm">{{ product.name }}</p>
                    <p class="text-xs text-muted">{{ product.slug }}</p>
                  </div>
                </div>
              </td>
              <td class="px-5 py-4 text-sm text-muted">{{ product.category?.name }}</td>
              <td class="px-5 py-4">
                <p class="font-semibold text-charcoal text-sm">৳{{ Number(product.price).toFixed(2) }}</p>
                <p v-if="product.discount_price" class="text-xs text-success">৳{{ Number(product.discount_price).toFixed(2) }}</p>
              </td>
              <td class="px-5 py-4">
                <span :class="product.stock > 10 ? 'badge-success' : product.stock > 0 ? 'badge-warning' : 'badge-error'"
                  class="badge text-xs">{{ product.stock }}</span>
              </td>
              <td class="px-5 py-4">
                <span :class="product.is_active ? 'badge-success' : 'badge-error'" class="badge text-xs">
                  {{ product.is_active ? 'Active' : 'Inactive' }}
                </span>
                <span v-if="product.is_featured" class="badge badge-purple text-xs ml-1">Featured</span>
              </td>
              <td class="px-5 py-4">
                <div class="flex items-center justify-end gap-2">
                  <button @click="openEdit(product)"
                    class="text-xs text-primary px-3 py-1.5 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                    Edit
                  </button>
                  <button @click="deleteProduct(product)"
                    class="text-xs text-error px-3 py-1.5 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                    Delete
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="!products.data?.length">
              <td colspan="6" class="px-5 py-12 text-center text-muted text-sm">
                No products found.
                <Link :href="route('admin.products.create')" class="text-primary hover:underline">Add your first product</Link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="products.last_page > 1" class="flex justify-center gap-2 p-5 border-t border-purple-50">
        <template v-for="link in products.links" :key="link.label">
          <Link v-if="link.url" :href="link.url"
            :class="link.active ? 'bg-primary text-white' : 'bg-white text-charcoal hover:bg-purple-50'"
            class="px-4 py-2 rounded-xl text-sm font-medium border border-purple-100 transition-colors"
            v-html="link.label">
          </Link>
        </template>
      </div>
    </div>

    <!-- ── Edit Product Modal ── -->
    <Teleport to="body">
      <Transition name="modal">
        <div v-if="showEdit" class="fixed inset-0 z-50 flex">

          <!-- Backdrop -->
          <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeEdit"></div>

          <!-- Drawer panel -->
          <div class="relative ml-auto w-full max-w-2xl h-full bg-white shadow-2xl flex flex-col overflow-hidden">

            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-purple-50 shrink-0">
              <div>
                <h2 class="font-semibold text-charcoal text-lg">Edit Product</h2>
                <p class="text-muted text-xs mt-0.5 truncate max-w-xs">{{ editForm.name }}</p>
              </div>
              <button @click="closeEdit" class="w-9 h-9 rounded-full hover:bg-purple-50 flex items-center justify-center transition-colors text-muted hover:text-charcoal">
                <XMarkIcon class="w-5 h-5" />
              </button>
            </div>

            <!-- Scrollable body -->
            <div class="flex-1 overflow-y-auto p-6 space-y-5">

              <!-- Current Image -->
              <div v-if="editForm.featured_image_url || imagePreview" class="flex items-center gap-4 p-4 bg-purple-50 rounded-2xl">
                <img :src="imagePreview || editForm.featured_image_url" alt="Product" class="w-20 h-20 object-cover rounded-xl shrink-0">
                <div>
                  <p class="font-medium text-charcoal text-sm">Product Image</p>
                  <button type="button" @click="$refs.imgInput.click()" class="text-primary text-xs hover:underline mt-1">Change image</button>
                </div>
              </div>
              <input ref="imgInput" type="file" accept="image/*" class="hidden" @change="handleImg">

              <!-- Name + Category -->
              <div class="grid md:grid-cols-2 gap-4">
                <div>
                  <label class="text-sm font-medium text-charcoal mb-1.5 block">Product Name *</label>
                  <input v-model="editForm.name" type="text" class="input-field" required>
                  <p v-if="editErrors.name" class="text-error text-xs mt-1">{{ editErrors.name }}</p>
                </div>
                <div>
                  <label class="text-sm font-medium text-charcoal mb-1.5 block">Category *</label>
                  <select v-model="editForm.category_id" class="input-field" required>
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                  </select>
                </div>
              </div>

              <!-- Short Description -->
              <div>
                <label class="text-sm font-medium text-charcoal mb-1.5 block">Short Description</label>
                <textarea v-model="editForm.short_description" rows="2" class="input-field" placeholder="Brief description"></textarea>
              </div>

              <!-- Description -->
              <div>
                <label class="text-sm font-medium text-charcoal mb-1.5 block">Full Description</label>
                <textarea v-model="editForm.description" rows="4" class="input-field" placeholder="Detailed description"></textarea>
              </div>

              <!-- Ingredients -->
              <div>
                <label class="text-sm font-medium text-charcoal mb-1.5 block">Ingredients</label>
                <textarea v-model="editForm.ingredients" rows="2" class="input-field" placeholder="Ingredients list"></textarea>
              </div>

              <!-- Pricing + Inventory -->
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                  <label class="text-sm font-medium text-charcoal mb-1.5 block">Price (৳) *</label>
                  <input v-model="editForm.price" type="number" step="0.01" class="input-field" required>
                  <p v-if="editErrors.price" class="text-error text-xs mt-1">{{ editErrors.price }}</p>
                </div>
                <div>
                  <label class="text-sm font-medium text-charcoal mb-1.5 block">Sale Price (৳)</label>
                  <input v-model="editForm.discount_price" type="number" step="0.01" class="input-field" placeholder="Optional">
                </div>
                <div>
                  <label class="text-sm font-medium text-charcoal mb-1.5 block">Stock *</label>
                  <input v-model="editForm.stock" type="number" class="input-field" required>
                  <p v-if="editErrors.stock" class="text-error text-xs mt-1">{{ editErrors.stock }}</p>
                </div>
                <div>
                  <label class="text-sm font-medium text-charcoal mb-1.5 block">Weight</label>
                  <input v-model="editForm.weight" type="text" class="input-field" placeholder="e.g. 200g">
                </div>
              </div>

              <!-- Toggles -->
              <div class="flex gap-6">
                <label class="flex items-center gap-2.5 cursor-pointer">
                  <input v-model="editForm.is_active" type="checkbox" class="rounded text-primary w-4 h-4">
                  <span class="text-sm font-medium text-charcoal">Active</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer">
                  <input v-model="editForm.is_featured" type="checkbox" class="rounded text-primary w-4 h-4">
                  <span class="text-sm font-medium text-charcoal">Featured</span>
                </label>
              </div>

            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-purple-50 flex gap-3 shrink-0 bg-white">
              <button @click="closeEdit" class="btn-secondary flex-1 py-2.5 text-sm">Cancel</button>
              <button @click="submitEdit" :disabled="editProcessing" class="btn-primary flex-1 py-2.5 text-sm">
                {{ editProcessing ? 'Saving…' : 'Save Changes' }}
              </button>
            </div>

          </div>
        </div>
      </Transition>
    </Teleport>

  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useToast } from 'vue-toastification'
import { XMarkIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  products: Object,
  categories: Array,
})

const toast = useToast()

// ── Delete ──
const deleteProduct = (product) => {
  if (!confirm(`Delete "${product.name}"? This cannot be undone.`)) return
  router.delete(route('admin.products.destroy', product.id), {
    onSuccess: () => toast.success('Product deleted!'),
  })
}

// ── Edit Modal ──
const showEdit      = ref(false)
const editProcessing = ref(false)
const editErrors    = ref({})
const imagePreview  = ref(null)
const imgInput      = ref(null)

const editForm = ref({
  id: null, name: '', category_id: '', short_description: '',
  description: '', ingredients: '', price: '', discount_price: '',
  weight: '', stock: 0, is_active: true, is_featured: false,
  featured_image_url: '', featured_image: null,
})

const openEdit = (product) => {
  editErrors.value  = {}
  imagePreview.value = null
  editForm.value = {
    id:                product.id,
    name:              product.name,
    category_id:       product.category_id,
    short_description: product.short_description || '',
    description:       product.description || '',
    ingredients:       product.ingredients || '',
    price:             product.price,
    discount_price:    product.discount_price || '',
    weight:            product.weight || '',
    stock:             product.stock,
    is_active:         product.is_active,
    is_featured:       product.is_featured,
    featured_image_url: product.featured_image_url,
    featured_image:    null,
  }
  showEdit.value = true
  document.body.style.overflow = 'hidden'
}

const closeEdit = () => {
  showEdit.value = false
  document.body.style.overflow = ''
}

const handleImg = (e) => {
  const file = e.target.files[0]
  if (file) {
    editForm.value.featured_image = file
    imagePreview.value = URL.createObjectURL(file)
  }
}

const submitEdit = () => {
  editProcessing.value = true
  editErrors.value = {}

  const fd = new FormData()
  fd.append('_method', 'PUT')
  fd.append('name',              editForm.value.name)
  fd.append('category_id',       editForm.value.category_id)
  fd.append('short_description', editForm.value.short_description || '')
  fd.append('description',       editForm.value.description || '')
  fd.append('ingredients',       editForm.value.ingredients || '')
  fd.append('price',             editForm.value.price)
  fd.append('discount_price',    editForm.value.discount_price || '')
  fd.append('weight',            editForm.value.weight || '')
  fd.append('stock',             editForm.value.stock)
  fd.append('is_active',         editForm.value.is_active ? 1 : 0)
  fd.append('is_featured',       editForm.value.is_featured ? 1 : 0)
  if (editForm.value.featured_image) {
    fd.append('featured_image', editForm.value.featured_image)
  }

  router.post(route('admin.products.update', editForm.value.id), fd, {
    forceFormData: true,
    onSuccess: () => {
      toast.success('Product updated!')
      closeEdit()
      editProcessing.value = false
    },
    onError: (errs) => {
      editErrors.value = errs
      editProcessing.value = false
    },
  })
}
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active { transition: opacity 0.25s ease; }
.modal-enter-from,
.modal-leave-to    { opacity: 0; }

.modal-enter-active .relative,
.modal-leave-active .relative { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
.modal-enter-from .relative    { transform: translateX(100%); }
.modal-leave-to .relative      { transform: translateX(100%); }
</style>
