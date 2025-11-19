<template>
  <div class="min-h-screen bg-gray-100">
    <Head title="Tambah Buku - Admin" />
    
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
          <Link href="/admin/user" class="text-gray-600 hover:text-green-600 text-sm font-medium">Kelola Pengguna</Link>
          <Link href="/admin/siswa" class="text-gray-600 hover:text-green-600 text-sm font-medium">Kelola Siswa</Link>
          <Link href="/admin/berita" class="text-gray-600 hover:text-green-600 text-sm font-medium">Berita & Dokumentasi</Link>
          <Link href="/admin/perpustakaan" class="text-green-600 text-sm font-bold border-b-2 border-green-600">Perpustakaan</Link>
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

    <main class="p-8 max-w-3xl mx-auto">
      <div class="mb-6">
        <Link href="/admin/perpustakaan" class="text-green-600 hover:text-green-700 flex items-center gap-2 mb-4">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Kembali ke Daftar Buku
        </Link>
        <h1 class="text-3xl font-bold text-green-600">Tambah Buku Baru</h1>
        <p class="text-gray-600 text-sm mt-2">Isi formulir di bawah untuk menambahkan buku baru ke perpustakaan</p>
      </div>

      <div class="bg-white rounded-lg shadow-md p-8">
        <form @submit.prevent="submitForm">
          <div class="space-y-6">
            <!-- Judul Buku -->
            <div>
              <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                Judul Buku <span class="text-red-500">*</span>
              </label>
              <input
                id="judul"
                v-model="form.judul"
                type="text"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                :class="{ 'border-red-500': form.errors.judul }"
                placeholder="Masukkan judul buku"
              />
              <p v-if="form.errors.judul" class="mt-1 text-sm text-red-600">{{ form.errors.judul }}</p>
            </div>

            <!-- Kategori -->
            <div>
              <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                Kategori
              </label>
              <input
                id="kategori"
                v-model="form.kategori"
                type="text"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                :class="{ 'border-red-500': form.errors.kategori }"
                placeholder="Contoh: Fiksi, Non-Fiksi, Pelajaran, dll"
              />
              <p v-if="form.errors.kategori" class="mt-1 text-sm text-red-600">{{ form.errors.kategori }}</p>
              <p class="mt-1 text-sm text-gray-500">Opsional - Kosongkan jika tidak ada kategori</p>
            </div>

            <!-- Status -->
            <div>
              <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                Status <span class="text-red-500">*</span>
              </label>
              <select
                id="status"
                v-model="form.status"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                :class="{ 'border-red-500': form.errors.status }"
              >
                <option value="">Pilih Status</option>
                <option value="Tersedia">Tersedia</option>
                <option value="Dipinjam">Dipinjam</option>
                <option value="Hilang">Hilang</option>
              </select>
              <p v-if="form.errors.status" class="mt-1 text-sm text-red-600">{{ form.errors.status }}</p>
            </div>
          </div>

          <!-- Buttons -->
          <div class="mt-8 flex gap-4">
            <button
              type="submit"
              :disabled="form.processing"
              class="flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="form.processing">Menyimpan...</span>
              <span v-else>Simpan Buku</span>
            </button>
            <Link
              href="/admin/perpustakaan"
              class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-medium text-center"
            >
              Batal
            </Link>
          </div>
        </form>
      </div>
    </main>
  </div>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
  user: Object
});

const form = useForm({
  judul: '',
  kategori: '',
  status: 'Tersedia'
});

const submitForm = () => {
  form.post('/admin/perpustakaan', {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
    }
  });
};
</script>
