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
                  <Link :href="route('admin.products.edit', product.id)"
                    class="text-xs text-primary hover:underline px-3 py-1.5 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                    Edit
                  </Link>
                  <button @click="deleteProduct(product)"
                    class="text-xs text-error hover:underline px-3 py-1.5 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                    Delete
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="!products.data?.length">
              <td colspan="6" class="px-5 py-12 text-center text-muted text-sm">
                No products found. <Link :href="route('admin.products.create')" class="text-primary hover:underline">Add your first product</Link>
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
  </AdminLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useToast } from 'vue-toastification'

const props = defineProps({ products: Object })
const toast = useToast()

const deleteProduct = (product) => {
  if (!confirm(`Delete "${product.name}"? This cannot be undone.`)) return
  router.delete(route('admin.products.destroy', product.id), {
    onSuccess: () => toast.success('Product deleted!'),
  })
}
</script>
