<template>
  <div class="min-h-screen bg-gray-100">
    <Head title="Edit Siswa - Admin" />
    
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
          <Link href="/admin/siswa" class="text-green-600 text-sm font-bold border-b-2 border-green-600">Kelola Siswa</Link>
          <Link href="/admin/berita" class="text-gray-600 hover:text-green-600 text-sm font-medium">Berita & Dokumentasi</Link>
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
        <Link href="/admin/siswa" class="text-green-600 hover:text-green-700 text-sm font-medium mb-4 inline-block">
          ← Kembali ke Daftar Siswa
        </Link>
        <h1 class="text-4xl font-bold text-green-600 mb-2">Edit Data Siswa</h1>
        <p class="text-gray-600 text-sm">Ubah data siswa dan informasi orang tua di bawah ini.</p>
      </div>

      <div class="bg-white rounded-lg shadow-md p-8">
        <form @submit.prevent="handleSubmit">
          
          <!-- Nama Lengkap Siswa -->
          <div class="mb-6">
            <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
              Nama Lengkap Siswa <span class="text-red-500">*</span>
            </label>
            <input 
              v-model="form.nama_lengkap"
              id="nama_lengkap" 
              type="text" 
              required
              placeholder="Masukkan nama lengkap siswa"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              :class="{ 'border-red-500': form.errors.nama_lengkap }"
            >
            <p v-if="form.errors.nama_lengkap" class="mt-1 text-sm text-red-600">{{ form.errors.nama_lengkap }}</p>
          </div>

          <!-- Status -->
          <div class="mb-6">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
              Status <span class="text-red-500">*</span>
            </label>
            <select 
              v-model="form.status"
              id="status" 
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              :class="{ 'border-red-500': form.errors.status }"
            >
              <option value="Aktif">Aktif</option>
              <option value="Nonaktif">Nonaktif</option>
            </select>
            <p v-if="form.errors.status" class="mt-1 text-sm text-red-600">{{ form.errors.status }}</p>
          </div>

          <div class="border-t pt-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Data Orang Tua</h3>
          </div>

          <!-- Nama Orang Tua -->
          <div class="mb-6">
            <label for="nama_orang_tua" class="block text-sm font-medium text-gray-700 mb-2">
              Nama Orang Tua <span class="text-red-500">*</span>
            </label>
            <input 
              v-model="form.nama_orang_tua"
              id="nama_orang_tua" 
              type="text" 
              required
              placeholder="Masukkan nama orang tua"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              :class="{ 'border-red-500': form.errors.nama_orang_tua }"
            >
            <p v-if="form.errors.nama_orang_tua" class="mt-1 text-sm text-red-600">{{ form.errors.nama_orang_tua }}</p>
          </div>

          <!-- Email Orang Tua -->
          <div class="mb-6">
            <label for="email_orang_tua" class="block text-sm font-medium text-gray-700 mb-2">
              Email Orang Tua <span class="text-red-500">*</span>
            </label>
            <input 
              v-model="form.email_orang_tua"
              id="email_orang_tua" 
              type="email" 
              required
              placeholder="Masukkan email orang tua"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              :class="{ 'border-red-500': form.errors.email_orang_tua }"
            >
            <p v-if="form.errors.email_orang_tua" class="mt-1 text-sm text-red-600">{{ form.errors.email_orang_tua }}</p>
          </div>

          <!-- Error Global -->
          <div v-if="form.errors.error" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-sm text-red-600">{{ form.errors.error }}</p>
          </div>

          <!-- Buttons -->
          <div class="flex justify-end gap-4">
            <Link 
              href="/admin/siswa" 
              class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium"
            >
              Batal
            </Link>
            <button 
              type="submit" 
              :disabled="form.processing"
              class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium disabled:opacity-50"
            >
              {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
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
  siswa: Object,
});

// Initialize form dengan data siswa yang ada
const form = useForm({
  nama_lengkap: props.siswa.nama_lengkap,
  status: props.siswa.status,
  nama_orang_tua: props.siswa.orang_tua?.nama || '',
  email_orang_tua: props.siswa.orang_tua?.email || '',
});

const handleSubmit = () => {
  // Menggunakan POST sesuai permintaan user (bukan PUT/PATCH)
  form.post(`/admin/siswa/${props.siswa.id}/update`, {
    preserveScroll: true,
    onSuccess: () => {
      // Form akan di-reset otomatis dan redirect oleh controller
    },
  });
};
</script>
