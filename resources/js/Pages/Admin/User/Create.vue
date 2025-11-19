<template>
  <div class="min-h-screen bg-gray-100">
    <Head title="Tambah Pengguna - Admin" />
    
    <nav class="bg-white shadow-sm sticky top-0 z-50">
      <div class="px-8 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
          <div class="h-10 w-10 bg-green-500 rounded flex items-center justify-center">
            <span class="text-white font-bold text-xs">TP</span>
          </div>
          <span class="text-2xl font-bold text-green-600">Terminal Pintar</span>
        </div>
        
        <div class="flex items-center gap-8">
          <Link href="/dashboardadmin" class="text-gray-600 hover:text-green-600 text-sm font-medium">Dashboard</Link>
          <Link href="/admin/user" class="text-green-600 text-sm font-bold border-b-2 border-green-600">Kelola Pengguna</Link>
        </div>

        <div class="flex items-center gap-4">
          <div class="flex items-center gap-2">
            <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
            </svg>
            <div>
              <p class="text-sm font-medium text-gray-800">{{ user?.nama || 'Admin User' }}</p>
              <p class="text-xs text-gray-500">Administrator</p>
            </div>
          </div>
          <Link href="/logout" method="post" as="button" class="text-sm text-red-600 hover:text-red-700 font-medium">
            Logout
          </Link>
        </div>
      </div>
    </nav>

    <main class="p-8 max-w-4xl mx-auto">
      <div class="mb-8">
        <Link href="/admin/user" class="text-green-600 hover:text-green-700 text-sm font-medium mb-4 inline-block">
          ← Kembali ke Daftar Pengguna
        </Link>
        <h1 class="text-4xl font-bold text-green-600 mb-2">Tambah Pengguna Baru</h1>
        <p class="text-gray-600 text-sm">Isi form di bawah untuk menambahkan pengguna baru ke sistem.</p>
      </div>

      <div class="bg-white rounded-lg shadow-md p-8">
        <form @submit.prevent="handleSubmit">
          
          <!-- Nama -->
          <div class="mb-6">
            <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
              Nama Lengkap <span class="text-red-500">*</span>
            </label>
            <input 
              v-model="form.nama"
              id="nama" 
              type="text" 
              required
              placeholder="Masukkan nama lengkap"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              :class="{ 'border-red-500': form.errors.nama }"
            >
            <p v-if="form.errors.nama" class="mt-1 text-sm text-red-600">{{ form.errors.nama }}</p>
          </div>

          <!-- Email -->
          <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
              Email <span class="text-red-500">*</span>
            </label>
            <input 
              v-model="form.email"
              id="email" 
              type="email" 
              required
              placeholder="Masukkan email"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              :class="{ 'border-red-500': form.errors.email }"
            >
            <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
          </div>

          <!-- Password -->
          <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
              Password <span class="text-red-500">*</span>
            </label>
            <input 
              v-model="form.password"
              id="password" 
              type="password" 
              required
              placeholder="Masukkan password (minimal 8 karakter)"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              :class="{ 'border-red-500': form.errors.password }"
            >
            <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">{{ form.errors.password }}</p>
            <p class="mt-1 text-xs text-gray-500">Password minimal 8 karakter</p>
          </div>

          <!-- Role -->
          <div class="mb-6">
            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
              Role <span class="text-red-500">*</span>
            </label>
            <select 
              v-model="form.role"
              id="role" 
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              :class="{ 'border-red-500': form.errors.role }"
            >
              <option value="">Pilih Role</option>
              <option v-for="roleItem in roles" :key="roleItem.id" :value="roleItem.name">
                {{ roleItem.name }}
              </option>
            </select>
            <p v-if="form.errors.role" class="mt-1 text-sm text-red-600">{{ form.errors.role }}</p>
            <p class="mt-1 text-xs text-gray-500">Pilih role untuk menentukan hak akses pengguna</p>
          </div>

          <!-- Error Global -->
          <div v-if="form.errors.error" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-sm text-red-600">{{ form.errors.error }}</p>
          </div>

          <!-- Buttons -->
          <div class="flex justify-end gap-4">
            <Link 
              href="/admin/user" 
              class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium"
            >
              Batal
            </Link>
            <button 
              type="submit" 
              :disabled="form.processing"
              class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium disabled:opacity-50"
            >
              {{ form.processing ? 'Menyimpan...' : 'Simpan Pengguna' }}
            </button>
          </div>
        </form>
      </div>
    </main>
  </div>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
  user: Object,
  roles: Array,
});

const form = useForm({
  nama: '',
  email: '',
  password: '',
  role: '',
});

const handleSubmit = () => {
  form.post('/admin/user', {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
    },
  });
};
</script>
