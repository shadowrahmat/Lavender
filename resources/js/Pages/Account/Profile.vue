<template>
  <AppLayout>
    <Head title="My Profile" />
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="flex items-center justify-between mb-8">
        <h1 class="font-display text-3xl font-bold text-charcoal">My Profile</h1>
        <Link :href="route('account.dashboard')" class="text-primary text-sm hover:underline">← Dashboard</Link>
      </div>

      <!-- Profile Form -->
      <div class="card p-8 mb-6">
        <h2 class="font-display font-semibold text-charcoal text-xl mb-5">Personal Information</h2>
        <form @submit.prevent="updateProfile" class="space-y-4">
          <div>
            <label class="text-sm font-medium text-charcoal mb-1.5 block">Full Name</label>
            <input v-model="profileForm.name" type="text" class="input-field" required>
            <p v-if="profileErrors.name" class="text-error text-xs mt-1">{{ profileErrors.name }}</p>
          </div>
          <div>
            <label class="text-sm font-medium text-charcoal mb-1.5 block">Email Address</label>
            <input v-model="profileForm.email" type="email" class="input-field" required>
            <p v-if="profileErrors.email" class="text-error text-xs mt-1">{{ profileErrors.email }}</p>
          </div>
          <div>
            <label class="text-sm font-medium text-charcoal mb-1.5 block">Phone Number</label>
            <input v-model="profileForm.phone" type="tel" class="input-field">
          </div>
          <button type="submit" :disabled="updatingProfile" class="btn-primary">
            {{ updatingProfile ? 'Saving...' : 'Save Changes' }}
          </button>
        </form>
      </div>

      <!-- Password Form -->
      <div class="card p-8">
        <h2 class="font-display font-semibold text-charcoal text-xl mb-5">Change Password</h2>
        <form @submit.prevent="updatePassword" class="space-y-4">
          <div>
            <label class="text-sm font-medium text-charcoal mb-1.5 block">Current Password</label>
            <input v-model="passwordForm.current_password" type="password" class="input-field" required>
            <p v-if="passwordErrors.current_password" class="text-error text-xs mt-1">{{ passwordErrors.current_password }}</p>
          </div>
          <div>
            <label class="text-sm font-medium text-charcoal mb-1.5 block">New Password</label>
            <input v-model="passwordForm.password" type="password" class="input-field" required>
            <p v-if="passwordErrors.password" class="text-error text-xs mt-1">{{ passwordErrors.password }}</p>
          </div>
          <div>
            <label class="text-sm font-medium text-charcoal mb-1.5 block">Confirm New Password</label>
            <input v-model="passwordForm.password_confirmation" type="password" class="input-field" required>
          </div>
          <button type="submit" :disabled="updatingPassword" class="btn-primary">
            {{ updatingPassword ? 'Updating...' : 'Change Password' }}
          </button>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { useToast } from 'vue-toastification'

const props = defineProps({ user: Object })
const toast = useToast()

const profileForm = ref({ name: props.user.name, email: props.user.email, phone: props.user.phone })
const profileErrors = ref({})
const updatingProfile = ref(false)

const passwordForm = ref({ current_password: '', password: '', password_confirmation: '' })
const passwordErrors = ref({})
const updatingPassword = ref(false)

const updateProfile = () => {
  updatingProfile.value = true
  profileErrors.value = {}
  router.post(route('account.profile.update'), profileForm.value, {
    onSuccess: () => { updatingProfile.value = false; toast.success('Profile updated!') },
    onError: (errs) => { profileErrors.value = errs; updatingProfile.value = false },
  })
}

const updatePassword = () => {
  updatingPassword.value = true
  passwordErrors.value = {}
  router.post(route('account.password'), passwordForm.value, {
    onSuccess: () => {
      updatingPassword.value = false
      passwordForm.value = { current_password: '', password: '', password_confirmation: '' }
      toast.success('Password changed!')
    },
    onError: (errs) => { passwordErrors.value = errs; updatingPassword.value = false },
  })
}
</script>
