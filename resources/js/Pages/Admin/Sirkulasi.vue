<template>
  <div class="min-h-screen bg-gray-100">
    <Head title="Sirkulasi Perpustakaan - Admin" />
    
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

    <main class="p-8">
      <div class="mb-8 flex justify-between items-center">
        <div>
          <Link href="/admin/perpustakaan" class="text-green-600 hover:text-green-700 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar Buku
          </Link>
          <h1 class="text-4xl font-bold text-green-600 mb-2">Sirkulasi Perpustakaan</h1>
          <p class="text-gray-600 text-sm">Kelola peminjaman dan pengembalian buku</p>
        </div>
      </div>

      <!-- Success Message -->
      <div v-if="$page.props.flash?.success" class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ $page.props.flash.success }}
      </div>

      <!-- Error Message -->
      <div v-if="$page.props.flash?.error || $page.props.errors?.error" class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        {{ $page.props.flash?.error || $page.props.errors?.error }}
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Form Peminjaman -->
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex items-center gap-3 mb-6">
            <div class="text-3xl">📤</div>
            <div>
              <h2 class="text-2xl font-bold text-gray-800">Peminjaman Buku</h2>
              <p class="text-sm text-gray-600">Catat peminjaman buku baru</p>
            </div>
          </div>

          <form @submit.prevent="submitPeminjaman">
            <div class="space-y-4">
              <div>
                <label for="buku_pinjam" class="block text-sm font-medium text-gray-700 mb-2">
                  Pilih Buku <span class="text-red-500">*</span>
                </label>
                <select
                  id="buku_pinjam"
                  v-model="formPinjam.buku_id"
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  :class="{ 'border-red-500': formPinjam.errors.buku_id }"
                >
                  <option value="">-- Pilih Buku --</option>
                  <option v-for="buku in bukuTersedia" :key="buku.id" :value="buku.id">
                    {{ buku.judul }} {{ buku.kategori ? `(${buku.kategori})` : '' }}
                  </option>
                </select>
                <p v-if="formPinjam.errors.buku_id" class="mt-1 text-sm text-red-600">{{ formPinjam.errors.buku_id }}</p>
                <p v-if="!bukuTersedia || bukuTersedia.length === 0" class="mt-1 text-sm text-yellow-600">
                  Tidak ada buku yang tersedia untuk dipinjam
                </p>
              </div>

              <div>
                <label for="peminjam" class="block text-sm font-medium text-gray-700 mb-2">
                  Peminjam <span class="text-red-500">*</span>
                </label>
                <select
                  id="peminjam"
                  v-model="formPinjam.peminjam_id"
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  :class="{ 'border-red-500': formPinjam.errors.peminjam_id }"
                >
                  <option value="">-- Pilih Peminjam --</option>
                  <option v-for="usr in users" :key="usr.id" :value="usr.id">
                    {{ usr.nama }} ({{ usr.email }})
                  </option>
                </select>
                <p v-if="formPinjam.errors.peminjam_id" class="mt-1 text-sm text-red-600">{{ formPinjam.errors.peminjam_id }}</p>
              </div>

              <div>
                <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700 mb-2">
                  Tanggal Pinjam <span class="text-red-500">*</span>
                </label>
                <input
                  id="tanggal_pinjam"
                  v-model="formPinjam.tanggal_pinjam"
                  type="date"
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                  :class="{ 'border-red-500': formPinjam.errors.tanggal_pinjam }"
                />
                <p v-if="formPinjam.errors.tanggal_pinjam" class="mt-1 text-sm text-red-600">{{ formPinjam.errors.tanggal_pinjam }}</p>
              </div>
            </div>

            <button
              type="submit"
              :disabled="formPinjam.processing || !bukuTersedia || bukuTersedia.length === 0"
              class="mt-6 w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="formPinjam.processing">Memproses...</span>
              <span v-else>Catat Peminjaman</span>
            </button>
          </form>
        </div>

        <!-- Form Pengembalian -->
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex items-center gap-3 mb-6">
            <div class="text-3xl">📥</div>
            <div>
              <h2 class="text-2xl font-bold text-gray-800">Pengembalian Buku</h2>
              <p class="text-sm text-gray-600">Catat pengembalian buku yang dipinjam</p>
            </div>
          </div>

          <form @submit.prevent="submitPengembalian">
            <div class="space-y-4">
              <div>
                <label for="buku_kembali" class="block text-sm font-medium text-gray-700 mb-2">
                  Pilih Buku yang Dikembalikan <span class="text-red-500">*</span>
                </label>
                <select
                  id="buku_kembali"
                  v-model="formKembali.peminjaman_id"
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  :class="{ 'border-red-500': formKembali.errors.peminjaman_id }"
                >
                  <option value="">-- Pilih Buku --</option>
                  <option 
                    v-for="buku in bukuDipinjam" 
                    :key="buku.id" 
                    :value="buku.peminjaman?.[0]?.id"
                  >
                    {{ buku.judul }} - Peminjam: {{ buku.peminjaman?.[0]?.peminjam?.nama || 'N/A' }}
                  </option>
                </select>
                <p v-if="formKembali.errors.peminjaman_id" class="mt-1 text-sm text-red-600">{{ formKembali.errors.peminjaman_id }}</p>
                <p v-if="!bukuDipinjam || bukuDipinjam.length === 0" class="mt-1 text-sm text-yellow-600">
                  Tidak ada buku yang sedang dipinjam
                </p>
              </div>

              <div>
                <label for="tanggal_kembali" class="block text-sm font-medium text-gray-700 mb-2">
                  Tanggal Kembali <span class="text-red-500">*</span>
                </label>
                <input
                  id="tanggal_kembali"
                  v-model="formKembali.tanggal_kembali"
                  type="date"
                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  :class="{ 'border-red-500': formKembali.errors.tanggal_kembali }"
                />
                <p v-if="formKembali.errors.tanggal_kembali" class="mt-1 text-sm text-red-600">{{ formKembali.errors.tanggal_kembali }}</p>
              </div>
            </div>

            <button
              type="submit"
              :disabled="formKembali.processing || !bukuDipinjam || bukuDipinjam.length === 0"
              class="mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="formKembali.processing">Memproses...</span>
              <span v-else>Catat Pengembalian</span>
            </button>
          </form>
        </div>
      </div>

      <!-- Summary Section -->
      <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Ringkasan</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div class="bg-green-50 p-4 rounded-lg">
            <p class="text-sm text-gray-600">Buku Tersedia</p>
            <p class="text-2xl font-bold text-green-600">{{ bukuTersedia?.length || 0 }}</p>
          </div>
          <div class="bg-blue-50 p-4 rounded-lg">
            <p class="text-sm text-gray-600">Sedang Dipinjam</p>
            <p class="text-2xl font-bold text-blue-600">{{ bukuDipinjam?.length || 0 }}</p>
          </div>
          <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-600">Total User</p>
            <p class="text-2xl font-bold text-gray-600">{{ users?.length || 0 }}</p>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  user: Object,
  bukuTersedia: Array,
  bukuDipinjam: Array,
  users: Array,
});

// Form for Peminjaman
const formPinjam = useForm({
  buku_id: '',
  peminjam_id: '',
  tanggal_pinjam: new Date().toISOString().split('T')[0], // Today's date
});

// Form for Pengembalian
const formKembali = useForm({
  peminjaman_id: '',
  tanggal_kembali: new Date().toISOString().split('T')[0], // Today's date
});

const submitPeminjaman = () => {
  formPinjam.post('/admin/perpustakaan/pinjam', {
    preserveScroll: true,
    onSuccess: () => {
      formPinjam.reset();
      formPinjam.tanggal_pinjam = new Date().toISOString().split('T')[0];
    }
  });
};

const submitPengembalian = () => {
  formKembali.post('/admin/perpustakaan/kembalikan', {
    preserveScroll: true,
    onSuccess: () => {
      formKembali.reset();
      formKembali.tanggal_kembali = new Date().toISOString().split('T')[0];
    }
  });
};
</script>
